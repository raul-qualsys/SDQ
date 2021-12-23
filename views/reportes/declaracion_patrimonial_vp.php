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
###################################################################
#Denise Cigarroa Rodriguez 21/08/2020# 
#Esta es una variable global que contiene la dirección de la empresa em turno#
$direccion = CALLE_EMPRESA." ".EXT_EMPRESA." ".COL_COMPLETE." ".MUN_COMPLETE."<br>".CP_EMPRESA." ".EST_COMPLETE;
#######################################################################
if(isset($_POST["rfc"])){
   $rfc = $_POST['rfc'];
   $ejercicio = $_POST['ejercicio'];
   $tipo_decl=$_POST['tipo_decl'];
   $declara_completo=$_POST['declara_completo'];
 }
 else{
   $rfc = $_GET['rfc'];
   $ejercicio = $_GET['ejercicio'];  
   $tipo_decl=$_GET['tipo_decl'];
   $declara_completo=$_GET['declara_completo'];
 }
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


  $sqlo = "SELECT *
  FROM qsy_declaraciones
  WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
  $resulo=pg_query($conn,$sqlo);
  $reporo=pg_fetch_all($resulo);


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
when 'N' then 'NO'
when 'S' then 'SI' END AS servidor_anio_prev
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
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento 
FROM qsy_muebles WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
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
              <br>";

               if (pg_num_rows($resulti1)>0){
                $html1.="
                <table>
                  <tr>
                    <td class='rpt'>
                      ".$reportei1[0]['nombre'].' '.$reportei1[0]['primer_ap'].' '.$reportei1[0]['segundo_ap']."
                    </td>
                  </tr> 
                  <tr><td class='rpt'>TIPO DE DECLARACIÓN: $tipdecl</td></tr>
                  <tr><td class='rpt'>
                      FECHA DE LA DECLARACIÓN: ".$reporo[0]['fecha_presenta']." 
                    </td></tr>
                </table>
                <table>
                  <tr>
                    <td class='rps' colspan='2'>DATOS GENERALES DEL SERVIDOR PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cpt' colspan='2'>NOMBRE(S):</td>
                    <td class='cpt' colspan='2'> ".$reportei1[0]['nombre'].' '.$reportei1[0]['primer_ap'].' '.$reportei1[0]['segundo_ap']."</td>
                  </tr> 
                  <tr>
                    <td class='cpt' colspan='2'>CORREO ELECTRÓNICO INSTITUCIONAL:</td>
                    <td class='cpt' colspan='2'>".$reportei1[0]['email_institucional']."</td>
                  </tr> 
                </table>";}
                 else{
                  $html1.="
                  <table>
                  <tr>
                    <td class='rpt'>
                      SIN INFORMACIÓN
                    </td>
                  </tr>
                  </table>"; 
                 }
                $html1.="
                <table>
                  <caption class='rps'>DATOS CURRICULARES DEL DECLARANTE
                  </caption>";
                  if (pg_num_rows($resulti3)>0){
                  foreach ($reportei3 as $repi3) {
                  $html1.="
                  <tr>
                    <td class='cp4t_nv'>NIVEL ESCOLAR</td>
                    <td class='cp4t_nv'>INSTITUCIÓN EDUCATIVA</td>
                    <td class='cp4t_nv'>CARRERA O ÁREA DE CONOCIMIENTO</td>
                    <td class='cp4t_nv'>UBICACIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi3['nivel_escolar']."</td>
                    <td class='cp4_nv'>".$repi3['institucion']."</td>
                    <td class='cp4_nv'>".$repi3['carrera']."</td>
                    <td class='cp4_nv' colspan='2'>".$repi3['ubicacion']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv'>ESTATUS</td>
                    <td class='cp4t_nv'>DOCUMENTO OBTENIDO</td>
                    <td class='cp4t_nv'>FECHA DE OBTENCIÓN DEL DOCUMENTO</td>
                    <td class='cp4t_nv'></td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi3['estatus_estudio']."</td>
                    <td class='cp4_nv'>".$repi3['doc_obtenido']."</td>
                    <td class='cp4_nv'>".$repi3['fecha_doc']."</td>
                    <td class='cp4_nv'></td>                  
                  </tr>
                   ";}}
                   else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";}
                 $html1.="                            
                </table>

                <table>";
                // Denise Cigarroa 21/08/2020
              //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M') {
                   $html1.="<caption class='rps'>DATOS DEL EMPLEO, CARGO O COMISIÓN ACTUAL
                  </caption>";}
                  elseif ($tipo_decl =='C'){
                   $html1.="<caption class='rps'>DATOS DEL EMPLEO, CARGO O COMISIÓN QUE CONCLUYE
                  </caption>";}
                    else{
                 $html1.="<caption class='rps'>DATOS DEL EMPLEO, CARGO O COMISIÓN QUE INICIA
                  </caption>";}
                  if (pg_num_rows($resulti4)>0){
                  foreach ($reportei4 as $repi4) {
                  $html1.="
                  <tr>
                    <td class='cp4t_nv'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp4t_nv'>ÁMBITO PÚBLICO</td>
                    <td class='cp4t_nv' colspan='2'>NOMBRE DEL ENTE PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi4['orden_descr']."</td> 
                    <td class='cp4_nv'>".$repi4['ambito_descr']."</td> 
                    <td class='cp4_nv' colspan='2'>".$repi4['dependencia_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv'>ÁREA DE ADSCRIPCIÓN</td>
                    <td class='cp4t_nv'>EMPLEO, CARGO O COMISIÓN</td>
                    <td class='cp4t_nv'>¿ESTA CONTRATADO POR HONORARIOS?</td> 
                    <td class='cp4t_nv'>NIVEL DEL EMPLEO, CARGO O COMISIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi4['area_descr']."</td>
                    <td class='cp4_nv'>".$repi4['puesto_descr']."</td>
                    <td class='cp4_nv'>".$repi4['honorarios']."</td> 
                    <td class='cp4_nv'>".$repi4['nivel_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='4'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv' colspan='4'>".$repi4['funcion_principal']."</td> 
                  </tr>
                  <tr>";

                  if ($tipo_decl =='C'){
                    $html1.="<td class='cp4t_nv' colspan='2'>FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN</td>";}
                    else{
                     $html1.="<td class='cp4t_nv' colspan='2'>FECHA DE TOMA DE POSESIÓN DEL EMPLEO, CARGO O COMISIÓN</td>";}

                  $html1.="<td class='cp4t_nv' colspan='2'>TELÉFONO DE OFICINA Y EXTENSIÓN</td> 
                  </tr>
                  <tr>
                    <td class='cp4_nv' colspan='2'>".$repi4['fecha_inicio']."</td>
                    <td class='cp4_nv' colspan='2'>".$repi4['tel_oficina'].' '.$repi4['extension']."</td> 
                  </tr>
                  <tr>
                    <td class='cp4t_nvt' colspan='4'>DOMICILIO DEL EMPLEO, CARGO O COMISIÓN</td> 
                  </tr>
                  <tr>
                    <td class='cp4t_nv'>PAÍS</td>
                    <td class='cp4t_nv'>CALLE</td>
                    <td class='cp4t_nv'>NÚMERO EXTERIOR</td>
                    <td class='cp4t_nv'>NÚMERO INTERIOR</td>
                  </tr>
                  <tr>
                  <td class='cp4_nv'>".$repi4['pais_descr']."</td> 
                  <td class='cp4_nv'>".$repi4['calle']."</td>
                    <td class='cp4_nv'>".$repi4['num_exterior']."</td>
                    <td class='cp4_nv'>".$repi4['num_interior']."</td>
                  </tr>
                  <tr>              
                    <td class='cp4t_nv'>COLONIA / LOCALIDAD</td>
                    <td class='cp4t_nv'>MUNICIPIO / ALCALDÍA</td>
                    <td class='cp4t_nv'>ENTIDAD FEDERATIVA</td>
                    <td class='cp4t_nv'>CÓDIGO POSTAL</td>
                  </tr>
                  <tr>                 
                    <td class='cp4_nv'>".$repi4['colonia_descr']."</td>
                    <td class='cp4_nv'>".$repi4['municipio_descr']."</td>
                    <td class='cp4_nv'>".$repi4['estado_descr']."</td>
                    <td class='cp4_nv'>".$repi4['codigopostal']."</td>
                  </tr>";}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";}
                 $html1.="  
                </table>
                
                <table>
                  <caption class='rps'>EXPERIENCIA LABORAL (ÚLTIMOS CINCO EMPLEOS)
                  </caption>";
                  if (pg_num_rows($resulti5)>0){
                  foreach ($reportei5 as $repi5) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi5['movimiento']."</td>
                  </tr>";
                  if ($repi5['movimiento']=='NINGUNO' or $repi5['movimiento']=='BAJA'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t_nv'>ÁMBITO / SECTOR  EN EL QUE LABORASTE</td>
                    <td class='cp3t_nv'>NIVEL / ORDEN DE GOBIERNO</td>
                    <td class='cp3t_nv'>ÁMBITO PÚBLICO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi5['actividad_laboral']."</td>";
                  if ($repi5['actividad_laboral']=='NINGUNO'){
                    $html1.=" <td class='cp3_nv'></td>
                    <td class='cp3_nv'></td></tr>";}
                    else{
                    $html1.="
                    <td class='cp3_nv'>".$repi5['orden_descr']."</td>
                    <td class='cp3_nv'>".$repi5['ambito_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp3t_nv'>NOMBRE DEL ENTE PÚBLICO / NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t_nv'>RFC</td>
                    <td class='cp3t_nv'>ÁREA DE ADSCRIPCIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi5['dependencia_descr']."</td>
                    <td class='cp4_nv'>".$repi5['rfc_empresa']."</td>
                    <td class='cp4_nv'>".$repi5['area_descr']."</td> 
                  </tr>
                  <tr>
                    <td class='cp3t_nv'>EMPLEO, CARGO O COMISIÓN / PUESTO</td>
                    <td class='cp3t_nv'>ESPECIFIQUE FUNCIÓN PRINCIPAL</td>
                    <td class='cp3t_nv'>SECTOR AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi5['puesto_descr']."</td>
                    <td class='cp4_nv'>".$repi5['funcion_principal']."</td>
                    <td class='cp4_nv'>".$repi5['sector_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv'>FECHA DE INGRESO</td>
                    <td class='cp4t_nv'>FECHA DE EGRESO</td>
                    <td class='cp4t_nv'>LUGAR DONDE SE UBICA</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi5['fecha_inicio']."</td>
                    <td class='cp4_nv'>".$repi5['fecha_fin']."</td>
                    <td class='cp4_nv'>".$repi5['ubicacion']."</td>
                  </tr>";}}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";} 
                  $html1.="        
                </table>";
                #Esta sentencia se cumple si la declaración es completa#
                if ($declara_completo == 'C') {

                $html1.="<table>";
                // Denise Cigarroa 21/08/2020
              //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html1.="<caption class='rps'>INGRESOS NETOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (ENTRE EL 1 DE ENERO Y 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)
                  </caption>";}
                  elseif ($tipo_decl =='C'){
                    $html1.="<caption class='rps'>INGRESOS NETOS DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS
                  </caption>";}
                  else{
                 $html1.="<caption class='rps'>INGRESOS NETOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SITUACIÓN ACTUAL)
                  </caption>";}
                  ############################################################################
                  if (pg_num_rows($resulti8)>0){
                  foreach ($reportei8 as $repi8) {
                  $html1.="
                  <tr>";
                  // Denise Cigarroa 21/08/2020
              //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html1.="<td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN ANUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                    elseif ($tipo_decl =='C'){
                    $html1.="<td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN NETA DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SULDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                    else{
                   $html1.="<td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS  Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                   ##########################################################################
                   $html1.="<td class='cp4_nv'>".$repi8['remunera_neta']."</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                  //Con esta sentencia se identifica el tipo de declaración a imprimir
                    if ($tipo_decl =='I'){
                   $html1.="<td class='cp4t_nv' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.4)</td>";}
                   else{
                    $html1.="<td class='cp4t_nv' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.5)</td>";}
                    ###########################################################################
                   $html1.="<td class='cp4_nv'>".$repi8['otros_ingresos']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['activ_industrial']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>NOMBRE O RAZÓN SOCIAL</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['razon_social']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>TIPO DE NEGOCIO</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_negocio']."</td>
                  </tr>
                 <tr>
                    <td class='cp4t_nv' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANACIAS) (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['activ_financiera']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANANCIA</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_instrumento']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['serv_profesionales']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_servicio']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='3'>II.4.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['no_considerados']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGUROS DE VIDA, ETC.)</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_ingreso']."</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='C'){
                   $html1.="<td class='cp4t_nv' colspan='3'>A.- INGRESO DEL DECLARANTE DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN (SUMA DEL NUMERAL I Y II)</td>";}
                   elseif ($tipo_decl =='M'){
                    $html1.="<td class='cp4t_nv' colspan='3'>A.- INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                    else{
                      $html1.="<td class='cp4t_nv' colspan='3'>A.- INGRESO MENSUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                  ###########################################################################
                   $html1.="<td class='cp4_nv'>".$repi8['ingreso_neto']."</td>
                  </tr>   
                   <tr>
                    <td class='cp4t_nv' colspan='3'>C.- TOTAL DE INGRESOS MENSUALES NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SUMA DE LOS APARTADOS A Y B)</td>
                    <td class='cp4_nv'>".$repi8['total_ingresos']."</td>
                  </tr>";}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";} 
                 $html1.="                              
                </table>";
                // Denise Cigarroa 21/08/2020
              //Este apartado se imprimen cuando la declaración no es de modificación
                if ($tipo_decl <>'M'){
                $html1.="<table>
                <caption class='rps'>¿TE DESEMPEÑASTE COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR?
                  </caption>";
                  if (pg_num_rows($resulti9)>0){
                  foreach ($reportei9 as $repi9) {
                  $html1.="
                  <tr>
                    <td class='cp4_nv' colspan='4'>".$repi9['servidor_anio_prev']."</td>
                  </tr>";
                  if ($repi9['servidor_anio_prev']=='NO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp4t_nv' colspan='2'>FECHA DE INICIO</td>
                    <td class='cp4t_nv' colspan='2'>FECHA DE CONCLUSIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv' colspan='2'>".$repi9['fecha_inicio']."</td>
                    <td class='cp4_nv' colspan='2'>".$repi9['fecha_fin']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>INGRESOS NETOS, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑO COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR</td>
                  <td class='cp4_nv'>".$repi9['ingreso_neto']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN NETA DEL DECLARANTE, RECIBIDA DURANTE EL TIEMPO QUE SE DESEMPEÑO COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['remunera_neta']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑO COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL II.1 AL II.5)</td>
                  <td class='cp4_nv'>".$repi9['otros_ingresos']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['activ_industrial']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv'>NOMBRE O RAZÓN SOCIAL</td>
                  <td class='cp4_nv'>".$repi9['razon_social']."</td>
                  <td class='cp4t_nv'>TIPO DE NEGOCIO</td>
                  <td class='cp4_nv'>".$repi9['tipo_negocio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANANCIAS)(DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['activ_financiera']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANACIA</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['otro_instrumento']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['serv_profesionales']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['tipo_servicio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.4.- POR ENAJENACIÓN DE BIENES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['enajena_bienes']."</td>
                  </tr>   
                  <tr>
                  <td class='cp4t_nv' colspan='2'>TIPO DE BIEN ENAJENADO</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['tipo_descr']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.5.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['no_considerados']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGURO DE VIDA, ETC.)</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['tipo_ingreso']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t_nv' colspan='3'>A.- INGRESO NETO DEL DECLARANTE, RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL NUMERAL I Y II)</td>
                  <td class='cp4_nv'>".$repi9['ingreso_neto']."</td>
                  </tr>  
                  <tr>
                  <td class='cp4t_nv' colspan='3'>C.- TOTAL DE INGRESOS NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS, EN EL AÑO INMEDIATO ANTERIOR (SUMA DE LOS APARTADOS A Y B)</td>
                  <td class='cp4_nv'>".$repi9['total_ingresos']."</td>
                  </tr> 
                  ";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";} 
                $html1.="</table>";}

                $html1.="<table>";
                // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl=='M'){
                    $html1.="<caption class='rps'>BIENES INMUEBLES (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)</caption>";}
                    else{
                 $html1.="<caption class='rps'>BIENES INMUEBLES (SITUACIÓN ACTUAL)
                  </caption>";}
              ###############################################################################
                  $html1.="<tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE BIENES DECLARADOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PUBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>BIENES DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti10)>0){
                  foreach ($reportei10 as $repi10) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi10['movimiento']."</td>
                  </tr>";
                  if ($repi10['movimiento']=='NINGUNO' or $repi10['movimiento']=='BAJA'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t_nv'>TIPO DE INMUEBLE</td>
                    <td class='cp3t_nv'>PORCENTAJE DE PROPIEDAD DEL DECLARANTE CONFORME A ESCRITURACIÓN O CONTRATO</td>
                    <td class='cp3t_nv'>SUPERFICIE DEL TERRENO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi10['otro_inmueble']."</td>
                    <td class='cp3_nv'>".$repi10['pct_propiedad']."</td>
                    <td class='cp3_nv'>".$repi10['sup_terreno']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t_nv'>SUPERFICIE DE CONSTRUCCIÓN</td>
                    <td class='cp3t_nv'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t_nv'>RFC</td>
                  </tr>      
                  <tr>
                    <td class='cp3_nv'>".$repi10['sup_construc']."</td>
                    <td class='cp3_nv'>".$repi10['nombre_tercero']."</td>
                    <td class='cp3_nv'>".$repi10['rfc_tercero']."</td>
                  </tr>                  
                  <tr>
                    <td class='cp3t_nv'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t_nv'>FORMA DE PAGO</td>
                    <td class='cp3t_nv'>VALOR DE ADQUISICIÓN</td>
                  </tr>                
                  <tr>
                    <td class='cp3_nv'>".$repi10['adquisicion_descr']."</td>
                    <td class='cp3_nv'>".$repi10['forma_descr']."</td>
                    <td class='cp3_nv'>".$repi10['valor_adquisicion']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t_nv'>¿EL VALOR DE LA ADQUISICIÓN DEL INMUEBLE ES CONFORME A?</td>
                    <td class='cp3t_nv'>TIPO DE MONEDA</td>
                    <td class='cp3t_nv'>FECHA DE ADQUISICIÓN DEL INMUEBLE</td>
                  </tr> 
                  <tr>
                    <td class='cp3_nv'>".$repi10['conforme_descr']."</td>
                    <td class='cp3_nv'>".$repi10['tipo_moneda']."</td>
                    <td class='cp3_nv'>".$repi10['fecha_adquisicion']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t_nv' colspan='3'>EN CASO DE BAJA DEL INMUEBLE INCLUIR MOTIVO</td>
                  </tr>
                  <tr>
                  <td class='cp3_nv' colspan='3'>".$repi10['causa_baja']."</td>
                  </tr>
                  ";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";} 
                 $html1.=" 
                </table>

                <table>";
                // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html1.="<caption class='rps'>VEHÍCULOS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)
                  </caption>";}
                  else{
                $html1.="<caption class='rps'>VEHÍCULOS (SITUACIÓN ACTUAL)
                  </caption>";}
                  ##########################################################################
                  $html1.="<tr>
                    <td class='ct' colspan='4'>TODOS LOS DATOS DE VEHÍCULOS DECLARADOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PUBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='4'>VEHÍCULOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti11)>0){
                  foreach ($reportei11 as $repi11) {
                  $html1.="
                  <tr>
                    <td class='cp4_nv' colspan='4'>TIPO DE MOVIMIENTO: ".$repi11['movimiento']."</td>
                  </tr>";
                  if ($repi11['movimiento']=='NINGUNO' or $repi11['movimiento']=='BAJA'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp4t_nv'>TIPO DE VEHÍCULO</td>
                    <td class='cp4t_nv'>MARCA</td>
                    <td class='cp4t_nv'>MODELO</td>
                    <td class='cp4t_nv'>AÑO</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi11['tipo_vehiculo']."</td>
                    <td class='cp4_nv'>".$repi11['marca']."</td>
                    <td class='cp4_nv'>".$repi11['modelo']."</td>
                    <td class='cp4_nv'>".$repi11['anio']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t_nv' colspan='2'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp4t_nv'>RFC</td>
                    <td class='cp4t_nv'>FORMA DE ADQUISICIÓN</td>
                    
                  </tr> 
                  <tr>
                    <td class='cp4_nv' colspan='2'>".$repi11['nombre_tercero']."</td>
                    <td class='cp4_nv'>".$repi11['rfc_tercero']."</td>
                    <td class='cp4_nv'>".$repi11['adquisicion_descr']."</td>
                    
                  </tr> 
                  <tr>
                    <td class='cp4t_nv'>FORMA DE PAGO</td>
                    <td class='cp4t_nv'>VALOR DE ADQUISICIÓN</td>
                    <td class='cp4t_nv'>TIPO DE MONEDA</td>
                    <td class='cp4t_nv'>FECHA DE ADQUISICIÓN</td>
                  </tr>  
                  <tr>
                    <td class='cp4_nv'>".$repi11['forma_descr']."</td>
                    <td class='cp4_nv'>".$repi11['valor_adquisicion']."</td>
                    <td class='cp4_nv'>".$repi11['tipo_moneda']."</td>
                    <td class='cp4_nv'>".$repi11['fecha_adquisicion']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t_nv' colspan='4'>EN CASO DE BAJA DEL VEHÍCULO INCLUIR MOTIVO</td>
                  </tr> 
                  <tr>
                    <td class='cp4_nv' colspan='4'>".$repi11['causa_baja']."</td>
                  </tr>";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";} 
                 $html1.=" 
                </table>

                <table>";
                // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html1.="<caption class='rps'>BIENES MUEBLES (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)
                  </caption>";}
                  else{
                $html1.="<caption class='rps'>BIENES MUEBLES (SITUACIÓN ACTUAL)
                  </caption>";}
                  ###########################################################################
                 $html1.="<tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LOS BIENES DECLARADOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PUBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>BIENES DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti12)>0){
                  foreach ($reportei12 as $repi12) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi12['movimiento']."</td>
                  </tr>";
                  if ($repi12['movimiento']=='NINGUNO' or $repi12['movimiento']=='BAJA'){
                    $html1.=" ";}
                    else{
                    $html1.="                   
                  <tr>
                    <td class='cp3t_nv'>TIPO DE BIEN</td>
                    <td class='cp3t_nv'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t_nv'>RFC</td>
                    </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi12['tipo_descr']."</td>
                    <td class='cp3_nv'>".$repi12['nombre_tercero']."</td>
                    <td class='cp3_nv'>".$repi12['rfc_tercero']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t_nv'>DESCRIPCIÓN DEL BIEN</td>
                    <td class='cp3t_nv'>FORMA DE ADQUISICIÓN</td>
                    <td class='cp3t_nv'>FORMA DE PAGO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi12['descripcion']."</td>
                    <td class='cp3_nv'>".$repi12['adquisicion_descr']."</td>
                    <td class='cp3_nv'>".$repi12['forma_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t_nv'>VALOR DE ADQUISICIÓN DEL MUEBLE</td>
                    <td class='cp3t_nv'>TIPO DE MONEDA</td>
                    <td class='cp3t_nv'>FECHA DE ADQUISICIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi12['valor_adquisicion']."</td>
                    <td class='cp3_nv'>".$repi12['tipo_moneda']."</td>
                    <td class='cp3_nv'>".$repi12['fecha_adquisicion']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t_nv' colspan='3'>EN CASO DE BAJA DEL MUEBLE INCLUIR MOTIVO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv' colspan='3'>".$repi12['causa_baja']."</td>
                  </tr>
                  ";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";} 
                 $html1.="  
                </table>

                <table>";
                // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html1.="<caption class='rps'>INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES / ACTIVOS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)
                  </caption>";}
                  else{
                $html1.="<caption class='rps'>INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES / ACTIVOS (SITUACIÓN ACTUAL)
                  </caption>";}
                  #######################################################################
                 $html1.="<tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LAS INVERSIONES, CUENTAS BANCARIAS Y OTROS TIPOS DE VALORES / ACTIVOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PUBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti13)>0){
                  foreach ($reportei13 as $repi13) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi13['movimiento']."</td>
                  </tr>";
                  if ($repi13['movimiento']=='NINGUNO' or $repi13['movimiento']=='BAJA'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t_nv'>TIPO DE INVERSIÓN / ACTIVO</td>
                    <td class='cp3t_nv'>BANCARIA</td>
                    <td class='cp3t_nv'>NOMBRE DEL TERCERO O TERCEROS</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi13['tipo_inver_descr']."</td>
                    <td class='cp3_nv'>".$repi13['bancaria_descr']."</td>
                    <td class='cp3_nv'>".$repi13['nombre_tercero']."</td>
                  </tr> 
                 <tr>
                    
                    <td class='cp3t_nv'>RFC</td>
                    <td class='cp3t_nv'>FONDOS DE INVERSIÓN</td>
                    <td class='cp3t_nv'>ORGANIZACIONES PRIVADAS Y / O MERCANTILES</td>
                  </tr>
                  <tr>
                    
                    <td class='cp3_nv'>".$repi13['rfc_tercero']."</td>
                    <td class='cp3_nv'>".$repi13['fondo_descr']."</td>
                    <td class='cp3_nv'>".$repi13['org_descr']."</td>
                  </tr>
                   <tr>
                    
                    <td class='cp3t_nv'>POSESIONES DE MONEDAS Y / O METALES</td>
                    <td class='cp3t_nv'>SEGUROS</td>
                    <td class='cp3t_nv'>VALORES BURSÁTILES</td>
                  </tr>
                   <tr>
                    
                    <td class='cp3_nv'>".$repi13['monedas_descr']."</td>
                    <td class='cp3_nv'>".$repi13['seguros_descr']."</td>
                    <td class='cp3_nv'>".$repi13['valor_descr']."</td>
                  </tr>
                   <tr>
                    
                    <td class='cp3t_nv'>AFORES Y OTROS </td>
                    <td class='cp3t_nv'>¿DONDE SE LOCALIZA LA INVERSIÓN, CUENTA BANCARIA U OTRO TIPO DE VALORES / ACTIVOS?</td>
                    <td class='cp3t_nv'>INSTITUCIÓN O RAZÓN SOCIAL</td>
                  </tr>
                  <tr>
                    
                    <td class='cp3_nv'>".$repi13['afores_descr']."</td>
                    <td class='cp3_nv'>".$repi13['ubicacion']."</td>
                    <td class='cp3_nv'>".$repi13['razon_social']."</td>
                  </tr>
                   
                   <tr>
                    <td class='cp3t_nv'>RFC</td>
                    <td class='cp3t_nv'>PAÍS DONDE SE LOCALIZA</td>
                    <td class='cp3t_nv'>TIPO DE MONEDA</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi13['rfc_institucion']."</td>
                    <td class='cp3_nv'>".$repi13['pais_descr']."</td>
                    <td class='cp3_nv'>".$repi13['tipo_moneda']."</td>
                  </tr>
                  ";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";} 
                 $html1.=" 
                </table>

                <table>";
                // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html1.="<caption class='rps'>ADEUDOS / PASIVOS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)
                  </caption>";}
                  else{
                 $html1.="<caption class='rps'>ADEUDOS / PASIVOS (SITUACIÓN ACTUAL)
                  </caption>";}
                  #########################################################################
                 $html1.="<tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LOS ADEUDOS / PASIVOS A NOMBRE DE LA PAREJA, DEPENDIENTES ECONÓMICOS Y / O TERCEROS O QUE SEAN EN COPROPIEDAD CON EL DECLARANTE NO SERÁN PUBLICOS.</td>
                  </tr>
                  <tr>
                    <td class='cv' colspan='3'>ADEUDOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti14)>0){
                  foreach ($reportei14 as $repi14){
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi14['movimiento']."</td>
                  </tr>";
                  if ($repi14['movimiento']=='NINGUNO' or $repi14['movimiento']=='BAJA'){
                    $html1.=" ";}
                    else{
                    $html1.="
                 <tr>
                    <td class='cp3t_nv'>TIPO DE ADEUDO</td>
                    <td class='cp3t_nv'>FECHA DE ADQUISICIÓN DEL ADEUDO / PASIVO</td>
                    <td class='cp3t_nv'>MONTO ORIGINAL DEL ADEUDO / PASIVO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi14['tipo_adeudo_descr']."</td>
                    <td class='cp3_nv'>".$repi14['fecha_adquisicion']."</td>
                    <td class='cp3_nv'>".$repi14['monto_original']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t_nv'>TIPO DE MONEDA</td>
                    <td class='cp3t_nv'>NOMBRE DEL TERCERO O TERCEROS</td>
                    <td class='cp3t_nv'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi14['tipo_moneda']."</td>
                    <td class='cp3_nv'>".$repi14['nombre_tercero']."</td>
                    <td class='cp3_nv'>".$repi14['rfc_tercero']."</td>
                  </tr>";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";}
                 $html1.=" 
                </table>

                <table>";
                // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html1.="<caption class='rps'>PRÉSTAMO O COMODATO POR TERCEROS (ENTRE EL 1 DE ENERO Y EL 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)
                  </caption>";}
                  else{
                 $html1.="<caption class='rps'>PRÉSTAMO O COMODATO POR TERCEROS (SITUACIÓN ACTUAL)
                  </caption>";}
                  #############################################################################
                  if (pg_num_rows($resulti15)>0){
                  foreach ($reportei15 as $repi15) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi15['movimiento']."</td>
                  </tr>";
                  if ($repi15['movimiento']=='NINGUNO' or $repi15['movimiento']=='BAJA'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t_nv'>TIPO DE BIEN.</td>
                    <td class='cp3t_nv'>INMUEBLE</td>
                    <td class='cp3t_nv'>VEHÍCULO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv' colspan='2'>".$repi15['tipo_comodato']."</td>
                    <td class='cp3_nv' colspan='2'>".$repi15['tipo_inmueble']."</td>
                    <td class='cp3_nv' colspan='2'>".$repi15['tipo_vehiculo']."</td>
                  </tr>   
                  <tr>
                    <td class='cp3t_nv'>MARCA</td>
                    <td class='cp3t_nv'>MODELO</td>
                    <td class='cp3t_nv'>AÑO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi15['marca']."</td>
                    <td class='cp3_nv'>".$repi15['modelo']."</td>
                    <td class='cp3_nv'>".$repi15['anio']."</td>
                  </tr>";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";}
                 $html1.="  
                </table>
              </main>
              </body>
                ";

$mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html1);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');

  }
// Denise Cigarroa 21/08/2020
//Esta sentencia se cumple para las declaraciones parciales
else{

  $html1.="<table>";
                // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                if ($tipo_decl =='M'){
                  $html1.="<caption class='rps'>INGRESOS NETOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (ENTRE EL 1 DE ENERO Y 31 DE DICIEMBRE DEL AÑO INMEDIATO ANTERIOR)
                  </caption>";}
                  elseif ($tipo_decl =='C'){
                    $html1.="<caption class='rps'>INGRESOS NETOS DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS
                  </caption>";}
                  else{
                 $html1.="<caption class='rps'>INGRESOS NETOS DEL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SITUACIÓN ACTUAL)
                  </caption>";}
                  ###########################################################################
                  if (pg_num_rows($resulti8)>0){
                  foreach ($reportei8 as $repi8) {
                  $html1.="
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='M'){
                    $html1.="<td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN ANUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                    elseif ($tipo_decl =='C'){
                    $html1.="<td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN NETA DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SULDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                    else{
                   $html1.="<td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (POR CONCEPTO DE SUELDOS HONORARIOS, COMPENSACIONES BONOS  Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>";}
                   ############################################################################
                   $html1.="<td class='cp4_nv'>".$repi8['remunera_neta']."</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                    if ($tipo_decl =='I'){
                   $html1.="<td class='cp4t_nv' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.4)</td>";}
                   else{
                    $html1.="<td class='cp4t_nv' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE (SUMA DEL II.1 AL II.5)</td>";}
                    ###########################################################################
                   $html1.="<td class='cp4_nv'>".$repi8['otros_ingresos']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['activ_industrial']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>NOMBRE O RAZÓN SOCIAL</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['razon_social']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>TIPO DE NEGOCIO</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_negocio']."</td>
                  </tr>
                 <tr>
                    <td class='cp4t_nv' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANACIAS) (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['activ_financiera']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANANCIA</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_instrumento']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['serv_profesionales']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_servicio']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='3'>II.4.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                    <td class='cp4_nv'>".$repi8['no_considerados']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGUROS DE VIDA, ETC.)</td>
                    <td class='cp4_nv' colspan='2'>".$repi8['tipo_ingreso']."</td>
                  </tr>
                  <tr>";
                  // Denise Cigarroa 21/08/2020
                 //Con esta sentencia se identifica el tipo de declaración a imprimir
                  if ($tipo_decl =='C'){
                   $html1.="<td class='cp4t_nv' colspan='3'>A.- INGRESO DEL DECLARANTE DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN (SUMA DEL NUMERAL I Y II)</td>";}
                   elseif ($tipo_decl =='M'){
                    $html1.="<td class='cp4t_nv' colspan='3'>A.- INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                    else{
                      $html1.="<td class='cp4t_nv' colspan='3'>A.- INGRESO MENSUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II)</td>";}
                    ##########################################################################
                   $html1.="<td class='cp4_nv'>".$repi8['ingreso_neto']."</td>
                  </tr>   
                   <tr>
                    <td class='cp4t_nv' colspan='3'>C.- TOTAL DE INGRESOS MENSUALES NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS (SUMA DE LOS APARTADOS A Y B)</td>
                    <td class='cp4_nv'>".$repi8['total_ingresos']."</td>
                  </tr> 
                  ";}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";}
                 $html1.="                              
                </table>";
                // Denise Cigarroa 21/08/2020
              //Este apartado se imprimen cuando la declaración no es de modificación
                if ($tipo_decl <>'M'){
               $html1.="<table border='1' cellspacing='0'>
                  <caption class='rps'>¿TE DESEMPEÑASTE COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR?
                  </caption>";
                  if (pg_num_rows($resulti9)>0){
                  foreach ($reportei9 as $repi9) {
                  $html1.="
                  <tr>
                    <td class='cp4_nv' colspan='4'>".$repi9['servidor_anio_prev']."</td>
                  </tr>";
                  if ($repi9['servidor_anio_prev']=='NO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp4t_nv' colspan='2'>FECHA DE INICIO</td>
                    <td class='cp4t_nv' colspan='2'>FECHA DE CONCLUSIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv' colspan='2'>".$repi9['fecha_inicio']."</td>
                    <td class='cp4_nv' colspan='2'>".$repi9['fecha_fin']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>INGRESOS NETOS, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑO COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR</td>
                  <td class='cp4_nv'>".$repi9['ingreso_neto']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>I.- REMUNERACIÓN NETA DEL DECLARANTE, RECIBIDA DURANTE EL TIEMPO QUE SE DESEMPEÑO COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS, AGUINALDOS Y OTRAS PRESTACIONES) (CANTIDADES NETAS DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['remunera_neta']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.- OTROS INGRESOS DEL DECLARANTE, RECIBIDOS DURANTE EL TIEMPO EN EL QUE SE DESEMPEÑO COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL II.1 AL II.5)</td>
                  <td class='cp4_nv'>".$repi9['otros_ingresos']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.1.- POR ACTIVIDAD INDUSTRIAL, COMERCIAL Y / O EMPRESARIAL (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['activ_industrial']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv'>NOMBRE O RAZÓN SOCIAL</td>
                  <td class='cp4_nv'>".$repi9['razon_social']."</td>
                  <td class='cp4t_nv'>TIPO DE NEGOCIO</td>
                  <td class='cp4_nv'>".$repi9['tipo_negocio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.2.- POR ACTIVIDAD FINANCIERA (RENDIMIENTOS O GANANCIAS)(DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['activ_financiera']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='2'>TIPO DE INSTRUMENTO QUE GENERÓ EL RENDIMIENTO O GANACIA</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['otro_instrumento']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.3.- POR SERVICIOS PROFESIONALES, CONSEJOS, CONSULTORÍAS Y / O ASESORÍAS (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['serv_profesionales']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='2'>TIPO DE SERVICIO PRESTADO</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['tipo_servicio']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.4.- POR ENAJENACIÓN DE BIENES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['enajena_bienes']."</td>
                  </tr>   
                  <tr>
                  <td class='cp4t_nv' colspan='2'>TIPO DE BIEN ENAJENADO</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['tipo_descr']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='3'>II.5.- OTROS INGRESOS NO CONSIDERADOS A LOS ANTERIORES (DESPUÉS DE IMPUESTOS)</td>
                  <td class='cp4_nv'>".$repi9['no_considerados']."</td>
                  </tr>
                  <tr>
                  <td class='cp4t_nv' colspan='2'>ESPECIFICAR TIPO DE INGRESO (ARRENDAMIENTO, REGALÍA, SORTEOS, CONCURSOS, DONACIONES, SEGURO DE VIDA, ETC.)</td>
                  <td class='cp4_nv' colspan='2'>".$repi9['tipo_ingreso']."</td>
                  </tr> 
                  <tr>
                  <td class='cp4t_nv' colspan='3'>A.- INGRESO NETO DEL DECLARANTE, RECIBIDO EN EL AÑO INMEDIATO ANTERIOR (SUMA DEL NUMERAL I Y II)</td>
                  <td class='cp4_nv'>".$repi9['ingreso_neto']."</td>
                  </tr>  
                  <tr>
                  <td class='cp4t_nv' colspan='3'>C.- TOTAL DE INGRESOS NETOS PERCIBIDOS POR EL DECLARANTE, PAREJA Y / O DEPENDIENTES ECONÓMICOS, EN EL AÑO INMEDIATO ANTERIOR (SUMA DE LOS APARTADOS A Y B)</td>
                  <td class='cp4_nv'>".$repi9['total_ingresos']."</td>
                  </tr>";}}}
                  else{$html1.="<tr>
                   <td class='cp4_nv'>SIN INFORMACIÓN</td>
                   </tr>";}
                 $html1.="                       
               </table>";}
             $html1.="</main>
              </body>";}

  $mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html1);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  
  $mpdf->Output($td.$ti.$rfc.$ejercicio.'DP.pdf','I');