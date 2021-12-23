<?php
    include("../include/conexion.php");
    include("../include/funciones.php");
    include("../include/constantes.php");
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require('../PHPMailer/src/Exception.php');
	require('../PHPMailer/src/PHPMailer.php');
	require('../PHPMailer/src/SMTP.php');

if(isset($_POST)){
	$rfc = escape($_POST['rfc'].$_POST['homoclave']);
	$pass=htmlspecialchars($_POST["password"]);
	$date = date('Y-m-d');
	$nombre = escape($_POST['nombre']);
	$apellido1 = escape($_POST['apellido1']);
	$apellido2 = escape($_POST['apellido2']);
	$curp = escape($_POST['curp']);
	$emp_num = escape($_POST['emp_num']);
	$tipo_empleado = escape($_POST['tipo_empleado']);
	$email1 = pg_escape_string($_POST['email_trabajo']);
	$email2 = pg_escape_string($_POST['email']);
	$tel1 = escape($_POST['tel_casa']);
	$tel2 = escape($_POST['tel_celular']);
	$ecivil = escape($_POST['edo_civil']);
	$pais = escape($_POST['pais']);
	$nacionalidad = escape($_POST['nacionalidad']);
	$sexo = escape($_POST['sexo']);
	$fecha_nac = escape($_POST['fecha_nac']);
	$fecha_contratacion = escape($_POST['fecha_contratacion']);
	if($fecha_contratacion==""){
		$fecha_contratacion=$date;
	}

    $codigopostal=escape($_POST["cp"]);
    $calle=escape($_POST["calle"]);
    $num_exterior=escape($_POST["noext"]);
    $num_interior=escape($_POST["noint"]);
	if($nombre=="" || $apellido1=="" || $curp=="" || $rfc=="" || $email2=="" || $fecha_nac=="" || $pass==""){
		print_r("No se han llenado todos los campos requeridos en la sección de Datos Generales.");die;
	}
    //$colonia=htmlspecialchars($_POST["colonia"]);
    //$municipio=htmlspecialchars($_POST["municipio"]);
    //$estado=htmlspecialchars($_POST["estado"]);

	if($_POST["estado2"]!=""){
		$estado="";
		$estado_descr=escape($_POST["estado2"]);
	}
	else{
		$estado=escape($_POST["estado"]);
		$estado_descr=get_descr($conn,$estado,"descr","qsy_estados","estado");
	}
	if(isset($_POST["municipio"])){
		$municipio=escape($_POST["municipio"]);
		$municipio_descr=get_municipio($conn,$municipio,$estado);
	}
	else{
		$municipio="";
		$municipio_descr="";		
	}
	if(isset($_POST["colonia2"])){
		$colonia=escape($_POST["colonia2"]);
		$colonia_descr=get_colonia($conn,$colonia,$codigopostal);
	}
	else{
		$colonia="";
		$colonia_descr=escape($_POST["colonia"]);
	}
	$pais=escape($_POST["pais"]);
	$pais_descr=get_descr($conn,$pais,"descr","qsy_paises","pais");

    $orden_id=escape($_POST["nivel_gobierno"]);
    $ambito_id=escape($_POST["ambito_publico"]);
    $dependencia=escape($_POST["ente"]);
    $area_adscripcion=escape($_POST["area"]);
    $id_puesto=escape($_POST["puesto"]);
		if($id_puesto=='')$id_puesto=0;
    $nivel_empleo=escape($_POST["nivel_empleo"]);
    $funcion_principal=escape($_POST["funcion_principal"]);
    $tel_oficina=escape($_POST["telefono"]);
    $extension=escape($_POST["extension"]);

    $ubicacion2=escape(UBI_EMPRESA);
    $calle2=escape(CALLE_EMPRESA);
    $num_exterior2=escape(EXT_EMPRESA);
    $num_interior2=escape(INT_EMPRESA);
    $colonia2=escape(COL_EMPRESA);
    $municipio2=escape(MUN_EMPRESA);
    $estado2=escape(EST_EMPRESA);
    $pais2=escape(PAIS_EMPRESA);
    $codigopostal2=escape(CP_EMPRESA);

	if($orden_id=="" || $ambito_id=="" || $dependencia==""){
		print_r("No se han llenado todos los campos requeridos en la sección de Datos de Empleo.");die;
	}

	$query="SELECT rfc FROM qsy_rh_empleados WHERE rfc='$rfc'";
	$result=pg_query($conn,$query);
	$val=pg_fetch_row($result);
	if($val){
		//print_r($val);
		echo "El RFC ya está asociado a otro usuario.";
	}
	else{

	$query = "INSERT INTO qsy_rh_empleados VALUES ('$rfc','$date','$nombre','$apellido1','$apellido2','$curp','$email1','$email2','$tel1','$tel2','$ecivil','$nacionalidad','$nacionalidad','$sexo','$fecha_nac','A','$fecha_contratacion',null,'$tipo_empleado','$emp_num')";
	//print_r($query);
	$result=pg_query($conn,$query) or die("Problema al insertar el usuario.");

	$query = "INSERT INTO qsy_rh_direcciones VALUES ('$rfc','$date','$calle','$num_exterior','$num_interior','$colonia','$colonia_descr','$municipio','$municipio_descr','$estado','$estado_descr','$pais','$pais_descr','$codigopostal')";
	$result=pg_query($conn,$query) or die("Problema al insertar la dirección.");

	$query = "INSERT INTO qsy_rh_empleos VALUES ('$rfc','$date','$orden_id','$ambito_id','$dependencia','$area_adscripcion','$nivel_empleo',$id_puesto,'$funcion_principal','$fecha_contratacion','$tel_oficina','$extension','$ubicacion2','$calle2','$num_exterior2','$num_interior2','$colonia2','$municipio2','$estado2','$pais2','$codigopostal2')";
	$result=pg_query($conn,$query) or die("Problema al insertar el empleo.");

	$contrasena = password_hash($pass, PASSWORD_BCRYPT);
	$usuario=pg_escape_string($_POST["user"]);

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

	$query="INSERT INTO qsy_seguridad VALUES ('$rfc','$date',1,'$contrasena','$usuario')";
	$result=pg_query($conn,$query) or die("Problema al insertar la contraseña.");
	if($_POST["perfil"]=="RH"){
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','E','A')";
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','C','I')";
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','R','I')";
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','T','I')";
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		echo "Los datos han sido guardados.";
		}
	if($_POST["perfil"]=="TI"){
		if(isset($_POST["declarante"])){
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','E','A')";
		}
		else{
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','E','I')";
		}
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		if(isset($_POST["rh"])){
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','R','A')";
		}
		else{
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','R','I')";
		}
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		if(isset($_POST["contraloria"])){
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','C','A')";
		}
		else{
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','C','I')";
		}
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		if(isset($_POST["ti"])){
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','T','A')";
		}
		else{
		$query="INSERT INTO qsy_roles VALUES ('$rfc','$date','T','I')";
		}
		$result=pg_query($conn,$query) or die("Problema al insertar el permiso.");
		echo "Los datos han sido guardados.";
		}
	}

}
?>