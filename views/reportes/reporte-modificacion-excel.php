<?php
require_once('../../vendor/autoload.php');
include("../../include/conexion.php");
include("../../include/constantes.php");

// $sql = "SELECT * FROM view_reporte_gral";
// $result=pg_query($conn,$sql);
// $reporte=pg_fetch_all($result);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

$color = color;
ini_set('memory_limit', '1024M');

/* 09-09-2020 DMQ-Qualsys Se cambiaron los avisos de los reportes. */
if (isset($_POST['enlace'])) {
//     //Recogemos las claves enviadas
    $status_m = $_POST['stus_m'];
    $decla_cd = $_POST['dec'];
    $ejercicio_m = $_POST['ejerciciom'];
    $psto = $_POST['puesto'];
    $ads = $_POST['adscripcion'];
    
    
  if (empty($status_m) AND empty($decla_cd) AND empty($ejercicio_m) AND empty($psto)
      AND empty($ads))
  {
    echo "F";
  }
/* Fin de actualización. */

  else{

    // $esta=substr($estatuser_cd, 0,1);
    // $sx=substr($sexo_cd, 0,1);
    // $st=substr($estu_cd, 0,1);
    // $dla=substr($decla_cd, 0,1);
    // $tipdla=substr($t_decla_cd, 0,1);

    $sql = "SELECT * FROM qsy_reporte_mayo_vw
    where ('".$status_m."' is not null And estatus_decl LIKE (UPPER('%".$status_m."%')))
    and ('".$decla_cd."' is not null And declaracion LIKE (UPPER('%".$decla_cd."%')))
    and ('".$ejercicio_m."' is not null And ejercicio LIKE (UPPER('%".$ejercicio_m."%')))
    and ('".$psto."' is not null And puesto LIKE (UPPER('%".$psto."%')))
    and ('".$ads."' is not null And adscripcion LIKE (UPPER('%".$ads."%')))
    order by rfc asc";

$result=pg_query($conn,$sql);
$reporte=pg_fetch_all($result);



if (pg_num_rows($result)>0){

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

  $tituloReporte = "REPORTE MODIFICACIÓN";
  $fechaActual = "Ciudad de México, ".actual_date();
  $mensaje1 = "Filtro de Búsqueda";
  $encabezados = array('Consecutivo','Nombre Servidor','CURP', 'RFC','Edad','Sexo','Grado de Estudios','Puesto', 'Área Adscripción','Ejercicio Declaración','Declaración','Estatus Declaración','Fecha de presentación');

  $subencabezado = array('Ejercicio','Declaración','Estatus Declaración','Puesto','Área de Adscripción');
#OBTENER GETACTIVESHEET
  $hoja = $documento->getActiveSheet();
#TITULO DE LA HOJA DE EXCEL
  $hoja->setTitle($tituloReporte);
# TITULAR DEL ARCHIVO
  #Combinar Celdas
    $hoja->mergeCells('G2:J3');
  #Llenar celda
    $hoja->setCellValue("G2", $tituloReporte);
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('G2')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $documento->getActiveSheet()->getStyle('G2')
      ->getFill()->getStartColor()->setARGB('D9D9D9');

 #Tipo de fuente Tamaño y negritas
    $documento->getActiveSheet()->getStyle('G2')
      ->getFont()
      ->setSize(20)
      ->setBold(true);
 #Borde
    $documento->getActiveSheet()->getStyle('G2:J3')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

#FECHA
  #Combinar celda
    $hoja->mergeCells('L2:N2');
  #Lenar celda celda  
    $hoja->setCellValue("L2", $fechaActual);
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('L2')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

    $documento->getActiveSheet()->getStyle('L2')
      ->getFill()->getStartColor()->setARGB('D9D9D9');
  #Tipo de fuente y Tamaño
    $documento->getActiveSheet()->getStyle('L2')
      ->getFont()
      ->setSize(11)
      ->setBold(true);
  #Ajustar Texto
    $documento->getActiveSheet()->getStyle('A1:Z9000')->getAlignment()->setWrapText(true);
  #Bordes
    $documento->getActiveSheet()->getStyle('L2:N2')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

#FILTROS
  #ENCABEZADO DE FILTROS 
    #Combinar Celdas 
      $hoja->mergeCells('B7:C7');
    #Llenar Celda Encabezado Filtros
      $hoja->setCellValue("B7", $mensaje1);

      #Tipo de fuente y Tamaño
    $documento->getActiveSheet()->getStyle('B7')
      ->getFont()
      ->setSize(11)
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


$hoja->setCellValue("B8", $subencabezado[0]);
$hoja->setCellValue("C8", $subencabezado[1]);
$hoja->setCellValue("D8", $subencabezado[2]);
$hoja->setCellValue("E8", $subencabezado[3]);
$hoja->setCellValue("F8", $subencabezado[4]);

$hoja->setCellValue('B9', $ejercicio_m);
$hoja->setCellValue('C9', $decla_cd);
$hoja->setCellValue('D9', $status_m);
$hoja->setCellValue('E9', $psto);
$hoja->setCellValue('F9', $ads);

$documento->getActiveSheet()->getStyle('B8:F8')
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B8:F8')
    ->getFill()->getStartColor()->setARGB($color);

$documento->getActiveSheet()->getStyle('B8:F8')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);


$documento->getActiveSheet()->getStyle('B9:F9')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

$i = 12;
$n = 1; //Numero de fila donde se va a comenzar a rellenar
 foreach ($reporte as $rep){

         $hoja->setCellValue('B'.$i, $n);
         $hoja->setCellValue('C'.$i, $rep['nombre_completo']);
         $hoja->setCellValue('D'.$i, $rep['curp']);
         $hoja->setCellValue('E'.$i, $rep['rfc']);
         $hoja->setCellValue('F'.$i, $rep['edad']);
         $hoja->setCellValue('G'.$i, $rep['sexo']);
         $hoja->setCellValue('H'.$i, $rep['nivel_escolar']);
         $hoja->setCellValue('I'.$i, $rep['puesto']);
         $hoja->setCellValue('J'.$i, $rep['adscripcion']);
         $hoja->setCellValue('K'.$i, $rep['ejercicio']);
         $hoja->setCellValue('L'.$i, $rep['declaracion']);
         $hoja->setCellValue('M'.$i, $rep['estatus_decl']);
         $hoja->setCellValue('N'.$i, $rep['fecha_presenta']);        



         $documento->getActiveSheet()->getStyle('B'.$i.':N'.$i)->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

         $documento->getActiveSheet()->getStyle('B'.$i)
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B'.$i)
    ->getFill()->getStartColor()->setARGB($color);

    $documento->getActiveSheet()->getStyle('B'.$i.':N'.$i)
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

$documento->getActiveSheet()->getStyle('A1:Z9000')
      ->getFont()
      ->setName('Source Sans Pro');

#COLOR DE LETRA
$documento->getActiveSheet()->getStyle('B11:N11')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);



$documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


$documento->getActiveSheet()->getStyle('B11:N11')
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B11:N11')
    ->getFill()->getStartColor()->setARGB($color);

# DIMENCION DE columnas
$documento->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$documento->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$documento->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$documento->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$documento->getActiveSheet()->getColumnDimension('F')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('G')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('H')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('I')->setWidth(51);
$documento->getActiveSheet()->getColumnDimension('J')->setWidth(57);
$documento->getActiveSheet()->getColumnDimension('K')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('L')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('M')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('N')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('O')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('P')->setWidth(28);


#LOGOTIPO
$logo = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
$logo->setName('Logo');
$logo->setDescription('Logo');
$logo->setPath('../../css/images/qsy_logo_nivel.png');
$logo->setHeight(100);
// $logo->setWidth(400);
$logo->setCoordinates('B2');

$logo->setWorksheet($documento->getActiveSheet());

$logo2 = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
$logo2->setName('Logo');
$logo2->setDescription('Logo');
$logo2->setPath('../../css/images/qsy_logo_depend.png');
$logo2->setHeight(100);
// $logo->setWidth(400);
$logo2->setCoordinates('D2');

$logo2->setWorksheet($documento->getActiveSheet());

 
# Le pasamos la ruta de guardado
$writer->save('ReporteModificacion.xlsx');

header("Content-disposition: attachment; filename=ReporteModificacion.xlsx");
header("Content-type: MIME");
readfile("ReporteModificacion.xlsx");
}
else{
/* 09-09-2020 DMQ-Qualsys Se cambiaron los avisos de los reportes. */
  echo "N";
/* Fin de actualización. */
}}}
?>