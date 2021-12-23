<?php
function escape($string){
	$string=str_replace('"',"'",$string);
	//print_r($string);
	//$string=htmlspecialchars($string);
	$string=mb_strtoupper(str_replace("\r\n","\n",$string));
	$string=pg_escape_string($string);
	return $string;
}
function format_date($date){
	if($date=='')
		$fecha='null';
	else
		$fecha="'".htmlspecialchars($date)."'";
	return $fecha;
}
/* 21-08-2020 DMQ-Qualsys Cambio de opciones en catálogo de puestos */
function es_declarante($rfc,$conn){
	/* 31-08-2020 DMQ-Qualsys Cambio de consulta según fecha efectiva */
	$query="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= CURRENT_DATE AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
	/* Fin de actualización */
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	if($row){
		return $row["declaracion"];
	}
	return "N";
}
/* Fin de actualización */
function nombre_empleado($rfc,$conn){
	$query="SELECT * FROM qsy_rh_empleados WHERE rfc like '%".$rfc."%'";
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	$nombre=$row["nombre"];

	return $nombre;
}

function ConectarFTP(){
//Permite conectarse al Servidor FTP
//$ftp=ftp_connect("148.240.92.126",201); //Obtiene un manejador del Servidor FTP
//ftp_login($ftp,"ftp","ftprpd"); //Se loguea al Servidor FTP
//$ftp=ftp_connect("192.168.1.66",21); //Obtiene un manejador del Servidor FTP
//ftp_pasv($ftp,true); //Establece el modo de conexión
//return $ftp; //Devuelve el manejador a la función
}

function subir_archivo($archivo_local,$archivo_servidor){
$ftp=ConectarFTP() or die("ABC"); //Obtiene un manejador y se conecta al Servidor FTP
//print_r($archivo_local." ".$archivo_remoto);die;
ftp_put($ftp,$archivo_local,$archivo_servidor,FTP_BINARY);
//Sube un archivo al Servidor FTP en modo Binario
ftp_quit($ftp); //Cierra la conexion FTP
}
function estatus_form($rfc,$tipo_decl,$ejercicio,$declaracion,$tabla){
	$sql="SELECT estatus FROM $tabla WHERE rfc='$rfc' AND tipo_decl='$tipo_decl' AND ejercicio=$ejercicio AND declaracion='$declaracion' order by estatus";
	$result=pg_query($sql);
	$row=pg_fetch_assoc($result);
	if($row){
		if($row["estatus"]=='A'){
			$estatus=HTTP_PATH."/images/ico-aviso.png";
		}
		else{
			$estatus=HTTP_PATH."/images/ico-correcto.png";			
		}
	}
	else{
		$estatus=HTTP_PATH."/images/ico-error.png";
	}
	return $estatus;
}
/* 24-08-2020 DMQ-Qualsys Cambio de variables en aviso principal. */
function get_notificacion($id,$conn){
	$query="SELECT * FROM qsy_instalacion WHERE dependencia = 1";
	$result=pg_query($conn,$query);
	$fila=pg_fetch_assoc($result);
	$texto="";
	$query="SELECT * FROM qsy_texto_notificaciones WHERE id = $id";
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	if($row){
		$texto=$row["texto"];
		$texto=str_replace("{dependencia}", NOMBRE_EMPRESA, $texto);
		$texto=str_replace("{calle}", CALLE_EMPRESA, $texto);
		$texto=str_replace("{num_exterior}", EXT_EMPRESA, $texto);
		$texto=str_replace("{num_interior}", INT_EMPRESA, $texto);
		$texto=str_replace("{colonia}", COL_COMPLETE, $texto);
		$texto=str_replace("{codigopostal}", CP_EMPRESA, $texto);
		$texto=str_replace("{municipio}", MUN_COMPLETE, $texto);
		$texto=str_replace("{estado}", EST_COMPLETE, $texto);
		$texto=str_replace("{telefono_1}", TEL1_EMPRESA, $texto);
		$texto=str_replace("{email}", CORREO1_EMPRESA, $texto);
	}
	return $texto;
}
/* Fin de actualización */

function get_tiempos($conn,$notif,$decl){
	$query="SELECT dias FROM qsy_notificaciones_dias WHERE tipo_decl='$decl' AND tipo_notificacion='$notif'";
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	return $row["dias"];
}
function lista_ejercicios($variable){
	$sql="SELECT distinct(ejercicio) FROM qsy_declaraciones order by ejercicio desc";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="";
		foreach ($val as $key => $row) {
			$extra="";
			if($row["ejercicio"]==$variable){$extra=" selected";}
			$html.="<option value='".$row["ejercicio"]."' $extra>".$row["ejercicio"]."</option>";
		}
		echo $html;
	}
}
/* 21-08-2020 DMQ-Qualsys Modificación de listas desplegables.*/
function lista_dependencias(){
	$sql="SELECT dependencia,descr FROM qsy_dependencias WHERE estatus='A' order by principal desc,otra desc,descr";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	$dep="<option value=''>::</option>";
	if($val){
		foreach ($val as $key => $row) {
			$dep.="<option value='".$row["dependencia"]."'>".$row["descr"]."</option>";
		}
	}
	echo $dep;
}
function lista_areas(){
	$sql="SELECT area_adscripcion,descr FROM qsy_areas_adscripcion WHERE estatus='A' order by descr";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	$html="<option value=''>::</option>";
	if($val){
		foreach ($val as $key => $row) {
			$html.="<option value='".$row["area_adscripcion"]."'>".$row["descr"]."</option>";
		}
	}
	echo $html;
}
function lista_puestos(){
	/*28-08-2020 DMQ-Qualsys Cambio en consulta para no mostrar puestos duplicados con el mismo id. */
	$sql="SELECT id,max(puesto) from qsy_puestos group by id order by max(puesto)";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	$html="<option value=''>::</option>";
	if($val){
		foreach ($val as $key => $row) {
			$id=$row["id"];
			$sql="SELECT * from qsy_puestos where id=$id and estatus='A' and fecha_efec  <= CURRENT_DATE order by fecha_efec desc limit 1";
			$result=pg_query($sql);
			$val=pg_fetch_array($result);
			if($val){
				$html.="<option value='".$val["id"]."'>".$val["puesto"] ." - ". $val["descr"]."</option>";
			}
		}
	}
	echo $html;
	/*Fin de actualización.*/
}
/* Fin de actualización */
function datos_declarante($rfc,$conn){
	$query="SELECT * FROM qsy_rh_empleados WHERE rfc like '%".$rfc."%'";
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	$datos=array(
			"rfc"=>$row["rfc"],
			"nombre"=>$row["nombre"],
			"apaterno"=>$row["primer_ap"],
			"amaterno"=>$row["segundo_ap"]
		);

	return $datos;
}
function lista_valores($campo){
	$campo=mb_strtoupper($campo);
	$sql="SELECT valor,descr FROM qsy_listas_valores WHERE campo like '$campo'";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="<option value=''>::</option>";
		foreach ($val as $key => $row) {
			# code...
			$html.="<option value='".$row["valor"]."'>".$row["descr"]."</option>";
		}
		echo $html;
	}
}

function actualizacion_usuario($conn,$rfc,$correo1,$correo2,$tel1,$tel2){
	$query="UPDATE qsy_rh_empleados SET email_institucional='$correo1',email_personal='$correo2',tel_casa='$tel1',celular_personal='$tel2' WHERE rfc like '%".$rfc."%'";
	$result=pg_query($conn,$query);
}

function datos_empleado($rfc,$conn){
	$query="SELECT * FROM qsy_rh_empleados WHERE rfc like '%".$rfc."%'";
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	$nombre=$row["nombre"];
	$apellido1=$row["primer_ap"];
	$apellido2=$row["segundo_ap"];
	$curp=$row["curp"];
	$correo1=$row["email_institucional"];
	$correo2=$row["email_personal"];
	$tel1=$row["tel_casa"];
	$tel2=$row["celular_personal"];
	$ecivil=$row["estado_civil"];
	$pais_nac=$row["pais_nacimiento"];
	$nacionalidad=$row["nacionalidad"];
	$sexo=$row["sexo"];
	$fecha_nac=$row["fecha_nacimiento"];
	$fecha_contra=$row["fecha_contrata"];
	$fecha_baja=$row["fecha_baja"];
	$emp_num=$row["emp_num"];
	$tipo_empleado=$row["tipo_empleado"];

	$calle="";
	$num_exterior="";
	$num_interior="";
	$colonia="";
	$colonia_descr="";
	$municipio="";
	$estado="";
	$estado_descr="";
	$pais="";
	$codigopostal="";
	$query="SELECT * FROM qsy_rh_direcciones WHERE rfc like '%".$rfc."%'";
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	if($row){
		$calle=$row["calle"];
		$num_exterior=$row["num_exterior"];
		$num_interior=$row["num_interior"];
		$colonia=$row["colonia"];
		$colonia_descr=$row["colonia_descr"];
		$municipio=$row["municipio"];
		$estado=$row["estado"];
		$estado_descr=$row["estado_descr"];
		$pais=$row["pais"];
		$codigopostal=$row["codigopostal"];
	}
	//print_r($nombre);die;
	$arreglo=array(
		"rfc"=>$rfc,
		"nombre" => $nombre,
		"apellido1"=> $apellido1,
		"apellido2"=>$apellido2,
		"curp"=>$curp,
		"correo1"=>$correo1,
		"correo2"=>$correo2,
		"tel1"=>$tel1,
		"tel2"=>$tel2,
		"ecivil"=>$ecivil,
		"pais_nac"=>$pais_nac,
		"nacionalidad"=>$nacionalidad,
		"sexo"=>$sexo,
		"fecha_nac"=>$fecha_nac,
		"fecha_contra"=>$fecha_contra,
		"fecha_baja"=>$fecha_baja,
		"emp_num"=>$emp_num,
		"tipo_empleado"=>$tipo_empleado,
		"calle"=>$calle,
		"num_exterior"=>$num_exterior,
		"num_interior"=>$num_interior,
		"colonia"=>$colonia,
		"colonia_descr"=>$colonia_descr,
		"municipio"=>$municipio,
		"estado"=>$estado,
		"estado_descr"=>$estado_descr,
		"pais"=>$pais_nac,
		"codigopostal"=>$codigopostal
		);
	return $arreglo;
}
function datos_empleo($rfc,$conn){
	$orden_id="";
	$ambito_id="";
	$dependencia="";
	$area_adscripcion="";
	$nivel_empleo="";
	$id_puesto="";
	$funcion_principal="";
	$fecha_inicio="";
	$tel_oficina="";
	$extension="";
	$ubicacion="";
	$calle="";
	$num_exterior="";
	$num_interior="";
	$colonia="";
	$municipio="";
	$estado="";
	$pais="";
	$codigopostal="";

	$query="SELECT * FROM qsy_rh_empleos WHERE rfc like '%".$rfc."%'";
	$result=pg_query($conn,$query);
	$row=pg_fetch_assoc($result);
	if($row){
		$orden_id=$row["orden_id"];
		$ambito_id=$row["ambito_id"];
		$dependencia=$row["dependencia"];
		$area_adscripcion=$row["area_adscripcion"];
		$nivel_empleo=$row["nivel_empleo"];
		$id_puesto=$row["id_puesto"];
		$funcion_principal=$row["funcion_principal"];
		$fecha_inicio=$row["fecha_inicio"];
		$tel_oficina=$row["tel_oficina"];
		$extension=$row["extension"];
		$ubicacion=$row["ubicacion"];
		$calle=$row["calle"];
		$num_exterior=$row["num_exterior"];
		$num_interior=$row["num_interior"];
		$colonia=$row["colonia"];
		$municipio=$row["municipio"];
		$estado=$row["estado"];
		$pais=$row["pais"];
		$codigopostal=$row["codigopostal"];
	}

	$arreglo=array(
		"orden_id"=>$orden_id,
		"ambito_id" => $ambito_id,
		"dependencia"=> $dependencia,
		"area_adscripcion"=>$area_adscripcion,
		"nivel_empleo"=>$nivel_empleo,
		"id_puesto"=>$id_puesto,
		"funcion_principal"=>$funcion_principal,
		"fecha_inicio"=>$fecha_inicio,
		"tel_oficina"=>$tel_oficina,
		"extension"=>$extension,
		"ubicacion"=>$ubicacion,
		"calle"=>$calle,
		"num_exterior"=>$num_exterior,
		"num_interior"=>$num_interior,
		"colonia"=>$colonia,
		"municipio"=>$municipio,
		"estado"=>$estado,
		"pais"=>$pais,
		"codigopostal"=>$codigopostal
		);
	return $arreglo;
}


function setDeclaracion($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND tipo_decl='$dec' AND declaracion='P'";
	$result=pg_query($conn,$sql) or header("Location:declaracion-patrimonial.php");
	$row=pg_fetch_row($result);
    if($row){
    	if($row[0]=='P' || $row[0]=='X'){
    		$_SESSION["ejercicio"]=$ejercicio;
    		$_SESSION["tipo_declaracion"]=$dec;
    		$_SESSION["declaracion"]="P";
    		header("Location:declaracion-presentada.php");
    	}
    }
    else{
		$date = date('Y-m-d', time());
		//se debe hacer una comparación con la fecha límite de presentación de declaración para definir si se inserta como E ó O (P y X no aplican, son al momento del envío final)
		if($dec=="I"){
			$sql="SELECT fecha_contrata FROM qsy_rh_empleados WHERE rfc='$rfc'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){
				$fecha=date("Y-m-d");
				$fecha_actual = date_create($fecha);
				$fecha_contrata = date_create($val["fecha_contrata"]);
				$intervalo = date_diff($fecha_contrata, $fecha_actual);
				if($intervalo->m >= 1){
					$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','O')";
				}
				else{
					$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','E')";
				}
			}
		}
		if($dec=="M"){
			$sql="SELECT fecha_contrata FROM qsy_rh_empleados WHERE rfc='$rfc'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){
				$fecha_contrata = date_create($val["fecha_contrata"]);
				$fecha_actual=date("Y-m-d");
				$mes_actual = date("m",strtotime($fecha_actual));

				$anio_contrata = date("Y",strtotime($val["fecha_contrata"]));
				$anio_actual = date("Y",strtotime($fecha_actual));
				if($anio_contrata == $anio_actual){
				//No se puede hacer dec de modificación el mismo año que se contrata
	    		//header("Location:declaracion-patrimonial.php"); //descomentar en produccion
				$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','E')";//comentar en producción
				}
				else{
					if($mes_actual == '05' && $anio_actual==$ejercicio){
						$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','E')";
					}
					else{
						$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','O')";
					}
					//print_r($sql);
				}
			}
		}
		if($dec=="C"){
			$sql="SELECT fecha_baja FROM qsy_rh_empleados WHERE rfc='$rfc'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){
				$fecha_actual=date("Y-m-d");
				$fecha_actual = date_create($fecha_actual);
				$fecha_baja = date_create($val["fecha_baja"]);
				$intervalo = date_diff($fecha_baja, $fecha_actual);
				if($intervalo->m >= 1){
						$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','O')";
				}
				else{
						$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','E')";
				}
				//print_r($sql);
			}
		}
    	//$sql="INSERT INTO qsy_declaraciones VALUES ('P',$ejercicio,'$date','$rfc','$dec','E')";
		$result=pg_query($conn,$sql);
    }
}
function setDeclaracionI($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND tipo_decl='$dec' AND declaracion='I'";
	$result=pg_query($conn,$sql) or header("Location:declaracion-intereses.php");
	$row=pg_fetch_row($result);
    if($row){
    	if($row[0]=='P' || $row[0]=='X'){
    		$_SESSION["ejercicio"]=$ejercicio;
    		$_SESSION["tipo_declaracion"]=$dec;
    		$_SESSION["declaracion"]="I";
    		header("Location:declaracion-presentada.php");
    	}
    }
    else{
		$date = date('Y-m-d', time());
		$sql="SELECT conformidad FROM qsy_declaraciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND tipo_decl='$dec' AND declaracion='P'";
		$result=pg_query($conn,$sql);
		$row=pg_fetch_row($result);
	    if($row) $conformidad=$row[0];
	    else $conformidad="N";
		if($dec=="I"){
			$sql="SELECT fecha_contrata FROM qsy_rh_empleados WHERE rfc='$rfc'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){
				$fecha=date("Y-m-d");
				$fecha_actual = date_create($fecha);
				$fecha_contrata = date_create($val["fecha_contrata"]);
				$intervalo = date_diff($fecha_contrata, $fecha_actual);
				if($intervalo->m >= 1){
					$sql="INSERT INTO qsy_declaraciones VALUES ('I',$ejercicio,'$date','$rfc','$dec','O',null,'$conformidad')";
				}
				else{
					$sql="INSERT INTO qsy_declaraciones VALUES ('I',$ejercicio,'$date','$rfc','$dec','E',null,'$conformidad')";
				}
			}
		}
		if($dec=="M"){
			$sql="SELECT fecha_contrata FROM qsy_rh_empleados WHERE rfc='$rfc'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){
				$fecha_contrata = date_create($val["fecha_contrata"]);
				$fecha_actual=date("Y-m-d");
				$mes_actual = date("m",strtotime($fecha_actual));

				$anio_contrata = date("Y",strtotime($val["fecha_contrata"]));
				$anio_actual = date("Y",strtotime($fecha_actual));
				if($anio_contrata == $anio_actual){
				//No se puede hacer dec de modificación el mismo año que se contrata
		    		header("Location:declaracion-intereses.php");
				}
				else{
					if($mes_actual == '05' && $anio_actual==$ejercicio){
						$sql="INSERT INTO qsy_declaraciones VALUES ('I',$ejercicio,'$date','$rfc','$dec','E',null,'$conformidad')";
					}
					else{
						$sql="INSERT INTO qsy_declaraciones VALUES ('I',$ejercicio,'$date','$rfc','$dec','O',null,'$conformidad')";
					}
					//print_r($sql);
				}
			}
		}
		if($dec=="C"){
			$sql="SELECT fecha_baja FROM qsy_rh_empleados WHERE rfc='$rfc'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){
				$fecha_actual=date("Y-m-d");
				$fecha_actual = date_create($fecha_actual);
				$fecha_baja = date_create($val["fecha_baja"]);
				$intervalo = date_diff($fecha_baja, $fecha_actual);
				if($intervalo->m >= 1){
						$sql="INSERT INTO qsy_declaraciones VALUES ('I',$ejercicio,'$date','$rfc','$dec','O',null,'$conformidad')";
				}
				else{
						$sql="INSERT INTO qsy_declaraciones VALUES ('I',$ejercicio,'$date','$rfc','$dec','E',null,'$conformidad')";
				}
				//print_r($sql);
			}
		}
		$result=pg_query($conn,$sql);
    }
}
function get_descr($conn,$valor,$descr,$tabla,$campo){
	$query="SELECT $descr from $tabla where $campo='$valor'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		return $val[0];
	}
	return $valor;
}
/*28-08-2020 DMQ-Qualsys Cambio en obtención de puesto para inserción en tablas de formularios. */
function get_descr_puesto($conn,$id){
	$query="SELECT descr from qsy_puestos where id=$id and estatus='A' and fecha_efec <= CURRENT_DATE order by fecha_efec desc limit 1";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		return $val[0];
	}
	return $valor;
}
/* Fin de actualización.*/
function get_descr_lista($conn,$valor,$descr,$tabla,$valor_campo){
	$valor_campo=mb_strtoupper($valor_campo);
	$query="SELECT $descr from $tabla where campo='$valor_campo' AND valor='$valor'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		return $val[0];
	}
	return $valor;
}
function get_valor_lista($conn,$descr,$valor,$tabla,$valor_campo){
	$valor_campo=mb_strtoupper($valor_campo);
	$query="SELECT $valor from $tabla where campo='$valor_campo' AND descr='$descr'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		return $val[0];
	}
	return "";
}
function get_colonia($conn,$colonia,$cp){
	$query="SELECT descr from qsy_colonias where codigo_postal='$cp' and colonia='$colonia'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		return $val[0];
	}
	return "";
}
function get_municipio($conn,$municipio,$estado){
	$query="SELECT descr from qsy_municipios where municipio='$municipio' and estado='$estado'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		return $val[0];
	}
	return "";
}

function get_empleo($conn,$rfc,$ejercicio,$tipo_decl,$declaracion){
	$query="SELECT puesto_descr from qsy_comision_actual where rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='P' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
			return $val[0];
	}
	return 0;
}
function get_area($conn,$rfc,$ejercicio,$tipo_decl,$declaracion){
	$query="SELECT area_descr from qsy_comision_actual where rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='P' AND tipo_decl='$tipo_decl'";
	//$query="SELECT b.descr from qsy_rh_empleos a,qsy_areas_adscripcion b where a.rfc='$rfc' AND a.area_adscripcion=b.area_adscripcion";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
			return $val[0];
	}
	return 0;
}
function get_sexo($conn,$rfc){
	$query="SELECT sexo from qsy_rh_empleados where rfc='$rfc'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
			return $val[0];
	}
	return 0;
}
function get_nombre($conn,$rfc){
	$query="SELECT nombre,primer_ap,segundo_ap from qsy_rh_empleados where rfc='$rfc'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
			return $val[1]." ".$val[2]." ".$val[0];
	}
	return 0;
}
function get_folio($conn,$rfc,$ejercicio,$tipo_decl,$declaracion){
	$query="SELECT folio_acuse from qsy_declaraciones where rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$declaracion' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
			return $val[0];
	}
	return 0;
}

/*28-08-2020 DMQ-Qualsys Mostrar tipo de notificación.*/
function get_avisos(){
	$sql="SELECT * FROM qsy_texto_notificaciones order by id";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	$arreglo=array();
	if($val){
		foreach ($val as $key => $row) {
			$arreglo[$key]=array(
				"id"=>$row["id"],
				"tipo"=>$row["tipo_notif"],
				"nombre"=>$row["nombre_notif"],
				"texto"=>$row["texto"]
			);
		}
	}
	return $arreglo;
}
/*Fin de actualización. */

function paises(){
	$sql="SELECT pais,descr FROM qsy_paises WHERE estatus='A' order by pais='MEX' desc,descr asc";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="<option value=''>::</option>";
		foreach ($val as $key => $row) {
			# code...
			$html.="<option value='".$row["pais"]."'>".$row["descr"]."</option>";
		}
		echo $html;
	}
}
function nacionalidades(){
	$sql="SELECT nacionalidad,descr FROM qsy_nacionalidades WHERE estatus='A' order by descr='MEXICANA' desc,descr asc";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="<option value=''>::</option>";
		foreach ($val as $key => $row) {
			# code...
			$html.="<option value='".$row["nacionalidad"]."'>".$row["descr"]."</option>";
		}
		echo $html;
	}
}
function entidades(){
	$sql="SELECT estado,descr FROM qsy_estados WHERE estatus='A' order by descr";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="<option value=''>::</option>";
		foreach ($val as $key => $row) {
			# code...
			$html.="<option value='".$row["estado"]."'>".$row["descr"]."</option>";
		}
		echo $html;
	}
}

function monedas(){
	$sql="SELECT moneda,descr FROM qsy_monedas WHERE estatus='A' order by moneda='MXN' desc,descr asc";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="<option value=''>::</option>";
		foreach ($val as $key => $row) {
			# code...
			$html.="<option value='".$row["moneda"]."'>".$row["descr"]."</option>";
		}
		echo $html;
	}
}

function getstatus($status,$anterior,$dec){
    switch($status){
        case 'X':
            $newstatus=HTTP_PATH."/images/ico-correcto.png";
            break;
        case 'P':
            $newstatus=HTTP_PATH."/images/ico-correcto.png";
            break;
        case 'N':
            $newstatus=HTTP_PATH."/images/ico-blanco.png";
            break;
        default:
            $newstatus=$anterior;
            break;
    }
    return $newstatus;
}

