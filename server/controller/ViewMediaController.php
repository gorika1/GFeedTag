<?php

use Gear\Controller\ControllerAJAX;
use Gear\SocialApis\Instagram;
use Gear\SocialApis\Twitter;
require_once 'server/model/MediaModel.php';

class ViewMediaController extends ControllerAJAX {

	private $drawing;
	private $twitter;
	private $myMedia;
	private $lastTime; // guarda el time del ultimo media obtenido desde la base de datos
	private $tags; // tags configurados en la base de datos

	public function __construct()
	{
		header("Access-Control-Allow-Origin: *");
		$this->drawing = new ViewMediaDrawing();
		$this->myMedia = new MediaModel();
		$this->tags = $this->myMedia->getTags();
		$this->callFunctions();
	} // end __construct

//**************************************************************************
//************************* PRIVATE METHODS ********************************
//**************************************************************************

	private function callFunctions()
	{
		if( isset( $_POST[ 'get' ] )  )
		{
			// Si es para obtener las imagenes
			if( $_POST[ 'get' ] == 'Photos' )
			{
				// Si es en la primera llamada para obtener las imagenes
				if( $_POST[ 'lastTime' ] == 1 )
				{
					$myMedia = new MediaModel();
					if( sizeof( $myMedia->getTags() ) )
					{
						$this->getTwitterMedia();
						$this->getInstagramMedia( true );

						$initialDate = $this->myMedia->getInitialDate();

						$this->callDraw( $this->drawing, 'Photos', 
									array( $initialDate, $_POST[ 'lastPos' ], true ) );
					}
					else
					{
						// Si no hay un hashtag configurado
						// emite un codigo para avisar del problema
						echo json_encode( array( 'error' => 1 ) );
					} // end if...else interno					
					
				}
				else 
				{
					$this->getTwitterMedia();
					$this->getInstagramMedia();

					$this->callDraw( $this->drawing, 'Photos', 
								array( $_POST[ 'lastTime' ], $_POST[ 'lastPos' ], true ) );
				} // end if...else
				
			} // end if $_POST[ 'get' ]

			// Si es para obtener el registro de las imagenes borradas en el admin
			else if ( $_POST [ 'get' ] == 'PhotosDeleted' )
			{
				// Comprueba que exista el registro
				// de imagenes eliminadas y lo retorna 
				if( is_file( 'server/'. $_SESSION[ 'idUser' ] . '_deleted.dat' ) )
				{
					$file = fopen( 'server/'. $_SESSION[ 'idUser' ] . '_deleted.dat', 'r' );
					$data = array();

					while ( !feof( $file ) )  
					{
						$data[] = str_replace( "\n", '', fgets( $file ) );
					}

					fclose( $file );
					unlink( 'server/'. $_SESSION[ 'idUser' ] . '_deleted.dat' );
					echo json_encode( $data );
				} // end if
				
			} // end if
			
		}
		else
		{
			$this->firstLoad();
		} // end if...else
	} // end callFunctions

	private function firstLoad()
	{
		$this->drawing->drawPage( 'Visor FeedTag', array(),
									array( 'LastTime' => $this->myMedia->getLastTime( 1 ) ) );
	} // end firsLoad

	private function getTwitterMedia()
	{
		$settings = $this->myMedia->getTwitterSettings();

		//Recurso del API que queremos consultar
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$tags = $this->getTwitterTagQuery();
		$getfield = '?q=' . $tags . '+exclude:retweets&count=90&since_id=' . $this->myMedia->getNextTweetId();
		$requestMethod = 'GET';
		$twitter = new Twitter( $settings[ 'tw_access_token'],
								$settings[ 'tw_access_token_secret'],
								$settings[ 'tw_consumer_key'],
								$settings[ 'tw_consumer_secret' ] );

		$tweets = json_decode( $twitter->setGetfield($getfield)
	        ->buildOauth($url, $requestMethod)
	        ->performRequest(), true );
		//print_r( $tweets );
		// Guarda el payload en la base de datos
		$this->myMedia->saveTweets( $tweets );

	} // end getTwitterMedia

	//Genera el string correcto para realizar la peticion multitag
	private function getTwitterTagQuery()
	{
		$query = '';

		$size = sizeof( $this->tags );

		for( $i = 0; $i < $size; $i++ )
		{
			// Si no es el ultimo hashtag a agregar al query
			if( $i < $size - 1 )
				$query .= '#' . $this->tags[ $i ][ 'hashtag' ] . '+OR+';
			else
				$query .= '#' . $this->tags[ $i ][ 'hashtag' ];
		} // end for

		return $query;
	} // end getTwitterTagQuery

	// Itera a traves de los distintos tags y realiza una peticion por tag
	// debido a que Instagram no permite realizar peticiones multitag
	private function getInstagramMedia( $firstTime = false )
	{
		// Cantidad de hashtags
		$size = sizeof( $this->tags );

		for( $i = 0; $i < $size; $i++ )
		{
			// Tag a Buscar
			$tag = $this->tags[ $i ][ 'hashtag' ];

			$idHashtag = $this->myMedia->getHashTagByName( $tag );

			$next_insta_id = $this->myMedia->getNextInstaId( $idHashtag );


			$url = sprintf( 'https://api.instagram.com/v1/tags/%s/media/recent?access_token=%s&count=30&min_tag_id=%s',
							$tag, 
							$this->myMedia->getInstagramToken(),
							$next_insta_id
						);

			// Si es la primera llamada
			if( $firstTime || ( ! $this->myMedia->existRegisters( $idHashtag ) ) )
			{
				$gotAllResults = false;
			    $photos = array();
			    $count = 0;

			    // Solo se permiten en total 4 peticiones a Instagram
			    // es decir, un maximo de 120 fotos
			    while( !$gotAllResults && $count < 4 ) 
			    {
			        $result = json_decode( file_get_contents( $url ), true );
			        $photos[] = $result;

			        if (!isset($result['pagination']['next_url'] )) 
			        {
			            $gotAllResults = true;
			        } 
			        else 
			        {
			            $url = $result['pagination']['next_url'];
			        } // end if...else

			        $count += 1;
			    } // end while

				//Guarda el payload en la base de datos
				$this->myMedia->saveInstagramPhotos( $photos, $tag, $idHashtag );

				continue;
			} // end if



			$photos[] = json_decode( file_get_contents( $url ), true );
		
			//Guarda el payload en la base de datos
			$this->myMedia->saveInstagramPhotos( $photos, $tag, $idHashtag );

		} // end for

		// Guarda el next_id para obtener las fotos en la siguiente peticion
		//$this->myMedia->setNextInstaId();

	} // end getInstagramMedia

} // end ViewMediaController

//****************************************************************************

if( !isset( $_SESSION[ 'user'] ) )
{
	header( 'Location: login' );
}
else
{
	$page = new ViewMediaController();		
} // end if...else




