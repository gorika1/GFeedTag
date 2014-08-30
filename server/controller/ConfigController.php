<?php

use Gear\Controller\ControllerAJAX;
require_once 'server/model/AdminModel.php';

class ConfigController extends ControllerAJAX 
{
	public function __construct()
	{
		$myAdmin = new AdminModel();

		// Si es para actualizar un dato
		if( isset( $_POST[ 'update' ] ) )
		{
			// Si es para actualizar un hashtag	
			if( $_POST[ 'update' ] == 'Hashtag' )
				$myAdmin->updateHashtag( $_POST );

			// Si es para actualizar la fecha inicial
			else if ( $_POST[ 'update' ] == 'initialDate' )
				$myAdmin->setInitialDate( $_POST[ 'date' ] );

			// Si es para actualizar el perfil del usuario
			else if( $_POST[ 'update' ] == 'profile' )
			{
				$myAdmin->setProfile( $_POST[ 'data' ] );
			}
		}

		// Si es para eliminar registros
		else if( isset( $_POST[ 'delete' ] ) )
		{
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
			// Si es para agregar un hashtag
			if( $_POST[ 'add' ] == "Hashtag" )
				$myAdmin->addHashtag( $_POST[ 'hashtag' ] );

			// Si es para agregar una palabra al blacklist
			else if( $_POST[ 'add' ] == "word" )
				$myAdmin->addWord( $_POST[ 'word' ] );
		}
		// Si llamada es para obtener datos
		else if( isset( $_POST[ 'get' ] ) )
		{
			if( $_POST[ 'get' ] == 'profile' )
			{
				echo json_encode( $myAdmin->getProfile() );
			} // end if
		}
		// Si es la llamada para la carga de la página
		else
		{
			$drawing = new ConfigDrawing();
	
			// Obtiene la fecha inicial de manera formateada
			$initialDate = $myAdmin->getInitialDate( true );

			$drawing->drawPage( 'Configurar | FeedTag', 
								array( 'Hashtags()', 'Blacklist()' ),
								array( 'FechaInicial' => $initialDate ) );
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