function aviso_notificaciones($conn,$rfc,$dec){
	/* 21-08-2020 DMQ-Qualsys Cambio de consulta para no mostrar aquellos usuarios que no declaran. */
	$sql="SELECT a.fecha_contrata,a.fecha_baja FROM qsy_rh_empleados a,qsy_rh_empleos b,qsy_puestos c WHERE a.rfc=b.rfc AND b.id_puesto=c.id AND a.rfc='$rfc' AND c.declaracion!='N'";
	/* Fin de actualización */
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	$counter=0;
	if($val){
		$fecha_contrata=$val["fecha_contrata"];
		$fecha_baja=$val["fecha_baja"];
		$fecha_actual=date("Y-m-d");
		if($fecha_contrata!=""){
			$fecha_limite_c = strtotime ('+60 day',strtotime($fecha_contrata)) ;
			$fecha_limite_c = date ( 'Y-m-d' , $fecha_limite_c );
			$anio_c=date("Y",strtotime($fecha_contrata));
		}
		else{
			$fecha_limite_c ="";
			$anio_c=0;
		}
		if($fecha_baja!=""){
			$fecha_limite_b = strtotime ('+60 day',strtotime($fecha_baja)) ;
			$fecha_limite_b = date ( 'Y-m-d' , $fecha_limite_b );
			$anio_b=date("Y",strtotime($fecha_baja));
		}
		else{
			$fecha_limite_b ="";
			$anio_b=0;
		}
		$html="";

		/*31-08-2020 DMQ-Qualsys Cambio en notificaciones para puestos según fecha efectiva. */
		if($fecha_contrata <= $fecha_actual && $anio_c>0 /* && $fecha_actual <= $fecha_limite_c*/){
			$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='I' AND ejercicio=$anio_c AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_all($result);
			if($val){

			}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$counter++;
					}
				}
			}
		}
		$mes_actual=date("m");
		$anio_actual=date("Y");
		if($anio_c>0){
			for($i=($anio_c+1);$i<=$anio_actual;$i++){
				if((($mes_actual >= "05" && $anio_actual-$i==0) || $anio_actual>$i) && ($fecha_baja=="" || "$i-05-01" < $fecha_baja)){
					$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='M' AND ejercicio=$i AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
					$result=pg_query($conn,$sql);
					$val=pg_fetch_assoc($result);
					if($val){}
					else{
						$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$i-05-31' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
						$result2=pg_query($conn,$sql);
						$val2=pg_fetch_assoc($result2);
						if($val2){
							$declara_completo=$val2["declaracion"];
							if($declara_completo=="N"){}
							else{
								$counter++;
							}
						}
					}
				}
			}
		}
		if($fecha_baja <= $fecha_actual && $anio_b>0/* && $fecha_actual <= $fecha_limite_b*/){
			$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='C' AND ejercicio=$anio_b AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_baja' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$counter++;
					}
				}
			}
		}
		/* Fin de actualización. */
	}
	return $counter;
}
function notificar_declaraciones($conn,$rfc,$dec){
	if($dec=="P")$declaracion="Patrimonial";
	if($dec=="I")$declaracion="de Intereses";
	/* 21-08-2020 DMQ-Qualsys Cambio de consulta para no mostrar aquellos usuarios que no declaran. */
	$sql="SELECT a.fecha_contrata,a.fecha_baja FROM qsy_rh_empleados a,qsy_rh_empleos b,qsy_puestos c WHERE a.rfc=b.rfc AND b.id_puesto=c.id AND a.rfc='$rfc' AND c.declaracion!='N'";
	/* Fin de actualización */
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	if($val){
		$fecha=date("Y-m-d");
		$fecha_actual_1 = date_create($fecha);
		$fecha_contrata = date_create($val["fecha_contrata"]);
		$intervalo = date_diff($fecha_contrata, $fecha_actual_1);
		$valorI=60 - $intervalo->format('%a');

		$fecha_baja = date_create($val["fecha_baja"]);
		$intervalo = date_diff($fecha_baja, $fecha_actual_1);
		$valorC=60 - $intervalo->format('%a');

		$fecha_contrata=$val["fecha_contrata"];
		$fecha_baja=$val["fecha_baja"];
		$fecha_actual=date("Y-m-d");
		if($fecha_contrata!=""){
			$fecha_limite_c = strtotime ('+60 day',strtotime($fecha_contrata)) ;
			$fecha_limite_c = date ( 'Y-m-d' , $fecha_limite_c );
			$anio_c=date("Y",strtotime($fecha_contrata));
		}
		else{
			$fecha_limite_c ="";
			$anio_c=0;
		}
		if($fecha_baja!=""){
			$fecha_limite_b = strtotime ('+60 day',strtotime($fecha_baja)) ;
			$fecha_limite_b = date ( 'Y-m-d' , $fecha_limite_b );
			$anio_b=date("Y",strtotime($fecha_baja));
		}
		else{
			$fecha_limite_b ="";
			$anio_b=0;
		}
		$sql="SELECT texto FROM qsy_texto_notificaciones WHERE id=2";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_assoc($result);
		$texto=$val["texto"];
		$html="";
		if($fecha_contrata <= $fecha_actual && $anio_c>0 /* && $fecha_actual <= $fecha_limite_c*/){
			if($fecha_actual <= $fecha_limite_c){
				$varI=str_replace("{total}", $valorI, $texto);
				$varI=str_replace("{declaracion}", "Inicio", $varI);
				$varI=str_replace("{dec}", $declaracion, $varI);
			}
			else {
				$sql="SELECT texto FROM qsy_texto_notificaciones WHERE id=3";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_assoc($result);
				$texto2=$val["texto"];
				$varI=str_replace("{declaracion}","Inicio",$texto2);
				$varI=str_replace("{dias}", abs($valorI), $varI);
				$varI=str_replace("{dec}", $declaracion, $varI);
				$varI=str_replace("{ejercicio}", $anio_c, $varI);
			}
			/*31-08-2020 DMQ-Qualsys Cambio en notificaciones para puestos según fecha efectiva. */
			$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='I' AND ejercicio=$anio_c AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_all($result);
			if($val){

			}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$html.="<p class='notificacion-p'>$varI</p>";
					}
				}
			}
			/* Fin de actualización. */
		}
		$mes_actual=date("m");
		$anio_actual=date("Y");
		if($anio_c>0){
			for($i=($anio_c+1);$i<=$anio_actual;$i++){
				$fecha_m=date("$i-05-31");
				$fecha_limite_m=date_create($fecha_m);
				$intervalo = date_diff($fecha_limite_m, $fecha_actual_1);
				$valorM=$intervalo->format('%a');
				if($fecha_limite_m>=$fecha_actual_1){
				$varM=str_replace("{total}", $valorM, $texto);
				$varM=str_replace("{declaracion}", "Modificación", $varM);
				$varM=str_replace("{dec}", $declaracion, $varM);
				}
				else{
				$sql="SELECT texto FROM qsy_texto_notificaciones WHERE id=3";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_assoc($result);
				$texto2=$val["texto"];
				$varM=str_replace("{declaracion}","Modificación",$texto2);
				$varM=str_replace("{dias}", abs($valorM), $varM);
				$varM=str_replace("{dec}", $declaracion, $varM);
				$varM=str_replace("{ejercicio}", $i, $varM);
				}
				/*31-08-2020 DMQ-Qualsys Cambio en notificaciones para puestos según fecha efectiva. */
				if((($mes_actual >= "05" && $anio_actual-$i==0) || $anio_actual>$i) && ($fecha_baja=="" || "$i-05-01" < $fecha_baja)){
					$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='M' AND ejercicio=$i AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
					$result=pg_query($conn,$sql);
					$val=pg_fetch_assoc($result);
					if($val){}
					else{
						$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$i-05-31' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
						$result2=pg_query($conn,$sql);
						$val2=pg_fetch_assoc($result2);
						if($val2){
							$declara_completo=$val2["declaracion"];
							if($declara_completo=="N"){}
							else{
								$html.="<p class='notificacion-p'>$varM</p>";
							}
						}
					}
				}
				/* Fin de actualización. */
			}
		}
		else{
		}
		if($fecha_baja <= $fecha_actual && $anio_b>0/* && $fecha_actual <= $fecha_limite_b*/){
			if($fecha_actual <= $fecha_limite_b){
				$varC=str_replace("{total}", $valorC, $texto);
				$varC=str_replace("{declaracion}", "Conclusión", $varC);
				$varC=str_replace("{dec}", $declaracion, $varC);
			}
			else{
				$sql="SELECT texto FROM qsy_texto_notificaciones WHERE id=3";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_assoc($result);
				$texto2=$val["texto"];
				$varC=str_replace("{declaracion}","Conclusión",$texto2);
				$varC=str_replace("{dias}", abs($valorC), $varC);
				$varC=str_replace("{dec}", $declaracion, $varC);
				$varC=str_replace("{ejercicio}", $anio_b, $varC);

			} 
			/*31-08-2020 DMQ-Qualsys Cambio en notificaciones para puestos según fecha efectiva. */
			$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='C' AND ejercicio=$anio_b AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){}
			else{
				$sql="";
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_baja' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
					$html.="<p class='notificacion-p'>$varC</p>";
					}
				}
			}
			/* Fin de actualización. */
		}
	echo $html;
	}
}
function declaracion_a_presentar($conn,$rfc,$ejercicio,$tipo_decl,$dec,$completo){
	if($dec=="P")$declaracion="PATRIMONIAL";
	if($dec=="I")$declaracion="INTERESES";
	if($tipo_decl=="I")$tipo_declaracion="INICIAL";
	if($tipo_decl=="M")$tipo_declaracion="MODIFICACIÓN";
	if($tipo_decl=="C")$tipo_declaracion="CONCLUSIÓN";
	if($dec=="P")$link="declaracion_patrimonial.php";
	else $link="reporte_declaracion_intereses.php";
	$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	if($val){
		/* 21-08-2020 DMQ-Qualsys Se cambió el ícono de Revisar */
		$html=
		"<tr>
			<td><span>".$ejercicio."</span></td>
			<td><span>".$declaracion."</span></td>
			<td><span>".$tipo_declaracion."</span></td>
			<td style='text-align: center;'>
                <form action='".HTTP_PATH."/views/reportes/$link' method='POST' target='_blank'>
                <input type='hidden' name='rfc' value='".$rfc."'>
                <input type='hidden' name='declaracion' value='".$dec."'>
                <input type='hidden' name='tipo_decl' value='".$tipo_decl."'>
                <input type='hidden' name='ejercicio' value='".$ejercicio."'>
                <input type='hidden' name='declara_completo' value='".$completo."'>
				<button type='submit' class='iniciar boton-oculto' style='margin:2% 0;' title='Revisar'><img src='".HTTP_PATH."/images/revisar.png' style='height: 20px;'></button>
                </form>
			</td>
			<td style='text-align: center;'>
			<form action='#' method='POST' id='comprobar'>
				<input type='hidden' name='comprobacion' value='1'>
                <input type='hidden' name='rfc' value='".$rfc."'>
                <input type='hidden' name='declaracion' value='".$tipo_decl."'>
                <input type='hidden' name='tipo-declaracion' value='".$dec."'>
                <input type='hidden' name='ejercicio' value='".$ejercicio."'>
                <input type='hidden' name='declara_completo' value='".$completo."'>
				<center>
					<button type='button' id='finalizar'>Enviar</button>
				</center>
                </form>
			</td>
		</tr>";
		/* Fin de actualización */
		print_r($html);
	}
}

function declaraciones_pendientes($conn,$rfc,$dec){
	$sql="SELECT fecha_contrata,fecha_baja FROM qsy_rh_empleados WHERE rfc='$rfc'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	if($val){
		$fecha_contrata=$val["fecha_contrata"];
		$fecha_baja=$val["fecha_baja"];
		$fecha_actual=date("Y-m-d");
		if($fecha_contrata!=""){
			$fecha_limite_c = strtotime ('+60 day',strtotime($fecha_contrata)) ;
			$fecha_limite_c = date ( 'Y-m-d' , $fecha_limite_c );
			$anio_c=date("Y",strtotime($fecha_contrata));
		}
		else{
			$fecha_limite_c ="";
			$anio_c=0;
		}
		if($fecha_baja!=""){
			$fecha_limite_b = strtotime ('+60 day',strtotime($fecha_baja)) ;
			$fecha_limite_b = date ( 'Y-m-d' , $fecha_limite_b );
			$anio_b=date("Y",strtotime($fecha_baja));
		}
		else{
			$fecha_limite_b ="";
			$anio_b=0;
		}
		//print_r($anio_c);
		if($dec=="P"){$url="1-datos-generales.php?aviso=1#01";$declaracion="PATRIMONIAL";}
		if($dec=="I"){$url="1-participacion-empresa.php#01";$declaracion="INTERESES";}
		$cabecera="
			<div class='subsubtitle'>Declaraciones pendientes</div>
				<div class='results'>
					<table cellspacing='0' cellpadding='0' style='text-align: center;'>
						<tr>
							<th style='width: 20%;'>Ejercicio</th>
							<th style='width: 20%;'>Declaración</th>
							<th style='width: 20%;'>Tipo de Declaración</th>
							<th style='width: 20%;'>Dec. Imprimible</th>
							<th style='width: 20%;'>Acción</th>
						</tr>";
		$html="";
		if($fecha_contrata <= $fecha_actual && $anio_c>0 /* && $fecha_actual <= $fecha_limite_c*/){
			$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='I' AND ejercicio=$anio_c AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){}
			else{
			/*28-08-2020 DMQ-Qualsys Cambio de declaraciones pendientes según puesto y fecha efectiva.*/
				if($dec=="P")$link="declaracion_patrimonial.php";
				else $link="reporte_declaracion_intereses.php";
				/* 19-08-2020 DMQ-Qualsys Se cambió el tamaño de las columnas */
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
					$html.=
					"<tr>	
						<td><span>".$anio_c."</span></td>
						<td><span>".$declaracion."</span></td>
						<td><span>INICIAL</span></td>
						<td style='text-align: center;'>
		                    <form action='".HTTP_PATH."/views/reportes/$link' method='POST' target='_blank'>
		                    <input type='hidden' name='rfc' value='".$rfc."'>
		                    <input type='hidden' name='declaracion' value='".$dec."'>
		                    <input type='hidden' name='tipo_decl' value='I'>
		                    <input type='hidden' name='ejercicio' value='".$anio_c."'>
		                    <input type='hidden' name='declara_completo' value='".$declara_completo."'>
		                    <button type='submit'  class='boton-oculto' style='margin:2% 0;'><img src='".HTTP_PATH."/images/revisar.png' style='height: 20px;'></button>
		                    </form>
						</td>
						<td style='text-align: center;'>
							<form action='$url' method='POST'>
		                    <input type='hidden' name='rfc' value='".$rfc."'>
		                    <input type='hidden' name='declaracion' value='I'>
		                    <input type='hidden' name='ejercicio' value='".$anio_c."'>
		                    <input type='hidden' name='declara_completo' value='".$declara_completo."'>
							<center>
						";
					/*Fin de actualización*/
						if($dec=="P"){
							$html.="<button class='iniciar'>Iniciar</button>";
						}
						else if($dec=="I"){
							$sql="SELECT * FROM qsy_declaraciones WHERE rfc like '$rfc' AND declaracion='P' AND ejercicio=$anio_c AND tipo_decl='I' AND (estatus_decl='X' OR estatus_decl='P' OR estatus_decl='N')";
							$resultado=pg_query($conn,$sql);
							$valor=pg_fetch_assoc($resultado);
							if($valor){
								$html.="<button class='iniciar'>Iniciar</button>";
							}
							else{
								$html.="<button class='aviso-declaracion' type='button' style='background-color:#666666;cursor:default;'>Iniciar</button>";	
							}
						}
		                $html.="
							</center>
		                    </form>
						</td>
					</tr>";

					}
				}
			}
			/*Fin de actualización.*/
		}
		$mes_actual=date("m");
		//print_r($mes_actual);
		$anio_actual=date("Y");
		//int $i=$anio_c;
		if($anio_c>0){
			for($i=($anio_c+1);$i<=$anio_actual;$i++){		
				if((($mes_actual >= "05" && $anio_actual-$i==0) || $anio_actual>$i) && ($fecha_baja=="" || "$i-05-01" < $fecha_baja)){
					$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='M' AND ejercicio=$i AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
					$result=pg_query($conn,$sql);
					$val=pg_fetch_assoc($result);
					if($val){}
					else{
					/*28-08-2020 DMQ-Qualsys Cambio de declaraciones pendientes según puesto y fecha efectiva.*/
						if($dec=="P")$link="declaracion_patrimonial.php";
						else $link="reporte_declaracion_intereses.php";

						$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$i-05-31' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
						$result2=pg_query($conn,$sql);
						$val2=pg_fetch_assoc($result2);
						if($val2){
							$declara_completo=$val2["declaracion"];
							if($declara_completo=="N"){}
							else{
							/* 19-08-2020 DMQ-Qualsys Se cambió el tamaño de las columnas */
							$html.=
							"<tr>	
								<td><span>".$i."</span></td>
								<td><span>".$declaracion."</span></td>
								<td><span>MODIFICACIÓN</span></td>
								<td style='text-align: center;'>
				                    <form action='".HTTP_PATH."/views/reportes/$link' method='POST' target='_blank'>
				                    <input type='hidden' name='rfc' value='".$rfc."'>
				                    <input type='hidden' name='declaracion' value='".$dec."'>
				                    <input type='hidden' name='tipo_decl' value='M'>
				                    <input type='hidden' name='ejercicio' value='".$i."'>
				                    <input type='hidden' name='declara_completo' value='".$declara_completo."'>
				                    <button type='submit'  class='boton-oculto' style='margin:2% 0;'><img src='".HTTP_PATH."/images/revisar.png' style='height: 20px;'></button>
				                    </form>
								</td>
								<td style='text-align: center;'>
								<form action='$url' method='POST'>
				                    <input type='hidden' name='rfc' value='".$rfc."'>
				                    <input type='hidden' name='declaracion' value='M'>
				                    <input type='hidden' name='ejercicio' value='".$i."'>
				                    <input type='hidden' name='declara_completo' value='".$declara_completo."'>
									<center>
								";
							/*Fin de actualización*/
								if($dec=="P"){
									$html.="<button class='iniciar'>Iniciar</button>";
								}
								else if($dec=="I"){
									$sql="SELECT * FROM qsy_declaraciones WHERE rfc like '$rfc' AND declaracion='P' AND ejercicio=$i AND tipo_decl='M' AND (estatus_decl='X' OR estatus_decl='P' OR estatus_decl='N')";
									$resultado=pg_query($conn,$sql);
									$valor=pg_fetch_assoc($resultado);
									if($valor){
										$html.="<button class='iniciar'>Iniciar</button>";
									}
									else{
										$html.="<button class='aviso-declaracion' type='button' style='background-color:#666666;cursor:default;'>Iniciar</button>";	
									}
								}
				                $html.="
									</center>
		
				                    </form>
								</td>
							</tr>";
							}
						}					
					}
					/*Fin de actualización. */
				}
			}
		}
		else{
			$html.="<span style='color:red;font-weight:bold;'>La fecha de contratación no está configurada.</span>";
		}
		if($fecha_baja <= $fecha_actual && $anio_b>0/* && $fecha_actual <= $fecha_limite_b*/){
			$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='$dec' AND tipo_decl='C' AND ejercicio=$anio_b AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_assoc($result);
			if($val){}
			else{
				if($dec=="P")$link="declaracion_patrimonial.php";
				else $link="reporte_declaracion_intereses.php";
				/*28-08-2020 DMQ-Qualsys Cambio de declaraciones pendientes según puesto y fecha efectiva.*/
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_baja' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						/* 19-08-2020 DMQ-Qualsys Se cambió el tamaño de las columnas */
						$html.=
						"<tr>	
							<td><span>".$anio_b."</span></td>
							<td><span>".$declaracion."</span></td>
							<td><span>CONCLUSIÓN</span></td>
							<td style='text-align: center;'>
			                    <form action='".HTTP_PATH."/views/reportes/$link' method='POST' target='_blank'>
			                    <input type='hidden' name='rfc' value='".$rfc."'>
			                    <input type='hidden' name='declaracion' value='".$dec."'>
			                    <input type='hidden' name='tipo_decl' value='C'>
			                    <input type='hidden' name='ejercicio' value='".$anio_b."'>
			                    <input type='hidden' name='declara_completo' value='".$declara_completo."'>
			                    <button type='submit'  class='boton-oculto' style='margin:2% 0;'><img src='".HTTP_PATH."/images/revisar.png' style='height: 20px;'></button>
			                    </form>
							</td>
							<td style='text-align: center;'>
							<form action='$url' method='POST'>
			                    <input type='hidden' name='rfc' value='".$rfc."'>
			                    <input type='hidden' name='declaracion' value='C'>
			                    <input type='hidden' name='ejercicio' value='".$anio_b."'>
			                    <input type='hidden' name='declara_completo' value='".$declara_completo."'>
								<center>
							";
						/*Fin de actualización*/
							if($dec=="P"){
								$html.="<button class='iniciar'>Iniciar</button>";
							}
							else if($dec=="I"){
								$sql="SELECT * FROM qsy_declaraciones WHERE rfc like '$rfc' AND declaracion='P' AND ejercicio=$anio_b AND tipo_decl='C' AND (estatus_decl='X' OR estatus_decl='P' OR estatus_decl='N')";
								$resultado=pg_query($conn,$sql);
								$valor=pg_fetch_assoc($resultado);
								if($valor){
									$html.="<button class='iniciar'>Iniciar</button>";
								}
								else{
									$html.="<button class='aviso-declaracion' type='button' style='background-color:#666666;cursor:default;'>Iniciar</button>";	
								}
							}
			                $html.="
								</center>
			                    </form>
							</td>
						</tr>";
					}
				}
			/*Fin de actualización*/
			}
		}
		if($html!=""){
			$html=$cabecera.$html."</table></div>";
			echo $html;
		}
	}
}
function declaraciones_presentadas($conn,$rfc,$dec=""){
	if($dec!=""){$extrasql=" AND declaracion='$dec'";}
	else{$extrasql="";}
	$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND (estatus_decl='P' OR estatus_decl='X') $extrasql order by ejercicio desc,declaracion desc";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="";

		foreach ($val as $key => $row) {
			/*04-09-2020 DMQ-Qualsys Cambio para mostrar la declaración correcta según la fecha efectiva del puesto*/
			$sql="SELECT a.declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '".$row["fecha_presenta"]."' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
			$result=pg_query($sql);
			$val2=pg_fetch_assoc($result);
			if($val2)$completa=$val2["declaracion"];
			else $completa="N";

			$declaracion=get_descr_lista($conn,$row["declaracion"],"descr","qsy_listas_valores","Declaracion");
			$tipo_decl=get_descr_lista($conn,$row["tipo_decl"],"descr","qsy_listas_valores","Tipo_Decl");
			if($row["declaracion"]=="P")$link="declaracion_patrimonial.php";
			if($row["declaracion"]=="I")$link="reporte_declaracion_intereses.php";
			
			$html.=
			"<tr>	
				<td><span>".$row["ejercicio"]."</span></td>
				<td><span>".$declaracion."</span></td>
				<td><span>".$tipo_decl."</span></td>
				<td style='text-align: center;'>
                    <form action='".HTTP_PATH."/views/reportes/$link' method='POST' target='_blank'>
                    <input type='hidden' name='rfc' value='".$rfc."'>
                    <input type='hidden' name='declaracion' value='".$row["declaracion"]."'>
                    <input type='hidden' name='tipo_decl' value='".$row["tipo_decl"]."'>
                    <input type='hidden' name='ejercicio' value='".$row["ejercicio"]."'>
                    <input type='hidden' name='declara_completo' value='".$completa."'>
                    <button type='submit'  class='boton-oculto' style='margin:2% 0;'><img src='".HTTP_PATH."/images/descargar.png' style='height: 20px;'></button>
                    </form>
				</td>
				<td style='text-align: center;'>
                    <form action='".HTTP_PATH."/views/reportes/acuse_recibo.php' method='POST' target='_blank'>
                    <input type='hidden' name='rfc' value='".$rfc."'>
                    <input type='hidden' name='declaracion' value='".$row["declaracion"]."'>
                    <input type='hidden' name='tipo_decl' value='".$row["tipo_decl"]."'>
                    <input type='hidden' name='ejercicio' value='".$row["ejercicio"]."'>
                    <button type='submit'  class='boton-oculto' style='margin:2% 0;'><img src='".HTTP_PATH."/images/descargar.png' style='height: 20px;'></button>
                    </form>
				</td>
			</tr>";
			/*Fin de actualización*/

		}
		echo $html;
	}
}
function declaraciones_publicas($conn,$rfc,$dec=""){
	if($dec!=""){$extrasql=" AND declaracion='$dec'";}
	else{$extrasql="";}
	$sql="SELECT * FROM qsy_declaraciones WHERE rfc='$rfc' AND (estatus_decl='P' OR estatus_decl='X') $extrasql order by ejercicio desc,declaracion desc";
	$result=pg_query($sql);
	$val=pg_fetch_all($result);
	if($val){
		$html="";

		foreach ($val as $key => $row) {
			/*04-09-2020 DMQ-Qualsys Cambio para mostrar la declaración correcta según la fecha efectiva del puesto*/
			$sql="SELECT fecha_contrata,fecha_baja FROM qsy_rh_empleados WHERE rfc='$rfc'";
			$result=pg_query($sql);
			$val2=pg_fetch_assoc($result);
		      if($row['tipo_decl']=="I"){
		        $tipo_decl="I";
		        $fecha_declaracion=$val2["fecha_contrata"];
		      }
		      else if($row['tipo_decl']=="M"){
		        $tipo_decl="M";
		        $fecha_declaracion=$row['ejercicio']."-05-31";
		      }
		      else{
		        $tipo_decl="C";
		        $fecha_declaracion=$val2["fecha_baja"];
		      }
			$sql="SELECT a.declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '".$fecha_declaracion."' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
			$result=pg_query($sql);
			$val2=pg_fetch_assoc($result);
			if($val2)$completa=$val2["declaracion"];
			else $completa="N";

			$declaracion=get_descr_lista($conn,$row["declaracion"],"descr","qsy_listas_valores","Declaracion");
			$tipo_decl=get_descr_lista($conn,$row["tipo_decl"],"descr","qsy_listas_valores","Tipo_Decl");
			if($row["declaracion"]=="P")$link="declaracion_patrimonial_vp.php";
			if($row["declaracion"]=="I")$link="declaracionvp_intereses.php";
			$html.=
			"<tr>	
				<td><span>".$row["ejercicio"]."</span></td>
				<td><span>".$declaracion."</span></td>
				<td><span>".$tipo_decl."</span></td>
				<td style='text-align: center;'>
                    <form action='".HTTP_PATH."/views/reportes/$link' method='POST' target='_blank'>
                    <input type='hidden' name='rfc' value='".$rfc."'>
                    <input type='hidden' name='declaracion' value='".$row["declaracion"]."'>
                    <input type='hidden' name='tipo_decl' value='".$row["tipo_decl"]."'>
                    <input type='hidden' name='ejercicio' value='".$row["ejercicio"]."'>
                    <input type='hidden' name='declara_completo' value='".$completa."'>
                    <button type='submit' class='guardar-user-data' style='margin:2% 0;width:80%;'>Descargar</button>
                    </form>
				</td>
			</tr>";

		}
		echo $html;
	}
}
function checkform1($dec,$ejercicio,$rfc,$conn){
	$html="";
	$sql="SELECT * FROM qsy_datos_generales WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND estatus='A'";
	$result=pg_query($conn,$sql);
	$row=pg_fetch_assoc($result);
    if($row){
    	$html="Campos pendientes:<br>";
		if(trim($row["nombre"])==""){$html.="- Nombre<br>";}
		if(trim($row["primer_ap"])==""){$html.="- Primer apellido<br>";}
		if($row["curp"]==""){$html.="- CURP<br>";}
		if(trim($row["email_personal"])==""){$html.="- Correo personal<br>";}
		if($row["tel_casa"]=="" && $row["celular_personal"]==""){$html.="- Un teléfono de contacto<br>";}
		if($row["estado_civil"]==""){$html.="- Estado civil<br>";}
		if($row["pais_nacimiento"]==""){$html.="- País<br>";}
		if($row["nacionalidad"]==""){$html.="- Nacionalidad<br>";}
		if($dec=="M"){
			if($row["servidor_publico"]==""){$html.="- Servidor público<br>";}
		}
		if($html=="Campos pendientes:<br>")$html="";
		if($html==""){
			$sql="UPDATE qsy_datos_generales SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
			$result=pg_query($conn,$sql);
		}
	}
	return $html;
}

function cargarform1($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$nombre="";
	$apaterno="";
	$amaterno="";
	$curp="";
	$rfc_short="";
	$hc="";
	$correo1="";
	$correo2="";
	$tel1="";
	$tel2="";
	$ecivil="";
	$regimen="";
	$otro="";
	$pais="";
	$nacionalidad="";
	$servidor="";
	$observaciones="";
	if($row){
		$sql="SELECT * FROM qsy_datos_generales WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
		$result=pg_query($conn,$sql);
		$row=pg_fetch_row($result);
	    if($row){
			$nombre=$row[4];
			$apaterno=$row[5];
			$amaterno=$row[6];
			$curp=$row[7];
			$rfc_short=substr($rfc,0,10);
			$hc=substr($rfc,10,3);
			$correo1=$row[8];
			$correo2=$row[9];
			$tel1=$row[10];
			$tel2=$row[11];
			$ecivil=$row[12];
			$regimen=$row[13];
			$otro=$row[14];
			$pais=$row[15];
			$nacionalidad=$row[17];
			$servidor=$row[19];
			$observaciones=$row[20];
		}
		else{
			$sql="SELECT * FROM qsy_declaraciones a,qsy_datos_generales b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
		    	$nombre=$row["nombre"];
		    	$apaterno=$row["primer_ap"];
		    	$amaterno=$row["segundo_ap"];
		    	$rfc_short=substr($rfc,0,10);
		    	$hc=substr($rfc,10,3);
		    	$curp=$row["curp"];
		    	$correo1=$row["email_institucional"];
		    	$correo2=$row["email_personal"];
		    	$tel1=$row["tel_casa"];
		    	$tel2=$row["celular_personal"];
		    	$ecivil=$row["estado_civil"];
		    	$regimen=$row["regimen_matri"];
				$otro=$row["otro_regimen"];
		    	$pais=$row["pais_nacimiento"];
		    	$pais_desc=$row["pais_desc"];
		    	$nacionalidad=$row["nacionalidad"];
		    	$nacionalidad_desc=$row["nacionalidad_desc"];
				$servidor=$row["servidor_publico"];
				$observaciones=$row["observaciones"];
		    	
		    	$nombre2=pg_escape_string($row["nombre"]);
		    	$apaterno2=pg_escape_string($row["primer_ap"]);
		    	$amaterno2=pg_escape_string($row["segundo_ap"]);
		    	$curp2=pg_escape_string($row["curp"]);
		    	$correo12=pg_escape_string($row["email_institucional"]);
		    	$correo22=pg_escape_string($row["email_personal"]);
		    	$tel12=pg_escape_string($row["tel_casa"]);
		    	$tel22=pg_escape_string($row["celular_personal"]);
		    	$ecivil2=pg_escape_string($row["estado_civil"]);
		    	$regimen2=pg_escape_string($row["regimen_matri"]);
				$otro2=pg_escape_string($row["otro_regimen"]);
		    	$pais2=pg_escape_string($row["pais_nacimiento"]);
		    	$pais_desc2=pg_escape_string($row["pais_desc"]);
		    	$nacionalidad2=pg_escape_string($row["nacionalidad"]);
		    	$nacionalidad_desc2=pg_escape_string($row["nacionalidad_desc"]);
				$servidor2=pg_escape_string($row["servidor_publico"]);
				$observaciones2=pg_escape_string($row["observaciones"]);

				$sql="INSERT INTO qsy_datos_generales VALUES ('$rfc',$ejercicio,'P','$dec','$nombre2','$apaterno2','$amaterno2','$curp2','$correo12','$correo22','$tel12','$tel22','$ecivil2','$regimen2','$otro2','$pais2','$pais_desc2','$nacionalidad2','$nacionalidad_desc2','$servidor2','$observaciones2','A')";
				$result=pg_query($conn,$sql);
			}
			else{
				$sql="SELECT * FROM qsy_rh_empleados WHERE rfc='$rfc'";
				$result2=pg_query($conn,$sql);
				$row=pg_fetch_row($result2);
			    if($row){
					//print_r($row);die;
			    	$nombre=$row[2];
			    	$apaterno=$row[3];
			    	$amaterno=$row[4];
			    	$rfc_short=substr($rfc,0,10);
			    	$hc=substr($rfc,10,3);
			    	$curp=$row[5];
			    	$correo1=$row[6];
			    	$correo2=$row[7];
			    	$tel1=$row[8];
			    	$tel2=$row[9];
			    	$ecivil=$row[10];
			    	$pais=$row[11];
			    	$nacionalidad=$row[12];
				}
			}
		}
	}
	$arreglo=array(
					"id"=>$rfc,
					"nombre" => $nombre,
					"apellido1"=> $apaterno,
					"apellido2"=>$amaterno,
					"rfc"=>$rfc_short,
					"hc"=>$hc,
					"curp"=>$curp,
					"correo1"=>$correo1,
					"correo2"=>$correo2,
					"tel1"=>$tel1,
					"tel2"=>$tel2,
					"ecivil"=>$ecivil,
					"pais"=>$pais,
					"nacionalidad"=>$nacionalidad,
					"regimen"=>$regimen,
					"otro"=>$otro,
					"pais"=>$pais,
					"nacionalidad"=>$nacionalidad,
					"servidor"=>$servidor,
					"observaciones"=>$observaciones
					);
