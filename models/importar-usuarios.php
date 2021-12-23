<?php
	include '../vendor/autoload.php';
	include("../include/conexion.php");
    include("../include/funciones.php");
    include("../include/constantes.php");
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require('../PHPMailer/src/Exception.php');
	require('../PHPMailer/src/PHPMailer.php');
	require('../PHPMailer/src/SMTP.php');

if($_FILES["imported"]["name"] != ''){

	$allowed_extension = array('xls', 'xlsx');
	$file_array = explode(".", $_FILES["imported"]["name"]);
	$file_extension = end($file_array);
 if(in_array($file_extension, $allowed_extension)){
	print_r("<div class='forms-container'>");
 	//print_r("Excel!");
   $file_name = time() . '.' . $file_extension;
  move_uploaded_file($_FILES['imported']['tmp_name'], $file_name);
  $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

  $spreadsheet = $reader->load($file_name);

  unlink($file_name);

  $fecha=date("Y-m-d");
  $data = $spreadsheet->getActiveSheet()->toArray();
  unset($data[0]);
  $counter=0;
  foreach($data as $row){
/*	   $insert_data = array(
	    '$rfc'  => $row[0],
	    ':last_name'  => $row[1],
	    ':created_at'  => $row[2],
	    ':updated_at'  => $row[3]
	   );
*/
	$counter++;
	$rfc = escape($row[0]);
	if(strlen($rfc)!=13){
		print_r("El registro $counter no es válido. Verifica los datos.<br>");
		continue;
	}
	//if(strlen($nombre)<=30)	$nombre = escape($row[1]);
	$nombre = strlen($row[1])<=30 ? escape($row[1]) : "";
	$apellido1 = strlen($row[2])<=30 ? escape($row[2]) : "";
	$apellido2 = strlen($row[3])<=30 ? escape($row[3]) : "";
	$curp = strlen($row[4])<=18 ? escape($row[4]) : "";
	$email1 = strlen($row[5])<=50 ? pg_escape_string($row[5]) : "";
	$email2 = strlen($row[6])<=50 ? pg_escape_string($row[6]) : "";
	$tel1 = strlen($row[7])<=10 ? escape($row[7]) : "";
	$tel2 = strlen($row[8])<=10 ? escape($row[8]) : "";
	$ecivil = escape($row[9]);
	$ecivil = escape(get_valor_lista($conn,$ecivil,"valor","qsy_listas_valores","ESTADO CIVIL"));
	$pais= escape($row[10]);
	$pais_descr = strlen($row[10])<=100 ? escape($pais) : "";
	$pais = escape(get_descr($conn,$pais,"pais","qsy_paises","descr"));
	$pais = strlen($row[10])<=3 ? escape($pais) : "";
	$nacionalidad= escape($row[11]);
	$nacionalidad = escape(get_descr($conn,$nacionalidad,"nacionalidad","qsy_nacionalidades","descr"));
	$nacionalidad = strlen($row[11])<=3 ? escape($nacionalidad) : "";
	$sexo = escape($row[12]);
	if($sexo=="Hombre")$sexo="H";else $sexo="M";
	$fecha_nac = escape($row[13]);
	if($fecha_nac > "01-01-1910")$fecha_nac='';
	if($fecha_nac=="")$fecha_nac='null';
	else $fecha_nac="'".$fecha_nac."'";
	$fecha_contratacion = escape($row[14]);
	if($fecha_contratacion > "01-01-1910")$fecha_contratacion='';
	if($fecha_contratacion=="")$fecha_contratacion='null';
	else $fecha_contratacion="'".$fecha_contratacion."'";

	$codigopostal = strlen($row[15])<=5 ? escape($row[15]) : "";
    $calle = strlen($row[19])<=50 ? escape($row[19]) : "";
    $num_exterior = strlen($row[20])<=15 ? escape($row[20]) : "";
	$num_interior = strlen($row[21])<=15 ? escape($row[21]) : "";
	$estado_descr = strlen($row[16])<=50 ? escape($row[16]) : "";
	$estado = escape(get_descr($conn,$estado_descr,"estado","qsy_estados","descr"));
	$estado = strlen($estado)<=3 ? escape($estado) : "";
	$municipio_descr = strlen($row[17])<=100 ? escape($row[17]) : "";
    $municipio="";
	$colonia_descr = strlen($row[18])<=100 ? escape($row[18]) : "";
    $colonia="";

	$orden_id = escape(get_valor_lista($conn,$row[22],"valor","qsy_listas_valores","ORDEN_ID"));
	$orden_id = strlen($orden_id)<=2 ? escape($orden_id) : "";
	$ambito_id = escape(get_valor_lista($conn,$row[23],"valor","qsy_listas_valores","AMBITO_ID"));
	$ambito_id = strlen($ambito_id)<=2 ? escape($ambito_id) : "";
	$dependencia = escape(get_descr($conn,$row[24],"dependencia","qsy_dependencias","descr"));
	$dependencia = strlen($dependencia)<=3 ? escape($dependencia) : "";
	$area_adscripcion = escape(get_descr($conn,$row[25],"area_adscripcion","qsy_areas_adscripcion","descr"));
	$area_adscripcion = strlen($area_adscripcion)<=10 || $area_adscripcion!="OTRA" ? escape($area_adscripcion) : "";
	$nivel_empleo = strlen($row[26])<=10 ? escape($row[26]) : "";
	$id_puesto = escape(get_descr($conn,$row[27],"id","qsy_puestos","descr"));
    if($id_puesto=="" || $id_puesto=="OTRO" || strlen($id_puesto)>3)$id_puesto=0;
	$funcion_principal = strlen($row[28])<=100 ? escape($row[28]) : "";
	$tel_oficina = strlen($row[29])<=10 ? escape($row[29]) : "";
	$extension = strlen($row[30])<=15 ? escape($row[30]) : "";

    $ubicacion2=escape(UBI_EMPRESA);
    $calle2=escape(CALLE_EMPRESA);
    $num_exterior2=escape(EXT_EMPRESA);
    $num_interior2=escape(INT_EMPRESA);
    $colonia2=escape(COL_EMPRESA);
    $municipio2=escape(MUN_EMPRESA);
    $estado2=escape(EST_EMPRESA);
    $pais2=escape(PAIS_EMPRESA);
    $codigopostal2=escape(CP_EMPRESA);


	$query="SELECT rfc FROM qsy_rh_empleados WHERE rfc='$rfc'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		print_r("El declarante con el RFC $rfc ya existe. Verifica los datos.<br>");
	}
	else{
	$query = "INSERT INTO qsy_rh_empleados VALUES ('$rfc','$fecha','$nombre','$apellido1','$apellido2','$curp','$email1','$email2','$tel1','$tel2','$ecivil', '$pais','$nacionalidad','$sexo',$fecha_nac,'A',$fecha_contratacion)";
	//print_r($query."<br>");
	$result=pg_query($conn,$query) or die("Problema al insertar el usuario.");
	$query = "INSERT INTO qsy_rh_direcciones VALUES ('$rfc','$fecha','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal')";
	//print_r($query."<br>");
	$result=pg_query($conn,$query) or die("Problema al insertar la dirección.");
	$query = "INSERT INTO qsy_rh_empleos VALUES ('$rfc','$fecha','$orden_id','$ambito_id','$dependencia','$area_adscripcion','$nivel_empleo',$id_puesto,'$funcion_principal',$fecha_contratacion,'$tel_oficina','$extension','$ubicacion2','$calle2','$num_exterior2','$num_interior2','$colonia2','$municipio2','$estado2','$pais2','$codigopostal2')";
	//print_r($query."<br>");
	$result=pg_query($conn,$query) or die("Problema al insertar el empleo.");

	$cadena = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrtuvwxyz0123456789";
	$longitudCadena=strlen($cadena);
	$pass = "";
	$longitudPass=10;
	$pos=0;
	for($i=1 ; $i<=$longitudPass ; $i++){
		$pos= rand(0,57);
		$pass .= substr($cadena,$pos,1);
	}

	//$pass=htmlspecialchars($_POST["password"]);
	$contrasena = password_hash($pass, PASSWORD_BCRYPT);
	$usuario="excel";
	//print_r($pass."<br>");

	try {
		$send_mail = new PHPMailer(TRUE);
		$send_mail->CharSet = 'UTF-8';
		$send_mail->setFrom(USER_MAIL, NOMBRE_SIS);
		$send_mail->addAddress($email2, $nombre);
		$send_mail->Subject = 'Envío de Contraseña';
		$send_mail->isHTML(TRUE);
		$send_mail->Body = 'Hola '.$nombre.'.<br><br>
		Tu contraseña para el sistema de declaraciones es: '.$pass;
		$send_mail->isSMTP();
		$send_mail->Host = SMTP_MAIL;
		$send_mail->SMTPAuth = TRUE;
		$send_mail->SMTPSecure = 'tls';
		$send_mail->Username = USER_MAIL;
		$send_mail->Password = PASS_MAIL;
		$send_mail->Port = PORT_MAIL;
		$send_mail->send();
		$msj="Se ha enviado la contraseña a tu correo electrónico.";
	}
	catch (Exception $e){$msj=$e->errorMessage();}
	catch (\Exception $e){$msj=$e->getMessage();}

	$query="INSERT INTO qsy_seguridad VALUES ('$rfc','$fecha',1,'$contrasena','$usuario')";
	//print_r($query."<br>");
	$result=pg_query($conn,$query) or die("Problema al insertar la contraseña.");
	$query="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','E','A')";
	$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
	//print_r($query."<br>");
	$query="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','C','I')";
	$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
	//print_r($query."<br>");
	$query="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','R','I')";
	$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
	//print_r($query."<br>");
	$query="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','T','I')";
	$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
	//print_r($query."<br>");
	print_r("El declarante con el RFC $rfc se ha agregado correctamente.<br>");
  }
}
	//$message = '<div class="alert alert-success">Data Imported Successfully</div>';
	//print_r("El archivo ".$_FILES["imported"]["name"]." se ha procesado.");
	print_r("</div>");
}
}
?>