<?php
namespace Gear\Db;

use Gear\Db\Connecting;

class GMySqli
{
	static $registers;
	static $mysqli = null;
	static $query = null;
	
			
	static function checkMysqli()
	{
		//si no hay una conexion mysqli, lo crea
		if ( !isset( self::$mysqli ) ) 
		{
			self::$mysqli = Connecting::startConnection();
		}		
	}//end checkMysqli



	static function freeResult( $result )
	{
		if( is_object( $result ) )
			$result->free();
		else
			return;
	}//end freeResult

	


	static function viewQuery()
	{
		echo self::$query;
	}


//****************************************************************


	static function execQuery( $query, $returnRegisters = false )
	{
		self::checkMysqli();

		//Si hay que retornar registros
		if( $returnRegisters )	{

			//Si se realiza la consulta correctamente
			if( $result = self::$mysqli->query( $query ) ) {
				
				self::$registers = array();//$registers pasa a ser un array

				while( $register = $result->fetch_assoc() ) {
					self::$registers[] = $register;//En cada iteracion guarda una fila de datos en el array

				}//end while

			} else { //Si hay una consulta fallida
				self::$query;
				throw new \Exception( GMySqli::viewQuery() . 'Error ' . self::$mysqli->errno . ': ' . self::$mysqli->error );				
			}//end if..else

			self::freeResult( $result );//Libera memoria

		} else { //Si no hay que retornar registros

			if( $result = self::$mysqli->query( self::$query ) )//Si se realiza la consulta correctamente
				self::$registers = true;//register se convierte en una variable booleana que indica el exito de la operacion
			else
			{
				self::$registers = false;//o false en caso de haber algun error
				throw new \Exception( GMySqli::viewQuery() . 'Error ' . self::$mysqli->errno . ': ' . self::$mysqli->error );				
			} // end if...else
		}//end if..else externo


		//Se retorna los registros o true, en caso de una buena ejecucion
		//Y false en caso de un error
		return self::$registers;

	}//end execQuery


//*****************************************************************

	
	static function getRegister( $from, $campos = '*', $where = null )
	{
		self::checkMysqli();//comprueba si existe el objeto mysqli

		//Crea el query
		self::$query = 'SELECT ';

		if( $campos == '*' )
		{
			self::$query .= '* FROM ' . $from;
		}
		else
		{
			if( is_array( $campos ) )
			{
				$size = sizeof( $campos );
				for( $i = 0; $i < $size; $i++ )
				{
					// Si no es el ultimo indice
					if( $i < $size - 1 )
					{
						self::$query .=  $campos[ $i ] . ', ';
					} 
					else
					{
						self::$query .= $campos[ $i ] . ' FROM ' . $from;
					} // end if...else
				} // end for
			}
		} // end if...else externo
		

		if( isset( $where ) )
			self::$query = self::$query.' WHERE '. $where . ';';


		//Si la consulta se realizo correctamente
		if( $result = self::$mysqli->query( self::$query ) )
			self::$registers = $result->fetch_assoc();//Se guarda un array asociativo del resultado			
		else
			throw new \Exception( GMySqli::viewQuery() . 'Error ' . self::$mysqli->errno . ': ' . self::$mysqli->error );


		self::freeResult( $result );

		return self::$registers;

	}//end getRegister



//*****************************************************************

	
	static function getRegisters( $from, $campos = '*', $where = null, $order = null, $limit = null )
	{
		self::checkMysqli();

		//Crea el query
		self::$query = 'SELECT ';

		if( $campos == '*' )
		{
			self::$query .= '* FROM ' . $from;
		}
		else
		{
			if( is_array( $campos ) )
			{
				$size = sizeof( $campos );
				for( $i = 0; $i < $size; $i++ )
				{
					// Si no es el ultimo indice
					if( $i < $size - 1 )
					{
						self::$query .=  $campos[ $i ] . ', ';
					} 
					else
					{
						self::$query .= $campos[ $i ] . ' FROM ' . $from;
					} // end if...else
				} // end for
			}
		} // end if...else externo
		
		if( isset( $where ) )
			self::$query = self::$query.' WHERE '.$where;
			
		if( isset( $order ) ) 
			self::$query = self::$query.' ORDER BY '.$order;
			
		if( isset( $limit ) )
			self::$query = self::$query.' LIMIT '.$limit;

		
		self::execQuery( self::$query, true );

		return self::$registers;
		
		
	}//end getRegisters

	
//*****************************************************************
	
	
	static function setRegister( $table, $campos, $values )
	{
		self::$query = "INSERT INTO ".$table;
		
		if( is_array( $campos ) || $campos == '*' )
		{
			if( is_array( $campos ) )
			{
				$size = $sizeof( $campos );
				// Guarda el valor del ultimo indice para poder cerrar el parentesis
				$last = $size - 1;

				self::$query = self::$query . ' ( ';

				for( $i = 0; $i < $size; $i++ )
				{
					// Si no es el ultimo indice
					if( $i < $last )
					{
						self::$query .= $campos[ $i ] . ', ';
					} 
					else
					{
						self::$query .= $campos[ $i ] . ')';
					} // end if...else
				} // end for
			} // end if...else externo
		} 
		else
		{
			throw new \Exception( 'Tipo de dato incorrecto para los campos en donde insertar' );
		} // end if...else externo
			
		if( is_array( $values ) )
		{
			$size = sizeof( $values );

			self::$query = self::$query . " VALUES( '";
			for( $i = 0; $i < $size; $i++ )
			{
				// Si no es la ultima posicion
				if( $i < $size - 1 )
					self::$query = self::$query . $values[ $i ] . "', '";
				else
					self::$query = self::$query . $values[ $i ] . "' );";
			} // end for
		}
		else
		{
			throw new \Exception( 'No se pasaron valores a insertar o el tipo de datos es incorrecto' );
		} // end if...else
		
		self::$query = strip_tags( self::$query );
		$bool = self::execQuery( self::$query );
			
		return $bool;
	}//end setRegister
	
	
//*****************************************************************


