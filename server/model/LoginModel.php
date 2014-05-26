<?php

use Gear\Session\Session;

class LoginModel
{
	public function login()
	{
		$user = $_POST[ 'user' ];
		$pass = $_POST[ 'password' ];

		$login = new Session();
		if( $register = $login->existAccount( 'Usuarios', 
									array( 'user', 'pass' ),
									array( $user, $pass),
									'nombreUsuario, TiposUsuarios_idTipoUsuario'
								))
		{
			$level = $register[ 'TiposUsuarios_idTipoUsuario' ];
			
			$login->setLevel( $level ); // establece el nivel de sesion

			$_SESSION[ 'name' ] = $register[ 'nombreUsuario' ];

			$this->redirect( $level ); // redirecciona a la pagina correcta
		}
		else
			echo 'mal';
	} // end login


	private function redirect( &$level )
	{
		switch ( $level )
		{
			case 1:
				header( 'Location: pedidos' );
				break;
			
			case 2:
				header( 'Location: cocina' );
				break;

			case 3:
				header( 'Location: barra' );
				break;

			case 4:
				header( 'Location: admin' );
				break;
		}
	} // end redirect

} // end Login