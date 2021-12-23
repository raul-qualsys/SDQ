<!-- Denise Cigarroa Rodriguez 21/08/2020 
Se agregaron dos variables para el pie de pagina y los logos 
asi mismo se cambio el color de los encabezados de las tablas -->
<!--#############################################################-->
  <?php
  require_once('../../vendor/autoload.php');
  include("../../include/conexion.php");
  include("../../include/funciones.php");

#Denise Cigarroa Rodriguez 21/08/2020# 
#Se agrego el include para traer la dirección de la empresa#
include("../../include/constantes.php");
###################################################################

#Denise Cigarroa Rodriguez 21/08/2020# 
#Esta es una variable global que contiene la dirección de la empresa em turno#
$direccion = CALLE_EMPRESA." ".EXT_EMPRESA." ".COL_COMPLETE." ".MUN_COMPLETE."<br>".CP_EMPRESA." ".EST_COMPLETE;
#######################################################################

$rfc=$_POST["rfc"];
$ejercicio=$_POST["ejercicio"];
$tipo_decl=$_POST["tipo_decl"];

$sqli1="SELECT *, CASE declarante
WHEN 'E' THEN 'DECLARANTE'
WHEN 'P' THEN 'PAREJA'
WHEN 'D' THEN 'DEPENDIENTE ECONÓMICO' END AS declarante,
CASE remuneracion
WHEN 'S' THEN 'SI'
WHEN 'N' THEN 'NO' END AS remuneracion,
CASE ubicacion
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_participa_empresas WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti1=pg_query($conn,$sqli1);
$reportei1=pg_fetch_all($resulti1);

$sqli2="SELECT *,CASE declarante
WHEN 'E' THEN 'DECLARANTE'
WHEN 'P' THEN 'PAREJA'
WHEN 'D' THEN 'DEPENDIENTE ECONÓMICO' END AS declarante,
CASE tipo_institucion
WHEN 'P' THEN 'PARTIDOS POLITICOS'
WHEN 'S' THEN 'GREMIOS / SINDICATOS'
WHEN 'C' THEN 'ORGANIZACIONES DE LA SOCIEDAD CIVIL'
WHEN 'B' THEN 'ASOCIACIONES BENEFICAS'
WHEN 'O' THEN 'OTRO' END AS tipo_institucion,
CASE ubicacion
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_decisiones WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti2=pg_query($conn,$sqli2);
$reportei2=pg_fetch_all($resulti2);

$sqli3="SELECT *, CASE beneficiario
WHEN 'AB' THEN 'ABUELO(A)'
WHEN 'AH' THEN 'AHIJADO(A)'
WHEN 'CO' THEN 'CONCUBINA/CONCUBINARIO'
WHEN 'CV' THEN 'CONVIVIENTE'
WHEN 'CY' THEN 'CÓNYUGE'
WHEN 'CU' THEN 'CUÑADO(A)'
WHEN 'DE' THEN 'DECLARANTE'
WHEN 'HE' THEN 'HERMANO(A)'
WHEN 'HI' THEN 'HIJO(A)'
WHEN 'MA' THEN 'MADRE'
WHEN 'NI' THEN 'NIETO(A)'
WHEN 'NU' THEN 'NUERA'
WHEN 'PA' THEN 'PADRE'
WHEN 'PR' THEN 'PRIMO(A)'
WHEN 'SO' THEN 'SOBRINO(A)'
WHEN 'TI' THEN 'TÍO(A)'
WHEN 'YE' THEN 'YERNO'
WHEN 'OT' THEN 'OTRO' END AS beneficiario,
CASE tipo_apoyo
WHEN 'U' THEN 'SUBSIDIO'
WHEN 'E' THEN 'SERVICIO'
WHEN 'B' THEN 'OBRA'
WHEN 'O' THEN 'OTRO' END AS tipo_apoyo,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_apoyos WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti3=pg_query($conn,$sqli3);
$reportei3=pg_fetch_all($resulti3);

