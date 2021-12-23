<?php

function red_social($nombre,$red,$nombre_red){
		switch($red){
		case 'FB':
			define($nombre,'http://facebook.com/'.$nombre_red);
			break;
		case 'TW':
			define($nombre,'http://twitter.com/'.$nombre_red);
			break;
		case 'IN':
			define($nombre,'http://instagram.com/'.$nombre_red);
			break;
		case 'LN':
			define($nombre,'http://linkedin.com/in/'.$nombre_red);
			break;
		case 'YT':
			define($nombre,'http://youtube.com/'.$nombre_red);
			break;
		case 'WA':
			define($nombre,'https://web.whatsapp.com/');
			break;
		default:
			define($nombre,$nombre_red);
			break;
	}
}

	date_default_timezone_set("America/Mexico_City");
	$local="";

	define('LOCALPATH', $_SERVER['DOCUMENT_ROOT']);
	define('HTTP_PATH', 'http://' . $_SERVER['HTTP_HOST']);

	define('SMTP_MAIL', 'smtp.outlook.com');
	define('USER_MAIL', 'david_mejia@qualsys.com.mx');
	define('PASS_MAIL', '24139073.1c3');
	define('PORT_MAIL', 587);

	$query="SELECT * FROM qsy_instalacion WHERE dependencia=1";
	$result=pg_query($conn,$query);
    $val=pg_fetch_array($result);
    if($val){
    	//print_r($val);
    	$nombre=$val["nombre"];
    	$pais=$val["pais"];
    	$colonia=$val["colonia"];
    	$municipio=$val["municipio"];
    	$estado=$val["estado"];
    	$cp=$val["codigopostal"];
    	$redi1=$val["red_tipo1"];
    	$redi2=$val["red_tipo2"];
    	$red1=$val["red_nombre1"];
    	$red2=$val["red_nombre2"];
    	$calle=$val["calle"];
    	$noext=$val["num_exterior"];
    	$noint=$val["num_interior"];

		define('NOMBRE_EMPRESA',$nombre);
		define('CALLE_EMPRESA',$calle);
		define('UBI_EMPRESA','M');
		define('EXT_EMPRESA',$noext);
		define('INT_EMPRESA',$noint);
		define('COL_EMPRESA',$colonia);
		define('MUN_EMPRESA',$municipio);
		define('EST_EMPRESA',$estado);
		define('PAIS_EMPRESA',$pais);
		define('CP_EMPRESA',$cp);
		define('RED1_IMAGEN',$redi1);
		define('RED2_IMAGEN',$redi2);
		define('RED1_EMPRESA',$red1);
		define('RED2_EMPRESA',$red2);
		red_social('RED1_LINK',$redi1,$red1);
		red_social('RED2_LINK',$redi2,$red2);

		define('TEL1_EMPRESA',$val["telefono1"]);
		define('TEL2_EMPRESA',$val["telefono2"]);
		define('EXT1_EMPRESA',$val["extension1"]);
		define('EXT2_EMPRESA',$val["extension2"]);
		define('CORREO1_EMPRESA',$val["contacto1"]);
		define('CORREO2_EMPRESA',$val["contacto2"]);
//twitter.com/nombre
//facebook.com/nombre
//instagram.com/nombre
//linkedin.com/in/nombre
//youtube.com
//whatsapp
		define('CALLE_COMPLETE','Calle');
		define('UBI_COMPLETE','M');
		define('EXT_COMPLETE','68');
		define('INT_COMPLETE','');
		if($pais!=""){
			$query="SELECT descr FROM qsy_paises WHERE pais='$pais'";
			$result=pg_query($conn,$query);
		    $val=pg_fetch_array($result);
			define('PAIS_COMPLETE',$val);
		}
		else
			define('PAIS_COMPLETE','');
		if($estado!=""){
			$query="SELECT descr FROM qsy_estados WHERE estado='$estado'";
			$result=pg_query($conn,$query);
		    $val=pg_fetch_row($result);
			define('EST_COMPLETE',$val[0]);
		}
		else
			define('EST_COMPLETE','');
		if($estado!="" && $municipio!=""){
			$query="SELECT descr FROM qsy_municipios WHERE municipio='$municipio' AND estado='$estado'";
			$result=pg_query($conn,$query);
		    $val=pg_fetch_row($result);
			define('MUN_COMPLETE',$val[0]);
		}
		else
			define('MUN_COMPLETE','');

		define('COL_COMPLETE',$colonia);
		define('CP_COMPLETE',$cp);
    }
	else{
		define('NOMBRE_EMPRESA','');
		define('CALLE_EMPRESA','');
		define('UBI_EMPRESA','');
		define('EXT_EMPRESA','');
		define('INT_EMPRESA','');
		define('COL_EMPRESA','');
		define('MUN_EMPRESA','');
		define('EST_EMPRESA','');
		define('PAIS_EMPRESA','');
		define('CP_EMPRESA','');
		define('RED1_IMAGEN','FB');
		define('RED2_IMAGEN','TW');
		define('RED1_EMPRESA','Facebook');
		define('RED2_EMPRESA','Twitter');
		red_social('RED1_LINK','FB','');
		red_social('RED2_LINK','TW','');

		define('TEL1_EMPRESA','5555555555');
		define('TEL2_EMPRESA','');
		define('EXT1_EMPRESA','###');
		define('EXT2_EMPRESA','');
		define('CORREO1_EMPRESA','Correo');
		define('CORREO2_EMPRESA','');

		define('CALLE_COMPLETE','');
		define('UBI_COMPLETE','');
		define('EXT_COMPLETE','');
		define('INT_COMPLETE','');
		define('COL_COMPLETE','');
		define('MUN_COMPLETE','');
		define('EST_COMPLETE','');
		define('PAIS_COMPLETE','');
		define('CP_COMPLETE','');
	}

	define ('NOMBRE_SIS','Sistema de Declaraciones');
	define('color','3f395c');
//	define('color','8A0808');
?>