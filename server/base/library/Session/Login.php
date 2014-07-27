<?php
namespace Gear\Session;

use Gear\Db\GMySqli;

session_start();

class Login
{
	private $where; // condicional where de la consulta
	private $column;

	public function __construct()
	{

	}

	public function existAccount( $table, $colsTable, $datosComparar, $datosRecoger = '*' ) 
	{
		$this->generateWhere( $table, $colsTable, $datosComparar );

		//Si no hay registros, retorna falso
		if( GMySqli::getCountRegisters( $table, $this->column, $this->where ) == 0 ) 
		{
			return false;
		} 
		else 
		{
			//Crea el query
			GMySqli::$query = "SELECT ".$datosRecoger." FROM ".$table.
				" WHERE ".$this->where . ';';

			//Realiza la consulta
			if( $result = GMySqli::$mysqli->query( GMySqli::$query ) )
				$register = $result->fetch_assoc();

			//Retorna el registro
			return $register;
		}//end if...else		
		
	}//end existAccount

	/**********************************************************************
	***********************************************************************
	***********************************************************************/
	private function generateWhere( &$table, &$colsTable, &$datosComparar )
	{
		
		if( ( !is_array( $colsTable ) ) or ( ! is_array( $datosComparar ) ) )
		{
			$this->where = $colsTable . ' = ' . $datosComparar;
			$this->column = $colsTable;
		}
		else // Genera el where si la aceptacion depende de varias comparaciones
		{
			// Si existe la misma cantidad de columnas y datos que comparar
			if( sizeof( $colsTable ) == sizeof( $datosComparar ) )
			{
				//se declara fuera para poder usar break, para que coincidan las posiciones de $colsTable con $datosComparar
				$i = 0;

				foreach ( $colsTable as $a ) {

					while( $i < sizeof( $datosComparar ) ) 
					{
						if( $i == 0 ) 
						{
							$this->where = $a." = '".$datosComparar[ $i ]."'";

						} else 
						{					
							$this->where = $this->where." AND ".$a." = '".$datosComparar[ $i ]."'";

						}//end if..else

						$i++;//se aumenta una posicion
						break;//se sale del flujo de control de while y el flujo regresa a colstable
					} // end while
				} // end foreach

				$this->column = $colsTable[ '0' ];
			}
			else
			{
				throw new Exception("La cantidad de columnas y datos a comparar no coinciden");				
			} // end if.. else

		} // end if... else

	} // end generateWhere
	
}//end Login


//Created by Gear
//..:::Lorines:::..