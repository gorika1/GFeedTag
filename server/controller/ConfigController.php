<?php

use Gear\Controller\ControllerAJAX;
require_once 'server/model/AdminModel.php';

class ConfigController extends ControllerAJAX 
{
	public function __construct()
	{
		// Si es para actualizar un hashtag
		if( isset( $_POST[ 'update' ] ) && $_POST[ 'update' ] == 'Hashtag' )
		{
			$myAdmin = new AdminModel();
			$myAdmin->updateHashtag( $_POST );
		}

		// Si es para eliminar registros
		else if( isset( $_POST[ 'delete' ] ) )
		{
			$myAdmin = new AdminModel();

			// Si es para eliminar un hashtag
			if( $_POST[ 'delete' ] == 'Hashtag' )
				$myAdmin->deleteHashtag( $_POST[ 'idHashtag' ] );

			// Si es para eliminar una palabra del blacklist
			else if( $_POST[ 'delete'] == 'Word' )
				$myAdmin->deleteWord( $_POST[ 'idWord' ] );

		}

		// Si es para agregar registros
		else if( isset( $_POST[ 'add' ] ) )
		{
			$myAdmin = new AdminModel();

			// Si es para agregar un hashtag
			if( $_POST[ 'add' ] == "Hashtag" )
				$myAdmin->addHashtag( $_POST[ 'hashtag' ] );

			// Si es para agregar una palabra al blacklist
			else if( $_POST[ 'add' ] == "word" )
				$myAdmin->addWord( $_POST[ 'word' ] );
		}
		
		// Si es la llamada para la carga de la página
		else
		{
			$drawing = new ConfigDrawing();

			$drawing->drawPage( 'Configurar | FeedTag', array( 'Hashtags()', 'Blacklist()' ) );
		} // end if..else
	} // end __construct
} // end ConfigController


// Si no esta logueado
if( !isset( $_SESSION[ 'user'] ) )
{
	header( 'Location: ../login' );
}
else
{
	$page = new ConfigController();
}
