<?php

namespace Gear\Draw;

class MasterDrawing
{
	protected $drawer; // replica del objeto drawer
	protected $login; // replica del objeto login
	protected $className; // nombre de la clase hija que utiliza las funciones genericas

	protected $template;

	public function __construct()
	{
		//Clona los datos de login si existe
		global $login;
		if( isset( $login ) )
			$this->login = $login;

		$this->className = str_replace( 'Drawing', '', get_class( $this ) );
	}//end __construct


	public function setTemplate()
	{
		//Establece el directorio de las listas de una vista		
		$this->template = file_get_contents( 'client/html/master/' . lcfirst( $this->className ) . '/' . lcfirst( $this->className ) . '.html' );
	}

	public function translateConst()
	{
		global $drawer;
		$this->template = $drawer->draw( $this->translate, $this->template );
	}
}//end MasterView