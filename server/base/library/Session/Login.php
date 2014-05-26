<?php
namespace Gear\Session;

use Gear\Db\GMySqli;

session_start();

class Login
{
	public function __construct() {
	}//end __construct


	//************************************************************************************************


	public function existAccount( $table, $colsTable, $datosComparar, $datosRecoger = '*' ) {
		$i = 0;//se declara fuera para poder usar break, para que coincidan las posiciones de $colsTable con $datosComparar

		foreach ( $colsTable as $a ) {

			while( $i < sizeof( $datosComparar ) ) {
				if( $i == 0 ) {
					$where = $a." = '".$datosComparar[ $i ]."'";

				} else {					
					$where = $where." AND ".$a." = '".$datosComparar[ $i ]."'";

				}//end if..else

				$i++;//se aumenta una posicion
				break;//se sale del flujo de control de while y el flujo regresa a colstable
			}
		}
		
		//Si no hay registros, retorna falso
		if( GMySqli::getCountRegisters( $table, $colsTable[ 0 ], $where ) == 0 ) {
			return false;

		} 
		else 
		{
			//Crea el query
			GMySqli::$query = "SELECT ".$datosRecoger." FROM ".$table.
				" WHERE ".$where;

			//Realiza la consulta
			if( $result = GMySqli::$mysqli->query( GMySqli::$query ) )
				$register = $result->fetch_assoc();

			//Retorna el registro
			return $register;

		}//end if...else
		
	}//end existAccount
	
}//end Login


//Created by Gear
//..:::Lorines:::..