<?php
include("../include/conexion.php");

if(isset($_POST["cp"])){
	$cp=$_POST["cp"];
	$sql="SELECT * FROM qsy_codigos_postales WHERE codigopostal like '$cp'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	$html="";
	if($val){
		$municipio=$val['municipio'];
		$estado=$val['estado'];
		$sql="SELECT estado,descr FROM qsy_estados WHERE estado like '$estado'";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_assoc($result);
		$estado=$val['estado'];

		$sql="SELECT municipio,descr FROM qsy_municipios WHERE estado like '$estado' AND estatus='A' order by descr";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
		if($val){
			$html='';
			foreach($val as $key=>$registro){
				$html.='<option value="'.$registro["municipio"].'"';
				if($registro["municipio"]==$municipio){$html.=' selected';}
				$html.='>'.$registro["descr"].'</option>';
			}
		}
		
		$arreglo=array(
			"estado"=>$estado,
			"municipio"=>$html
		);
		print_r(json_encode($arreglo));
	}
	else{
		print_r("1");
	}
}

if(isset($_POST["estado"])){
	$estado=$_POST["estado"];
	$sql="SELECT municipio,descr FROM qsy_municipios WHERE estado like '$estado' AND estatus='A' order by descr";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	$html='';
	if($val){
		foreach($val as $key=>$registro){
			$html.='<option value="'.$registro["municipio"].'">'.$registro["descr"].'</option>';
		}
	}
	echo $html;
}

if(isset($_POST["codigopostal"])){
	$cp=$_POST["codigopostal"];
	$sql="SELECT colonia,descr FROM qsy_colonias WHERE codigo_postal like '$cp' order by descr";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	$html='';
	if($val){
		foreach($val as $key=>$registro){
			$html.='<option value="'.$registro["colonia"].'">'.$registro["descr"].'</option>';
		}
	}
	echo $html;
}

?>