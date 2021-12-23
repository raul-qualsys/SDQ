<?php
require 'phpqrcode/qrlib.php';

$dir = 'temp/';

if(!file_exists($dir))
mkdir($dir);

$filename = $dir.'codigoqr.png';
$tamanio = 10;
$level = 'M';
$frameSize = 3;
$contenido = 'HOLA MUNDO';

QRcode::png ($contenido, $filename, $level, $tamanio, $frameSize);
echo '<img src="'.$filename.'" />';
?>