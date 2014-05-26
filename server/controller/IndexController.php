<?php

use Gear\Controller\ControllerAJAX;

require_once 'server/model/LoginModel.php';
require_once 'server/drawing/IndexDrawing.php';

class IndexController extends ControllerAJAX
{
	public function __construct()
	{
		$drawing = new IndexDrawing();
		$drawing->drawPage( 'Bienvenido al sistema de pedidos de Honolulu' );
	} // end __construct

	public function login()
	{
		$myLogin = new LoginModel();
		$myLogin->login();
	} // end login
} // end ControllerAJAX




$page = new IndexController();

if( isset( $_POST[ 'user'] ) && isset( $_POST[ 'password' ] ) )
{
	$page->login();
}