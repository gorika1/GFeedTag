<?php
	
use Gear\Session\Session;

use Gear\Controller\ControllerAJAX;

if( isset( $_SESSION[ 'userLevel' ] ) && $_SESSION[ 'userLevel' ] == 4 )
{
	require_once 'server/model/PedidosModel.php';
	require_once 'server/model/CocinaModel.php';
	require_once 'server/model/BarraModel.php';
	require_once 'server/drawing/AdminDrawing.php';

	class AdminController extends ControllerAJAX
	{
		public function __construct()
		{
			$obj = new AdminDrawing();

			if( $this->evaluateUpdate( 'POST' ) )
			{
				$this->callDraw( $obj, array( true ) );
			}
			else
			{				
				$obj->drawPage( 'Administrar - Honolulu', array( 'GeneralInfo()' ) );
			}//end if...else
			
		} // end __construct

	} // end AdminController


	$obj = new AdminController();

} //end if

else
{
	global $server;
	header( 'Location: ' . $server );
}