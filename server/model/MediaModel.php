<?php
use Gear\Db\GMySqli;

class MediaModel
{
	private $next_insta_id; // almacena cada valor del siguiente id en instagram para obtener el mayor de todas las peticiones

	public function __construct()
	{
		$this->next_insta_id = 0;
	} // end __construct

	/*
	* @prevTime es el time del ultimo media de la anterior peticion
	*/
	public function getMedia( $prevTime )
	{
		$photos = GMySqli::getRegisters( 'Media', 
												array( 'idMedia', 'url', 'text', 'screen_name', '_from' ), 
												'Users_idUser = ' . $_SESSION[ 'idUser' ] . ' AND time > ' . $prevTime,
												'time DESC'  );
		return $photos;
	} // end getTwitterMedia

	// Obtiene el tiempo de la ultima publicacion
	public function getLastTime( $prevTime = 1 )
	{
		$query = 'SELECT MAX( time ) AS time FROM Media;';
		$lastTime = GMySqli::execQuery( $query, true );
		$lastTime = $lastTime[ 0 ][ 'time' ];
		
		return $lastTime;
	} // end getLastTime

	public function getTags()
	{
		return GMySqli::getRegisters( 'Hashtags', array( 'hashtag' ), 'Users_idUser = ' . $_SESSION[ 'idUser' ] );
	} // end getTags

	/*****************************************************************************
	******************************* TWITTER ************************************
	*****************************************************************************/

	public function getTwitterSettings()
	{
		$settings = GMySqli::getRegister( 'Users', 
			array( 'tw_access_token', 'tw_access_token_secret', 'tw_consumer_key', 'tw_consumer_secret' ),
			'idUser = ' . "'" . $_SESSION[ 'idUser'] . "'" );

		return $settings;
	} // end getTwitterSettings

	public function saveTweets( &$tweets )
	{
		$size = sizeof( $tweets[ 'statuses' ] );

		// Guarda el next_id para obtener los tweets en la siguiente peticion
		if( $size > 0 )
		{
			$this->setNextTweetId( $tweets[ 'statuses' ][ 0 ][ 'id_str' ] );
		} // end if

		for( $i = 0; $i < $size; $i++ )
		{
			// Extrae los datos del tweet solo si posee una imagen
			if( isset( $tweets[ 'statuses' ][ $i ][ 'entities' ][ 'media' ] ) )
			{
				// tweet id
				$id = $tweets[ 'statuses' ][ $i ][ 'id_str' ];

				// image url
				$mediaUrl = $tweets[ 'statuses' ][ $i ][ 'entities' ][ 'media' ][ 0 ][ 'media_url' ];
				
				// tweet text
				$tweetUrl = $tweets[ 'statuses' ][ $i ][ 'entities' ][ 'media' ][ 0 ][ 'url' ];	

				$text = $tweets[ 'statuses' ][ $i ][ 'text' ];
				$text = str_replace( $tweetUrl, '', $text );
				$text = $this->formatText( $text );
				

				// tweet time
				$time = strtotime( $tweets[ 'statuses'][ $i ][ 'created_at' ] );

				// tweet owner
				$screen_name = $tweets[ 'statuses' ][ $i ][ 'user' ][ 'screen_name' ];

				// Comprueba de que el texto no posea palabras censuradas
				if( $this->checkText( $text ) )
				{
					// Pasa 1 para indicar que el dato viene desde twitter
					GMySqli::setRegister( 'Media', '*', 
										array( $id, $_SESSION[ 'idUser' ], $mediaUrl, $text, $time, $screen_name, 1  ) );
				} // end if

			} // end if
		} // end foreach
	} // end saveTweets


	public function getNextTweetId()
	{		
		$reg = GMySqli::getRegister( 'Users', array( 'next_tw_id' ), 'idUser = ' . $_SESSION[ 'idUser'] );
		return $reg[ 'next_tw_id' ];		
	} // end getNextTweetId


	private function setNextTweetId( $next_id )
	{
		GMySqli::updateRegister( 'Users', array(  'next_tw_id' => $next_id ), "idUser = " . $_SESSION[ 'idUser' ] );
	} // end setNextTweetId


	/*****************************************************************************
	******************************* INSTAGRAM ************************************
	*****************************************************************************/

	public function getInstagramToken()
	{
		$query = 'SELECT it.insta_token
					FROM insta_tokens AS it
					INNER JOIN Users AS u ON it.idToken = u.insta_token
					WHERE idUser = ' . $_SESSION[ 'idUser' ];

		$access_token = GMySqli::execQuery( $query, true );
		return $access_token[ 0 ][ 'insta_token' ];
	} // end getInstagramToken

