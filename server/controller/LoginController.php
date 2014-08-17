<?php

use Gear\SocialApis\Instagram;
use Gear\Session\Login;
use Gear\Db\GMySqli;

class LoginController
{
	public function __construct()
	{

		if( !isset( $_POST[ 'user' ] ) and !isset( $_POST[ 'pass' ] ) )
		{
			$drawing = new LoginDrawing();
			$drawing->drawPage( 'Ingresar FeedTag' );
		}
		else
		{
			$this->login( $_POST[ 'user'], $_POST[ 'pass' ] );
		} // end if...else
		
	} // end __construct

	/**********************************************************************
	***********************************************************************
	***********************************************************************/

	private function login( $user, $pass )
	{
		$login = new Login();
		$account = $login->existAccount( 'Users', array( 'user', 'pass' ), array( $user, $pass ) ) ;

		if( !$account )
		{
			echo 'El nombre de usuario o contraseña no existe';
		}
		else
		{
			$_SESSION[ 'user' ] = $account[ 'user' ];
			$_SESSION[ 'idUser' ] = $account[ 'idUser' ];
			$_SESSION[ 'userName' ] = $account[ 'name' ];
			echo 'true';
		} // end if...else
		
	} // end login

} // end ControllerAJAX




$page = new LoginController();