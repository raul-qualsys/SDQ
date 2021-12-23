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

if(isset($_POST["rfc"])){
  $rfc = $_POST['rfc'];
  $ejercicio = $_POST['ejercicio'];
  $tipo_decl = $_POST['tipo_decl'];
  $tipo_decl=substr($tipo_decl, 0,1);
}
else{
  $rfc = $_GET['rfc'];
  $ejercicio = $_GET['ejercicio'];
  $tipo_decl = $_GET['tipo_decl'];
  $tipo_decl=substr($tipo_decl, 0,1);
}

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
FROM qsy_participa_empresas where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
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
FROM qsy_decisiones where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
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
FROM qsy_apoyos  where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
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
FROM qsy_representaciones where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
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
FROM qsy_clientes where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
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
FROM qsy_beneficios_privados where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
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
FROM qsy_fideicomisos where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
$resulti7=pg_query($conn,$sqli7);
$reportei7=pg_fetch_all($resulti7);

if(isset($_POST["rfc"])){
  $nombre=get_nombre($conn,$_POST["rfc"]);
  $rfc = $_POST["rfc"];
  $sexo=get_sexo($conn,$_POST["rfc"]);
  $sx = '';
}
else{
  $nombre=get_nombre($conn,$_GET["rfc"]);
  $rfc = $_GET["rfc"];
  $sexo=get_sexo($conn,$_GET["rfc"]);
  $sx = '';  
}

if($sexo =="M")
{$sx='LA';}
else
{ $sx = 'EL';}

$ti= substr($tipo_decl, 0,1);
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

$sqlo = "SELECT *
  FROM qsy_declaraciones
  where tipo_decl = '$tipo_decl' AND rfc='$rfc' AND ejercicio=$ejercicio";
  $resulo=pg_query($conn,$sqlo);
  $reporo=pg_fetch_all($resulo);

