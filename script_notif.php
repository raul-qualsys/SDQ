<?php 
/*
31-08-2020
DMQ-Qualsys

Cambio en envío de correos para puestos según fecha efectiva.

*/
include("include/conexion.php");
include("include/constantes.php");
include("include/funciones.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('PHPMailer/src/Exception.php');
require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/SMTP.php');

function enviar_correo($email,$texto){
	try {
		$send_mail = new PHPMailer(TRUE);
		$send_mail->CharSet = 'UTF-8';
		$send_mail->setFrom(USER_MAIL, 'Sistema de Declaraciones');
		$send_mail->addAddress($email);
		$send_mail->Subject = 'Su Declaración aún no ha sido enviada.';
		$send_mail->isHTML(TRUE);
		$send_mail->Body = $texto;
		$send_mail->isSMTP();
		$send_mail->Host = SMTP_MAIL;
		$send_mail->SMTPAuth = TRUE;
		$send_mail->SMTPSecure = 'tls';
		$send_mail->Username = USER_MAIL;
		$send_mail->Password = PASS_MAIL;
		$send_mail->Port = PORT_MAIL;
		$send_mail->send();
	}
	catch (Exception $e)
	{
	   $msj=$e->errorMessage();
	   print_r($msj);
	}
	catch (\Exception $e)
	{
	   $msj=$e->getMessage();
	   print_r($msj);
	}
}

/*function enviar_correo($email,$texto){
	print_r($email." ".$texto."<br>");	
}*/

$sql="SELECT texto FROM qsy_texto_notificaciones where id=1";
$result=pg_query($conn,$sql);
$val=pg_fetch_assoc($result);
$texto=$val["texto"];
$alerta_ini=get_tiempos($conn,"A","I");
$alerta_mod=get_tiempos($conn,"A","M");
$alerta_con=get_tiempos($conn,"A","C");
$tolera_ini=get_tiempos($conn,"T","I");
$tolera_mod=get_tiempos($conn,"T","M");
$tolera_con=get_tiempos($conn,"T","C");

/* 21-08-2020 DMQ-Qualsys Cambio de consulta para no enviar correo a aquellos usuarios que no declaran. */
$sql="SELECT a.rfc,a.fecha_contrata,a.fecha_baja,a.email_institucional,a.email_personal FROM qsy_rh_empleados a,qsy_rh_empleos b WHERE a.rfc=b.rfc";
/* Fin de actualización */
$result=pg_query($conn,$sql);
$val=pg_fetch_all($result);
if($val){
	foreach ($val as $key => $empleado) {
		$rfc=$empleado["rfc"];
		$email=$empleado["email_personal"];
		if($email==""){$email=$empleado["email_institucional"];}
		$fecha_contrata=$empleado["fecha_contrata"];
		$fecha_baja=$empleado["fecha_baja"];
		$fecha_actual=date("Y-m-d");
		if($fecha_contrata!=""){
			$fecha_limite_c = date('Y-m-d',strtotime('+60 day',strtotime($fecha_contrata)));
			$fecha_alerta_ini = date('Y-m-d',strtotime('-'.$alerta_ini.' day',strtotime($fecha_limite_c)));
			$fecha_tolera_ini = date('Y-m-d',strtotime('+'.$tolera_ini.' day',strtotime($fecha_limite_c)));
			$anio_c=date("Y",strtotime($fecha_contrata));
		}
		else{
			$fecha_limite_c="";
			$fecha_alerta_ini="";
			$fecha_tolera_ini="";
			$anio_c=0;
		}
		if($fecha_baja!=""){
			$fecha_limite_b = date('Y-m-d',strtotime('+60 day',strtotime($fecha_baja)));
			$fecha_alerta_con = date('Y-m-d',strtotime('-'.$alerta_con.' day',strtotime($fecha_limite_b)));
			$fecha_tolera_con = date('Y-m-d',strtotime('+'.$tolera_con.' day',strtotime($fecha_limite_b)));
			$anio_b=date("Y",strtotime($fecha_baja));
		}
		else{
			$fecha_limite_b="";
			$fecha_alerta_con="";
			$fecha_tolera_con="";
			$anio_b=0;
		}
		$mes_actual=date("m");
		$anio_actual=date("Y");
		$fecha_alerta_mod= date('Y-m-d',strtotime('-'.$alerta_mod.' day',strtotime(date("$anio_actual-05-31"))));
		$fecha_tolera_mod= date('Y-m-d',strtotime('+'.$tolera_mod.' day',strtotime(date("$anio_actual-05-31"))));

		if($fecha_alerta_ini <= $fecha_actual && $anio_c>0 && $fecha_actual <= $fecha_limite_c){
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='P' AND tipo_decl='I' AND ejercicio=$anio_c AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_all($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Inicio", $texto);
						$var=str_replace("{dec}", "Patrimonial", $var);
						enviar_correo($email,$var);
					}
				}
			}
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='I' AND tipo_decl='I' AND ejercicio=$anio_c AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_all($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Inicio", $texto);
						$var=str_replace("{dec}", "de Intereses", $var);
						enviar_correo($email,$var);
					}
				}
			}
		}
		if($anio_c<$anio_actual && $fecha_alerta_mod<=$fecha_actual && $fecha_actual <= "$anio_actual-05-31" && ($fecha_baja=="" || "$anio_actual-05-01" < $fecha_baja)){
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='P' AND tipo_decl='M' AND ejercicio=$anio_actual AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_assoc($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$anio_actual-05-31' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Modificación", $texto);
						$var=str_replace("{dec}", "Patrimonial", $var);
						enviar_correo($email,$var);
					}
				}
			}
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='I' AND tipo_decl='M' AND ejercicio=$anio_actual AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_assoc($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$anio_actual-05-31' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Modificación", $texto);
						$var=str_replace("{dec}", "de Intereses", $var);
						enviar_correo($email,$var);
					}
				}
			}
		}
		if($fecha_alerta_con <= $fecha_actual && $anio_b>0 && $fecha_actual <= $fecha_limite_b){
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='P' AND tipo_decl='C' AND ejercicio=$anio_b AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
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
						$var=str_replace("{declaracion}", "Conclusión", $texto);
						$var=str_replace("{dec}", "Patrimonial", $var);
						enviar_correo($email,$var);
					}
				}
			}
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='I' AND tipo_decl='C' AND ejercicio=$anio_b AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
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
						$var=str_replace("{declaracion}", "Conclusión", $texto);
						$var=str_replace("{dec}", "de Intereses", $var);
						enviar_correo($email,$var);
					}
				}
			}
		}
		if($anio_c>0 && $fecha_limite_c < $fecha_actual && $fecha_actual <= $fecha_tolera_ini){
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='P' AND tipo_decl='I' AND ejercicio=$anio_c AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_all($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Inicio", $texto);
						$var=str_replace("{dec}", "Patrimonial", $var);
						enviar_correo($email,$var);
					}
				}
			}
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='I' AND tipo_decl='I' AND ejercicio=$anio_c AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_all($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Inicio", $texto);
						$var=str_replace("{dec}", "de Intereses", $var);
						enviar_correo($email,$var);
					}
				}
			}
		}
		if($anio_c<$anio_actual && "$anio_actual-05-31"<$fecha_actual && $fecha_actual<=$fecha_tolera_mod && ($fecha_baja=="" || "$anio_actual-05-01" < $fecha_baja)){
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='P' AND tipo_decl='M' AND ejercicio=$anio_actual AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_assoc($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$anio_actual-05-31' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Modificación", $texto);
						$var=str_replace("{dec}", "Patrimonial", $var);
						enviar_correo($email,$var);
					}
				}
			}
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='I' AND tipo_decl='M' AND ejercicio=$anio_actual AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
			$result=pg_query($conn,$sql);
			$val2=pg_fetch_assoc($result);
			if($val2){}
			else{
				$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.estatus='A' AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$anio_actual-05-31' AND estatus='A' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1))";
				$result2=pg_query($conn,$sql);
				$val2=pg_fetch_assoc($result2);
				if($val2){
					$declara_completo=$val2["declaracion"];
					if($declara_completo=="N"){}
					else{
						$var=str_replace("{declaracion}", "Modificación", $texto);
						$var=str_replace("{dec}", "de Intereses", $var);
						enviar_correo($email,$var);
					}
				}
			}
		}
		if($anio_b>0 && $fecha_limite_b < $fecha_actual && $fecha_actual <= $fecha_tolera_con){
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='P' AND tipo_decl='C' AND ejercicio=$anio_b AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
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
						$var=str_replace("{declaracion}", "Conclusión", $texto);
						$var=str_replace("{dec}", "Patrimonial", $var);
						enviar_correo($email,$var);
					}
				}
			}
			$sql="SELECT estatus_decl FROM qsy_declaraciones WHERE rfc='$rfc' AND declaracion='I' AND tipo_decl='C' AND ejercicio=$anio_b AND (estatus_decl='P' OR estatus_decl='X' OR estatus_decl='N')";
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
						$var=str_replace("{declaracion}", "Conclusión", $texto);
						$var=str_replace("{dec}", "de Intereses", $var);
						enviar_correo($email,$var);
					}
				}
			}
		}
	}
}
?>