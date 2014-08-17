<?php

namespace Gear\Draw;

use Gear\Draw\Template;

class Drawer extends Template {

	private $drawing;
	private $head;
	private $server;
	private $template;
	private $constControlledDrawing;
	private $checked;


	public function __construct( &$masterPage ) 
	{
		global $server;
		if( isset( $server ) )
			$this->server = $server;

		$this->drawing = array();

		if( isset( $masterPage[ 'With Controller' ] ) )
		{
			$this->checked = $masterPage[ 'With Controller' ]; // Guarda los componentes que requieren de un controlador
			unset( $masterPage[ 'With Controller' ] );
			$this->constControlledDrawing = $this->processWithController( $this->checked );//obtiene el array de objetos devueltos por processWithController
		}//end if

		$uri = 'client/html/master/';

		//Si se pasaron zonas constantes extrax
		if( isset( $masterPage[ 'Extras' ] ) )
		{
			//se itera por los datos pasados y se guarda entre el drawing constante
			foreach ( $masterPage[ 'Extras' ] as $key => $value ) 
			{
				$template = file_get_contents( $uri . $value .'.html' );
				$this->setDrawConst( $template, $key );
			}//end foreach
		}//end if

		
		$this->headTemplate = file_get_contents( $uri . $masterPage[ 'HEAD' ] . '.html' );

		if( isset( $this->headerTemplate ) )
			$this->headerTemplate = file_get_contents( $uri . $masterPage[ 'HEADER' ] . '.html' );

		$this->footerTemplate = file_get_contents( $uri . $masterPage[ 'FOOTER' ] . '.html' );

	}//end __construct


	//**********************************************************************************


	//Dibuja las partes principales de las paginas
	private function principalDraw() 
	{

		$this->drawing[ 'HEAD' ] = $this->getHead();

		if( $this->getHeader() != '' )
			$this->drawing[ 'HEADER' ] = $this->getHeader();

		$this->drawing[ 'FOOTER' ] = $this->getFooter();
		$this->drawing[ '[Lx]' ] = '';

		// Obtiene todos los que necesitan un controlador
		if( isset( $this->checked ) )
			$this->drawConstWithController();

	}//end principalTranslate



	//***********************************************************************************


	//Dibuja los vinculos a archivos locales en el servidor
	private function drawLocal() {
		$this->setPage( str_replace( "lhref=\"", "href=\"".$this->server, $this->getPage() ) );
		$this->setPage( str_replace( "lsrc=\"", "src=\"".$this->server, $this->getPage() ) );
		$this->setPage( str_replace( "laction=\"", "action=\"".$this->server, $this->getPage() ) );
	}




	//**********************************************************************************

	private function createDrawing( &$array = array() ) {

		//Por cada elemento del parametro array
		foreach ( $array as $key => $value ) {
			$this->drawing[ $key ] = $value;//guarda en la propiedad draw
		}//end foreach

	}//end createDraw



	//*********************************************************************************************


	//Traduce un template de listado
	private function drawList( &$array, &$template ) {

		foreach( $array as $key => $valor )
		{
			$template = str_replace( '{'.$key.'}', $valor, $template );										
		}//end foreach

		//Devuelve un template traducido
		return $template;

	}//end translate



	//************************************************************************************
	public function draw( &$array = array(), &$template = null ) {
		
		//Si se establecio un template, quiere decir que se traduce una lista
		if( isset( $template ) ) 
		{
			$template = $this->drawList( $array, $template );
			return $template;
		}//end if

		//Crea el diccionario para las principales partes de una pagina
		$this->principalDraw();

		//Crea el diccionario especifico de la pagina
		$this->createDrawing( $array );

		foreach( $this->drawing as $key => $valor ) 
		{
			//Obtiene el template mediante el metodo getPage de la clase Index y lo traduce. Y utiliza el metodo setPage de la clase Index para establecer la pagina.
			$this->setPage( str_replace( '{'.$key.'}', $valor, $this->getPage() ) );

		}//end foreach

		$this->drawLocal();//Traduce los vinculos a archivos locales en el servidor

		//Imprime la página traducida
		echo $this->getPage();	

	}//end draw


	//*******************************************************************************************************


	//Traduce un lista de traducciones para un listado

	public function convertListToString( &$listAsArray, &$template, &$listToString ) {

		$listToString = '';
		foreach ( $listAsArray as $drawing ) {
			$templateTemp = $template;//crea un template temporal que en cada iteracion vuelve a tener las claves a traducir
			$listToString = $listToString.$this->draw( $drawing, $templateTemp );
		}//end foreach

	}//end convertListToString

	
	//*******************************************************************************************************


	//Definir un template constante del sitio
	public function setDrawConst( &$template, $key ) 
	{
		
		if( !array_key_exists( $key, $this->drawing ) ) 
		{
			$this->drawing[ $key ] = $template;
			
		}//end if
		
	}//end setDrawictionaryConst


	// Agrega las plantillas de las constantes con controlador
	public function drawConstWithController()
	{
		$constants = array();

		foreach ( $this->checked as $key => $value )
			$constants[] = $key;

		for( $i = 0; $i < sizeof( $this->constControlledDrawing ); $i++ ) 
		{
			$template = $this->constControlledDrawing[ $i ]->getTemplate();

			$this->setDrawConst( $template, $constants[ $i ] );
		}
		
	}


}//end Draw

?>