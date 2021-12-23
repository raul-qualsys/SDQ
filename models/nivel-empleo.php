<?php

include("../include/conexion.php");

if(!empty($_POST["puesto"])){
	$id=$_POST["puesto"];
	$sql="SELECT nivel FROM qsy_puestos where id=$id and estatus='A' and fecha_efec <= CURRENT_DATE order by fecha_efec desc limit 1";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	if($val){
		echo $val["nivel"];
	}
	echo "";
}
if(!empty($_POST["puesto1"])){
	$id=$_POST["puesto1"];
	$sql="SELECT nivel FROM qsy_puestos where id=$id and estatus='A' and fecha_efec <= CURRENT_DATE order by fecha_efec desc limit 1";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	if($val){
		echo $val["nivel"];
	}
	echo "";
}

?>