<?php

namespace Gear\Draw;

class Template 
{
	protected $headTemplate;
	protected $headerTemplate;
	protected $footerTemplate;
	protected $pageTemplate;


	//************************************************************************************
	// Genera la direccion al controller del parametro pasado por url

	public function getMVC( $posGET, $default, $error ) 
	{
		$action; // almacena el nombre de los directorios
		$action2 = ''; // almacena el nombre del controller y el drawing

		if( isset( $_GET[ $posGET ] ) )
		{
			$action = $_GET[ $posGET ];
			global $folder;
			$folder = $action;
		}			
		else
			$action = $default;

		// Si el action vino con guiones en la url
		if( strpos( $action, '-' )  !== false )
		{
			$pieces = explode( '-', $action );

			for( $i = 0; $i < sizeof( $pieces ); $i++ )
			{
				$action2 .= ucfirst( $pieces[ $i ] );
			} // end for
		} 
		else
		{
			$action2 = ucfirst( $action );
		}// end if...else

		if( is_file( 'server/controller/'. $action2 .'Controller.php' ) ) 
		{				 
			$this->pageTemplate = file_get_contents( 'client/html/app/'.$action.'/'.$action.'.html' );
			$controller = 'server/controller/'. $action2 .'Controller.php';
			$drawing = 'server/drawing/' . $action2 . 'Drawing.php';
		} 
		else 
		{
			$this->pageTemplate = file_get_contents( 'client/html/notification/' . $error . '.html' );
			$controller = 'server/controller/'. ucfirst( $error ).'Controller.php';
			$drawing = 'server/drawing/' . ucfirst( $error ) . 'Drawing.php';
		}//end if..else

		require_once $drawing;
		require_once  $controller;

	}//end setMVC


	//************************************************************************************

	public function processWithController( &$withController )
	{
		$obj = '';
		foreach ( $withController as $actual ) 
		{
		    $filesName = $actual[ 'Files Names' ];
		    $filesName2 = lcfirst( $filesName );
		    require_once 'server/controller/' . $filesName2 . 'Controller.php';//Obtiene el controller

		    $template = file_get_contents( 'client/html/master/' . $filesName2 . '/' . $filesName2 . '.html' ); //obtiene el template
		    $class = $filesName . 'Drawing();';//Obtiene el nombre de la clase
		    eval( "\$obj[] = new $class" ); //crea el objeto de la clase
		}
		return $obj; //retorna el array de objetos drawing constantes controlados
	}//end processWithController

	//************************************************************************************
	//************************ GETTERS AND SETTERS ***************************************
	public function getHead() {
		return $this->headTemplate;
	}//end getHeader


	public function getHeader() {
		return $this->headerTemplate;
	}//end getHeader

	public function getFooter() {
		return $this->footerTemplate;
	}//end getHeader

	public function getPage() {
		return $this->pageTemplate;
	}//end getPageTemplate

	public function setPage( $template ) {
		$this->pageTemplate = $template;
	}//end setPage

}//end Template

?>