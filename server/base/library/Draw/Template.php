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
		if( isset( $_GET[ $posGET ] ) )
			$action = $_GET[ $posGET ];
		else
			$action = $default;

		if( is_file( 'server/controller/'. ucfirst( $action ).'Controller.php' ) ) {				 
			$this->pageTemplate = file_get_contents( 'client/html/app/'.$action.'/'.$action.'.html' );
			$controller = 'server/controller/'. ucfirst( $action ).'Controller.php';
		} 
		else 
		{
			$this->pageTemplate = file_get_contents( 'client/html/notification/' . $error . '.html' );
			$controller = 'server/controller/'. ucfirst( $error ).'Controller.php';
		}//end if..else

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