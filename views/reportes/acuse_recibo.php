  <?php
  require_once('../../vendor/autoload.php');
  include("../../include/conexion.php");
  include("../../include/funciones.php");
  require 'codigo_qr/phpqrcode/qrlib.php';
  include("../../include/constantes.php");

$direccion = CALLE_EMPRESA." ".EXT_EMPRESA." ".COL_COMPLETE." ".MUN_COMPLETE."<br>".CP_EMPRESA." ".EST_COMPLETE;

function actual_date()  
{  
    $week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");  
    $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");  
    $year_now = date ("Y");  
    $month_now = date ("n");  
    $day_now = date ("j");  
    $week_day_now = date ("w");  
    $date = $day_now . " de " . $months[$month_now] . " de " . $year_now;   
    return $date;    
} 

$fechaActual = "Ciudad de México, a ".actual_date();
$nombre=get_nombre($conn,$_POST["rfc"]);
$empleo=get_empleo($conn,$_POST["rfc"],$_POST["ejercicio"],$_POST["tipo_decl"],$_POST["declaracion"]);
$area=get_area($conn,$_POST["rfc"],$_POST["ejercicio"],$_POST["tipo_decl"],$_POST["declaracion"]);
$sexo=get_sexo($conn,$_POST["rfc"]);
$folio=get_folio($conn,$_POST["rfc"],$_POST["ejercicio"],$_POST["tipo_decl"],$_POST["declaracion"]);

$rfc = $_POST["rfc"];
$ejercicio = $_POST["ejercicio"];
$t_decl = '';
$dec = '';
$frac = '';
$frac2 = '';
$sx = '';
$sx2 = '';

if($sexo =="M")
{
  $sx='la';
  $sx2 = 'adscrita';
  $sx3 = 'designada';
}
else
{ $sx = 'el';
  $sx2 = 'adscrito';
  $sx3 = 'designado';
}

if($_POST["declaracion"]=="P"){

  $dec = "PATRIMONIAL";

if($_POST["tipo_decl"] == "I")
{
  $t_decl = 'INICIAL';
  $frac = '33 Fracción I, incisos a y b, 34 primer párrafo y 38 ';
  $frac2 = 'fracción I, inciso b)';
}
elseif($_POST["tipo_decl"] == "M")
{
  $t_decl = 'MODIFICACIÓN';
  $frac = '33 fracción II, 34 párrafo primero y 38 ';
  $frac2 = 'fracción I';
}

elseif($_POST["tipo_decl"] == "C")
{
  $t_decl = 'CONCLUSIÓN';
  $frac = '33 fracción III, 34 párrafo primero y 38';
  $frac2 = 'fracción I';
}}

else{
  $dec = "INTERESES";
  $frac2 = 'fracción I e incisos a) y b)';
  $frac = '35 y 38 ';

  if($_POST["tipo_decl"] == "I")
{
  $t_decl = 'INICIAL';
}
elseif($_POST["tipo_decl"] == "M")
{
  $t_decl = 'MODIFICACIÓN';
}

elseif($_POST["tipo_decl"] == "C")
{
  $t_decl = 'CONCLUSIÓN';
}
}


$dir = 'codigo_qr/temp/';
  if(!file_exists($dir))
  mkdir($dir);

$filename = $dir.'codigoqr'.$rfc.'.png';
$tamanio = 2;
$level = 'M';
$frameSize = 3;
$contenido = 'LUGAR Y FECHA DE ACUSE: '.$fechaActual.PHP_EOL.
             ' SERVIDOR PUBLICO: '.$nombre.PHP_EOL.
             ' RFC: '.$rfc.PHP_EOL.
             ' PUESTO: '.$empleo.PHP_EOL.
             ' AREA DE ADSCRIPCIÓN: '.$area.PHP_EOL.
             ' DECLARACIÓN: '.$dec.PHP_EOL.
             ' TIPO DE DECLARACIÓN: '.$t_decl.PHP_EOL.
             ' EJERCICIO: '.$ejercicio.PHP_EOL.
             ' FOLIO: '.$folio.PHP_EOL;


QRcode::png ($contenido, $filename, $level, $tamanio, $frameSize);

$logo1="<img src='".HTTP_PATH."/css/images/qsy_logo_nivel.png' style='height:70px;width: auto;'>";
$logo2="<img src='".HTTP_PATH."/css/images/qsy_logo_depend.png' style='height:70px;width: auto;'>";

/*28-08-2020 DMQ-Qualsys Adaptación para cambiar los textos del acuse.*/
  $html = "<body>
            <header class='clearfix'>
              <div id='logo1'>
                $logo1
              </div>
              <div id='logo2'>
                $logo2
              </div>
            </header>
            <p id='subtil2'>ACUSE DE RECIBO</p>
              <main>
                <table>

                  <tr>
                    
                    <td class='cpfo' colspan='2'>Folio: $folio.</td><br> <br> <br> <br>
                    <td class='cpfe' colspan='2'><label>".$fechaActual."</label></td>
                  </tr> 
                  <br> <br> <br> <br>
                  <tr>
                    <td class='ar' colspan='4'>";
                      $texto=get_notificacion(7,$conn);
                      $texto=str_replace("{sexo}", $sx, $texto);
                      $texto=str_replace("{nombre}", $nombre, $texto);
                      $texto=str_replace("{sexo2}", $sx2, $texto);
                      $texto=str_replace("{puesto}", $empleo, $texto);
                      $texto=str_replace("{area}", $area, $texto);
                      $texto=str_replace("{frac}", $frac2, $texto);
                      $html.="<p>$texto</p>
                    </td>
                  </tr> 
                  <br> <br> <br>
                  <tr>";
                  if($_POST["declaracion"]=="P")
                  $html.="
                    <td class='cp' colspan=4>Declaración ".$t_decl." de Situación Patrimonial</td>";
                  else
                  $html.="
                    <td class='cp' colspan=4>Declaración ".$t_decl." de Conflicto de Intereses</td>";
                  $html.="</tr> 
                  <br><br> <br>
                  <tr>
                    <td class='ar' colspan='4'>";
                      $texto=get_notificacion(8,$conn);
                      $texto=str_replace("{frac}", $frac, $texto);
                      $html.="<p>$texto</p>
                    </td>
                  </tr>
                  <br> <br> <br> <br> <br> <br> <br> <br> 
                  <tr>
                    <td class='cp' colspan='4'>
                      <img src=".$filename." />
                    </td>
                  </tr> 
                </table>
            </main>
    </body>";
/* Fin de actualización. */

  $mpdf = new \Mpdf\Mpdf([]);
  $css = file_get_contents('css/style.css');
  $mpdf->writeHtml($css,1);
  $mpdf->writeHtml($html);
  $mpdf->SetFooter($direccion);
   $mpdf->Output($folio.'.pdf','I');