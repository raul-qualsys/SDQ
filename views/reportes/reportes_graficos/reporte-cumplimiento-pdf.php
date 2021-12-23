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
  WHERE (ejercicio ='".$ej."' or '".$ej."' = '')
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion like '%".$ads."%' or '".$ads."'='')
  and (puesto like '%".$pto."%' or '".$pto."'='')
  order by rfc asc";
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

 	$sqlp = "SELECT count (*) as p FROM qsy_indicadores_vw
   where estatus_decl = 'PRESENTADA' 
  and (ejercicio ='".$ej."' or '".$ej."' = '')
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
   $resulp=pg_query($conn,$sqlp);
   $resultadop=pg_fetch_all($resulp);
   $pres = $resultadop[0]['p'];
   
   $sqlo = "SELECT count (*) as o FROM qsy_indicadores_vw
   where estatus_decl = 'OMISA'
   and (ejercicio ='".$ej."' or '".$ej."' = '')
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
   $resulo=pg_query($conn,$sqlo);
   $resultado=pg_fetch_all($resulo);
   $omis = $resultado[0]['o'];
   
   $sqle = "SELECT count (*) as e FROM qsy_indicadores_vw
   where estatus_decl = 'EN TIEMPO'
  and (ejercicio ='".$ej."' or '".$ej."' = '')
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
   $resule=pg_query($conn,$sqle);
   $resultadoe=pg_fetch_all($resule); 
   $tiem = $resultadoe[0]['e'];
   
   $sqlx = "SELECT count (*) as x FROM qsy_indicadores_vw
   where estatus_decl = 'EXTEMPORANEA'
   and (ejercicio ='".$ej."' or '".$ej."' = '')
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')";
   $resulx=pg_query($conn,$sqlx);
   $resultadox=pg_fetch_all($resulx); 
   $ext = $resultadox[0]['x'];

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
                 <td class='cs' colspan='6';>Filtros</td>
                 </tr>
                  <tr>
                    <td class='cp5t'>Ejercicio</td>
                    <td class='cp5t'>Declaración</td>
                    <td class='cp5t'>Tipo Declaración</td>
                    <td class='cp5t'>Estatus Declaración</td>
                    <td class='cp5t'>Área de Adscripción</td>
                    <td class='cp5t'>Puesto</td>
                  </tr>
                  <tr>
                    <td class='cp5'>".$ej."</td>
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
                    <td class='csi' colspan='5';><img src='imagenseccion/archivos/cumplimiento".$rfc.".png' class='graf'></td>
                  </tr>
                  </table>
               <table border='1' cellspacing='0'  class='seccion2'>
                 <tr>
                 <td class='cs' colspan='2';>Resumen</td>
                 </tr>
                  <tr>
                    <td class='cp2t'>Estatus Declaración</td>
                    <td class='cp2t'>Total</td>
                  </tr>
                  <tr>
                    <td class='cp5'>Presentadas</td>
                    <td class='cp5'>".$pres."</td>
                  </tr>
                  <tr>
                    <td class='cp5'>Omisa</td>
                    <td class='cp5'>".$omis."</td>
                  </tr>
                  <tr>
                    <td class='cp5'>En Tiempo</td>
                    <td class='cp5'>".$tiem."</td>
                  </tr>
                  <tr>
                    <td class='cp5'>Extemporaneas</td>
                    <td class='cp5'>".$ext."</td>
                  </tr>
               </table>
            </main>
    </body>";

  $mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('../css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html1);
  $mpdf->SetFooter($direccion);
  $mpdf->Output('ReporteCumplimiento.pdf','I');
}}}
  ?>