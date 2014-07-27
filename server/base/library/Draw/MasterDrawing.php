<?php

namespace Gear\Draw;

class MasterDrawing
{
	protected $folder; // replica la variable global folder de process.php

	protected $template;

	public function __construct()
	{
		global $folder;
		$this->folder = $folder;
	}//end __construct


	public function setTemplate()
	{
		global $folder;

		//Establece el directorio de las listas de una vista		
		$this->template = file_get_contents( 'client/html/master/' . $folder . '/' . lcfirst( $this->className ) . '.html' );
	}

	public function translateConst()
	{
		global $drawer;
		$this->template = $drawer->draw( $this->translate, $this->template );
	}
}//end MasterView