//	print_r($arreglo);die;
	return $arreglo;
}
function checkform2($dec,$ejercicio,$rfc,$conn){
	$html="";
	$sql="SELECT * FROM qsy_direcciones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND estatus='A'";
	$result=pg_query($conn,$sql);
	$row=pg_fetch_assoc($result);
    if($row){
    	$html="Campos pendientes:<br>";
		if($row["ubicacion"]==""){$html.="- Ubicación<br>";}
		if($row["pais"]==""){$html.="- País<br>";}
		if($row["ubicacion"]=="M"){
			if(trim($row["codigopostal"])==""){$html.="- Código postal<br>";}
			if(trim($row["estado_desc"])==""){$html.="- Estado<br>";}
			if(trim($row["municipio_desc"])==""){$html.="- Municipio<br>";}
			if(trim($row["colonia_desc"])==""){$html.="- Colonia<br>";}
			if(trim($row["calle"])==""){$html.="- Calle<br>";}
			if(trim($row["num_exterior"])==""){$html.="- No. exterior<br>";}
		}
		if($html=="Campos pendientes:<br>")$html="";
		if($html==""){
			$sql="UPDATE qsy_direcciones SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
			$result=pg_query($conn,$sql);
		}
	}
	return $html;
}
function cargarform2($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$ubicacion="";
	$calle="";
	$num_exterior="";
	$num_interior="";
	$colonia="";
	$colonia_desc="";
	$municipio="";
	$municipio_desc="";
	$estado="";
	$estado_desc="";
	$pais="";
	$pais_desc="";
	$cp="";
	$observaciones="";
	if($row){
		$sql="SELECT * FROM qsy_direcciones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
		$result=pg_query($conn,$sql);
		$row=pg_fetch_assoc($result);
	    if($row){
		$ubicacion=$row["ubicacion"];
		$calle=$row["calle"];
		$num_exterior=$row["num_exterior"];
		$num_interior=$row["num_interior"];
		$colonia=$row["colonia"];
		$colonia_desc=$row["colonia_desc"];
		$municipio=$row["municipio"];
		$municipio_desc=$row["municipio_desc"];
		$estado=$row["estado"];
		$estado_desc=$row["estado_desc"];
		$pais=$row["pais"];
		$pais_desc=$row["pais_desc"];
		$cp=$row["codigopostal"];
		$observaciones=$row["observaciones"];

		}
		else{
			$sql="SELECT * FROM qsy_declaraciones a,qsy_direcciones b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ubicacion=$row["ubicacion"];
				$calle=$row["calle"];
				$num_exterior=$row["num_exterior"];
				$num_interior=$row["num_interior"];
				$colonia=$row["colonia"];
				$colonia_desc=$row["colonia_desc"];
				$municipio=$row["municipio"];
				$municipio_desc=$row["municipio_desc"];
				$estado=$row["estado"];
				$estado_desc=$row["estado_desc"];
				$pais=$row["pais"];
				$pais_desc=$row["pais_desc"];
				$cp=$row["codigopostal"];
				$observaciones=$row["observaciones"];

				$ubicacion2=pg_escape_string($row["ubicacion"]);
				$calle2=pg_escape_string($row["calle"]);
				$num_exterior2=pg_escape_string($row["num_exterior"]);
				$num_interior2=pg_escape_string($row["num_interior"]);
				$colonia2=pg_escape_string($row["colonia"]);
				$colonia_desc2=pg_escape_string($row["colonia_desc"]);
				$municipio2=pg_escape_string($row["municipio"]);
				$municipio_desc2=pg_escape_string($row["municipio_desc"]);
				$estado2=pg_escape_string($row["estado"]);
				$estado_desc2=pg_escape_string($row["estado_desc"]);
				$pais2=pg_escape_string($row["pais"]);
				$pais_desc2=pg_escape_string($row["pais_desc"]);
				$cp2=pg_escape_string($row["codigopostal"]);
				$observaciones2=pg_escape_string($row["observaciones"]);

				$sql="INSERT INTO qsy_direcciones VALUES ('$rfc',$ejercicio,'P','$dec','$ubicacion2','$calle2','$num_exterior2','$num_interior2','$colonia2','$colonia_desc2','$municipio2','$municipio_desc2','$estado2','$estado_desc2','$pais2','$pais_desc2','$cp2','$observaciones2','A')";
				$result=pg_query($conn,$sql);
			}
			else{

				$sql="SELECT * FROM qsy_rh_direcciones WHERE rfc='$rfc'";
				$result2=pg_query($conn,$sql);
				$row=pg_fetch_assoc($result2);
			    if($row){
					$calle=$row["calle"];
					$num_exterior=$row["num_exterior"];
					$num_interior=$row["num_interior"];
					$colonia=$row["colonia"];
					$colonia_desc=$row["colonia_descr"];
					$municipio=$row["municipio"];
					$municipio_desc=$row["municipio_descr"];
					$estado=$row["estado"];
					$estado_desc=$row["estado_descr"];
					$pais=$row["pais"];
					$pais_desc=$row["pais_descr"];
					$cp=$row["codigopostal"];
				}
			}
		}
	}
	$arreglo=array(
					"id"=>$rfc,
					"ubicacion" => $ubicacion,
					"calle"=> $calle,
					"num_exterior"=>$num_exterior,
					"num_interior"=>$num_interior,
					"colonia"=>$colonia,
					"colonia_desc"=>$colonia_desc,
					"municipio"=>$municipio,
					"municipio_desc"=>$municipio_desc,
					"estado"=>$estado,
					"estado_desc"=>$estado_desc,
					"pais"=>$pais,
					"pais_desc"=>$pais_desc,
					"cp"=>$cp,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}

