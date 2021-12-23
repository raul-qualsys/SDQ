<!-- Denise Cigarroa Rodriguez 21/08/2020 
Se conbinaron las tres declaraciones patrimoniales en una sola
se cambio el estatus de las declaraciones completas y parciales
Tambien se agregaron dos variables para el pie de pagina y los logos 
asi mismo se cambio el color de los encabezados de las tablas -->
<!--#############################################################-->
<?php
require_once('../../vendor/autoload.php');
include("../../include/conexion.php");
#Denise Cigarroa Rodriguez 21/08/2020# 
#Se agrego el include para traer la dirección de la empresa#
include("../../include/constantes.php");
include("../../include/funciones.php");
###################################################################
#Denise Cigarroa Rodriguez 21/08/2020# 
#Esta es una variable global que contiene la dirección de la empresa em turno#
$direccion = CALLE_EMPRESA." ".EXT_EMPRESA." ".COL_COMPLETE." ".MUN_COMPLETE."<br>".CP_EMPRESA." ".EST_COMPLETE;
#######################################################################
$rfc=$_POST["rfc"];
$ejercicio=$_POST["ejercicio"];
$tipo_decl=$_POST["tipo_decl"];
$declara_completo=$_POST["declara_completo"];

#Denise Cigarroa Rodriguez 21/08/2020# 
#Esta sentencia dice que tipo de declaración se imprimira#
if ($tipo_decl =='I'){
$tipdecl='INICIAL';
}elseif ($tipo_decl =='M') {
  $tipdecl='MODIFICACIÓN';
}elseif ($tipo_decl =='C') {
  $tipdecl='CONCLUSIÓN';}
#####################################################################

$sqlg = "SELECT rfc,id_puesto,declaracion
  FROM qsy_rh_empleos eos
  join qsy_puestos tos on id_puesto = id
  where rfc='$rfc';";
  $result=pg_query($conn,$sqlg);
  $report=pg_fetch_all($result);

$sqli1="SELECT *, CASE estado_civil
WHEN 'SO' THEN 'SOLTERO(A)'
WHEN 'CA' THEN 'CASADO(A)'
WHEN 'UN' THEN 'UNION LIBRE'
WHEN 'SC' THEN 'SOCIEDAD DE CONVIVENCIA'
WHEN 'VI' THEN 'VIUDO(A)'
WHEN 'DI' THEN 'DIVORCIADO(A)'
 END AS estado_civil,
 CASE regimen_matri
 WHEN 'C' THEN 'SOCIEDAD CONYUGAL'
 WHEN 'S' THEN 'SEPARACIÓN DE BIENES'
 WHEN 'O' THEN 'OTRO' END AS regimen_matri
 FROM qsy_datos_generales WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti1=pg_query($conn,$sqli1);
$reportei1=pg_fetch_all($resulti1);

$sqli2="SELECT * FROM qsy_direcciones WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti2=pg_query($conn,$sqli2);
$reportei2=pg_fetch_all($resulti2);

$sqli3="SELECT *, CASE nivel_escolar 
WHEN 'P' THEN 'PRIMARIA'
WHEN 'S' THEN 'SECUNDARIA'
WHEN 'B' THEN 'BACHILLERATO'
WHEN 'T' THEN 'CARRERA TÉCNICA O COMERCIAL'
WHEN 'L' THEN 'LICENCIATURA'
WHEN 'E' THEN 'ESPECIALIDAD'
WHEN 'M' THEN 'MAESTRIA'
WHEN 'D' THEN 'DOCTORADO' END AS nivel_escolar,
CASE estatus_estudio
WHEN 'C' THEN 'CURSANDO'
WHEN 'F' THEN 'FINALIZADO'
WHEN 'T' THEN 'TRUNCO' END AS estatus_estudio,
CASE doc_obtenido
WHEN 'B' THEN 'BOLETA'
WHEN 'C' THEN 'CERTIFICADO'
WHEN 'S' THEN 'CONSTANCIA' 
WHEN 'T' THEN 'TITULO' END AS doc_obtenido,
CASE ubicacion
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion
FROM qsy_escolaridades WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio
ORDER BY fecha_doc DESC";
$resulti3=pg_query($conn,$sqli3);
$reportei3=pg_fetch_all($resulti3);

$sqli4="SELECT *, CASE honorarios 
WHEN 'N' THEN 'NO'
WHEN 'S' THEN 'SI' END AS honorarios,
CASE otro_empleo
WHEN 'N' THEN 'NO'
WHEN 'S' THEN 'SI' END AS otro_empleo
FROM qsy_comision_actual WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti4=pg_query($conn,$sqli4);
$reportei4=pg_fetch_all($resulti4);

$sqli5="SELECT *, CASE ubicacion
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE actividad_laboral
WHEN 'U' THEN 'PUBLICO'
WHEN 'V' THEN 'PRIVADO'
WHEN 'O' THEN 'OTRO'
WHEN 'N' THEN 'NINGUNO' END AS actividad_laboral,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento 
FROM qsy_experiencia WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio
order by fecha_inicio desc FETCH FIRST 5 ROWS ONLY";
$resulti5=pg_query($conn,$sqli5);
$reportei5=pg_fetch_all($resulti5);

$sqli6="SELECT *, CASE extranjero
when 'N' then 'NO'
when 'S' then 'SI' END AS extranjero,
CASE dependiente
when 'N' then 'NO'
when 'S' then 'SI' END AS dependiente,
CASE mismo_domicilio
when 'N' then 'NO'
when 'S' then 'SI' END AS mismo_domicilio,
CASE proveedor
when 'N' then 'NO'
when 'S' then 'SI' END AS proveedor,
CASE residencia
when 'M' then 'México'
when 'E' then 'Extranjero' 
when 'D' then 'Desconocido' END AS residencia,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_parejas WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti6=pg_query($conn,$sqli6);
$reportei6=pg_fetch_all($resulti6);

$sqli7="SELECT *, CASE extranjero
when 'N' then 'NO'
when 'S' then 'SI' END AS extranjero, 
CASE mismo_domicilio
when 'N' then 'NO'
when 'S' then 'SI' END AS mismo_domicilio,
CASE residencia
when 'M' then 'México'
when 'E' then 'Extranjero' 
when 'D' then 'Desconocido' END AS residencia,
CASE actividad_laboral
when 'U' then 'Publico'
when 'V' then 'privado' 
when 'O' then 'Otro'
when 'N' then 'Ninguno' END AS actividad_laboral,
CASE proveedor 
when 'N' then 'NO'
when 'S' then 'SI' END AS proveedor,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_dependientes WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti7=pg_query($conn,$sqli7);
$reportei7=pg_fetch_all($resulti7);

$sqli8="SELECT * FROM qsy_ingresos_netos WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti8=pg_query($conn,$sqli8);
$reportei8=pg_fetch_all($resulti8);

$sqli9="SELECT *,
case servidor_anio_prev
when 'N' THEN 'NO' 
WHEN 'S' THEN 'SI' END AS servidor_anio_prev
FROM qsy_ingresos_anio_anterior 
WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti9=pg_query($conn,$sqli9);
$reportei9=pg_fetch_all($resulti9);

$sqli10="SELECT *, CASE ubicacion 
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento 
FROM qsy_inmuebles WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti10=pg_query($conn,$sqli10);
$reportei10=pg_fetch_all($resulti10);

$sqli11="SELECT *, CASE tipo_vehiculo 
WHEN 'M' THEN 'Automóvil / Motocicleta'
WHEN 'A' THEN 'AERONAVE'
WHEN 'B' THEN 'BARCO / YATE'
WHEN 'O' THEN 'OTRO' END AS tipo_vehiculo,
CASE ubicacion 
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento 
FROM qsy_vehiculos WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti11=pg_query($conn,$sqli11);
$reportei11=pg_fetch_all($resulti11);

$sqli12="SELECT *,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento  FROM qsy_muebles WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti12=pg_query($conn,$sqli12);
$reportei12=pg_fetch_all($resulti12);

$sqli13="SELECT *, CASE ubicacion 
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento  
FROM qsy_inversiones WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti13=pg_query($conn,$sqli13);
$reportei13=pg_fetch_all($resulti13);


$sqli14="SELECT *,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento 
FROM qsy_adeudos WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti14=pg_query($conn,$sqli14);
$reportei14=pg_fetch_all($resulti14);

$sqli15="SELECT *, CASE tipo_comodato
when 'I' THEN 'INMUEBLE'
WHEN 'V' THEN 'VEHICULO' END AS tipo_comodato,
CASE ubicacion 
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE tipo_vehiculo 
WHEN 'M' THEN 'Automóvil / Motocicleta'
WHEN 'A' THEN 'AERONAVE'
WHEN 'B' THEN 'BARCO / YATE'
WHEN 'O' THEN 'OTRO' END AS tipo_vehiculo,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_comodatos WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti15=pg_query($conn,$sqli15);
$reportei15=pg_fetch_all($resulti15);

$ti= $reportei1[0]['tipo_decl'];
$td=$reportei1[0]['declaracion'];
#Denise Cigarroa 21/08/2020#
#Aqui se manda a llamar los logos para el cabecero de la declaración#
$logo1="<img src='".HTTP_PATH."/css/images/qsy_logo_nivel.png' style='height:70px;width: auto;'>";
$logo2="<img src='".HTTP_PATH."/css/images/qsy_logo_depend.png' style='height:70px;width: auto;'>";
################################################################################################
/*28-08-2020 DMQ-Qualsys Adaptación para cambiar los textos de la declaración imprimible.*/
    $html1="<body>
            <header class='clearfix'>
              <div id='logo1'>
                $logo1
              </div>
              <p id='til'>
                ÓRGANO INTERNO DE CONTROL
              <p>
              <div id='logo2'>
                $logo2
              </div>
            </header>
              <main>
                <table>
                  <tr>
                    <td class='cpt' colspan='3'>
                      <p>ÓRGANO INTERNO DE CONTROL DE LA DEPENDENCIA ".NOMBRE_EMPRESA.":</p><br>";
                      $texto=get_notificacion(9,$conn);
                      $texto=str_replace("{declaracion}","DE SITUACIÓN PATRIMONIAL",$texto);
                      $html1.="<p>$texto</p>
                    </td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td class='cs' colspan='3'>I. DECLARACIÓN DE SITUACIÓN PATRIMONIAL </td>
                    <td class='cs' colspan='2'>$tipdecl</td>
                  </tr>  
                </table>
                <table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 1.- DATOS GENERALES</td>
                  </tr>";
