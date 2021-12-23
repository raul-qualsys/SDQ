<?php
require_once('../../vendor/autoload.php');
include("../../include/conexion.php");

$sql = "SELECT * FROM qsy_reporte_gral_vw";
$result=pg_query($conn,$sql);
$reporte=pg_fetch_all($result);



$html = "<body>
          <header class='clearfix'>
            <div id='logo'>
              <img src='../../css/images/logo2.png'>
            </div>
            <div id='company'>
              <h2 class='name'>Reporte General</h2>
              <p>Ciudad de México, Lunes 2 de Marzo de 2020<p>
              <p>pág 1 - 1</p>
            </div>
          </header>
    <main>
      <div id='details' class='clearfix'>
        <div id='client'>
          <h2 class='name'>Resultado para:</h2><br>
          <table>
           <tr>
            <th>Status: activo</th> 
            <th>Sexo: Mujer</th>
            <th>Grado de estudios: Licenciatura</th>
           </tr> 
           <tr>
            <th>Fecha de presentación:29-02-2020</th>            
            <th>Tipo de presentación: En tiempo</th>
           </tr>
          </table>
        </div>
      </div>
      <table border='0' cellspacing='0' cellpadding='0'>
        <thead class = 'ethead'>
          <tr class='etr'>
            <th class='eth'>CONSECUTIVO</th>
            <th class='eth'>SERVIDOR  <br> PUBLICO</th>
            <th class='eth'>CURP</th>
            <th class='eth'>RFC</th>
            <th class='eth'>EDAD</th>
            <th class='eth'>SEXO</th>
            <th class='eth'>GRADO DE ESTUDIOS</th>
            <th class='eth'>STATUS</th>
            <th class='eth'>PUESTO</th>
            <th class='eth'>AREA DE ADSCRIPCIÓN</th>
            <th class='eth'>TIPO DE DECLARACIÓN</th>
            <th class='eth'>EJERCICIO EN QUE DECLARA</th>
            <th class='eth'>FECHA DE DECLARACIÓN</th>
            <th class='eth'>TIPO DE PRESENTACIÓN</th>
          </tr>
        </thead>
        <tbody>";
        $cont = 1;
        foreach ($reporte as $rep) {

                  $html.="<tr>
                            <td class='no'>".$cont."</td>
                            <td>".$rep['nombre'].' '.$rep['primer_ap'].' '.$rep['segundo_ap']."</td>
                            <td>".$rep['curp']."</td>
                            <td>".$rep['rfc']."</td>
                            <td>".$rep['edad']."</td>
                            <td>".$rep['sexo']."</td>
                            <td>".$rep['nivel_escolar']."</td>
                            <td>".$rep['estatus_empleado']."</td>
                            <td>".$rep['puesto']."</td>
                            <td>".$rep['adscripcion']."</td>
                            <td>".$rep['tipo_decl']."</td>
                            <td>".$rep['ejercicio']."</td>
                            <td>".$rep['fecha_presenta']."</td>
                            <td>".$rep['estatus_decl']."</td>
                          </tr>";
                          $cont ++;
                        }

        $html.="</tbody>
      </table>
    </main>
  </body>";

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$css = file_get_contents('css/style.css');
$mpdf->writeHtml($css,1);
$mpdf->writeHtml($html);

$mpdf->Output();