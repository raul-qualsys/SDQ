<?php
    include("../include/conexion.php");
    include("../include/funciones.php");

  require_once('../vendor/autoload.php');
  include("../include/constantes.php");
  require '../views/reportes/codigo_qr/phpqrcode/qrlib.php';
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require('../PHPMailer/src/Exception.php');
  require('../PHPMailer/src/PHPMailer.php');
  require('../PHPMailer/src/SMTP.php');
  

function actual_date()  
{  
    $week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");  
    $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");  
    $year_now = date ("Y");  
    $month_now = date ("n");  
    $day_now = date ("j");  
    $week_day_now = date ("w");  
    $date = $day_now . " de " . $months[$month_now] . " de " . $year_now;   
    return $date;    
} 

if(isset($_POST["conformidad"])){
	$rfc=htmlspecialchars($_POST["rfc"]);
	$ejercicio=htmlspecialchars($_POST["ejercicio"]);
	$dec=htmlspecialchars($_POST["tipo-declaracion"]);
	$tipo_decl=htmlspecialchars($_POST["declaracion"]);
	$conformidad=htmlspecialchars($_POST["conformidad"]);
	$sql="UPDATE qsy_declaraciones SET conformidad='$conformidad' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	//print_r($_POST);
}
if(isset($_POST["form1"])){
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$nombre=escape($_POST["nombre"]);
	$primerAp=escape($_POST["primerApellido"]);
	$segundoAp=escape($_POST["segundoApellido"]);
	$curp=escape($_POST["curp"]);
	$correo1=pg_escape_string(htmlspecialchars($_POST["email_trabajo"]));
	$correo2=pg_escape_string(htmlspecialchars($_POST["email"]));
	$tel1=escape($_POST["tel_casa"]);
	$tel2=escape($_POST["tel_celular"]);
	$ecivil=escape($_POST["edo_civil"]);
	$regmat=escape($_POST["regimen"]);
	$otro=escape($_POST["otro"]);
	$idpais=escape($_POST["pais"]);
	$pais=escape(get_descr($conn,$idpais,"descr","qsy_paises","pais"));
	$idnacionalidad=escape($_POST["nacionalidad"]);
	$nacionalidad=escape(get_descr($conn,$idnacionalidad,"descr","qsy_nacionalidades","nacionalidad"));
	if(isset($_POST["servidor"])){
		$servidor=escape($_POST["servidor"]);
	}
	else $servidor="";
	$observaciones=escape($_POST["observaciones"]);

	if($nombre!="" && $primerAp!="" && $curp!="" && $correo1!="" && ($tel1!="" || $tel2!="") && $ecivil!="" && $idpais!="" && $idnacionalidad!="" && ($tipo_decl!="M" || $servidor!="")) $estatus="C";
	else $estatus="A";
	
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_datos_generales WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_row($result);
	if($val){
		$sql="UPDATE qsy_datos_generales SET nombre='$nombre',primer_ap='$primerAp',segundo_ap='$segundoAp',curp='$curp',email_institucional='$correo1',email_personal='$correo2',tel_casa='$tel1',celular_personal='$tel2',estado_civil='$ecivil',regimen_matri='$regmat',otro_regimen='$otro',pais_nacimiento='$idpais',pais_desc='$pais',nacionalidad='$idnacionalidad',nacionalidad_desc='$nacionalidad',servidor_publico='$servidor',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_datos_generales VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl','$nombre','$primerAp','$segundoAp','$curp','$correo1','$correo2','$tel1','$tel2','$ecivil','$regmat','$otro','$idpais','$pais','$idnacionalidad','$nacionalidad','$servidor','$observaciones','$estatus')";
		$result=pg_query($conn,$sql);
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["form2"])){
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$cp=escape($_POST["cp"]);
	$calle=escape($_POST["calle"]);
	$num_exterior=escape($_POST["noexterior"]);
	$num_interior=escape($_POST["nointerior"]);
	if($_POST["estado"]!=""){
		$estado=escape($_POST["estado"]);
		$estado_desc=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	}
	else{
		$estado="";
		$estado_desc=escape($_POST["estado2"]);
	}
	if(isset($_POST["municipio"])){
		$municipio=escape($_POST["municipio"]);
		$municipio_desc=escape(get_municipio($conn,$municipio,$estado));
	}
	else{
		$municipio="";
		$municipio_desc="";
	}
	if(isset($_POST["colonia2"])){
		$colonia=escape($_POST["colonia2"]);
		$colonia_desc=escape(get_colonia($conn,$colonia,$cp));
	}
	else{
		$colonia="";
		$colonia_desc=escape($_POST["colonia"]);
	}
	$pais=escape($_POST["pais"]);
	$pais_desc=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$observaciones=escape($_POST["observaciones"]);
	if($ubicacion=="M" && $calle!="" && $num_exterior!="" && $colonia_desc!="" && $municipio_desc!="" && $estado_desc!="" && $pais!="" && $cp!="") $estatus="C";
	else if($ubicacion=="E" && $pais!="")$estatus="C";
	else $estatus="A";

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_direcciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_row($result);
	if($val){
		$sql="UPDATE qsy_direcciones SET ubicacion='$ubicacion',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',colonia_desc='$colonia_desc',municipio='$municipio',municipio_desc='$municipio_desc',estado='$estado',estado_desc='$estado_desc',pais='$pais',pais_desc='$pais_desc',codigopostal='$cp',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_direcciones VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$colonia_desc','$municipio','$municipio_desc','$estado','$estado_desc','$pais','$pais_desc','$cp','$observaciones','$estatus')";
		$result=pg_query($conn,$sql);
	}
	print_r("Los datos se han guardado.");

}

