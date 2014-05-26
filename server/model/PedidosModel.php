<?php

use Gear\Db\GMySqli;

class Pedidos
{
	private $datos; // almacena los pedidos que ya se hicieron en la mesa que se selecciono
	private $monto; // total de los pedidos hechos por una mesa
	private $mesa;

	//Guarda el pedido
	public function setPedido( &$datos = array() )
	{
		if( isset( $datos[ 'mesa' ] ) && isset( $datos[ 'data' ] ) )
		{
			$this->mesa = $datos[ 'mesa' ];

			//Decode the ids and types
			$idFoods = json_decode( urldecode( $datos['data'] ), true );

			foreach ( $idFoods as $food ) 
			{
				if( $food[ 'type' ] == 1 ) 
				{
					$this->setPedidoMenu( $food );
					fopen( 'server/cocina.dat', 'a' );
				}
				else if( $food[ 'type' ] == 2 )
				{
					$this->setPedidoPizza( $food );
					if( !is_file( 'server/cocina.dat' ) )
						fopen( 'server/cocina.dat', 'a' );
				}//end if
				else if( $food[ 'type' ] == 3 )
				{
					$this->setPedidoBebida( $food );
						fopen( 'server/barra.dat', 'a' );
				}
			}//end foreach
			
			$this->setMontoPedido();

		}//end if externo
	}//end setPedido

	//**********************************************************************************************


	private function setPedidoMenu( &$food )
	{
		$precio = GMySqli::getRegister( 'Menus', 'precio', 'idMenu = '. $food[ 'id' ] );
		$precio = $precio[ 'precio' ];

		if( GMySqli::existRegister( 'PedidosMenus', 'Pedidos_nroMesa = ' . $this->mesa . ' AND Menus_idMenu= ' . $food['id'] ) )
		{
			$currentAmount = GMySqli::getRegister( 'PedidosMenus' , 'cantidad', 
				'Pedidos_nroMesa=' . $this->mesa . ' AND Menus_idMenu = ' . $food['id'] );

			$currentAmount = $currentAmount[ 'cantidad' ];
			$added = $food[ 'amount' ] - $currentAmount;

			GMySqli::updateRegister( 'PedidosMenus', 'cantidad = ' . ( $currentAmount + $food[ 'amount' ] ) . ', fecha = curdate(), hora = curtime()', 
				'Pedidos_nroMesa = '. $this->mesa . ' AND Menus_idMenu = ' . $food['id'] );
		}				
		else
		{
			GMySqli::setRegister( 'PedidosMenus', 
				'Pedidos_nroMesa, Menus_idMenu, cantidad, fecha, hora', 
				$this->mesa . ', ' . $food[ 'id' ] . ', ' . $food[ 'amount'] . ', curdate(), curtime()' );

		}

		$this->monto = $this->monto + ( $precio * $food[ 'amount' ] );
		
	}


	//*******************************************************************************************************************

	private function setPedidoPizza( &$food )
	{
		$precio = GMySqli::getRegister( 'Pizzas', 'precio', 'idPizza = '. $food [ 'id' ]  );
		$precio = $precio[ 'precio' ];

		if( GMySqli::existRegister( 'PedidosPizzas', 'Pedidos_nroMesa = ' . $this->mesa .  ' AND Pizzas_idPizza= ' . $food['id'] ) )
		{
			$currentAmount = GMySqli::getRegister( 'PedidosPizzas' , 'cantidad', 
				'Pedidos_nroMesa=' . $this->mesa . ' AND Pizzas_idPizza = ' . $food['id'] );

			$currentAmount = $currentAmount[ 'cantidad' ];
			$added = $food[ 'amount' ] - $currentAmount;

			GMySqli::updateRegister( 'PedidosPizzas', 'cantidad = ' . ( $currentAmount + $food[ 'amount' ] ) . ', fecha = curdate(), hora = curtime()', 
				'Pedidos_nroMesa = '. $this->mesa . ' AND Pizzas_idPizza = ' . $food['id'] );
		}
			
		else
		{
			GMySqli::setRegister( 'PedidosPizzas',
				'Pedidos_nroMesa, Pizzas_idPizza, cantidad, fecha, hora', 
				$this->mesa . ', ' . $food[ 'id' ] . ', ' . $food[ 'amount'] . ', curdate(), curtime()' );
		}

		$this->monto = $this->monto + ( $precio * $food[ 'amount' ] );
	}


	//*************************************************************************************************************


	private function setPedidoBebida( &$food )
	{
		$precio = GMySqli::getRegister( 'Bebidas', 'precio', 'idBebida = '. $food [ 'id' ]  );
		$precio = $precio[ 'precio' ];

		if( GMySqli::existRegister( 'PedidosBebidas', 'Pedidos_nroMesa = ' . $this->mesa .  ' AND Bebidas_idBebida= ' . $food['id'] ) )
		{
			$currentAmount = GMySqli::getRegister( 'PedidosBebidas' , 'cantidad', 
				'Pedidos_nroMesa=' . $this->mesa . ' AND Bebidas_idBebida = ' . $food['id'] );

			$currentAmount = $currentAmount[ 'cantidad' ];

			GMySqli::updateRegister( 'PedidosBebidas', 'cantidad = ' . $food[ 'amount' ] . ', fecha = curdate(), hora = curtime()', 
				'Pedidos_nroMesa = '. $this->mesa . ' AND Bebidas_idBebida = ' . $food['id'] );
		}
			
		else
		{
			GMySqli::setRegister( 'PedidosBebidas', 
				'Pedidos_nroMesa, Bebidas_idBebida, cantidad, fecha, hora', 
				$this->mesa . ', ' . $food[ 'id' ] . ', ' . $food[ 'amount'] . ', curdate(), curtime()' );
		}

		$this->monto = $this->monto + ( $precio * $food[ 'amount' ] );

		GMySqli::viewQuery();
	}


