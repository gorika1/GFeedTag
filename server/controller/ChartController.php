<?php

use Gear\Controller\ControllerAJAX;

class ChartController extends ControllerAJAX
{
	public function __construct()
	{
		$drawing = new ChartDrawing();
		$drawing->drawPage( 'Estadísticas | FeedTag' );
	}
} // end ChartController

$page = new ChartController();