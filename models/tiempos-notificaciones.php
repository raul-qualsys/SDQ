<?php 
include("../include/conexion.php");

if(isset($_POST["alertaI"])){
	$alertaI=htmlspecialchars($_POST["alertaI"]);
	$alertaM=htmlspecialchars($_POST["alertaM"]);
	$alertaC=htmlspecialchars($_POST["alertaC"]);
	$toleraI=htmlspecialchars($_POST["toleraI"]);
	$toleraM=htmlspecialchars($_POST["toleraM"]);
	$toleraC=htmlspecialchars($_POST["toleraC"]);

	$sql="UPDATE qsy_notificaciones_dias SET dias=$alertaI WHERE tipo_decl='I' AND tipo_notificacion='A'";
	$result=pg_query($conn,$sql);
	$sql="UPDATE qsy_notificaciones_dias SET dias=$alertaM WHERE tipo_decl='M' AND tipo_notificacion='A'";
	$result=pg_query($conn,$sql);
	$sql="UPDATE qsy_notificaciones_dias SET dias=$alertaC WHERE tipo_decl='C' AND tipo_notificacion='A'";
	$result=pg_query($conn,$sql);
	$sql="UPDATE qsy_notificaciones_dias SET dias=$toleraI WHERE tipo_decl='I' AND tipo_notificacion='T'";
	$result=pg_query($conn,$sql);
	$sql="UPDATE qsy_notificaciones_dias SET dias=$toleraM WHERE tipo_decl='M' AND tipo_notificacion='T'";
	$result=pg_query($conn,$sql);
	$sql="UPDATE qsy_notificaciones_dias SET dias=$toleraC WHERE tipo_decl='C' AND tipo_notificacion='T'";
	$result=pg_query($conn,$sql);

	echo "Los cambios se guardaron satisfactoriamente.";
}
 ?>