function checkform3($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_escolaridades WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["nivel_escolar"]==""){$html[$key].="- Nivel escolar<br>";}
			if($row["institucion"]==""){$html[$key].="- Institución educativa<br>";}
			if($row["estatus_estudio"]==""){$html[$key].="- Estatus<br>";}
			if($row["doc_obtenido"]==""){$html[$key].="- Documento obtenido<br>";}
			if($row["fecha_doc"]==""){$html[$key].="- Fecha de obtención del documento<br>";}
			if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}

			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_escolaridades SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform3($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$nivel_escolar=array();
	$institucion=array();
	$carrera=array();
	$estatus_estudio=array();
	$doc_obtenido=array();
	$fecha_doc=array();
	$ubicacion=array();
	$observaciones=array();
	if($row){
		$sql="SELECT * FROM qsy_escolaridades WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$nivel_escolar[$key]=$row["nivel_escolar"];
				$institucion[$key]=$row["institucion"];
				$carrera[$key]=$row["carrera"];
				$estatus_estudio[$key]=$row["estatus_estudio"];
				$doc_obtenido[$key]=$row["doc_obtenido"];
				$fecha_doc[$key]=$row["fecha_doc"];
				$ubicacion[$key]=$row["ubicacion"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_escolaridades b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_escolaridades WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$nivel_escolar[$key]=$row["nivel_escolar"];
						$institucion[$key]=$row["institucion"];
						$carrera[$key]=$row["carrera"];
						$estatus_estudio[$key]=$row["estatus_estudio"];
						$doc_obtenido[$key]=$row["doc_obtenido"];
						$fecha_doc[$key]=$row["fecha_doc"];
						$ubicacion[$key]=$row["ubicacion"];
						$observaciones[$key]=$row["observaciones"];

						$nivel_escolar2[$key]=pg_escape_string($row["nivel_escolar"]);
						$institucion2[$key]=pg_escape_string($row["institucion"]);
						$carrera2[$key]=pg_escape_string($row["carrera"]);
						$estatus_estudio2[$key]=pg_escape_string($row["estatus_estudio"]);
						$doc_obtenido2[$key]=pg_escape_string($row["doc_obtenido"]);
						$fecha_doc2[$key]=format_date($row["fecha_doc"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_escolaridades VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$nivel_escolar2[$key]','$institucion2[$key]','$carrera2[$key]','$estatus_estudio2[$key]','$doc_obtenido2[$key]',$fecha_doc2[$key],'$ubicacion2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_escolaridades WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>3,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"nivel_escolar"=>$nivel_escolar,
					"institucion"=>$institucion,
					"carrera"=>$carrera,
					"estatus_estudio"=>$estatus_estudio,
					"doc_obtenido"=>$doc_obtenido,
					"fecha_doc"=>$fecha_doc,
					"ubicacion" => $ubicacion,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform4($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_comision_actual WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["orden_id"]==""){$html[$key].="- Orden de gobierno<br>";}
			if($row["ambito_id"]==""){$html[$key].="- Ámbito público<br>";}
			if($row["dependencia"]=="" || ($row["dependencia"]=="999") && $row["dependencia_descr"]==""){$html[$key].="- Ente público<br>";}
			if($row["area_descr"]==""){$html[$key].="- Área<br>";}
			if($row["puesto_descr"]==""){$html[$key].="- Empleo<br>";}
			if($row["nivel_descr"]==""){$html[$key].="- Nivel de empleo<br>";}
			if($row["honorarios"]==""){$html[$key].="- Contratado por honorarios<br>";}
			if($row["funcion_principal"]==""){$html[$key].="- Función principal<br>";}
			if($row["fecha_inicio"]==""){$html[$key].="- Fecha<br>";}
			if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
			if($row["pais"]==""){$html[$key].="- País<br>";}
			if($row["ubicacion"]=="M"){
				if(trim($row["codigopostal"])==""){$html[$key].="- Código postal<br>";}
				if(trim($row["estado_descr"])==""){$html[$key].="- Estado<br>";}
				if(trim($row["municipio_descr"])==""){$html[$key].="- Municipio<br>";}
				if(trim($row["colonia_descr"])==""){$html[$key].="- Colonia<br>";}
				if(trim($row["calle"])==""){$html[$key].="- Calle<br>";}
				if(trim($row["num_exterior"])==""){$html[$key].="- No. exterior<br>";}
			}
			if($dec=="M"){
				if($row["otro_empleo"]==""){$html[$key].="- Otro empleo<br>";}
			}

			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_comision_actual SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform4($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$orden_id=array();
	$orden_descr=array();
	$ambito_id=array();
	$ambito_descr=array();
	$dependencia=array();
	$dependencia_descr=array();
	$area_adscripcion=array();
	$area_descr=array();
	$nivel_empleo=array();
	$nivel_descr=array();
	$otro_empleo=array();
	$puesto=array();
	$puesto_descr=array();
	$honorarios=array();
	$funcion_principal=array();
	$fecha_inicio=array();
	$tel_oficina=array();
	$extension=array();
	$ubicacion=array();
	$calle=array();
	$num_exterior=array();
	$num_interior=array();
	$colonia=array();
	$colonia_desc=array();
	$municipio=array();
	$municipio_desc=array();
	$estado=array();
	$estado_desc=array();
	$pais=array();
	$pais_desc=array();
	$cp=array();
	$observaciones=array();
	if($row){
		$sql="SELECT * FROM qsy_comision_actual WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$orden_id[$key]=$row["orden_id"];
				$orden_descr[$key]=$row["orden_descr"];
				$ambito_id[$key]=$row["ambito_id"];
				$ambito_descr[$key]=$row["ambito_descr"];
				$dependencia[$key]=$row["dependencia"];
				$dependencia_descr[$key]=$row["dependencia_descr"];
				$area_adscripcion[$key]=$row["area_adscripcion"];
				$area_descr[$key]=$row["area_descr"];
				$nivel_empleo[$key]=$row["nivel_empleo"];
				$nivel_descr[$key]=$row["nivel_descr"];
				$otro_empleo[$key]=$row["otro_empleo"];
				$puesto[$key]=$row["id_puesto"];
				$puesto_descr[$key]=$row["puesto_descr"];
				$honorarios[$key]=$row["honorarios"];
				$funcion_principal[$key]=$row["funcion_principal"];
				$fecha_inicio[$key]=$row["fecha_inicio"];
				$tel_oficina[$key]=$row["tel_oficina"];
				$extension[$key]=$row["extension"];
				$ubicacion[$key]=$row["ubicacion"];
				$calle[$key]=$row["calle"];
				$num_exterior[$key]=$row["num_exterior"];
				$num_interior[$key]=$row["num_interior"];
				$colonia[$key]=$row["colonia"];
				$colonia_desc[$key]=$row["colonia_descr"];
				$municipio[$key]=$row["municipio"];
				$municipio_desc[$key]=$row["municipio_descr"];
				$estado[$key]=$row["estado"];
				$estado_desc[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_desc[$key]=$row["pais_descr"];
				$cp[$key]=$row["codigopostal"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_comision_actual b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_comision_actual WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$orden_id[$key]=$row["orden_id"];
						$orden_descr[$key]=$row["orden_descr"];
						$ambito_id[$key]=$row["ambito_id"];
						$ambito_descr[$key]=$row["ambito_descr"];
						$dependencia[$key]=$row["dependencia"];
						$dependencia_descr[$key]=$row["dependencia_descr"];
						$area_adscripcion[$key]=$row["area_adscripcion"];
						$area_descr[$key]=$row["area_descr"];
						$nivel_empleo[$key]=$row["nivel_empleo"];
						$nivel_descr[$key]=$row["nivel_descr"];
						$otro_empleo[$key]=$row["otro_empleo"];
						$puesto[$key]=$row["id_puesto"];
						$puesto_descr[$key]=$row["puesto_descr"];
						$honorarios[$key]=$row["honorarios"];
						$funcion_principal[$key]=$row["funcion_principal"];
						if($dec=="C"){
							$query="SELECT fecha_baja FROM qsy_rh_empleados WHERE rfc like '$rfc'";
							$result2=pg_query($conn,$query);
							$row2=pg_fetch_assoc($result2);
							if($row2){
								$row["fecha_inicio"]=$row2["fecha_baja"];
							}
						}
						$fecha_inicio[$key]=$row["fecha_inicio"];
						$fecha_inicio2[$key]=format_date($row["fecha_inicio"]);
						$tel_oficina[$key]=$row["tel_oficina"];
						$extension[$key]=$row["extension"];
						$ubicacion[$key]=$row["ubicacion"];
						$calle[$key]=$row["calle"];
						$num_exterior[$key]=$row["num_exterior"];
						$num_interior[$key]=$row["num_interior"];
						$colonia[$key]=$row["colonia"];
						$colonia_desc[$key]=$row["colonia_descr"];
						$municipio[$key]=$row["municipio"];
						$municipio_desc[$key]=$row["municipio_descr"];
						$estado[$key]=$row["estado"];
						$estado_desc[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_desc[$key]=$row["pais_descr"];
						$cp[$key]=$row["codigopostal"];
						$observaciones[$key]=$row["observaciones"];

						$orden_id2[$key]=pg_escape_string($row["orden_id"]);
						$orden_descr2[$key]=pg_escape_string($row["orden_descr"]);
						$ambito_id2[$key]=pg_escape_string($row["ambito_id"]);
						$ambito_descr2[$key]=pg_escape_string($row["ambito_descr"]);
						$dependencia2[$key]=pg_escape_string($row["dependencia"]);
						$dependencia_descr2[$key]=pg_escape_string($row["dependencia_descr"]);
						$area_adscripcion2[$key]=pg_escape_string($row["area_adscripcion"]);
						$area_descr2[$key]=pg_escape_string($row["area_descr"]);
						$nivel_empleo2[$key]=pg_escape_string($row["nivel_empleo"]);
						$nivel_descr2[$key]=pg_escape_string($row["nivel_descr"]);
						$otro_empleo2[$key]=pg_escape_string($row["otro_empleo"]);
						$puesto2[$key]=pg_escape_string($row["id_puesto"]);
						$puesto_descr2[$key]=pg_escape_string($row["puesto_descr"]);
						$honorarios2[$key]=pg_escape_string($row["honorarios"]);
						$funcion_principal2[$key]=pg_escape_string($row["funcion_principal"]);
						$tel_oficina2[$key]=pg_escape_string($row["tel_oficina"]);
						$extension2[$key]=pg_escape_string($row["extension"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$calle2[$key]=pg_escape_string($row["calle"]);
						$num_exterior2[$key]=pg_escape_string($row["num_exterior"]);
						$num_interior2[$key]=pg_escape_string($row["num_interior"]);
						$colonia2[$key]=pg_escape_string($row["colonia"]);
						$colonia_desc2[$key]=pg_escape_string($row["colonia_descr"]);
						$municipio2[$key]=pg_escape_string($row["municipio"]);
						$municipio_desc2[$key]=pg_escape_string($row["municipio_descr"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_desc2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_desc2[$key]=pg_escape_string($row["pais_descr"]);
						$cp2[$key]=pg_escape_string($row["codigopostal"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_comision_actual VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$otro_empleo[$key]','$movimiento[$key]','$orden_id2[$key]','$orden_descr2[$key]','$ambito_id2[$key]','$ambito_descr2[$key]','$dependencia2[$key]','$dependencia_descr2[$key]','$area_adscripcion2[$key]','$area_descr2[$key]','$nivel_empleo2[$key]','$nivel_descr2[$key]','$puesto2[$key]','$puesto_descr2[$key]','$honorarios2[$key]','$funcion_principal2[$key]',$fecha_inicio2[$key],'$tel_oficina2[$key]','$extension2[$key]','$ubicacion2[$key]','$calle2[$key]','$num_exterior2[$key]','$num_interior2[$key]','$colonia2[$key]','$colonia_desc2[$key]','$municipio2[$key]','$municipio_desc2[$key]','$estado2[$key]','$estado_desc2[$key]','$pais2[$key]','$pais_desc2[$key]','$cp2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}
			else{
				$sql="SELECT * FROM qsy_rh_empleos WHERE rfc='$rfc'";
				$result2=pg_query($conn,$sql);
				$row=pg_fetch_assoc($result2);
			    if($row){
					$secuencia[0]=1;
					$movimiento[0]="A";
					$orden_id[0]=$row["orden_id"];
					$orden_descr[0]=mb_strtoupper(get_descr_lista($conn,$orden_id[0],"descr","qsy_listas_valores","Orden_ID"));
					$ambito_id[0]=$row["ambito_id"];
					$ambito_descr[0]=mb_strtoupper(get_descr_lista($conn,$ambito_id[0],"descr","qsy_listas_valores","Ambito_ID"));
					$dependencia[0]=$row["dependencia"];
					$dependencia_descr[0]=mb_strtoupper(get_descr($conn,$dependencia[0],"descr","qsy_dependencias","dependencia"));
					$area_adscripcion[0]=$row["area_adscripcion"];
					$area_descr[0]=mb_strtoupper(get_descr($conn,$area_adscripcion[0],"descr","qsy_areas_adscripcion","area_adscripcion"));
					$nivel_empleo[0]="";
					$nivel_descr[0]=$row["nivel_empleo"];
					$otro_empleo[0]="";
					$puesto[0]=$row["id_puesto"];
					$puesto_descr[0]=mb_strtoupper(get_descr($conn,$puesto[0],"descr","qsy_puestos","id"));
					$honorarios[0]="S";
					$funcion_principal[0]=$row["funcion_principal"];
					$fecha_inicio[0]=$row["fecha_inicio"];
					$tel_oficina[0]=$row["tel_oficina"];
					$extension[0]=$row["extension"];
					$ubicacion[0]=$row["ubicacion"];
					$cp[0]=$row["codigopostal"];
					$calle[0]=$row["calle"];
					$num_exterior[0]=$row["num_exterior"];
					$num_interior[0]=$row["num_interior"];
					$estado[0]=$row["estado"];
					$estado_desc[0]=mb_strtoupper(get_descr($conn,$estado[0],"descr","qsy_estados","estado"));
					$municipio[0]=$row["municipio"];
					$municipio_desc[0]=mb_strtoupper(get_municipio($conn,$municipio[0],$estado[0]));
					$colonia[0]=$row["colonia"];
					$colonia_desc[0]=mb_strtoupper(get_colonia($conn,$colonia[0],$cp[0]));
					$pais[0]=$row["pais"];
					$pais_desc=mb_strtoupper(get_descr($conn,$pais[0],"descr","qsy_paises","pais"));
					$observaciones[0]="";
				}
			}
		}
	}

	$sql="SELECT count(*) FROM qsy_comision_actual WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>4,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"orden_id"=>$orden_id,
					"orden_descr"=>$orden_descr,
					"ambito_id"=>$ambito_id,
					"ambito_descr"=>$ambito_descr,
					"dependencia"=>$dependencia,
					"dependencia_descr"=>$dependencia_descr,
					"area_adscripcion"=>$area_adscripcion,
					"area_descr"=>$area_descr,
					"nivel_empleo"=>$nivel_empleo,
					"nivel_descr"=>$nivel_descr,
					"otro_empleo"=>$otro_empleo,
					"puesto"=>$puesto,
					"puesto_descr"=>$puesto_descr,
					"honorarios"=>$honorarios,
					"funcion_principal"=>$funcion_principal,
					"fecha_inicio"=>$fecha_inicio,
					"tel_oficina"=>$tel_oficina,
					"extension"=>$extension,
					"ubicacion"=>$ubicacion,
					"calle"=>$calle,
					"num_exterior"=>$num_exterior,
					"num_interior"=>$num_interior,
					"colonia"=>$colonia,
					"colonia_desc"=>$colonia_desc,
					"municipio"=>$municipio,
					"municipio_desc"=>$municipio_desc,
					"estado"=>$estado,
					"estado_desc"=>$estado_desc,
					"pais"=>$pais,
					"pais_desc"=>$pais_desc,
					"cp"=>$cp,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform5($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_experiencia WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["actividad_laboral"]==""){$html[$key].="- Actividad laboral<br>";}
			if($row["actividad_laboral"]=="N"){}
			if($row["actividad_laboral"]=="U"){
				if($row["orden_id"]==""){$html[$key].="- Orden de gobierno<br>";}
				if($row["ambito_id"]==""){$html[$key].="- Ámbito público<br>";}
				if($row["dependencia"]=="" || ($row["dependencia"]=="999") && $row["dependencia_descr"]==""){$html[$key].="- Ente público<br>";}
				if($row["area_descr"]==""){$html[$key].="- Área<br>";}
				if($row["puesto_descr"]==""){$html[$key].="- Empleo<br>";}
				if($row["funcion_principal"]==""){$html[$key].="- Función principal<br>";}
				if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
				if($row["fecha_fin"]==""){$html[$key].="- Fecha de finalización<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
			}
			if($row["actividad_laboral"]=="V" || $row["actividad_laboral"]=="O"){
				if($row["nombre_empresa"]==""){$html[$key].="- Nombre de la empresa<br>";}
				if($row["sector"]==""){$html[$key].="- Sector<br>";}
				//if($row["rfc_empresa"]==""){$html[$key].="- RFC de la empresa<br>";}
				if($row["area_descr"]==""){$html[$key].="- Área<br>";}
				if($row["puesto_descr"]==""){$html[$key].="- Empleo<br>";}
				if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
				if($row["fecha_fin"]==""){$html[$key].="- Fecha de finalización<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
			}

			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_experiencia SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}

function cargarform5($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$actividad_laboral=array();
	$otra_actividad=array();
	$orden_id=array();
	$orden_descr=array();
	$ambito_id=array();
	$ambito_descr=array();
	$dependencia=array();
	$dependencia_descr=array();
	$area_adscripcion=array();
	$area_descr=array();
	$puesto=array();
	$puesto_descr=array();
	$nombre_empresa=array();
	$rfc_empresa=array();
	$funcion_principal=array();
	$fecha_inicio=array();
	$fecha_fin=array();
	$sector=array();
	$sector_descr=array();
	$otro_sector=array();
	$ubicacion=array();
	$observaciones=array();
	if($row){
		$sql="SELECT * FROM qsy_experiencia WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$actividad_laboral[$key]=$row["actividad_laboral"];
				$otra_actividad[$key]=$row["otra_actividad"];
				$orden_id[$key]=$row["orden_id"];
				$orden_descr[$key]=$row["orden_descr"];
				$ambito_id[$key]=$row["ambito_id"];
				$ambito_descr[$key]=$row["ambito_descr"];
				$dependencia[$key]=$row["dependencia"];
				$dependencia_descr[$key]=$row["dependencia_descr"];
				$area_adscripcion[$key]=$row["area_adscripcion"];
				$area_descr[$key]=$row["area_descr"];
				$puesto[$key]=$row["puesto_id"];
				$puesto_descr[$key]=$row["puesto_descr"];
				$nombre_empresa[$key]=$row["nombre_empresa"];
				$rfc_empresa[$key]=$row["rfc_empresa"];
				$funcion_principal[$key]=$row["funcion_principal"];
				$fecha_inicio[$key]=$row["fecha_inicio"];
				$fecha_fin[$key]=$row["fecha_fin"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$ubicacion[$key]=$row["ubicacion"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_experiencia b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_experiencia WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$actividad_laboral[$key]=$row["actividad_laboral"];
						$otra_actividad[$key]=$row["otra_actividad"];
						$orden_id[$key]=$row["orden_id"];
						$orden_descr[$key]=$row["orden_descr"];
						$ambito_id[$key]=$row["ambito_id"];
						$ambito_descr[$key]=$row["ambito_descr"];
						$dependencia[$key]=$row["dependencia"];
						$dependencia_descr[$key]=$row["dependencia_descr"];
						$area_adscripcion[$key]=$row["area_adscripcion"];
						$area_descr[$key]=$row["area_descr"];
						$puesto[$key]=$row["puesto_id"];
						$puesto_descr[$key]=$row["puesto_descr"];
						$nombre_empresa[$key]=$row["nombre_empresa"];
						$rfc_empresa[$key]=$row["rfc_empresa"];
						$funcion_principal[$key]=$row["funcion_principal"];
						$fecha_inicio[$key]=$row["fecha_inicio"];
						$fecha_fin[$key]=$row["fecha_fin"];
						$fecha_inicio2[$key]=format_date($row["fecha_inicio"]);
						$fecha_fin2[$key]=format_date($row["fecha_fin"]);
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$ubicacion[$key]=$row["ubicacion"];
						$observaciones[$key]=$row["observaciones"];

						$actividad_laboral2[$key]=pg_escape_string($row["actividad_laboral"]);
						$otra_actividad2[$key]=pg_escape_string($row["otra_actividad"]);
						$orden_id2[$key]=pg_escape_string($row["orden_id"]);
						$orden_descr2[$key]=pg_escape_string($row["orden_descr"]);
						$ambito_id2[$key]=pg_escape_string($row["ambito_id"]);
						$ambito_descr2[$key]=pg_escape_string($row["ambito_descr"]);
						$dependencia2[$key]=pg_escape_string($row["dependencia"]);
						$dependencia_descr2[$key]=pg_escape_string($row["dependencia_descr"]);
						$area_adscripcion2[$key]=pg_escape_string($row["area_adscripcion"]);
						$area_descr2[$key]=pg_escape_string($row["area_descr"]);
						$puesto2[$key]=pg_escape_string($row["puesto_id"]);
						$puesto_descr2[$key]=pg_escape_string($row["puesto_descr"]);
						$nombre_empresa2[$key]=pg_escape_string($row["nombre_empresa"]);
						$rfc_empresa2[$key]=pg_escape_string($row["rfc_empresa"]);
						$funcion_principal2[$key]=pg_escape_string($row["funcion_principal"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_experiencia VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$actividad_laboral2[$key]','$otra_actividad2[$key]','$orden_id2[$key]','$orden_descr2[$key]','$ambito_id2[$key]','$ambito_descr2[$key]','$dependencia2[$key]','$dependencia_descr2[$key]','$area_adscripcion2[$key]','$area_descr2[$key]',$puesto2[$key],'$puesto_descr2[$key]','$nombre_empresa2[$key]','$rfc_empresa2[$key]','$funcion_principal2[$key]',$fecha_inicio2[$key],$fecha_fin2[$key],'$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]','$ubicacion2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_experiencia WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>5,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"actividad_laboral"=>$actividad_laboral,
					"otra_actividad"=>$otra_actividad,
					"orden_id"=>$orden_id,
					"orden_descr"=>$orden_descr,
					"ambito_id"=>$ambito_id,
					"ambito_descr"=>$ambito_descr,
					"dependencia"=>$dependencia,
					"dependencia_descr"=>$dependencia_descr,
					"area_adscripcion"=>$area_adscripcion,
					"area_descr"=>$area_descr,
					"puesto"=>$puesto,
					"puesto_descr"=>$puesto_descr,
					"nombre_empresa"=>$nombre_empresa,
					"rfc_empresa"=>$rfc_empresa,
					"funcion_principal"=>$funcion_principal,
					"fecha_inicio"=>$fecha_inicio,
					"fecha_fin"=>$fecha_fin,
					"sector"=>$sector,
					"sector_descr"=>$sector_descr,
					"otro_sector"=>$otro_sector,
					"ubicacion"=>$ubicacion,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform6($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_parejas WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["nombre"]==""){$html[$key].="- Nombre<br>";}
				if($row["primer_apellido"]==""){$html[$key].="- Primer apellido<br>";}
				if($row["fecha_nac"]==""){$html[$key].="- Fecha de nacimiento<br>";}
				if($row["curp"]==""){$html[$key].="- CURP<br>";}
				if($row["rfc_pareja"]==""){$html[$key].="- RFC<br>";}
				if($row["extranjero"]==""){$html[$key].="- Ciudadano extranjero<br>";}
				if($row["dependiente"]==""){$html[$key].="- ¿Es dependiente económico?<br>";}
				if($row["mismo_domicilio"]==""){$html[$key].="- ¿Vive en el mismo domicilio?<br>";}
				if($row["relacion_pareja"]==""){$html[$key].="- Relación<br>";}
				if($row["residencia"]==""){$html[$key].="- Residencia<br>";}
				if($row["pais"]==""){$html[$key].="- País<br>";}
				if($row["residencia"]=="M"){
					if(trim($row["codigopostal"])==""){$html[$key].="- Código postal<br>";}
					if(trim($row["estado_descr"])==""){$html[$key].="- Estado<br>";}
					if(trim($row["municipio_descr"])==""){$html[$key].="- Municipio<br>";}
					if(trim($row["colonia_descr"])==""){$html[$key].="- Colonia<br>";}
					if(trim($row["calle"])==""){$html[$key].="- Calle<br>";}
					if(trim($row["num_exterior"])==""){$html[$key].="- No. exterior<br>";}
				}
				if($row["actividad_laboral"]==""){$html[$key].="- Actividad laboral<br>";}
				if($row["actividad_laboral"]=="N"){}
				if($row["actividad_laboral"]=="U"){
					if($row["orden_id"]==""){$html[$key].="- Orden de gobierno<br>";}
					if($row["ambito_id"]==""){$html[$key].="- Ámbito público<br>";}
					if($row["dependencia"]=="" || ($row["dependencia"]=="999") && $row["dependencia_descr"]==""){$html[$key].="- Ente público<br>";}
					if($row["area_descr"]==""){$html[$key].="- Área<br>";}
					if($row["puesto_descr"]==""){$html[$key].="- Empleo<br>";}
					if($row["funcion_principal"]==""){$html[$key].="- Función principal<br>";}
					if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
					if($row["sueldo_mensual"]==""){$html[$key].="- Sueldo mensual<br>";}
				}
				if($row["actividad_laboral"]=="V" || $row["actividad_laboral"]=="O"){
					if($row["nombre_empresa"]==""){$html[$key].="- Nombre de la empresa<br>";}
					if($row["sector"]==""){$html[$key].="- Sector<br>";}
					if($row["rfc_empresa"]==""){$html[$key].="- RFC de la empresa<br>";}
					if($row["area_descr"]==""){$html[$key].="- Área<br>";}
					if($row["puesto_descr"]==""){$html[$key].="- Empleo<br>";}
					if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
					if($row["sueldo_mensual"]==""){$html[$key].="- Sueldo mensual<br>";}
					if($row["proveedor"]==""){$html[$key].="- Proveedor<br>";}
				}
			}

			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_parejas SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform6($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$nombre=array();
	$primer_apellido=array();
	$segundo_apellido=array();
	$fecha_nac=array();
	$rfc_pareja=array();
	$relacion_pareja=array();
	$relacion_descr=array();
	$extranjero=array();
	$curp=array();
	$dependiente=array();
	$mismo_domicilio=array();
	$residencia=array();
	$calle=array();
	$num_exterior=array();
	$num_interior=array();
	$colonia=array();
	$colonia_descr=array();
	$municipio=array();
	$municipio_descr=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$codigopostal=array();
	$actividad_laboral=array();
	$actividad_descr=array();
	$otro_ambito=array();
	$orden_id=array();
	$orden_descr=array();
	$ambito_id=array();
	$ambito_descr=array();
	$dependencia=array();
	$dependencia_descr=array();
	$area_adscripcion=array();
	$area_descr=array();
	$puesto=array();
	$puesto_descr=array();
	$nombre_empresa=array();
	$rfc_empresa=array();
	$funcion_principal=array();
	$fecha_inicio=array();
	$sector=array();
	$sector_descr=array();
	$sueldo_mensual=array();
	$otro_sector=array();
	$proveedor=array();
	$observaciones=array();
	if($row){
		$sql="SELECT * FROM qsy_parejas WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$nombre[$key]=$row["nombre"];
				$primer_apellido[$key]=$row["primer_apellido"];
				$segundo_apellido[$key]=$row["segundo_apellido"];
				$fecha_nac[$key]=$row["fecha_nac"];
				$rfc_pareja[$key]=$row["rfc_pareja"];
				$relacion_pareja[$key]=$row["relacion_pareja"];
				$relacion_descr[$key]=$row["relacion_descr"];
				$extranjero[$key]=$row["extranjero"];
				$curp[$key]=$row["curp"];
				$dependiente[$key]=$row["dependiente"];
				$mismo_domicilio[$key]=$row["mismo_domicilio"];
				$residencia[$key]=$row["residencia"];
				$calle[$key]=$row["calle"];
				$num_exterior[$key]=$row["num_exterior"];
				$num_interior[$key]=$row["num_interior"];
				$colonia[$key]=$row["colonia"];
				$colonia_descr[$key]=$row["colonia_descr"];
				$municipio[$key]=$row["municipio"];
				$municipio_descr[$key]=$row["municipio_descr"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$codigopostal[$key]=$row["codigopostal"];
				$actividad_laboral[$key]=$row["actividad_laboral"];
				$actividad_descr[$key]=$row["actividad_descr"];
				$otro_ambito[$key]=$row["otro_ambito"];
				$orden_id[$key]=$row["orden_id"];
				$orden_descr[$key]=$row["orden_descr"];
				$ambito_id[$key]=$row["ambito_id"];
				$ambito_descr[$key]=$row["ambito_descr"];
				$dependencia[$key]=$row["dependencia"];
				$dependencia_descr[$key]=$row["dependencia_descr"];
				$area_adscripcion[$key]=$row["area_adscripcion"];
				$area_descr[$key]=$row["area_descr"];
				$puesto[$key]=$row["id_puesto"];
				$puesto_descr[$key]=$row["puesto_descr"];
				$nombre_empresa[$key]=$row["nombre_empresa"];
				$rfc_empresa[$key]=$row["rfc_empresa"];
				$funcion_principal[$key]=$row["funcion_principal"];
				$fecha_inicio[$key]=$row["fecha_inicio"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$sueldo_mensual[$key]=$row["sueldo_mensual"];
				$proveedor[$key]=$row["proveedor"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_parejas b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_parejas WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$nombre[$key]=$row["nombre"];
						$primer_apellido[$key]=$row["primer_apellido"];
						$segundo_apellido[$key]=$row["segundo_apellido"];
						$fecha_nac[$key]=$row["fecha_nac"];
						$fecha_nac2[$key]=format_date($row["fecha_nac"]);
						$rfc_pareja[$key]=$row["rfc_pareja"];
						$relacion_pareja[$key]=$row["relacion_pareja"];
						$relacion_descr[$key]=$row["relacion_descr"];
						$extranjero[$key]=$row["extranjero"];
						$curp[$key]=$row["curp"];
						$dependiente[$key]=$row["dependiente"];
						$mismo_domicilio[$key]=$row["mismo_domicilio"];
						$residencia[$key]=$row["residencia"];
						$calle[$key]=$row["calle"];
						$num_exterior[$key]=$row["num_exterior"];
						$num_interior[$key]=$row["num_interior"];
						$colonia[$key]=$row["colonia"];
						$colonia_descr[$key]=$row["colonia_descr"];
						$municipio[$key]=$row["municipio"];
						$municipio_descr[$key]=$row["municipio_descr"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$codigopostal[$key]=$row["codigopostal"];
						$actividad_laboral[$key]=$row["actividad_laboral"];
						$actividad_descr[$key]=$row["actividad_descr"];
						$otro_ambito[$key]=$row["otro_ambito"];
						$orden_id[$key]=$row["orden_id"];
						$orden_descr[$key]=$row["orden_descr"];
						$ambito_id[$key]=$row["ambito_id"];
						$ambito_descr[$key]=$row["ambito_descr"];
						$dependencia[$key]=$row["dependencia"];
						$dependencia_descr[$key]=$row["dependencia_descr"];
						$area_adscripcion[$key]=$row["area_adscripcion"];
						$area_descr[$key]=$row["area_descr"];
						$puesto[$key]=$row["id_puesto"];
						$puesto_descr[$key]=$row["puesto_descr"];
						$nombre_empresa[$key]=$row["nombre_empresa"];
						$rfc_empresa[$key]=$row["rfc_empresa"];
						$funcion_principal[$key]=$row["funcion_principal"];
						$fecha_inicio[$key]=$row["fecha_inicio"];
						$fecha_inicio2[$key]=format_date($row["fecha_inicio"]);
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$sueldo_mensual[$key]=$row["sueldo_mensual"];
						$proveedor[$key]=$row["proveedor"];
						$observaciones[$key]=$row["observaciones"];

						$nombre2[$key]=pg_escape_string($row["nombre"]);
						$primer_apellido2[$key]=pg_escape_string($row["primer_apellido"]);
						$segundo_apellido2[$key]=pg_escape_string($row["segundo_apellido"]);
						$rfc_pareja2[$key]=pg_escape_string($row["rfc_pareja"]);
						$relacion_pareja2[$key]=pg_escape_string($row["relacion_pareja"]);
						$relacion_descr2[$key]=pg_escape_string($row["relacion_descr"]);
						$extranjero2[$key]=pg_escape_string($row["extranjero"]);
						$curp2[$key]=pg_escape_string($row["curp"]);
						$dependiente2[$key]=pg_escape_string($row["dependiente"]);
						$mismo_domicilio2[$key]=pg_escape_string($row["mismo_domicilio"]);
						$residencia2[$key]=pg_escape_string($row["residencia"]);
						$calle2[$key]=pg_escape_string($row["calle"]);
						$num_exterior2[$key]=pg_escape_string($row["num_exterior"]);
						$num_interior2[$key]=pg_escape_string($row["num_interior"]);
						$colonia2[$key]=pg_escape_string($row["colonia"]);
						$colonia_descr2[$key]=pg_escape_string($row["colonia_descr"]);
						$municipio2[$key]=pg_escape_string($row["municipio"]);
						$municipio_descr2[$key]=pg_escape_string($row["municipio_descr"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$codigopostal2[$key]=pg_escape_string($row["codigopostal"]);
						$actividad_laboral2[$key]=pg_escape_string($row["actividad_laboral"]);
						$actividad_descr2[$key]=pg_escape_string($row["actividad_descr"]);
						$otro_ambito2[$key]=pg_escape_string($row["otro_ambito"]);
						$orden_id2[$key]=pg_escape_string($row["orden_id"]);
						$orden_descr2[$key]=pg_escape_string($row["orden_descr"]);
						$ambito_id2[$key]=pg_escape_string($row["ambito_id"]);
						$ambito_descr2[$key]=pg_escape_string($row["ambito_descr"]);
						$dependencia2[$key]=pg_escape_string($row["dependencia"]);
						$dependencia_descr2[$key]=pg_escape_string($row["dependencia_descr"]);
						$area_adscripcion2[$key]=pg_escape_string($row["area_adscripcion"]);
						$area_descr2[$key]=pg_escape_string($row["area_descr"]);
						$puesto2[$key]=pg_escape_string($row["id_puesto"]);
						$puesto_descr2[$key]=pg_escape_string($row["puesto_descr"]);
						$nombre_empresa2[$key]=pg_escape_string($row["nombre_empresa"]);
						$rfc_empresa2[$key]=pg_escape_string($row["rfc_empresa"]);
						$funcion_principal2[$key]=pg_escape_string($row["funcion_principal"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$sueldo_mensual2[$key]=pg_escape_string($row["sueldo_mensual"]);
						$proveedor2[$key]=pg_escape_string($row["proveedor"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_parejas VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$nombre2[$key]','$primer_apellido2[$key]','$segundo_apellido2[$key]',$fecha_nac2[$key],'$rfc_pareja2[$key]','$relacion_pareja2[$key]','$relacion_descr2[$key]','$extranjero2[$key]','$curp2[$key]','$dependiente2[$key]','$mismo_domicilio2[$key]','$residencia2[$key]','$calle2[$key]','$num_exterior2[$key]','$num_interior2[$key]','$colonia2[$key]','$colonia_descr2[$key]','$municipio2[$key]','$municipio_descr2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$codigopostal2[$key]','$actividad_laboral2[$key]','$actividad_descr2[$key]','$otro_ambito2[$key]','$orden_id2[$key]','$orden_descr2[$key]','$ambito_id2[$key]','$ambito_descr2[$key]','$dependencia2[$key]','$dependencia_descr2[$key]','$area_adscripcion2[$key]','$area_descr2[$key]',$puesto2[$key],'$puesto_descr2[$key]','$nombre_empresa2[$key]','$rfc_empresa2[$key]','$funcion_principal2[$key]',$fecha_inicio2[$key],'$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]',$sueldo_mensual2[$key],'$proveedor2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_parejas WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>6,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"nombre"=> $nombre,
					"primer_apellido"=> $primer_apellido,
					"segundo_apellido"=> $segundo_apellido,
					"fecha_nac" => $fecha_nac,
					"rfc_pareja" => $rfc_pareja,
					"relacion_pareja" => $relacion_pareja,
					"relacion_descr" => $relacion_descr,
					"extranjero" => $extranjero,
					"curp" => $curp,
					"dependiente" => $dependiente,
					"mismo_domicilio" => $mismo_domicilio,
					"residencia" => $residencia,
					"calle" => $calle,
					"num_exterior" => $num_exterior,
					"num_interior" => $num_interior,
					"colonia" => $colonia,
					"colonia_descr" => $colonia_descr,
					"municipio" => $municipio,
					"municipio_descr" => $municipio_descr,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"cp" => $codigopostal,
					"actividad_laboral" => $actividad_laboral,
					"actividad_descr" => $actividad_descr,
					"otro_ambito" => $otro_ambito,
					"orden_id" => $orden_id,
					"orden_descr" => $orden_descr,
					"ambito_id" => $ambito_id,
					"ambito_descr" => $ambito_descr,
					"dependencia" => $dependencia,
					"dependencia_descr" => $dependencia_descr,
					"area_adscripcion" => $area_adscripcion,
					"area_descr" => $area_descr,
					"puesto" =>$puesto,
					"puesto_descr" => $puesto_descr,
					"nombre_empresa" => $nombre_empresa,
					"rfc_empresa" => $rfc_empresa,
					"funcion_principal" => $funcion_principal,
					"fecha_inicio" => $fecha_inicio,
					"sector" => $sector,
					"sector_descr" => $sector_descr,
					"otro_sector" => $otro_sector,
					"sueldo_mensual" => $sueldo_mensual,
					"proveedor" => $proveedor,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform7($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_dependientes WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["nombre"]==""){$html[$key].="- Nombre<br>";}
				if($row["primer_apellido"]==""){$html[$key].="- Primer apellido<br>";}
				if($row["fecha_nac"]==""){$html[$key].="- Fecha de nacimiento<br>";}
				if($row["curp"]==""){$html[$key].="- CURP<br>";}
				if($row["extranjero"]==""){$html[$key].="- Ciudadano extranjero<br>";}
				if($row["mismo_domicilio"]==""){$html[$key].="- ¿Vive en el mismo domicilio?<br>";}
				if($row["relacion_depend"]==""){$html[$key].="- Relación<br>";}
				if($row["residencia"]==""){$html[$key].="- Residencia<br>";}
				if($row["pais"]==""){$html[$key].="- País<br>";}
				if($row["residencia"]=="M"){
					if(trim($row["codigopostal"])==""){$html[$key].="- Código postal<br>";}
					if(trim($row["estado_descr"])==""){$html[$key].="- Estado<br>";}
					if(trim($row["municipio_descr"])==""){$html[$key].="- Municipio<br>";}
					if(trim($row["colonia_descr"])==""){$html[$key].="- Colonia<br>";}
					if(trim($row["calle"])==""){$html[$key].="- Calle<br>";}
					if(trim($row["num_exterior"])==""){$html[$key].="- No. exterior<br>";}
				}
				if($row["actividad_laboral"]==""){$html[$key].="- Actividad laboral<br>";}
				if($row["actividad_laboral"]=="N"){}
				if($row["actividad_laboral"]=="U"){
					if($row["orden_id"]==""){$html[$key].="- Orden de gobierno<br>";}
					if($row["ambito_id"]==""){$html[$key].="- Ámbito público<br>";}
					if($row["dependencia"]=="" || ($row["dependencia"]=="999") && $row["dependencia_descr"]==""){$html[$key].="- Ente público<br>";}
					if($row["area_descr"]==""){$html[$key].="- Área<br>";}
					if($row["puesto_descr"]==""){$html[$key].="- Empleo<br>";}
					if($row["funcion_principal"]==""){$html[$key].="- Función principal<br>";}
					if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
					if($row["sueldo_mensual"]==""){$html[$key].="- Sueldo mensual<br>";}
				}
				if($row["actividad_laboral"]=="V" || $row["actividad_laboral"]=="O"){
					if($row["nombre_empresa"]==""){$html[$key].="- Nombre de la empresa<br>";}
					if($row["sector"]==""){$html[$key].="- Sector<br>";}
					if($row["rfc_empresa"]==""){$html[$key].="- RFC de la empresa<br>";}
					if($row["area_descr"]==""){$html[$key].="- Área<br>";}
					if($row["puesto_descr"]==""){$html[$key].="- Empleo<br>";}
					if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
					if($row["sueldo_mensual"]==""){$html[$key].="- Sueldo mensual<br>";}
					if($row["proveedor"]==""){$html[$key].="- Proveedor<br>";}
				}
			}

			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_dependientes SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform7($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$nombre=array();
	$primer_apellido=array();
	$segundo_apellido=array();
	$fecha_nac=array();
	$rfc_dependiente=array();
	$relacion_depend=array();
	$relacion_descr=array();
	$otra_relacion=array();
	$extranjero=array();
	$curp=array();
	$mismo_domicilio=array();
	$residencia=array();
	$calle=array();
	$num_exterior=array();
	$num_interior=array();
	$colonia=array();
	$colonia_descr=array();
	$municipio=array();
	$municipio_descr=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$codigopostal=array();
	$actividad_laboral=array();
	$otro_ambito=array();
	$orden_id=array();
	$orden_descr=array();
	$ambito_id=array();
	$ambito_descr=array();
	$dependencia=array();
	$dependencia_descr=array();
	$area_adscripcion=array();
	$area_descr=array();
	$nivel_empleo=array();
	$nivel_descr=array();
	$puesto_id=array();
	$puesto_descr=array();
	$nombre_empresa=array();
	$rfc_empresa=array();
	$funcion_principal=array();
	$fecha_inicio=array();
	$sector=array();
	$sector_descr=array();
	$otro_sector=array();
	$sueldo_mensual=array();
	$proveedor=array();
	$observaciones=array();
	if($row){
		$sql="SELECT * FROM qsy_dependientes WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$nombre[$key]=$row["nombre"];
				$primer_apellido[$key]=$row["primer_apellido"];
				$segundo_apellido[$key]=$row["segundo_apellido"];
				$fecha_nac[$key]=$row["fecha_nac"];
				$rfc_dependiente[$key]=$row["rfc_dependiente"];
				$relacion_depend[$key]=$row["relacion_depend"];
				$relacion_descr[$key]=$row["relacion_descr"];
				$otra_relacion[$key]=$row["otra_relacion"];
				$extranjero[$key]=$row["extranjero"];
				$curp[$key]=$row["curp"];
				$mismo_domicilio[$key]=$row["mismo_domicilio"];
				$residencia[$key]=$row["residencia"];
				$calle[$key]=$row["calle"];
				$num_exterior[$key]=$row["num_exterior"];
				$num_interior[$key]=$row["num_interior"];
				$colonia[$key]=$row["colonia"];
				$colonia_descr[$key]=$row["colonia_descr"];
				$municipio[$key]=$row["municipio"];
				$municipio_descr[$key]=$row["municipio_descr"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$codigopostal[$key]=$row["codigopostal"];
				$actividad_laboral[$key]=$row["actividad_laboral"];
				$otro_ambito[$key]=$row["otro_ambito"];
				$orden_id[$key]=$row["orden_id"];
				$orden_descr[$key]=$row["orden_descr"];
				$ambito_id[$key]=$row["ambito_id"];
				$ambito_descr[$key]=$row["ambito_descr"];
				$dependencia[$key]=$row["dependencia"];
				$dependencia_descr[$key]=$row["dependencia_descr"];
				$area_adscripcion[$key]=$row["area_adscripcion"];
				$area_descr[$key]=$row["area_descr"];
				$nivel_empleo[$key]=$row["nivel_empleo"];
				$nivel_descr[$key]=$row["nivel_descr"];
				$puesto_id[$key]=$row["puesto_id"];
				$puesto_descr[$key]=$row["puesto_descr"];
				$nombre_empresa[$key]=$row["nombre_empresa"];
				$rfc_empresa[$key]=$row["rfc_empresa"];
				$funcion_principal[$key]=$row["funcion_principal"];
				$fecha_inicio[$key]=$row["fecha_inicio"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$sueldo_mensual[$key]=$row["sueldo_mensual"];
				$proveedor[$key]=$row["proveedor"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_dependientes b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_dependientes WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$nombre[$key]=$row["nombre"];
						$primer_apellido[$key]=$row["primer_apellido"];
						$segundo_apellido[$key]=$row["segundo_apellido"];
						$fecha_nac[$key]=$row["fecha_nac"];
						$fecha_nac2[$key]=format_date($row["fecha_nac"]);
						$rfc_dependiente[$key]=$row["rfc_dependiente"];
						$relacion_depend[$key]=$row["relacion_depend"];
						$relacion_descr[$key]=$row["relacion_descr"];
						$otra_relacion[$key]=$row["otra_relacion"];
						$extranjero[$key]=$row["extranjero"];
						$curp[$key]=$row["curp"];
						$mismo_domicilio[$key]=$row["mismo_domicilio"];
						$residencia[$key]=$row["residencia"];
						$calle[$key]=$row["calle"];
						$num_exterior[$key]=$row["num_exterior"];
						$num_interior[$key]=$row["num_interior"];
						$colonia[$key]=$row["colonia"];
						$colonia_descr[$key]=$row["colonia_descr"];
						$municipio[$key]=$row["municipio"];
						$municipio_descr[$key]=$row["municipio_descr"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$codigopostal[$key]=$row["codigopostal"];
						$actividad_laboral[$key]=$row["actividad_laboral"];
						$otro_ambito[$key]=$row["otro_ambito"];
						$orden_id[$key]=$row["orden_id"];
						$orden_descr[$key]=$row["orden_descr"];
						$ambito_id[$key]=$row["ambito_id"];
						$ambito_descr[$key]=$row["ambito_descr"];
						$dependencia[$key]=$row["dependencia"];
						$dependencia_descr[$key]=$row["dependencia_descr"];
						$area_adscripcion[$key]=$row["area_adscripcion"];
						$area_descr[$key]=$row["area_descr"];
						$nivel_empleo[$key]=$row["nivel_empleo"];
						$nivel_descr[$key]=$row["nivel_descr"];
						$puesto_id[$key]=$row["puesto_id"];
						$puesto_descr[$key]=$row["puesto_descr"];
						$nombre_empresa[$key]=$row["nombre_empresa"];
						$rfc_empresa[$key]=$row["rfc_empresa"];
						$funcion_principal[$key]=$row["funcion_principal"];
						$fecha_inicio[$key]=$row["fecha_inicio"];
						$fecha_inicio2[$key]=format_date($row["fecha_inicio"]);
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$sueldo_mensual[$key]=$row["sueldo_mensual"];
						$proveedor[$key]=$row["proveedor"];
						$observaciones[$key]=$row["observaciones"];

						$nombre2[$key]=pg_escape_string($row["nombre"]);
						$primer_apellido2[$key]=pg_escape_string($row["primer_apellido"]);
						$segundo_apellido2[$key]=pg_escape_string($row["segundo_apellido"]);
						$rfc_dependiente2[$key]=pg_escape_string($row["rfc_dependiente"]);
						$relacion_depend2[$key]=pg_escape_string($row["relacion_depend"]);
						$relacion_descr2[$key]=pg_escape_string($row["relacion_descr"]);
						$otra_relacion2[$key]=pg_escape_string($row["otra_relacion"]);
						$extranjero2[$key]=pg_escape_string($row["extranjero"]);
						$curp2[$key]=pg_escape_string($row["curp"]);
						$mismo_domicilio2[$key]=pg_escape_string($row["mismo_domicilio"]);
						$residencia2[$key]=pg_escape_string($row["residencia"]);
						$calle2[$key]=pg_escape_string($row["calle"]);
						$num_exterior2[$key]=pg_escape_string($row["num_exterior"]);
						$num_interior2[$key]=pg_escape_string($row["num_interior"]);
						$colonia2[$key]=pg_escape_string($row["colonia"]);
						$colonia_descr2[$key]=pg_escape_string($row["colonia_descr"]);
						$municipio2[$key]=pg_escape_string($row["municipio"]);
						$municipio_descr2[$key]=pg_escape_string($row["municipio_descr"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$codigopostal2[$key]=pg_escape_string($row["codigopostal"]);
						$actividad_laboral2[$key]=pg_escape_string($row["actividad_laboral"]);
						$otro_ambito2[$key]=pg_escape_string($row["otro_ambito"]);
						$orden_id2[$key]=pg_escape_string($row["orden_id"]);
						$orden_descr2[$key]=pg_escape_string($row["orden_descr"]);
						$ambito_id2[$key]=pg_escape_string($row["ambito_id"]);
						$ambito_descr2[$key]=pg_escape_string($row["ambito_descr"]);
						$dependencia2[$key]=pg_escape_string($row["dependencia"]);
						$dependencia_descr2[$key]=pg_escape_string($row["dependencia_descr"]);
						$area_adscripcion2[$key]=pg_escape_string($row["area_adscripcion"]);
						$area_descr2[$key]=pg_escape_string($row["area_descr"]);
						$nivel_empleo2[$key]=pg_escape_string($row["nivel_empleo"]);
						$nivel_descr2[$key]=pg_escape_string($row["nivel_descr"]);
						$puesto_id2[$key]=pg_escape_string($row["puesto_id"]);
						$puesto_descr2[$key]=pg_escape_string($row["puesto_descr"]);
						$nombre_empresa2[$key]=pg_escape_string($row["nombre_empresa"]);
						$rfc_empresa2[$key]=pg_escape_string($row["rfc_empresa"]);
						$funcion_principal2[$key]=pg_escape_string($row["funcion_principal"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$sueldo_mensual2[$key]=pg_escape_string($row["sueldo_mensual"]);
						$proveedor2[$key]=pg_escape_string($row["proveedor"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_dependientes VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$nombre2[$key]','$primer_apellido2[$key]','$segundo_apellido2[$key]',$fecha_nac2[$key],'$rfc_dependiente2[$key]','$relacion_depend2[$key]','$relacion_descr2[$key]','$otra_relacion2[$key]','$extranjero2[$key]','$curp2[$key]','$mismo_domicilio2[$key]','$residencia2[$key]','$calle2[$key]','$num_exterior2[$key]','$num_interior2[$key]','$colonia2[$key]','$colonia_descr2[$key]','$municipio2[$key]','$municipio_descr2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$codigopostal2[$key]','$actividad_laboral2[$key]','$otro_ambito2[$key]','$orden_id2[$key]','$orden_descr2[$key]','$ambito_id2[$key]','$ambito_descr2[$key]','$dependencia2[$key]','$dependencia_descr2[$key]','$area_adscripcion2[$key]','$area_descr2[$key]','$nivel_empleo2[$key]','$nivel_descr2[$key]',$puesto_id2[$key],'$puesto_descr2[$key]','$nombre_empresa2[$key]','$rfc_empresa2[$key]','$funcion_principal2[$key]',$fecha_inicio2[$key],'$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]',$sueldo_mensual2[$key],'$proveedor2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_dependientes WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>7,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"nombre"=> $nombre,
					"primer_apellido"=> $primer_apellido,
					"segundo_apellido"=> $segundo_apellido,
					"fecha_nac" => $fecha_nac,
					"rfc_dependiente" => $rfc_dependiente,
					"relacion_depend" => $relacion_depend,
					"relacion_descr" => $relacion_descr,
					"otra_relacion" => $otra_relacion,
					"extranjero" => $extranjero,
					"curp" => $curp,
					"mismo_domicilio" => $mismo_domicilio,
					"residencia" => $residencia,
					"calle" => $calle,
					"num_exterior" => $num_exterior,
					"num_interior" => $num_interior,
					"colonia" => $colonia,
					"colonia_descr" => $colonia_descr,
					"municipio" => $municipio,
					"municipio_descr" => $municipio_descr,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"cp" => $codigopostal,
					"actividad_laboral" => $actividad_laboral,
					"otro_ambito" => $otro_ambito,
					"orden_id" => $orden_id,
					"orden_descr" => $orden_descr,
					"ambito_id" => $ambito_id,
					"ambito_descr" => $ambito_descr,
					"dependencia" => $dependencia,
					"dependencia_descr" => $dependencia_descr,
					"area_adscripcion" => $area_adscripcion,
					"area_descr" => $area_descr,
					"nivel_empleo" => $nivel_empleo,
					"nivel_descr" => $nivel_descr,
					"puesto_id" =>$puesto_id,
					"puesto_descr" => $puesto_descr,
					"nombre_empresa" => $nombre_empresa,
					"rfc_empresa" => $rfc_empresa,
					"funcion_principal" => $funcion_principal,
					"fecha_inicio" => $fecha_inicio,
					"sector" => $sector,
					"sector_descr" => $sector,
					"otro_sector" => $otro_sector,
					"sueldo_mensual" => $sueldo_mensual,
					"proveedor" => $proveedor,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform8($dec,$ejercicio,$rfc,$conn){
	$html="";
	$sql="SELECT * FROM qsy_ingresos_netos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND estatus='A'";
	$result=pg_query($conn,$sql);
	$row=pg_fetch_assoc($result);
    if($row){
    	$html="Campos pendientes:<br>";

		if($row["remunera_neta"]==""){$html.="- Remuneración neta<br>";}
		if($row["activ_industrial"]==""){$html.="- Actividad industrial<br>";}
		if($row["activ_financiera"]==""){$html.="- Actividad financiera<br>";}
		if($row["serv_profesionales"]==""){$html.="- Servicios profesionales<br>";}
		if($row["tipo_servicio"]==""){$html.="- Tipo de servicio<br>";}
		if($row["no_considerados"]==""){$html.="- Ingresos no considerados<br>";}
		if($row["ingreso_pareja"]==""){$html.="- Ingresos de pareja o dependientes<br>";}
		
		if($dec=="M" || $dec=="C"){
			if($row["enajena_bienes"]==""){$html.="- Enajenación de bienes<br>";}
		}
		if($html=="Campos pendientes:<br>")$html="";
		if($html==""){
			$sql="UPDATE qsy_ingresos_netos SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
			$result=pg_query($conn,$sql);
		}
	}
	return $html;
}
function cargarform8($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$movimiento="";
	$remunera_neta=0;
	$otros_ingresos=0;
	$activ_industrial=0;
	$razon_social="";
	$tipo_negocio="";
	$activ_financiera=0;
	$tipo_instrumento="";
	$otro_instrumento="";
	$serv_profesionales=0;
	$tipo_servicio="NINGUNO";
	$enajena_bienes=0;
	$tipo_bien="";
	$tipo_descr="";
	$no_considerados=0;
	$tipo_ingreso="";
	$ingreso_neto=0;
	$ingreso_pareja=0;
	$total_ingresos=0;
	$observaciones="";

	if($row){
		$sql="SELECT * FROM qsy_ingresos_netos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$remunera_neta=$row["remunera_neta"];
				$otros_ingresos=$row["otros_ingresos"];
				$activ_industrial=$row["activ_industrial"];
				$razon_social=$row["razon_social"];
				$tipo_negocio=$row["tipo_negocio"];
				$activ_financiera=$row["activ_financiera"];
				$tipo_instrumento=$row["tipo_instrumento"];
				$otro_instrumento=$row["otro_instrumento"];
				$serv_profesionales=$row["serv_profesionales"];
				$tipo_servicio=$row["tipo_servicio"];
				$enajena_bienes=$row["enajena_bienes"];
				$tipo_bien=$row["tipo_bien"];
				$tipo_descr=$row["tipo_descr"];
				$no_considerados=$row["no_considerados"];
				$tipo_ingreso=$row["tipo_ingreso"];
				$ingreso_neto=$row["ingreso_neto"];
				$ingreso_pareja=$row["ingreso_pareja"];
				$total_ingresos=$row["total_ingresos"];
				$observaciones=$row["observaciones"];
			}
		}
		else{
			if($dec=="M"){			
				$sql="SELECT * FROM qsy_declaraciones a,qsy_ingresos_netos b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl='M' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
				$result=pg_query($conn,$sql);
				$row=pg_fetch_assoc($result);
				//print_r($row);die;
				if($row){
					$remunera_neta=$row["remunera_neta"];
					$otros_ingresos=$row["otros_ingresos"];
					$activ_industrial=$row["activ_industrial"];
					$razon_social=$row["razon_social"];
					$tipo_negocio=$row["tipo_negocio"];
					$activ_financiera=$row["activ_financiera"];
					$tipo_instrumento=$row["tipo_instrumento"];
					$otro_instrumento=$row["otro_instrumento"];
					$serv_profesionales=$row["serv_profesionales"];
					$tipo_servicio=$row["tipo_servicio"];
					$enajena_bienes=$row["enajena_bienes"];
					$tipo_bien=$row["tipo_bien"];
					$tipo_descr=$row["tipo_descr"];
					$no_considerados=$row["no_considerados"];
					$tipo_ingreso=$row["tipo_ingreso"];
					$ingreso_neto=$row["ingreso_neto"];
					$ingreso_pareja=$row["ingreso_pareja"];
					$total_ingresos=$row["total_ingresos"];
					$observaciones=$row["observaciones"];

					$remunera_neta2=pg_escape_string($row["remunera_neta"]);
					$otros_ingresos2=pg_escape_string($row["otros_ingresos"]);
					$activ_industrial2=pg_escape_string($row["activ_industrial"]);
					$razon_social2=pg_escape_string($row["razon_social"]);
					$tipo_negocio2=pg_escape_string($row["tipo_negocio"]);
					$activ_financiera2=pg_escape_string($row["activ_financiera"]);
					$tipo_instrumento2=pg_escape_string($row["tipo_instrumento"]);
					$otro_instrumento2=pg_escape_string($row["otro_instrumento"]);
					$serv_profesionales2=pg_escape_string($row["serv_profesionales"]);
					$tipo_servicio2=pg_escape_string($row["tipo_servicio"]);
					$enajena_bienes2=pg_escape_string($row["enajena_bienes"]);
					$tipo_bien2=pg_escape_string($row["tipo_bien"]);
					$tipo_descr2=pg_escape_string($row["tipo_descr"]);
					$no_considerados2=pg_escape_string($row["no_considerados"]);
					$tipo_ingreso2=pg_escape_string($row["tipo_ingreso"]);
					$ingreso_neto2=pg_escape_string($row["ingreso_neto"]);
					$ingreso_pareja2=pg_escape_string($row["ingreso_pareja"]);
					$total_ingresos2=pg_escape_string($row["total_ingresos"]);
					$observaciones2=pg_escape_string($row["observaciones"]);

					$sql="INSERT INTO qsy_ingresos_netos VALUES ('$rfc',$ejercicio,'P','$dec','A',$remunera_neta2,$otros_ingresos2,$activ_industrial2,'$razon_social2','$tipo_negocio2',$activ_financiera2,'$tipo_instrumento2','$otro_instrumento2',$serv_profesionales2,'$tipo_servicio2',$enajena_bienes2,'$tipo_bien2','$tipo_descr2',$no_considerados2,'$tipo_ingreso2',$ingreso_neto2,$ingreso_pareja2,$total_ingresos2,'$observaciones2','A')";
					$result=pg_query($conn,$sql);
				}
			}
		}
	}

	$arreglo=array(
					"id"=>$rfc,
					"movimiento"=> $movimiento,
					"remunera_neta"=> $remunera_neta,
					"otros_ingresos"=> $otros_ingresos,
					"activ_industrial"=> $activ_industrial,
					"razon_social"=> $razon_social,
					"tipo_negocio"=> $tipo_negocio,
					"activ_financiera"=> $activ_financiera,
					"tipo_instrumento"=> $tipo_instrumento,
					"otro_instrumento"=> $otro_instrumento,
					"serv_profesionales"=> $serv_profesionales,
					"tipo_servicio"=> $tipo_servicio,
					"enajena_bienes"=> $enajena_bienes,
					"tipo_bien"=> $tipo_bien,
					"tipo_descr"=> $tipo_descr,
					"no_considerados"=> $no_considerados,
					"tipo_ingreso"=> $tipo_ingreso,
					"ingreso_neto"=> $ingreso_neto,
					"ingreso_pareja"=> $ingreso_pareja,
					"total_ingresos"=> $total_ingresos,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform9($dec,$ejercicio,$rfc,$conn){
	$html="";
	$sql="SELECT * FROM qsy_ingresos_anio_anterior WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND estatus='A'";
	$result=pg_query($conn,$sql);
	$row=pg_fetch_assoc($result);
    if($row){
    	$html="Campos pendientes:<br>";

		if($row["servidor_anio_prev"]!="N"){
			if($row["fecha_inicio"]==""){$html.="- Fecha de inicio<br>";}
			if($row["fecha_fin"]==""){$html.="- Fecha de conclusión<br>";}
			if($row["remunera_neta"]==""){$html.="- Remuneración neta<br>";}
			if($row["activ_industrial"]==""){$html.="- Actividad industrial<br>";}
			if($row["activ_financiera"]==""){$html.="- Actividad financiera<br>";}
			if($row["serv_profesionales"]==""){$html.="- Servicios profesionales<br>";}
			if($row["tipo_servicio"]==""){$html.="- Tipo de servicio<br>";}
			if($row["no_considerados"]==""){$html.="- Ingresos no considerados<br>";}
			if($row["ingreso_pareja"]==""){$html.="- Ingresos de pareja o dependientes<br>";}
			if($row["enajena_bienes"]==""){$html.="- Enajenación de bienes<br>";}
		}

		if($html=="Campos pendientes:<br>")$html="";
		if($html==""){
			$sql="UPDATE qsy_ingresos_anio_anterior SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
			$result=pg_query($conn,$sql);
		}
	}
	return $html;
}
function cargarform9($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$servidor_anio_prev="";
	$fecha_inicio=NULL;
	$fecha_fin=NULL;
	$remunera_neta=0;
	$otros_ingresos=0;
	$activ_industrial=0;
	$razon_social="";
	$tipo_negocio="";
	$activ_financiera=0;
	$tipo_instrumento="";
	$otro_instrumento="";
	$serv_profesionales=0;
	$tipo_servicio="NINGUNO";
	$enajena_bienes=0;
	$tipo_bien="";
	$tipo_descr="";
	$no_considerados=0;
	$tipo_ingreso="";
	$ingreso_neto=0;
	$ingreso_pareja=0;
	$total_ingresos=0;
	$observaciones="";

	if($row){
		$sql="SELECT * FROM qsy_ingresos_anio_anterior WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$servidor_anio_prev=$row["servidor_anio_prev"];
				$fecha_inicio=$row["fecha_inicio"];
				$fecha_fin=$row["fecha_fin"];
				$remunera_neta=$row["remunera_neta"];
				$otros_ingresos=$row["otros_ingresos"];
				$activ_industrial=$row["activ_industrial"];
				$razon_social=$row["razon_social"];
				$tipo_negocio=$row["tipo_negocio"];
				$activ_financiera=$row["activ_financiera"];
				$tipo_instrumento=$row["tipo_instrumento"];
				$otro_instrumento=$row["otro_instrumento"];
				$serv_profesionales=$row["serv_profesionales"];
				$tipo_servicio=$row["tipo_servicio"];
				$enajena_bienes=$row["enajena_bienes"];
				$tipo_bien=$row["tipo_bien"];
				$tipo_descr=$row["tipo_descr"];
				$no_considerados=$row["no_considerados"];
				$tipo_ingreso=$row["tipo_ingreso"];
				$ingreso_neto=$row["ingreso_neto"];
				$ingreso_pareja=$row["ingreso_pareja"];
				$total_ingresos=$row["total_ingresos"];
				$observaciones=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT * FROM qsy_declaraciones a,qsy_ingresos_anio_anterior b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$servidor_anio_prev=$row["servidor_anio_prev"];
				$fecha_inicio=$row["fecha_inicio"];
				$fecha_inicio2=format_date($row["fecha_inicio"]);
				$fecha_fin=$row["fecha_fin"];
				$fecha_fin2=format_date($row["fecha_fin"]);
				$remunera_neta=$row["remunera_neta"];
				$otros_ingresos=$row["otros_ingresos"];
				$activ_industrial=$row["activ_industrial"];
				$razon_social=$row["razon_social"];
				$tipo_negocio=$row["tipo_negocio"];
				$activ_financiera=$row["activ_financiera"];
				$tipo_instrumento=$row["tipo_instrumento"];
				$otro_instrumento=$row["otro_instrumento"];
				$serv_profesionales=$row["serv_profesionales"];
				$tipo_servicio=$row["tipo_servicio"];
				$enajena_bienes=$row["enajena_bienes"];
				$tipo_bien=$row["tipo_bien"];
				$tipo_descr=$row["tipo_descr"];
				$no_considerados=$row["no_considerados"];
				$tipo_ingreso=$row["tipo_ingreso"];
				$ingreso_neto=$row["ingreso_neto"];
				$ingreso_pareja=$row["ingreso_pareja"];
				$total_ingresos=$row["total_ingresos"];
				$observaciones=$row["observaciones"];

				$servidor_anio_prev2=pg_escape_string($row["servidor_anio_prev"]);
				$remunera_neta2=pg_escape_string($row["remunera_neta"]);
				$otros_ingresos2=pg_escape_string($row["otros_ingresos"]);
				$activ_industrial2=pg_escape_string($row["activ_industrial"]);
				$razon_social2=pg_escape_string($row["razon_social"]);
				$tipo_negocio2=pg_escape_string($row["tipo_negocio"]);
				$activ_financiera2=pg_escape_string($row["activ_financiera"]);
				$tipo_instrumento2=pg_escape_string($row["tipo_instrumento"]);
				$otro_instrumento2=pg_escape_string($row["otro_instrumento"]);
				$serv_profesionales2=pg_escape_string($row["serv_profesionales"]);
				$tipo_servicio2=pg_escape_string($row["tipo_servicio"]);
				$enajena_bienes2=pg_escape_string($row["enajena_bienes"]);
				$tipo_bien2=pg_escape_string($row["tipo_bien"]);
				$tipo_descr2=pg_escape_string($row["tipo_descr"]);
				$no_considerados2=pg_escape_string($row["no_considerados"]);
				$tipo_ingreso2=pg_escape_string($row["tipo_ingreso"]);
				$ingreso_neto2=pg_escape_string($row["ingreso_neto"]);
				$ingreso_pareja2=pg_escape_string($row["ingreso_pareja"]);
				$total_ingresos2=pg_escape_string($row["total_ingresos"]);
				$observaciones2=pg_escape_string($row["observaciones"]);
				$sql="INSERT INTO qsy_ingresos_anio_anterior VALUES ('$rfc',$ejercicio,'P','$dec','$servidor_anio_prev2',$fecha_inicio2,$fecha_fin2,$remunera_neta2,$otros_ingresos2,$activ_industrial2,'$razon_social2','$tipo_negocio2',$activ_financiera2,'$tipo_instrumento2','$otro_instrumento2',$serv_profesionales2,'$tipo_servicio2',$enajena_bienes2,'$tipo_bien2','$tipo_descr2',$no_considerados2,'$tipo_ingreso2',$ingreso_neto2,$ingreso_pareja2,$total_ingresos2,'$observaciones2','A')";
				$result=pg_query($conn,$sql);
			}
		}
	}
	$arreglo=array(
					"id"=>$rfc,
					"servidor_anio_prev"=> $servidor_anio_prev,
					"fecha_inicio"=> $fecha_inicio,
					"fecha_fin"=> $fecha_fin,
					"remunera_neta"=> $remunera_neta,
					"otros_ingresos"=> $otros_ingresos,
					"activ_industrial"=> $activ_industrial,
					"razon_social"=> $razon_social,
					"tipo_negocio"=> $tipo_negocio,
					"activ_financiera"=> $activ_financiera,
					"tipo_instrumento"=> $tipo_instrumento,
					"otro_instrumento"=> $otro_instrumento,
					"serv_profesionales"=> $serv_profesionales,
					"tipo_servicio"=> $tipo_servicio,
					"enajena_bienes"=> $enajena_bienes,
					"tipo_bien"=> $tipo_bien,
					"tipo_descr"=> $tipo_descr,
					"no_considerados"=> $no_considerados,
					"tipo_ingreso"=> $tipo_ingreso,
					"ingreso_neto"=> $ingreso_neto,
					"ingreso_pareja"=> $ingreso_pareja,
					"total_ingresos"=> $total_ingresos,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform10($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_inmuebles WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";
	
			if($row["movimiento"]!="N"){
				if($row["tipo_inmueble"]==""){$html[$key].="- Tipo de inmueble<br>";}
				if($row["titular"]==""){$html[$key].="- Titular<br>";}
				if($row["pct_propiedad"]==""){$html[$key].="- Porcentaje de la propiedad<br>";}
				if($row["sup_terreno"]==""){$html[$key].="- Superfice del terreno<br>";}
				if($row["sup_construc"]==""){$html[$key].="- Superficie de construcción<br>";}
				if($row["adquisicion"]==""){$html[$key].="- Forma de adquisición<br>";}
				if($row["forma_pago"]==""){$html[$key].="- Forma de pago<br>";}
				if($row["valor_adquisicion"]==""){$html[$key].="- Valor de adquisición<br>";}
				if($row["tipo_moneda"]==""){$html[$key].="- Tipo de moneda<br>";}
				if($row["fecha_adquisicion"]==""){$html[$key].="- Fecha de adquisición<br>";}
				if($row["registro_publico"]==""){$html[$key].="- Identificador de registro público<br>";}
				if($row["valor_conforme_a"]==""){$html[$key].="- Valor de adquisición (Conformidad)<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["pais"]==""){$html[$key].="- País<br>";}
				if($row["ubicacion"]=="M"){
					if(trim($row["codigopostal"])==""){$html[$key].="- Código postal<br>";}
					if(trim($row["estado_descr"])==""){$html[$key].="- Estado<br>";}
					if(trim($row["municipio_descr"])==""){$html[$key].="- Municipio<br>";}
					if(trim($row["colonia_descr"])==""){$html[$key].="- Colonia<br>";}
					if(trim($row["calle"])==""){$html[$key].="- Calle<br>";}
					if(trim($row["num_exterior"])==""){$html[$key].="- No. exterior<br>";}
				}
			}

			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_inmuebles SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform10($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_inmueble=array();
	$otro_inmueble=array();
	$titular=array();
	$titular_descr=array();
	$pct_propiedad=array();
	$sup_terreno=array();
	$sup_construc=array();
	$adquisicion=array();
	$adquisicion_descr=array();
	$forma_pago=array();
	$forma_descr=array();
	$tercero=array();
	$tercero_descr=array();
	$nombre_tercero=array();
	$rfc_tercero=array();
	$transmisor=array();
	$transmisor_descr=array();
	$nombre_transmisor=array();
	$rfc_transmisor=array();
	$relacion=array();
	$relacion_descr=array();
	$otra_relacion=array();
	$valor_adquisicion=array();
	$tipo_moneda=array();
	$fecha_adquisicion=array();
	$registro_publico=array();
	$valor_conforme_a=array();
	$conforme_descr=array();
	$ubicacion=array();
	$calle=array();
	$num_exterior=array();
	$num_interior=array();
	$colonia=array();
	$colonia_descr=array();
	$municipio=array();
	$municipio_descr=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$codigopostal=array();
	$causa_baja=array();
	$otra_causa=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_inmuebles WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_inmueble[$key]=$row["tipo_inmueble"];
				$otro_inmueble[$key]=$row["otro_inmueble"];
				$titular[$key]=$row["titular"];
				$titular_descr[$key]=$row["titular_descr"];
				$pct_propiedad[$key]=$row["pct_propiedad"];
				$sup_terreno[$key]=$row["sup_terreno"];
				$sup_construc[$key]=$row["sup_construc"];
				$adquisicion[$key]=$row["adquisicion"];
				$adquisicion_descr[$key]=$row["adquisicion_descr"];
				$forma_pago[$key]=$row["forma_pago"];
				$forma_descr[$key]=$row["forma_descr"];
				$tercero[$key]=$row["tercero"];
				$tercero_descr[$key]=$row["tercero_descr"];
				$nombre_tercero[$key]=$row["nombre_tercero"];
				$rfc_tercero[$key]=$row["rfc_tercero"];
				$transmisor[$key]=$row["transmisor"];
				$transmisor_descr[$key]=$row["transmisor_descr"];
				$nombre_transmisor[$key]=$row["nombre_transmisor"];
				$rfc_transmisor[$key]=$row["rfc_transmisor"];
				$relacion[$key]=$row["relacion"];
				$relacion_descr[$key]=$row["relacion_descr"];
				$otra_relacion[$key]=$row["otra_relacion"];
				$valor_adquisicion[$key]=$row["valor_adquisicion"];
				$tipo_moneda[$key]=$row["tipo_moneda"];
				$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
				$registro_publico[$key]=$row["registro_publico"];
				$valor_conforme_a[$key]=$row["valor_conforme_a"];
				$conforme_descr[$key]=$row["conforme_descr"];
				$ubicacion[$key]=$row["ubicacion"];
				$calle[$key]=$row["calle"];
				$num_exterior[$key]=$row["num_exterior"];
				$num_interior[$key]=$row["num_interior"];
				$colonia[$key]=$row["colonia"];
				$colonia_descr[$key]=$row["colonia_descr"];
				$municipio[$key]=$row["municipio"];
				$municipio_descr[$key]=$row["municipio_descr"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$codigopostal[$key]=$row["codigopostal"];
				$causa_baja[$key]=$row["causa_baja"];
				$otra_causa[$key]=$row["otra_causa"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_inmuebles b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_inmuebles WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_inmueble[$key]=$row["tipo_inmueble"];
						$otro_inmueble[$key]=$row["otro_inmueble"];
						$titular[$key]=$row["titular"];
						$titular_descr[$key]=$row["titular_descr"];
						$pct_propiedad[$key]=$row["pct_propiedad"];
						$sup_terreno[$key]=$row["sup_terreno"];
						$sup_construc[$key]=$row["sup_construc"];
						$adquisicion[$key]=$row["adquisicion"];
						$adquisicion_descr[$key]=$row["adquisicion_descr"];
						$forma_pago[$key]=$row["forma_pago"];
						$forma_descr[$key]=$row["forma_descr"];
						$tercero[$key]=$row["tercero"];
						$tercero_descr[$key]=$row["tercero_descr"];
						$nombre_tercero[$key]=$row["nombre_tercero"];
						$rfc_tercero[$key]=$row["rfc_tercero"];
						$transmisor[$key]=$row["transmisor"];
						$transmisor_descr[$key]=$row["transmisor_descr"];
						$nombre_transmisor[$key]=$row["nombre_transmisor"];
						$rfc_transmisor[$key]=$row["rfc_transmisor"];
						$relacion[$key]=$row["relacion"];
						$relacion_descr[$key]=$row["relacion_descr"];
						$otra_relacion[$key]=$row["otra_relacion"];
						$valor_adquisicion[$key]=$row["valor_adquisicion"];
						$tipo_moneda[$key]=$row["tipo_moneda"];
						$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
						$fecha_adquisicion2[$key]=format_date($row["fecha_adquisicion"]);
						$registro_publico[$key]=$row["registro_publico"];
						$valor_conforme_a[$key]=$row["valor_conforme_a"];
						$conforme_descr[$key]=$row["conforme_descr"];
						$ubicacion[$key]=$row["ubicacion"];
						$calle[$key]=$row["calle"];
						$num_exterior[$key]=$row["num_exterior"];
						$num_interior[$key]=$row["num_interior"];
						$colonia[$key]=$row["colonia"];
						$colonia_descr[$key]=$row["colonia_descr"];
						$municipio[$key]=$row["municipio"];
						$municipio_descr[$key]=$row["municipio_descr"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$codigopostal[$key]=$row["codigopostal"];
						$causa_baja[$key]=$row["causa_baja"];
						$otra_causa[$key]=$row["otra_causa"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_inmueble2[$key]=pg_escape_string($row["tipo_inmueble"]);
						$otro_inmueble2[$key]=pg_escape_string($row["otro_inmueble"]);
						$titular2[$key]=pg_escape_string($row["titular"]);
						$titular_descr2[$key]=pg_escape_string($row["titular_descr"]);
						$pct_propiedad2[$key]=pg_escape_string($row["pct_propiedad"]);
						$sup_terreno2[$key]=pg_escape_string($row["sup_terreno"]);
						$sup_construc2[$key]=pg_escape_string($row["sup_construc"]);
						$adquisicion2[$key]=pg_escape_string($row["adquisicion"]);
						$adquisicion_descr2[$key]=pg_escape_string($row["adquisicion_descr"]);
						$forma_pago2[$key]=pg_escape_string($row["forma_pago"]);
						$forma_descr2[$key]=pg_escape_string($row["forma_descr"]);
						$tercero2[$key]=pg_escape_string($row["tercero"]);
						$tercero_descr2[$key]=pg_escape_string($row["tercero_descr"]);
						$nombre_tercero2[$key]=pg_escape_string($row["nombre_tercero"]);
						$rfc_tercero2[$key]=pg_escape_string($row["rfc_tercero"]);
						$transmisor2[$key]=pg_escape_string($row["transmisor"]);
						$transmisor_descr2[$key]=pg_escape_string($row["transmisor_descr"]);
						$nombre_transmisor2[$key]=pg_escape_string($row["nombre_transmisor"]);
						$rfc_transmisor2[$key]=pg_escape_string($row["rfc_transmisor"]);
						$relacion2[$key]=pg_escape_string($row["relacion"]);
						$relacion_descr2[$key]=pg_escape_string($row["relacion_descr"]);
						$otra_relacion2[$key]=pg_escape_string($row["otra_relacion"]);
						$valor_adquisicion2[$key]=pg_escape_string($row["valor_adquisicion"]);
						$tipo_moneda2[$key]=pg_escape_string($row["tipo_moneda"]);
						$registro_publico2[$key]=pg_escape_string($row["registro_publico"]);
						$valor_conforme_a2[$key]=pg_escape_string($row["valor_conforme_a"]);
						$conforme_descr2[$key]=pg_escape_string($row["conforme_descr"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$calle2[$key]=pg_escape_string($row["calle"]);
						$num_exterior2[$key]=pg_escape_string($row["num_exterior"]);
						$num_interior2[$key]=pg_escape_string($row["num_interior"]);
						$colonia2[$key]=pg_escape_string($row["colonia"]);
						$colonia_descr2[$key]=pg_escape_string($row["colonia_descr"]);
						$municipio2[$key]=pg_escape_string($row["municipio"]);
						$municipio_descr2[$key]=pg_escape_string($row["municipio_descr"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$codigopostal2[$key]=pg_escape_string($row["codigopostal"]);
						$causa_baja2[$key]=pg_escape_string($row["causa_baja"]);
						$otra_causa2[$key]=pg_escape_string($row["otra_causa"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_inmuebles VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_inmueble2[$key]','$otro_inmueble2[$key]','$titular2[$key]','$titular_descr2[$key]',$pct_propiedad2[$key],$sup_terreno2[$key],$sup_construc2[$key],'$adquisicion2[$key]','$adquisicion_descr2[$key]','$forma_pago2[$key]','$forma_descr2[$key]','$tercero2[$key]','$tercero_descr2[$key]','$nombre_tercero2[$key]','$rfc_tercero2[$key]','$transmisor2[$key]','$transmisor_descr2[$key]','$nombre_transmisor2[$key]','$rfc_transmisor2[$key]','$relacion2[$key]','$relacion_descr2[$key]','$otra_relacion2[$key]',$valor_adquisicion2[$key],'$tipo_moneda2[$key]',$fecha_adquisicion2[$key],'$registro_publico2[$key]','$valor_conforme_a2[$key]','$conforme_descr2[$key]','$ubicacion2[$key]','$calle2[$key]','$num_exterior2[$key]','$num_interior2[$key]','$colonia2[$key]','$colonia_descr2[$key]','$municipio2[$key]','$municipio_descr2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$codigopostal2[$key]','$causa_baja2[$key]','$otra_causa2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}
		}
	}
	$sql="SELECT count(*) FROM qsy_inmuebles WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>10,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_inmueble" => $tipo_inmueble,
					"otro_inmueble"=>$otro_inmueble,
					"titular" => $titular,
					"titular_descr" => $titular_descr,
					"pct_propiedad" => $pct_propiedad,
					"sup_terreno" => $sup_terreno,
					"sup_construc" => $sup_construc,
					"adquisicion" => $adquisicion,
					"adquisicion_descr" => $adquisicion_descr,
					"forma_pago" => $forma_pago,
					"forma_descr" => $forma_descr,
					"tercero" => $tercero,
					"tercero_descr" =>$tercero_descr,
					"nombre_tercero" => $nombre_tercero,
					"rfc_tercero" => $rfc_tercero,
					"transmisor" => $transmisor,
					"transmisor_descr" => $transmisor_descr,
					"nombre_transmisor" => $nombre_transmisor,
					"rfc_transmisor" => $rfc_transmisor,
					"relacion" => $relacion,
					"relacion_descr" => $relacion_descr,
					"otra_relacion" => $otra_relacion,
					"valor_adquisicion" => $valor_adquisicion,
					"tipo_moneda" => $tipo_moneda,
					"fecha_adquisicion" => $fecha_adquisicion,
					"registro_publico" => $registro_publico,
					"valor_conforme_a" => $valor_conforme_a,
					"conforme_descr" => $conforme_descr,
					"ubicacion" => $ubicacion,
					"calle" => $calle,
					"num_exterior" => $num_exterior,
					"num_interior" => $num_interior,
					"colonia" => $colonia,
					"colonia_descr" => $colonia_descr,
					"municipio" => $municipio,
					"municipio_descr" => $municipio_descr,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"cp" => $codigopostal,
					"causa_baja" => $causa_baja,
					"otra_causa" => $otra_causa,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform11($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_vehiculos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["tipo_vehiculo"]==""){$html[$key].="- Tipo de vehiculo<br>";}
				if($row["titular"]==""){$html[$key].="- Titular<br>";}
				if($row["marca"]==""){$html[$key].="- Marca<br>";}
				if($row["modelo"]==""){$html[$key].="- Modelo<br>";}
				if($row["anio"]==""){$html[$key].="- Año<br>";}
				if($row["serie"]==""){$html[$key].="- No. de serie<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["pais"]==""){$html[$key].="- País<br>";}
				if($row["ubicacion"]=="M"){
					if(trim($row["estado_descr"])==""){$html[$key].="- Estado<br>";}
				}
				if($row["adquisicion"]==""){$html[$key].="- Forma de adquisición<br>";}
				if($row["forma_pago"]==""){$html[$key].="- Forma de pago<br>";}
				if($row["valor_adquisicion"]==""){$html[$key].="- Valor de adquisición<br>";}
				if($row["tipo_moneda"]==""){$html[$key].="- Tipo de moneda<br>";}
				if($row["fecha_adquisicion"]==""){$html[$key].="- Fecha de adquisición<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_vehiculos SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform11($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_vehiculo=array();
	$otro_vehiculo=array();
	$titular=array();
	$titular_descr=array();
	$tercero=array();
	$tercero_descr=array();
	$nombre_tercero=array();
	$rfc_tercero=array();
	$transmisor=array();
	$transmisor_descr=array();
	$nombre_transmisor=array();
	$rfc_transmisor=array();
	$relacion=array();
	$relacion_descr=array();
	$otra_relacion=array();
	$marca=array();
	$modelo=array();
	$anio=array();
	$serie=array();
	$ubicacion=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$adquisicion=array();
	$adquisicion_descr=array();
	$forma_pago=array();
	$forma_descr=array();
	$valor_adquisicion=array();
	$tipo_moneda=array();
	$fecha_adquisicion=array();
	$causa_baja=array();
	$otra_causa=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_vehiculos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_vehiculo[$key]=$row["tipo_vehiculo"];
				$otro_vehiculo[$key]=$row["otro_vehiculo"];
				$titular[$key]=$row["titular"];
				$titular_descr[$key]=$row["titular_descr"];
				$tercero[$key]=$row["tercero"];
				$tercero_descr[$key]=$row["tercero_descr"];
				$nombre_tercero[$key]=$row["nombre_tercero"];
				$rfc_tercero[$key]=$row["rfc_tercero"];
				$transmisor[$key]=$row["transmisor"];
				$transmisor_descr[$key]=$row["transmisor_descr"];
				$nombre_transmisor[$key]=$row["nombre_transmisor"];
				$rfc_transmisor[$key]=$row["rfc_transmisor"];
				$relacion[$key]=$row["relacion"];
				$relacion_descr[$key]=$row["relacion_descr"];
				$otra_relacion[$key]=$row["otra_relacion"];
				$marca[$key]=$row["marca"];
				$modelo[$key]=$row["modelo"];
				$anio[$key]=$row["anio"];
				$serie[$key]=$row["serie"];
				$ubicacion[$key]=$row["ubicacion"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$adquisicion[$key]=$row["adquisicion"];
				$adquisicion_descr[$key]=$row["adquisicion_descr"];
				$forma_pago[$key]=$row["forma_pago"];
				$forma_descr[$key]=$row["forma_descr"];
				$valor_adquisicion[$key]=$row["valor_adquisicion"];
				$tipo_moneda[$key]=$row["tipo_moneda"];
				$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
				$causa_baja[$key]=$row["causa_baja"];
				$otra_causa[$key]=$row["otra_causa"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_vehiculos b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_vehiculos WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_vehiculo[$key]=$row["tipo_vehiculo"];
						$otro_vehiculo[$key]=$row["otro_vehiculo"];
						$titular[$key]=$row["titular"];
						$titular_descr[$key]=$row["titular_descr"];
						$tercero[$key]=$row["tercero"];
						$tercero_descr[$key]=$row["tercero_descr"];
						$nombre_tercero[$key]=$row["nombre_tercero"];
						$rfc_tercero[$key]=$row["rfc_tercero"];
						$transmisor[$key]=$row["transmisor"];
						$transmisor_descr[$key]=$row["transmisor_descr"];
						$nombre_transmisor[$key]=$row["nombre_transmisor"];
						$rfc_transmisor[$key]=$row["rfc_transmisor"];
						$relacion[$key]=$row["relacion"];
						$relacion_descr[$key]=$row["relacion_descr"];
						$otra_relacion[$key]=$row["otra_relacion"];
						$marca[$key]=$row["marca"];
						$modelo[$key]=$row["modelo"];
						$anio[$key]=$row["anio"];
						$serie[$key]=$row["serie"];
						$ubicacion[$key]=$row["ubicacion"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$adquisicion[$key]=$row["adquisicion"];
						$adquisicion_descr[$key]=$row["adquisicion_descr"];
						$forma_pago[$key]=$row["forma_pago"];
						$forma_descr[$key]=$row["forma_descr"];
						$valor_adquisicion[$key]=$row["valor_adquisicion"];
						$tipo_moneda[$key]=$row["tipo_moneda"];
						$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
						$fecha_adquisicion2[$key]=format_date($row["fecha_adquisicion"]);
						$causa_baja[$key]=$row["causa_baja"];
						$otra_causa[$key]=$row["otra_causa"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_vehiculo2[$key]=pg_escape_string($row["tipo_vehiculo"]);
						$otro_vehiculo2[$key]=pg_escape_string($row["otro_vehiculo"]);
						$titular2[$key]=pg_escape_string($row["titular"]);
						$titular_descr2[$key]=pg_escape_string($row["titular_descr"]);
						$tercero2[$key]=pg_escape_string($row["tercero"]);
						$tercero_descr2[$key]=pg_escape_string($row["tercero_descr"]);
						$nombre_tercero2[$key]=pg_escape_string($row["nombre_tercero"]);
						$rfc_tercero2[$key]=pg_escape_string($row["rfc_tercero"]);
						$transmisor2[$key]=pg_escape_string($row["transmisor"]);
						$transmisor_descr2[$key]=pg_escape_string($row["transmisor_descr"]);
						$nombre_transmisor2[$key]=pg_escape_string($row["nombre_transmisor"]);
						$rfc_transmisor2[$key]=pg_escape_string($row["rfc_transmisor"]);
						$relacion2[$key]=pg_escape_string($row["relacion"]);
						$relacion_descr2[$key]=pg_escape_string($row["relacion_descr"]);
						$otra_relacion2[$key]=pg_escape_string($row["otra_relacion"]);
						$marca2[$key]=pg_escape_string($row["marca"]);
						$modelo2[$key]=pg_escape_string($row["modelo"]);
						$anio2[$key]=pg_escape_string($row["anio"]);
						$serie2[$key]=pg_escape_string($row["serie"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$adquisicion2[$key]=pg_escape_string($row["adquisicion"]);
						$adquisicion_descr2[$key]=pg_escape_string($row["adquisicion_descr"]);
						$forma_pago2[$key]=pg_escape_string($row["forma_pago"]);
						$forma_descr2[$key]=pg_escape_string($row["forma_descr"]);
						$valor_adquisicion2[$key]=pg_escape_string($row["valor_adquisicion"]);
						$tipo_moneda2[$key]=pg_escape_string($row["tipo_moneda"]);
						$causa_baja2[$key]=pg_escape_string($row["causa_baja"]);
						$otra_causa2[$key]=pg_escape_string($row["otra_causa"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_vehiculos VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_vehiculo2[$key]','$otro_vehiculo2[$key]','$titular2[$key]','$titular_descr2[$key]','$tercero2[$key]','$tercero_descr2[$key]','$nombre_tercero2[$key]','$rfc_tercero2[$key]','$transmisor2[$key]','$transmisor_descr2[$key]','$nombre_transmisor2[$key]','$rfc_transmisor2[$key]','$relacion2[$key]','$relacion_descr2[$key]','$otra_relacion2[$key]','$marca2[$key]','$modelo2[$key]',$anio2[$key],'$serie2[$key]','$ubicacion2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$adquisicion2[$key]','$adquisicion_descr2[$key]','$forma_pago2[$key]','$forma_descr2[$key]',$valor_adquisicion2[$key],'$tipo_moneda2[$key]',$fecha_adquisicion2[$key],'$causa_baja2[$key]','$otra_causa2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_vehiculos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>11,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_vehiculo" => $tipo_vehiculo,
					"otro_vehiculo"=>$otro_vehiculo,
					"titular" => $titular,
					"titular_descr" => $titular_descr,
					"tercero" => $tercero,
					"tercero_descr" =>$tercero_descr,
					"nombre_tercero" => $nombre_tercero,
					"rfc_tercero" => $rfc_tercero,
					"transmisor" => $transmisor,
					"transmisor_descr" => $transmisor_descr,
					"nombre_transmisor" => $nombre_transmisor,
					"rfc_transmisor" => $rfc_transmisor,
					"relacion" => $relacion,
					"relacion_descr" => $relacion_descr,
					"otra_relacion" => $otra_relacion,
					"marca" => $marca,
					"modelo" => $modelo,
					"anio" => $anio,
					"serie" => $serie,
					"ubicacion" => $ubicacion,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"adquisicion" => $adquisicion,
					"adquisicion_descr" => $adquisicion_descr,
					"forma_pago" => $forma_pago,
					"forma_descr" => $forma_descr,
					"valor_adquisicion" => $valor_adquisicion,
					"tipo_moneda" => $tipo_moneda,
					"fecha_adquisicion" => $fecha_adquisicion,
					"causa_baja" => $causa_baja,
					"otra_causa" => $otra_causa,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform12($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_muebles WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["tipo_mueble"]==""){$html[$key].="- Tipo de mueble<br>";}
				if($row["titular"]==""){$html[$key].="- Titular<br>";}
				if($row["descripcion"]==""){$html[$key].="- Descripción<br>";}
				if($row["adquisicion"]==""){$html[$key].="- Forma de adquisición<br>";}
				if($row["forma_pago"]==""){$html[$key].="- Forma de pago<br>";}
				if($row["valor_adquisicion"]==""){$html[$key].="- Valor de adquisición<br>";}
				if($row["tipo_moneda"]==""){$html[$key].="- Tipo de moneda<br>";}
				if($row["fecha_adquisicion"]==""){$html[$key].="- Fecha de adquisición<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_muebles SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform12($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_mueble=array();
	$tipo_descr=array();
	$titular=array();
	$titular_descr=array();
	$tercero=array();
	$tercero_descr=array();
	$nombre_tercero=array();
	$rfc_tercero=array();
	$transmisor=array();
	$transmisor_descr=array();
	$nombre_transmisor=array();
	$rfc_transmisor=array();
	$relacion=array();
	$relacion_descr=array();
	$otra_relacion=array();
	$descripcion=array();
	$adquisicion=array();
	$adquisicion_descr=array();
	$forma_pago=array();
	$forma_descr=array();
	$valor_adquisicion=array();
	$tipo_moneda=array();
	$fecha_adquisicion=array();
	$causa_baja=array();
	$otra_causa=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_muebles WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_mueble[$key]=$row["tipo_mueble"];
				$tipo_descr[$key]=$row["tipo_descr"];
				$titular[$key]=$row["titular"];
				$titular_descr[$key]=$row["titular_descr"];
				$tercero[$key]=$row["tercero"];
				$tercero_descr[$key]=$row["tercero_descr"];
				$nombre_tercero[$key]=$row["nombre_tercero"];
				$rfc_tercero[$key]=$row["rfc_tercero"];
				$transmisor[$key]=$row["transmisor"];
				$transmisor_descr[$key]=$row["transmisor_descr"];
				$nombre_transmisor[$key]=$row["nombre_transmisor"];
				$rfc_transmisor[$key]=$row["rfc_transmisor"];
				$relacion[$key]=$row["relacion"];
				$relacion_descr[$key]=$row["relacion_descr"];
				$otra_relacion[$key]=$row["otra_relacion"];
				$descripcion[$key]=$row["descripcion"];
				$adquisicion[$key]=$row["adquisicion"];
				$adquisicion_descr[$key]=$row["adquisicion_descr"];
				$forma_pago[$key]=$row["forma_pago"];
				$forma_descr[$key]=$row["forma_descr"];
				$valor_adquisicion[$key]=$row["valor_adquisicion"];
				$tipo_moneda[$key]=$row["tipo_moneda"];
				$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
				$causa_baja[$key]=$row["causa_baja"];
				$otra_causa[$key]=$row["otra_causa"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_muebles b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_muebles WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_mueble[$key]=$row["tipo_mueble"];
						$tipo_descr[$key]=$row["tipo_descr"];
						$titular[$key]=$row["titular"];
						$titular_descr[$key]=$row["titular_descr"];
						$tercero[$key]=$row["tercero"];
						$tercero_descr[$key]=$row["tercero_descr"];
						$nombre_tercero[$key]=$row["nombre_tercero"];
						$rfc_tercero[$key]=$row["rfc_tercero"];
						$transmisor[$key]=$row["transmisor"];
						$transmisor_descr[$key]=$row["transmisor_descr"];
						$nombre_transmisor[$key]=$row["nombre_transmisor"];
						$rfc_transmisor[$key]=$row["rfc_transmisor"];
						$relacion[$key]=$row["relacion"];
						$relacion_descr[$key]=$row["relacion_descr"];
						$otra_relacion[$key]=$row["otra_relacion"];
						$descripcion[$key]=$row["descripcion"];
						$adquisicion[$key]=$row["adquisicion"];
						$adquisicion_descr[$key]=$row["adquisicion_descr"];
						$forma_pago[$key]=$row["forma_pago"];
						$forma_descr[$key]=$row["forma_descr"];
						$valor_adquisicion[$key]=$row["valor_adquisicion"];
						$tipo_moneda[$key]=$row["tipo_moneda"];
						$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
						$fecha_adquisicion2[$key]=format_date($row["fecha_adquisicion"]);
						$causa_baja[$key]=$row["causa_baja"];
						$otra_causa[$key]=$row["otra_causa"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_mueble2[$key]=pg_escape_string($row["tipo_mueble"]);
						$tipo_descr2[$key]=pg_escape_string($row["tipo_descr"]);
						$titular2[$key]=pg_escape_string($row["titular"]);
						$titular_descr2[$key]=pg_escape_string($row["titular_descr"]);
						$tercero2[$key]=pg_escape_string($row["tercero"]);
						$tercero_descr2[$key]=pg_escape_string($row["tercero_descr"]);
						$nombre_tercero2[$key]=pg_escape_string($row["nombre_tercero"]);
						$rfc_tercero2[$key]=pg_escape_string($row["rfc_tercero"]);
						$transmisor2[$key]=pg_escape_string($row["transmisor"]);
						$transmisor_descr2[$key]=pg_escape_string($row["transmisor_descr"]);
						$nombre_transmisor2[$key]=pg_escape_string($row["nombre_transmisor"]);
						$rfc_transmisor2[$key]=pg_escape_string($row["rfc_transmisor"]);
						$relacion2[$key]=pg_escape_string($row["relacion"]);
						$relacion_descr2[$key]=pg_escape_string($row["relacion_descr"]);
						$otra_relacion2[$key]=pg_escape_string($row["otra_relacion"]);
						$descripcion2[$key]=pg_escape_string($row["descripcion"]);
						$adquisicion2[$key]=pg_escape_string($row["adquisicion"]);
						$adquisicion_descr2[$key]=pg_escape_string($row["adquisicion_descr"]);
						$forma_pago2[$key]=pg_escape_string($row["forma_pago"]);
						$forma_descr2[$key]=pg_escape_string($row["forma_descr"]);
						$valor_adquisicion2[$key]=pg_escape_string($row["valor_adquisicion"]);
						$tipo_moneda2[$key]=pg_escape_string($row["tipo_moneda"]);
						$causa_baja2[$key]=pg_escape_string($row["causa_baja"]);
						$otra_causa2[$key]=pg_escape_string($row["otra_causa"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_muebles VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_mueble2[$key]','$tipo_descr2[$key]','$titular2[$key]','$titular_descr2[$key]','$tercero2[$key]','$tercero_descr2[$key]','$nombre_tercero2[$key]','$rfc_tercero2[$key]','$transmisor2[$key]','$transmisor_descr2[$key]','$nombre_transmisor2[$key]','$rfc_transmisor2[$key]','$relacion2[$key]','$relacion_descr2[$key]','$otra_relacion2[$key]','$descripcion2[$key]','$adquisicion2[$key]','$adquisicion_descr2[$key]','$forma_pago2[$key]','$forma_descr2[$key]',$valor_adquisicion2[$key],'$tipo_moneda2[$key]',$fecha_adquisicion2[$key],'$causa_baja2[$key]','$otra_causa2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_muebles WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>12,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_mueble" => $tipo_mueble,
					"tipo_descr"=>$tipo_descr,
					"titular" => $titular,
					"titular_descr" => $titular_descr,
					"tercero" => $tercero,
					"tercero_descr" =>$tercero_descr,
					"nombre_tercero" => $nombre_tercero,
					"rfc_tercero" => $rfc_tercero,
					"transmisor" => $transmisor,
					"transmisor_descr" => $transmisor_descr,
					"nombre_transmisor" => $nombre_transmisor,
					"rfc_transmisor" => $rfc_transmisor,
					"relacion" => $relacion,
					"relacion_descr" => $relacion_descr,
					"otra_relacion" => $otra_relacion,
					"descripcion" => $descripcion,
					"adquisicion" => $adquisicion,
					"adquisicion_descr" => $adquisicion_descr,
					"forma_pago" => $forma_pago,
					"forma_descr" => $forma_descr,
					"valor_adquisicion" => $valor_adquisicion,
					"tipo_moneda" => $tipo_moneda,
					"fecha_adquisicion" => $fecha_adquisicion,
					"causa_baja" => $causa_baja,
					"otra_causa" => $otra_causa,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform13($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_inversiones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["tipo_inversion"]==""){$html[$key].="- Tipo de inversión<br>";}
				if($row["tipo_inversion"]=="B" && $row["bancaria"]==""){$html[$key].="- Bancaria<br>";}
				if($row["tipo_inversion"]=="F" && $row["fondo"]==""){$html[$key].="- Fondos de inversión<br>";}
				if($row["tipo_inversion"]=="O" && $row["org_privada"]==""){$html[$key].="- Organizaciones privadas<br>";}
				if($row["tipo_inversion"]=="M" && $row["monedas"]==""){$html[$key].="- Posesión de monedas<br>";}
				if($row["tipo_inversion"]=="S" && $row["seguros"]==""){$html[$key].="- Seguros<br>";}
				if($row["tipo_inversion"]=="V" && $row["valor_bursatil"]==""){$html[$key].="- Valores bursátiles<br>";}
				if($row["tipo_inversion"]=="A" && $row["afores"]==""){$html[$key].="- Afores<br>";}
				if($row["titular"]==""){$html[$key].="- Titular<br>";}
				if($row["num_cta"]==""){$html[$key].="- Número de cuenta<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["pais"]==""){$html[$key].="- País<br>";}
				if($row["razon_social"]==""){$html[$key].="- Razón social<br>";}
				if($row["rfc_institucion"]==""){$html[$key].="- RFC de la institución<br>";}
				if($row["saldo"]==""){$html[$key].="- Saldo<br>";}
				if($row["tipo_moneda"]==""){$html[$key].="- Tipo de moneda<br>";}

			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_inversiones SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform13($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_inversion=array();
	$tipo_inver_descr=array();
	$bancaria=array();
	$bancaria_descr=array();
	$fondo=array();
	$fondo_descr=array();
	$org_privada=array();
	$org_descr=array();
	$monedas=array();
	$monedas_descr=array();
	$seguros=array();
	$seguros_descr=array();
	$valor_bursatil=array();
	$valor_descr=array();
	$afores=array();
	$afores_descr=array();
	$titular=array();
	$titular_descr=array();
	$tercero=array();
	$tercero_descr=array();
	$nombre_tercero=array();
	$rfc_tercero=array();
	$num_cta=array();
	$ubicacion=array();
	$razon_social=array();
	$rfc_institucion=array();
	$pais=array();
	$pais_descr=array();
	$saldo=array();
	$tipo_moneda=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_inversiones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_inversion[$key]=$row["tipo_inversion"];
				$tipo_inversion_descr[$key]=$row["tipo_inver_descr"];
				$bancaria[$key]=$row["bancaria"];
				$bancaria_descr[$key]=$row["bancaria_descr"];
				$fondo[$key]=$row["fondo"];
				$fondo_descr[$key]=$row["fondo_descr"];
				$org_privada[$key]=$row["org_privada"];
				$org_descr[$key]=$row["org_descr"];
				$monedas[$key]=$row["monedas"];
				$monedas_descr[$key]=$row["monedas_descr"];
				$seguros[$key]=$row["seguros"];
				$seguros_descr[$key]=$row["seguros_descr"];
				$valor_bursatil[$key]=$row["valor_bursatil"];
				$valor_descr[$key]=$row["valor_descr"];
				$afores[$key]=$row["afores"];
				$afores_descr[$key]=$row["afores_descr"];
				$titular[$key]=$row["titular"];
				$titular_descr[$key]=$row["titular_descr"];
				$tercero[$key]=$row["tercero"];
				$tercero_descr[$key]=$row["tercero_descr"];
				$nombre_tercero[$key]=$row["nombre_tercero"];
				$rfc_tercero[$key]=$row["rfc_tercero"];
				$num_cta[$key]=$row["num_cta"];
				$ubicacion[$key]=$row["ubicacion"];
				$razon_social[$key]=$row["razon_social"];
				$rfc_institucion[$key]=$row["rfc_institucion"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$saldo[$key]=$row["saldo"];
				$tipo_moneda[$key]=$row["tipo_moneda"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_inversiones b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_inversiones WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_inversion[$key]=$row["tipo_inversion"];
						$tipo_inversion_descr[$key]=$row["tipo_inver_descr"];
						$bancaria[$key]=$row["bancaria"];
						$bancaria_descr[$key]=$row["bancaria_descr"];
						$fondo[$key]=$row["fondo"];
						$fondo_descr[$key]=$row["fondo_descr"];
						$org_privada[$key]=$row["org_privada"];
						$org_descr[$key]=$row["org_descr"];
						$monedas[$key]=$row["monedas"];
						$monedas_descr[$key]=$row["monedas_descr"];
						$seguros[$key]=$row["seguros"];
						$seguros_descr[$key]=$row["seguros_descr"];
						$valor_bursatil[$key]=$row["valor_bursatil"];
						$valor_descr[$key]=$row["valor_descr"];
						$afores[$key]=$row["afores"];
						$afores_descr[$key]=$row["afores_descr"];
						$titular[$key]=$row["titular"];
						$titular_descr[$key]=$row["titular_descr"];
						$tercero[$key]=$row["tercero"];
						$tercero_descr[$key]=$row["tercero_descr"];
						$nombre_tercero[$key]=$row["nombre_tercero"];
						$rfc_tercero[$key]=$row["rfc_tercero"];
						$num_cta[$key]=$row["num_cta"];
						$ubicacion[$key]=$row["ubicacion"];
						$razon_social[$key]=$row["razon_social"];
						$rfc_institucion[$key]=$row["rfc_institucion"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$saldo[$key]=$row["saldo"];
						$tipo_moneda[$key]=$row["tipo_moneda"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_inversion2[$key]=pg_escape_string($row["tipo_inversion"]);
						$tipo_inversion_descr2[$key]=pg_escape_string($row["tipo_inver_descr"]);
						$bancaria2[$key]=pg_escape_string($row["bancaria"]);
						$bancaria_descr2[$key]=pg_escape_string($row["bancaria_descr"]);
						$fondo2[$key]=pg_escape_string($row["fondo"]);
						$fondo_descr2[$key]=pg_escape_string($row["fondo_descr"]);
						$org_privada2[$key]=pg_escape_string($row["org_privada"]);
						$org_descr2[$key]=pg_escape_string($row["org_descr"]);
						$monedas2[$key]=pg_escape_string($row["monedas"]);
						$monedas_descr2[$key]=pg_escape_string($row["monedas_descr"]);
						$seguros2[$key]=pg_escape_string($row["seguros"]);
						$seguros_descr2[$key]=pg_escape_string($row["seguros_descr"]);
						$valor_bursatil2[$key]=pg_escape_string($row["valor_bursatil"]);
						$valor_descr2[$key]=pg_escape_string($row["valor_descr"]);
						$afores2[$key]=pg_escape_string($row["afores"]);
						$afores_descr2[$key]=pg_escape_string($row["afores_descr"]);
						$titular2[$key]=pg_escape_string($row["titular"]);
						$titular_descr2[$key]=pg_escape_string($row["titular_descr"]);
						$tercero2[$key]=pg_escape_string($row["tercero"]);
						$tercero_descr2[$key]=pg_escape_string($row["tercero_descr"]);
						$nombre_tercero2[$key]=pg_escape_string($row["nombre_tercero"]);
						$rfc_tercero2[$key]=pg_escape_string($row["rfc_tercero"]);
						$num_cta2[$key]=pg_escape_string($row["num_cta"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$razon_social2[$key]=pg_escape_string($row["razon_social"]);
						$rfc_institucion2[$key]=pg_escape_string($row["rfc_institucion"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$saldo2[$key]=pg_escape_string($row["saldo"]);
						$tipo_moneda2[$key]=pg_escape_string($row["tipo_moneda"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_inversiones VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_inversion2[$key]','$tipo_inversion_descr2[$key]','$bancaria2[$key]','$bancaria_descr2[$key]','$fondo2[$key]','$fondo_descr2[$key]','$org_privada2[$key]','$org_descr2[$key]','$monedas2[$key]','$monedas_descr2[$key]','$seguros2[$key]','$seguros_descr2[$key]','$valor_bursatil2[$key]','$valor_descr2[$key]','$afores2[$key]','$afores_descr2[$key]','$titular2[$key]','$titular_descr2[$key]','$tercero2[$key]','$tercero_descr2[$key]','$nombre_tercero2[$key]','$rfc_tercero2[$key]','$num_cta2[$key]','$ubicacion2[$key]','$razon_social2[$key]','$rfc_institucion2[$key]','$pais2[$key]','$pais_descr2[$key]',$saldo2[$key],'$tipo_moneda2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_inversiones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>13,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_inversion" => $tipo_inversion,
					"tipo_inver_descr"=>$tipo_inver_descr,
					"bancaria" => $bancaria,
					"bancaria_descr" => $bancaria_descr,
					"fondo" => $fondo,
					"fondo_descr" => $fondo_descr,
					"org_privada" => $org_privada,
					"org_descr" => $org_descr,
					"monedas" => $monedas,
					"monedas_descr"=>$monedas_descr,
					"seguros" => $seguros,
					"seguros_descr" => $seguros_descr,
					"valor_bursatil" => $valor_bursatil,
					"valor_descr" => $valor_descr,
					"afores" => $afores,
					"afores_descr" => $afores_descr,
					"titular" => $titular,
					"titular_descr" => $titular_descr,
					"tercero" => $tercero,
					"tercero_descr" =>$tercero_descr,
					"nombre_tercero" => $nombre_tercero,
					"rfc_tercero" => $rfc_tercero,
					"num_cta" => $num_cta,
					"ubicacion" => $ubicacion,
					"razon_social" => $razon_social,
					"rfc_institucion" => $rfc_institucion,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"saldo" => $saldo,
					"tipo_moneda" => $tipo_moneda,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform14($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_adeudos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["tipo_adeudo"]==""){$html[$key].="- Tipo de adeudo<br>";}
				if($row["titular"]==""){$html[$key].="- Titular<br>";}
				if($row["num_cta"]==""){$html[$key].="- Número de cuenta<br>";}
				if($row["fecha_adquisicion"]==""){$html[$key].="- Fecha de adquisición<br>";}
				if($row["monto_original"]==""){$html[$key].="- Monto original<br>";}
				if($row["tipo_moneda"]==""){$html[$key].="- Tipo de moneda<br>";}
				if($row["saldo"]==""){$html[$key].="- Saldo<br>";}
				if($row["otorgante"]==""){$html[$key].="- Otorgante<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["pais"]==""){$html[$key].="- País<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_adeudos SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform14($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_adeudo=array();
	$tipo_adeudo_descr=array();
	$otro_adeudo=array();
	$titular=array();
	$titular_descr=array();
	$tercero=array();
	$tercero_descr=array();
	$nombre_tercero=array();
	$rfc_tercero=array();
	$num_cta=array();
	$fecha_adquisicion=array();
	$monto_original=array();
	$tipo_moneda=array();
	$saldo=array();
	$otorgante=array();
	$otorgante_descr=array();
	$razon_social=array();
	$rfc_institucion=array();
	$ubicacion=array();
	$pais=array();
	$pais_descr=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_adeudos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_adeudo[$key]=$row["tipo_adeudo"];
				$tipo_adeudo_descr[$key]=$row["tipo_adeudo_descr"];
				$otro_adeudo[$key]=$row["otro_adeudo"];
				$titular[$key]=$row["titular"];
				$titular_descr[$key]=$row["titular_descr"];
				$tercero[$key]=$row["tercero"];
				$tercero_descr[$key]=$row["tercero_descr"];
				$nombre_tercero[$key]=$row["nombre_tercero"];
				$rfc_tercero[$key]=$row["rfc_tercero"];
				$num_cta[$key]=$row["num_cta"];
				$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
				$monto_original[$key]=$row["monto_original"];
				$tipo_moneda[$key]=$row["tipo_moneda"];
				$saldo[$key]=$row["saldo"];
				$otorgante[$key]=$row["otorgante"];
				$otorgante_descr[$key]=$row["otorgante_descr"];
				$razon_social[$key]=$row["razon_social"];
				$rfc_institucion[$key]=$row["rfc_institucion"];
				$ubicacion[$key]=$row["ubicacion"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_adeudos b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_adeudos WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_adeudo[$key]=$row["tipo_adeudo"];
						$tipo_adeudo_descr[$key]=$row["tipo_adeudo_descr"];
						$otro_adeudo[$key]=$row["otro_adeudo"];
						$titular[$key]=$row["titular"];
						$titular_descr[$key]=$row["titular_descr"];
						$tercero[$key]=$row["tercero"];
						$tercero_descr[$key]=$row["tercero_descr"];
						$nombre_tercero[$key]=$row["nombre_tercero"];
						$rfc_tercero[$key]=$row["rfc_tercero"];
						$num_cta[$key]=$row["num_cta"];
						$fecha_adquisicion[$key]=$row["fecha_adquisicion"];
						$fecha_adquisicion2[$key]=format_date($row["fecha_adquisicion"]);
						$monto_original[$key]=$row["monto_original"];
						$tipo_moneda[$key]=$row["tipo_moneda"];
						$saldo[$key]=$row["saldo"];
						$otorgante[$key]=$row["otorgante"];
						$otorgante_descr[$key]=$row["otorgante_descr"];
						$razon_social[$key]=$row["razon_social"];
						$rfc_institucion[$key]=$row["rfc_institucion"];
						$ubicacion[$key]=$row["ubicacion"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_adeudo2[$key]=pg_escape_string($row["tipo_adeudo"]);
						$tipo_adeudo_descr2[$key]=pg_escape_string($row["tipo_adeudo_descr"]);
						$otro_adeudo2[$key]=pg_escape_string($row["otro_adeudo"]);
						$titular2[$key]=pg_escape_string($row["titular"]);
						$titular_descr2[$key]=pg_escape_string($row["titular_descr"]);
						$tercero2[$key]=pg_escape_string($row["tercero"]);
						$tercero_descr2[$key]=pg_escape_string($row["tercero_descr"]);
						$nombre_tercero2[$key]=pg_escape_string($row["nombre_tercero"]);
						$rfc_tercero2[$key]=pg_escape_string($row["rfc_tercero"]);
						$num_cta2[$key]=pg_escape_string($row["num_cta"]);
						$monto_original2[$key]=pg_escape_string($row["monto_original"]);
						$tipo_moneda2[$key]=pg_escape_string($row["tipo_moneda"]);
						$saldo2[$key]=pg_escape_string($row["saldo"]);
						$otorgante2[$key]=pg_escape_string($row["otorgante"]);
						$otorgante_descr2[$key]=pg_escape_string($row["otorgante_descr"]);
						$razon_social2[$key]=pg_escape_string($row["razon_social"]);
						$rfc_institucion2[$key]=pg_escape_string($row["rfc_institucion"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_adeudos VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_adeudo2[$key]','$tipo_adeudo_descr2[$key]','$otro_adeudo2[$key]','$titular2[$key]','$titular_descr2[$key]','$tercero2[$key]','$tercero_descr2[$key]','$nombre_tercero2[$key]','$rfc_tercero2[$key]','$num_cta2[$key]',$fecha_adquisicion2[$key],$monto_original2[$key],'$tipo_moneda2[$key]',$saldo2[$key],'$otorgante2[$key]','$otorgante_descr2[$key]','$razon_social2[$key]','$rfc_institucion2[$key]','$ubicacion2[$key]','$pais2[$key]','$pais_descr2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}
		}
	}
	$sql="SELECT count(*) FROM qsy_adeudos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>14,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_adeudo" => $tipo_adeudo,
					"tipo_adeudo_descr"=>$tipo_adeudo_descr,
					"otro_adeudo" => $otro_adeudo,
					"titular" => $titular,
					"titular_descr" => $titular_descr,
					"tercero" => $tercero,
					"tercero_descr" =>$tercero_descr,
					"nombre_tercero" => $nombre_tercero,
					"rfc_tercero" => $rfc_tercero,
					"num_cta" => $num_cta,
					"fecha_adquisicion" => $fecha_adquisicion,
					"monto_original" => $monto_original,
					"tipo_moneda" => $tipo_moneda,
					"saldo" => $saldo,
					"otorgante" => $otorgante,
					"otorgante_descr" => $otorgante_descr,
					"razon_social" => $razon_social,
					"rfc_institucion" => $rfc_institucion,
					"ubicacion" => $ubicacion,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkform15($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_comodatos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["tipo_comodato"]==""){$html[$key].="- Tipo de comodato<br>";}
				if($row["tipo_comodato"]=="I"){
					if($row["tipo_inmueble"]==""){$html[$key].="- Tipo de inmueble<br>";}
					if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
					if($row["pais"]==""){$html[$key].="- País<br>";}
					if($row["ubicacion"]=="M"){
						if(trim($row["codigopostal"])==""){$html[$key].="- Código postal<br>";}
						if(trim($row["estado_descr"])==""){$html[$key].="- Estado<br>";}
						if(trim($row["municipio_descr"])==""){$html[$key].="- Municipio<br>";}
						if(trim($row["colonia_descr"])==""){$html[$key].="- Colonia<br>";}
						if(trim($row["calle"])==""){$html[$key].="- Calle<br>";}
						if(trim($row["num_exterior"])==""){$html[$key].="- No. exterior<br>";}
					}
				}
				if($row["tipo_comodato"]=="V"){
					if($row["tipo_vehiculo"]==""){$html[$key].="- Tipo de vehiculo<br>";}
					if($row["marca"]==""){$html[$key].="- Marca<br>";}
					if($row["modelo"]==""){$html[$key].="- Modelo<br>";}
					if($row["anio"]==""){$html[$key].="- Año<br>";}
					if($row["serie"]==""){$html[$key].="- No. de serie<br>";}
					if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
					if($row["pais"]==""){$html[$key].="- País<br>";}
					if($row["ubicacion"]=="M"){
						if(trim($row["estado_descr"])==""){$html[$key].="- Estado<br>";}
					}
					if($row["dueno"]==""){$html[$key].="- Titular<br>";}
					if($row["nombre_dueno"]==""){$html[$key].="- Nombre del titular<br>";}
					if($row["rfc_dueno"]==""){$html[$key].="- RFC del titular<br>";}
					if($row["relacion"]==""){$html[$key].="- Relación con el titular<br>";}
				}
			}

			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_comodatos SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarform15($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_comodato=array();
	$tipo_inmueble=array();
	$otro_inmueble=array();
	$ubicacion=array();
	$calle=array();
	$num_exterior=array();
	$num_interior=array();
	$colonia=array();
	$colonia_descr=array();
	$municipio=array();
	$municipio_descr=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$codigopostal=array();
	$tipo_vehiculo=array();
	$otro_vehiculo=array();
	$marca=array();
	$modelo=array();
	$anio=array();
	$serie=array();
	$dueno=array();
	$dueno_descr=array();
	$nombre_dueno=array();
	$rfc_dueno=array();
	$relacion=array();
	$relacion_descr=array();
	$otra_relacion=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_comodatos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_comodato[$key]=$row["tipo_comodato"];
				$tipo_inmueble[$key]=$row["tipo_inmueble"];
				$otro_inmueble[$key]=$row["otro_inmueble"];
				$ubicacion[$key]=$row["ubicacion"];
				$calle[$key]=$row["calle"];
				$num_exterior[$key]=$row["num_exterior"];
				$num_interior[$key]=$row["num_interior"];
				$colonia[$key]=$row["colonia"];
				$colonia_descr[$key]=$row["colonia_descr"];
				$municipio[$key]=$row["municipio"];
				$municipio_descr[$key]=$row["municipio_descr"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$codigopostal[$key]=$row["codigopostal"];
				$tipo_vehiculo[$key]=$row["tipo_vehiculo"];
				$otro_vehiculo[$key]=$row["otro_vehiculo"];
				$marca[$key]=$row["marca"];
				$modelo[$key]=$row["modelo"];
				$anio[$key]=$row["anio"];
				$serie[$key]=$row["serie"];
				$dueno[$key]=$row["dueno"];
				$dueno_descr[$key]=$row["dueno_descr"];
				$nombre_dueno[$key]=$row["nombre_dueno"];
				$rfc_dueno[$key]=$row["rfc_dueno"];
				$relacion[$key]=$row["relacion"];
				$relacion_descr[$key]=$row["relacion_descr"];
				$otra_relacion[$key]=$row["otra_relacion"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_comodatos b WHERE a.rfc='$rfc' and a.declaracion='P' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_comodatos WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='P' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_comodato[$key]=$row["tipo_comodato"];
						$tipo_inmueble[$key]=$row["tipo_inmueble"];
						$otro_inmueble[$key]=$row["otro_inmueble"];
						$ubicacion[$key]=$row["ubicacion"];
						$calle[$key]=$row["calle"];
						$num_exterior[$key]=$row["num_exterior"];
						$num_interior[$key]=$row["num_interior"];
						$colonia[$key]=$row["colonia"];
						$colonia_descr[$key]=$row["colonia_descr"];
						$municipio[$key]=$row["municipio"];
						$municipio_descr[$key]=$row["municipio_descr"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$codigopostal[$key]=$row["codigopostal"];
						$tipo_vehiculo[$key]=$row["tipo_vehiculo"];
						$otro_vehiculo[$key]=$row["otro_vehiculo"];
						$marca[$key]=$row["marca"];
						$modelo[$key]=$row["modelo"];
						$anio[$key]=$row["anio"];
						if($anio[$key]=="")$anio[$key]=0;
						$serie[$key]=$row["serie"];
						$dueno[$key]=$row["dueno"];
						$dueno_descr[$key]=$row["dueno_descr"];
						$nombre_dueno[$key]=$row["nombre_dueno"];
						$rfc_dueno[$key]=$row["rfc_dueno"];
						$relacion[$key]=$row["relacion"];
						$relacion_descr[$key]=$row["relacion_descr"];
						$otra_relacion[$key]=$row["otra_relacion"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_comodato2[$key]=pg_escape_string($row["tipo_comodato"]);
						$tipo_inmueble2[$key]=pg_escape_string($row["tipo_inmueble"]);
						$otro_inmueble2[$key]=pg_escape_string($row["otro_inmueble"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$calle2[$key]=pg_escape_string($row["calle"]);
						$num_exterior2[$key]=pg_escape_string($row["num_exterior"]);
						$num_interior2[$key]=pg_escape_string($row["num_interior"]);
						$colonia2[$key]=pg_escape_string($row["colonia"]);
						$colonia_descr2[$key]=pg_escape_string($row["colonia_descr"]);
						$municipio2[$key]=pg_escape_string($row["municipio"]);
						$municipio_descr2[$key]=pg_escape_string($row["municipio_descr"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$codigopostal2[$key]=pg_escape_string($row["codigopostal"]);
						$tipo_vehiculo2[$key]=pg_escape_string($row["tipo_vehiculo"]);
						$otro_vehiculo2[$key]=pg_escape_string($row["otro_vehiculo"]);
						$marca2[$key]=pg_escape_string($row["marca"]);
						$modelo2[$key]=pg_escape_string($row["modelo"]);
						$anio2[$key]=pg_escape_string($row["anio"]);
						if($anio2[$key]=="")$anio2[$key]=0;
						$serie2[$key]=pg_escape_string($row["serie"]);
						$dueno2[$key]=pg_escape_string($row["dueno"]);
						$dueno_descr2[$key]=pg_escape_string($row["dueno_descr"]);
						$nombre_dueno2[$key]=pg_escape_string($row["nombre_dueno"]);
						$rfc_dueno2[$key]=pg_escape_string($row["rfc_dueno"]);
						$relacion2[$key]=pg_escape_string($row["relacion"]);
						$relacion_descr2[$key]=pg_escape_string($row["relacion_descr"]);
						$otra_relacion2[$key]=pg_escape_string($row["otra_relacion"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_comodatos VALUES ('$rfc',$ejercicio,'P','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_comodato2[$key]','$tipo_inmueble2[$key]','$otro_inmueble2[$key]','$ubicacion2[$key]','$calle2[$key]','$num_exterior2[$key]','$num_interior2[$key]','$colonia2[$key]','$colonia_descr2[$key]','$municipio2[$key]','$municipio_descr2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$codigopostal2[$key]','$tipo_vehiculo2[$key]','$otro_vehiculo2[$key]','$marca2[$key]','$modelo2[$key]',$anio2[$key],'$serie2[$key]','$dueno2[$key]','$dueno_descr2[$key]','$nombre_dueno2[$key]','$rfc_dueno2[$key]','$relacion2[$key]','$relacion_descr2[$key]','$otra_relacion2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_comodatos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='P'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>15,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_comodato" => $tipo_comodato,
					"tipo_inmueble" => $tipo_inmueble,
					"otro_inmueble" => $otro_inmueble,
					"ubicacion" => $ubicacion,
					"calle" => $calle,
					"num_exterior" => $num_exterior,
					"num_interior" => $num_interior,
					"colonia" => $colonia,
					"colonia_descr" => $colonia_descr,
					"municipio" => $municipio,
					"municipio_descr" => $municipio_descr,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"cp" => $codigopostal,
					"tipo_vehiculo" => $tipo_vehiculo,
					"otro_vehiculo" => $otro_vehiculo,
					"marca" => $marca,
					"modelo" => $modelo,
					"anio" => $anio,
					"serie" => $serie,
					"dueno" => $dueno,
					"dueno_descr" =>$dueno_descr,
					"nombre_dueno" => $nombre_dueno,
					"rfc_dueno" => $rfc_dueno,
					"relacion" => $relacion,
					"relacion_descr" => $relacion_descr,
					"otra_relacion" => $otra_relacion,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkformi1($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_participa_empresas WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["declarante"]==""){$html[$key].="- Participante<br>";}
				if($row["nombre_empresa"]==""){$html[$key].="- Nombre de la empresa<br>";}
				if($row["rfc_empresa"]==""){$html[$key].="- RFC de la empresa<br>";}
				if($row["pct_participacion"]==""){$html[$key].="- Porcentaje de participación<br>";}
				if($row["tipo_participacion"]==""){$html[$key].="- Tipo de participación<br>";}
				if($row["remuneracion"]==""){$html[$key].="- Remuneración<br>";}
				if($row["remuneracion"]=="S" && $row["monto_mensual"]==""){$html[$key].="- Monto mensual<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["ubicacion"]=="M" && $row["estado"]==""){$html[$key].="- Estado<br>";}
				if($row["ubicacion"]=="E" && $row["pais"]==""){$html[$key].="- País<br>";}
				if($row["sector"]==""){$html[$key].="- Sector<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_participa_empresas SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarformi1($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$nombre_empresa=array();
	$rfc_empresa=array();
	$pct_participacion=array();
	$tipo_participacion=array();
	$tipo_part_descr=array();
	$otra_participacion=array();
	$remuneracion=array();
	$monto_mensual=array();
	$ubicacion=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$sector=array();
	$sector_descr=array();
	$otro_sector=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_participa_empresas WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$nombre_empresa[$key]=$row["nombre_empresa"];
				$rfc_empresa[$key]=$row["rfc_empresa"];
				$pct_participacion[$key]=$row["pct_participacion"];
				$tipo_participacion[$key]=$row["tipo_participacion"];
				$tipo_part_descr[$key]=$row["tipo_part_descr"];
				$otra_participacion[$key]=$row["otra_participacion"];
				$remuneracion[$key]=$row["remuneracion"];
				$monto_mensual[$key]=$row["monto_mensual"];
				$ubicacion[$key]=$row["ubicacion"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_participa_empresas b WHERE a.rfc='$rfc' and a.declaracion='I' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_participa_empresas WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='I' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$nombre_empresa[$key]=$row["nombre_empresa"];
						$rfc_empresa[$key]=$row["rfc_empresa"];
						$pct_participacion[$key]=$row["pct_participacion"];
						$tipo_participacion[$key]=$row["tipo_participacion"];
						$tipo_part_descr[$key]=$row["tipo_part_descr"];
						$otra_participacion[$key]=$row["otra_participacion"];
						$remuneracion[$key]=$row["remuneracion"];
						$monto_mensual[$key]=$row["monto_mensual"];
						$ubicacion[$key]=$row["ubicacion"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$nombre_empresa2[$key]=pg_escape_string($row["nombre_empresa"]);
						$rfc_empresa2[$key]=pg_escape_string($row["rfc_empresa"]);
						$pct_participacion2[$key]=pg_escape_string($row["pct_participacion"]);
						$tipo_participacion2[$key]=pg_escape_string($row["tipo_participacion"]);
						$tipo_part_descr2[$key]=pg_escape_string($row["tipo_part_descr"]);
						$otra_participacion2[$key]=pg_escape_string($row["otra_participacion"]);
						$remuneracion2[$key]=pg_escape_string($row["remuneracion"]);
						$monto_mensual2[$key]=pg_escape_string($row["monto_mensual"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_participa_empresas VALUES ('$rfc',$ejercicio,'I','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$nombre_empresa2[$key]','$rfc_empresa2[$key]',$pct_participacion2[$key],'$tipo_participacion2[$key]','$tipo_part_descr2[$key]','$otra_participacion2[$key]','$remuneracion2[$key]',$monto_mensual2[$key],'$ubicacion2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_participa_empresas WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>16,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"nombre_empresa" => $nombre_empresa,
					"rfc_empresa" => $rfc_empresa,
					"pct_participacion" => $pct_participacion,
					"tipo_participacion" => $tipo_participacion,
					"tipo_part_descr" => $tipo_part_descr,
					"otra_participacion" => $otra_participacion,
					"remuneracion" => $remuneracion,
					"monto_mensual" => $monto_mensual,
					"ubicacion" => $ubicacion,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"sector" => $sector,
					"sector_descr" => $sector_descr,
					"otro_sector" => $otro_sector,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkformi2($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_decisiones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["declarante"]==""){$html[$key].="- Participante<br>";}
				if($row["tipo_institucion"]==""){$html[$key].="- Tipo de institución<br>";}
				if($row["nombre_inst"]==""){$html[$key].="- Nombre de la institución<br>";}
				if($row["rfc_inst"]==""){$html[$key].="- RFC de la institución<br>";}
				if($row["puesto_descr"]==""){$html[$key].="- Puesto<br>";}
				if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
				if($row["remuneracion"]==""){$html[$key].="- Remuneración<br>";}
				if($row["remuneracion"]=="S" && $row["monto_mensual"]==""){$html[$key].="- Monto mensual<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["ubicacion"]=="M" && $row["estado"]==""){$html[$key].="- Estado<br>";}
				if($row["ubicacion"]=="E" && $row["pais"]==""){$html[$key].="- País<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_decisiones SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarformi2($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_institucion=array();
	$otra_institucion=array();
	$nombre_inst=array();
	$rfc_inst=array();
	$puesto_id=array();
	$puesto_descr=array();
	$fecha_inicio=array();
	$remuneracion=array();
	$monto_mensual=array();
	$ubicacion=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_decisiones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_institucion[$key]=$row["tipo_institucion"];
				$otra_institucion[$key]=$row["otra_institucion"];
				$nombre_inst[$key]=$row["nombre_inst"];
				$rfc_inst[$key]=$row["rfc_inst"];
				$puesto_id[$key]=$row["puesto_id"];
				$puesto_descr[$key]=$row["puesto_descr"];
				$fecha_inicio[$key]=$row["fecha_inicio"];
				$remuneracion[$key]=$row["remuneracion"];
				$monto_mensual[$key]=$row["monto_mensual"];
				$ubicacion[$key]=$row["ubicacion"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_decisiones b WHERE a.rfc='$rfc' and a.declaracion='I' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_decisiones WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='I' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_institucion[$key]=$row["tipo_institucion"];
						$otra_institucion[$key]=$row["otra_institucion"];
						$nombre_inst[$key]=$row["nombre_inst"];
						$rfc_inst[$key]=$row["rfc_inst"];
						$puesto_id[$key]=$row["puesto_id"];
						$puesto_descr[$key]=$row["puesto_descr"];
						$fecha_inicio[$key]=$row["fecha_inicio"];
						$fecha_inicio2[$key]=format_date($row["fecha_inicio"]);
						$remuneracion[$key]=$row["remuneracion"];
						$monto_mensual[$key]=$row["monto_mensual"];
						$ubicacion[$key]=$row["ubicacion"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_institucion2[$key]=pg_escape_string($row["tipo_institucion"]);
						$otra_institucion2[$key]=pg_escape_string($row["otra_institucion"]);
						$nombre_inst2[$key]=pg_escape_string($row["nombre_inst"]);
						$rfc_inst2[$key]=pg_escape_string($row["rfc_inst"]);
						$puesto_id2[$key]=pg_escape_string($row["puesto_id"]);
						$puesto_descr2[$key]=pg_escape_string($row["puesto_descr"]);
						$remuneracion2[$key]=pg_escape_string($row["remuneracion"]);
						$monto_mensual2[$key]=pg_escape_string($row["monto_mensual"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_decisiones VALUES ('$rfc',$ejercicio,'I','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_institucion2[$key]','$otra_institucion2[$key]','$nombre_inst2[$key]','$rfc_inst2[$key]',$puesto_id2[$key],'$puesto_descr2[$key]',$fecha_inicio2[$key],'$remuneracion2[$key]',$monto_mensual2[$key],'$ubicacion2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_decisiones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>17,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_institucion" => $tipo_institucion,
					"otra_institucion" => $otra_institucion,
					"nombre_inst" => $nombre_inst,
					"rfc_inst" => $rfc_inst,
					"puesto_id" => $puesto_id,
					"puesto_descr" => $puesto_descr,
					"fecha_inicio" => $fecha_inicio,
					"remuneracion" => $remuneracion,
					"monto_mensual" => $monto_mensual,
					"ubicacion" => $ubicacion,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkformi3($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_apoyos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["beneficiario"]==""){$html[$key].="- Beneficiario<br>";}
				if($row["nombre_prog"]==""){$html[$key].="- Nombre del programa<br>";}
				if($row["instit_otorgante"]==""){$html[$key].="- Institución otorgante<br>";}
				if($row["orden_id"]==""){$html[$key].="- Orden de gobierno<br>";}
				if($row["tipo_apoyo"]==""){$html[$key].="- Tipo de apoyo<br>";}
				if($row["forma_recep"]==""){$html[$key].="- Forma de recepción del apoyo<br>";}
				if($row["monto_mensual"]==""){$html[$key].="- Monto mensual<br>";}
				if($row["forma_recep"]=="E"){
					if($row["apoyo_descr"]==""){$html[$key].="- Apoyo específico<br>";}
				}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_apoyos SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarformi3($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$beneficiario=array();
	$beneficiario_descr=array();
	$otro_beneficiario=array();
	$nombre_prog=array();
	$instit_otorgante=array();
	$orden_id=array();
	$orden_descr=array();
	$tipo_apoyo=array();
	$otro_apoyo=array();
	$forma_recep=array();
	$forma_descr=array();
	$monto_mensual=array();
	$apoyo_descr=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_apoyos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$beneficiario[$key]=$row["beneficiario"];
				$beneficiario_descr[$key]=$row["beneficiario_descr"];
				$otro_beneficiario[$key]=$row["otro_beneficiario"];
				$nombre_prog[$key]=$row["nombre_prog"];
				$instit_otorgante[$key]=$row["instit_otorgante"];
				$orden_id[$key]=$row["orden_id"];
				$orden_descr[$key]=$row["orden_descr"];
				$tipo_apoyo[$key]=$row["tipo_apoyo"];
				$otro_apoyo[$key]=$row["otro_apoyo"];
				$forma_recep[$key]=$row["forma_recep"];
				$forma_descr[$key]=$row["forma_descr"];
				$monto_mensual[$key]=$row["monto_mensual"];
				$apoyo_descr[$key]=$row["apoyo_descr"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_apoyos b WHERE a.rfc='$rfc' and a.declaracion='I' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_apoyos WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='I' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$beneficiario[$key]=$row["beneficiario"];
						$beneficiario_descr[$key]=$row["beneficiario_descr"];
						$otro_beneficiario[$key]=$row["otro_beneficiario"];
						$nombre_prog[$key]=$row["nombre_prog"];
						$instit_otorgante[$key]=$row["instit_otorgante"];
						$orden_id[$key]=$row["orden_id"];
						$orden_descr[$key]=$row["orden_descr"];
						$tipo_apoyo[$key]=$row["tipo_apoyo"];
						$otro_apoyo[$key]=$row["otro_apoyo"];
						$forma_recep[$key]=$row["forma_recep"];
						$forma_descr[$key]=$row["forma_descr"];
						$monto_mensual[$key]=$row["monto_mensual"];
						$apoyo_descr[$key]=$row["apoyo_descr"];
						$observaciones[$key]=$row["observaciones"];

						$beneficiario2[$key]=pg_escape_string($row["beneficiario"]);
						$beneficiario_descr2[$key]=pg_escape_string($row["beneficiario_descr"]);
						$otro_beneficiario2[$key]=pg_escape_string($row["otro_beneficiario"]);
						$nombre_prog2[$key]=pg_escape_string($row["nombre_prog"]);
						$instit_otorgante2[$key]=pg_escape_string($row["instit_otorgante"]);
						$orden_id2[$key]=pg_escape_string($row["orden_id"]);
						$orden_descr2[$key]=pg_escape_string($row["orden_descr"]);
						$tipo_apoyo2[$key]=pg_escape_string($row["tipo_apoyo"]);
						$otro_apoyo2[$key]=pg_escape_string($row["otro_apoyo"]);
						$forma_recep2[$key]=pg_escape_string($row["forma_recep"]);
						$forma_descr2[$key]=pg_escape_string($row["forma_descr"]);
						$monto_mensual2[$key]=pg_escape_string($row["monto_mensual"]);
						$apoyo_descr2[$key]=pg_escape_string($row["apoyo_descr"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_apoyos VALUES ('$rfc',$ejercicio,'I','$dec',$secuencia[$key],'$movimiento[$key]','$beneficiario2[$key]','$beneficiario_descr2[$key]','$otro_beneficiario2[$key]','$nombre_prog2[$key]','$instit_otorgante2[$key]','$orden_id2[$key]','$orden_descr2[$key]','$tipo_apoyo2[$key]','$otro_apoyo2[$key]','$forma_recep2[$key]','$forma_descr2[$key]',$monto_mensual2[$key],'$apoyo_descr2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}
		}
	}
	$sql="SELECT count(*) FROM qsy_apoyos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>18,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"beneficiario" => $beneficiario,
					"beneficiario_descr" => $beneficiario_descr,
					"otro_beneficiario" => $otro_beneficiario,
					"nombre_prog" => $nombre_prog,
					"instit_otorgante" => $instit_otorgante,
					"orden_id" => $orden_id,
					"orden_descr" => $orden_descr,
					"tipo_apoyo" => $tipo_apoyo,
					"otro_apoyo" => $otro_apoyo,
					"forma_recep" => $forma_recep,
					"forma_descr" => $forma_descr,
					"monto_mensual" => $monto_mensual,
					"apoyo_descr" => $apoyo_descr,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkformi4($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_representaciones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["declarante"]==""){$html[$key].="- Participante<br>";}
				if($row["tipo_repres"]==""){$html[$key].="- Tipo de representación<br>";}
				if($row["fecha_inicio"]==""){$html[$key].="- Fecha de inicio<br>";}
				if($row["representa"]==""){$html[$key].="- Representante/Representado<br>";}
				if($row["nombre_repre"]==""){$html[$key].="- Nombre de representante/representado<br>";}
				if($row["rfc_repre"]==""){$html[$key].="- RFC de representante/representado<br>";}
				if($row["remuneracion"]==""){$html[$key].="- Remuneración<br>";}
				if($row["remuneracion"]=="S" && $row["monto_mensual"]==""){$html[$key].="- Monto mensual<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["ubicacion"]=="M" && $row["estado"]==""){$html[$key].="- Estado<br>";}
				if($row["ubicacion"]=="E" && $row["pais"]==""){$html[$key].="- País<br>";}
				if($row["sector"]==""){$html[$key].="- Sector<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_representaciones SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarformi4($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_repres=array();
	$tipo_descr=array();
	$fecha_inicio=array();
	$representa=array();
	$nombre_repre=array();
	$rfc_repre=array();
	$remuneracion=array();
	$monto_mensual=array();
	$ubicacion=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$sector=array();
	$sector_descr=array();
	$otro_sector=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_representaciones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_repres[$key]=$row["tipo_repres"];
				$tipo_descr[$key]=$row["tipo_descr"];
				$fecha_inicio[$key]=$row["fecha_inicio"];
				$representa[$key]=$row["representa"];
				$nombre_repre[$key]=$row["nombre_repre"];
				$rfc_repre[$key]=$row["rfc_repre"];
				$remuneracion[$key]=$row["remuneracion"];
				$monto_mensual[$key]=$row["monto_mensual"];
				$ubicacion[$key]=$row["ubicacion"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_representaciones b WHERE a.rfc='$rfc' and a.declaracion='I' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_representaciones WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='I' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_repres[$key]=$row["tipo_repres"];
						$tipo_descr[$key]=$row["tipo_descr"];
						$fecha_inicio[$key]=$row["fecha_inicio"];
						$fecha_inicio2[$key]=format_date($row["fecha_inicio"]);
						$representa[$key]=$row["representa"];
						$nombre_repre[$key]=$row["nombre_repre"];
						$rfc_repre[$key]=$row["rfc_repre"];
						$remuneracion[$key]=$row["remuneracion"];
						$monto_mensual[$key]=$row["monto_mensual"];
						$ubicacion[$key]=$row["ubicacion"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_repres2[$key]=pg_escape_string($row["tipo_repres"]);
						$tipo_descr2[$key]=pg_escape_string($row["tipo_descr"]);
						$representa2[$key]=pg_escape_string($row["representa"]);
						$nombre_repre2[$key]=pg_escape_string($row["nombre_repre"]);
						$rfc_repre2[$key]=pg_escape_string($row["rfc_repre"]);
						$remuneracion2[$key]=pg_escape_string($row["remuneracion"]);
						$monto_mensual2[$key]=pg_escape_string($row["monto_mensual"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_representaciones VALUES ('$rfc',$ejercicio,'I','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_repres2[$key]','$tipo_descr2[$key]',$fecha_inicio2[$key],'$representa2[$key]','$nombre_repre2[$key]','$rfc_repre2[$key]','$remuneracion2[$key]',$monto_mensual2[$key],'$ubicacion2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}
		}
	}
	$sql="SELECT count(*) FROM qsy_representaciones WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>19,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante" => $declarante,
					"tipo_repres" => $tipo_repres,
					"tipo_descr" => $tipo_descr,
					"fecha_inicio" => $fecha_inicio,
					"representa" => $representa,
					"nombre_repre" => $nombre_repre,
					"rfc_repre" => $rfc_repre,
					"remuneracion" => $remuneracion,
					"monto_mensual" => $monto_mensual,
					"ubicacion" => $ubicacion,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"sector" => $sector,
					"sector_descr" => $sector_descr,
					"otro_sector" => $otro_sector,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkformi5($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_clientes WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["declarante"]==""){$html[$key].="- Participante<br>";}
				if($row["nombre_empresa"]==""){$html[$key].="- Nombre de la empresa<br>";}
				if($row["rfc_empresa"]==""){$html[$key].="- RFC de la empresa<br>";}
				if($row["cliente"]==""){$html[$key].="- Cliente<br>";}
				if($row["nombre_cliente"]==""){$html[$key].="- Nombre del cliente<br>";}
				if($row["rfc_cliente"]==""){$html[$key].="- RFC del cliente<br>";}
				if($row["sector"]==""){$html[$key].="- Sector<br>";}
				if($row["monto_mensual"]==""){$html[$key].="- Monto mensual<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
				if($row["ubicacion"]=="M" && $row["estado"]==""){$html[$key].="- Estado<br>";}
				if($row["ubicacion"]=="E" && $row["pais"]==""){$html[$key].="- País<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_clientes SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarformi5($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$actividad=array();
	$declarante=array();
	$nombre_empresa=array();
	$rfc_empresa=array();
	$cliente=array();
	$cliente_descr=array();
	$nombre_cliente=array();
	$rfc_cliente=array();
	$sector=array();
	$sector_descr=array();
	$otro_sector=array();
	$monto_mensual=array();
	$ubicacion=array();
	$estado=array();
	$estado_descr=array();
	$pais=array();
	$pais_descr=array();
	$observaciones=array();


	if($row){
		$sql="SELECT * FROM qsy_clientes WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$actividad[$key]=$row["actividad"];
				$declarante[$key]=$row["declarante"];
				$nombre_empresa[$key]=$row["nombre_empresa"];
				$rfc_empresa[$key]=$row["rfc_empresa"];
				$cliente[$key]=$row["cliente"];
				$cliente_descr[$key]=$row["cliente_descr"];
				$nombre_cliente[$key]=$row["nombre_cliente"];
				$rfc_cliente[$key]=$row["rfc_cliente"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$monto_mensual[$key]=$row["monto_mensual"];
				$ubicacion[$key]=$row["ubicacion"];
				$estado[$key]=$row["estado"];
				$estado_descr[$key]=$row["estado_descr"];
				$pais[$key]=$row["pais"];
				$pais_descr[$key]=$row["pais_descr"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_clientes b WHERE a.rfc='$rfc' and a.declaracion='I' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_clientes WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='I' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$actividad[$key]=$row["actividad"];
						$declarante[$key]=$row["declarante"];
						$nombre_empresa[$key]=$row["nombre_empresa"];
						$rfc_empresa[$key]=$row["rfc_empresa"];
						$cliente[$key]=$row["cliente"];
						$cliente_descr[$key]=$row["cliente_descr"];
						$nombre_cliente[$key]=$row["nombre_cliente"];
						$rfc_cliente[$key]=$row["rfc_cliente"];
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$monto_mensual[$key]=$row["monto_mensual"];
						$ubicacion[$key]=$row["ubicacion"];
						$estado[$key]=$row["estado"];
						$estado_descr[$key]=$row["estado_descr"];
						$pais[$key]=$row["pais"];
						$pais_descr[$key]=$row["pais_descr"];
						$observaciones[$key]=$row["observaciones"];

						$actividad2[$key]=pg_escape_string($row["actividad"]);
						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$nombre_empresa2[$key]=pg_escape_string($row["nombre_empresa"]);
						$rfc_empresa2[$key]=pg_escape_string($row["rfc_empresa"]);
						$cliente2[$key]=pg_escape_string($row["cliente"]);
						$cliente_descr2[$key]=pg_escape_string($row["cliente_descr"]);
						$nombre_cliente2[$key]=pg_escape_string($row["nombre_cliente"]);
						$rfc_cliente2[$key]=pg_escape_string($row["rfc_cliente"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$monto_mensual2[$key]=pg_escape_string($row["monto_mensual"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$estado2[$key]=pg_escape_string($row["estado"]);
						$estado_descr2[$key]=pg_escape_string($row["estado_descr"]);
						$pais2[$key]=pg_escape_string($row["pais"]);
						$pais_descr2[$key]=pg_escape_string($row["pais_descr"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_clientes VALUES ('$rfc',$ejercicio,'I','$dec',$secuencia[$key],'$movimiento[$key]','$actividad2[$key]','$declarante2[$key]','$nombre_empresa2[$key]','$rfc_empresa2[$key]','$cliente2[$key]','$cliente_descr2[$key]','$nombre_cliente2[$key]','$rfc_cliente2[$key]','$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]',$monto_mensual2[$key],'$ubicacion2[$key]','$estado2[$key]','$estado_descr2[$key]','$pais2[$key]','$pais_descr2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}
		}
	}
	$sql="SELECT count(*) FROM qsy_clientes WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>20,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"actividad" => $actividad,
					"declarante" => $declarante,
					"nombre_empresa" => $nombre_empresa,
					"rfc_empresa" => $rfc_empresa,
					"cliente" => $cliente,
					"cliente_descr" => $cliente_descr,
					"nombre_cliente" => $nombre_cliente,
					"rfc_cliente" => $rfc_cliente,
					"sector" => $sector,
					"sector_descr" => $sector_descr,
					"otro_sector" => $otro_sector,
					"monto_mensual" => $monto_mensual,
					"ubicacion" => $ubicacion,
					"estado" => $estado,
					"estado_descr" => $estado_descr,
					"pais" => $pais,
					"pais_descr" => $pais_descr,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkformi6($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_beneficios_privados WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["tipo_beneficio"]==""){$html[$key].="- Tipo de beneficio<br>";}
				if($row["beneficiario"]==""){$html[$key].="- Beneficiario<br>";}
				if($row["otorgante"]==""){$html[$key].="- Otorgante<br>";}
				if($row["nombre_otorga"]==""){$html[$key].="- Nombre del otorgante<br>";}
				if($row["rfc_otorga"]==""){$html[$key].="- RFC del otorgante<br>";}
				if($row["forma_recep"]==""){$html[$key].="- Forma de recepción del apoyo<br>";}
				if($row["forma_recep"]=="E"){
					if($row["beneficio_descr"]==""){$html[$key].="- Beneficio específico<br>";}
				}
				if($row["monto_mensual"]==""){$html[$key].="- Monto mensual<br>";}
				if($row["tipo_moneda"]==""){$html[$key].="- Tipo de moneda<br>";}
				if($row["sector"]==""){$html[$key].="- Sector<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_beneficios_privados SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarformi6($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$tipo_beneficio=array();
	$otro_beneficio=array();
	$beneficiario=array();
	$beneficiario_descr=array();
	$otro_beneficiario=array();
	$otorgante=array();
	$otorgante_descr=array();
	$nombre_otorga=array();
	$rfc_otorga=array();
	$forma_recep=array();
	$forma_descr=array();
	$beneficio_descr=array();
	$monto_mensual=array();
	$tipo_moneda=array();
	$sector=array();
	$sector_descr=array();
	$otro_sector=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_beneficios_privados WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$tipo_beneficio[$key]=$row["tipo_beneficio"];
				$otro_beneficio[$key]=$row["otro_beneficio"];
				$beneficiario[$key]=$row["beneficiario"];
				$beneficiario_descr[$key]=$row["beneficiario_descr"];
				$otro_beneficiario[$key]=$row["otro_beneficiario"];
				$otorgante[$key]=$row["otorgante"];
				$otorgante_descr[$key]=$row["otorgante_descr"];
				$nombre_otorga[$key]=$row["nombre_otorga"];
				$rfc_otorga[$key]=$row["rfc_otorga"];
				$forma_recep[$key]=$row["forma_recep"];
				$forma_descr[$key]=$row["forma_descr"];
				$beneficio_descr[$key]=$row["beneficio_descr"];
				$monto_mensual[$key]=$row["monto_mensual"];
				$tipo_moneda[$key]=$row["tipo_moneda"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_beneficios_privados b WHERE a.rfc='$rfc' and a.declaracion='I' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_beneficios_privados WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='I' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$tipo_beneficio[$key]=$row["tipo_beneficio"];
						$otro_beneficio[$key]=$row["otro_beneficio"];
						$beneficiario[$key]=$row["beneficiario"];
						$beneficiario_descr[$key]=$row["beneficiario_descr"];
						$otro_beneficiario[$key]=$row["otro_beneficiario"];
						$otorgante[$key]=$row["otorgante"];
						$otorgante_descr[$key]=$row["otorgante_descr"];
						$nombre_otorga[$key]=$row["nombre_otorga"];
						$rfc_otorga[$key]=$row["rfc_otorga"];
						$forma_recep[$key]=$row["forma_recep"];
						$forma_descr[$key]=$row["forma_descr"];
						$beneficio_descr[$key]=$row["beneficio_descr"];
						$monto_mensual[$key]=$row["monto_mensual"];
						$tipo_moneda[$key]=$row["tipo_moneda"];
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$observaciones[$key]=$row["observaciones"];

						$tipo_beneficio2[$key]=pg_escape_string($row["tipo_beneficio"]);
						$otro_beneficio2[$key]=pg_escape_string($row["otro_beneficio"]);
						$beneficiario2[$key]=pg_escape_string($row["beneficiario"]);
						$beneficiario_descr2[$key]=pg_escape_string($row["beneficiario_descr"]);
						$otro_beneficiario2[$key]=pg_escape_string($row["otro_beneficiario"]);
						$otorgante2[$key]=pg_escape_string($row["otorgante"]);
						$otorgante_descr2[$key]=pg_escape_string($row["otorgante_descr"]);
						$nombre_otorga2[$key]=pg_escape_string($row["nombre_otorga"]);
						$rfc_otorga2[$key]=pg_escape_string($row["rfc_otorga"]);
						$forma_recep2[$key]=pg_escape_string($row["forma_recep"]);
						$forma_descr2[$key]=pg_escape_string($row["forma_descr"]);
						$beneficio_descr2[$key]=pg_escape_string($row["beneficio_descr"]);
						$monto_mensual2[$key]=pg_escape_string($row["monto_mensual"]);
						$tipo_moneda2[$key]=pg_escape_string($row["tipo_moneda"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);

						$sql="INSERT INTO qsy_beneficios_privados VALUES ('$rfc',$ejercicio,'I','$dec',$secuencia[$key],'$movimiento[$key]','$tipo_beneficio2[$key]','$otro_beneficio2[$key]','$beneficiario2[$key]','$beneficiario_descr2[$key]','$otro_beneficiario2[$key]','$otorgante2[$key]','$otorgante_descr2[$key]','$nombre_otorga2[$key]','$rfc_otorga2[$key]','$forma_recep2[$key]','$forma_descr2[$key]','$beneficio_descr2[$key]',$monto_mensual2[$key],'$tipo_moneda2[$key]','$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}
		}
	}
	$sql="SELECT count(*) FROM qsy_beneficios_privados WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>21,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"tipo_beneficio" => $tipo_beneficio,
					"otro_beneficio" => $otro_beneficio,
					"beneficiario" => $beneficiario,
					"beneficiario_descr" => $beneficiario_descr,
					"otro_beneficiario" => $otro_beneficiario,
					"otorgante" => $otorgante,
					"otorgante_descr" => $otorgante_descr,
					"nombre_otorga" => $nombre_otorga,
					"rfc_otorga" => $rfc_otorga,
					"forma_recep" => $forma_recep,
					"forma_descr" => $forma_descr,
					"beneficio_descr" => $beneficio_descr,
					"monto_mensual" => $monto_mensual,
					"tipo_moneda" => $tipo_moneda,
					"sector" => $sector,
					"sector_descr" => $sector_descr,
					"otro_sector" => $otro_sector,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}
function checkformi7($dec,$ejercicio,$rfc,$conn){
	$html=array();
	$sql="SELECT * FROM qsy_fideicomisos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' ORDER BY secuencia DESC";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
    
    if($val){
		foreach ($val as $key => $row) {
	    	$html[$key]="Campos pendientes:<br>";

			if($row["movimiento"]!="N"){
				if($row["declarante"]==""){$html[$key].="- Participación<br>";}
				if($row["tipo_fideicomiso"]==""){$html[$key].="- Tipo de fideicomiso<br>";}
				if($row["rfc_fideicomiso"]==""){$html[$key].="- RFC del fideicomiso<br>";}
				if($row["como_participa"]==""){$html[$key].="- Tipo de participación<br>";}
				if($row["como_participa"]=="A"){
					if($row["fideicomitente"]==""){$html[$key].="- Fideicomitente<br>";}
					if($row["nom_fideicomitente"]==""){$html[$key].="- Nombre del fideicomitente<br>";}
					if($row["rfc_fideicomitente"]==""){$html[$key].="- RFC del fideicomitente<br>";}
				}
				if($row["como_participa"]=="B"){
					if($row["nom_fiduciario"]==""){$html[$key].="- Nombre del fiduciario<br>";}
					if($row["rfc_fiduciario"]==""){$html[$key].="- RFC del fiduciario<br>";}
				}
				if($row["como_participa"]=="C"){
					if($row["fideicomisario"]==""){$html[$key].="- Fideicomisario<br>";}
					if($row["nom_fideicomisario"]==""){$html[$key].="- Nombre del fideicomisario<br>";}
					if($row["rfc_fideicomisario"]==""){$html[$key].="- RFC del fideicomisario<br>";}
				}
				if($row["sector"]==""){$html[$key].="- Sector<br>";}
				if($row["ubicacion"]==""){$html[$key].="- Ubicación<br>";}
			}
			if($html[$key]=="Campos pendientes:<br>")$html[$key]="";
			if($html[$key]==""){
				$sql="UPDATE qsy_fideicomisos SET estatus='C' WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' AND secuencia=".$row['secuencia'];
				$result=pg_query($conn,$sql);
			}
		}
	}
	return $html;
}
function cargarformi7($dec,$ejercicio,$rfc,$conn){
	$sql="SELECT estatus_decl from qsy_declaraciones where rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql) or die("Error");
	$row=pg_fetch_assoc($result);
	$arreglo=array();
	$secuencia=array();
	$movimiento=array();
	$declarante=array();
	$tipo_fideicomiso=array();
	$tipo_descr=array();
	$como_participa=array();
	$participa_descr=array();
	$rfc_fideicomiso=array();
	$fideicomitente=array();
	$fideicomitente_descr=array();
	$nom_fideicomitente=array();
	$rfc_fideicomitente=array();
	$nom_fiduciario=array();
	$rfc_fiduciario=array();
	$fideicomisario=array();
	$fideicomisario_descr=array();
	$nom_fideicomisario=array();
	$rfc_fideicomisario=array();
	$sector=array();
	$sector_descr=array();
	$otro_sector=array();
	$ubicacion=array();
	$observaciones=array();

	if($row){
		$sql="SELECT * FROM qsy_fideicomisos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I' order by secuencia desc";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_all($result);
	    if($val){
			foreach ($val as $key => $row) {
				$secuencia[$key]=$row["secuencia"];
				$movimiento[$key]=$row["movimiento"];
				$declarante[$key]=$row["declarante"];
				$tipo_fideicomiso[$key]=$row["tipo_fideicomiso"];
				$tipo_descr[$key]=$row["tipo_descr"];
				$como_participa[$key]=$row["como_participa"];
				$participa_descr[$key]=$row["participa_descr"];
				$rfc_fideicomiso[$key]=$row["rfc_fideicomiso"];
				$fideicomitente[$key]=$row["fideicomitente"];
				$fideicomitente_descr[$key]=$row["fideicomitente_descr"];
				$nom_fideicomitente[$key]=$row["nom_fideicomitente"];
				$rfc_fideicomitente[$key]=$row["rfc_fideicomitente"];
				$nom_fiduciario[$key]=$row["nom_fiduciario"];
				$rfc_fiduciario[$key]=$row["rfc_fiduciario"];
				$fideicomisario[$key]=$row["fideicomisario"];
				$fideicomisario_descr[$key]=$row["fideicomisario_descr"];
				$nom_fideicomisario[$key]=$row["nom_fideicomisario"];
				$rfc_fideicomisario[$key]=$row["rfc_fideicomisario"];
				$sector[$key]=$row["sector"];
				$sector_descr[$key]=$row["sector_descr"];
				$otro_sector[$key]=$row["otro_sector"];
				$ubicacion[$key]=$row["ubicacion"];
				$observaciones[$key]=$row["observaciones"];
			}
		}
		else{
			$sql="SELECT a.ejercicio,a.tipo_decl FROM qsy_declaraciones a,qsy_fideicomisos b WHERE a.rfc='$rfc' and a.declaracion='I' and a.tipo_decl!='C' and a.rfc=b.rfc and a.declaracion=b.declaracion and a.tipo_decl=b.tipo_decl and a.ejercicio=b.ejercicio order by a.fecha_presenta desc,a.tipo_decl desc,a.ejercicio desc";
			$result=pg_query($conn,$sql);
			$row=pg_fetch_assoc($result);
			//print_r($row);die;
			if($row){
				$ejercicio_ant=$row["ejercicio"];
				$tipo_decl_ant=$row["tipo_decl"];
				$sql="SELECT * FROM qsy_fideicomisos WHERE rfc='$rfc' AND tipo_decl='$tipo_decl_ant' AND ejercicio=$ejercicio_ant AND declaracion='I' AND (movimiento!='B' AND movimiento!='N') order by secuencia desc";
				$result=pg_query($conn,$sql);
				$val=pg_fetch_all($result);
			    if($val){
					$nueva_sec=sizeof($val);
					foreach ($val as $key => $row) {
						$secuencia[$key]=$nueva_sec;
						$nueva_sec--;
						$movimiento[$key]=$row["movimiento"];
						$declarante[$key]=$row["declarante"];
						$tipo_fideicomiso[$key]=$row["tipo_fideicomiso"];
						$tipo_descr[$key]=$row["tipo_descr"];
						$como_participa[$key]=$row["como_participa"];
						$participa_descr[$key]=$row["participa_descr"];
						$rfc_fideicomiso[$key]=$row["rfc_fideicomiso"];
						$fideicomitente[$key]=$row["fideicomitente"];
						$fideicomitente_descr[$key]=$row["fideicomitente_descr"];
						$nom_fideicomitente[$key]=$row["nom_fideicomitente"];
						$rfc_fideicomitente[$key]=$row["rfc_fideicomitente"];
						$nom_fiduciario[$key]=$row["nom_fiduciario"];
						$rfc_fiduciario[$key]=$row["rfc_fiduciario"];
						$fideicomisario[$key]=$row["fideicomisario"];
						$fideicomisario_descr[$key]=$row["fideicomisario_descr"];
						$nom_fideicomisario[$key]=$row["nom_fideicomisario"];
						$rfc_fideicomisario[$key]=$row["rfc_fideicomisario"];
						$sector[$key]=$row["sector"];
						$sector_descr[$key]=$row["sector_descr"];
						$otro_sector[$key]=$row["otro_sector"];
						$ubicacion[$key]=$row["ubicacion"];
						$observaciones[$key]=$row["observaciones"];

						$declarante2[$key]=pg_escape_string($row["declarante"]);
						$tipo_fideicomiso2[$key]=pg_escape_string($row["tipo_fideicomiso"]);
						$tipo_descr2[$key]=pg_escape_string($row["tipo_descr"]);
						$como_participa2[$key]=pg_escape_string($row["como_participa"]);
						$participa_descr2[$key]=pg_escape_string($row["participa_descr"]);
						$rfc_fideicomiso2[$key]=pg_escape_string($row["rfc_fideicomiso"]);
						$fideicomitente2[$key]=pg_escape_string($row["fideicomitente"]);
						$fideicomitente_descr2[$key]=pg_escape_string($row["fideicomitente_descr"]);
						$nom_fideicomitente2[$key]=pg_escape_string($row["nom_fideicomitente"]);
						$rfc_fideicomitente2[$key]=pg_escape_string($row["rfc_fideicomitente"]);
						$nom_fiduciario2[$key]=pg_escape_string($row["nom_fiduciario"]);
						$rfc_fiduciario2[$key]=pg_escape_string($row["rfc_fiduciario"]);
						$fideicomisario2[$key]=pg_escape_string($row["fideicomisario"]);
						$fideicomisario_descr2[$key]=pg_escape_string($row["fideicomisario_descr"]);
						$nom_fideicomisario2[$key]=pg_escape_string($row["nom_fideicomisario"]);
						$rfc_fideicomisario2[$key]=pg_escape_string($row["rfc_fideicomisario"]);
						$sector2[$key]=pg_escape_string($row["sector"]);
						$sector_descr2[$key]=pg_escape_string($row["sector_descr"]);
						$otro_sector2[$key]=pg_escape_string($row["otro_sector"]);
						$ubicacion2[$key]=pg_escape_string($row["ubicacion"]);
						$observaciones2[$key]=pg_escape_string($row["observaciones"]);
						$sql="INSERT INTO qsy_fideicomisos VALUES ('$rfc',$ejercicio,'I','$dec',$secuencia[$key],'$movimiento[$key]','$declarante2[$key]','$tipo_fideicomiso2[$key]','$tipo_descr2[$key]','$como_participa2[$key]','$participa_descr2[$key]','$rfc_fideicomiso2[$key]','$fideicomitente2[$key]','$fideicomitente_descr2[$key]','$nom_fideicomitente2[$key]','$rfc_fideicomitente2[$key]','$nom_fiduciario2[$key]','$rfc_fiduciario2[$key]','$fideicomisario2[$key]','$fideicomisario_descr2[$key]','$nom_fideicomisario2[$key]','$rfc_fideicomisario2[$key]','$sector2[$key]','$sector_descr2[$key]','$otro_sector2[$key]','$ubicacion2[$key]','$observaciones2[$key]','A')";
						$result=pg_query($conn,$sql);
					}
				}
			}

		}
	}
	$sql="SELECT count(*) FROM qsy_fideicomisos WHERE rfc='$rfc' AND tipo_decl='$dec' AND ejercicio=$ejercicio AND declaracion='I'";
	$result=pg_query($conn,$sql);
	$total2=pg_fetch_row($result);
	//print_r($total);
	$total=sizeof($secuencia);
	$arreglo=array(
					"id"=>$rfc,
					"total"=>$total,
					"total2"=>$total2[0],
					"form"=>22,
					"secuencia"=> $secuencia,
					"movimiento"=> $movimiento,
					"declarante"=> $declarante,
					"tipo_fideicomiso" => $tipo_fideicomiso,
					"tipo_descr" => $tipo_descr,
					"como_participa" => $como_participa,
					"participa_descr" => $participa_descr,
					"rfc_fideicomiso" => $rfc_fideicomiso,
					"fideicomitente" => $fideicomitente,
					"fideicomitente_descr" => $fideicomitente_descr,
					"nom_fideicomitente" => $nom_fideicomitente,
					"rfc_fideicomitente" => $rfc_fideicomitente,
					"nom_fiduciario" => $nom_fiduciario,
					"rfc_fiduciario" => $rfc_fiduciario,
					"fideicomisario" => $fideicomisario,
					"fideicomisario_descr" => $fideicomisario_descr,
					"nom_fideicomisario" => $nom_fideicomisario,
					"rfc_fideicomisario" => $rfc_fideicomisario,
					"sector" => $sector,
					"sector_descr" => $sector_descr,
					"otro_sector" => $otro_sector,
					"ubicacion" => $ubicacion,
					"observaciones"=>$observaciones
					);
	//print_r($arreglo);die;
	return $arreglo;
}

?>