	static function updateRegister( $table, $set, $where )
	{
		self::$query = "UPDATE " . $table. " SET ";

		$size = sizeof( $set );
		$count = 1; // contador para encontrar el ultimo indice de $set
		foreach ( $set as $key => $value ) 
		{
			if( $count < $size )
				self::$query .= $key . " = '" . $value . "', ";
			else
				self::$query .= $key . " = '" . $value . "' WHERE ";

			$count += 1;
		} // end foreach

		self::$query .= $where;

		$bool = self::execQuery( self::$query );

		return $bool;
		
	}//end updateRegister
	
	
//*****************************************************************


	static function deleteRegister( $table, $where ) 
	{		
		self::$query = "DELETE FROM ".$table.
		" WHERE ".$where;
		
		$bool = self::execQuery( self::$query );
		
		return $bool;
	}//end deleteRegister
	
	
//*******************************************************


	static function getCountRegisters( $from, $campo = '*', $where = null )
	{
		self::checkMysqli();
		
		self::$query = "SELECT COUNT( ".$campo." ) FROM ".$from;
		
		if( isset( $where ) )
			self::$query = self::$query." WHERE ".$where;
		
		if( $result = self::$mysqli->query( self::$query ) )
		{
			$count = $result->fetch_array();				
			$count = $count[ 0 ];
		}//end if
		else
			$count = false;
		
		self::$query = self::$query . ';';
		self::freeResult( $result );

		return $count;
		
	}//end getCountRegisters

//*******************************************************************************


	static function existRegister( $from, $where )
	{
		self::checkMysqli();
		self::$query = "SELECT * FROM ". $from . " WHERE ". $where;
		
		$result = self::$mysqli->query( self::$query );

		if( $result->num_rows > 0 )
			return true;
		else
			return false;
	}

//*******************************************************************************

	static function getPaginacion( $inicio, $categoria ) 
	{
		self::checkMysqli();
					
		self::$query = "SELECT count( * ) FROM noticias
					WHERE
					id_categoria = $categoria
					ORDER BY id_noticia ASC";
					
		$result = self::$mysqli->query( self::$query );			
					
		self::$query = "SELECT * FROM noticias
					WHERE
					id_categoria = $categoria
					ORDER BY id_noticia ASC
					LIMIT $inicio , 10";

		if( $result = self::$mysqli->query( self::$query ) )
		{
			self::$registers = array();
			while( $register = $result->fetch_assoc() )
			{
				self::$registers[] = $register;
			}
			
			return self::$registers;	
		}
	}//end getPaginacion
	

//*****************************************************************


	static function uploadFile()
	{
		copy( $_FILES[ 'photo' ][ 'tmp_name' ], 'photos/'.$_FILES[ 'photo' ][ 'name' ] );

		$img = new SimpleImage();
		$img->load( 'photos/'.$_FILES[ 'photo' ][ 'name' ] );
		$img->resize( 100, 200 );
		$img->save( 'photos/imagen.jpg' );
		unlink( 'photos/'.$_FILES[ 'photo' ][ 'name' ] );
	}


//*****************************************************************


	static function shortenText( $text, $count ) 
	{
		$subText = substr( $text, 0, $count );
		return $subText;
	}//end shortenText


//***************************************************************


	static function textFormat( $text, $separate, $cod = true )
	{
		$text = trim( $text );//quita los espacios de los costados

		if( $cod ) {				
			$text = strtolower( str_replace( ' ', $separate, $text ) );
			$text = self::removeAccents( $text );
		} else {
			$text = str_replace( $separate, ' ' , $text );
		}//end if

		return $text;
	}//end textFormat



//***********************************************************************

	static function removeAccents( $text ) {
		$text = str_replace( 'á', 'a', $text );
		$text = str_replace( 'é', 'e', $text );
		$text = str_replace( 'í', 'i', $text );
		$text = str_replace( 'ó', 'o', $text );
		$text = str_replace( 'ú', 'u', $text );

		return $text;
	}


//***************************************************************


	static function numberEncripting( $pass )
	{
		echo "<script src='js/md5.js'></script>
		<script src='js/utf8_encode.js'></script>
		<script>
			var pass = md5(".$pass.");
		</script>";
		
		$pass = "<script> document.write(pass) </script>";
			
		$passE = md5( $pass );
		
		return $passE;
		
	}//end encripting


//*****************************************************************


	static function dateReverse( $date, $separate )
	{
		$day = substr( $date, 8, 2 );
		$month = substr( $date, 5, 2 );
		$year = substr( $date, 0, 4 );

		$date = $day.$separate.$month.$separate.$year;

		return $date;
	}//end dateReverse

}//end GMySqli


//*****************************************************************

?>
