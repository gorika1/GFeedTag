<?php

namespace Gear\Controller;

class ControllerAJAX
{	
	private $method;
	
	//Recibe un arreglo en donde se pasan los argumentos de la funcion que retorna los datos actualizados
	//El orden de los valores debe ser igual al orden de los argumentos
	public function callDraw( &$objDrawing, $method, $parameters = array() )
	{
		//Elimina los punto y coma (;) para evitar ataques
		$method = str_replace( ';', '', $method );

		$returns = array();//almacena los valores devueltos en el update de datos

		if( isset( $parameters ) )
		{
			$argumentos = '';
			//Itera a traves de parametros para generar el string para pasarlos
			for( $i = 0; $i < sizeof( $parameters ); $i++ )
			{
				if( $i + 1 < sizeof( $parameters ) ) //Si el indice actual no es el ultimo
					$argumentos = $argumentos . "'" . $parameters[ $i ] . "'" . ', '; //genera el string de los valores a pasar
				else
				{
					$argumentos = $argumentos . "'" . $parameters[ $i ] . "'";
				} // end if..else
					
			} // end for
			$argumentos = $argumentos . ', '. 1;

			eval( "\$returns = \$objDrawing->draw". $method . "( " . $argumentos . ");" );
		}
		else
			$value = eval( "\$returns = \$objDrawing->draw".$method.'();' );

		echo json_encode( $returns );
	}//end callDraw

}//end ControllerAJAX