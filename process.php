<?php
require_once 'server/libraries.php'; // Carga las librerias a usar

$file = str_replace( "\\", '/', __FILE__ ); //reemplaza las barras inversas por las barras (solo para Windows)
$server = str_replace( $_SERVER[ 'DOCUMENT_ROOT' ], '', $file ); // elimina el directorio root de la direccion dada por __FILE__

$server = str_replace( 'process.php', '', $server ); // quita la palabra process.php para quedarse con la raiz de la web

$config = require 'config.php';

if( isset( $config[ 'Master Page' ] ) )
	$drawer = new Gear\Draw\Drawer( $config[ 'Master Page' ] );

if ( isset( $config[ 'Data Base' ] ) )
{
	$dataBase = $config[ 'Data Base' ];
	Gear\Db\Connecting::setConnectData( $dataBase );
} // end if

$folder; // almacena la carpeta correspondiente de client/html/app/

$urlController = $drawer->getMVC( 'action', 'login', 'error' );//obtiene el controller de acuerdo al valor de la posicion action en $_GET[]
