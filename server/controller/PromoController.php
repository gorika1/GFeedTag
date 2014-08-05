<?php

use Gear\Controller\ControllerAJAX;

class PromoController extends ControllerAJAX 
{

	public function __construct()
	{
		if( !isset( $_SESSION[ 'user'] ) )
		{
			header( 'Location: login' );
		}
		else
		{
			$drawing = new PromoDrawing();
			$drawing->drawPage( 'Promociones | FeedTag' );
		} // end if...else
	} // end __construct

} // end PromoController

$page = new PromoController();