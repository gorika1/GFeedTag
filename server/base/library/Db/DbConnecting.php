<?php
//Creado por Valentin Sánchez
//21/07/2013

namespace Gear\Db;

class Connecting
{	
	static $host;
	static $user;
	static $password;
	static $db;
		
	static function startConnection()
	{
		$connection = new \mysqli( self::$host, self::$user, self::$password, self::$db );
		
		$connection->set_charset( 'utf8' );
		
		if( mysqli_connect_error() )
			echo 'Error in the connection';
		else
			return $connection;
			
	}//end startConnection


	static function setConnectData( &$data )
	{
		self::$host = $data[ 'Host' ];
		self::$user = $data[ 'User' ];
		self::$password = $data[ 'Password' ];
		self::$db = $data[ 'DB' ];
	}
	
}//end Connecting
