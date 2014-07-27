<?php

use Gear\Controller\ControllerAJAX;

class ConfigController extends ControllerAJAX 
{
	public function __construct()
	{
		$drawing = new ConfigDrawing();
		$drawing->drawPage( 'FeedTag Dashboard');
	}
} // end ConfigController

$page = new ConfigController();