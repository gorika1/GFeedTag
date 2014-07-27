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
												array( 'url', 'text', 'screen_name', '_from' ), 
												'Users_idUser = ' . $_SESSION[ 'idUser' ] . ' AND time > ' . $prevTime,
												'time' );
		return $photos;
	} // end getTwitterMedia

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
				if( strlen( $text ) > 100 )
					$text = substr( $text, 0, 96 ) . '...';

				// tweet time
				$time = strtotime( $tweets[ 'statuses'][ $i ][ 'created_at' ] );

				// tweet owner
				$screen_name = $tweets[ 'statuses' ][ $i ][ 'user' ][ 'screen_name' ];

				// Pasa 1 para indicar que el dato viene desde twitter
				GMySqli::setRegister( 'Media', '*', 
										array( $id, $_SESSION[ 'idUser' ], $mediaUrl, $text, $time, $screen_name, 1  ) );
					
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
			if( strlen( $text ) > 100 )
				$text = substr( $text, 0, 96 ) . '...';

			$time = $photos[ 'data' ][ $i ][ 'caption' ][ 'created_time' ];
			$screen_name = $photos[ 'data' ][ $i ][ 'user' ][ 'username' ];

			// Pasa 2 para indicar que el dato viene desde instagram
			GMySqli::setRegister( 'Media', '*', array( 
									$idMedia, $_SESSION[ 'idUser' ], $url, $text, $time, $screen_name, 2  ) );
		} // end for
	} // end saveInstaPhotos


	public function getNextInstaId()
	{
		$reg = GMySqli::getRegister( 'Users', array( 'next_insta_id' ), 'idUser = ' . $_SESSION[ 'idUser' ] );

		return $reg[ 'next_insta_id' ];		
	} // end getLastTweetId

	public function setNextInstaId()
	{
		//echo $this->next_insta_id;
		if( $this->next_insta_id > 0 )
			GMySqli::updateRegister( 'Users', array(  'next_insta_id' => $this->next_insta_id ), "idUser = " . $_SESSION[ 'idUser' ] );
	} // end setNextInstaId

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

} // end MediaModel