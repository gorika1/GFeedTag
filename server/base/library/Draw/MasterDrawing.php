<?php

namespace Gear\Draw;

class MasterDrawing
{
	protected $className;

	protected $list;
	protected $template;

	public function __construct()
	{
		$this->className = str_replace( 'Drawing', '', get_class( $this ) );
	} // end __construct

	public function setTemplate()
	{
		//Establece el directorio de las listas de una vista		
		$this->template = file_get_contents( 'client/html/master/' . lcfirst( $this->className ) . '/' . lcfirst( $this->className ) . '.html' );
	} // end setTemplate

	public function translateConst()
	{
		global $drawer; // Obtiene el drawer declarado en process.php
		$this->template = $drawer->draw( $this->list, $this->template );
	} // end translateConst
}//end MasterView