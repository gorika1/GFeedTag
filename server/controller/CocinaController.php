<?php

require_once 'server/drawing/CocinaDrawing.php';
require_once 'server/model/CocinaModel.php';

use Gear\Controller\ControllerAJAX;

class CocinaController extends ControllerAJAX
{
	public function __construct()
	{
		if( isset( $_POST[ 'update' ] ) )
		{
			$obj =  new CocinaDrawing();
			//Si el update es solo para obtener los datos (en la primera carga) para luego comparar
			if( isset($_POST[ 'load' ]) && $_POST[ 'load' ] === 'true' )
				$this->callDraw( $obj, $_POST[ 'update' ], array( true ) );
			else // Si se hace para ver si existen nuevos pedidos
				if( is_file( 'server/cocina.dat' ) )
				{
					$this->callDraw( $obj, $_POST[ 'update' ], array( true ) );
					// Si no existe el indice load quiere decir que la peticion viene desde el visor del admin
					if( isset( $_POST['load'] ) ) 
						unlink( 'server/cocina.dat' );
				}						
				else
					echo json_encode( array() ); //return a void json
		}
		else if( isset( $_POST[ 'delete' ] ) )
		{
			$obj = new Cocina();
			$obj->closePedido( json_decode( $_POST[ 'data' ], true ) );
		}
		else
		{
			$page = new CocinaDrawing();
			$page->drawPage( 'Cocina - Honolulu', array( 'Pedidos()' ) );
		}
	}
}

$obj = new CocinaController();