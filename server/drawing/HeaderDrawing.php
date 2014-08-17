<?php
use Gear\Draw\MasterDrawing;

class HeaderDrawing extends MasterDrawing 
{

	private $nombreUsuario;

	public function __construct() 
	{
		parent::__construct();

		if( isset( $_SESSION[ 'idUser' ] ) ) 
		{
			$this->nombreUsuario = $_SESSION[ 'userName' ];		
		}//end if

	}//end __construct


	//***********************************************************************************************************

	public function getTemplate() {

		$this->setTemplate();

		//Si es un usuario logueado
		if( isset( $this->nombreUsuario ) ) 
		{
			$this->list = array(
				'UserName' => $this->nombreUsuario, //el nombre
			);

			$this->translateConst( $this->list, $this->template );	
		}

		return $this->template;
	}

}//end HeaderDrawing