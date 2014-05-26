<?php
use Gear\Draw\Drawing;
class AdminDrawing extends Drawing
{
	public function drawGeneralInfo( $ajax = false )
	{
		$myPedidos = new Pedidos();
		$myCocina = new Cocina();
		$myBarra = new Barra();

		if( !$ajax )
		{
			$this->principalList[ 'Amount Mesas' ] = $myPedidos->getOpenTables() . ( $myPedidos->getOpenTables() == 1 ? ' mesa abierta' : ' mesas abiertas' );
			$this->principalList[ 'Amount Cocina' ] = $myCocina->getAmountOrders() . ( $myCocina->getAmountOrders() == 1 ? ' pedido' : ' pedidos' );
			$this->principalList[ 'Amount Barra' ] = $myBarra->getAmountOrders() . ( $myBarra->getAmountOrders() == 1 ?  ' pedido' : ' pedidos' );
		}
		else
		{
			$datos['AmountMesas'] = $myPedidos->getOpenTables();
			$datos[ 'AmountCocina' ] = $myCocina->getAmountOrders();
			$datos[ 'AmountBarra' ] = $myBarra->getAmountOrders();
			return $datos;
		}
				 
	}
} // end AdminDrawing

?>