$sqli4="SELECT *,CASE declarante
WHEN 'E' THEN 'DECLARANTE'
WHEN 'P' THEN 'PAREJA'
WHEN 'D' THEN 'DEPENDIENTE ECONÓMICO' END AS declarante,
CASE representa
WHEN 'F' THEN 'PERSONA FÍSICA'
WHEN 'M' THEN 'PERSONA MORAL' END AS representa,
CASE remuneracion
WHEN 'S' THEN 'SI'
WHEN 'N' THEN 'NO' END AS remuneracion,
CASE ubicacion
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_representaciones WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti4=pg_query($conn,$sqli4);
$reportei4=pg_fetch_all($resulti4);

$sqli5="SELECT *, CASE actividad
WHEN 'S' THEN 'SI'
WHEN 'N' THEN 'NO' END AS actividad,
CASE declarante
WHEN 'E' THEN 'DECLARANTE'
WHEN 'P' THEN 'PAREJA'
WHEN 'D' THEN 'DEPENDIENTE ECONÓMICO' END AS declarante,
CASE ubicacion
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_clientes WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti5=pg_query($conn,$sqli5);
$reportei5=pg_fetch_all($resulti5);

$sqli6="SELECT *, CASE tipo_beneficio
WHEN 'S' THEN 'SORTEO'
WHEN 'C' THEN 'CONCURSO'
WHEN 'D' THEN 'DONACIÓN'
WHEN 'O' THEN 'OTRO' END AS tipo_beneficio,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento
FROM qsy_beneficios_privados WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti6=pg_query($conn,$sqli6);
$reportei6=pg_fetch_all($resulti6);

$sqli7="SELECT *, CASE declarante
WHEN 'E' THEN 'DECLARANTE'
WHEN 'P' THEN 'PAREJA'
WHEN 'D' THEN 'DEPENDIENTE ECONÓMICO' END AS declarante,
CASE ubicacion
WHEN 'M' THEN 'MÉXICO'
WHEN 'E' THEN 'EXTRANJERO' END AS ubicacion,
CASE movimiento
WHEN 'A' THEN 'ALTA'
WHEN 'B' THEN 'BAJA'
WHEN 'M' THEN 'MODIFICACIÓN'
WHEN 'N' THEN 'NINGUNO'
WHEN 'S' THEN 'SIN CAMBIO' END AS movimiento 
FROM qsy_fideicomisos WHERE tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti7=pg_query($conn,$sqli7);
$reportei7=pg_fetch_all($resulti7);

$nombre=get_nombre($conn,$_POST["rfc"]);
$rfc = $_POST["rfc"];
$sexo=get_sexo($conn,$_POST["rfc"]);
$sx = '';

if($sexo =="M")
{$sx='LA';}
else
{ $sx = 'EL';}

$ti=$_POST["tipo_decl"];
$td=$reportei1[0]['declaracion'];
$tdi = '';
if($tipo_decl == "I")
{
  $tdi = 'INICIAL';
}
elseif($ti == "M")
{
  $tdi = 'MODIFICACIÓN';
}

elseif($ti == "C")
{
  $tdi = 'CONCLUSIÓN';
}

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
              <br>
                <table>
                  <tr>
                    <td class='cpt' colspan='3'>
                      <p>ÓRGANO INTERNO DE CONTROL DE LA DEPENDENCIA ".NOMBRE_EMPRESA.":</p><br>";
                      $texto=get_notificacion(9,$conn);
                      $texto=str_replace("{declaracion}","DE CONFLICTO DE INTERESES",$texto);
                      $html1.="<p>$sx QUE SUSCRIBE, $nombre, CON RFC $rfc</p><br>
                      <p>$texto</p>
                    </td>
                  </tr> 
                </table>
                <table>
                  <tr>";
