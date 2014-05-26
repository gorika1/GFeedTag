<?php

use Gear\Db\GMySqli;

class Listar
{
	private $lista;//almacena los elementos de las categorias (pizzas, menus y bebidas)
	

	public function getLista( $id )
	{
		//Si la peticion quiere las pizzas
		if( $id == 6 ) {
			$this->getListaPizzas();
		} else if( $id == 7 ) { //Si la peticion quiere las bebidas
			$this->lista = GMySqli::getRegisters( 'Bebidas', 'idBebida, nombreBebida, precio' );
		} else {
			$this->getListaMenu( $id );
		}//end if else interno

		return $this->lista;
	}//end getLista


	//****************************************************************************************

	public function getListaMenu( $idTipoMenu )
	{			
		$this->lista = GMySqli::getRegisters( 'Menus', 'idMenu, nombreMenu, precio', 
			sprintf( 'TiposMenus_idTipoMenu=%s', 
					$idTipoMenu
				) );

		for( $i = 0; $i < sizeof( $this->lista ); $i++ )
		{				
			$this->lista[$i][ 'ingredientes' ] = $this->getIngredientes( $this->lista[$i][ 'idMenu'], 1 );//almacena los ingredientes en un array
			$this->lista[$i][ 'stringIngredientes' ] = $this->getIngredientesAsString( $this->lista[$i][ 'ingredientes' ] );//almacena los ingredientes en string
		}//end for

	}//end getListaMenu

	//************************************************************************************************

	public function getListaPizzas(){
		$this->lista = GMySqli::getRegisters( 'Pizzas', 'idPizza, nombrePizza, precio' );

		for( $i = 0; $i < sizeof( $this->lista ); $i++ )
		{
			$this->lista[ $i ][ 'ingredientes' ] = $this->getIngredientes( $this->lista[$i]['idPizza'], 2 );
			$this->lista[ $i ][ 'stringIngredientes' ] = $this->getIngredientesAsString( $this->lista[ $i ][ 'ingredientes' ] );
		}//end for
	}

	//*************************************************************************************************

	public function getIngredientes( $id, $tipo )
	{
		//Si se quiere los ingredientes de un Menu
		if( $tipo == 1 ) {
			$ingredientes = GMySqli::execQuery( 
				"SELECT i.nombreIngrediente
				FROM IngredientesMenus as im
				INNER JOIN Menus as m
				ON im.Menus_idMenu = m.idMenu 
				AND m.idMenu = $id
				INNER JOIN Ingredientes as i
				ON im.Ingredientes_idIngrediente = i.idIngrediente ", true );

		} else if( $tipo == 2 ) {//Si se quiere los ingredientes de una pizza
			$ingredientes = GMySqli::execQuery( 
				"SELECT i.nombreIngrediente
				FROM IngredientesPizzas as ip
				INNER JOIN Pizzas as p
				ON ip.Pizzas_idPizza = p.idPizza
				AND p.idPizza = $id
				INNER JOIN Ingredientes as i
				ON ip.Ingredientes_idIngrediente = i.idIngrediente ", true );
		}

		return $ingredientes;
	}//end getIngredientes


	//********************************************************************************

	public function getIngredientesAsString( $arrayIngredientes )
	{
		$string = '';
		for( $i = 0; $i < sizeof( $arrayIngredientes ); $i++ ) 
		{
			if( $i + 1 < sizeof( $arrayIngredientes ) ) //Si el indice actual no es el ultimo
				$string = $string . $arrayIngredientes[ $i ][ 'nombreIngrediente' ] . ', ';
			else
				$string = $string . $arrayIngredientes[ $i ][ 'nombreIngrediente' ];

		}//end for

		return $string;
	}//end getIngredientesAsString



	//************************************************************************************************

	
}//end Listar