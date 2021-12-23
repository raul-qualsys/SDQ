  <?php
  require_once('../../../vendor/autoload.php');
  include("../../../include/conexion.php");
  include("../../../include/constantes.php");

$rfc = $_GET['rfc'];

if (isset($_POST['pdf'])) {
    //Recogemos las claves enviadas
    $ej = $_POST['ejercicio_pd'];
    $decl = $_POST['declaracion_pd'];
    $tip = $_POST['tipodecl_pd'];
    $est = $_POST['estatusdecl_pd'];
    $ads = $_POST['area_pd'];
    $pto = $_POST['puesto_pd'];
    $jr = $_POST['check_list_pd'];

if (empty($ej) AND empty($decl) AND empty($tip) AND empty($est) AND empty($ads) AND empty($pto))
  {
    echo'<script type="text/javascript">
        alert("Debe seleccionar un parámetro para generar el documento");
        window.close();
        </script>';
  }
  else{

    $sqv="SELECT *
  FROM qsy_indicadores_vw 
  WHERE (ejercicio in (".$jr."))
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion like '%".$ads."%' or '".$ads."'='')
  and (puesto like '%".$pto."%' or '".$pto."'='')";
  $resulv=pg_query($conn,$sqv);
  $reporv=pg_fetch_all($resulv);

  if (pg_num_rows($resulv)<=0)
 {
    echo'<script type="text/javascript">
        alert("No se encontraron resultados");
        window.close();
        </script>';
   }
   else{ 

  $z=$reporv[0]['adscripcion'];

  $z = $ads;

  $pr=$reporv[0]['puesto'];

  $pr = $pto;

$direccion = CALLE_EMPRESA." ".EXT_EMPRESA." ".COL_COMPLETE." ".MUN_COMPLETE."<br>".CP_EMPRESA." ".EST_COMPLETE;

    $sql="SELECT distinct ejercicio
FROM qsy_indicadores_vw 
where (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA') 
  and (ejercicio in (".$jr."))
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
$sqle=pg_query($conn,$sql);
$ejer=pg_fetch_all($sqle); 

$sqli="SELECT count(*) as ti FROM qsy_indicadores_vw
WHERE tipo_decl = 'INICIAL' 
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
and (ejercicio in (".$jr."))
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
$sqli=pg_query($conn,$sqli);
$ini=pg_fetch_all($sqli); 
$ti = $ini[0]['ti'];

$sqlm="SELECT count(*) as tm FROM qsy_indicadores_vw
WHERE tipo_decl = 'MODIFICACIÓN' 
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
and (ejercicio in (".$jr."))
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
$sqlm=pg_query($conn,$sqlm);
$mod=pg_fetch_all($sqlm); 
$tm = $mod[0]['tm'];

$sqlc="SELECT count(*) as tc FROM qsy_indicadores_vw
WHERE tipo_decl = 'CONCLUSIÓN' 
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
and (ejercicio in (".$jr."))
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
$sqlc=pg_query($conn,$sqlc);
$conc=pg_fetch_all($sqlc); 
$tc = $conc[0]['tc']; 

$logo1="<img src='".HTTP_PATH."/css/images/qsy_logo_nivel.png' style='height:70px;width: auto;'>";
$logo2="<img src='".HTTP_PATH."/css/images/qsy_logo_depend.png' style='height:70px;width: auto;'>";

  $html1="<body>
            <header class='clearfix'>
              <div id='logo1'>
                $logo1
              </div>
              <p id='til'>
                Cumplimiento de Declaraciones
              <p>
              <div id='logo2'>
                $logo2
              </div>
            </header>
              <main>
              <table border='1' cellspacing='0'  class='seccion2'>
                 <tr>
                 <td class='cs' colspan='7';>Filtros</td>
                 </tr>
                  <tr>
                    <td class='cp5t'>Ejercicio</td>
                    <td class='cp5t'>Comparativo</td>
                    <td class='cp5t'>Declaración</td>
                    <td class='cp5t'>Tipo Declaración</td>
                    <td class='cp5t'>Estatus Declaración</td>
                    <td class='cp5t'>Área de Adscripción</td>
                    <td class='cp5t'>Puesto</td>
                  </tr>
                  <tr>
                    <td class='cp5'>".$ej."</td>
                    <td class='cp5'>".$jr."</td>
                    <td class='cp5'>".$decl."</td>
                    <td class='cp5'>".$tip."</td>
                    <td class='cp5'>".$est."</td>
                    <td class='cp5'>".$ads."</td>
                    <td class='cp5'>".$pto."</td>
                  </tr>
               </table>
               <table border='1' cellspacing='0'  class='seccion2'>
                 <tr>
                 <td class='cs' colspan='5';>Grafico</td>
                 </tr>
                  <tr>
                    <td class='csi' colspan='5';><img src='imagenseccion/archivos/ejercicios".$rfc.".png' class='graf'></td>
                  </tr>
                  </table>
               <table border='1' cellspacing='0'  class='seccion2'>
                 <tr>
                 <td class='cs' colspan='4';>Resumen</td>
                 </tr>
                  <tr>
                    <td class='cp4t'>Ejercicios Fiscales</td>
                    <td class='cp4t'>Inicial</td>
                    <td class='cp4t'>Modificación</td>
                    <td class='cp4t'>Conclusión</td>
                  </tr>";
                  foreach ($ejer as $eje) {

                    $sqlm="SELECT count(*) as m FROM qsy_indicadores_vw
                    WHERE tipo_decl = 'MODIFICACIÓN' 
                    and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
                    and ejercicio = '".$eje['ejercicio']."'
                    and (ejercicio in (".$jr."))
                    and (declaracion ='".$decl."' or '".$decl."'='')
                    and (tipo_decl ='".$tip."' or '".$tip."'='')
                    and (estatus_decl ='".$est."' or '".$est."'='')
                    and (adscripcion = '".$ads."' or '".$ads."'='')
                    and (puesto = '".$pto."' or '".$pto."'='')";
                    $sqlm=pg_query($conn,$sqlm);
                    $mod=pg_fetch_all($sqlm); 
                    $m = $mod[0]['m'];

                    $sqli="SELECT count(*) as i FROM qsy_indicadores_vw
                    WHERE tipo_decl = 'INICIAL' 
                    and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
                    and ejercicio = '".$eje['ejercicio']."'
                    and (ejercicio in (".$jr."))
                    and (declaracion ='".$decl."' or '".$decl."'='')
                    and (tipo_decl ='".$tip."' or '".$tip."'='')
                    and (estatus_decl ='".$est."' or '".$est."'='')
                    and (adscripcion = '".$ads."' or '".$ads."'='')
                    and (puesto = '".$pto."' or '".$pto."'='')";
                    $sqli=pg_query($conn,$sqli);
                    $ini=pg_fetch_all($sqli); 
                    $i = $ini[0]['i'];

                    $sqlc="SELECT count(*) as c FROM qsy_indicadores_vw
                    WHERE tipo_decl = 'CONCLUSIÓN' 
                    and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
                    and ejercicio = '".$eje['ejercicio']."'
                    and (ejercicio in (".$jr."))
                    and (declaracion ='".$decl."' or '".$decl."'='')
                    and (tipo_decl ='".$tip."' or '".$tip."'='')
                    and (estatus_decl ='".$est."' or '".$est."'='')
                    and (adscripcion = '".$ads."' or '".$ads."'='')
                    and (puesto = '".$pto."' or '".$pto."'='')";
                    $sqlc=pg_query($conn,$sqlc);
                    $conc=pg_fetch_all($sqlc); 
                    $c = $conc[0]['c'];
                                      
                 $html1 .="<tr>
                    <td class='cp4'>".$eje['ejercicio']."</td>
                    <td class='cp4'>".$i."</td>
                    <td class='cp4'>".$m."</td>
                    <td class='cp4'>".$c."</td>
                  </tr>";}
               $html1 .="<tr>
                    <td class='cp4t'>Total</td>
                    <td class='cp4'>".$ti."</td>
                    <td class='cp4'>".$tm."</td>
                    <td class='cp4'>".$tc."</td>
                  </tr>
               </table>
                
            </main>
    </body>";
// print_r($html1);
  $mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('../css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html1);
  $mpdf->SetFooter($direccion);
  $mpdf->Output('ReporteEjerciciosFiscales.pdf','I');
}}}
  ?>