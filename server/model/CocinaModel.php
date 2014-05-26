<?php

use Gear\Db\GMySqli;

class Cocina
{
	private $orders;
	private $pizzas;

	public function getPedidos()
	{
		$this->orders = GMySqli::getRegisters( 'PedidosMenus', '*', 'cantidad > entregado', 'hora' );

		for( $i = 0; $i < sizeof( $this->orders ); $i++ )
		{
			$this->orders[ $i ][ 'foodName' ] = $this->getFoodById( $this->orders[ $i ][ 'Menus_idMenu' ], 1 );
			$this->orders[ $i ][ 'identificator' ] = $this->orders[ $i ][ 'Pedidos_nroMesa' ] . $this->orders[ $i ][ 'Menus_idMenu' ] . 1;
			$this->orders[ $i ][ 'cantidad' ] = $this->orders[ $i ][ 'cantidad' ] - $this->orders[ $i ][ 'entregado' ];
			unset( $this->orders[ $i ][ 'Menus_idMenu' ] );
		}

		$this->getPizzas();

		$this->orders = array_merge( $this->orders, $this->pizzas );

		return $this->orders;
	}//end getPedidos

	//*************************************************************************************************

	public function closePedido( &$data = array() )
	{
		$delivered = $this->getDelivered( $data );

		$newDelivered = $delivered + $data[ 'amount' ];
		
		$this->setDelivered( $newDelivered, $data );

	}

	//*************************************************************************************************

	public function getAmountOrders()
	{
		$amountMenus = GMySqli::getCountRegisters( 'PedidosMenus', '*', 'cantidad > entregado' );
		$amountPizzas = GMySqli::getCountRegisters( 'PedidosPizzas', '*', 'cantidad > entregado' );
		return $amountPizzas + $amountMenus;
	}

	//**************************************************************************************************

	private function getDelivered( &$data )
	{
		if( $data[ 'type' ] == 1 )
			$delivered = GMySqli::getRegister( 'PedidosMenus', 'entregado',
			'Pedidos_nroMesa = ' . $data[ 'mesa' ] . ' AND Menus_idMenu = ' . $data[ 'id' ] );
		else
			$delivered = GMySqli::getRegister( 'PedidosPizzas', 'entregado',
			'Pedidos_nroMesa = ' . $data[ 'mesa' ] . ' AND Pizzas_idPizza = ' . $data[ 'id' ] );

		$delivered = $delivered[ 'entregado' ];

		return $delivered;
	}

	//**************************************************************************************************

	private function setDelivered( &$amount, &$data )
	{
		if( $data[ 'type' ] == 1 )
			GMySqli::updateRegister( 'PedidosMenus', 'entregado = ' . $amount,
			'Pedidos_nroMesa = ' . $data[ 'mesa' ] . ' AND Menus_idMenu = '  . $data[ 'id' ] );
		else
			GMySqli::updateRegister( 'PedidosPizzas', 'entregado = ' . $amount,
			'Pedidos_nroMesa = ' . $data[ 'mesa' ] . ' AND Pizzas_idPizza = ' . $data[ 'id' ] );
		GMySqli::viewQuery();
	}

	//**************************************************************************************************

	private function getPizzas()
	{
		$this->pizzas = GMySqli::getRegisters( 'PedidosPizzas', '*', 'cantidad > entregado', 'hora' );

		for( $i = 0; $i < sizeof( $this->pizzas ); $i++ )
		{
			$this->pizzas[ $i ][ 'foodName' ] = $this->getFoodById( $this->pizzas[ $i ][ 'Pizzas_idPizza' ], 2 );
			$this->pizzas[ $i ][ 'identificator' ] = $this->pizzas[ $i ][ 'Pedidos_nroMesa' ] . $this->pizzas[ $i ][ 'Pizzas_idPizza' ] . 2;
			$this->pizzas[ $i ][ 'cantidad' ] = $this->pizzas[ $i ][ 'cantidad' ] - $this->pizzas[ $i ][ 'entregado' ];
			unset( $this->pizzas[ $i ][ 'Pizzas_idPizza' ] );
		}//end for

	}//end getPizzas


	//***************************************************************************************************

	private function getFoodById( $id, $type )
	{
		if( $type == 1 ) // if it needs the menus
		{
			$name = GMySqli::getRegister( 'Menus', 'nombreMenu', 'idMenu = ' . $id );
			$name = $name[ 'nombreMenu' ];
		}
		else //if it needs the pizzas
		{
			$name = GMySqli::getRegister( 'Pizzas', 'nombrePizza', 'idPizza = ' . $id );
			$name = $name[ 'nombrePizza' ];
		}//end if...else

		return $name;

	}//end getMenuById
}//end Cocina