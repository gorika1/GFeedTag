<?php

use Gear\Controller\ControllerAJAX;
use Gear\Db\GMySqli;
use Gear\SocialApis\Twitter;

class DashBoardController extends ControllerAJAX 
{

	public function __construct()
	{
		if( !isset( $_SESSION[ 'user'] ) )
		{
			header( 'Location: login' );
		}
		else
		{
			$drawing = new DashBoardDrawing();
			$drawing->drawPage( 'Administrar FeedTag' );
		} // end if...else
	} // end __construct

} // end AdminController

$page = new DashBoardController();