if(isset($_POST["form3"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$nivel_escolar=escape($_POST["nivel_escolar"]);
	$institucion=escape($_POST["institucion"]);
	$carrera=escape($_POST["carrera"]);
	$fecha_doc=format_date($_POST["fecha_doc"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$estatus_estudio=escape($_POST["estatus_estudio"]);
	$doc_obtenido=escape($_POST["doc_obtenido"]);
	$observaciones=escape($_POST["observaciones"]);
	if($nivel_escolar!="" && $institucion!="" && $estatus_estudio!="" && $doc_obtenido!="" && $fecha_doc!="" && $ubicacion!="") $estatus="C";
	else $estatus="A";
	//1 para upload_max_filsize de php.ini
	//2 para MAX_FILE_SIZE de form html
	//3 para subida parcial
	if(isset($_FILES["archivo"]) && $_FILES["archivo"]["error"]!=4 && $_FILES["archivo"]["size"]<5240000){ //Comprueba si la variable "archivo" se ha definido
		$nombre="../files/certificados/".$rfc."_".$nivel_escolar."_".$secuencia.".pdf";
		move_uploaded_file($_FILES["archivo"]["tmp_name"],$nombre);
	}

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_escolaridades WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_escolaridades VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$nivel_escolar','$institucion','$carrera','$estatus_estudio','$doc_obtenido',$fecha_doc,'$ubicacion','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_escolaridades SET movimiento='$movimiento',nivel_escolar='$nivel_escolar',institucion='$institucion',carrera='$carrera',estatus_estudio='$estatus_estudio',doc_obtenido='$doc_obtenido',fecha_doc=$fecha_doc,ubicacion='$ubicacion',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_escolaridades VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$nivel_escolar','$institucion','$carrera','$estatus_estudio','$doc_obtenido',$fecha_doc,'$ubicacion','$observaciones','$estatus')";
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");

}

if(isset($_POST["form4"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$orden_id=escape($_POST["nivel_gobierno"]);
	$orden_descr=escape(get_descr_lista($conn,$orden_id,"descr","qsy_listas_valores","Orden_ID"));
	$ambito_id=escape($_POST["ambito_publico"]);
	$ambito_descr=escape(get_descr_lista($conn,$ambito_id,"descr","qsy_listas_valores","Ambito_ID"));
	if($_POST["ente"]!=""){
		if($_POST["area"]!="" || $_POST["puesto"]!=""){
			$dependencia=escape($_POST["ente"]);
			$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
			$area_adscripcion=escape($_POST["area"]);
			$area_descr=escape(get_descr($conn,$area_adscripcion,"descr","qsy_areas_adscripcion","area_adscripcion"));
			$id_puesto=escape($_POST["puesto"]);
			$puesto_descr=escape(get_descr_puesto($conn,$id_puesto));
		}
		else{
		$dependencia=escape($_POST["ente"]);
		$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$id_puesto=0;
		$puesto_descr=escape($_POST["puesto2"]);
		}
		if($_POST["ente"]=="999"){
			$dependencia_descr=escape($_POST["otro_ente"]);
		}
	}
	else{
		$dependencia="";
		$dependencia_descr="";
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$id_puesto=0;
		$puesto_descr=escape($_POST["puesto2"]);
	}

	$nivel_empleo="";
	$nivel_descr=escape($_POST["nivel_empleo"]);
	if(isset($_POST["otro_empleo"])){
		$otro_empleo=escape($_POST["otro_empleo"]);
	}
	else{
		$otro_empleo="";
	}

	$honorarios=escape($_POST["honorarios"]);
	$funcion_principal=escape($_POST["funcion_principal"]);
	$fecha_inicio=format_date($_POST["fecha_empleo"]);
	$tel_oficina=escape($_POST["telefono"]);
	$extension=escape($_POST["extension"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$cp=escape($_POST["cp"]);
	$calle=escape($_POST["calle"]);
	$num_exterior=escape($_POST["noexterior"]);
	$num_interior=escape($_POST["nointerior"]);
	if($_POST["estado2"]!=""){
		$estado="";
		$estado_descr=escape($_POST["estado2"]);
	}
	else{
		$estado=escape($_POST["estado"]);
		$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	}
	if(isset($_POST["municipio"])){
		$municipio=escape($_POST["municipio"]);
		$municipio_descr=escape(get_municipio($conn,$municipio,$estado));
	}
	else{
		$municipio="";
		$municipio_descr="";		
	}
	if(isset($_POST["colonia2"])){
		$colonia=escape($_POST["colonia2"]);
		$colonia_descr=escape(get_colonia($conn,$colonia,$cp));
	}
	else{
		$colonia="";
		$colonia_descr=escape($_POST["colonia"]);
	}
	$pais=escape($_POST["pais"]);
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));

	$observaciones=escape($_POST["observaciones"]);

	if($orden_id!="" && $ambito_id!="" && $dependencia_descr!="" && $area_descr!="" && $puesto_descr!="" && $nivel_descr!="" && $honorarios!="" && $funcion_principal!="" && $fecha_inicio!="" && $ubicacion!="" && $pais!=""){
		if($ubicacion=="M" && $calle!="" && $num_exterior!="" && $colonia_descr!="" && $municipio_descr!="" && $estado_descr!="" && $cp!="") $estatus="C";
		else if($ubicacion=="E")$estatus="C";
		else $estatus="A";
	} 
	else $estatus="A";

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_comision_actual WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_comision_actual VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$otro_empleo','$movimiento','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr','$nivel_empleo','$nivel_descr','$id_puesto','$puesto_descr','$honorarios','$funcion_principal',$fecha_inicio,'$tel_oficina','$extension','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$cp','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_comision_actual SET movimiento='$movimiento',otro_empleo='$otro_empleo',orden_id='$orden_id',orden_descr='$orden_descr',ambito_id='$ambito_id',ambito_descr='$ambito_descr',dependencia='$dependencia',dependencia_descr='$dependencia_descr',area_adscripcion='$area_adscripcion',area_descr='$area_descr',nivel_empleo='$nivel_empleo',nivel_descr='$nivel_descr',id_puesto='$id_puesto',puesto_descr='$puesto_descr',honorarios='$honorarios',funcion_principal='$funcion_principal',fecha_inicio=$fecha_inicio,tel_oficina='$tel_oficina',extension='$extension',ubicacion='$ubicacion',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',colonia_descr='$colonia_descr',municipio='$municipio',municipio_descr='$municipio_descr',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',codigopostal='$cp',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";

		}
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_comision_actual VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$otro_empleo','$movimiento','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr','$nivel_empleo','$nivel_descr','$id_puesto','$puesto_descr','$honorarios','$funcion_principal',$fecha_inicio,'$tel_oficina','$extension','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$cp','$observaciones','$estatus')";

		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");

}
if(isset($_POST["form5"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$actividad_laboral=escape($_POST["sector"]);
	$otra_actividad=escape($_POST["otro_ambito"]);
	$orden_id=escape($_POST["nivel"]);
	$orden_descr=escape(get_descr_lista($conn,$orden_id,"descr","qsy_listas_valores","Orden_ID"));
	$ambito_id=escape($_POST["ambito_publico"]);
	$ambito_descr=escape(get_descr_lista($conn,$ambito_id,"descr","qsy_listas_valores","Ambito_ID"));
	if($_POST["ente"]!=""){
		if($_POST["area"]!="" || $_POST["puesto"]!=""){
			$dependencia=escape($_POST["ente"]);
			$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
			$area_adscripcion=escape($_POST["area"]);
			$area_descr=escape(get_descr($conn,$area_adscripcion,"descr","qsy_areas_adscripcion","area_adscripcion"));
			$puesto_id=escape($_POST["puesto"]);
			$puesto_descr=escape(get_descr_puesto($conn,$puesto_id));
		}
		else{
		$dependencia=escape($_POST["ente"]);
		$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$puesto_id=0;
		$puesto_descr=escape($_POST["puesto2"]);
		}
		if($_POST["ente"]=="999"){
			$dependencia_descr=escape($_POST["otro_ente"]);
		}
	}
	else{
		$dependencia="";
		$dependencia_descr=escape($_POST["ente2"]);
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$puesto_id=0;
		$puesto_descr=escape($_POST["puesto2"]);
	}

	$nombre_empresa=escape($_POST["ente2"]);
	$rfc_empresa=escape($_POST["rfc_empresa"]);
	$funcion_principal=escape($_POST["funcion_principal"]);
	$fecha_inicio=format_date($_POST["fecha_ingreso"]);
	$fecha_fin=format_date($_POST["fecha_egreso"]);
	$sector=escape($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$observaciones=escape($_POST["observaciones"]);
	if($actividad_laboral=="N"){
		$movimiento="N";$estatus="C";$otra_actividad="";$orden_id="";$orden_descr="";$ambito_id="";$ambito_descr="";$dependencia="";$dependencia_descr="";$area_adscripcion="";$area_descr="";$puesto_id=0;$puesto_descr="";$nombre_empresa="";$rfc_empresa="";$funcion_principal="";$fecha_inicio="null";$fecha_fin="null";$sector="";$sector_descr="";$otro_sector="";$ubicacion="";
	}
	else if($actividad_laboral=="U" && $orden_id!="" && $ambito_id!="" && $dependencia_descr!="" && $area_descr!="" && $puesto_descr!="" && $funcion_principal!="" && $fecha_inicio!="" && $fecha_fin!="" && $ubicacion!="") {$estatus="C";}
	else if(($actividad_laboral=="V" || $actividad_laboral=="O") && $nombre_empresa!="" && $sector!="" && $area_descr!="" && $puesto_descr!="" && $fecha_inicio!="" && $fecha_fin!="" && $ubicacion!="") {$estatus="C";}
	else {$estatus="A";}

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_experiencia WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_experiencia VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$actividad_laboral','$otra_actividad','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr',$puesto_id,'$puesto_descr','$nombre_empresa','$rfc_empresa','$funcion_principal',$fecha_inicio,$fecha_fin,'$sector','$sector_descr','$otro_sector','$ubicacion','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_experiencia SET movimiento='$movimiento',actividad_laboral='$actividad_laboral',otra_actividad='$otra_actividad',orden_id='$orden_id',orden_descr='$orden_descr',ambito_id='$ambito_id',ambito_descr='$ambito_descr',dependencia='$dependencia',dependencia_descr='$dependencia_descr',area_adscripcion='$area_adscripcion',area_descr='$area_descr',puesto_id=$puesto_id,puesto_descr='$puesto_descr',nombre_empresa='$nombre_empresa',rfc_empresa='$rfc_empresa',funcion_principal='$funcion_principal',fecha_inicio=$fecha_inicio,fecha_fin=$fecha_fin,sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',ubicacion='$ubicacion',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_experiencia VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$actividad_laboral','$otra_actividad','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr',$puesto_id,'$puesto_descr','$nombre_empresa','$rfc_empresa','$funcion_principal',$fecha_inicio,$fecha_fin,'$sector','$sector_descr','$otro_sector','$ubicacion','$observaciones','$estatus')";
		$result=pg_query($conn,$sql);
	}
	print_r("Los datos se han guardado.");

}
if(isset($_POST["form6"])){
	//print_r($_POST);
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$nombre=escape($_POST["nombre"]);
	$primer_apellido=escape($_POST["primer_apellido"]);
	$segundo_apellido=escape($_POST["segundo_apellido"]);
	$fecha_nac=format_date($_POST["fecha_nac"]);
	$rfc_pareja=escape($_POST["rfc_pareja"]);
	$relacion_pareja=escape($_POST["relacion_pareja"]);
	//$relacion_descr=htmlspecialchars($_POST["relacion_pareja"]);
	$relacion_descr=escape(get_descr_lista($conn,$relacion_pareja,"descr","qsy_listas_valores","Relacion_Pareja"));
	$extranjero=escape($_POST["extranjero"]);
	$curp=escape($_POST["curp"]);
	$dependiente=escape($_POST["dependiente"]);
	$mismo_domicilio=escape($_POST["mismo_domicilio"]);
	$residencia=escape($_POST["residencia"]);
	$codigopostal=escape($_POST["cp"]);
	$calle=escape($_POST["calle"]);
	$num_exterior=escape($_POST["noexterior"]);
	$num_interior=escape($_POST["nointerior"]);
	if($_POST["estado2"]!=""){
		$estado="";
		$estado_descr=escape($_POST["estado2"]);
	}
	else{
		$estado=escape($_POST["estado"]);
		$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	}
	if(isset($_POST["municipio"])){
		$municipio=escape($_POST["municipio"]);
		$municipio_descr=escape(get_municipio($conn,$municipio,$estado));
	}
	else{
		$municipio="";
		$municipio_descr="";
	}
	if(isset($_POST["colonia2"])){
		$colonia=escape($_POST["colonia2"]);
		$colonia_descr=escape(get_colonia($conn,$colonia,$codigopostal));
	}
	else{
		$colonia="";
		$colonia_descr=escape($_POST["colonia"]);
	}
	$pais=escape($_POST["pais"]);
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$actividad_laboral=escape($_POST["sector"]);
	$actividad_descr=escape(get_descr_lista($conn,$actividad_laboral,"descr","qsy_listas_valores","Actividad_Laboral"));
	$otro_ambito=escape($_POST["otro_ambito"]);
	$orden_id=escape($_POST["nivel"]);
	$orden_descr=escape(get_descr_lista($conn,$orden_id,"descr","qsy_listas_valores","Orden_ID"));
	$ambito_id=escape($_POST["ambito_publico"]);
	$ambito_descr=escape(get_descr_lista($conn,$ambito_id,"descr","qsy_listas_valores","Ambito_ID"));
	if($_POST["ente"]!=""){
		if($_POST["area"]!="" || $_POST["puesto"]!=""){
			$dependencia=escape($_POST["ente"]);
			$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
			$area_adscripcion=escape($_POST["area"]);
			$area_descr=escape(get_descr($conn,$area_adscripcion,"descr","qsy_areas_adscripcion","area_adscripcion"));
			$id_puesto=escape($_POST["puesto"]);
			$puesto_descr=escape(get_descr_puesto($conn,$id_puesto));
		}
		else{
		$dependencia=escape($_POST["ente"]);
		$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$id_puesto=0;
		$puesto_descr=escape($_POST["puesto2"]);
		}
		if($_POST["ente"]=="999"){
			$dependencia_descr=escape($_POST["otro_ente"]);
		}
	}
	else{
		$dependencia="";
		$dependencia_descr=escape($_POST["ente2"]);
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$id_puesto=0;
		$puesto_descr=escape($_POST["puesto2"]);
	}
	$nombre_empresa=escape($_POST["ente2"]);
	$rfc_empresa=escape($_POST["rfc_empresa"]);
	$funcion_principal=escape($_POST["funcion_principal"]);
	$fecha_inicio=format_date($_POST["fecha_ingreso"]);
	$sector=escape($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$sueldo_mensual=str_replace(",","",escape($_POST["sueldo_mensual"]));
	if($sueldo_mensual=="")$sueldo_mensual=0;
	$proveedor=escape($_POST["proveedor"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($nombre!="" && $primer_apellido!="" && $fecha_nac!="" && $rfc_pareja!="" && $relacion_pareja!="" && $extranjero!="" && $curp!="" && $dependiente!="" && $mismo_domicilio!="" && $residencia!="" && $pais!=""){
			if(($residencia=="M" && $calle!="" && $num_exterior!="" && $colonia_descr!="" && $municipio_descr!="" && $estado_descr!="" && $codigopostal!="") || ($residencia!="M")){
				if($actividad_laboral=="N")$estatus="C";
				else if($actividad_laboral=="U" && $orden_id!="" && $ambito_id!="" && $dependencia_descr!="" && $area_descr!="" && $puesto_descr!="" && $funcion_principal!="" && $fecha_inicio!="" && $sueldo_mensual!="") $estatus="C";
				else if(($actividad_laboral=="V" || $actividad_laboral=="O") && $nombre_empresa!="" && $sector!="" && $rfc_empresa!="" && $area_descr!="" && $puesto_descr!="" && $fecha_inicio!="" && $sueldo_mensual!="" && $proveedor!="") $estatus="C";
				else $estatus="A";
			}
			else $estatus="A";
		}
		else $estatus="A";
	}
	else {
		$estatus="C";
		$nombre="";$primer_apellido="";$segundo_apellido="";$fecha_nac="null";$rfc_pareja="";$relacion_pareja="";$relacion_descr="";$extranjero="";$curp="";$dependiente="";$mismo_domicilio="";$residencia="";$calle="";$num_exterior="";$num_interior="";$colonia="";$colonia_descr="";$municipio="";$municipio_descr="";$estado="";$estado_descr="";$pais="";$pais_descr="";$codigopostal="";$actividad_laboral="";$actividad_descr="";$otro_ambito="";$orden_id="";$orden_descr="";$ambito_id="";$ambito_descr="";$dependencia="";$dependencia_descr="";$area_adscripcion="";$area_descr="";$id_puesto=0;$puesto_descr="";$nombre_empresa="";$rfc_empresa="";$funcion_principal="";$fecha_inicio="null";$sector="";$sector_descr="";$otro_sector="";$sueldo_mensual=0;$proveedor="";
	}

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_parejas WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_parejas VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$nombre','$primer_apellido','$segundo_apellido',$fecha_nac,'$rfc_pareja','$relacion_pareja','$relacion_descr','$extranjero','$curp','$dependiente','$mismo_domicilio','$residencia','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$actividad_laboral','$actividad_descr','$otro_ambito','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr',$id_puesto,'$puesto_descr','$nombre_empresa','$rfc_empresa','$funcion_principal',$fecha_inicio,'$sector','$sector_descr','$otro_sector',$sueldo_mensual,'$proveedor','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_parejas SET movimiento='$movimiento',nombre='$nombre',primer_apellido='$primer_apellido',segundo_apellido='$segundo_apellido',fecha_nac=$fecha_nac,rfc_pareja='$rfc_pareja',relacion_pareja='$relacion_pareja',relacion_descr='$relacion_descr',extranjero='$extranjero',curp='$curp',dependiente='$dependiente',mismo_domicilio='$mismo_domicilio',residencia='$residencia',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',colonia_descr='$colonia_descr',municipio='$municipio',municipio_descr='$municipio_descr',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',codigopostal='$codigopostal',actividad_laboral='$actividad_laboral',actividad_descr='$actividad_descr',otro_ambito='$otro_ambito',orden_id='$orden_id',orden_descr='$orden_descr',ambito_id='$ambito_id',ambito_descr='$ambito_descr',dependencia='$dependencia',dependencia_descr='$dependencia_descr',area_adscripcion='$area_adscripcion',area_descr='$area_descr',id_puesto=$id_puesto,puesto_descr='$puesto_descr',nombre_empresa='$nombre_empresa',rfc_empresa='$rfc_empresa',funcion_principal='$funcion_principal',fecha_inicio=$fecha_inicio,sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',sueldo_mensual=$sueldo_mensual,proveedor='$proveedor',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_parejas VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$nombre','$primer_apellido','$segundo_apellido',$fecha_nac,'$rfc_pareja','$relacion_pareja','$relacion_descr','$extranjero','$curp','$dependiente','$mismo_domicilio','$residencia','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$actividad_laboral','$actividad_descr','$otro_ambito','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr',$id_puesto,'$puesto_descr','$nombre_empresa','$rfc_empresa','$funcion_principal',$fecha_inicio,'$sector','$sector_descr','$otro_sector',$sueldo_mensual,'$proveedor','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}
if(isset($_POST["form7"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$nombre=escape($_POST["nombre"]);
	$primer_apellido=escape($_POST["primer_apellido"]);
	$segundo_apellido=escape($_POST["segundo_apellido"]);
	$fecha_nac=format_date($_POST["fecha_nac"]);
	$rfc_dependiente=escape($_POST["rfc_dependiente"]);
	$relacion_depend=escape($_POST["relacion_depend"]);
	$relacion_descr=escape(get_descr_lista($conn,$relacion_depend,"descr","qsy_listas_valores","Relacion_Depend"));
	$otra_relacion=escape($_POST["otra_relacion"]);
	$extranjero=escape($_POST["extranjero"]);
	$curp=escape($_POST["curp"]);
	$mismo_domicilio=escape($_POST["mismo_domicilio"]);
	$residencia=escape($_POST["residencia"]);
	$codigopostal=escape($_POST["cp"]);
	$calle=escape($_POST["calle"]);
	$num_exterior=escape($_POST["noexterior"]);
	$num_interior=escape($_POST["nointerior"]);
	if($_POST["estado2"]!=""){
		$estado="";
		$estado_descr=escape($_POST["estado2"]);
	}
	else{
		$estado=escape($_POST["estado"]);
		$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	}
	if(isset($_POST["municipio"])){
		$municipio=escape($_POST["municipio"]);
		$municipio_descr=escape(get_municipio($conn,$municipio,$estado));
	}
	else{
		$municipio="";
		$municipio_descr="";		
	}
	if(isset($_POST["colonia2"])){
		$colonia=escape($_POST["colonia2"]);
		$colonia_descr=escape(get_colonia($conn,$colonia,$codigopostal));
	}
	else{
		$colonia="";
		$colonia_descr=escape($_POST["colonia"]);
	}
	$pais=escape($_POST["pais"]);
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$actividad_laboral=escape($_POST["sector"]);
	$otro_ambito=escape($_POST["otro_ambito"]);
	$orden_id=escape($_POST["nivel"]);
	$orden_descr=escape(get_descr_lista($conn,$orden_id,"descr","qsy_listas_valores","Orden_ID"));
	$ambito_id=escape($_POST["ambito_publico"]);
	$ambito_descr=escape(get_descr_lista($conn,$ambito_id,"descr","qsy_listas_valores","Ambito_ID"));
	if($_POST["ente"]!=""){
		if($_POST["area"]!="" || $_POST["puesto"]!=""){
			$dependencia=escape($_POST["ente"]);
			$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
			$area_adscripcion=escape($_POST["area"]);
			$area_descr=escape(get_descr($conn,$area_adscripcion,"descr","qsy_areas_adscripcion","area_adscripcion"));
			$puesto_id=escape($_POST["puesto"]);
			$puesto_descr=escape(get_descr_puesto($conn,$puesto_id));
		}
		else{
		$dependencia=escape($_POST["ente"]);
		$dependencia_descr=escape(get_descr($conn,$dependencia,"descr","qsy_dependencias","dependencia"));
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$puesto_id=0;
		$puesto_descr=escape($_POST["puesto2"]);
		}
		if($_POST["ente"]=="999"){
			$dependencia_descr=escape($_POST["otro_ente"]);
		}
	}
	else{
		$dependencia="";
		$dependencia_descr=escape($_POST["ente2"]);
		$area_adscripcion="";
		$area_descr=escape($_POST["area2"]);
		$puesto_id=0;
		$puesto_descr=escape($_POST["puesto2"]);
	}

	$nombre_empresa=escape($_POST["ente2"]);
	$nivel_empleo="";
	$nivel_descr="";
	$rfc_empresa=escape($_POST["rfc_empresa"]);
	$funcion_principal=escape($_POST["funcion_principal"]);
	$fecha_inicio=format_date($_POST["fecha_ingreso"]);
	$sector=escape($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$sueldo_mensual=str_replace(",","",escape($_POST["sueldo_mensual"]));
	if($sueldo_mensual=="")$sueldo_mensual=0;
	$proveedor=escape($_POST["proveedor"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($nombre!="" && $primer_apellido!="" && $fecha_nac!="" && $relacion_depend!="" && $extranjero!="" && $curp!="" && $mismo_domicilio!="" && $residencia!="" && $pais!=""){
			if(($residencia=="M" && $calle!="" && $num_exterior!="" && $colonia_descr!="" && $municipio_descr!="" && $estado_descr!="" && $codigopostal!="") || ($residencia!="M")){
				if($actividad_laboral=="N")$estatus="C";
				else if($actividad_laboral=="U" && $orden_id!="" && $ambito_id!="" && $dependencia_descr!="" && $area_descr!="" && $puesto_descr!="" && $funcion_principal!="" && $fecha_inicio!="" && $sueldo_mensual!="") $estatus="C";
				else if(($actividad_laboral=="V" || $actividad_laboral=="O") && $nombre_empresa!="" && $sector!="" && $rfc_empresa!="" && $area_descr!="" && $puesto_descr!="" && $fecha_inicio!="" && $sueldo_mensual!="" && $proveedor!="") $estatus="C";
				else $estatus="A";
			}
			else $estatus="A";
		}
		else $estatus="A";
	}
	else{
		$estatus="C";
		$nombre="";$primer_apellido="";$segundo_apellido="";$fecha_nac="null";$rfc_dependiente="";$relacion_depend="";$relacion_descr="";$otra_relacion="";$extranjero="";$curp="";$mismo_domicilio="";$residencia="";$calle="";$num_exterior="";$num_interior="";$colonia="";$colonia_descr="";$municipio="";$municipio_descr="";$estado="";$estado_descr="";$pais="";$pais_descr="";$codigopostal="";$actividad_laboral="";$otro_ambito="";$orden_id="";$orden_descr="";$ambito_id="";$ambito_descr="";$dependencia="";$dependencia_descr="";$area_adscripcion="";$area_descr="";$nivel_empleo="";$nivel_descr="";$puesto_id=0;$puesto_descr="";$nombre_empresa="";$rfc_empresa="";$funcion_principal="";$fecha_inicio="null";$sector="";$sector_descr="";$otro_sector="";$sueldo_mensual=0;$proveedor="";
	} 
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_dependientes WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_dependientes VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$nombre','$primer_apellido','$segundo_apellido',$fecha_nac,'$rfc_dependiente','$relacion_depend','$relacion_descr','$otra_relacion','$extranjero','$curp','$mismo_domicilio','$residencia','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$actividad_laboral','$otro_ambito','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr','$nivel_empleo','$nivel_descr',$puesto_id,'$puesto_descr','$nombre_empresa','$rfc_empresa','$funcion_principal',$fecha_inicio,'$sector','$sector_descr','$otro_sector',$sueldo_mensual,'$proveedor','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_dependientes SET movimiento='$movimiento',nombre='$nombre',primer_apellido='$primer_apellido',segundo_apellido='$segundo_apellido',fecha_nac=$fecha_nac,rfc_dependiente='$rfc_dependiente',relacion_depend='$relacion_depend',relacion_descr='$relacion_descr',otra_relacion='$otra_relacion',extranjero='$extranjero',curp='$curp',mismo_domicilio='$mismo_domicilio',residencia='$residencia',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',colonia_descr='$colonia_descr',municipio='$municipio',municipio_descr='$municipio_descr',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',codigopostal='$codigopostal',actividad_laboral='$actividad_laboral',otro_ambito='$otro_ambito',orden_id='$orden_id',orden_descr='$orden_descr',ambito_id='$ambito_id',ambito_descr='$ambito_descr',dependencia='$dependencia',dependencia_descr='$dependencia_descr',area_adscripcion='$area_adscripcion',area_descr='$area_descr',nivel_empleo='$nivel_empleo',nivel_descr='$nivel_descr',puesto_id=$puesto_id,puesto_descr='$puesto_descr',nombre_empresa='$nombre_empresa',rfc_empresa='$rfc_empresa',funcion_principal='$funcion_principal',fecha_inicio=$fecha_inicio,sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',sueldo_mensual=$sueldo_mensual,proveedor='$proveedor',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_dependientes VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$nombre','$primer_apellido','$segundo_apellido',$fecha_nac,'$rfc_dependiente','$relacion_depend','$relacion_descr','$otra_relacion','$extranjero','$curp','$mismo_domicilio','$residencia','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$actividad_laboral','$otro_ambito','$orden_id','$orden_descr','$ambito_id','$ambito_descr','$dependencia','$dependencia_descr','$area_adscripcion','$area_descr','$nivel_empleo','$nivel_descr',$puesto_id,'$puesto_descr','$nombre_empresa','$rfc_empresa','$funcion_principal',$fecha_inicio,'$sector','$sector_descr','$otro_sector',$sueldo_mensual,'$proveedor','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");

}
if(isset($_POST["form8"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$remunera_neta=str_replace(",","",escape($_POST["remunera_neta"]));
	if($remunera_neta=='')$remunera_neta=0;
	$otros_ingresos=str_replace(",","",escape($_POST["otros_ingresos"]));
	if($otros_ingresos=='')$otros_ingresos=0;
	$activ_industrial=str_replace(",","",escape($_POST["activ_industrial"]));
	if($activ_industrial=='')$activ_industrial=0;
	$razon_social=escape($_POST["razon_social"]);
	$tipo_negocio=escape($_POST["tipo_neg"]);
	$activ_financiera=str_replace(",","",escape($_POST["activ_financiera"]));
	if($activ_financiera=='')$activ_financiera=0;
	$tipo_instrumento=escape($_POST["instrumento"]);
	$otro_instrumento=escape($_POST["otro_instrumento"]);
	$serv_profesionales=str_replace(",","",escape($_POST["serv_profesionales"]));
	if($serv_profesionales=='')$serv_profesionales=0;
	$tipo_servicio=escape($_POST["tipo_servicio"]);
	$no_considerados=str_replace(",","",escape($_POST["no_considerados"]));
	if($no_considerados=='')$no_considerados=0;
	$tipo_ingreso=escape($_POST["tipo_ingreso"]);
	$ingreso_neto=str_replace(",","",escape($_POST["ingreso_neto"]));
	if($ingreso_neto=='')$ingreso_neto=0;
	$ingreso_pareja=str_replace(",","",escape($_POST["ingreso_pareja"]));
	if($ingreso_pareja=='')$ingreso_pareja=0;
	$total_ingresos=str_replace(",","",escape($_POST["ingreso_total"]));
	if($total_ingresos=='')$total_ingresos=0;
	$observaciones=escape($_POST["observaciones"]);
	$enajena_bienes=0;
	$tipo_bien="";
	$tipo_descr="";
	if(isset($_POST["enajena_bienes"])){
		$enajena_bienes=str_replace(",","",escape($_POST["enajena_bienes"]));
		if($enajena_bienes=='')$enajena_bienes=0;
		$tipo_bien=escape($_POST["tipo_bien"]);
		$tipo_descr=escape(get_descr_lista($conn,$tipo_bien,"descr","qsy_listas_valores","Tipo_Bien"));
	}
	if($remunera_neta!="" && $otros_ingresos!="" && $activ_industrial!="" && $activ_financiera!="" && $serv_profesionales!="" && $tipo_servicio!="" && $no_considerados!="" && $ingreso_neto!="" && $ingreso_pareja!="" && $total_ingresos!="" && ($enajena_bienes!="" || $tipo_decl=="I")) $estatus="C";
	else $estatus="A";

	if(isset($_FILES["archivo"]) && $_FILES["archivo"]["error"]!=4 && $_FILES["archivo"]["size"]<5240000){ //Comprueba si la variable "archivo" se ha definido
		$nombre="../files/isr/".$rfc."-".$ejercicio.".pdf";
		move_uploaded_file($_FILES["archivo"]["tmp_name"],$nombre);
	}

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_ingresos_netos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		$sql="UPDATE qsy_ingresos_netos SET remunera_neta=$remunera_neta,otros_ingresos=$otros_ingresos,activ_industrial=$activ_industrial,razon_social='$razon_social',tipo_negocio='$tipo_negocio',activ_financiera=$activ_financiera,tipo_instrumento='$tipo_instrumento',otro_instrumento='$otro_instrumento',serv_profesionales=$serv_profesionales,tipo_servicio='$tipo_servicio',enajena_bienes=$enajena_bienes,tipo_bien='$tipo_bien',tipo_descr='$tipo_descr',no_considerados=$no_considerados,tipo_ingreso='$tipo_ingreso',ingreso_neto=$ingreso_neto,ingreso_pareja=$ingreso_pareja,total_ingresos=$total_ingresos,observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_ingresos_netos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl','A',$remunera_neta,$otros_ingresos,$activ_industrial,'$razon_social','$tipo_negocio',$activ_financiera,'$tipo_instrumento','$otro_instrumento',$serv_profesionales,'$tipo_servicio',$enajena_bienes,'$tipo_bien','$tipo_descr',$no_considerados,'$tipo_ingreso',$ingreso_neto,$ingreso_pareja,$total_ingresos,'$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}
if(isset($_POST["form9"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$servidor_anio_prev=escape($_POST["servidor"]);
	$fecha_inicio=format_date($_POST["fecha_inicio"]);
	$fecha_fin=format_date($_POST["fecha_fin"]);
	$remunera_neta=str_replace(",","",escape($_POST["remunera_neta"]));
	if($remunera_neta=='')$remunera_neta=0;
	$otros_ingresos=str_replace(",","",escape($_POST["otros_ingresos"]));
	if($otros_ingresos=='')$otros_ingresos=0;
	$activ_industrial=str_replace(",","",escape($_POST["activ_industrial"]));
	if($activ_industrial=='')$activ_industrial=0;
	$razon_social=escape($_POST["razon_social"]);
	$tipo_negocio=escape($_POST["tipo_neg"]);
	$activ_financiera=str_replace(",","",escape($_POST["activ_financiera"]));
	if($activ_financiera=='')$activ_financiera=0;
	$tipo_instrumento=escape($_POST["instrumento"]);
	$otro_instrumento=escape($_POST["otro_instrumento"]);
	$serv_profesionales=str_replace(",","",escape($_POST["serv_profesionales"]));
	if($serv_profesionales=='')$serv_profesionales=0;
	$tipo_servicio=escape($_POST["tipo_servicio"]);
	$no_considerados=str_replace(",","",escape($_POST["no_considerados"]));
	if($no_considerados=='')$no_considerados=0;
	$tipo_ingreso=escape($_POST["tipo_ingreso"]);
	$ingreso_neto=str_replace(",","",escape($_POST["ingreso_neto"]));
	if($ingreso_neto=='')$ingreso_neto=0;
	$ingreso_pareja=str_replace(",","",escape($_POST["ingreso_pareja"]));
	if($ingreso_pareja=='')$ingreso_pareja=0;
	$total_ingresos=str_replace(",","",escape($_POST["ingreso_total"]));
	if($total_ingresos=='')$total_ingresos=0;
	$observaciones=escape($_POST["observaciones"]);
	$enajena_bienes=0;
	$tipo_bien="";
	$tipo_descr="";
	if(isset($_POST["enajena_bienes"])){
		$enajena_bienes=str_replace(",","",escape($_POST["enajena_bienes"]));
		if($enajena_bienes=='')$enajena_bienes=0;
		$tipo_bien=escape($_POST["tipo_bien"]);
		$tipo_descr=escape(get_descr_lista($conn,$tipo_bien,"descr","qsy_listas_valores","Tipo_Bien"));
	}
	if($servidor_anio_prev=="N"){
		$estatus="C";
		$fecha_inicio="null";$fecha_fin="null";$remunera_neta=0;$otros_ingresos=0;$activ_industrial=0;$razon_social="";$tipo_negocio="";$activ_financiera=0;$tipo_instrumento="";$otro_instrumento="";$serv_profesionales=0;$tipo_servicio="";$enajena_bienes=0;$tipo_bien="";$tipo_descr="";$no_considerados=0;$tipo_ingreso="";$ingreso_neto=0;$ingreso_pareja=0;$total_ingresos=0;
	}
	else{
		if($fecha_inicio!="" && $fecha_fin!="" && $remunera_neta!="" && $otros_ingresos!="" && $activ_industrial!="" && $activ_financiera!="" && $serv_profesionales!="" && $tipo_servicio!="" && $no_considerados!="" && $ingreso_neto!="" && $ingreso_pareja!="" && $total_ingresos!="" && $enajena_bienes!="") $estatus="C";
		else $estatus="A";
	}

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_ingresos_anio_anterior WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		$sql="UPDATE qsy_ingresos_anio_anterior SET servidor_anio_prev='$servidor_anio_prev',fecha_inicio=$fecha_inicio,fecha_fin=$fecha_fin,remunera_neta=$remunera_neta,otros_ingresos=$otros_ingresos,activ_industrial=$activ_industrial,razon_social='$razon_social',tipo_negocio='$tipo_negocio',activ_financiera=$activ_financiera,tipo_instrumento='$tipo_instrumento',otro_instrumento='$otro_instrumento',serv_profesionales=$serv_profesionales,tipo_servicio='$tipo_servicio',enajena_bienes=$enajena_bienes,tipo_bien='$tipo_bien',tipo_descr='$tipo_descr',no_considerados=$no_considerados,tipo_ingreso='$tipo_ingreso',ingreso_neto=$ingreso_neto,ingreso_pareja=$ingreso_pareja,total_ingresos=$total_ingresos,observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_ingresos_anio_anterior VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl','$servidor_anio_prev',$fecha_inicio,$fecha_fin,$remunera_neta,$otros_ingresos,$activ_industrial,'$razon_social','$tipo_negocio',$activ_financiera,'$tipo_instrumento','$otro_instrumento',$serv_profesionales,'$tipo_servicio',$enajena_bienes,'$tipo_bien','$tipo_descr',$no_considerados,'$tipo_ingreso',$ingreso_neto,$ingreso_pareja,$total_ingresos,'$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["form10"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante="";
	$tipo_inmueble=escape($_POST["tipo_inmueble"]);
	$otro_inmueble=escape($_POST["otro_inmueble"]);
	$titular=escape($_POST["titular"]);
	//$titular_descr=htmlspecialchars($_POST["titular"]);
	$titular_descr=escape(get_descr_lista($conn,$titular,"descr","qsy_listas_valores","Titular"));
	$pct_propiedad=escape($_POST["pct_propiedad"]);
		if($pct_propiedad=='')$pct_propiedad="0.00";
	$sup_terreno=str_replace(",","",escape($_POST["sup_terreno"]));
		if($sup_terreno=='')$sup_terreno="0.00";
	$sup_construc=str_replace(",","",escape($_POST["sup_construc"]));
		if($sup_construc=='')$sup_construc="0.00";
	$adquisicion=escape($_POST["adquisicion"]);
	$adquisicion_descr=escape(get_descr_lista($conn,$adquisicion,"descr","qsy_listas_valores","Adquisicion"));
	$forma_pago=escape($_POST["forma_pago"]);
	$forma_descr=escape(get_descr_lista($conn,$forma_pago,"descr","qsy_listas_valores","Forma_Pago"));
	$tercero=escape($_POST["tercero"]);
	$tercero_descr=escape(get_descr_lista($conn,$tercero,"descr","qsy_listas_valores","Tercero"));
	$nombre_tercero=escape($_POST["nombre_tercero"]);
	$rfc_tercero=escape($_POST["rfc_tercero"]);
	$transmisor=escape($_POST["transmisor"]);
	$transmisor_descr=escape(get_descr_lista($conn,$transmisor,"descr","qsy_listas_valores","Tercero"));
	$nombre_transmisor=escape($_POST["nombre_transmisor"]);
	$rfc_transmisor=escape($_POST["rfc_transmisor"]);
	$relacion=escape($_POST["relacion"]);
	$relacion_descr=escape(get_descr_lista($conn,$relacion,"descr","qsy_listas_valores","Relacion"));
	$otra_relacion=escape($_POST["otra_relacion"]);
	$valor_adquisicion=str_replace(",","",escape($_POST["valor_adquisicion"]));
		if($valor_adquisicion=='')$valor_adquisicion="0.00";
	$tipo_moneda=escape($_POST["tipo_moneda"]);
	$fecha_adquisicion=format_date($_POST["fecha_adquisicion"]);
	$registro_publico=escape($_POST["registro_publico"]);
	$valor_conforme_a=escape($_POST["valor_conforme_a"]);
	$conforme_descr=escape(get_descr_lista($conn,$valor_conforme_a,"descr","qsy_listas_valores","Valor_Conforme_A"));
	$ubicacion=escape($_POST["ubicacion"]);
	$codigopostal=escape($_POST["cp"]);
	$calle=escape($_POST["calle"]);
	$num_exterior=escape($_POST["noexterior"]);
	$num_interior=escape($_POST["nointerior"]);
	if($_POST["estado2"]!=""){
		$estado="";
		$estado_descr=escape($_POST["estado2"]);
	}
	else{
		$estado=escape($_POST["estado"]);
		$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	}
	if(isset($_POST["municipio"])){
		$municipio=escape($_POST["municipio"]);
		$municipio_descr=escape(get_municipio($conn,$municipio,$estado));
	}
	else{
		$municipio="";
		$municipio_descr="";		
	}
	if(isset($_POST["colonia2"])){
		$colonia=escape($_POST["colonia2"]);
		$colonia_descr=escape(get_colonia($conn,$colonia,$codigopostal));
	}
	else{
		$colonia="";
		$colonia_descr=escape($_POST["colonia"]);
	}
	$pais=escape($_POST["pais"]);
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$causa_baja=escape($_POST["causa_baja"]);
	$otra_causa=escape($_POST["otra_causa"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($tipo_inmueble!="" && $titular!="" && $pct_propiedad!="" && $sup_terreno!="" && $sup_construc!="" && $adquisicion!="" && $forma_pago!="" && $valor_adquisicion!="" && $tipo_moneda!="" && $fecha_adquisicion!="" && $registro_publico!="" && $valor_conforme_a!="" && $ubicacion!="" && $pais!=""){
			if(($ubicacion=="M" && $calle!="" && $num_exterior!="" && $colonia_descr!="" && $municipio_descr!="" && $estado_descr!="" && $pais!="" && $codigopostal!="") || $ubicacion!="M") 
				$estatus="C";
			else $estatus="A";
		}
		else $estatus="A";
	}
	else{
		$estatus="C";
		$tipo_inmueble="";$otro_inmueble="";$titular="";$titular_descr="";$pct_propiedad="0.00";$sup_terreno="0.00";$sup_construc="0.00";$adquisicion="";$adquisicion_descr="";$forma_pago="";$forma_descr="";$tercero="";$tercero_descr="";$nombre_tercero="";$rfc_tercero="";$transmisor="";$transmisor_descr="";$nombre_transmisor="";$rfc_transmisor="";$relacion="";$relacion_descr="";$otra_relacion="";$valor_adquisicion="0.00";$tipo_moneda="";$fecha_adquisicion="null";$registro_publico="";$valor_conforme_a="";$conforme_descr="";$ubicacion="";$calle="";$num_exterior="";$num_interior="";$colonia="";$colonia_descr="";$municipio="";$municipio_descr="";$estado="";$estado_descr="";$pais="";$pais_descr="";$codigopostal="";$causa_baja="";$otra_causa="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_inmuebles WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_inmuebles VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_inmueble','$otro_inmueble','$titular','$titular_descr',$pct_propiedad,$sup_terreno,$sup_construc,'$adquisicion','$adquisicion_descr','$forma_pago','$forma_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$transmisor','$transmisor_descr','$nombre_transmisor','$rfc_transmisor','$relacion','$relacion_descr','$otra_relacion',$valor_adquisicion,'$tipo_moneda',$fecha_adquisicion,'$registro_publico','$valor_conforme_a','$conforme_descr','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$causa_baja','$otra_causa','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_inmuebles SET movimiento='$movimiento',declarante='$declarante',tipo_inmueble='$tipo_inmueble',otro_inmueble='$otro_inmueble',titular='$titular',titular_descr='$titular_descr',pct_propiedad=$pct_propiedad,sup_terreno=$sup_terreno,sup_construc=$sup_construc,adquisicion='$adquisicion',adquisicion_descr='$adquisicion_descr',forma_pago='$forma_pago',forma_descr='$forma_descr',tercero='$tercero',tercero_descr='$tercero_descr',nombre_tercero='$nombre_tercero',rfc_tercero='$rfc_tercero',transmisor='$transmisor',transmisor_descr='$transmisor_descr',nombre_transmisor='$nombre_transmisor',rfc_transmisor='$rfc_transmisor',relacion='$relacion',relacion_descr='$relacion_descr',otra_relacion='$otra_relacion',valor_adquisicion=$valor_adquisicion,tipo_moneda='$tipo_moneda',fecha_adquisicion=$fecha_adquisicion,registro_publico='$registro_publico',valor_conforme_a='$valor_conforme_a',conforme_descr='$conforme_descr',ubicacion='$ubicacion',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',colonia_descr='$colonia_descr',municipio='$municipio',municipio_descr='$municipio_descr',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',codigopostal='$codigopostal',causa_baja='$causa_baja',otra_causa='$otra_causa',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_inmuebles VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_inmueble','$otro_inmueble','$titular','$titular_descr',$pct_propiedad,$sup_terreno,$sup_construc,'$adquisicion','$adquisicion_descr','$forma_pago','$forma_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$transmisor','$transmisor_descr','$nombre_transmisor','$rfc_transmisor','$relacion','$relacion_descr','$otra_relacion',$valor_adquisicion,'$tipo_moneda',$fecha_adquisicion,'$registro_publico','$valor_conforme_a','$conforme_descr','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$causa_baja','$otra_causa','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["form11"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante="";
	$tipo_vehiculo=escape($_POST["tipo_vehiculo"]);
	$otro_vehiculo=escape($_POST["otro_vehiculo"]);
	$titular=escape($_POST["titular"]);
	$titular_descr=escape(get_descr_lista($conn,$titular,"descr","qsy_listas_valores","Titular"));
	$tercero=escape($_POST["tercero"]);
	$tercero_descr=escape(get_descr_lista($conn,$tercero,"descr","qsy_listas_valores","Tercero"));
	$nombre_tercero=escape($_POST["nombre_tercero"]);
	$rfc_tercero=escape($_POST["rfc_tercero"]);
	$transmisor=escape($_POST["transmisor"]);
	$transmisor_descr=escape(get_descr_lista($conn,$transmisor,"descr","qsy_listas_valores","Tercero"));
	$nombre_transmisor=escape($_POST["nombre_transmisor"]);
	$rfc_transmisor=escape($_POST["rfc_transmisor"]);
	$relacion=escape($_POST["relacion"]);
	$relacion_descr=escape(get_descr_lista($conn,$relacion,"descr","qsy_listas_valores","Relacion"));
	$otra_relacion=escape($_POST["otra_relacion"]);
	$marca=escape($_POST["marca"]);
	$modelo=escape($_POST["modelo"]);
	$anio=escape($_POST["anio"]);
		if($anio=='')$anio=0;
	$serie=escape($_POST["serie"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$estado=escape($_POST["estado"]);
	$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	$pais=escape($_POST["pais"]);
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$adquisicion=escape($_POST["adquisicion"]);
	$adquisicion_descr=escape(get_descr_lista($conn,$adquisicion,"descr","qsy_listas_valores","Adquisicion"));
	$forma_pago=escape($_POST["forma_pago"]);
	$forma_descr=escape(get_descr_lista($conn,$forma_pago,"descr","qsy_listas_valores","Forma_Pago"));
	$valor_adquisicion=str_replace(",","",escape($_POST["valor_adquisicion"]));
		if($valor_adquisicion=='')$valor_adquisicion="0.00";
	$tipo_moneda=escape($_POST["tipo_moneda"]);
	$fecha_adquisicion=format_date($_POST["fecha_adquisicion"]);
	$causa_baja=escape($_POST["causa_baja"]);
	$otra_causa=escape($_POST["otra_causa"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($tipo_vehiculo!="" && $titular!="" && $marca!="" && $modelo!="" && $anio!="" && $serie!="" && $ubicacion!="" && $pais!="" && $adquisicion!="" && $forma_pago!="" && $valor_adquisicion!="" && $tipo_moneda!="" && $fecha_adquisicion!=""){
			if(($ubicacion=="M" && $estado!="") || $ubicacion!="M"){
				$estatus="C";
			}
			else $estatus="A";
		}
		else $estatus="A";
	}
	else{ 
		$estatus="C";
		$tipo_vehiculo="";$otro_vehiculo="";$titular="";$titular_descr="";$tercero="";$tercero_descr="";$nombre_tercero="";$rfc_tercero="";$transmisor="";$transmisor_descr="";$nombre_transmisor="";$rfc_transmisor="";$relacion="";$relacion_descr="";$otra_relacion="";$marca="";$modelo="";$anio=0;$serie="";$ubicacion="";$estado="";$estado_descr="";$pais="";$pais_descr="";$adquisicion="";$adquisicion_descr="";$forma_pago="";$forma_descr="";$valor_adquisicion=0;$tipo_moneda="";$fecha_adquisicion="null";$causa_baja="";$otra_causa="";
	}
	//print_r($estatus);
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_vehiculos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_vehiculos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_vehiculo','$otro_vehiculo','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$transmisor','$transmisor_descr','$nombre_transmisor','$rfc_transmisor','$relacion','$relacion_descr','$otra_relacion','$marca','$modelo',$anio,'$serie','$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$adquisicion','$adquisicion_descr','$forma_pago','$forma_descr',$valor_adquisicion,'$tipo_moneda',$fecha_adquisicion,'$causa_baja','$otra_causa','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_vehiculos SET movimiento='$movimiento',declarante='$declarante',tipo_vehiculo='$tipo_vehiculo',otro_vehiculo='$otro_vehiculo',titular='$titular',titular_descr='$titular_descr',tercero='$tercero',tercero_descr='$tercero_descr',nombre_tercero='$nombre_tercero',rfc_tercero='$rfc_tercero',transmisor='$transmisor',transmisor_descr='$transmisor_descr',nombre_transmisor='$nombre_transmisor',rfc_transmisor='$rfc_transmisor',relacion='$relacion',relacion_descr='$relacion_descr',otra_relacion='$otra_relacion',marca='$marca',modelo='$modelo',anio=$anio,serie='$serie',ubicacion='$ubicacion',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',adquisicion='$adquisicion',adquisicion_descr='$adquisicion_descr',forma_pago='$forma_pago',forma_descr='$forma_descr',valor_adquisicion=$valor_adquisicion,tipo_moneda='$tipo_moneda',fecha_adquisicion=$fecha_adquisicion,causa_baja='$causa_baja',otra_causa='$otra_causa',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_vehiculos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_vehiculo','$otro_vehiculo','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$transmisor','$transmisor_descr','$nombre_transmisor','$rfc_transmisor','$relacion','$relacion_descr','$otra_relacion','$marca','$modelo',$anio,'$serie','$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$adquisicion','$adquisicion_descr','$forma_pago','$forma_descr',$valor_adquisicion,'$tipo_moneda',$fecha_adquisicion,'$causa_baja','$otra_causa','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["form12"])){
	//print_r($rfc);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante="";
	$tipo_mueble=escape($_POST["tipo_mueble"]);
	$tipo_descr=escape($_POST["tipo_descr"]); //otro_mueble
	$titular=escape($_POST["titular"]);
	$titular_descr=escape(get_descr_lista($conn,$titular,"descr","qsy_listas_valores","Titular"));
	$tercero=escape($_POST["tercero"]);
	$tercero_descr=escape(get_descr_lista($conn,$tercero,"descr","qsy_listas_valores","Tercero"));
	$nombre_tercero=escape($_POST["nombre_tercero"]);
	$rfc_tercero=escape($_POST["rfc_tercero"]);
	$transmisor=escape($_POST["transmisor"]);
	$transmisor_descr=escape(get_descr_lista($conn,$transmisor,"descr","qsy_listas_valores","Tercero"));
	$nombre_transmisor=escape($_POST["nombre_transmisor"]);
	$rfc_transmisor=escape($_POST["rfc_transmisor"]);
	$relacion=escape($_POST["relacion"]);
	$relacion_descr=escape(get_descr_lista($conn,$relacion,"descr","qsy_listas_valores","Relacion"));
	$otra_relacion=escape($_POST["otra_relacion"]);
	$descripcion=escape($_POST["descripcion"]);
	$adquisicion=escape($_POST["adquisicion"]);
	$adquisicion_descr=escape(get_descr_lista($conn,$adquisicion,"descr","qsy_listas_valores","Adquisicion"));
	$forma_pago=escape($_POST["forma_pago"]);
	$forma_descr=escape(get_descr_lista($conn,$forma_pago,"descr","qsy_listas_valores","Forma_Pago"));
	$valor_adquisicion=str_replace(",","",escape($_POST["valor_adquisicion"]));
		if($valor_adquisicion=='')$valor_adquisicion="0.00";
	$tipo_moneda=escape($_POST["tipo_moneda"]);
	$fecha_adquisicion=format_date($_POST["fecha_adquisicion"]);
	$causa_baja=escape($_POST["causa_baja"]);
	$otra_causa=escape($_POST["otra_causa"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($tipo_mueble!="" && $titular!="" && $descripcion!="" && $adquisicion!="" && $forma_pago!="" && $valor_adquisicion!="" && $tipo_moneda!="" && $fecha_adquisicion!="")$estatus="C";
		else $estatus="A";
	}
	else{ 
		$estatus="C";
		$tipo_mueble="";$tipo_descr="";$titular="";$titular_descr="";$tercero="";$tercero_descr="";$nombre_tercero="";$rfc_tercero="";$transmisor="";$transmisor_descr="";$nombre_transmisor="";$rfc_transmisor="";$relacion="";$relacion_descr="";$otra_relacion="";$descripcion="";$adquisicion="";$adquisicion_descr="";$forma_pago="";$forma_descr="";$valor_adquisicion=0;$tipo_moneda="";$fecha_adquisicion="null";$causa_baja="";$otra_causa="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_muebles WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_muebles VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_mueble','$tipo_descr','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$transmisor','$transmisor_descr','$nombre_transmisor','$rfc_transmisor','$relacion','$relacion_descr','$otra_relacion','$descripcion','$adquisicion','$adquisicion_descr','$forma_pago','$forma_descr',$valor_adquisicion,'$tipo_moneda',$fecha_adquisicion,'$causa_baja','$otra_causa','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_muebles SET movimiento='$movimiento',declarante='$declarante',tipo_mueble='$tipo_mueble',tipo_descr='$tipo_descr',titular='$titular',titular_descr='$titular_descr',tercero='$tercero',tercero_descr='$tercero_descr',nombre_tercero='$nombre_tercero',rfc_tercero='$rfc_tercero',transmisor='$transmisor',transmisor_descr='$transmisor_descr',nombre_transmisor='$nombre_transmisor',rfc_transmisor='$rfc_transmisor',relacion='$relacion',relacion_descr='$relacion_descr',otra_relacion='$otra_relacion',descripcion='$descripcion',adquisicion='$adquisicion',adquisicion_descr='$adquisicion_descr',forma_pago='$forma_pago',forma_descr='$forma_descr',valor_adquisicion=$valor_adquisicion,tipo_moneda='$tipo_moneda',fecha_adquisicion=$fecha_adquisicion,causa_baja='$causa_baja',otra_causa='$otra_causa',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_muebles VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_mueble','$tipo_descr','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$transmisor','$transmisor_descr','$nombre_transmisor','$rfc_transmisor','$relacion','$relacion_descr','$otra_relacion','$descripcion','$adquisicion','$adquisicion_descr','$forma_pago','$forma_descr',$valor_adquisicion,'$tipo_moneda',$fecha_adquisicion,'$causa_baja','$otra_causa','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}
if(isset($_POST["form13"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante="";
	$tipo_inversion=escape($_POST["tipo_inversion"]);
	$tipo_inver_descr=escape(get_descr_lista($conn,$tipo_inversion,"descr","qsy_listas_valores","Tipo_Inversion"));
	$bancaria=escape($_POST["bancaria"]);
	$bancaria_descr=escape(get_descr_lista($conn,$bancaria,"descr","qsy_listas_valores","Bancaria"));
	$fondo=escape($_POST["fondo"]);
	$fondo_descr=escape(get_descr_lista($conn,$fondo,"descr","qsy_listas_valores","Fondo"));
	$org_privada=escape($_POST["org_privada"]);
	$org_descr=escape(get_descr_lista($conn,$org_privada,"descr","qsy_listas_valores","Org_Privada"));
	$monedas=escape($_POST["monedas"]);
	$monedas_descr=escape(get_descr_lista($conn,$monedas,"descr","qsy_listas_valores","Monedas"));
	$seguros=escape($_POST["seguros"]);
	$seguros_descr=escape(get_descr_lista($conn,$seguros,"descr","qsy_listas_valores","Seguros"));
	$valor_bursatil=escape($_POST["valor_bursatil"]);
	$valor_descr=escape(get_descr_lista($conn,$valor_bursatil,"descr","qsy_listas_valores","Valor_Bursatil"));
	$afores=escape($_POST["afores"]);
	$afores_descr=escape(get_descr_lista($conn,$afores,"descr","qsy_listas_valores","Afores"));
	$titular=escape($_POST["titular"]);
	$titular_descr=escape(get_descr_lista($conn,$titular,"descr","qsy_listas_valores","Titular"));
	$tercero=escape($_POST["tercero"]);
	$tercero_descr=escape(get_descr_lista($conn,$tercero,"descr","qsy_listas_valores","Tercero"));
	$nombre_tercero=escape($_POST["nombre_tercero"]);
	$rfc_tercero=escape($_POST["rfc_tercero"]);
	$num_cta=escape($_POST["num_cta"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$razon_social=escape($_POST["razon_social"]);
	$rfc_institucion=escape($_POST["rfc_institucion"]);
	$pais=escape($_POST["pais"]);
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$saldo=str_replace(",","",escape($_POST["saldo"]));
		if($saldo=='')$saldo=0;
	$tipo_moneda=escape($_POST["tipo_moneda"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($tipo_inversion!="" && ($bancaria!="" || $fondo!="" || $org_privada!="" || $monedas!="" || $seguros!="" || $valor_bursatil!="" || $afores!="") && $titular!="" && $num_cta!="" && $ubicacion!="" && $razon_social!="" && $rfc_institucion!="" && $pais!="" && $saldo!="" && $tipo_moneda!="")$estatus="C";
		else $estatus="A";
	}
	else{
		$estatus="C";
		$tipo_inversion="";$tipo_inver_descr="";$bancaria="";$bancaria_descr="";$fondo="";$fondo_descr="";$org_privada="";$org_descr="";$monedas="";$monedas_descr="";$seguros="";$seguros_descr="";$valor_bursatil="";$valor_descr="";$afores="";$afores_descr="";$titular="";$titular_descr="";$tercero="";$tercero_descr="";$nombre_tercero="";$rfc_tercero="";$num_cta="";$ubicacion="";$razon_social="";$rfc_institucion="";$pais="";$pais_descr="";$saldo=0;$tipo_moneda="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_inversiones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_inversiones VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_inversion','$tipo_inver_descr','$bancaria','$bancaria_descr','$fondo','$fondo_descr','$org_privada','$org_descr','$monedas','$monedas_descr','$seguros','$seguros_descr','$valor_bursatil','$valor_descr','$afores','$afores_descr','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$num_cta','$ubicacion','$razon_social','$rfc_institucion','$pais','$pais_descr',$saldo,'$tipo_moneda','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_inversiones SET movimiento='$movimiento',declarante='$declarante',tipo_inversion='$tipo_inversion',tipo_inver_descr='$tipo_inver_descr',bancaria='$bancaria',bancaria_descr='$bancaria_descr',fondo='$fondo',fondo_descr='$fondo_descr',org_privada='$org_privada',org_descr='$org_descr',monedas='$monedas',monedas_descr='$monedas_descr',seguros='$seguros',seguros_descr='$seguros_descr',valor_bursatil='$valor_bursatil',valor_descr='$valor_descr',afores='$afores',afores_descr='$afores_descr',titular='$titular',titular_descr='$titular_descr',tercero='$tercero',tercero_descr='$tercero_descr',nombre_tercero='$nombre_tercero',rfc_tercero='$rfc_tercero',num_cta='$num_cta',ubicacion='$ubicacion',razon_social='$razon_social',rfc_institucion='$rfc_institucion',pais='$pais',pais_descr='$pais_descr',saldo=$saldo,tipo_moneda='$tipo_moneda',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_inversiones VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_inversion','$tipo_inver_descr','$bancaria','$bancaria_descr','$fondo','$fondo_descr','$org_privada','$org_descr','$monedas','$monedas_descr','$seguros','$seguros_descr','$valor_bursatil','$valor_descr','$afores','$afores_descr','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$num_cta','$ubicacion','$razon_social','$rfc_institucion','$pais','$pais_descr',$saldo,'$tipo_moneda','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}
if(isset($_POST["form14"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante="";
	$tipo_adeudo=escape($_POST["tipo_adeudo"]);
	$tipo_adeudo_descr=escape(get_descr_lista($conn,$tipo_adeudo,"descr","qsy_listas_valores","Tipo_Adeudo"));
	$otro_adeudo=escape($_POST["otro_adeudo"]);
	$titular=escape($_POST["titular"]);
	$titular_descr=escape(get_descr_lista($conn,$titular,"descr","qsy_listas_valores","Titular"));
	$tercero=escape($_POST["tercero"]);
	$tercero_descr=escape(get_descr_lista($conn,$tercero,"descr","qsy_listas_valores","Tercero"));
	$nombre_tercero=escape($_POST["nombre_tercero"]);
	$rfc_tercero=escape($_POST["rfc_tercero"]);
	$num_cta=escape($_POST["num_cta"]);
	$fecha_adquisicion=format_date($_POST["fecha_adquisicion"]);
	$monto_original=str_replace(",","",escape($_POST["monto_original"]));
		if($monto_original=='')$monto_original=0;
	$tipo_moneda=escape($_POST["tipo_moneda"]);
	$saldo=str_replace(",","",escape($_POST["saldo"]));
		if($saldo=='')$saldo=0;
	$otorgante=escape($_POST["otorgante"]);
	$otorgante_descr=escape(get_descr_lista($conn,$otorgante,"descr","qsy_listas_valores","Otorgante"));
	$razon_social=escape($_POST["razon_social"]);
	$rfc_institucion=escape($_POST["rfc_institucion"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$pais=escape($_POST["pais"]);
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($tipo_adeudo!="" && $titular!="" && $num_cta!="" && $fecha_adquisicion!="" && $monto_original!="" && $tipo_moneda!="" && $saldo!="" && $otorgante!="" && $ubicacion!="" && $pais!="")$estatus="C";
		else $estatus="A";
	}
	else{ 
		$estatus="C";
		$tipo_adeudo="";$tipo_adeudo_descr="";$otro_adeudo="";$titular="";$titular_descr="";$tercero="";$tercero_descr="";$nombre_tercero="";$rfc_tercero="";$num_cta="";$fecha_adquisicion="null";$monto_original=0;$tipo_moneda="";$saldo=0;$otorgante="";$otorgante_descr="";$razon_social="";$rfc_institucion="";$ubicacion="";$pais="";$pais_descr="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_adeudos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_adeudos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_adeudo','$tipo_adeudo_descr','$otro_adeudo','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$num_cta',$fecha_adquisicion,$monto_original,'$tipo_moneda',$saldo,'$otorgante','$otorgante_descr','$razon_social','$rfc_institucion','$ubicacion','$pais','$pais_descr','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_adeudos SET movimiento='$movimiento',declarante='$declarante',tipo_adeudo='$tipo_adeudo',tipo_adeudo_descr='$tipo_adeudo_descr',otro_adeudo='$otro_adeudo',titular='$titular',titular_descr='$titular_descr',tercero='$tercero',tercero_descr='$tercero_descr',nombre_tercero='$nombre_tercero',rfc_tercero='$rfc_tercero',num_cta='$num_cta',fecha_adquisicion=$fecha_adquisicion,monto_original=$monto_original,tipo_moneda='$tipo_moneda',saldo=$saldo,otorgante='$otorgante',otorgante_descr='$otorgante_descr',razon_social='$razon_social',rfc_institucion='$rfc_institucion',ubicacion='$ubicacion',pais='$pais',pais_descr='$pais_descr',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_adeudos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_adeudo','$tipo_adeudo_descr','$otro_adeudo','$titular','$titular_descr','$tercero','$tercero_descr','$nombre_tercero','$rfc_tercero','$num_cta',$fecha_adquisicion,$monto_original,'$tipo_moneda',$saldo,'$otorgante','$otorgante_descr','$razon_social','$rfc_institucion','$ubicacion','$pais','$pais_descr','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}
if(isset($_POST["form15"])){
	//print_r($_POST);
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante="";
	$tipo_comodato=escape($_POST["tipo_comodato"]);
	$tipo_inmueble=escape($_POST["tipo_inmueble"]);
	$codigopostal=escape($_POST["cp"]);
	$calle=escape($_POST["calle"]);
	$num_exterior=escape($_POST["noexterior"]);
	$num_interior=escape($_POST["nointerior"]);
	$colonia="";
	$colonia_descr=escape($_POST["colonia"]);
	$municipio="";
	$municipio_descr="";
	if($tipo_comodato=='V'){
		if(isset($_POST["ubicacion2"]))
			$ubicacion=escape($_POST["ubicacion2"]);
		else
			$ubicacion="";
		$otro_inmueble="";
		$otro_vehiculo=escape($_POST["otro"]);
		$estado=escape($_POST["estado3"]);
		$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
		$pais=escape($_POST["pais2"]);
	}
	else{
		if(isset($_POST["ubicacion"]))
			$ubicacion=escape($_POST["ubicacion"]);
		else
			$ubicacion="";
		$otro_inmueble=escape($_POST["otro"]);
		$otro_vehiculo="";
		$estado="";
		$estado_descr=escape($_POST["estado"]);
		$pais=escape($_POST["pais"]);
		if($_POST["estado2"]!=""){
			$estado="";
			$estado_descr=escape($_POST["estado2"]);
		}
		else{
			$estado=escape($_POST["estado"]);
			$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
		}
		if(isset($_POST["municipio"])){
			$municipio=escape($_POST["municipio"]);
			$municipio_descr=escape(get_municipio($conn,$municipio,$estado));
		}
		else{
			$municipio="";
			$municipio_descr="";		
		}
		if(isset($_POST["colonia2"])){
			$colonia=escape($_POST["colonia2"]);
			$colonia_descr=escape(get_colonia($conn,$colonia,$codigopostal));
		}
		else{
			$colonia="";
			$colonia_descr=escape($_POST["colonia"]);
		}
	}
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$tipo_vehiculo=escape($_POST["tipo_vehiculo"]);
	$marca=escape($_POST["marca"]);
	$modelo=escape($_POST["modelo"]);
	$anio=escape($_POST["anio"]);
		if($anio=='')$anio="null";
	$serie=escape($_POST["serie"]);
	$dueno=escape($_POST["dueno"]);
	$dueno_descr=escape(get_descr_lista($conn,$dueno,"descr","qsy_listas_valores","Dueno"));
	$nombre_dueno=escape($_POST["nombre_dueno"]);
	$rfc_dueno=escape($_POST["rfc_dueno"]);
	$relacion=escape($_POST["relacion"]);
	$relacion_descr=escape(get_descr_lista($conn,$relacion,"descr","qsy_listas_valores","Relacion"));
	$otra_relacion=escape($_POST["otra_relacion"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($tipo_comodato=="I"){
			if($tipo_inmueble!="" && $ubicacion!="" && $pais!=""){
				if(($ubicacion=="M" && $calle!="" && $num_exterior!="" && $colonia_descr!="" && $municipio_descr!="" && $estado_descr!="" && $codigopostal!="") || ($ubicacion!="M"))$estatus="C";
				else $estatus="A";
			}
			else $estatus="A";
		}
		else if($tipo_comodato=="V"){
			if($tipo_vehiculo!="" && $marca!="" && $modelo!="" && $anio!="" && $serie!="" && $ubicacion!="" && $pais!="" && $dueno!="" && $nombre_dueno!="" && $rfc_dueno!="" && $relacion!=""){
				if(($ubicacion=="M" && $estado!="") || $ubicacion!="M")$estatus="C";
				else $estatus="A";
			}
			else $estatus="A";
		} 
		else $estatus="A";
	}
	else {
		$estatus="C";
		$tipo_comodato="";$tipo_inmueble="";$otro_inmueble="";$ubicacion="";$calle="";$num_exterior="";$num_interior="";$colonia="";$colonia_descr="";$municipio="";$municipio_descr="";$estado="";$estado_descr="";$pais="";$pais_descr="";$codigopostal="";$tipo_vehiculo="";$otro_vehiculo="";$marca="";$modelo="";$anio="null";$serie="";$dueno="";$dueno_descr="";$nombre_dueno="";$rfc_dueno="";$relacion="";$relacion_descr="";$otra_relacion="";
	}

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_comodatos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_comodatos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_comodato','$tipo_inmueble','$otro_inmueble','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$tipo_vehiculo','$otro_vehiculo','$marca','$modelo',$anio,'$serie','$dueno','$dueno_descr','$nombre_dueno','$rfc_dueno','$relacion','$relacion_descr','$otra_relacion','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_comodatos SET movimiento='$movimiento',declarante='$declarante',tipo_comodato='$tipo_comodato',tipo_inmueble='$tipo_inmueble',otro_inmueble='$otro_inmueble',ubicacion='$ubicacion',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',colonia_descr='$colonia_descr',municipio='$municipio',municipio_descr='$municipio_descr',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',codigopostal='$codigopostal',tipo_vehiculo='$tipo_vehiculo',otro_vehiculo='$otro_vehiculo',marca='$marca',modelo='$modelo',anio=$anio,serie='$serie',dueno='$dueno',dueno_descr='$dueno_descr',nombre_dueno='$nombre_dueno',rfc_dueno='$rfc_dueno',relacion='$relacion',relacion_descr='$relacion_descr',otra_relacion='$otra_relacion',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_comodatos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_comodato','$tipo_inmueble','$otro_inmueble','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal','$tipo_vehiculo','$otro_vehiculo','$marca','$modelo',$anio,'$serie','$dueno','$dueno_descr','$nombre_dueno','$rfc_dueno','$relacion','$relacion_descr','$otra_relacion','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["formi1"])){
	//print_r($_POST);
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante=escape($_POST["declarante"]);
	$nombre_empresa=escape($_POST["nombre_empresa"]);
	$rfc_empresa=escape($_POST["rfc_empresa"]);
	$pct_participacion=escape($_POST["pct_participacion"]);
		if($pct_participacion=='')$pct_participacion=0;
	$tipo_participacion=escape($_POST["tipo_participacion"]);
	$tipo_part_descr=escape(get_descr_lista($conn,$tipo_participacion,"descr","qsy_listas_valores","Tipo_Participacion"));
	$otra_participacion=escape($_POST["otra_participacion"]);
	$remuneracion=escape($_POST["remuneracion"]);
	$monto_mensual=str_replace(",","",escape($_POST["monto_mensual"]));
		if($monto_mensual=='')$monto_mensual=0;
	$ubicacion=escape($_POST["ubicacion"]);
	$estado=escape($_POST["estado"]);
	$pais=escape($_POST["pais"]);
	$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$sector=escape($_POST["sector-pert"]);
	//$sector_descr=htmlspecialchars($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($declarante!="" && $nombre_empresa!="" && $rfc_empresa!="" && $pct_participacion!="" && $tipo_participacion!="" && $remuneracion!="" && ($remuneracion=="N" || $monto_mensual!="") && $ubicacion!="" && ($pais!="" || $estado!="") && $sector!="")$estatus="C";
		else $estatus="A";
	}
	else{
		$estatus="C";
		$declarante="";$nombre_empresa="";$rfc_empresa="";$pct_participacion=0;$tipo_participacion="";$tipo_part_descr="";$otra_participacion="";$remuneracion="";$monto_mensual=0;$ubicacion="";$estado="";$estado_descr="";$pais="";$pais_descr="";$sector="";$sector_descr="";$otro_sector="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_participa_empresas WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_participa_empresas VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$nombre_empresa','$rfc_empresa',$pct_participacion,'$tipo_participacion','$tipo_part_descr','$otra_participacion','$remuneracion',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$sector','$sector_descr','$otro_sector','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_participa_empresas SET movimiento='$movimiento',declarante='$declarante',nombre_empresa='$nombre_empresa',rfc_empresa='$rfc_empresa',pct_participacion=$pct_participacion,tipo_participacion='$tipo_participacion',tipo_part_descr='$tipo_part_descr',otra_participacion='$otra_participacion',remuneracion='$remuneracion',monto_mensual=$monto_mensual,ubicacion='$ubicacion',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_participa_empresas VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$nombre_empresa','$rfc_empresa',$pct_participacion,'$tipo_participacion','$tipo_part_descr','$otra_participacion','$remuneracion',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$sector','$sector_descr','$otro_sector','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["formi2"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante=escape($_POST["declarante"]);
	$tipo_institucion=escape($_POST["tipo_institucion"]);
	$otra_institucion=escape($_POST["otra_institucion"]);
	$nombre_inst=escape($_POST["nombre_inst"]);
	$rfc_inst=escape($_POST["rfc_inst"]);
	$puesto_id=0;
	$puesto_descr=escape($_POST["puesto_descr"]);
	$fecha_inicio=format_date($_POST["fecha_inicio"]);
	$remuneracion=escape($_POST["remuneracion"]);
	$monto_mensual=str_replace(",","",escape($_POST["monto_mensual"]));
		if($monto_mensual=='')$monto_mensual=0;
	$ubicacion=escape($_POST["ubicacion"]);
	$estado=escape($_POST["estado"]);
	$pais=escape($_POST["pais"]);
	$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($declarante!="" && $tipo_institucion!="" && $nombre_inst!="" && $rfc_inst!="" && $puesto_descr!="" &&  $fecha_inicio!="" && $remuneracion!="" && ($remuneracion=="N" || $monto_mensual!="") && $ubicacion!="" && ($pais!="" || $estado!=""))$estatus="C";
		else $estatus="A";
	}
	else{
		$estatus="C";
		$declarante="";$tipo_institucion="";$otra_institucion="";$nombre_inst="";$rfc_inst="";$puesto_id=0;$puesto_descr="";$fecha_inicio="null";$remuneracion="";$monto_mensual=0;$ubicacion="";$estado="";$estado_descr="";$pais="";$pais_descr="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_decisiones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_decisiones VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_institucion','$otra_institucion','$nombre_inst','$rfc_inst',$puesto_id,'$puesto_descr',$fecha_inicio,'$remuneracion',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_decisiones SET movimiento='$movimiento',declarante='$declarante',tipo_institucion='$tipo_institucion',otra_institucion='$otra_institucion',nombre_inst='$nombre_inst',rfc_inst='$rfc_inst',puesto_id=$puesto_id,puesto_descr='$puesto_descr',fecha_inicio=$fecha_inicio,remuneracion='$remuneracion',monto_mensual=$monto_mensual,ubicacion='$ubicacion',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_decisiones VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_institucion','$otra_institucion','$nombre_inst','$rfc_inst',$puesto_id,'$puesto_descr',$fecha_inicio,'$remuneracion',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["formi3"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$beneficiario=escape($_POST["beneficiario"]);
	$beneficiario_descr=escape(get_descr_lista($conn,$beneficiario,"descr","qsy_listas_valores","Beneficiario"));
	$otro_beneficiario=escape($_POST["otro_beneficiario"]);
	$nombre_prog=escape($_POST["nombre_prog"]);
	$instit_otorgante=escape($_POST["instit_otorgante"]);
	$orden_id=escape($_POST["orden"]);
	$orden_descr=escape(get_descr_lista($conn,$orden_id,"descr","qsy_listas_valores","Orden_ID"));
	$tipo_apoyo=escape($_POST["tipo_apoyo"]);
	$otro_apoyo=escape($_POST["otro_apoyo"]);
	$forma_recep=escape($_POST["forma_recep"]);
	$forma_descr=escape(get_descr_lista($conn,$forma_recep,"descr","qsy_listas_valores","Forma_Recep"));
	$monto_mensual=str_replace(",","",escape($_POST["monto_mensual"]));
		if($monto_mensual=='')$monto_mensual=0;
	$apoyo_descr=escape($_POST["apoyo_descr"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($beneficiario!="" && $nombre_prog!="" && $instit_otorgante!="" && $orden_id!="" && $tipo_apoyo!="" &&  $forma_recep!="" && $monto_mensual!="" && ($forma_recep=="M" || $apoyo_descr!=""))$estatus="C";
		else $estatus="A";
	}
	else {
		$estatus="C";
		$beneficiario="";$beneficiario_descr="";$otro_beneficiario="";$nombre_prog="";$instit_otorgante="";$orden_id="";$orden_descr="";$tipo_apoyo="";$otro_apoyo="";$forma_recep="";$forma_descr="";$monto_mensual=0;$apoyo_descr="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_decisiones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_apoyos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$beneficiario','$beneficiario_descr','$otro_beneficiario','$nombre_prog','$instit_otorgante','$orden_id','$orden_descr','$tipo_apoyo','$otro_apoyo','$forma_recep','$forma_descr',$monto_mensual,'$apoyo_descr','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_apoyos SET movimiento='$movimiento',beneficiario='$beneficiario',beneficiario_descr='$beneficiario_descr',otro_beneficiario='$otro_beneficiario',nombre_prog='$nombre_prog',instit_otorgante='$instit_otorgante',orden_id='$orden_id',orden_descr='$orden_descr',tipo_apoyo='$tipo_apoyo',otro_apoyo='$otro_apoyo',forma_recep='$forma_recep',forma_descr='$forma_descr',monto_mensual=$monto_mensual,apoyo_descr='$apoyo_descr',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_apoyos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$beneficiario','$beneficiario_descr','$otro_beneficiario','$nombre_prog','$instit_otorgante','$orden_id','$orden_descr','$tipo_apoyo','$otro_apoyo','$forma_recep','$forma_descr',$monto_mensual,'$apoyo_descr','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["formi4"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante=escape($_POST["declarante"]);
	$tipo_repres=escape($_POST["tipo_repres"]);
	$tipo_descr=escape(get_descr_lista($conn,$tipo_repres,"descr","qsy_listas_valores","Tipo_Repres"));
	$fecha_inicio=format_date($_POST["fecha_inicio"]);
	$representa=escape($_POST["representa"]);
	$nombre_repre=escape($_POST["nombre_repre"]);
	$rfc_repre=escape($_POST["rfc_repre"]);
	$remuneracion=escape($_POST["remuneracion"]);
	$monto_mensual=str_replace(",","",escape($_POST["monto_mensual"]));
		if($monto_mensual=='')$monto_mensual=0;
	$ubicacion=escape($_POST["ubicacion"]);
	$estado=escape($_POST["estado"]);
	$pais=escape($_POST["pais"]);
	$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$sector=escape($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($declarante!="" && $tipo_repres!="" && $fecha_inicio!="" && $representa!="" && $nombre_repre!="" && $rfc_repre!="" &&  $remuneracion!="" && ($remuneracion=="N" || $monto_mensual!="") && $ubicacion!="" && ($pais!="" || $estado!="") && $sector!="")$estatus="C";
		else $estatus="A";
	}
	else{
		$estatus="C";
		$declarante="";$tipo_repres="";$tipo_descr="";$fecha_inicio="null";$representa="";$nombre_repre="";$rfc_repre="";$remuneracion="";$monto_mensual=0;$ubicacion="";$estado="";$estado_descr="";$pais="";$pais_descr="";$sector="";$sector_descr="";$otro_sector="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_representaciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_representaciones VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_repres','$tipo_descr',$fecha_inicio,'$representa','$nombre_repre','$rfc_repre','$remuneracion',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$sector','$sector_descr','$otro_sector','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_representaciones SET movimiento='$movimiento',declarante='$declarante',tipo_repres='$tipo_repres',tipo_descr='$tipo_descr',fecha_inicio=$fecha_inicio,representa='$representa',nombre_repre='$nombre_repre',rfc_repre='$rfc_repre',remuneracion='$remuneracion',monto_mensual=$monto_mensual,ubicacion='$ubicacion',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_representaciones VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_repres','$tipo_descr',$fecha_inicio,'$representa','$nombre_repre','$rfc_repre','$remuneracion',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$sector','$sector_descr','$otro_sector','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}

if(isset($_POST["formi5"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$actividad=escape($_POST["actividad"]);
	$declarante=escape($_POST["declarante"]);
	$nombre_empresa=escape($_POST["nombre_empresa"]);
	$rfc_empresa=escape($_POST["rfc_empresa"]);
	$cliente=escape($_POST["cliente"]);
	$cliente_descr=escape(get_descr_lista($conn,$cliente,"descr","qsy_listas_valores","Cliente"));
	$nombre_cliente=escape($_POST["nombre_cliente"]);
	$rfc_cliente=escape($_POST["rfc_cliente"]);
	$sector=escape($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$monto_mensual=str_replace(",","",escape($_POST["monto_mensual"]));
		if($monto_mensual=='')$monto_mensual=0;
	$ubicacion=escape($_POST["ubicacion"]);
	$estado=escape($_POST["estado"]);
	$pais=escape($_POST["pais"]);
	$estado_descr=escape(get_descr($conn,$estado,"descr","qsy_estados","estado"));
	$pais_descr=escape(get_descr($conn,$pais,"descr","qsy_paises","pais"));
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($declarante!="" && $nombre_empresa!="" && $rfc_empresa!="" && $cliente!="" && $nombre_cliente!="" &&  $rfc_cliente!="" && $sector!="" && $monto_mensual!="" && $ubicacion!="" && ($pais!="" || $estado!=""))$estatus="C";
		else $estatus="A";
	}
	else {
		$estatus="C";
		$actividad="";$declarante="";$nombre_empresa="";$rfc_empresa="";$cliente="";$cliente_descr="";$nombre_cliente="";$rfc_cliente="";$sector="";$sector_descr="";$otro_sector="";$monto_mensual=0;$ubicacion="";$estado="";$estado_descr="";$pais="";$pais_descr="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_clientes WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_clientes VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$actividad','$declarante','$nombre_empresa','$rfc_empresa','$cliente','$cliente_descr','$nombre_cliente','$rfc_cliente','$sector','$sector_descr','$otro_sector',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_clientes SET movimiento='$movimiento',actividad='$actividad',declarante='$declarante',nombre_empresa='$nombre_empresa',rfc_empresa='$rfc_empresa',cliente='$cliente',cliente_descr='$cliente_descr',nombre_cliente='$nombre_cliente',rfc_cliente='$rfc_cliente',sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',monto_mensual=$monto_mensual,ubicacion='$ubicacion',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr='$pais_descr',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_clientes VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$actividad','$declarante','$nombre_empresa','$rfc_empresa','$cliente','$cliente_descr','$nombre_cliente','$rfc_cliente','$sector','$sector_descr','$otro_sector',$monto_mensual,'$ubicacion','$estado','$estado_descr','$pais','$pais_descr','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}
if(isset($_POST["formi6"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$tipo_beneficio=escape($_POST["tipo_beneficio"]);
	$otro_beneficio=escape($_POST["otro_beneficio"]);
	$beneficiario=escape($_POST["beneficiario"]);
	$beneficiario_descr=escape(get_descr_lista($conn,$beneficiario,"descr","qsy_listas_valores","Beneficiario"));
	$otro_beneficiario=escape($_POST["otro_beneficiario"]);
	$otorgante=escape($_POST["otorgante"]);
	$otorgante_descr=escape(get_descr_lista($conn,$otorgante,"descr","qsy_listas_valores","Otorgante"));
	$nombre_otorga=escape($_POST["nombre_otorga"]);
	$rfc_otorga=escape($_POST["rfc_otorga"]);
	$forma_recep=escape($_POST["forma_recep"]);
	$forma_descr=escape(get_descr_lista($conn,$forma_recep,"descr","qsy_listas_valores","Forma_Recep"));
	$beneficio_descr=escape($_POST["beneficio_descr"]);
	$monto_mensual=str_replace(",","",escape($_POST["monto_mensual"]));
		if($monto_mensual=='')$monto_mensual=0;
	$tipo_moneda=escape($_POST["tipo_moneda"]);
	$sector=escape($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($tipo_beneficio!="" && $beneficiario!="" && $otorgante!="" && $nombre_otorga!="" && $rfc_otorga!="" && $forma_recep!="" && ($forma_recep=="M" || $beneficio_descr!="") && $sector!="" && $monto_mensual!="" && $tipo_moneda!="")$estatus="C";
		else $estatus="A";
	}
	else {
		$estatus="C";
		$tipo_beneficio="";$otro_beneficio="";$beneficiario="";$beneficiario_descr="";$otro_beneficiario="";$otorgante="";$otorgante_descr="";$nombre_otorga="";$rfc_otorga="";$forma_recep="";$forma_descr="";$beneficio_descr="";$monto_mensual=0;$tipo_moneda="";$sector="";$sector_descr="";$otro_sector="";
	}
	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_beneficios_privados WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_beneficios_privados VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$tipo_beneficio','$otro_beneficio','$beneficiario','$beneficiario_descr','$otro_beneficiario','$otorgante','$otorgante_descr','$nombre_otorga','$rfc_otorga','$forma_recep','$forma_descr','$beneficio_descr',$monto_mensual,'$tipo_moneda','$sector','$sector_descr','$otro_sector','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_beneficios_privados SET movimiento='$movimiento',tipo_beneficio='$tipo_beneficio',otro_beneficio='$otro_beneficio',beneficiario='$beneficiario',beneficiario_descr='$beneficiario_descr',otro_beneficiario='$otro_beneficiario',otorgante='$otorgante',otorgante_descr='$otorgante_descr',nombre_otorga='$nombre_otorga',rfc_otorga='$rfc_otorga',forma_recep='$forma_recep',forma_descr='$forma_descr',beneficio_descr='$beneficio_descr',monto_mensual=$monto_mensual,tipo_moneda='$tipo_moneda',sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_beneficios_privados VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$tipo_beneficio','$otro_beneficio','$beneficiario','$beneficiario_descr','$otro_beneficiario','$otorgante','$otorgante_descr','$nombre_otorga','$rfc_otorga','$forma_recep','$forma_descr','$beneficio_descr',$monto_mensual,'$tipo_moneda','$sector','$sector_descr','$otro_sector','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	print_r("Los datos se han guardado.");
}
if(isset($_POST["formi7"])){
	//print_r($_POST);die;
	$rfc=escape($_POST["rfc"]);
	$ejercicio=escape($_POST["ejercicio"]);
	$dec=escape($_POST["tipo-declaracion"]);
	$tipo_decl=escape($_POST["declaracion"]);
	$total=escape($_POST["total_reg"]);
	$total2=escape($_POST["total_reg2"]);
	$secuencia=escape($_POST["secuencia"]);
	$movimiento=escape($_POST["movimiento"]);
	if($movimiento=="")$movimiento="A";
	$declarante=escape($_POST["declarante"]);
	$tipo_fideicomiso=escape($_POST["tipo_fideicomiso"]);
	$tipo_descr=escape(get_descr_lista($conn,$tipo_fideicomiso,"descr","qsy_listas_valores","Tipo_Fideicomiso"));
	$como_participa=escape($_POST["como_participa"]);
	$participa_descr=escape(get_descr_lista($conn,$como_participa,"descr","qsy_listas_valores","Como_Participa"));
	$rfc_fideicomiso=escape($_POST["rfc_fideicomiso"]);
	$fideicomitente=escape($_POST["fideicomitente"]);
	$fideicomitente_descr=escape(get_descr_lista($conn,$fideicomitente,"descr","qsy_listas_valores","Fideicomitente"));
	$nom_fideicomitente=escape($_POST["nom_fideicomitente"]);
	$rfc_fideicomitente=escape($_POST["rfc_fideicomitente"]);
	$nom_fiduciario=escape($_POST["nom_fiduciario"]);
	$rfc_fiduciario=escape($_POST["rfc_fiduciario"]);
	$fideicomisario=escape($_POST["fideicomisario"]);
	$fideicomisario_descr=escape(get_descr_lista($conn,$fideicomisario,"descr","qsy_listas_valores","Fideicomisario"));
	$nom_fideicomisario=escape($_POST["nom_fideicomisario"]);
	$rfc_fideicomisario=escape($_POST["rfc_fideicomisario"]);
	$sector=escape($_POST["sector-pert"]);
	$sector_descr=escape(get_descr_lista($conn,$sector,"descr","qsy_listas_valores","Sector"));
	$otro_sector=escape($_POST["otro_sector"]);
	$ubicacion=escape($_POST["ubicacion"]);
	$observaciones=escape($_POST["observaciones"]);

	if($movimiento!="N"){
		if($declarante!="" && $tipo_fideicomiso!="" && $como_participa!="" && $rfc_fideicomiso!="" && $sector!="" && $ubicacion!=""){
			if($como_participa=="A" && $fideicomitente!="" && $nom_fideicomitente!="" && $rfc_fideicomitente!="")
				$estatus="C";
			else if($como_participa=="B" && $nom_fiduciario!="" && $rfc_fiduciario!="")
				$estatus="C";
			else if($como_participa=="C" && $fideicomisario!="" && $nom_fideicomisario!="" && $rfc_fideicomisario!="")
				$estatus="C";
			else if($como_participa=="D")$estatus="C";
			else $estatus="A";
		}
		else $estatus="A";
	}
	else {
		$estatus="C";
		$declarante="";$tipo_fideicomiso="";$tipo_descr="";$como_participa="";$participa_descr="";$rfc_fideicomiso="";$fideicomitente="";$fideicomitente_descr="";$nom_fideicomitente="";$rfc_fideicomitente="";$nom_fiduciario="";$rfc_fiduciario="";$fideicomisario="";$fideicomisario_descr="";$nom_fideicomisario="";$rfc_fideicomisario="";$sector="";$sector_descr="";$otro_sector="";$ubicacion="";
	}

	$sql="SELECT rfc,ejercicio,declaracion,tipo_decl FROM qsy_fideicomisos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		//$secuencia=sizeof($val)+1;

		//print_r($total2." ".$total." ".$secuencia);
		if($secuencia > $total2){
		$total2++;
		$sql="INSERT INTO qsy_fideicomisos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$total2,'$movimiento','$declarante','$tipo_fideicomiso','$tipo_descr','$como_participa','$participa_descr','$rfc_fideicomiso','$fideicomitente','$fideicomitente_descr','$nom_fideicomitente','$rfc_fideicomitente','$nom_fiduciario','$rfc_fiduciario','$fideicomisario','$fideicomisario_descr','$nom_fideicomisario','$rfc_fideicomisario','$sector','$sector_descr','$otro_sector','$ubicacion','$observaciones','$estatus')";
		}
		else{
			$sql="UPDATE qsy_fideicomisos SET movimiento='$movimiento',declarante='$declarante',tipo_fideicomiso='$tipo_fideicomiso',tipo_descr='$tipo_descr',como_participa='$como_participa',participa_descr='$participa_descr',rfc_fideicomiso='$rfc_fideicomiso',fideicomitente='$fideicomitente',fideicomitente_descr='$fideicomitente_descr',nom_fideicomitente='$nom_fideicomitente',rfc_fideicomitente='$rfc_fideicomitente',nom_fiduciario='$nom_fiduciario',rfc_fiduciario='$rfc_fiduciario',fideicomisario='$fideicomisario',fideicomisario_descr='$fideicomisario_descr',nom_fideicomisario='$nom_fideicomisario',rfc_fideicomisario='$rfc_fideicomisario',sector='$sector',sector_descr='$sector_descr',otro_sector='$otro_sector',ubicacion='$ubicacion',observaciones='$observaciones',estatus='$estatus' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' AND secuencia=$secuencia";
		}
		//print_r($sql);
		$result=pg_query($conn,$sql);
	}
	else{
		$sql="INSERT INTO qsy_fideicomisos VALUES ('$rfc',$ejercicio,'$dec','$tipo_decl',$secuencia,'$movimiento','$declarante','$tipo_fideicomiso','$tipo_descr','$como_participa','$participa_descr','$rfc_fideicomiso','$fideicomitente','$fideicomitente_descr','$nom_fideicomitente','$rfc_fideicomitente','$nom_fiduciario','$rfc_fiduciario','$fideicomisario','$fideicomisario_descr','$nom_fideicomisario','$rfc_fideicomisario','$sector','$sector_descr','$otro_sector','$ubicacion','$observaciones','$estatus')";
		//print_r($sql);
		$result=pg_query($conn,$sql);
	//	print_r($sql);
	//	die;
	}
	
	print_r("Los datos se han guardado.");
}

/* 31/08/2020 DMQ-Qualsys Cambio por adecuación de puestos según fecha efectiva. */
if(isset($_POST["comprobacion"])){
	$rfc=htmlspecialchars($_POST["rfc"]);
	$ejercicio=htmlspecialchars($_POST["ejercicio"]);
	$dec=htmlspecialchars($_POST["tipo-declaracion"]);
	$tipo_decl=htmlspecialchars($_POST["declaracion"]);
	$declara_completo=htmlspecialchars($_POST["declara_completo"]);
	//si la fecha límite ya pasó, guardar en qsy_declaraciones como 'X', si está en tiempo, como 'P'
	$flag="N";
	if($dec=="P"){
		$sql="SELECT estatus FROM qsy_datos_generales WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_direcciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_escolaridades WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_comision_actual WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_experiencia WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		//
		$sql="SELECT estatus FROM qsy_ingresos_netos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		if($tipo_decl=="I" || $tipo_decl=="C"){
			$sql="SELECT estatus FROM qsy_ingresos_anio_anterior WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
		}

		/* 21-08-2020 DMQ-Qualsys Adaptación por las nuevas opciones del catálogo de puestos. */
		if($declara_completo=="C"){
		/* Fin de actualización */
			$sql="SELECT estatus FROM qsy_parejas WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
			$sql="SELECT estatus FROM qsy_dependientes WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
			$sql="SELECT estatus FROM qsy_inmuebles WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
			$sql="SELECT estatus FROM qsy_vehiculos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
			$sql="SELECT estatus FROM qsy_muebles WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
			$sql="SELECT estatus FROM qsy_inversiones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
			$sql="SELECT estatus FROM qsy_adeudos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
			$sql="SELECT estatus FROM qsy_comodatos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_row($result);
			if($val){if($val[0]!="C")$flag="S";}
			else $flag="S";
		}
	}
	if($dec=="I"){
		$sql="SELECT estatus FROM qsy_participa_empresas WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_decisiones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_apoyos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_representaciones WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_clientes WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_beneficios_privados WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
		$sql="SELECT estatus FROM qsy_fideicomisos WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl' order by estatus";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_row($result);
		if($val){if($val[0]!="C")$flag="S";}
		else $flag="S";
	}
	echo $flag;
}
/* Fin de actualización. */


if(isset($_POST["envio_declaracion"])){
	$rfc=htmlspecialchars($_POST["rfc"]);
	$ejercicio=htmlspecialchars($_POST["ejercicio"]);
	$dec=htmlspecialchars($_POST["tipo-declaracion"]);
	$tipo_decl=htmlspecialchars($_POST["declaracion"]);
	$fecha=date("Ymd");
	$folio=$dec.$tipo_decl.$rfc.$fecha;
	//print_r($_POST);die;
	if($tipo_decl=="I"){
		$sql="SELECT fecha_contrata FROM qsy_rh_empleados WHERE rfc='$rfc'";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_assoc($result);
		if($val){
			$fecha=date("Y-m-d");
			$fecha_actual = $fecha;
			$fecha_contrata = $val["fecha_contrata"];
			//$intervalo = date_diff($fecha_contrata, $fecha_actual);
			if($fecha_contrata!=""){
				$fecha_limite_c = strtotime ('+60 day',strtotime($fecha_contrata)) ;
				$fecha_limite_c = date ( 'Y-m-d' , $fecha_limite_c );
			}
			else{
				$fecha_limite_c ="";
			}
			if($fecha_contrata <= $fecha_actual && $fecha_actual <= $fecha_limite_c){
				$sql="UPDATE qsy_declaraciones SET estatus_decl='P',fecha_presenta='$fecha',folio_acuse='$folio' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
			}
			else{
				$sql="UPDATE qsy_declaraciones SET estatus_decl='X',fecha_presenta='$fecha',folio_acuse='$folio' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
			}
			//$mes_actual = date("m",strtotime($fecha));

			//print_r($sql);
		}
	}
	if($tipo_decl=="M"){
/*		$sql="SELECT fecha_contrata FROM qsy_rh_empleados WHERE rfc='$rfc'";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_assoc($result);
		if($val){
			$fecha_contrata = date_create($val["fecha_contrata"]);
			$anio_contrata = date("Y",strtotime($val["fecha_contrata"]));
			$anio_actual = date("Y",strtotime($fecha_actual));
*/
		$sql="SELECT fecha_contrata FROM qsy_rh_empleados WHERE rfc='$rfc'";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_assoc($result);
		if($val){
			$fecha=date("Y-m-d");
			$fecha_contrata = $val["fecha_contrata"];
			if($fecha_contrata!=""){
				$anio_c=date("Y",strtotime($fecha_contrata));
			}
			else{
				$anio_c=0;
			}
			$mes_actual=date("m");
			//print_r($mes_actual);
			$anio_actual=date("Y");
			if($mes_actual == "05" && $anio_actual>$anio_c){
				$sql="UPDATE qsy_declaraciones SET estatus_decl='P',fecha_presenta='$fecha',folio_acuse='$folio' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";				
			}
			else{
				$sql="UPDATE qsy_declaraciones SET estatus_decl='X',fecha_presenta='$fecha',folio_acuse='$folio' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
			}
			//print_r($sql);
		}
	}
/*	}*/
	if($tipo_decl=="C"){
		$sql="SELECT fecha_baja FROM qsy_rh_empleados WHERE rfc='$rfc'";
		$result=pg_query($conn,$sql);
		$val=pg_fetch_assoc($result);
		if($val){
			$fecha=date("Y-m-d");
			$fecha_actual = $fecha;
			$fecha_baja = $val["fecha_baja"];
			//$intervalo = date_diff($fecha_contrata, $fecha_actual);
			if($fecha_baja!=""){
				$fecha_limite_b = strtotime ('+60 day',strtotime($fecha_baja));
				$fecha_limite_b = date ( 'Y-m-d' , $fecha_limite_b);
			}
			else{
				$fecha_limite_b ="";
			}
			if($fecha_baja <= $fecha_actual && $fecha_actual <= $fecha_limite_b){
				$sql="UPDATE qsy_declaraciones SET estatus_decl='P',fecha_presenta='$fecha',folio_acuse='$folio' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
			}
			else{
				$sql="UPDATE qsy_declaraciones SET estatus_decl='X',fecha_presenta='$fecha',folio_acuse='$folio' WHERE rfc='$rfc' AND ejercicio=$ejercicio AND declaracion='$dec' AND tipo_decl='$tipo_decl'";
			}
			//print_r($sql);
		}
	}
	$result=pg_query($conn,$sql);

	$fechaActual = "Ciudad de México, a ".actual_date();
	$nombre=get_nombre($conn,$rfc);
	$empleo=get_empleo($conn,$rfc,$ejercicio,$tipo_decl,$dec);
	$area=get_area($conn,$rfc,$ejercicio,$tipo_decl,$dec);
	$sexo=get_sexo($conn,$rfc);
	$folio=get_folio($conn,$rfc,$ejercicio,$tipo_decl,$dec);
	$declaracion=$dec;

	$rfc = $_POST["rfc"];
	$ejercicio = $_POST["ejercicio"];
	$t_decl = '';
	$dec = '';
	$frac = '';
	$frac2 = '';
	$sx = '';
	$sx2 = '';

	if($sexo =="M")
	{
	  $sx='la';
	  $sx2 = 'adscrita';
	  $sx3 = 'designada';
	}
	else
	{ $sx = 'el';
	  $sx2 = 'adscrito';
	  $sx3 = 'designado';
	}

	if($declaracion=="P"){

	  $dec = "PATRIMONIAL";

	if($tipo_decl == "I")
	{
	  $t_decl = 'INICIAL';
	  $frac = '33 Fracción I, incisos a y b, 34 primer párrafo y 38 ';
	  $frac2 = 'fracción I, inciso b)';
	}
	elseif($tipo_decl == "M")
	{
	  $t_decl = 'MODIFICACIÓN';
	  $frac = '33 fracción II, 34 párrafo primero y 38 ';
	  $frac2 = 'fracción I';
	}

	elseif($tipo_decl == "C")
	{
	  $t_decl = 'CONCLUSIÓN';
	  $frac = '33 fracción III, 34 párrafo primero y 38';
	  $frac2 = 'fracción I';
	}}

	else{
	  $dec = "INTERESES";
	  $frac2 = 'fracción I e incisos a) y b)';
	  $frac = '35 y 38 ';

	  if($tipo_decl == "I")
	{
	  $t_decl = 'INICIAL';
	}
	elseif($tipo_decl == "M")
	{
	  $t_decl = 'MODIFICACIÓN';
	}

	elseif($tipo_decl == "C")
	{
	  $t_decl = 'CONCLUSIÓN';
	}
	}


	$dir = '../views/reportes/codigo_qr/temp/';
	  if(!file_exists($dir))
	  mkdir($dir);

	$filename = $dir.'codigoqr'.$rfc.'.png';
	$tamanio = 2;
	$level = 'M';
	$frameSize = 3;
	$contenido = 'LUGAR Y FECHA DE ACUSE: '.$fechaActual.PHP_EOL.
	             ' SERVIDOR PUBLICO: '.$nombre.PHP_EOL.
	             ' RFC: '.$rfc.PHP_EOL.
	             ' PUESTO: '.$empleo.PHP_EOL.
	             ' AREA DE ADSCRIPCIÓN: '.$area.PHP_EOL.
	             ' DECLARACIÓN: '.$dec.PHP_EOL.
	             ' TIPO DE DECLARACIÓN: '.$t_decl.PHP_EOL.
	             ' EJERCICIO: '.$ejercicio.PHP_EOL.
	             ' FOLIO: '.$folio.PHP_EOL;


	QRcode::png ($contenido, $filename, $level, $tamanio, $frameSize);

$logo1="<img src='../css/images/qsy_logo_nivel.png' style='height:70px;width: auto;'>";
$logo2="<img src='../css/images/qsy_logo_depend.png' style='height:70px;width: auto;'>";

/*28-08-2020 DMQ-Qualsys Adaptación para cambiar los textos del acuse.*/

	  $html = "<body>
            <header class='clearfix'>
              <div id='logo1'>
                $logo1
              </div>
              <div id='logo2'>
                $logo2
              </div>
            </header>
	            <p id='subtil2'>ACUSE DE RECIBO</p>
	              <main>
	                <table>

	                  <tr>
	                    
	                    <td class='cp' colspan='2'>Folio: $folio.</td><br> <br> <br> <br>
	                    <td class='cp' colspan='2'><label>".$fechaActual."</label></td>
	                  </tr> 
	                  <br> <br> <br> <br>
	                  <tr>
	                    <td class='ar' colspan='4'>";
	                      $texto=get_notificacion(7,$conn);
	                      $texto=str_replace("{sexo}", $sx, $texto);
	                      $texto=str_replace("{nombre}", $nombre, $texto);
	                      $texto=str_replace("{sexo2}", $sx2, $texto);
	                      $texto=str_replace("{puesto}", $empleo, $texto);
	                      $texto=str_replace("{area}", $area, $texto);
	                      $texto=str_replace("{frac}", $frac2, $texto);
	                      $html.="<p>$texto</p>
	                    </td>
	                  </tr> 
	                  <br> <br> <br>
	                  <tr>";
	                  if($declaracion=="P")
	                  $html.="
	                    <td class='cp' colspan=4>Declaración ".$t_decl." de Situación Patrimonial</td>";
	                  else
	                  $html.="
	                    <td class='cp' colspan=4>Declaración ".$t_decl." de Conflicto de Intereses</td>";
	                  $html.="</tr> 
	                  <br><br> <br>
	                  <tr>
	                    <td class='ar' colspan='4'>";
	                      $texto=get_notificacion(8,$conn);
	                      $texto=str_replace("{frac}", $frac, $texto);
	                      $html.="<p>$texto</p>
	                    </td>
	                  </tr>
	                  <br> <br> <br> <br> <br> <br> <br> <br> 
	                  <tr>
	                    <td class='cp' colspan='4'>
	                      <img src=".$filename." />
	                    </td>
	                  </tr> 
	                </table>
	            </main>
	    </body>";
/*Fin de actualización.*/

		$direccion = CALLE_EMPRESA." ".EXT_EMPRESA." ".COL_COMPLETE." ".MUN_COMPLETE."<br>".CP_EMPRESA." ".EST_COMPLETE;

	  $mpdf = new \Mpdf\Mpdf([]);
	  $css = file_get_contents('../views/reportes/css/style.css');
	  $mpdf->writeHtml($css,1);
	  $mpdf->writeHtml($html);
	  $mpdf->SetFooter($direccion);
	   //$mpdf->Output($folio.'.pdf','I');
	   $mpdf->Output('../views/reportes/acuses/'.$folio.'.pdf','F');

	  $sql="SELECT nombre,email_institucional,email_personal FROM qsy_rh_empleados WHERE rfc='$rfc'";
	  $result=pg_query($conn,$sql);
	  $val=pg_fetch_array($result);
	  if($val){
		$nombre=$val["nombre"];
		$correo=$val["email_personal"];
		if($correo==""){$correo=$val["email_institucional"];}
	  }
	  else{
		$nombre="Servidor Público";
		$correo="";
	  }
	  try {
	    $send_mail = new PHPMailer(TRUE);
	    $send_mail->CharSet = 'UTF-8';
	    $send_mail->setFrom(USER_MAIL, NOMBRE_SIS);
	    $send_mail->addAddress($correo, $nombre);
	    $send_mail->Subject = 'Acuse de recibo';
	    $send_mail->isHTML(TRUE);
	    $send_mail->Body = 'Hola '.$nombre.'.<br><br>
	    Tu declaración se ha enviado correctamente. Guarda tu acuse de recibo para cualquier aclaración.';
	    $send_mail->isSMTP();
	    $send_mail->Host = SMTP_MAIL;
	    $send_mail->SMTPAuth = TRUE;
	    $send_mail->SMTPSecure = 'tls';
	    $send_mail->Username = USER_MAIL;
	    $send_mail->Password = PASS_MAIL;
	    $send_mail->Port = PORT_MAIL;
	    $send_mail->AddAttachment('../views/reportes/acuses/'.$folio.'.pdf','acuse.pdf');
	    $send_mail->send();
	    $msj="Se ha enviado el acuse a tu correo electrónico.";
	  }
	  catch (Exception $e){$msj=$e->errorMessage();}
	  catch (\Exception $e){$msj=$e->getMessage();}
}

?>