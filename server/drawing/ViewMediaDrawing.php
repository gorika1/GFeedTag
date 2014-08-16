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

	* Es una funcion que solo se utiliza con llamadas via AJAX
	*/
	public function drawPhotos( $lastTime, $lastPos = 0, $isAJAX = false )
	{
		// Obtiene las fotos utilizando como referencia la fecha del ultimo
		$photos = $this->myMedia->getMedia( $lastTime );
		$size = sizeof( $photos );
		// Indice que indica la posicion del registro analizado
		$i = 1;


		for( ; $i <= $size; $i++ ) 
		{			
			$this->list[] = array(
				'URL' => $photos[ $i - 1 ][ 'url' ],
				'screenName' => $photos[ $i - 1 ][ 'screen_name' ],
				'text' => $photos[ $i - 1 ][ 'text' ],
				'from' => $photos[ $i - 1 ][ '_from' ],
				'position' => $i + $lastPos // Realiza la suma del indice y lasPos para obtener la posicion en el DOM
			);
		} // end for

		$tmp = $this->list;
		unset( $this->list );

		$this->list[ 'media' ] = $tmp;

		if( sizeof( $this->list[ 'media' ] ) > 0 )
		{
			$this->list[ 'lastTime' ] = $this->myMedia->getLastTime();
			$this->list[ 'lastPos' ] = $i + $lastPos - 1;
		} // end if				
		
		return $this->list;
	} // end drawPhotos
} // end Drawing