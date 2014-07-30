<?php

use Gear\Controller\ControllerAJAX;
require_once 'server/model/AdminModel.php';

class ConfigController extends ControllerAJAX 
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
			$drawing = new ConfigDrawing();

			// Obtiene la cuenta de la cantidad de fotos en instagram y twitter
			$myAdmin = new AdminModel();

			$drawing->drawPage( 'Configurar | FeedTag', array( 'Hashtags()' ) );
		} // end if..else
	} // end __construct
} // end ConfigController

$page = new ConfigController();