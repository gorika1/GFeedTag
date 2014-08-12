<?php

use Gear\Controller\ControllerAJAX;
require_once 'server/model/MediaModel.php';

class GalleryController extends ControllerAJAX
{
	public function __construct()
	{
		// Si se quieren eliminar registros
		if( isset( $_POST[ 'delete' ] ) )
		{
			// Si se quiere eliminar una publicacion
			// se agrega a un registro en un archivo
			// y luego se elimina de la base de datos
			if( $_POST[ 'delete' ] == 'media' )
			{	
				// Si no existe el archivo de registros eliminados	
				if( ! is_file( 'server/deleted.dat' ) )
				{
					$file = fopen( 'server/deleted.dat', 'a' );
					fwrite( $file, $_POST[ 'urlMedia' ] );
				}
				else
				{
					$file = fopen( 'server/deleted.dat', 'a' );
					fwrite( $file, "\n" . $_POST[ 'urlMedia' ] );
				} // end if...else

				$myMedia = new MediaModel();
				$myMedia->deleteMedia( $_POST[ 'idMedia' ], $_POST[ 'from' ] );
				
			} // end if
		}
		else
		{
			$drawing = new GalleryDrawing();
			$drawing->drawPage( 'Galeria | FeedTag', array( 'Media()' ) );
		} // end if...else

	} // end __construct

} // end GalleryController

$page = new GalleryController();