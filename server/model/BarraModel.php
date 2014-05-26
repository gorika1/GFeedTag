<?php
use Gear\Db\GMySqli;

class Barra
{
	private $orders;
	private $pizzas;

	public function getPedidos()
	{
		$this->orders = GMySqli::getRegisters( 'PedidosBebidas', '*', 'cantidad > entregado', 'hora' );

		for( $i = 0; $i < sizeof( $this->orders ); $i++ )
		{
			$this->orders[ $i ][ 'drinkName' ] = $this->getDrinkById( $this->orders[ $i ][ 'Bebidas_idBebida' ] );
			$this->orders[ $i ][ 'identificator' ] = $this->orders[ $i ][ 'Pedidos_nroMesa' ] . $this->orders[ $i ][ 'Bebidas_idBebida' ];
			$this->orders[ $i ][ 'cantidad' ] = $this->orders[ $i ][ 'cantidad' ] - $this->orders[ $i ][ 'entregado' ];
			unset( $this->orders[ $i ][ 'Bebidas_idBebida' ] );
		}

		return $this->orders;
	}//end getPedidos

	//*************************************************************************************************

	public function closePedido( &$data = array() )
	{
		$delivered = $this->getDelivered( $data );

		$newDelivered = $delivered + $data[ 'amount' ];
		
		$this->setDelivered( $newDelivered, $data );

	}

	//**************************************************************************************************

	public function getAmountOrders()
	{
		return GMySqli::getCountRegisters( 'PedidosBebidas', '*', 'cantidad > entregado' );
	}

	//**************************************************************************************************

	private function getDelivered( &$data )
	{
		$delivered = GMySqli::getRegister( 'PedidosBebidas', 'entregado',
		'Pedidos_nroMesa = ' . $data[ 'mesa' ] . ' AND Bebidas_idBebida = ' . $data[ 'id' ] );
		

		$delivered = $delivered[ 'entregado' ];

		return $delivered;
	}

	//**************************************************************************************************

	private function setDelivered( &$amount, &$data )
	{
		GMySqli::updateRegister( 'PedidosBebidas', 'entregado = ' . $amount,
		'Pedidos_nroMesa = ' . $data[ 'mesa' ] . ' AND Bebidas_idBebida = '  . $data[ 'id' ] );
	}


	//***************************************************************************************************

	private function getDrinkById( $id )
	{
		$name = GMySqli::getRegister( 'Bebidas', 'nombreBebida', 'idBebida = ' . $id );
		$name = $name[ 'nombreBebida' ];

		return $name;

	}//end getMenuById
}//end Barra