<?php

use Gear\Draw\Drawing;

class GalleryDrawing extends Drawing
{
	private $myMedia;

	public function __construct() 
	{
		parent::__construct();
		$this->myMedia = new MediaModel();

	} // end __construct

	public function drawMedia()
	{
		$media = $this->myMedia->getGallery();

		foreach ( $media as $media ) 
		{
		 	$this->list[] = array(
		 			'IdMedia' => $media[ 'idMedia' ],
		 			'Link' => $this->getLink( $media[ '_from' ] ),
		 			'MediaFrom' => $media[ '_from' ],
		 			'ImageURL' => $media[ 'url' ],
		 			'Texto' => $media[ 'text' ],
		 			'User' => $media[ 'screen_name' ],
		 			'Fuente' => $this->getFont( $media[ '_from' ] )
		 		);
		} // end foreach

		$this->setList( 'imagenes' );
		$this->draw( 'ListaMedia' );
	} // end drawMedia

	private function getLink( $from )
	{
		if( $from == 1 )
		{
			return 'https://twitter.com/';
		}
		else
			return 'https://instagram.com/';
	} // end getLink

	// Obtiene la red social de donde se extrajo la foto
	private function getFont( $identifier )
	{
		if( $identifier == 1 )
			return 'twitter';
		else
			return 'instagram';
	} // end getFont

} // end GalleryDrawing