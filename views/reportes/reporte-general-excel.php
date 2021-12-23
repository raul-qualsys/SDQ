<?php
/* Denise Cigarroa 21/08/2020
En este partado se modificaron los parametros que entran para filtrar los parametros */
require_once('../../vendor/autoload.php');
include("../../include/conexion.php");
include("../../include/constantes.php");


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// Denise Cigarroa 21/08/2020
// Se agrego una variable para el color de general del reporte en excel
//print_r(1);die;
$color = color;
// Denise Cigarroa 21/08/2020
// Se mofificaron los campos de entrada para filtrar en la consulta 
ini_set('memory_limit', '1024M');
//print_r("1");die;
if (isset($_POST['enlace'])) {
    //Recogemos las claves enviadas
    $decl = $_POST['declaracion'];
    $t_decl = $_POST['tip_declaracion'];
    $ejer = $_POST['ejerciciom'];
    $est_decl = $_POST['stus_m'];
    $serv = $_POST['servidor'];
    $est_serv = $_POST['estatus_serv'];
    $puest = $_POST['puesto'];
    $ads = $_POST['adscripcion'];

// Denise Cigarroa 21/08/2020
// Se cambiaron las variables de entrada
    if (empty($decl) AND empty($t_decl) AND empty($ejer) AND empty($est_decl)
      AND empty($serv) AND empty($est_serv) AND empty($puest) AND empty($ads))
  {
    echo "F";
  }
  else{
// Denise Cigarroa 21/08/2020
// Se cambiaron los campos de la consulta
$sqv="SELECT * FROM qsy_reporte_gral_vw
where (nombre_completo LIKE (UPPER('%".$serv."%')) or '".$serv."'='')
and (declaracion LIKE ('%".$decl."%') or '".$decl."'='')
and (tipo_decl LIKE ('%".$t_decl."%') or '".$t_decl."'='')
and (ejercicio LIKE ('%".$ejer."%') or '".$ejer."'='')
and (estatus_decl LIKE ('%".$est_decl."%') or '".$est_decl."'='')
and (estatus_empleado LIKE ('%".$est_serv."%') or '".$est_serv."'='')
and (puesto LIKE ('%".$puest."%') or '".$puest."'='')
and (adscripcion LIKE ('%".$ads."%') or '".$ads."'='')
order by rfc asc";
  $resulv=pg_query($conn,$sqv);
  $reporv=pg_fetch_all($resulv);

  if (pg_num_rows($resulv)>0){

  $z=$reporv[0]['adscripcion'];
  $p=$reporv[0]['puesto'];

  $z = $ads;
  $p = $puest;



$documento = new  \PhpOffice\PhpSpreadsheet\Spreadsheet();
$documento
    ->getProperties()
    ->setCreator("Aquí va el creador, como cadena")
    ->setLastModifiedBy('Parzibyte') // última vez modificado por
    ->setTitle('Mi primer documento creado con PhpSpreadSheet')
    ->setSubject('El asunto')
    ->setDescription('Este documento fue generado para parzibyte.me')
    ->setKeywords('etiquetas o palabras clave separadas por espacios')
    ->setCategory('La categoría');

#VARIABLES
function actual_date()  
{  
    $week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");  
    $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");  
    $year_now = date ("Y");  
    $month_now = date ("n");  
    $day_now = date ("j");  
    $week_day_now = date ("w");  
    $date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;   
    return $date;    
} 

  $tituloReporte = "REPORTE GENERAL";
  $fechaActual = "Ciudad de México, ".actual_date();
  $mensaje1 = "Filtro de Búsqueda";
  $encabezados = array('Consecutivo','Nombre Servidor','CURP', 'RFC','Edad','Sexo','Grado de Estudios', 'Estatus del Servidor', 'Puesto', 'Área Adscripción','Declaración','Tipo Declaración', 'Ejercicio Declaración','Fecha Declaración', 'Estatus Declaración');
// Denise Cigarroa 21/08/2020
// Se cambiaron los campos del encabezado de los filtros
  $subencabezado = array('Servidor Público','Declaración','Tipo de Declaración','Ejercicio','Estatus Declaración','Estatus Servidor','Puesto','Área de Adscripción');
#OBTENER GETACTIVESHEET
  $hoja = $documento->getActiveSheet();
#TITULO DE LA HOJA DE EXCEL
  $hoja->setTitle($tituloReporte);
# TITULAR DEL ARCHIVO
  #Combinar Celdas
    $hoja->mergeCells('H2:K3');
  #Llenar celda
    $hoja->setCellValue("H2", $tituloReporte);
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('H2')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $documento->getActiveSheet()->getStyle('H2')
      ->getFill()->getStartColor()->setARGB('D9D9D9');

 #Tipo de fuente Tamaño y negritas
    $documento->getActiveSheet()->getStyle('H2')
      ->getFont()
      ->setSize(20)
      ->setBold(true);
 #Borde
    $documento->getActiveSheet()->getStyle('H2:K3')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

#FECHA
  #Combinar celda
    $hoja->mergeCells('N2:P2');
    $hoja->mergeCells('B7:C7');
  #Lenar celda celda  
    $hoja->setCellValue("N2", $fechaActual);
    $hoja->setCellValue("B7", 'Filtros de Busqueda');
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('N2')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

    $documento->getActiveSheet()->getStyle('N2')
      ->getFill()->getStartColor()->setARGB('D9D9D9');
  #Tipo de fuente y Tamaño
    $documento->getActiveSheet()->getStyle('B7')
      ->getFont()
      ->setSize(11)
      ->setBold(true);
  #Ajustar Texto
    $documento->getActiveSheet()->getStyle('A1:Z9000')->getAlignment()->setWrapText(true);
  #Bordes
    $documento->getActiveSheet()->getStyle('N2:P2')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

$documento->getActiveSheet()->getStyle('A1:Z9000')
      ->getFont()
      ->setName('Source Sans Pro');
          
     #FILTROS
// Denise Cigarroa 21/08/2020
// Se quitaron algunos encabezados de los filtros
$hoja->setCellValue("B8", $subencabezado[0]);
$hoja->setCellValue("C8", $subencabezado[1]);
$hoja->setCellValue("D8", $subencabezado[2]);
$hoja->setCellValue("E8", $subencabezado[3]);
$hoja->setCellValue("F8", $subencabezado[4]);
$hoja->setCellValue("G8", $subencabezado[5]);
$hoja->setCellValue("H8", $subencabezado[6]);
$hoja->setCellValue("I8", $subencabezado[7]);

#Llenar celdas Dinamiocas
// Denise Cigarroa 21/08/2020
// Se quitaron algunos filtros y se actalizaron las variables de los mismos
$hoja->setCellValue("B9", $serv);
$hoja->setCellValue("C9", $decl);
$hoja->setCellValue("D9", $t_decl);
$hoja->setCellValue("E9", $ejer);
$hoja->setCellValue("F9", $est_decl);
$hoja->setCellValue("G9", $est_serv);
$hoja->setCellValue("H9", $puest);
$hoja->setCellValue("I9", $ads);

$documento->getActiveSheet()->getStyle('B8:I8')
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B8:I8')
    ->getFill()->getStartColor()->setARGB($color);

$documento->getActiveSheet()->getStyle('B8:I8')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

 #Tipo de Fuente, tamaño y negrita
      $documento->getActiveSheet()->getStyle('B8:I8')
        ->getFont()
        ->setBold(true);
        



#ECABEZADOS
$hoja->setCellValue("B11", $encabezados[0]);
$hoja->setCellValue("C11", $encabezados[1]);
$hoja->setCellValue("D11", $encabezados[2]);
$hoja->setCellValue("E11", $encabezados[3]);
$hoja->setCellValue("F11", $encabezados[4]);
$hoja->setCellValue("G11", $encabezados[5]);
$hoja->setCellValue("H11", $encabezados[6]);
$hoja->setCellValue("I11", $encabezados[7]);
$hoja->setCellValue("J11", $encabezados[8]);
$hoja->setCellValue("K11", $encabezados[9]);
$hoja->setCellValue("L11", $encabezados[10]);
$hoja->setCellValue("M11", $encabezados[11]);
$hoja->setCellValue("N11", $encabezados[12]);
$hoja->setCellValue("O11", $encabezados[13]);
$hoja->setCellValue("P11", $encabezados[14]);

$documento->getActiveSheet()->getStyle('B9:I9')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );



$i = 12;
$n = 1; //Numero de fila donde se va a comenzar a rellenar
 foreach ($reporv as $rep){

         $hoja->setCellValue('B'.$i, $n);
         $hoja->setCellValue('C'.$i, $rep['nombre_completo']);
         $hoja->setCellValue('D'.$i, $rep['curp']);
         $hoja->setCellValue('E'.$i, $rep['rfc']);
         $hoja->setCellValue('F'.$i, $rep['edad']);
         $hoja->setCellValue('G'.$i, $rep['sexo']);
         $hoja->setCellValue('H'.$i, $rep['nivel_escolar']);
         $hoja->setCellValue('I'.$i, $rep['estatus_empleado']);
         $hoja->setCellValue('J'.$i, $rep['puesto']);
         $hoja->setCellValue('K'.$i, $rep['adscripcion']);
         $hoja->setCellValue('L'.$i, $rep['declaracion']);
         $hoja->setCellValue('M'.$i, $rep['tipo_decl']);
         $hoja->setCellValue('N'.$i, $rep['ejercicio']);
         $hoja->setCellValue('O'.$i, $rep['fecha_presenta']);
         $hoja->setCellValue('P'.$i, $rep['estatus_decl']);



         $documento->getActiveSheet()->getStyle('C'.$i.':P'.$i)->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

         $documento->getActiveSheet()->getStyle('B'.$i)
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B'.$i)
    ->getFill()->getStartColor()->setARGB($color);

    $documento->getActiveSheet()->getStyle('B'.$i.':P'.$i)
      ->getFont()
      ->setSize(10);

      $documento->getActiveSheet()->getStyle('B'.$i)
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    
          $i++;
       $n++;
 }

$writer = new Xlsx($documento);

#ALINEACIÓN VERTICAL
    $documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);



#COLOR DE LETRA
$documento->getActiveSheet()->getStyle('B11:P11')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);



$documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


$documento->getActiveSheet()->getStyle('B11:P11')
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B11:P11')
    ->getFill()->getStartColor()->setARGB($color);

# DIMENCION DE columnas
$documento->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$documento->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$documento->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$documento->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$documento->getActiveSheet()->getColumnDimension('F')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('G')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('H')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('I')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('J')->setWidth(51);
$documento->getActiveSheet()->getColumnDimension('K')->setWidth(57);
$documento->getActiveSheet()->getColumnDimension('L')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('M')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('N')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('O')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('P')->setWidth(28);

#LOGOTIPO
// Denise Cigarroa 31/08/2020
// Se agregaron los logotipos en el reporte
$logo2 = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
$logo2->setName('Logo2');
$logo2->setDescription('Logo2');
$logo2->setPath('../../css/images/qsy_logo_depend.png');
$logo2->setHeight(100);
// $logo->setWidth(300);
$logo2->setCoordinates('D2');
$logo2->setWorksheet($documento->getActiveSheet());

$logo = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
$logo->setName('Logo');
$logo->setDescription('Logo');
$logo->setPath('../../css/images/qsy_logo_nivel.png');
$logo->setHeight(100);
// $logo->setWidth(400);
$logo->setCoordinates('B2');
$logo->setWorksheet($documento->getActiveSheet());



 
# Le pasamos la ruta de guardado
$writer->save('ReporteGeneral.xlsx');

header("Content-disposition: attachment; filename=ReporteGeneral.xlsx");
header("Content-type: MIME");
readfile("ReporteGeneral.xlsx");

return "Exito";
}
else{
  echo 'N';
}}}
?>