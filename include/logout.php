<?php
session_start();

$rfc=$_SESSION["rfc"];

$carpetaDestino = "../views/reportes/reportes_graficos/imagenseccion/archivos/";

if(file_exists($carpetaDestino)){
 	unlink($carpetaDestino.'cumplimiento'.$rfc.'.png'); //elimino el fichero
 	unlink($carpetaDestino.'area'.$rfc.'.png'); //elimino el fichero
 	unlink($carpetaDestino.'tipo'.$rfc.'.png');
 	unlink($carpetaDestino.'ejercicios'.$rfc.'.png');
}

$dir = "../views/reportes/codigo_qr/temp/"; 

 if(file_exists($dir)){
 	unlink($dir.'codigoqr'.$rfc.'.png'); //elimino el fichero
}

$acuses = "../views/reportes/acuses/"; 
 if(file_exists($acuses)){
	foreach (glob($acuses."*".$rfc."*.pdf") as $filename) {
    unlink($filename);
	}
}


$_SESSION = array();

session_destroy(); 

header("Location: ../login.php");
?>