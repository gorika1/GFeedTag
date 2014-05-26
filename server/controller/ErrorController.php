<?php

require_once 'server/drawing/ErrorDrawing.php';

$page = new ErrorDrawing();
$page->drawPage( 'La pagina solicitada no existe' );