/*Fin de actualización.*/
                  if (pg_num_rows($resulti1)>0){
                  $html1.="
                  <td class='cs' colspan='3'>II. DECLARACIÓN DE INTERESES</td>
                  <td class='cs' colspan='2'>".$tdi."</td>";
                }else{
                  $html1.="
                  <td class='cs' colspan='5'>II. DECLARACIÓN DE INTERESES</td>";
                }

                  $html1.="</tr>  
                </table>
                <table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 1.- PARTICIPACIÓN EN EMPRESAS, SOCIEDADES O ASOCIACIONES (HASTA LOS 2 ÚLTIMOS AÑOS)</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LA PARTICIPACIÓN EN EMPRESAS, SOCIEDADES O ASOCIACIONES DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti1)>0){
                  foreach ($reportei1 as $repi1){
                  $html1.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi1['movimiento']."</td>
                  </tr>";
                  if ($repi1['movimiento']=='NINGUNO'){
                    $html1.="
                  <tr>
                    <td class='cp3t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>PORCENTAJE DE PARTICIPACIÓN DE ACUERDO A ESCRITURA</td>
                    <td class='cp3t'>¿RECIBE REMUNERACIÓN POR SU PARTICIPACIÓN?</td>
                    <td class='cp3t'>MONTO MENSUAL NETO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi1['declarante']."</td>
                    <td class='cp3'>".$repi1['nombre_empresa']."</td>
                    <td class='cp3'>".$repi1['rfc_empresa']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp3' colspan='2'>".$repi1['tipo_part_descr']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>PORCENTAJE DE PARTICIPACIÓN DE ACUERDO A ESCRITURA</td>
                    <td class='cp3t'>¿RECIBE REMUNERACIÓN POR SU PARTICIPACIÓN?</td>
                    <td class='cp3t'>MONTO MENSUAL NETO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi1['pct_participacion']."</td>
                    <td class='cp3'>".$repi1['remuneracion']."</td>
                    <td class='cp3'>".$repi1['monto_mensual']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi1['ubicacion']."</td>
                    <td class='cp3' colspan='2'>".$repi1['sector_descr']."</td>
                  </tr>";}
                   $html1.="
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
                    <td class='cp3t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp3' colspan='2'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>PORCENTAJE DE PARTICIPACIÓN DE ACUERDO A ESCRITURA</td>
                    <td class='cp3t'>¿RECIBE REMUNERACIÓN POR SU PARTICIPACIÓN?</td>
                    <td class='cp3t'>MONTO MENSUAL NETO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
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
                 $html1.="                                 
                </table>";

                $html2="<table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='4'>APARTADO 2.- ¿PARTICIPA EN LA TOMA DE DECISIONES DE ALGUNA DE ESTAS INSTITUCIONES? (HASTA LOS 2 ÚLTIMOS AÑOS)</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='4'>TODOS LOS DATOS DE LA PARTICIPACIÓN EN ALGUNA DE ESTAS INSTITUCIONES DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti2)>0){
                  foreach ($reportei2 as $repi2) {
                  $html2.="
                  <tr>
                    <td class='cp4' colspan='4'>TIPO DE MOVIMIENTO: ".$repi2['movimiento']."</td>
                  </tr>";
                  if ($repi2['movimiento']=='NINGUNO'){
                    $html2.="
                  <tr>
                    <td class='cp4t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t'>TIPO DE INSTITUCIÓN</td>
                    <td class='cp4t'>NOMBRE DE LA INSTITUCIÓN</td>
                    <td class='cp4t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp4' colspan='3'>NO APLICA</td>
                  </tr>";}
                    else{
                    $html2.="
                  <tr>
                    <td class='cp4t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t'>TIPO DE INSTITUCIÓN</td>
                    <td class='cp4t'>NOMBRE DE LA INSTITUCIÓN</td>
                    <td class='cp4t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi2['declarante']."</td>
                    <td class='cp4'>".$repi2['tipo_institucion']."</td>
                    <td class='cp4'>".$repi2['nombre_inst']."</td>
                    <td class='cp4'>".$repi2['rfc_inst']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp4' colspan='3'>".$repi2['ubicacion']."</td>
                  </tr>";} 
                  $html2.="
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'>".$repi2['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html2.="
                  <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t'>TIPO DE INSTITUCIÓN</td>
                    <td class='cp4t'>NOMBRE DE LA INSTITUCIÓN</td>
                    <td class='cp4t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr> 
                  <tr>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp4' colspan='3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='4'></td>
                  </tr>";
                  }
                 $html2.="                                 
                </table>";
                $html3="<table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 3.- APOYOS O BENEFICIOS PÚBLICOS (HASTA LOS 2 ÚLTIMOS AÑOS)</td>
                  </tr>";
                  if (pg_num_rows($resulti3)>0){
                  foreach ($reportei3 as $repi3) {
                  $html3.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi3['movimiento']."</td>
                  </tr>";
                  if ($repi3['movimiento']=='NINGUNO'){
                    $html3.="
                  <tr>
                    <td class='cp3t'>BENEFICIARIO DE ALGÚN PROGRAMA PÚBLICO</td>
                    <td class='cp3t'>NOMBRE DEL PROGRAMA</td>
                    <td class='cp3t'>INSTITUCIÓN QUE OTORGA EL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NIVEL U ORDEN DE GOBIERNO</td>
                    <td class='cp3t'>TIPO DE APOYO</td>
                    <td class='cp3t'>FORMA DE RECEPCIÓN DEL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                   <tr>
                    <td class='cp3t'>MONTO APROXIMADO DEL APOYO MENSUAL</td>
                    <td class='cp3t' colspan='2'>ESPECIFIQUE EL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                    $html3.="
                  <tr>
                    <td class='cp3t'>BENEFICIARIO DE ALGÚN PROGRAMA PÚBLICO</td>
                    <td class='cp3t'>NOMBRE DEL PROGRAMA</td>
                    <td class='cp3t'>INSTITUCIÓN QUE OTORGA EL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi3['beneficiario']."</td>
                    <td class='cp3'>".$repi3['nombre_prog']."</td>
                    <td class='cp3'>".$repi3['instit_otorgante']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NIVEL U ORDEN DE GOBIERNO</td>
                    <td class='cp3t'>TIPO DE APOYO</td>
                    <td class='cp3t'>FORMA DE RECEPCIÓN DEL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi3['orden_descr']."</td>
                    <td class='cp3'>".$repi3['tipo_apoyo']."</td>
                    <td class='cp3'>".$repi3['forma_descr']."</td>
                  </tr> 
                   <tr>
                    <td class='cp3t'>MONTO APROXIMADO DEL APOYO MENSUAL</td>
                    <td class='cp3t' colspan='2'>ESPECIFIQUE EL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi3['monto_mensual']."</td>
                    <td class='cp3' colspan='2'>".$repi3['apoyo_descr']."</td>
                  </tr>";} 
                  $html3.="
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
                    <td class='cp3t'>BENEFICIARIO DE ALGÚN PROGRAMA PÚBLICO</td>
                    <td class='cp3t'>NOMBRE DEL PROGRAMA</td>
                    <td class='cp3t'>INSTITUCIÓN QUE OTORGA EL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>NIVEL U ORDEN DE GOBIERNO</td>
                    <td class='cp3t'>TIPO DE APOYO</td>
                    <td class='cp3t'>FORMA DE RECEPCIÓN DEL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                   <tr>
                    <td class='cp3t'>MONTO APROXIMADO DEL APOYO MENSUAL</td>
                    <td class='cp3t' colspan='2'>ESPECIFIQUE EL APOYO</td>
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
                 $html3.="                                 
                </table>";
                $html4="<table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 4.- REPRESENTACIÓN (HASTA LOS 2 ÚLTIMOS AÑOS)</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE REPRESENTACIÓN DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti4)>0){
                  foreach ($reportei4 as $repi4) {
                  $html4.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi4['movimiento']."</td>
                  </tr>";
                  if ($repi4['movimiento']=='NINGUNO'){
                    $html4.="
                  <tr>
                    <td class='cp3t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>TIPO DE PRESENTACIÓN</td>
                    <td class='cp3t'>FECHA DE INICIO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>REPRESENTANTE / REPRESENTADO</td>
                    <td class='cp3t' colspan='2'>NOMBRE O RAZÓN SOCIAL DEL REPRESENTANTE / REPRESENTADO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿RECIBE REMUNERACIÓN POR SU REPRESENTACIÓN?</td>
                    <td class='cp3t'>MONTO MENSUAL NETO DE SU REPRESENTACIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                    $html4.="
                  <tr>
                    <td class='cp3t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>TIPO DE PRESENTACIÓN</td>
                    <td class='cp3t'>FECHA DE INICIO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi4['declarante']."</td>
                    <td class='cp3'>".$repi4['tipo_descr']."</td>
                    <td class='cp3'>".$repi4['fecha_inicio']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>REPRESENTANTE / REPRESENTADO</td>
                    <td class='cp3t' colspan='2'>NOMBRE O RAZÓN SOCIAL DEL REPRESENTANTE / REPRESENTADO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi4['representa']."</td>
                    <td class='cp3' colspan='2'>".$repi4['nombre_repre']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿RECIBE REMUNERACIÓN POR SU REPRESENTACIÓN?</td>
                    <td class='cp3t'>MONTO MENSUAL NETO DE SU REPRESENTACIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi4['rfc_repre']."</td>
                    <td class='cp3'>".$repi4['remuneracion']."</td>
                    <td class='cp3'>".$repi4['monto_mensual']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi4['ubicacion']."</td>
                    <td class='cp3' colspan='2'>".$repi4['sector_descr']."</td>
                  </tr>";} 
                  $html4.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi4['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html4.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t'>TIPO DE PRESENTACIÓN</td>
                    <td class='cp3t'>FECHA DE INICIO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>REPRESENTANTE / REPRESENTADO</td>
                    <td class='cp3t' colspan='2'>NOMBRE O RAZÓN SOCIAL DEL REPRESENTANTE / REPRESENTADO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3' colspan='2'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>¿RECIBE REMUNERACIÓN POR SU REPRESENTACIÓN?</td>
                    <td class='cp3t'>MONTO MENSUAL NETO DE SU REPRESENTACIÓN</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr> 
                  <tr>
                    <td class='cp3t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
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
                 $html4.="                                 
                </table>";
                $html5="<table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='4'>APARTADO 5.- CLIENTES PRINCIPALES (HASTA LOS 2 ÚLTIMOS AÑOS)</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='4'>TODOS LOS DATOS DE CLIENTES PRINCIPALES DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='4'>SE MANIFESTARÁ EL BENEFICIO O GANANCIA DIRECTA DEL DECLARANTE SI SUPERA MENSUALMENTE 250 UNIDADES DE MEDIDA Y ACTUALIZACIÓN (UMA)</td>
                  </tr>";
                  if (pg_num_rows($resulti5)>0){
                  foreach ($reportei5 as $repi5) {
                  $html5.="
                  <tr>
                    <td class='cp4' colspan='4'>TIPO DE MOVIMIENTO: ".$repi5['movimiento']."</td>
                  </tr>";
                  if ($repi5['movimiento']=='NINGUNO'){
                    $html5.="
                  <tr>
                    <td class='cp4t'>¿REALIZA ALGUNA ACTIVIDAD LUCRATIVA INDEPENDIENTE AL EMPLEO, CARGO O COMISIÓN?</td>
                    <td class='cp4t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t'>NOMBRE DE LA EMPRESA O SERVICIO QUE PROPORCIONA</td>
                    <td class='cp4t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CLIENTE PRINCIPAL</td>
                    <td class='cp4t'>SEÑALE NOMBRE O RAZÓN SOCIAL DEL CLIENTE PRINCIPAL</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>MONTO APROXIMADO DEL BENEFICIO O GANANCIA MENSUAL QUE OBTIENE DEL CLIENTE PRINCIPAL</td>
                    <td class='cp4'>NO APLICA</td>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp4'>NO APLICA</td>
                  </tr>";}
                    else{
                    $html5.="
                  <tr>
                    <td class='cp4t'>¿REALIZA ALGUNA ACTIVIDAD LUCRATIVA INDEPENDIENTE AL EMPLEO, CARGO O COMISIÓN?</td>
                    <td class='cp4t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t'>NOMBRE DE LA EMPRESA O SERVICIO QUE PROPORCIONA</td>
                    <td class='cp4t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi5['actividad']."</td>
                    <td class='cp4'>".$repi5['declarante']."</td>
                    <td class='cp4'>".$repi5['nombre_empresa']."</td>
                    <td class='cp4'>".$repi5['rfc_empresa']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CLIENTE PRINCIPAL</td>
                    <td class='cp4t'>SEÑALE NOMBRE O RAZÓN SOCIAL DEL CLIENTE PRINCIPAL</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'>".$repi5['cliente_descr']."</td>
                    <td class='cp4'>".$repi5['nombre_cliente']."</td>
                    <td class='cp4'>".$repi5['rfc_cliente']."</td>
                    <td class='cp4'>".$repi5['sector_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>MONTO APROXIMADO DEL BENEFICIO O GANANCIA MENSUAL QUE OBTIENE DEL CLIENTE PRINCIPAL</td>
                    <td class='cp4'>".$repi5['monto_mensual']."</td>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp4'>".$repi5['ubicacion']."</td>
                  </tr>";}
                  $html5.="
                  <tr>
                    <td class='cp4t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='4'>".$repi5['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html5.="
                    <tr>
                    <td class='cv' colspan='4'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp4t'>¿REALIZA ALGUNA ACTIVIDAD LUCRATIVA INDEPENDIENTE AL EMPLEO, CARGO O COMISIÓN?</td>
                    <td class='cp4t'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t'>NOMBRE DE LA EMPRESA O SERVICIO QUE PROPORCIONA</td>
                    <td class='cp4t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t'>CLIENTE PRINCIPAL</td>
                    <td class='cp4t'>SEÑALE NOMBRE O RAZÓN SOCIAL DEL CLIENTE PRINCIPAL</td>
                    <td class='cp4t'>RFC</td>
                    <td class='cp4t'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t'>MONTO APROXIMADO DEL BENEFICIO O GANANCIA MENSUAL QUE OBTIENE DEL CLIENTE PRINCIPAL</td>
                    <td class='cp4'></td>
                    <td class='cp4t'>LUGAR DONDE SE UBICA</td>
                    <td class='cp4'></td>
                  </tr>
                  <tr>
                    <td class='cp4t' colspan='4'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp4' colspan='4'></td>
                  </tr>";
                  }
                 $html5.="                                 
                </table>";
                $html6="<table border='1' cellspacing='0'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 6.- BENEFICIOS PRIVADOS (HASTA LOS 2 ÚLTIMOS AÑOS)</td>
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
                    <td class='cp3t'>TIPO DE BENEFICIO</td>
                    <td class='cp3t'>BENEFICIARIO</td>
                    <td class='cp3t'>OTORGANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL OTORGANTE</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>FORMA DE RECEPCIÓN DEL BENEFICIO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESPECIFIQUE EL BENEFICIO</td>
                    <td class='cp3t'>MONTO MENSUAL APROXIMADO DEL BENEFICIO</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>";}
                    else{
                    $html6.="
                  <tr>
                    <td class='cp3t'>TIPO DE BENEFICIO</td>
                    <td class='cp3t'>BENEFICIARIO</td>
                    <td class='cp3t'>OTORGANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['tipo_beneficio']."</td>
                    <td class='cp3'>".$repi6['beneficiario_descr']."</td>
                    <td class='cp3'>".$repi6['otorgante_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL OTORGANTE</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>FORMA DE RECEPCIÓN DEL BENEFICIO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['nombre_otorga']."</td>
                    <td class='cp3'>".$repi6['rfc_otorga']."</td>
                    <td class='cp3'>".$repi6['forma_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESPECIFIQUE EL BENEFICIO</td>
                    <td class='cp3t'>MONTO MENSUAL APROXIMADO DEL BENEFICIO</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi6['beneficio_descr']."</td>
                    <td class='cp3'>".$repi6['monto_mensual']."</td>
                    <td class='cp3'>".$repi6['tipo_moneda']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
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
                    <td class='cp3t'>TIPO DE BENEFICIO</td>
                    <td class='cp3t'>BENEFICIARIO</td>
                    <td class='cp3t'>OTORGANTE</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL OTORGANTE</td>
                    <td class='cp3t'>RFC</td>
                    <td class='cp3t'>FORMA DE RECEPCIÓN DEL BENEFICIO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>ESPECIFIQUE EL BENEFICIO</td>
                    <td class='cp3t'>MONTO MENSUAL APROXIMADO DEL BENEFICIO</td>
                    <td class='cp3t'>TIPO DE MONEDA</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
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
                $html7.="<table border='1' cellspacing='0'  class='seccion2'>
                  <tr>
                    <td class='cs' colspan='3'>APARTADO 7.- FIDEICOMISOS (HASTA LOS 2 ÚLTIMOS AÑOS)</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE PARTICIPACIÓN EN FIDEICOMISOS DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti7)>0){
                  foreach ($reportei7 as $repi7) {
                  $html7.="
                  <tr>
                    <td class='cp3' colspan='3'>TIPO DE MOVIMIENTO: ".$repi7['movimiento']."</td>
                  </tr>";
                  if ($repi7['movimiento']=='NINGUNO'){
                    $html7.="
                  <tr>
                    <td class='cp3t'>PARTICIPACÓN EN FIDEICOMISOS</td>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TIPO DE FIDEICOMISO</td>
                    <td class='cp3t'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp3t'>RFC DEL FIDEICOMISO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>FIDEICOMITENTE</td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL FIDEICOMITENTE</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE O RAZÓN SOCIAL DEL FIDUCIARIO</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>FIDEICOMISARIO</td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL FIDEICOMISARIO</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                    <td class='cp3t'>¿DONDE SE LOCALIZA EL FIDEICOMISO?</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>NO APLICA</td>
                    <td class='cp3'>NO APLICA</td>
                  </tr>";}
                    else{
                    $html7.="
                  <tr>
                    <td class='cp3t'>PARTICIPACÓN EN FIDEICOMISOS</td>
                    <td class='cp3' colspan='2'>".$repi7['declarante']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TIPO DE FIDEICOMISO</td>
                    <td class='cp3t'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp3t'>RFC DEL FIDEICOMISO</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi7['tipo_descr']."</td>
                    <td class='cp3'>".$repi7['participa_descr']."</td>
                    <td class='cp3'>".$repi7['rfc_fideicomiso']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>FIDEICOMITENTE</td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL FIDEICOMITENTE</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi7['fideicomitente_descr']."</td>
                    <td class='cp3'>".$repi7['nom_fideicomitente']."</td>
                    <td class='cp3'>".$repi7['rfc_fideicomitente']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE O RAZÓN SOCIAL DEL FIDUCIARIO</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>".$repi7['nom_fiduciario']."</td>
                    <td class='cp3'>".$repi7['rfc_fiduciario']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>FIDEICOMISARIO</td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL FIDEICOMISARIO</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'>".$repi7['fideicomisario_descr']."</td>
                    <td class='cp3'>".$repi7['nom_fideicomisario']."</td>
                    <td class='cp3'>".$repi7['rfc_fideicomisario']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                    <td class='cp3t'>¿DONDE SE LOCALIZA EL FIDEICOMISO?</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'>".$repi7['sector_descr']."</td>
                    <td class='cp3'>".$repi7['ubicacion']."</td>
                  </tr>";}
                  $html7.="
                  <tr>
                    <td class='cp3t' colspan='3'>OBSERVACIONES</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='3'>".$repi7['observaciones']."</td>
                  </tr>";}}
                  else{
                    $html7.="
                    <tr>
                    <td class='cv' colspan='3'>SIN DATOS DECLARADOS</td>
                  </tr>
                  <tr>
                    <td class='cp3t'>PARTICIPACÓN EN FIDEICOMISOS</td>
                    <td class='cp3' colspan='2'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>TIPO DE FIDEICOMISO</td>
                    <td class='cp3t'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp3t'>RFC DEL FIDEICOMISO</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>FIDEICOMITENTE</td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL FIDEICOMITENTE</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>NOMBRE O RAZÓN SOCIAL DEL FIDUCIARIO</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3' colspan='2'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t'>FIDEICOMISARIO</td>
                    <td class='cp3t'>NOMBRE O RAZÓN SOCIAL DEL FIDEICOMISARIO</td>
                    <td class='cp3t'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                    <td class='cp3'></td>
                  </tr>
                  <tr>
                    <td class='cp3t' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                    <td class='cp3t'>¿DONDE SE LOCALIZA EL FIDEICOMISO?</td>
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
                 $html7.="                                 
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
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}  FIN');
  $mpdf->Output($td.$ti.$rfc.$ejercicio.'DA.pdf','I');