#Denise Cigarroa 21/08/2020#
#Aqui se manda a llamar los logos para el cabecero de la declaración#
$logo1="<img src='".HTTP_PATH."/css/images/qsy_logo_nivel.png' style='height:70px;width: auto;'>";
$logo2="<img src='".HTTP_PATH."/css/images/qsy_logo_depend.png' style='height:70px;width: auto;'>";
################################################################################################

  if($reporo[0]['conformidad']=='N'){
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
              <h5>EL SERVIDOR PÚBLICO NO DIO SU AUTORIZACIÓN PARA HACER PÚBLICA SU DECLARACIÓN DE CONFLICTO DE INTERESES</h5>
              </main>
            </body>";
  }
  
  else{
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
                    <td class='rpt'>
                      ".$nombre."
                    </td>
                  </tr> 
                  <tr>
                    <td class='rpt'>
                      FECHA DE LA DECLARACIÓN: ".$reporo[0]['fecha_presenta']." 
                    </td>
                  </tr>";
                  if (pg_num_rows($resulti1)>0){
                  $html1.="
                  <tr><td class='rpt'>DECLARACIÓN DE INTERESES</td></tr>
                  <tr><td class='rpt'>".$tdi."</td></tr>";
                }else{
                  $html1.="
                  class='rpt'>DECLARACIÓN DE INTERESES</td>";
                }

                $html1.="</tr>  
                </table>
                <table>
                  <caption class='rps'>PARTICIPACIÓN EN EMPRESAS, SOCIEDADES O ASOCIACIONES (HASTA LOS 2 ÚLTIMOS AÑOS)
                  </caption>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LA PARTICIPACIÓN EN EMPRESAS, SOCIEDADES O ASOCIACIONES DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti1)>0){
                  foreach ($reportei1 as $repi1) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi1['movimiento']."</td>
                  </tr>";
                  if ($repi1['movimiento']=='NINGUNO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t_nv'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t_nv'>NOMBRE DE LA EMPRESA, SOCIEDAD O ASOCIACIÓN</td>
                    <td class='cp3t_nv'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi1['declarante']."</td>
                    <td class='cp3_nv'>".$repi1['nombre_empresa']."</td>
                    <td class='cp3_nv'>".$repi1['rfc_empresa']."</td>
                  </tr> 
                  <tr>
                    <td class='cp3t_nv'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp3t_nv'>PORCENTAJE DE PARTICIPACIÓN DE ACUERDO A ESCRITURA</td>
                    <td class='cp3t_nv'>¿RECIBE REMUNERACIÓN POR SU PARTICIPACIÓN?</td>
                    
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi1['tipo_part_descr']."</td>
                    <td class='cp3_nv'>".$repi1['pct_participacion']."</td>
                    <td class='cp3_nv'>".$repi1['remuneracion']."</td>
                    
                  </tr> 
                  <tr>
                    <td class='cp3t_nv'>MONTO MENSUAL NETO</td>
                    <td class='cp3t_nv'>LUGAR DONDE SE UBICA</td>
                    <td class='cp3t_nv'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi1['monto_mensual']."</td>
                    <td class='cp3_nv'>".$repi1['ubicacion']."</td>
                    <td class='cp3_nv'>".$repi1['sector_descr']."</td>
                  </tr>";}}}
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
                </table>";

                $html1.="<table>
                  <caption class='rps'>¿PARTICIPA EN LA TOMA DE DECISIONES DE ALGUNA DE ESTAS INSTITUCIONES? (HASTA LOS 2 ÚLTIMOS AÑOS)
                  </caption>
                  <tr>
                    <td class='ct' colspan='3'>TODOS LOS DATOS DE LA PARTICIPACIÓN EN ALGUNA DE ESTAS INSTITUCIONES DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti2)>0){
                  foreach ($reportei2 as $repi2) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi2['movimiento']."</td>
                  </tr>";
                  if ($repi2['movimiento']=='NINGUNO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t_nv'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp3t_nv'>TIPO DE INSTITUCIÓN</td>
                    <td class='cp3t_nv'>LUGAR DONDE SE UBICA</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi2['declarante']."</td>
                    <td class='cp3_nv'>".$repi2['tipo_institucion']."</td>
                    <td class='cp3_nv'>".$repi2['ubicacion']."</td>
                  </tr> 
                  ";}}}
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
                </table>";
                $html1.="<table>
                  <caption class='rps'>APOYOS O BENEFICIOS PÚBLICOS (HASTA LOS 2 ÚLTIMOS AÑOS)
                  </caption>";
                  if (pg_num_rows($resulti3)>0){
                  foreach ($reportei3 as $repi3) {
                  $html1.="
                  <tr>
                    <td class='cp4_nv' colspan='4'>TIPO DE MOVIMIENTO: ".$repi3['movimiento']."</td>
                  </tr>";
                  if ($repi3['movimiento']=='NINGUNO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp4t_nv'>NOMBRE DEL PROGRAMA</td>
                    <td class='cp4t_nv'>INSTITUCIÓN QUE OTORGA EL APOYO</td>
                    <td class='cp4t_nv'>NIVEL U ORDEN DE GOBIERNO</td>
                    <td class='cp4t_nv'>TIPO DE APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi3['nombre_prog']."</td>
                    <td class='cp4_nv'>".$repi3['instit_otorgante']."</td>
                    <td class='cp4_nv'>".$repi3['orden_descr']."</td>
                    <td class='cp4_nv'>".$repi3['tipo_apoyo']."</td>
                  </tr> 
                   <tr>
                    <td class='cp4t_nv'>FORMA DE RECEPCIÓN DEL APOYO</td>
                    <td class='cp4t_nv'>MONTO APROXIMADO DEL APOYO MENSUAL</td>
                    <td class='cp4t_nv' colspan='2'>ESPECIFIQUE EL APOYO</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi3['forma_descr']."</td>
                    <td class='cp4_nv'>".$repi3['monto_mensual']."</td>
                    <td class='cp4_nv' colspan='2'>".$repi3['apoyo_descr']."</td>
                  </tr>";}}}
                  else{
                    $html1.="
                    <tr>
                    <td class='rpt'>
                      SIN INFORMACIÓN
                    </td>
                    </tr>";
                  }
                 $html1.="                                 
                </table>";
                $html1.="<table>
                  <caption class='rps'>REPRESENTACIÓN (HASTA LOS 2 ÚLTIMOS AÑOS)
                  </caption>
                  <tr>
                    <td class='ct' colspan='4'>TODOS LOS DATOS DE REPRESENTACIÓN DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti4)>0){
                  foreach ($reportei4 as $repi4) {
                  $html1.="
                  <tr>
                    <td class='cp4_nv' colspan='4'>TIPO DE MOVIMIENTO: ".$repi4['movimiento']."</td>
                  </tr>";
                  if ($repi4['movimiento']=='NINGUNO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp4t_nv'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t_nv'>TIPO DE PRESENTACIÓN</td>
                    <td class='cp4t_nv'>FECHA DE INICIO</td>
                    <td class='cp4t_nv'>¿RECIBE REMUNERACIÓN POR SU REPRESENTACIÓN?</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi4['declarante']."</td>
                    <td class='cp4_nv'>".$repi4['tipo_descr']."</td>
                    <td class='cp4_nv'>".$repi4['fecha_inicio']."</td>
                    <td class='cp4_nv'>".$repi4['remuneracion']."</td>
                  </tr> 
                  <tr>
                    <td class='cp4t_nv'>MONTO MENSUAL NETO DE SU REPRESENTACIÓN</td>
                    <td class='cp4t_nv'>LUGAR DONDE SE UBICA</td>
                    <td class='cp4t_nv' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                  <td class='cp4_nv'>".$repi4['monto_mensual']."</td>
                    <td class='cp4_nv'>".$repi4['ubicacion']."</td>
                    <td class='cp4_nv' colspan='2'>".$repi4['sector_descr']."</td>
                  </tr>";}}}
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
                </table>";
                $html1.="<table>
                  <caption class='rps'>CLIENTES PRINCIPALES (HASTA LOS 2 ÚLTIMOS AÑOS)
                  </caption>
                  <tr>
                    <td class='ct' colspan='4'>TODOS LOS DATOS DE CLIENTES PRINCIPALES DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>
                  <tr>
                    <td class='ct' colspan='4'>SE MANIFESTARÁ EL BENEFICIO O GANANCIA DIRECTA DEL DECLARANTE SI SUPERA MENSUALMENTE 250 UNIDADES DE MEDIDA Y ACTUALIZACIÓN (UMA)</td>
                  </tr>";
                  if (pg_num_rows($resulti5)>0){
                  foreach ($reportei5 as $repi5) {
                  $html1.="
                  <tr>
                    <td class='cp4_nv' colspan='4'>TIPO DE MOVIMIENTO: ".$repi5['movimiento']."</td>
                  </tr>";
                  if ($repi5['movimiento']=='NINGUNO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp4t_nv'>¿REALIZA ALGUNA ACTIVIDAD LUCRATIVA INDEPENDIENTE AL EMPLEO, CARGO O COMISIÓN?</td>
                    <td class='cp4t_nv'>¿DECLARANTE, PAREJA O DEPENDIENTE ECONÓMICO?</td>
                    <td class='cp4t_nv'>NOMBRE DE LA EMPRESA O SERVICIO QUE PROPORCIONA</td>
                    <td class='cp4t_nv'>RFC</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi5['actividad']."</td>
                    <td class='cp4_nv'>".$repi5['declarante']."</td>
                    <td class='cp4_nv'>".$repi5['nombre_empresa']."</td>
                    <td class='cp4_nv'>".$repi5['rfc_empresa']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv' colspan='2'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                    <td class='cp4t_nv'>MONTO APROXIMADO DEL BENEFICIO O GANANCIA MENSUAL QUE OBTIENE DEL CLIENTE PRINCIPAL</td>
                    <td class='cp4t_nv'>LUGAR DONDE SE UBICA</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv' colspan='2'>".$repi5['sector_descr']."</td>
                    <td class='cp4_nv'>".$repi5['monto_mensual']."</td>
                    <td class='cp4_nv'>".$repi5['ubicacion']."</td>
                  </tr>";}}}
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
                </table>";
                $html1.="<table>
                  <caption class='rps'>BENEFICIOS PRIVADOS (HASTA LOS 2 ÚLTIMOS AÑOS)
                  </caption>";
                  if (pg_num_rows($resulti6)>0){
                  foreach ($reportei6 as $repi6) {
                  $html1.="
                  <tr>
                    <td class='cp3_nv' colspan='3'>TIPO DE MOVIMIENTO: ".$repi6['movimiento']."</td>
                  </tr>";
                  if ($repi6['movimiento']=='NINGUNO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp3t_nv'>TIPO DE BENEFICIO</td>
                    <td class='cp3t_nv'>FORMA DE RECEPCIÓN DEL BENEFICIO</td>
                    <td class='cp3t_nv'>ESPECIFIQUE EL BENEFICIO</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi6['tipo_beneficio']."</td>
                    <td class='cp3_nv'>".$repi6['forma_descr']."</td>
                    <td class='cp3_nv'>".$repi6['beneficio_descr']."</td>
                  </tr>
                  <tr>
                    <td class='cp3t_nv'>MONTO MENSUAL APROXIMADO DEL BENEFICIO</td>
                    <td class='cp3t_nv'>TIPO DE MONEDA</td>
                    <td class='cp3t_nv'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                  </tr>
                  <tr>
                    <td class='cp3_nv'>".$repi6['monto_mensual']."</td>
                    <td class='cp3_nv'>".$repi6['tipo_moneda']."</td>
                    <td class='cp3_nv'>".$repi6['sector_descr']."</td>
                  </tr>";}}}
                  else{
                    $html1.="
                    <tr>
                    <td class='rpt'>
                      SIN INFORMACIÓN
                    </td>
                    </tr>";
                  }
                 $html1.="                                 
                </table>";
                $html1.="<table>
                  <caption class='rps'>FIDEICOMISOS (HASTA LOS 2 ÚLTIMOS AÑOS)
                  </caption>
                  <tr>
                    <td class='ct' colspan='4'>TODOS LOS DATOS DE PARTICIPACIÓN EN FIDEICOMISOS DE LA PAREJA O DEPENDIENTES ECONÓMICOS NO SERÁN PÚBLICOS</td>
                  </tr>";
                  if (pg_num_rows($resulti7)>0){
                  foreach ($reportei7 as $repi7) {
                  $html1.="
                  <tr>
                    <td class='cp4_nv' colspan='4'>TIPO DE MOVIMIENTO: ".$repi7['movimiento']."</td>
                  </tr>";
                  if ($repi7['movimiento']=='NINGUNO'){
                    $html1.=" ";}
                    else{
                    $html1.="
                  <tr>
                    <td class='cp4t_nv'>PARTICIPACÓN EN FIDEICOMISOS</td>
                    <td class='cp4t_nv'>TIPO DE FIDEICOMISO</td>
                    <td class='cp4t_nv'>TIPO DE PARTICIPACIÓN</td>
                    <td class='cp4t_nv'>RFC DEL FIDEICOMISO</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi7['declarante']."</td>
                    <td class='cp4_nv'>".$repi7['tipo_descr']."</td>
                    <td class='cp4_nv'>".$repi7['participa_descr']."</td>
                    <td class='cp4_nv'>".$repi7['rfc_fideicomiso']."</td>
                  </tr>
                  <tr>
                    <td class='cp4t_nv'>NOMBRE O RAZÓN SOCIAL DEL FIDUCIARIO</td>
                    <td class='cp4t_nv'>RFC</td>
                    <td class='cp4t_nv'>SECTOR PRODUCTIVO AL QUE PERTENECE</td>
                    <td class='cp4t_nv'>¿DONDE SE LOCALIZA EL FIDEICOMISO?</td>
                  </tr>
                  <tr>
                    <td class='cp4_nv'>".$repi7['nom_fiduciario']."</td>
                    <td class='cp4_nv'>".$repi7['rfc_fiduciario']."</td>
                    <td class='cp4_nv'>".$repi7['sector_descr']."</td>
                    <td class='cp4_nv'>".$repi7['ubicacion']."</td>
                  </tr>";}}}
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
                </table>
            </main>
    </body>";}

  $mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html1);
  $mpdf->SetFooter($direccion.' PÁGINA: {PAGENO}');
  $mpdf->Output($td.$ti.$rfc.$ejercicio.'DP.pdf','I');