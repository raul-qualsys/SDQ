<?php

include("../include/conexion.php");

if(!empty($_POST["rfc"])){
	$rfc=$_POST["rfc"];
	$tipo_decl=$_POST["tipo_decl"];
	$ejercicio=$_POST["ejercicio"];
	$sql="SELECT * FROM qsy_direcciones where rfc='$rfc' AND tipo_decl='$tipo_decl' AND ejercicio=$ejercicio";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	if($val){
		print_r(json_encode($val));
	}
}

?>