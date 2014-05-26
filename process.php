<?php

require_once 'server/libraries.php'; // Carga las librerias a usar


$server = str_replace( $_SERVER[ 'DOCUMENT_ROOT' ], '', __FILE__ ); // elimina el directorio root de la direccion dada por __FILE__
$server = str_replace( 'process.php', '', $server ); // quita la palabra process.php para quedarse con la raiz de la web

$config = require 'config.php';

if( isset( $config[ 'Master Page' ] ) )
	$drawer = new Gear\Draw\Drawer( $config[ 'Master Page' ] );

if ( isset( $config[ 'Data Base' ] ) )
{
	$dataBase = $config[ 'Data Base' ];
	Gear\Db\Connecting::setConnectData( $dataBase );
}

$urlController = $drawer->getMVC( 'action', 'index', 'error' );//obtiene el controller de acuerdo al valor de la posicion action en $_GET[]