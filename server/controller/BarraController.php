<?php

require_once 'server/drawing/BarraDrawing.php';
require_once 'server/model/BarraModel.php';

use Gear\Controller\ControllerAJAX;

class BarraController extends ControllerAJAX
{
	public function __construct()
	{
		if( isset( $_POST[ 'update' ] ) )
		{
			$obj =  new BarraDrawing();
			//Si el update es solo para obtener los datos (en la primera carga) para luego comparar
			if( isset( $_POST[ 'load'] ) && $_POST[ 'load' ] === 'true' )
				$this->callDraw( $obj, $_POST[ 'update' ], array( true ) );
			else // Si se hace para ver si existen nuevos pedidos
				if( is_file( 'server/barra.dat' ) )
				{
					$this->callDraw( $obj, $_POST[ 'update' ], array( true ) );
					if( isset( $_POST[ 'load' ] ) )
						unlink( 'server/barra.dat' );
				}						
				else
					echo json_encode( array() ); //return a void json
		}
		else if( isset( $_POST[ 'delete' ] ) )
		{
			$obj = new Barra();
			$obj->closePedido( json_decode( $_POST[ 'data' ], true ) );
		}
		else
		{
			$page = new BarraDrawing();
			$page->drawPage( 'Barra - Honolulu', array( 'Pedidos()' ) );
		}
	}
}

$obj = new BarraController();