<?php

use Gear\Draw\Drawing;

class CocinaDrawing extends Drawing
{
	private $myCocina;

	public function __construct()
	{
		parent::__construct();
		$this->myCocina = new Cocina();
	}

	public function drawPedidos( $isAJAX = false )
	{
		$this->setList( 'pedidos' );

		$orders = $this->myCocina->getPedidos();

		foreach ( $orders as $key => $value ) 
			$aux[ $key ] = $value[ 'hora' ];

		if( isset( $aux ) )
			array_multisort( $aux, SORT_ASC, $orders );

		foreach( $orders as $order )
		{
			$this->list[] = array(
				'Identificator' => $order[ 'identificator' ],
				'Table' => $order[ 'Pedidos_nroMesa' ],
				'Amount' => $order[ 'cantidad' ],
				'Food' => $order[ 'foodName' ],
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