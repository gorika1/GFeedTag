<?php
use Gear\Db\GMySQLi;

class AdminModel
{
	// Obtiene el recuento de acuerdo a la red social utilizada
	public function getRecount()
	{
		$counts[ 'twitter' ] = GMySQLi::getCountRegisters( 'media', 'idMedia', '_from = 1 AND Users_idUser = ' . $_SESSION[ 'idUser' ] );
		$counts[ 'insta' ] = GMySQLi::getCountRegisters( 'media', 'idMedia', '_from = 2 AND Users_idUser = ' . $_SESSION[ 'idUser' ] );

		return $counts;
	} // end getRecount

	// Obtiene el listado de hashtags
	public function getHashtags()
	{
		$hashtags = GMySQLi::getRegisters( 'hashtags', array( 'hashtag' ), 'Users_idUser = ' . $_SESSION[ 'idUser' ] );
		
		return $hashtags;
	} // end getHashtags
} // end AdminModel