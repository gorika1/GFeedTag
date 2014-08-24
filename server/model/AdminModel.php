<?php
use Gear\Db\GMySQLi;

class AdminModel
{
	// Obtiene el recuento de acuerdo a la red social utilizada
	public function getRecount()
	{
		$counts[ 'twitter' ] = GMySQLi::getCountRegisters( 'Media', 'idMedia', 
											'_from = 1 AND Users_idUser = ' . $_SESSION[ 'idUser' ] . ' AND time > ' . $this->getInitialDate() );
		$counts[ 'insta' ] = GMySQLi::getCountRegisters( 'Media', 'idMedia', 
											'_from = 2 AND Users_idUser = ' . $_SESSION[ 'idUser' ] . ' AND time > ' . $this->getInitialDate() );

		return $counts;
	} // end getRecount


	// Obtiene la fecha inicial de las publicaciones
	// La variable formated sirve para decir que se quiere
	// la fecha en formato d-m-Y para mostrarlo en el config
	public function getInitialDate( $formated = false )
	{
		$register = GMySQLi::getRegister( 'Users', array( 'initialDate' ), 'idUser = ' . $_SESSION[ 'idUser' ] );

		if( $formated == true )
		{
			if( $register[ 'initialDate' ] == 0 )
				return 'Traer desde todos los tiempos';

			return date( "d-m-Y", $register[ 'initialDate' ] );	
		} // end if

		return $register[ 'initialDate' ];
	} // end getInitialDate

	// Actualiza la fecha inicial para traer las imagenes
	public function setInitialDate( $date )
	{
		if( $date == false )
			$date = null;
		else
			$date = strtotime( $date );

		GMySQLi::updateRegister( 'Users', array( 'initialDate' => $date ), 'idUser =' . $_SESSION[ 'idUser' ] );
	}

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

		// Elimina toda la media del user
		// Debido a que debe reiniciar el id
		// de las ultimas publicaciones de twitter e instagram
		// en la tabla Users
		$medias = GMySQLi::getRegisters( 'Media', array( 'idMedia', '_from' ),
											'Users_idUser = ' . $_SESSION[ 'idUser' ] );
		foreach ( $medias as $media ) 
		{
			GMySQLi::deleteRegister( 'Media', 'idMedia = ' . $media[ 'idMedia' ] . ' AND _from = ' . $media[ '_from' ] );
		} // end foreach

		GMySQLi::updateRegister( 'Users', array( 'next_tw_id' => 1, 'next_insta_id' => 1), 
								 'idUser = ' . $_SESSION[ 'idUser' ] );
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