<?php

use Gear\Controller\ControllerAJAX;

class GalleryController extends ControllerAJAX
{
	public function __construct()
	{
		$drawing = new GalleryDrawing();
		$drawing->drawPage( 'Galeria | FeedTag');
	} // end __construct
} // end GalleryController

$page = new GalleryController();