<?php
include("../include/conexion.php");
include("../include/constantes.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../PHPMailer/src/Exception.php');
require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/SMTP.php');

function generaPass(){  

    $cadena = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrtuvwxyz0123456789";
    $longitudCadena=strlen($cadena);    
    $pass = "";
    $longitudPass=6;
    for($i=1 ; $i<=$longitudPass ; $i++){
        $pos=rand(0,$longitudCadena-1);     
        $pass .= substr($cadena,$pos,1);
    }
    return $pass;
}

if(isset($_POST['passuser']) && !empty($_POST['passuser'])) {
    $rfc = $_POST['rfc'];
	$sql = "SELECT rfc, secuencia,contrasena FROM qsy_seguridad WHERE rfc = '$rfc'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	$rows = pg_num_rows($result);
	if($rows > 0) {
		$row = pg_fetch_assoc($result);
		$contra=$row['contrasena'];
		$contrasena=password_verify($_POST['old-pass'],$contra);
		if($contrasena){
			if($_POST['new-pass'] == $_POST['new-pass2']){
				if(!empty($_POST['new-pass'])){
				    $newpass = password_hash($_POST['new-pass'], PASSWORD_BCRYPT);
					$sql="UPDATE qsy_seguridad SET contrasena = '$newpass' WHERE rfc = '$rfc'";
					$result=pg_query($conn,$sql);
					echo "Exito";
				}
				else echo "La contraseña no puede estar vacía.";
			}
			else echo "Las contraseñas no coinciden.";
		}
		else echo "Contraseña incorrecta.";	
	}
	else echo "Contraseña incorrecta.";
}

if (isset($_POST['passti']) && !empty($_POST['passti'])) {
    $rfc = $_POST['rfc'];
	if($_POST['new-pass'] == $_POST['new-pass2']){
		if(!empty($_POST['new-pass'])){
		    $newpass = password_hash($_POST['new-pass'], PASSWORD_BCRYPT);
			$sql = "SELECT rfc, secuencia,contrasena FROM qsy_seguridad WHERE rfc = '$rfc'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_all($result);
			if($val){
				$sql="UPDATE qsy_seguridad SET contrasena = '$newpass' WHERE rfc = '$rfc'";
			}
			else{
				$usuario_modif=$_POST['rfc_modif'];
				$fecha=date("Y-m-d");
				$sql="INSERT INTO qsy_seguridad VALUES ('$rfc','$fecha',1,'$newpass','$usuario_modif')";
			}
			$result=pg_query($conn,$sql);
			echo "Exito";
		}
		else echo "La contraseña no puede estar vacía.";
	}
	else echo "Las contraseñas no coinciden.";
}
if (isset($_POST['passmail']) && !empty($_POST['passmail'])) {

	$rfc = $_POST['rfc'];
	$pass = $_POST['pass'];
	$sql="SELECT contrasena FROM qsy_seguridad WHERE rfc='$rfc' AND contrasena='$pass'";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_all($result);
	if($val){
		if($_POST['new-pass'] == $_POST['new-pass2']){
			if(!empty($_POST['new-pass'])){
			    $newpass = password_hash($_POST['new-pass'], PASSWORD_BCRYPT);
				$sql="UPDATE qsy_seguridad SET contrasena = '$newpass' WHERE rfc = '$rfc'";
				$result=pg_query($conn,$sql);
				echo "Exito";
			}
			else echo "La contraseña no puede estar vacía.";
		}
		else echo "Las contraseñas no coinciden.";
	}
	else echo "El enlace es incorrecto, favor de verificar.";
}

if (isset($_POST['pass_change'])) {
    $rfc = $_POST['rfc'];
    $email = $_POST['email'];

    $sql="SELECT email_institucional,email_personal,nombre,primer_ap FROM qsy_rh_empleados e WHERE rfc='$rfc' AND (email_institucional='$email' OR email_personal='$email')";
	$result=pg_query($conn,$sql);
	$val=pg_fetch_assoc($result);
	if($val){
		$nombre=$val["nombre"]." ".$val["primer_ap"];
		$clave=generaPass();
		$newpass = password_hash($clave, PASSWORD_BCRYPT);
		$link=HTTP_PATH."/cambio-pass.php?rfc=".$rfc."&pass=".$newpass;
		$sql="SELECT * FROM qsy_seguridad WHERE rfc='$rfc'";
		$result=pg_query($conn,$sql);
		$val2=pg_fetch_assoc($result);
		if($val2){
			$sql="UPDATE qsy_seguridad SET contrasena = '$newpass' WHERE rfc = '$rfc'";
		}
		else{
			$fecha=date("Y-m-d");
			$sql="INSERT INTO qsy_seguridad VALUES ('$rfc','$fecha',1,'$newpass','$rfc')";
		}
		try {
			$send_mail = new PHPMailer(TRUE);
			$send_mail->CharSet = 'UTF-8';

			$send_mail->setFrom(USER_MAIL, NOMBRE_SIS);
			$send_mail->addAddress($email, $nombre);
			$send_mail->Subject = 'Cambio de contraseña';

			$send_mail->isHTML(TRUE);
			$send_mail->Body = 'Hola '.$nombre.'.<br><br>
			Por favor, da click en el siguiente enlace para cambiar tu contraseña: <br><strong> <a href='.$link.'>'.$link.'</a> </strong>';
			$send_mail->isSMTP();
			$send_mail->Host = SMTP_MAIL;
			$send_mail->SMTPAuth = TRUE;
			$send_mail->SMTPSecure = 'tls';
			$send_mail->Username = USER_MAIL;
			$send_mail->Password = PASS_MAIL;
			$send_mail->Port = PORT_MAIL;
			$send_mail->send();
	
			$result=pg_query($conn,$sql);
			$msj="Se ha enviado un enlace de recuperación a tu correo electrónico.";
		}
		catch (Exception $e)
		{
		   $msj=$e->errorMessage();
		}
		catch (\Exception $e)
		{
		   $msj=$e->getMessage();
		}
	}
	else{
		$msj="Datos incorrectos. Verifica tu RFC y correo con el área de Recursos Humanos.";
	}
	echo $msj;
}
?>