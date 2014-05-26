<?php

namespace Gear\Draw;

use Gear\Draw\MasterDrawing;

class Drawing extends MasterDrawing
{
	protected $server; // almacena la url absoluta en donde trabaja el programador		
	protected $template; // template de la lista con el que trabaja el codigo cliente en un momento dado
	protected $list; // lista de los words y su correspondiente traduccion

	protected $principalList = array(); // Almacena los distintos fragmentos html de listados


	public function __construct()
	{
		parent::__construct();

		//Establece la raiz de trabajo del programador
		global $server;
		if( isset( $server ) )
			$this->server = $server . 'server/';
		else
			$this->server = '/server/';

	}//end __construct


	//***********************************************************************************

	//Obtiene el template de la lista pasada
	protected function setList( $name )
	{
		//Establece el directorio de las listas de una vista			
		$directory = 'client/html/app/' . lcfirst( $this->className ).'/list/';
		// Obtiene el template a procesar
		$this->template = file_get_contents( $directory . $name . '.html' );
	}

	//***********************************************************************************
	//Crea la fraccion html correspondiente recibiendo como parametro el atributo (del codigo cliente) en donde se guardara el fragmento
	protected function draw( $listName, $list = null )
	{
		$this->principalList[ $listName ] = ''; // Crea el indice en el arreglo

		// Si no se ha establecido una lista distinta al atributo $this->list
		if( !isset( $list ) )
		{
			//Si no hay indices en $this->list no hay nada que traducir
			if( isset( $this->list ) )
			{
				// Crea el fragmento HTML
				$this->drawer->convertListToString( $this->list, $this->template, $this->principalList[ $listName ] );
				unset( $this->list );	
			}
		}
		// Si quiere que se le devuelva el template, se pasa una lista diferente al atributo $this->list
		else
		{
			$this->drawer->convertListToString( $list, $this->template, $this->principalList[ $listName ] );
			$template = $this->principalList[ $listName ];
			unset( $this->principalList[ $listName ] );
			return $template;
		} // end if
		// Borra los valores de $this->list para recibir un nuevo conjunto de words a traducir
	}//end translate

	//************************************************************************************

	public function drawPage( $title, $replaced = null, $extras = null )
	{
		//Clona los datos del objeto drawer
		global $drawer;
		$this->drawer = clone( $drawer );
		
		//Si se ha pasado la lista que reemplazar
		if( isset( $replaced ) )
		{
			foreach ( $replaced as $function )
			{
				eval( '$this->draw' . $function . ';' );
			}
				
		}//end if

		//Si se pasaron elementos extras que traducir
		if( isset( $extras ) )
		{
			foreach ( $extras as $key => $value ) {
				$this->principalList[ $key ] = $value;
			}
		}
		
		$this->principalList[ 'Title' ] = $title;
		$this->drawer->draw( $this->principalList );
	}//end translatePage

}//end Drawer