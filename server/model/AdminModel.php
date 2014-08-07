<?php
use Gear\Db\GMySQLi;

class AdminModel
{
	// Obtiene el recuento de acuerdo a la red social utilizada
	public function getRecount()
	{
		$counts[ 'twitter' ] = GMySQLi::getCountRegisters( 'Media', 'idMedia', '_from = 1 AND Users_idUser = ' . $_SESSION[ 'idUser' ] );
		$counts[ 'insta' ] = GMySQLi::getCountRegisters( 'Media', 'idMedia', '_from = 2 AND Users_idUser = ' . $_SESSION[ 'idUser' ] );

		return $counts;
	} // end getRecount

	// Obtiene el listado de hashtags
	public function getHashtags()
	{
		$hashtags = GMySQLi::getRegisters( 'Hashtags', 
											array( 'idHashtag, hashtag' ), 'Users_idUser = ' . $_SESSION[ 'idUser' ],
											'idHashtag ASC' );
		
		return $hashtags;
	} // end getHashtags

	// Agrega un hashtag
	public function addHashtag( $hashtag )
	{
		GMySQLi::setRegister( 'Hashtags', '*', array( null, $hashtag, $_SESSION[ 'idUser' ] ) );

		// Devuelve el id del hashtag agregado
		$lastId = GMySQLi::getRegisters( 'Hashtags', array( 'idHashtag' ), 
								'Users_idUser = ' . $_SESSION[ 'idUser' ],
								'idHashtag DESC', 1 );

		echo $lastId[0]['idHashtag'];
	} // end addHashtag

	// Actualiza el hashtag
	public function updateHashtag( $data )
	{
		GMySQLi::updateRegister( 'Hashtags', array( 'hashtag' => $data['hashtag'] ), 'idHashtag =' . $data[ 'idHashtag' ] );		
	}

	// Elimina el hashtag
	public function deleteHashtag( $idHashtag )
	{
		GMySQLi::deleteRegister( 'Hashtags', 'idHashtag =' . $idHashtag );
		echo GMySQLi::viewQuery();
	} // end deleteHashtag


	// Obtiene el listado de palabras censuradas
	public function getBlacklist()
	{
		$blacklist = GMySQLi::getRegisters( 'Blacklist', 
											array( 'idPalabra, palabra' ), 'Users_idUser = ' . $_SESSION[ 'idUser' ],
											'palabra ASC' );
		
		return $blacklist;
	} // end getHashtags

	// Agrega una palabra al blacklist
	public function addWord( $word )
	{
		//Controla si no existe un registro similar para luego insertar
		$reg = GMySQLi::getRegisters( 'Blacklist', array( 'idPalabra' ), "palabra = '" . $word . "'" );

		if( sizeof( $reg ) == 0 )
		{
			GMySQLi::setRegister( 'Blacklist', '*', array( null, strtolower( $word ), $_SESSION[ 'idUser' ] ) );

			// Devuelve el id de la palabra agregada
			$lastId = GMySQLi::getRegisters( 'Blacklist', array( 'idPalabra' ), 
								'Users_idUser = ' . $_SESSION[ 'idUser' ],
								'idPalabra DESC', 1 );

			echo $lastId[0]['idPalabra'];
		}
		else
			echo 'false';
	} // end addHashtag

	// Elimina una palabra del blacklist
	public function deleteWord( $idWord )
	{
		GMySQLi::deleteRegister( 'Blacklist', 'idPalabra =' . $idWord );
	} // end deleteHashtag
} // end AdminModel