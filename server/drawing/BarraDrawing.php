<?php

use Gear\Draw\Drawing;

class BarraDrawing extends Drawing
{
	private $myBarra;

	public function __construct()
	{
		parent::__construct();
		$this->myBarra = new Barra();
	}

	public function drawPedidos( $isAJAX = false )
	{
		$this->setList( 'pedidos' );

		$orders = $this->myBarra->getPedidos();

		foreach( $orders as $order )
		{
			$this->list[] = array(
				'Identificator' => $order[ 'identificator' ],
				'Table' => $order[ 'Pedidos_nroMesa' ],
				'Amount' => $order[ 'cantidad' ],
				'Drink' => $order[ 'drinkName' ],
				'Description' => '',
				'Hour' => $order[ 'hora' ],
			);
		}

		if( $isAJAX )
			return $this->list;
		else
			$this->draw( 'Pedidos' );
	}
}