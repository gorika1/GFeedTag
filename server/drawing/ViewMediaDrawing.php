<?php

use Gear\Draw\Drawing;

class ViewMediaDrawing extends Drawing {
	
	private $myMedia;

	public function __construct()
	{
		parent::__construct();
		$this->myMedia = new MediaModel();
	} // end __construct

	/*
	* @lastPos recibe el id de la ultima posicion para sumarlo
	* al indice @i en una llamada con AJAX
	*/
	public function drawPhotos( $lastTime, $lastPos = 0, $isAJAX = false )
	{
		$photos = $this->myMedia->getMedia( $lastTime );
		$size = sizeof( $photos );
		$i = 1;

		for( ; $i <= $size; $i++ ) 
		{
			$this->list[] = array(
				'URL' => $photos[ $i - 1 ][ 'url' ],
				'screenName' => $photos[ $i - 1 ][ 'screen_name' ],
				'text' => $photos[ $i - 1 ][ 'text' ],
				'from' => $photos[ $i - 1 ][ '_from' ],
				'position' => $i + $lastPos
			);
		} // end foreach

		if( $isAJAX )
		{
			$tmp = $this->list;
			unset( $this->list );

			$this->list[ 'media' ] = $tmp;

			if( sizeof( $this->list[ 'media' ] ) > 0 )
			{
				$this->list[ 'lastTime' ] = $this->myMedia->getLastTime();
				$this->list[ 'lastPos' ] = $i + $lastPos - 1;
			} // end if				
			
			return $this->list;
		}	
	} // end drawPhotos
} // end Drawing