	// Guarda las publicaciones de Instagram
	public function saveInstagramPhotos( &$photos )
	{
		// Compara el next_insta_id ya que se hace un ciclo para cubrir todos los hashtags configurados
		$this->compareNextInstaId( $photos[ 'pagination' ] );
	
		$size = sizeof( $photos[ 'data' ] );

		for( $i = 0; $i < $size; $i++ )
		{
			$idMedia = $photos[ 'data' ][ $i ][ 'caption' ][ 'id' ];
			$url = $photos[ 'data' ][ $i ][ 'images' ][ 'standard_resolution' ][ 'url' ];

			$text = $photos[ 'data' ][ $i ][ 'caption' ][ 'text' ];
			$text = $this->formatText( $text );


			$time = $photos[ 'data' ][ $i ][ 'caption' ][ 'created_time' ];
			$screen_name = $photos[ 'data' ][ $i ][ 'user' ][ 'username' ];

			// Comprueba de que el texto no posea palabras censuradas
			if( $this->checkText( $text ) )
			{
				// Pasa 2 para indicar que el dato viene desde instagram
				GMySqli::setRegister( 'Media', '*', array( 
										$idMedia, $_SESSION[ 'idUser' ], $url, $text, $time, $screen_name, 2  ) );
			} // end if
			 
		} // end for
	} // end saveInstaPhotos

	// Obtiene el next_id de Instagram
	public function getNextInstaId()
	{
		$reg = GMySqli::getRegister( 'Users', array( 'next_insta_id' ), 'idUser = ' . $_SESSION[ 'idUser' ] );

		return $reg[ 'next_insta_id' ];		
	} // end getLastTweetId

	// Establece el next_id de Instagram
	public function setNextInstaId()
	{
		//echo $this->next_insta_id;
		if( $this->next_insta_id > 0 )
			GMySqli::updateRegister( 'Users', array(  'next_insta_id' => $this->next_insta_id ), "idUser = " . $_SESSION[ 'idUser' ] );
	} // end setNextInstaId

	// Formatea el texto para poder insertarlo en la base de datos
	private function formatText( $text )
	{
		// Escapa los corazones poder insertar en la base de datos
		$text = str_replace( "\\u003c3", "<3", $text );

		// Limita el texto a 100 caractares
		if( strlen( $text ) > 100 )
			$text = substr( $text, 0, 96 ) . '...';

		// Escapa las comillas para poder insertar en la base de datos
		$text = str_replace( "'", "\'", $text );

		// Escapa las etiquetas
		$text = str_replace( "<", "&lt;", $text );

		return $text;
	} // end formatText

	// Comprueba de que el texto no tenga palabras censuradas
	private function checkText( $text )
	{
		$blacklist = $this->getBlackList();
		$bool = true;
		foreach( $blacklist as $word )
		{
			if( stripos( $text, $word[ 'palabra' ] ) )
				$bool = false;
		}

		return $bool;
	}

	// Obtiene el blacklist
	private function getBlackList()
	{
		return GMySqli::getRegisters( 'Blacklist', array( 'palabra' ), 'Users_idUser = ' . $_SESSION[ 'idUser' ] );
	} // end getBlackList

	// Compara los next_id de Instagram
	private function compareNextInstaId( &$pagination )
	{
		if( isset( $pagination[ 'min_tag_id' ] ) )
		{
			if( $pagination[ 'min_tag_id' ] > $this->next_insta_id )
			{
				$this->next_insta_id = $pagination[ 'min_tag_id' ];
				GMySqli::updateRegister( 'Users', array(  'next_insta_id' => $this->next_insta_id ), "idUser = " . $_SESSION[ 'idUser' ] );
			} // end if
		} // end if
	} // end setNextInstaId


	/*
		FOR THE GALLERY IN THE ADMIN
	*/
	public function getGallery()
	{
		$media = GMySqli::getRegisters( 'Media', array( 'idMedia', 'url', 'text', 'screen_name', '_from' ),
											'Users_idUser = ' . $_SESSION[ 'idUser' ],
											'time DESC' );

		return $media;
	} // end getGallery

	public function deleteMedia( $idMedia, $from )
	{
		GMySqli::deleteRegister( 'Media', 'idMedia =' . $idMedia . ' AND _from =' . $from );
	} // end deleteMedia

} // end MediaModel