	//***************************************************************************************************************

	private function setMontoPedido()
	{
		if( GMySqli::existRegister( "Pedidos", "nroMesa = " . $this->mesa ) ) 
		{
			$montoExis = GMySqli::getRegister( 'Pedidos', 'monto', 'nroMesa = ' . $this->mesa );
			$montoExis = $montoExis[ 'monto' ];
			$this->monto = $this->monto + $montoExis;
			GMySqli::updateRegister( "Pedidos", "monto = " . $this->monto, "nroMesa = " . $this->mesa );
		}
		else
			GMySqli::setRegister( 'Pedidos', 'nroMesa, monto', $this->mesa .', '. $this->monto );
	}

	
	//***********************************************************************************************************

	//Obtiene los pedidos actuales de la mesa elegida
	public function getCartSelect( &$mesaElegida ) 
	{
		$this->getPedidosMenus( $mesaElegida );
		$this->getPedidosPizzas( $mesaElegida );
		$this->getPedidosBebidas( $mesaElegida );

		//Obtiene el monto total del pedido
		$this->datos[ 'monto' ] = GMySqli::getRegister( 'Pedidos', 'monto', 'nroMesa = ' . $mesaElegida );
		$this->datos[ 'monto' ] = $this->datos[ 'monto' ][ 'monto' ];

		return $this->datos;
	}//end getCartSelect


	//***********************************************************************************************

	//Obtiene la cantidad de mesas abiertas actualmente
	public function getOpenTables()
	{
		return GMySqli::getCountRegisters( 'Pedidos', 'nroMesa' );
	}


	//************************************************************************************************

	private function getPedidosMenus( &$mesaElegida ) 
	{
		//Obtiene los pedidos, el precio y la cantidad
		$this->datos['pedidos'] = GMySqli::execQuery(
			"SELECT m.nombreMenu, m.precio, m.idMenu, pm.cantidad
			FROM Menus as m
			INNER JOIN PedidosMenus as pm
			ON m.idMenu = pm.Menus_idMenu
			AND pm.Pedidos_nroMesa = $mesaElegida", true );

		for( $i = 0; $i < sizeof( $this->datos['pedidos'] ); $i++ )
		{
			if( $this->datos['pedidos'][ $i ][ 'cantidad' ] > 1 )
				$this->datos['pedidos'][$i]['precio'] = $this->datos['pedidos'][ $i ][ 'precio' ] * $this->datos['pedidos'][ $i ][ 'cantidad' ];
			$this->datos['pedidos'][ $i ][ 'tipo' ] = 1;
		}
	}//end getPedidosMenus

	//************************************************************************************

	private function getPedidosPizzas( &$mesaElegida )
	{
		$datos['pedidos'] = GMySqli::execQuery(
			"SELECT p.nombrePizza, p.precio, p.idPizza, pp.cantidad
			FROM Pizzas as p
			INNER JOIN PedidosPizzas as pp
			ON p.idPizza = pp.Pizzas_idPizza
			AND pp.Pedidos_nroMesa = $mesaElegida", true );

		for( $i = 0; $i < sizeof( $datos['pedidos'] ); $i++ )
		{
			if( $datos['pedidos'][ $i ][ 'cantidad' ] > 1 )
				$datos['pedidos'][$i]['precio'] = $datos['pedidos'][ $i ][ 'precio' ] * $datos['pedidos'][ $i ][ 'cantidad' ];
			$datos['pedidos'][ $i ][ 'tipo' ] = 2;
		}
			

		$array = array_merge( $this->datos[ 'pedidos' ], $datos['pedidos'] );

		$this->datos[ 'pedidos' ] = $array;
	}//end getPeddidosPizzas

	//*************************************************************************************

	private function getPedidosBebidas( &$mesaElegida )
	{
		$datos['pedidos'] = GMySqli::execQuery(
			"SELECT b.nombreBebida, b.precio, b.idBebida, pb.cantidad
			FROM Bebidas as b
			INNER JOIN PedidosBebidas as pb
			ON b.idBebida = pb.Bebidas_idBebida
			AND pb.Pedidos_nroMesa = $mesaElegida", true );

		for( $i = 0; $i < sizeof( $datos['pedidos'] ); $i++ )
		{
			if( $datos['pedidos'][ $i ][ 'cantidad' ] > 1 )
				$datos['pedidos'][$i]['precio'] = $datos['pedidos'][ $i ][ 'precio' ] * $datos['pedidos'][ $i ][ 'cantidad' ];
			$datos['pedidos'][ $i ][ 'tipo' ] = 3;
		}

		$array = array_merge( $this->datos[ 'pedidos' ], $datos['pedidos'] );

		$this->datos[ 'pedidos' ] = $array;
	}//end getPedidosBebidas
}//end Pedidos