/* Fin de actualización.*/
                  if (pg_num_rows($resulti1)>0){
                  foreach ($reportei1 as $repi1) {

                  $html1.="
                  <tr>
                    <td class='cp3t'>PRIMER APELLIDO</td>
                    <td class='cp3t'>SEGUNDO APELLIDO</td>
                    <td class='cp3t'>NOMBRE(S)</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi1['primer_ap']."</td>
                    <td class='cp3'>".$repi1['segundo_ap']."</td>
                    <td class='cp3'>".$repi1['nombre']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>CURP</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>HOMOCLAVE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi1['curp']."</td>
                    <td class='cp3'>".substr($repi1['rfc'],0,-3)."</td>
                    <td class='cp3'>".substr($repi1['rfc'],10,3)."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>CORREO ELECTRÓNICO INSTITUCIONAL</td>
                    <td class='cp3t'>CORREO ELECTRÓNICO PERSONAL / ALTERNO</td>
                    <td class='cp3t'>NÚMERO TELEFÓNICO DE CASA</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi1['email_institucional']."</td>
                    <td class='cp3'>".$repi1['email_personal']."</td>
                    <td class='cp3'>".$repi1['tel_casa']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NÚMERO CELULAR PERSONAL</td>
                    <td class='cp3t'>SITUACIÓN PERSONAL / ESTADO CIVIL</td>
                    <td class='cp3t'>RÉGIMEN MATRIMONIAL </td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi1['celular_personal']."</td>
                    <td class='cp3'>".$repi1['estado_civil']."</td>
                    <td class='cp3'>".$repi1['regimen_matri']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS DE NACIMIENTO</td>
                    <td class='cp3t' colspan='2'>NACIONALIDAD</td>
                  </tr> 
                  <tr>
                    <td class='cp3' colspan='1'>".$repi1['pais_desc']."</td>
                    <td class='cp3' colspan='2'>".$repi1['nacionalidad_desc']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi1['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html1.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PRIMER APELLIDO</td>
                    <td class='cp3t'>SEGUNDO APELLIDO</td>
                    <td class='cp3t'>NOMBRE(S)</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>CURP</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>HOMOCLAVE</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>CORREO ELECTRÓNICO INSTITUCIONAL</td>
                    <td class='cp3t'>CORREO ELECTRÓNICO PERSONAL / ALTERNO</td>
                    <td class='cp3t'>NÚMERO TELEFÓNICO DE CASA</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NÚMERO CELULAR PERSONAL</td>
                    <td class='cp3t'>SITUACIÓN PERSONAL / ESTADO CIVIL</td>
                    <td class='cp3t'>RÉGIMEN MATRIMONIAL </td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS DE NACIMIENTO</td>
                    <td class='cp3t' colspan='2'>NACIONALIDAD</td>
                  </tr> 
                  <tr>
                    <td class='cp3' colspan='1'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 
                 $html1.="                                  
                </table>";

                $html2=" <table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='4'>APARTADO 2.- DOMICILIO DEL DECLARANTE</td>
                  </tr>";
                  if (pg_num_rows($resulti2)>0){
                  foreach ($reportei2 as $repi2) {
                  $html2.="
                  <tr>
                    <td class='cp4t' colspan='2'>PAÍS</td>
                    <td class='cp4  ' colspan='2'>".$repi2['pais_desc']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CALLE</td>
                    <td class='cp4t'>NÚMERO EXTERIOR</td>
                    <td class='cp4t'>NÚMERO INTERIOR</td>
                    <td class='cp4t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi2['calle']."</td>
                    <td class='cp4'>".$repi2['num_exterior']."</td>
                    <td class='cp4'>".$repi2['num_interior']."</td>
                    <td class='cp4'>".$repi2['colonia_desc']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi2['municipio_desc']."</td>
                    <td class='cp4'>".$repi2['estado_desc']."</td>
                    <td class='cp4'>".$repi2['codigopostal']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'>".$repi2['observaciones']."</td>
                  </tr>";}}

                  else{$html2.="
                  <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>PAÍS</td>
                    <td class='cp4  ' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CALLE</td>
                    <td class='cp4t'>NÚMERO EXTERIOR</td>
                    <td class='cp4t'>NÚMERO INTERIOR</td>
                    <td class='cp4t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'></td>
                  </tr>";}
                 $html2.="                                  
                </table>";
                
                $html3="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 3.- DATOS CURRICULARES DEL DECLARANTE</td>
                  </tr>";
                  if (pg_num_rows($resulti3)>0){
                  foreach ($reportei3 as $repi3) {
                  $html3.="
                  <tr>
                    <td class='cp3t'>NIVEL ESCOLAR</td>
                    <td class='cp3t'>INSTITUCIÓN EDUCATIVA</td>
                    <td class='cp3t'>CARRERA O ÁREA DE CONOCIMIENTO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi3['nivel_escolar']."</td>
                    <td class='cp3'>".$repi3['institucion']."</td>
                    <td class='cp3'>".$repi3['carrera']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESTATUS</td>
                    <td class='cp3t'>DOCUMENTO OBTENIDO</td>
                    <td class='cp3t'>FECHA DE OBTENCIÓN DEL DOCUMENTO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi3['estatus_estudio']."</td>
                    <td class='cp3'>".$repi3['doc_obtenido']."</td>
                    <td class='cp3'>".$repi3['fecha_doc']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA LA INSTITUCIÓN</td>
                    <td class='cp3' colspan='2'>".$repi3['ubicacion']."</td>
                  </tr>
                 
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi3['observaciones']."</td>
                  </tr>";}}

                  else{
                    $html3.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NIVEL ESCOLAR</td>
                    <td class='cp3t'>INSTITUCIÓN EDUCATIVA</td>
                    <td class='cp3t'>CARRERA O ÁREA DE CONOCIMIENTO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESTATUS</td>
                    <td class='cp3t'>DOCUMENTO OBTENIDO</td>
                    <td class='cp3t'>FECHA DE OBTENCIÓN DEL DOCUMENTO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA LA INSTITUCIÓN</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                 
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";}
                 $html3.="                                
                </table>";
              // Denise Cigarroa 21/08/2020
              //Con esta sentencia se identifica el tipo de declaración a imprimir
                   $html4="<table border='1' cellspacing='0'>
                  <tr>";
                  if ($tipo_decl =='M') {
                      $html4.="<td class='cs' colspan='4'>APARTADO 4.- DATOS DEL EMPLEO, CARGO O COMISIÓN ACTUAL</td>";}
                    elseif ($tipo_decl =='C') {
                     $html4.="<td class='cs' colspan='4'>APARTADO 4.- DATOS DEL EMPLEO, CARGO O COMISIÓN QUE CONCLUYE</td>";}
                     else{
                      $html4.="<td class='cs' colspan='4'>APARTADO 4.- DATOS DEL EMPLEO, CARGO O COMISIÓN QUE INICIA</td>";}
                  ####################################################################
                     $html4.="</tr>";

                  if (pg_num_rows($resulti4)>0){
                  foreach ($reportei4 as $repi4) {
                  $html4.="
                  <tr>
                    <td class='cp4t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp4' colspan='3'>".$repi4['orden_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>ÁMBITO PÚBLICO</td>
                    <td class='cp4' colspan='3'>".$repi4['ambito_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>NOMBRE DEL ENTE PÚBLICO</td>
                    <td class='cp4' colspan='3'>".$repi4['dependencia_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp4t'>EMPLEO, CARGO O COMISIÓN</td>
                    <td class='cp4t'>¿ESTÁ CONTRATADO POR HONORARIOS?</td> 
                    <td class='cp4t'>NIVEL DEL EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi4['area_descr']."</td>
                    <td class='cp4'>".$repi4['puesto_descr']."</td>
                    <td class='cp4'>".$repi4['honorarios']."</td> 
                    <td class='cp4'>".$repi4['nivel_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp4' colspan='3'>".$repi4['funcion_principal']."</td> 
                  </tr>
                  <tr>";
                  if ($tipo_decl =='C'){
                     $html4.="
                    <td class='cp4t' colspan='2'>FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN</td>";
                  }else{
                      $html4.="
                    <td class='cp4t' colspan='2'>FECHA DE TOMA DE POSESIÓN DEL EMPLEO, CARGO O COMISIÓN</td>";}

                    $html4.="<td class='cp4t' colspan='2'>TELÉFONO DE OFICINA Y EXTENSIÓN</td> 
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi4['fecha_inicio']."</td>
                    <td class='cp4' colspan='2'>".$repi4['tel_oficina'].' '.$repi4['extension']."</td> 
                  </tr>
                  <tr>
                    <td class='cv' colspan='4'>DOMICILIO DEL EMPLEO, CARGO O COMISIÓN</td> 
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>PAÍS</td>
                    <td class='cp4' colspan='2'>".$repi4['pais_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>CALLE</td>
                    <td class='cp4t'>NÚMERO EXTERIOR</td>
                    <td class='cp4t'>NÚMERO INTERIOR</td>
                    <td class='cp4t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi4['calle']."</td>
                    <td class='cp4'>".$repi4['num_exterior']."</td>
                    <td class='cp4'>".$repi4['num_interior']."</td>
                    <td class='cp4'>".$repi4['colonia_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi4['municipio_descr']."</td>
                    <td class='cp4'>".$repi4['estado_descr']."</td>
                    <td class='cp4'>".$repi4['codigopostal']."</td>
                  </tr>
                  <tr>
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'>".$repi4['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html4.="
                    <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp4' colspan='3'></td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>ÁMBITO PÚBLICO</td>
                    <td class='cp4' colspan='3'></td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>NOMBRE DEL ENTE PÚBLICO</td>
                    <td class='cp4' colspan='3'></td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp4t'>EMPLEO, CARGO O COMISIÓN</td>
                    <td class='cp4t'>¿ESTÁ CONTRATADO POR HONORARIOS?</td> 
                    <td class='cp4t'>NIVEL DEL EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td> 
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp4' colspan='3'></td> 
                  </tr>
                  <tr>
                    ";
                  if ($tipo_decl =='C'){
                     $html4.="
                    <td class='cp4t' colspan='2'>FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN</td>";
                  }else{
                      $html4.="
                    <td class='cp4t' colspan='2'>FECHA DE TOMA DE POSESIÓN DEL EMPLEO, CARGO O COMISIÓN</td>";}

                    $html4.="
                    <td class='cp4t' colspan='2'>TELÉFONO DE OFICINA Y EXTENSIÓN</td> 
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4' colspan='2'></td> 
                  </tr>
                  <tr>
                    <td class='cv' colspan='4'>DOMICILIO DEL EMPLEO, CARGO O COMISIÓN</td> 
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>PAÍS</td>
                    <td class='cp4' colspan='2'></td> 
                  </tr>
                  <tr>
                    <td class='cp4t'>CALLE</td>
                    <td class='cp4t'>NÚMERO EXTERIOR</td>
                    <td class='cp4t'>NÚMERO INTERIOR</td>
                    <td class='cp4t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'></td>
                  </tr>";
                  }
                 $html4.="  
                </table>";

                $html5="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 5.- EXPERIENCIA LABORAL (ÚLTIMOS CINCO EMPLEOS)</td>
                  </tr>";

                  if (pg_num_rows($resulti5)>0){
                  foreach ($reportei5 as $repi5) {
                  $html5.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi5['movimiento']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ÁMBITO / SECTOR  EN EL QUE LABORASTE</td>
                    <td class='cp3' colspan='2'>".$repi5['actividad_laboral']."</td> 
                  </tr>";
                  if ($repi5['actividad_laboral']=='NINGUNO'){
                    $html5.="
                  <tr>
                    <td class='cp3t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp3' colspan='2'>NO APLICA</td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>ÁMBITO PÚBLICO</td>
                    <td class='cp3' colspan='2'>NO APLICA</td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL ENTE PÚBLICO / NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>ÁREA DE ADSCRIPCIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>EMPLEO, CARGO O COMISIÓN / PUESTO</td>
                    <td class='cp3t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp3t'>SECTOR AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>FECHA DE INGRESO</td>
                    <td class='cp4t'>FECHA DE EGRESO</td>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html5.="
                  <tr>
                    <td class='cp3t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp3' colspan='2'>".$repi5['orden_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>ÁMBITO PÚBLICO</td>
                    <td class='cp3' colspan='2'>".$repi5['ambito_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL ENTE PÚBLICO / NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>ÁREA DE ADSCRIPCIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi5['dependencia_descr']."</td>
                    <td class='cp4'>".$repi5['rfc_empresa']."</td>
                    <td class='cp4'>".$repi5['area_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>EMPLEO, CARGO O COMISIÓN / PUESTO</td>
                    <td class='cp3t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp3t'>SECTOR AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi5['puesto_descr']."</td>
                    <td class='cp4'>".$repi5['funcion_principal']."</td>
                    <td class='cp4'>".$repi5['sector_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>FECHA DE INGRESO</td>
                    <td class='cp4t'>FECHA DE EGRESO</td>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi5['fecha_inicio']."</td>
                    <td class='cp4'>".$repi5['fecha_fin']."</td>
                    <td class='cp4'>".$repi5['ubicacion']."</td>
                  </tr>";}
                  $html5.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi5['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html5.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ÁMBITO / SECTOR  EN EL QUE LABORASTE</td>
                    <td class='cp3' colspan='2'></td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp3' colspan='2'></td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>ÁMBITO PÚBLICO</td>
                    <td class='cp3' colspan='2'></td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL ENTE PÚBLICO / NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>ÁREA DE ADSCRIPCIÓN / ÁREA</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td> 
                  </tr>
                  <tr>
                    <td class='cp3t'>EMPLEO, CARGO O COMISIÓN / PUESTO</td>
                    <td class='cp3t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp3t'>SECTOR AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t'>FECHA DE INGRESO</td>
                    <td class='cp4t'>FECHA DE EGRESO</td>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html5.="    
                </table>";
                #Esta sentencia se cumple si la declaración es completa#
                if ($declara_completo == 'C') {
                
                $html6="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 6.- DATOS DE LA PAREJA</td>
                  </tr>";
                  if (pg_num_rows($resulti6)>0){
                  foreach ($reportei6 as $repi6) {
                  $html6.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi6['movimiento']."</td>
                  </tr>";
                  if ($repi6['movimiento']=='NINGUNO'){
                    $html6.="
                  <tr>
                    <td class='cp3t'>PRIMER APELLIDO</td>
                    <td class='cp3t'>SEGUNDO APELLIDO</td>
                    <td class='cp3t'>NOMBRE(S)</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>CURP</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN CON EL DECLARANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>¿ES CIUDADANO EXTRANJERO?</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>¿ES DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>LUGAR DONDE RESIDE</td>
                    <td class='cp3t'>HABITA EN EL DOMICILIO DEL DECLARANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>DOMICILIO DE LA PAREJA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ACTIVIDAD LABORAL</td>
                    <td class='cp3t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp3t'>ÁMBITO PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL ENTE PÚBLICO</td>
                    <td class='cp3t'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp3t'>EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp3t'>SALARIO MENSUAL NETO</td>
                    <td class='cp3t'>FECHA DE INGRESO AL EMPLEO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿ES PROVEEDOR O CONTRATISTA DEL GOBIERNO?</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>SECTOR AL QUE PERTENECE</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";
                  }

                  else{
                  $html6.="
                  <tr>
                    <td class='cp3t'>PRIMER APELLIDO</td>
                    <td class='cp3t'>SEGUNDO APELLIDO</td>
                    <td class='cp3t'>NOMBRE(S)</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['primer_apellido']."</td>
                    <td class='cp3'>".$repi6['segundo_apellido']."</td>
                    <td class='cp3'>".$repi6['nombre']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>CURP</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN CON EL DECLARANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['curp']."</td>
                    <td class='cp3'>".$repi6['rfc_pareja']."</td>
                    <td class='cp3'>".$repi6['relacion_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>¿ES CIUDADANO EXTRANJERO?</td>
                    <td class='cp3'>".$repi6['extranjero']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>¿ES DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>LUGAR DONDE RESIDE</td>
                    <td class='cp3t'>HABITA EN EL DOMICILIO DEL DECLARANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['dependiente']."</td>
                    <td class='cp3'>".$repi6['residencia']."</td>
                    <td class='cp3'>".$repi6['mismo_domicilio']."</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>DOMICILIO DE LA PAREJA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['pais_descr']."</td>
                    <td class='cp3' colspan='2'>".$repi6['calle']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['num_exterior']."</td>
                    <td class='cp3'>".$repi6['num_interior']."</td>
                    <td class='cp3'>".$repi6['colonia_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['municipio_descr']."</td>
                    <td class='cp3'>".$repi6['estado_descr']."</td>
                    <td class='cp3'>".$repi6['codigopostal']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ACTIVIDAD LABORAL</td>
                    <td class='cp3t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp3t'>ÁMBITO PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['actividad_descr']."</td>
                    <td class='cp3'>".$repi6['orden_descr']."</td>
                    <td class='cp3'>".$repi6['ambito_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL ENTE PÚBLICO</td>
                    <td class='cp3t'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp3t'>EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['dependencia_descr']."</td>
                    <td class='cp3'>".$repi6['area_descr']."</td>
                    <td class='cp3'>".$repi6['puesto_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp3t'>SALARIO MENSUAL NETO</td>
                    <td class='cp3t'>FECHA DE INGRESO AL EMPLEO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['funcion_principal']."</td>
                    <td class='cp3'>".$repi6['sueldo_mensual']."</td>
                    <td class='cp3'>".$repi6['fecha_inicio']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿ES PROVEEDOR O CONTRATISTA DEL GOBIERNO?</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['nombre_empresa']."</td>
                    <td class='cp3'>".$repi6['rfc_empresa']."</td>
                    <td class='cp3'>".$repi6['proveedor']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>SECTOR AL QUE PERTENECE</td>
                    <td class='cp3' colspan='2'>".$repi6['sector_descr']."</td>
                  </tr>";}
                  $html6.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi6['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html6.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PRIMER APELLIDO</td>
                    <td class='cp3t'>SEGUNDO APELLIDO</td>
                    <td class='cp3t'>NOMBRE(S)</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>CURP</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN CON EL DECLARANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>¿ES CIUDADANO EXTRANJERO?</td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>¿ES DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>LUGAR DONDE RESIDE</td>
                    <td class='cp3t'>HABITA EN EL DOMICILIO DEL DECLARANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>DOMICILIO DE LA PAREJA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ACTIVIDAD LABORAL</td>
                    <td class='cp3t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp3t'>ÁMBITO PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL ENTE PÚBLICO</td>
                    <td class='cp3t'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp3t'>EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp3t'>SALARIO MENSUAL NETO</td>
                    <td class='cp3t'>FECHA DE INGRESO AL EMPLEO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿ES PROVEEDOR O CONTRATISTA DEL GOBIERNO?</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>SECTOR AL QUE PERTENECE</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html6.="   
                </table>";

                
                $html7="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='4'>APARTADO 7.- DATOS DEL DEPENDIENTE ECONÓMICO</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='4'>TODOS LOS DATOS RELATIVOS A MENORES DE EDAD NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti7)>0){
                  foreach ($reportei7 as $repi7) {
                  $html7.="
                  <tr>
                    <td class='cp4' colspan='4'>TIPO DE MOVIMIENTO: ".$repi7['movimiento']."</td>
                  </tr>";
                  if ($repi7['movimiento']=='NINGUNO'){
                  $html7.="
                  <tr>
                    <td class='cp4t'>NOMBRE(S)</td>
                    <td class='cp4t'>PRIMER APELLIDO</td>
                    <td class='cp4t'>SEGUNDO APELLIDO</td>
                    <td class='cp4t'>FECHA DE NACIMIENTO</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                   <tr>
                    <td class='cp4t'>PARENTESCO O RELACIÓN CON EL DECLARANTE</td>
                    <td class='cp4t'>CURP</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>¿ES CIUDADANO EXTRANJERO?</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>HABITA EN EL DOMICILIO DEL DECLARANTE</td>
                    <td class='cp4t' colspan='2'>LUGAR DONDE RESIDE</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>NO APLICA</td>
                    <td class='cp4' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='4'>DOMICILIO DEL DEPENDIENTE ECONÓMICO</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>PAÍS</td>
                    <td class='cp4' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CALLE</td>
                    <td class='cp4t'>NÚMERO EXTERIOR</td>
                    <td class='cp4t'>NÚMERO INTERIOR</td>
                    <td class='cp4t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>

                  <tr>
                    <td class='cp4t'>ACTIVIDAD LABORAL</td>
                    <td class='cp4t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp4t'>ÁMBITO PÚBLICO</td>
                    <td class='cp4t'>NOMBRE DEL ENTE PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp4t' colspan='2'>EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>NO APLICA</td>
                    <td class='cp4' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp4t'>SALARIO MENSUAL NETO</td>
                    <td class='cp4t'>FECHA DE INGRESO AL EMPLEO</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>¿ES PROVEEDOR O CONTRATISTA DEL GOBIERNO?</td>
                    <td class='cp4t'>SECTOR AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html7.="
                  <tr>
                    <td class='cp4t'>NOMBRE(S)</td>
                    <td class='cp4t'>PRIMER APELLIDO</td>
                    <td class='cp4t'>SEGUNDO APELLIDO</td>
                    <td class='cp4t'>FECHA DE NACIMIENTO</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi7['nombre']."</td>
                    <td class='cp4'>".$repi7['primer_apellido']."</td>
                    <td class='cp4'>".$repi7['segundo_apellido']."</td>
                    <td class='cp4'>".$repi7['fecha_nac']."</td>
                  </tr>
                   <tr>
                    <td class='cp4t'>PARENTESCO O RELACIÓN CON EL DECLARANTE</td>
                    <td class='cp4t'>CURP</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>¿ES CIUDADANO EXTRANJERO?</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi7['relacion_descr']."</td>
                    <td class='cp4'>".$repi7['curp']."</td>
                    <td class='cp4'>".$repi7['rfc_dependiente']."</td>
                    <td class='cp4'>".$repi7['extranjero']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>HABITA EN EL DOMICILIO DEL DECLARANTE</td>
                    <td class='cp4t' colspan='2'>LUGAR DONDE RESIDE</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi7['mismo_domicilio']."</td>
                    <td class='cp4' colspan='2'>".$repi7['residencia']."</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='4'>DOMICILIO DEL DEPENDIENTE ECONÓMICO</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>PAÍS</td>
                    <td class='cp4' colspan='2'>".$repi7['pais_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CALLE</td>
                    <td class='cp4t'>NÚMERO EXTERIOR</td>
                    <td class='cp4t'>NÚMERO INTERIOR</td>
                    <td class='cp4t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi7['calle']."</td>
                    <td class='cp4'>".$repi7['num_exterior']."</td>
                    <td class='cp4'>".$repi7['num_interior']."</td>
                    <td class='cp4'>".$repi7['colonia']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi7['municipio_descr']."</td>
                    <td class='cp4'>".$repi7['estado_descr']."</td>
                    <td class='cp4'>".$repi7['codigopostal']."</td>
                  </tr>

                  <tr>
                    <td class='cp4t'>ACTIVIDAD LABORAL</td>
                    <td class='cp4t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp4t'>ÁMBITO PÚBLICO</td>
                    <td class='cp4t'>NOMBRE DEL ENTE PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi7['actividad_laboral']."</td>
                    <td class='cp4'>".$repi7['orden_descr']."</td>
                    <td class='cp4'>".$repi7['ambito_descr']."</td>
                    <td class='cp4'>".$repi7['dependencia_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp4t' colspan='2'>EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi7['area_descr']."</td>
                    <td class='cp4' colspan='2'>".$repi7['puesto_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp4t'>SALARIO MENSUAL NETO</td>
                    <td class='cp4t'>FECHA DE INGRESO AL EMPLEO</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi7['funcion_principal']."</td>
                    <td class='cp4'>".$repi7['sueldo_mensual']."</td>
                    <td class='cp4'>".$repi7['fecha_inicio']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>¿ES PROVEEDOR O CONTRATISTA DEL GOBIERNO?</td>
                    <td class='cp4t'>SECTOR AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi7['nombre_empresa']."</td>
                    <td class='cp4'>".$repi7['rfc_empresa']."</td>
                    <td class='cp4'>".$repi7['proveedor']."</td>
                    <td class='cp4'>".$repi7['sector_descr']."</td>
                  </tr>";}
                  $html7.="
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'>".$repi7['observaciones']."</td>
                  </tr>";}}
                  else{$html7.="
                  <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>NOMBRE(S)</td>
                    <td class='cp4t'>PRIMER APELLIDO</td>
                    <td class='cp4t'>SEGUNDO APELLIDO</td>
                    <td class='cp4t'>FECHA DE NACIMIENTO</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                   <tr>
                    <td class='cp4t'>PARENTESCO O RELACIÓN CON EL DECLARANTE</td>
                    <td class='cp4t'>CURP</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>¿ES CIUDADANO EXTRANJERO?</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>HABITA EN EL DOMICILIO DEL DECLARANTE</td>
                    <td class='cp4t' colspan='2'>LUGAR DONDE RESIDE</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='4'>DOMICILIO DEL DEPENDIENTE ECONÓMICO</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>PAÍS</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CALLE</td>
                    <td class='cp4t'>NÚMERO EXTERIOR</td>
                    <td class='cp4t'>NÚMERO INTERIOR</td>
                    <td class='cp4t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>

                  <tr>
                    <td class='cp4t'>ACTIVIDAD LABORAL</td>
                    <td class='cp4t'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp4t'>ÁMBITO PÚBLICO</td>
                    <td class='cp4t'>NOMBRE DEL ENTE PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp4t' colspan='2'>EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp4t'>SALARIO MENSUAL NETO</td>
                    <td class='cp4t'>FECHA DE INGRESO AL EMPLEO</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>¿ES PROVEEDOR O CONTRATISTA DEL GOBIERNO?</td>
                    <td class='cp4t'>SECTOR AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'></td>
                  </tr>";}
                 $html7.="                                 
                </table>";
                $html8="<table border='1' cellspacing='0'>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M')
                  {
                    $html8.="<td class='cs' colspan='4'>APARTADO 8.- INGRESOS NETOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (ENTRE EL 1 DE ENERO Y 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</td>";}
                  elseif ($tipo_decl =='C'){
                    $html8.="<td class='cs' colspan='4'>APARTADO 8.- INGRESOS NETOS DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE, PAREJA Y/O DEPENDIENTES ECONÓMICOS</td>";}
                    else{
                    $html8.="<td class='cs' colspan='4'>APARTADO 8.- INGRESOS NETOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SITUACIÓN ACTUAL)</td>";}
                  ############################################################################
                  $html8.="</tr>";
                  if (pg_num_rows($resulti8)>0){
                  foreach ($reportei8 as $repi8) {
                  $html8.="
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M')
                  {
                    $html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN ANUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                elseif ($tipo_decl =='C')
                  {$html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SULDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                else{
                   $html8.=" <td class='cp4t' colspan='3'>I.- REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                    $html8.="<td class='cp4'>".$repi8['remunera_neta']."</td>
                  </tr>
                  <tr>";
                  ###########################################################################
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='I')
                  {
                    $html8.="
                    <td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.4)</td>";}
                    else{
                      $html8.="<td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.5)</td>";}
                  #############################################################################
                    $html8.="<td class='cp4'>".$repi8['otros_ingresos']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['activ_industrial']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>NOMBRE O RAZÓN SOCIAL</td>
                    <td class='cp4' colspan='2'>".$repi8['razon_social']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE NEGOCIO</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_negocio']."</td>
                  </tr>
                 <tr>
                    <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANACIAS) (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['activ_financiera']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANANCIA</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_instrumento']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['serv_profesionales']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_servicio']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.4.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['no_considerados']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGUROS DE VIDA, ETC.)</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_ingreso']."</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html8.="<td class='cp4t' colspan='3'>A.- INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                elseif ($tipo_decl =='C'){
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO DEL DECLARANTE DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN (SUMA DEL NUMERAL I Y II)</td>";}
                else{
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO MENSUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                   ###########################################################################
                   $html8.="<td class='cp4'>".$repi8['ingreso_neto']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='3'>B.- INGRESO MENSUAL NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['ingreso_pareja']."</td>
                  </tr>  
                   <tr>
                    <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS MENSUALES NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SUMA DE LOS APARTADOS A Y B)</td>
                    <td class='cp4'>".$repi8['total_ingresos']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='4'>".$repi8['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html8.="
                    <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M')
                  {
                    $html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN ANUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                elseif ($tipo_decl =='C')
                  {$html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SULDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                else{
                   $html8.=" <td class='cp4t' colspan='3'>I.- REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                   ############################################################################
                    $html8.="
                    <td class='cp4'></td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='I')
                  {
                    $html8.="
                    <td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.4)</td>";}
                    else{
                      $html8.="<td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.5)</td>";
                    }
                    $html8.="
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>NOMBRE O RAZÓN SOCIAL</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE NEGOCIO</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                 <tr>
                    <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANACIAS) (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANANCIA</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.4.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGUROS DE VIDA, ETC.)</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html8.="<td class='cp4t' colspan='3'>A.- INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                elseif ($tipo_decl =='C'){
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO DEL DECLARANTE DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN (SUMA DEL NUMERAL I Y II)</td>";}
                else{
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO MENSUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                   #####################################################################3
                   $html8.="
                    <td class='cp4'></td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='3'>B.- INGRESO MENSUAL NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>  
                   <tr>
                    <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS MENSUALES NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SUMA DE LOS APARTADOS A Y B)</td>
                    <td class='cp4'></td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='4'></td>
                  </tr>";
                  }
                 $html8.="                              
                </table>";
               // Denise Cigarroa 21/08/2020
              //esta sentencia imprime el formulario 9 si la declaración no es modificación 
               if ($tipo_decl <>'M'){

                $html9="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='4'>APARTADO 9.- ¿TE DESEMPEÑASTE COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR?</td>
                  </tr>";

                  if (pg_num_rows($resulti9)>0){
                  foreach ($reportei9 as $repi9) {
                  $html9.="
                  <tr>
                    <td class='cp4' colspan='4'>".$repi9['servidor_anio_prev']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>FECHA DE INICIO</td>
                    <td class='cp4t' colspan='2'>FECHA DE CONCLUSIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi9['fecha_inicio']."</td>
                    <td class='cp4' colspan='2'>".$repi9['fecha_fin']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>INGRESOS NETOS, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR</td>
                  <td class='cp4'>".$repi9['ingreso_neto']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL DECLARANTE, RECIBIDA DURANTE EL TIEMPO QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['remunera_neta']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL II.1 AL II.5)</td>
                  <td class='cp4'>".$repi9['otros_ingresos']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['activ_industrial']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t'>NOMBRE O RAZÓN SOCIAL</td>
                  <td class='cp4'>".$repi9['razon_social']."</td>
                  <td class='cp4t'>TIPO DE NEGOCIO</td>
                  <td class='cp4'>".$repi9['tipo_negocio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANANCIAS)(DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['activ_financiera']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANACIA</td>
                  <td class='cp4' colspan='2'>".$repi9['otro_instrumento']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['serv_profesionales']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                  <td class='cp4' colspan='2'>".$repi9['tipo_servicio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.4.- POR ENAJENACIÓN DE BIENES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['enajena_bienes']."</td>
                  </tr>   
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE BIEN ENAJENADO</td>
                  <td class='cp4' colspan='2'>".$repi9['tipo_descr']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.5.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['no_considerados']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGURO DE VIDA, ETC.)</td>
                  <td class='cp4' colspan='2'>".$repi9['tipo_ingreso']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>A.- INGRESO NETO DEL DECLARANTE, RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL NUMERAL I Y II)</td>
                  <td class='cp4'>".$repi9['ingreso_neto']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>B.- INGRESO NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['ingreso_pareja']."</td>
                  </tr>  
                  <tr>
                  <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS, EN EL AÑO INMEDIATO ANTERIOR (SUMA DE LOS APARTADOS A Y B)</td>
                  <td class='cp4'>".$repi9['total_ingresos']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'>".$repi9['observaciones']."</td>
                  </tr>";}}
                  else{$html9.="
                  <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>FECHA DE INICIO</td>
                    <td class='cp4t' colspan='2'>FECHA DE CONCLUSIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>INGRESOS NETOS, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL DECLARANTE, RECIBIDA DURANTE EL TIEMPO QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL II.1 AL II.5)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t'>NOMBRE O RAZÓN SOCIAL</td>
                  <td class='cp4'></td>
                  <td class='cp4t'>TIPO DE NEGOCIO</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANANCIAS)(DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANACIA</td>
                  <td class='cp4' colspan='2'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                  <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.4.- POR ENAJENACIÓN DE BIENES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>   
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE BIEN ENAJENADO</td>
                  <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.5.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGURO DE VIDA, ETC.)</td>
                  <td class='cp4' colspan='2'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>A.- INGRESO NETO DEL DECLARANTE, RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL NUMERAL I Y II)</td>
                  <td class='cp4'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>B.- INGRESO NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>  
                  <tr>
                  <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS, EN EL AÑO INMEDIATO ANTERIOR (SUMA DE LOS APARTADOS A Y B)</td>
                  <td class='cp4'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'></td>
                  </tr>";}
                 $html9.="                       
                </table>";}

                $html10="<table border='1' cellspacing='0'>
                  <tr>";
                    if ($tipo_decl=='M'){
                      $html10.="<td class='cs' colspan='3'>APARTADO 9.- BIENES INMUEBLES (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</td>";}
                      else{
                    $html10.="<td class='cs' colspan='3'>APARTADO 10.- BIENES INMUEBLES (SITUACIÓN ACTUAL)</td>";}
                  $html10.="</tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE BIENES DECLARADOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PÚBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>BIENES DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti10)>0){
                  foreach ($reportei10 as $repi10) {
                  $html10.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi10['movimiento']."</td>
                  </tr>";
                  if ($repi10['movimiento']=='NINGUNO'){
                    $html10.="
                  <tr>
                    <td class='cp3t'>TIPO DE INMUEBLE</td>
                    <td class='cp3t'>TITULAR DEL INMUEBLE</td>
                    <td class='cp3t'>PORCENTAJE DE PROPIEDAD DEL DECLARANTE CONFORME A ESCRITURACIÓN O CONTRATO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>SUPERFICIE DEL TERRENO</td>
                    <td class='cp3t'>SUPERFICIE DE CONSTRUCCIÓN</td>
                    <td class='cp3t'>TERCERO</td>
                  </tr>      
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>  
                  <tr>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>  
                  <tr>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t'>FORMA DE PAGO</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>                
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DE LA PROPIEDAD CON EL TITULAR </td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR DE LA PROPIEDAD</td>
                    <td class='cp3t'>RFC</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN</td>
                    <td class='cp3t'>¿EL VALOR DE LA ADQUISICIÓN DEL INMUEBLE ES CONFORME A?</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN DEL INMUEBLE</td>
                    <td class='cp3t' colspan='2'>DATOS DEL REGISTRO PÚBLICO DE LA PROPIEDAD FOLIO REAL U OTRO DATO QUE PERMITA SU IDENTIFICACIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cv' colspan='3'>UBICACIÓN DEL INMUEBLE</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>UBICACIÓN</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>EN CASO DE BAJA DEL INMUEBLE INCLUIR MOTIVO</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html10.="
                  <tr>
                    <td class='cp3t'>TIPO DE INMUEBLE</td>
                    <td class='cp3t'>TITULAR DEL INMUEBLE</td>
                    <td class='cp3t'>PORCENTAJE DE PROPIEDAD DEL DECLARANTE CONFORME A ESCRITURACIÓN O CONTRATO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi10['otro_inmueble']."</td>
                    <td class='cp3'>".$repi10['titular_descr']."</td>
                    <td class='cp3'>".$repi10['pct_propiedad']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>SUPERFICIE DEL TERRENO</td>
                    <td class='cp3t'>SUPERFICIE DE CONSTRUCCIÓN</td>
                    <td class='cp3t'>TERCERO</td>
                  </tr>      
                  <tr>
                    <td class='cp3'>".$repi10['sup_terreno']."</td>
                    <td class='cp3'>".$repi10['sup_construc']."</td>
                    <td class='cp3'>".$repi10['tercero_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>  
                  <tr>
                    <td class='cp3' colspan='2'>".$repi10['nombre_tercero']."</td>
                    <td class='cp3'>".$repi10['rfc_tercero']."</td>
                  </tr>  
                  <tr>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t'>FORMA DE PAGO</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>                
                  <tr>
                    <td class='cp3'>".$repi10['adquisicion_descr']."</td>
                    <td class='cp3'>".$repi10['forma_descr']."</td>
                    <td class='cp3'>".$repi10['transmisor_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DE LA PROPIEDAD CON EL TITULAR </td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR DE LA PROPIEDAD</td>
                    <td class='cp3t'>RFC</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>".$repi10['relacion_descr']."</td>
                    <td class='cp3'>".$repi10['nombre_transmisor']."</td>
                    <td class='cp3'>".$repi10['rfc_transmisor']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN</td>
                    <td class='cp3t'>¿EL VALOR DE LA ADQUISICIÓN DEL INMUEBLE ES CONFORME A?</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>".$repi10['valor_adquisicion']."</td>
                    <td class='cp3'>".$repi10['conforme_descr']."</td>
                    <td class='cp3'>".$repi10['tipo_moneda']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN DEL INMUEBLE</td>
                    <td class='cp3t' colspan='2'>DATOS DEL REGISTRO PÚBLICO DE LA PROPIEDAD FOLIO REAL U OTRO DATO QUE PERMITA SU IDENTIFICACIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi10['fecha_adquisicion']."</td>
                    <td class='cp3' colspan='2'>".$repi10['registro_publico']."</td>
                  </tr> 
                  <tr>
                    <td class='cv' colspan='3'>UBICACIÓN DEL INMUEBLE</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>UBICACIÓN</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi10['pais_descr']."</td>
                    <td class='cp3' colspan='2'>".$repi10['calle']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi10['num_exterior']."</td>
                    <td class='cp3'>".$repi10['num_interior']."</td>
                    <td class='cp3'>".$repi10['colonia_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi10['municipio_descr']."</td>
                    <td class='cp3'>".$repi10['estado_descr']."</td>
                    <td class='cp3'>".$repi10['codigopostal']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>EN CASO DE BAJA DEL INMUEBLE INCLUIR MOTIVO</td>
                    <td class='cp3' colspan='2'>".$repi10['causa_baja']."</td>
                  </tr>";}
                  $html10.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi10['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html10.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TIPO DE INMUEBLE</td>
                    <td class='cp3t'>TITULAR DEL INMUEBLE</td>
                    <td class='cp3t'>PORCENTAJE DE PROPIEDAD DEL DECLARANTE CONFORME A ESCRITURACIÓN O CONTRATO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>SUPERFICIE DEL TERRENO</td>
                    <td class='cp3t'>SUPERFICIE DE CONSTRUCCIÓN</td>
                    <td class='cp3t'>TERCERO</td>
                  </tr>      
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>  
                  <tr>
                    <td class='cp3' colspan='2'></td>
                    <td class='cp3'></td>
                  </tr>  
                  <tr>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t'>FORMA DE PAGO</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>                
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DE LA PROPIEDAD CON EL TITULAR </td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR DE LA PROPIEDAD</td>
                    <td class='cp3t'>RFC</td>
                  </tr> 
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN</td>
                    <td class='cp3t'>¿EL VALOR DE LA ADQUISICIÓN DEL INMUEBLE ES CONFORME A?</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                  </tr> 
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN DEL INMUEBLE</td>
                    <td class='cp3t' colspan='2'>DATOS DEL REGISTRO PÚBLICO DE LA PROPIEDAD FOLIO REAL U OTRO DATO QUE PERMITA SU IDENTIFICACIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr> 
                  <tr>
                    <td class='cv' colspan='3'>UBICACIÓN DEL INMUEBLE</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>UBICACIÓN</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>EN CASO DE BAJA DEL INMUEBLE INCLUIR MOTIVO</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html10.=" 
                </table>";
                $html11="<table border='1' cellspacing='0'>
                <tr>";
                // Denise Cigarroa 21/08/2020
               //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html11.="<td class='cs' colspan='3'>APARTADO 10.- VEHÍCULOS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</td>";}
                  else{
                  $html11.="
                    <td class='cs' colspan='3'>APARTADO 11.- VEHÍCULOS (SITUACIÓN ACTUAL) </td>";}
                  ############################################################################
                  $html11.="</tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE VEHÍCULOS DECLARADOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PÚBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>VEHÍCULOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti11)>0){
                  foreach ($reportei11 as $repi11) {
                  $html11.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi11['movimiento']."</td>
                  </tr>";
                  if ($repi11['movimiento']=='NINGUNO'){
                    $html11.="
                  <tr>
                    <td class='cp3t'>TIPO DE VEHÍCULO</td>
                    <td class='cp3t'>TITULAR DEL VEHÍCULO</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DEL VEHÍCULO CON EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>MARCA</td>
                    <td class='cp3t'>MODELO</td>
                    <td class='cp3t'>AÑO</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NÚMERO DE SERIE O REGISTRO</td>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿DONDE SE ENCUENTRA REGISTRADO?</td>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FORMA DE PAGO</td>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN</td>
                    <td class='cp3t'>TPO DE MONEDA</td>
                  </tr>  
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN</td>
                    <td class='cp3t' colspan='2'>EN CASO DE BAJA DEL VEHÍCULO INCLUIR MOTIVO</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html11.="
                  <tr>
                    <td class='cp3t'>TIPO DE VEHÍCULO</td>
                    <td class='cp3t'>TITULAR DEL VEHÍCULO</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi11['tipo_vehiculo']."</td>
                    <td class='cp3'>".$repi11['titular_descr']."</td>
                    <td class='cp3'>".$repi11['transmisor_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DEL VEHÍCULO CON EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi11['nombre_transmisor']."</td>
                    <td class='cp3'>".$repi11['rfc_transmisor']."</td>
                    <td class='cp3'>".$repi11['relacion_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>MARCA</td>
                    <td class='cp3t'>MODELO</td>
                    <td class='cp3t'>AÑO</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>".$repi11['marca']."</td>
                    <td class='cp3'>".$repi11['modelo']."</td>
                    <td class='cp3'>".$repi11['anio']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NÚMERO DE SERIE O REGISTRO</td>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>".$repi11['serie']."</td>
                    <td class='cp3'>".$repi11['tercero_descr']."</td>
                    <td class='cp3'>".$repi11['nombre_tercero']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿DONDE SE ENCUENTRA REGISTRADO?</td>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi11['rfc_tercero']."</td>
                    <td class='cp3'>".$repi11['ubicacion']."</td>
                    <td class='cp3'>".$repi11['adquisicion_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FORMA DE PAGO</td>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN</td>
                    <td class='cp3t'>TPO DE MONEDA</td>
                  </tr>  
                  <tr>
                    <td class='cp3'>".$repi11['forma_descr']."</td>
                    <td class='cp3'>".$repi11['valor_adquisicion']."</td>
                    <td class='cp3'>".$repi11['tipo_moneda']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN</td>
                    <td class='cp3t' colspan='2'>EN CASO DE BAJA DEL VEHÍCULO INCLUIR MOTIVO</td>
                  </tr> 
                  <tr>
                    <td class='cp3'>".$repi11['fecha_adquisicion']."</td>
                    <td class='cp3' colspan='2'>".$repi11['causa_baja']."</td>
                  </tr>";}
                  $html11.=" 
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi11['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html11.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TIPO DE VEHÍCULO</td>
                    <td class='cp3t'>TITULAR DEL VEHÍCULO</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DEL VEHÍCULO CON EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>MARCA</td>
                    <td class='cp3t'>MODELO</td>
                    <td class='cp3t'>AÑO</td>
                  </tr> 
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NÚMERO DE SERIE O REGISTRO</td>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                  </tr> 
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿DONDE SE ENCUENTRA REGISTRADO?</td>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FORMA DE PAGO</td>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN</td>
                    <td class='cp3t'>TPO DE MONEDA</td>
                  </tr>  
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN</td>
                    <td class='cp3t' colspan='2'>EN CASO DE BAJA DEL VEHÍCULO INCLUIR MOTIVO</td>
                  </tr> 
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html11.=" 
                </table>";
                $html12="<table border='1' cellspacing='0'>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                  //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                     $html12.="<td class='cs' colspan='3'>APARTADO 11.- BIENES MUEBLES (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</td>";}
                    else{
                  $html12.="
                    <td class='cs' colspan='3'>APARTADO 12.- BIENES MUEBLES (SITUACIÓN ACTUAL)</td>";}
                    ##################################################################3
                  $html12.="</tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LOS BIENES DECLARADOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PÚBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>BIENES DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";

                  if (pg_num_rows($resulti12)>0){
                  foreach ($reportei12 as $repi12) {
                  $html12.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi12['movimiento']."</td>
                  </tr>";
                  if ($repi12['movimiento']=='NINGUNO'){
                    $html12.="
                  <tr>
                    <td class='cp3t'>TITULAR DEL BIEN</td>
                    <td class='cp3t'>TIPO DE BIEN</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DEL MUEBLE CON EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                    </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>DESCRIPCIÓN DEL BIEN</td>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t'>FORMA DE PAGO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN DEL MUEBLE</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>EN CASO DE BAJA DEL MUEBLE INCLUIR MOTIVO</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html12.="
                  <tr>
                    <td class='cp3t'>TITULAR DEL BIEN</td>
                    <td class='cp3t'>TIPO DE BIEN</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi12['titular_descr']."</td>
                    <td class='cp3'>".$repi12['tipo_descr']."</td>
                    <td class='cp3'>".$repi12['transmisor_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DEL MUEBLE CON EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi12['nombre_transmisor']."</td>
                    <td class='cp3'>".$repi12['rfc_transmisor']."</td>
                    <td class='cp3'>".$repi12['relacion_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                    </tr>
                  <tr>
                    <td class='cp3'>".$repi12['tercero_descr']."</td>
                    <td class='cp3'>".$repi12['nombre_tercero']."</td>
                    <td class='cp3'>".$repi12['rfc_tercero']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>DESCRIPCIÓN DEL BIEN</td>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t'>FORMA DE PAGO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi12['descripcion']."</td>
                    <td class='cp3'>".$repi12['adquisicion_descr']."</td>
                    <td class='cp3'>".$repi12['forma_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN DEL MUEBLE</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi12['valor_adquisicion']."</td>
                    <td class='cp3'>".$repi12['tipo_moneda']."</td>
                    <td class='cp3'>".$repi12['fecha_adquisicion']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>EN CASO DE BAJA DEL MUEBLE INCLUIR MOTIVO</td>
                    <td class='cp3' colspan='2'>".$repi12['causa_baja']."</td>
                  </tr>";}
                  $html12.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi12['observaciones']."</td>
                  </tr>";}}
                  else {
                    $html12.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TITULAR DEL BIEN</td>
                    <td class='cp3t'>TIPO DE BIEN</td>
                    <td class='cp3t'>TRANSMISOR</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL TRANSMISOR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN DEL TRANSMISOR DEL MUEBLE CON EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                    </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>DESCRIPCIÓN DEL BIEN</td>
                    <td class='cp3t'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t'>FORMA DE PAGO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VALOR DE ADQUISICIÓN DEL MUEBLE</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>EN CASO DE BAJA DEL MUEBLE INCLUIR MOTIVO</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html12.="  
                </table>";
                $html13="<table border='1' cellspacing='0'>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html13.="<td class='cs' colspan='3'>APARTADO 12.- INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES / ACTIVOS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</td>";}
                  else{
                $html13.="<td class='cs' colspan='3'>APARTADO 13.- INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES / ACTIVOS (SITUACIÓN ACTUAL)</td>";}
                ###############################################################################
                  $html13.="</tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LAS INVERSIONES, CUENTAS BANCARIAS Y OTROS TIPOS DE VALORES / ACTIVOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PÚBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti13)>0){
                  foreach ($reportei13 as $repi13) {
                  $html13.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi13['movimiento']."</td>
                  </tr>";
                  if ($repi13['movimiento']=='NINGUNO'){
                    $html13.="
                  <tr>
                    <td class='cp3t'>TIPO DE INVERSIÓN / ACTIVO</td>
                    <td class='cp3t'>TITULAR DE LA INVERSIÓN, CUENTA BANCARIA Y OTRO TIPO DE VALORES</td>
                    <td class='cp3t'>BANCARIA</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                 <tr>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>FONDOS DE INVERSIÓN</td>
                    <td class='cp3t'>ORGANIZACIONES PRIVADAS Y / O MERCANTILES</td>
                    <td class='cp3t'>POSESIONES DE MONEDAS Y / O METALES</td>
                  </tr>
                   <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>SEGUROS</td>
                    <td class='cp3t'>VALORES BURSÁTILES</td>
                    <td class='cp3t'>AFORES Y OTROS </td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>NÚMERO DE CUENTA, CONTRATO O PÓLIZA</td>
                    <td class='cp3t'>¿DONDE SE LOCALIZA LA INVERSIÓN, CUENTA BANCARIA U OTRO TIPO DE VALORES / ACTIVOS?</td>
                    <td class='cp3t'>INSTITUCIÓN O RAZÓN SOCIAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t' colspan='2'>PAÍS DONDE SE LOCALIZA</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración para imprimir
                    if ($tipo_decl =='M'){
                      $html13.="<td class='cp3t' colspan='2'>SALDO AL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR</td>";}
                      else{
                    $html13.="<td class='cp3t' colspan='2'>SALDO A LA FECHA (SITUACIÓN ACTUAL)</td>";}
                    ###########################################################################
                    $html13.="<td class='cp3t'>TIPO DE MONEDA</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html13.="
                  <tr>
                    <td class='cp3t'>TIPO DE INVERSIÓN / ACTIVO</td>
                    <td class='cp3t'>TITULAR DE LA INVERSIÓN, CUENTA BANCARIA Y OTRO TIPO DE VALORES</td>
                    <td class='cp3t'>BANCARIA</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi13['tipo_inver_descr']."</td>
                    <td class='cp3'>".$repi13['titular_descr']."</td>
                    <td class='cp3'>".$repi13['bancaria_descr']."</td>
                  </tr> 
                 <tr>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi13['tercero_descr']."</td>
                    <td class='cp3'>".$repi13['nombre_tercero']."</td>
                    <td class='cp3'>".$repi13['rfc_tercero']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>FONDOS DE INVERSIÓN</td>
                    <td class='cp3t'>ORGANIZACIONES PRIVADAS Y / O MERCANTILES</td>
                    <td class='cp3t'>POSESIONES DE MONEDAS Y / O METALES</td>
                  </tr>
                   <tr>
                    <td class='cp3'>".$repi13['fondo_descr']."</td>
                    <td class='cp3'>".$repi13['org_descr']."</td>
                    <td class='cp3'>".$repi13['monedas_descr']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>SEGUROS</td>
                    <td class='cp3t'>VALORES BURSÁTILES</td>
                    <td class='cp3t'>AFORES Y OTROS </td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi13['seguros_descr']."</td>
                    <td class='cp3'>".$repi13['valor_descr']."</td>
                    <td class='cp3'>".$repi13['afores_descr']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>NÚMERO DE CUENTA, CONTRATO O PÓLIZA</td>
                    <td class='cp3t'>¿DONDE SE LOCALIZA LA INVERSIÓN, CUENTA BANCARIA U OTRO TIPO DE VALORES / ACTIVOS?</td>
                    <td class='cp3t'>INSTITUCIÓN O RAZÓN SOCIAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi13['num_cta']."</td>
                    <td class='cp3'>".$repi13['ubicacion']."</td>
                    <td class='cp3'>".$repi13['razon_social']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t' colspan='2'>PAÍS DONDE SE LOCALIZA</td>
                    
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi13['rfc_institucion']."</td>
                    <td class='cp3' colspan='2'>".$repi13['pais_descr']."</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                    if ($tipo_decl =='M'){
                      $html13.="<td class='cp3t' colspan='2'>SALDO AL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR</td>";}
                      else{
                    $html13.="<td class='cp3t' colspan='2'>SALDO A LA FECHA (SITUACIÓN ACTUAL)</td>";}
                    #######################################################################
                    $html13.="<td class='cp3t'>TIPO DE MONEDA</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>".$repi13['saldo']."</td>
                    <td class='cp3'>".$repi13['tipo_moneda']."</td>
                  </tr>";}
                  $html13.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi13['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html13.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TIPO DE INVERSIÓN / ACTIVO</td>
                    <td class='cp3t'>TITULAR DE LA INVERSIÓN, CUENTA BANCARIA Y OTRO TIPO DE VALORES</td>
                    <td class='cp3t'>BANCARIA</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                 <tr>
                    <td class='cp3t'>TERCERO</td>
                    <td class='cp3t'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>FONDOS DE INVERSIÓN</td>
                    <td class='cp3t'>ORGANIZACIONES PRIVADAS Y / O MERCANTILES</td>
                    <td class='cp3t'>POSESIONES DE MONEDAS Y / O METALES</td>
                  </tr>
                   <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>SEGUROS</td>
                    <td class='cp3t'>VALORES BURSÁTILES</td>
                    <td class='cp3t'>AFORES Y OTROS </td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>NÚMERO DE CUENTA, CONTRATO O PÓLIZA</td>
                    <td class='cp3t'>¿DONDE SE LOCALIZA LA INVERSIÓN, CUENTA BANCARIA U OTRO TIPO DE VALORES / ACTIVOS?</td>
                    <td class='cp3t'>INSTITUCIÓN O RAZÓN SOCIAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t' colspan='2'>PAÍS DONDE SE LOCALIZA</td>
                    
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                    if ($tipo_decl =='M'){
                      $html13.="<td class='cp3t' colspan='2'>SALDO AL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR</td>";}
                      else{
                    $html13.="<td class='cp3t' colspan='2'>SALDO A LA FECHA (SITUACIÓN ACTUAL)</td>";}
                    ############################################################################
                    $html13.="<td class='cp3t'>TIPO DE MONEDA</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html13.=" 
                </table>";
                $html14="<table border='1' cellspacing='0'>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html14.="<td class='cs' colspan='3'>APARTADO 13.- ADEUDOS / PASIVOS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</td>";}
                    else{
                  $html14.="<td class='cs' colspan='3'>APARTADO 14.- ADEUDOS / PASIVOS (SITUACIÓN ACTUAL)</td>";}
                  #############################################################################
                  $html14.="</tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LOS ADEUDOS / PASIVOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PÚBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>ADEUDOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti14)>0){
                  foreach ($reportei14 as $repi14) {
                  $html14.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi14['movimiento']."</td>
                  </tr>";
                  if ($repi14['movimiento']=='NINGUNO'){
                    $html14.="
                  <tr>
                    <td class='cp3t' colspan='2'>TITULAR DEL ADEUDO</td>
                    <td class='cp3t'>TIPO DE ADEUDO</td>
                    
                  </tr>
                  <tr>
                    <td class='cp3' colspan ='2'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                 <tr>
                    <td class='cp3t'>NÚMERO DE CUENTA O CONTRATO</td>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN DEL ADEUDO / PASIVO</td>
                    <td class='cp3t'>MONTO ORIGINAL DEL ADEUDO / PASIVO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                    <td class='cp3t'>SALDO INSOLUTO (SITUACIÓN ACTUAL)</td>
                    <td class='cp3t'>TERCERO</td>
                  </tr>
                   <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>OTORGANTE DEL CRÉDITO</td>
                    <td class='cp3t'>NOMBRE / INSTITUCIÓN O RAZÓN SOCIAL</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                   <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS DONDE SE LOCALIZA EL ADEUDO</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html14.="
                  <tr>
                    <td class='cp3t' colspan='2'>TITULAR DEL ADEUDO</td>
                    <td class='cp3t'>TIPO DE ADEUDO</td>
                    
                  </tr>
                  <tr>
                    <td class='cp3' colspan ='2'>".$repi14['titular_descr']."</td>
                    <td class='cp3'>".$repi14['tipo_adeudo_descr']."</td>
                  </tr> 
                 <tr>
                    <td class='cp3t'>NÚMERO DE CUENTA O CONTRATO</td>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN DEL ADEUDO / PASIVO</td>
                    <td class='cp3t'>MONTO ORIGINAL DEL ADEUDO / PASIVO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi14['num_cta']."</td>
                    <td class='cp3'>".$repi14['fecha_adquisicion']."</td>
                    <td class='cp3'>".$repi14['monto_original']."</td>
                  </tr>
                   <tr>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                    <td class='cp3t'>SALDO INSOLUTO (SITUACIÓN ACTUAL)</td>
                    <td class='cp3t'>TERCERO</td>
                  </tr>
                   <tr>
                    <td class='cp3'>".$repi14['tipo_moneda']."</td>
                    <td class='cp3'>".$repi14['saldo']."</td>
                    <td class='cp3'>".$repi14['tercero_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>".$repi14['nombre_tercero']."</td>
                    <td class='cp3'>".$repi14['rfc_tercero']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>OTORGANTE DEL CRÉDITO</td>
                    <td class='cp3t'>NOMBRE / INSTITUCIÓN O RAZÓN SOCIAL</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                   <tr>
                    <td class='cp3'>".$repi14['otorgante_descr']."</td>
                    <td class='cp3'>".$repi14['razon_social']."</td>
                    <td class='cp3'>".$repi14['rfc_institucion']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS DONDE SE LOCALIZA EL ADEUDO</td>
                    <td class='cp3' colspan='2'>".$repi14['pais_descr']."</td>
                  </tr>";}
                  $html14.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi14['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html14.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>TITULAR DEL ADEUDO</td>
                    <td class='cp3t'>TIPO DE ADEUDO</td>
                    
                  </tr>
                  <tr>
                    <td class='cp3' colspan ='2'></td>
                    <td class='cp3'></td>
                  </tr> 
                 <tr>
                    <td class='cp3t'>NÚMERO DE CUENTA O CONTRATO</td>
                    <td class='cp3t'>FECHA DE ADQUISICIÓN DEL ADEUDO / PASIVO</td>
                    <td class='cp3t'>MONTO ORIGINAL DEL ADEUDO / PASIVO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                    <td class='cp3t'>SALDO INSOLITO (SITUACIÓN ACTUAL)</td>
                    <td class='cp3t'>TERCERO</td>
                  </tr>
                   <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>OTORGANTE DEL CREDITO</td>
                    <td class='cp3t'>NOMBRE / INSTITUCIÓN O RAZÓN SOCIAL</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                   <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS DONDE SE LOCALIZA EL ADEUDO</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html14.=" 
                </table>";
                $html15="<table border='1' cellspacing='0'>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html15.="<td class='cs' colspan='3'>APARTADO 14.- PRÉSTAMO O COMODATO POR TERCEROS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</td>";}
                    else{
                   $html15.="<td class='cs' colspan='3'>APARTADO 15.- PRÉSTAMO O COMODATO POR TERCEROS (SITUACIÓN ACTUAL)</td>";}
                   ############################################################################
                  $html15.="</tr>";

                  if (pg_num_rows($resulti15)>0){
                  foreach ($reportei15 as $repi15) {
                  $html15.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi15['movimiento']."</td>
                  </tr>";
                  if ($repi15['movimiento']=='NINGUNO'){
                    $html15.="
                  <tr>
                    <td class='cp3t'>TIPO DE BIEN.</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>INMUEBLE</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>UBICACIÓN DEL INMUEBLE</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VEHÍCULO</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MARCA</td>
                    <td class='cp3t'>MODELO</td>
                    <td class='cp3t'>AÑO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO DE SERIE O REGISTRO</td>
                    <td class='cp3t'>¿DONDE SE ENCUENTRA REGISTRADO?</td>
                    <td class='cp3t'>DUEÑO O TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL DUEÑO O TITULAR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN CON EL DUEÑO O EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>";}
                    else{
                  $html15.="
                  <tr>
                    <td class='cp3t'>TIPO DE BIEN.</td>
                    <td class='cp3' colspan='2'>".$repi15['tipo_comodato']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>INMUEBLE</td>
                    <td class='cp3' colspan='2'>".$repi15['tipo_inmueble']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>UBICACIÓN DEL INMUEBLE</td>
                    <td class='cp3' colspan='2'>".$repi15['ubicacion']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi15['pais_descr']."</td>
                    <td class='cp3' colspan='2'>".$repi15['calle']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi15['num_exterior']."</td>
                    <td class='cp3'>".$repi15['num_interior']."</td>
                    <td class='cp3'>".$repi15['colonia_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi15['municipio_descr']."</td>
                    <td class='cp3'>".$repi15['estado_descr']."</td>
                    <td class='cp3'>".$repi15['codigopostal']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VEHÍCULO</td>
                    <td class='cp3' colspan='2'>".$repi15['tipo_vehiculo']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MARCA</td>
                    <td class='cp3t'>MODELO</td>
                    <td class='cp3t'>AÑO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi15['marca']."</td>
                    <td class='cp3'>".$repi15['modelo']."</td>
                    <td class='cp3'>".$repi15['anio']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO DE SERIE O REGISTRO</td>
                    <td class='cp3t'>¿DONDE SE ENCUENTRA REGISTRADO?</td>
                    <td class='cp3t'>DUEÑO O TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi15['serie']."</td>
                    <td class='cp3'>".$repi15['ubicacion']."</td>
                    <td class='cp3'>".$repi15['dueno_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL DUEÑO O TITULAR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN CON EL DUEÑO O EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi15['nombre_dueno']."</td>
                    <td class='cp3'>".$repi15['rfc_dueno']."</td>
                    <td class='cp3'>".$repi15['relacion_descr']."</td>
                  </tr>";}
                  $html15.="
                   <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi15['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html15.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TIPO DE BIEN.</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>INMUEBLE</td>
                    <td class='cp3' colspan='2'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>UBICACIÓN DEL INMUEBLE</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PAÍS</td>
                    <td class='cp3t' colspan='2'>CALLE</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO EXTERIOR</td>
                    <td class='cp3t'>NÚMERO INTERIOR</td>
                    <td class='cp3t'>COLONIA / LOCALIDAD</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp3t'>ENTIDAD FEDERATIVA</td>
                    <td class='cp3t'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>VEHÍCULO</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>MARCA</td>
                    <td class='cp3t'>MODELO</td>
                    <td class='cp3t'>AÑO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NÚMERO DE SERIE O REGISTRO</td>
                    <td class='cp3t'>¿DONDE SE ENCUENTRA REGISTRADO?</td>
                    <td class='cp3t'>DUEÑO O TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE DEL DUEÑO O TITULAR</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>RELACIÓN CON EL DUEÑO O EL TITULAR</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                   <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'></td>
                  </tr>";
                  }
                 $html15.="  
                </table>
            </main>
    </body>";

  $mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html1);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html2);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html3);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html4);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');

  $mpdf->AddPage();
  $mpdf->writeHtml($html5);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html6);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html7);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html8);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  // Denise Cigarroa 21/08/2020
  //Este apartado se imprimen cuando la declaración no es de modificación
  if ($tipo_decl <>'M'){
  $mpdf->AddPage();
  $mpdf->writeHtml($html9);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');}
  $mpdf->AddPage();
  $mpdf->writeHtml($html10);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html11);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html12);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html13);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html14);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html15);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO} FIN');
}
// Denise Cigarroa 21/08/2020
//Esta sentencia se cumple para las declaraciones parciales
else {

  $html8="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='4'>APARTADO 6.- INGRESOS NETOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti8)>0){
                  foreach ($reportei8 as $repi8) {
                  $html8.="
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M')
                  {
                    $html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN ANUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                elseif ($tipo_decl =='C')
                  {$html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SULDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                else{
                   $html8.=" <td class='cp4t' colspan='3'>I.- REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                   ############################################################################
                    $html8.="
                    <td class='cp4'>".$repi8['remunera_neta']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.- OTROS INGRESOS MENSUALES DEL DECLARANTE (SUMA DEL II.1 AL II.4)</td>
                    <td class='cp4'>".$repi8['otros_ingresos']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['activ_industrial']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>NOMBRE O RAZÓN SOCIAL</td>
                    <td class='cp4' colspan='2'>".$repi8['razon_social']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE NEGOCIO</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_negocio']."</td>
                  </tr>
                 <tr>
                    <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANACIAS) (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['activ_financiera']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANANCIA</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_instrumento']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['serv_profesionales']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_servicio']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.4.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['no_considerados']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGUROS DE VIDA, ETC.)</td>
                    <td class='cp4' colspan='2'>".$repi8['tipo_ingreso']."</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html8.="<td class='cp4t' colspan='3'>A.- INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                elseif ($tipo_decl =='C'){
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO DEL DECLARANTE DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN (SUMA DEL NUMERAL I Y II)</td>";}
                else{
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO MENSUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                   #############################################################################
                   $html8.="
                    <td class='cp4'>".$repi8['ingreso_neto']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='3'>B.- INGRESO MENSUAL NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'>".$repi8['ingreso_pareja']."</td>
                  </tr>  
                   <tr>
                    <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS MENSUALES NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SUMA DE LOS APARTADOS A Y B)</td>
                    <td class='cp4'>".$repi8['total_ingresos']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='4'>".$repi8['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html8.="
                    <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M')
                  {
                    $html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN ANUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                elseif ($tipo_decl =='C')
                  {$html8.="<td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SULDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                else{
                   $html8.=" <td class='cp4t' colspan='3'>I.- REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                   #############################################################################
                    $html8.="
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.- OTROS INGRESOS MENSUALES DEL DECLARANTE (SUMA DEL II.1 AL II.4)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>NOMBRE O RAZÓN SOCIAL</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE NEGOCIO</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                 <tr>
                    <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANACIAS) (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANANCIA</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='3'>II.4.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGUROS DE VIDA, ETC.)</td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html8.="<td class='cp4t' colspan='3'>A.- INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                elseif ($tipo_decl =='C'){
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO DEL DECLARANTE DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN (SUMA DEL NUMERAL I Y II)</td>";}
                else{
                   $html8.="<td class='cp4t' colspan='3'>A.- INGRESO MENSUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                   ###########################################################################
                   $html8.="<td class='cp4'></td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='3'>B.- INGRESO MENSUAL NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4'></td>
                  </tr>  
                   <tr>
                    <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS MENSUALES NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SUMA DE LOS APARTADOS A Y B)</td>
                    <td class='cp4'></td>
                  </tr> 
                  <tr>
                    <td class='cp4t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='4'></td>
                  </tr>";
                  }
                 $html8.="                              
                </table>";
                // Denise Cigarroa 21/08/2020
              //Esta sentencia se cumple si la declaración no es de modificación
                if ($tipo_decl <>'M'){
                $html9="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='4'>APARTADO 7.- ¿TE DESEMPEÑASTE COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR?</td>
                  </tr>";

                  if (pg_num_rows($resulti9)>0){
                  foreach ($reportei9 as $repi9) {
                  $html9.="
                  <tr>
                    <td class='cp4' colspan='4'>".$repi9['servidor_anio_prev']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>FECHA DE INICIO</td>
                    <td class='cp4t' colspan='2'>FECHA DE CONCLUSIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'>".$repi9['fecha_inicio']."</td>
                    <td class='cp4' colspan='2'>".$repi9['fecha_fin']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>INGRESOS NETOS, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR</td>
                  <td class='cp4'>".$repi9['ingreso_neto']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL DECLARANTE, RECIBIDA DURANTE EL TIEMPO QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['remunera_neta']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL II.1 AL II.5)</td>
                  <td class='cp4'>".$repi9['otros_ingresos']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['activ_industrial']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t'>NOMBRE O RAZÓN SOCIAL</td>
                  <td class='cp4'>".$repi9['razon_social']."</td>
                  <td class='cp4t'>TIPO DE NEGOCIO</td>
                  <td class='cp4'>".$repi9['tipo_negocio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANANCIAS)(DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['activ_financiera']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANACIA</td>
                  <td class='cp4' colspan='2'>".$repi9['otro_instrumento']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['serv_profesionales']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                  <td class='cp4' colspan='2'>".$repi9['tipo_servicio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.4.- POR ENAJENACIÓN DE BIENES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['enajena_bienes']."</td>
                  </tr>   
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE BIEN ENAJENADO</td>
                  <td class='cp4' colspan='2'>".$repi9['tipo_descr']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.5.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['no_considerados']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGURO DE VIDA, ETC.)</td>
                  <td class='cp4' colspan='2'>".$repi9['tipo_ingreso']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>A.- INGRESO NETO DEL DECLARANTE, RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL NUMERAL I Y II)</td>
                  <td class='cp4'>".$repi9['ingreso_neto']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>B.- INGRESO NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'>".$repi9['ingreso_pareja']."</td>
                  </tr>  
                  <tr>
                  <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS, EN EL AÑO INMEDIATO ANTERIOR (SUMA DE LOS APARTADOS A Y B)</td>
                  <td class='cp4'>".$repi9['total_ingresos']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'>".$repi9['observaciones']."</td>
                  </tr>";}}
                  else{$html9.="
                  <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='2'>FECHA DE INICIO</td>
                    <td class='cp4t' colspan='2'>FECHA DE CONCLUSIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='2'></td>
                    <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>INGRESOS NETOS, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>I.- REMUNERACIÓN NETA DEL DECLARANTE, RECIBIDA DURANTE EL TIEMPO QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑÓ COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL II.1 AL II.5)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t'>NOMBRE O RAZÓN SOCIAL</td>
                  <td class='cp4'></td>
                  <td class='cp4t'>TIPO DE NEGOCIO</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANANCIAS)(DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANACIA</td>
                  <td class='cp4' colspan='2'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                  <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.4.- POR ENAJENACIÓN DE BIENES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>   
                  <tr>
                  <td class='cp4t' colspan='2'>TIPO DE BIEN ENAJENADO</td>
                  <td class='cp4' colspan='2'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='3'>II.5.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>
                  <tr>
                  <td class='cp4t' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGURO DE VIDA, ETC.)</td>
                  <td class='cp4' colspan='2'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>A.- INGRESO NETO DEL DECLARANTE, RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL NUMERAL I Y II)</td>
                  <td class='cp4'></td>
                  </tr> 
                  <tr>
                  <td class='cp4t' colspan='3'>B.- INGRESO NETO DE LA PAREJA Y / O DEPENDIENTES ECONÓMICOS RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4'></td>
                  </tr>  
                  <tr>
                  <td class='cp4t' colspan='3'>C.- TOTAL DE INGRESOS NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS, EN EL AÑO INMEDIATO ANTERIOR (SUMA DE LOS APARTADOS A Y B)</td>
                  <td class='cp4'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'></td>
                  </tr>";}
                 $html9.="                       
                </table>";}
                $html9.=" </main>
    </body>";

  $mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html1);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html2);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html3);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html4);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html5);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->AddPage();
  $mpdf->writeHtml($html8);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  // Denise Cigarroa 21/08/2020
  //Este formulario se imprime cunado la declaración no es de modificación
  if ($tipo_decl <>'M'){
  $mpdf->AddPage();
  $mpdf->writeHtml($html9);
   $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO} FIN' );}} 
  $mpdf->Output($td.$ti.$rfc.$ejercicio.'DA.pdf','I');