<?php 
require_once "../clases/Archivo.php";

	$img = $_POST['img'];
	$nombre=$_POST['nombre'];

	$Archivo = new Archivo();

	echo $Archivo->subeimagen64temp($img, $nombre);
?>