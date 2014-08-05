<?php

use Gear\Controller\ControllerAJAX;
require_once 'server/model/AdminModel.php';

class DashBoardController extends ControllerAJAX 
{

	public function __construct()
	{
		// Si no esta logueado
		if( !isset( $_SESSION[ 'user'] ) )
		{
			header( 'Location: ../login' );
		}
		else
		{
			$drawing = new DashBoardDrawing();

			// Obtiene la cuenta de la cantidad de fotos en instagram y twitter
			$myAdmin = new AdminModel();
			$counter = $myAdmin->getRecount();

			$drawing->drawPage( 'DashBoard | FeedTag', null,
								array(
										'CantidadInsta' => $counter[ 'insta' ],
										'CantidadTwitter' => $counter[ 'twitter' ],
									) );
		} // end if...else
	} // end __construct

} // end AdminController

$page = new DashBoardController();