<?php
require_once('../../../vendor/autoload.php');
include("../../../include/conexion.php");
include("../../../include/constantes.php");

$rfc = $_GET['rfc'];
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

ini_set('memory_limit', '1024M');
if (isset($_POST['excel'])) {
    //Recogemos las claves enviadas
    $ej = $_POST['ejercicio_ex'];
    $decl = $_POST['declaracion_ex'];
    $tip = $_POST['tipodecl_ex'];
    $est = $_POST['estatusdecl_ex'];
    $ads = $_POST['area_ex'];
    $pto = $_POST['puesto_ex'];

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

$color = color;

    $sql="SELECT distinct tipo_decl
FROM qsy_indicadores_vw where tipo_decl <> ''
  and (ejercicio ='".$ej."' or '".$ej."' = '')
  and (declaracion ='".$decl."' or '".$decl."'='')
  and (tipo_decl ='".$tip."' or '".$tip."'='')
  and (estatus_decl ='".$est."' or '".$est."'='')
  and (adscripcion = '".$ads."' or '".$ads."'='')
  and (puesto = '".$pto."' or '".$pto."'='')
  order by tipo_decl asc";
$sqltd=pg_query($conn,$sql);
$tdel=pg_fetch_all($sqltd);

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


  $tituloReporte = "TIPO DE DECLARACIONES";
  $fechaActual = "Ciudad de México, ".actual_date();
  $mensaje1 = "Filtro de Búsqueda";
  $encabezados = array('Consecutivo','Nombre Servidor','RFC', 'CURP','Sexo','Estado Civil','Nacionalidad', 'Puesto','Nivel','Área Adscripción','Declaración', 'Tipo Declaración','Estatus Declaración','Fecha Declaración', 'Ejercicio');

  $subencabezado = array('Ejercicio Fiscal','Declaración','Tipo Declaración','Estatus Declaración','Área Adscripción','Puesto');

  
#OBTENER GETACTIVESHEET
  $hoja = $documento->getActiveSheet();
#TITULO DE LA HOJA DE EXCEL
  $hoja->setTitle($tituloReporte);
# TITULAR DEL ARCHIVO
  #Combinar Celdas
    $hoja->mergeCells('B2:F3');
  #Llenar celda
    $hoja->setCellValue("B2", $tituloReporte);
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('B2')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $documento->getActiveSheet()->getStyle('B2')
      ->getFill()->getStartColor()->setARGB('D9D9D9');

 #Tipo de fuente Tamaño y negritas
    $documento->getActiveSheet()->getStyle('B2')
      ->getFont()
      ->setSize(20)
      ->setBold(true);
 #Borde
    $documento->getActiveSheet()->getStyle('B2:F3')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

#FECHA
  #Combinar celda
    $hoja->mergeCells('B6:D6');
    $hoja->mergeCells('B8:C8');
  #Lenar celda celda  
    $hoja->setCellValue("B6", $fechaActual);
    $hoja->setCellValue("B8", 'Filtros de Busqueda');
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('B6')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

    $documento->getActiveSheet()->getStyle('B6')
      ->getFill()->getStartColor()->setARGB('D9D9D9');
  #Tipo de fuente y Tamaño
    $documento->getActiveSheet()->getStyle('B8')
      ->getFont()
      ->setSize(11)
      ->setBold(true);
 
  #Bordes
    $documento->getActiveSheet()->getStyle('B6:D6')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

          
     #FILTROS
$hoja->setCellValue("B9", $subencabezado[0]);
$hoja->setCellValue("C9", $subencabezado[1]);
$hoja->setCellValue("D9", $subencabezado[2]);
$hoja->setCellValue("E9", $subencabezado[3]);
$hoja->setCellValue("F9", $subencabezado[4]);
$hoja->setCellValue("G9", $subencabezado[5]);

#Llenar celdas Dinamiocas
$hoja->setCellValue("B10", $ej);
$hoja->setCellValue("C10", $decl);
$hoja->setCellValue("D10", $tip);
$hoja->setCellValue("E10", $est);    
$hoja->setCellValue("F10", $ads);
$hoja->setCellValue("G10", $pto); 

   
$documento->getActiveSheet()->getStyle('B9:G9')
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B9:G9')
    ->getFill()->getStartColor()->setARGB($color);

$documento->getActiveSheet()->getStyle('B9:G9')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

 #Tipo de Fuente, tamaño y negrita
      $documento->getActiveSheet()->getStyle('B9:G9')
        ->getFont()
        ->setBold(true);
        
$documento->getActiveSheet()->getStyle('B10:G10')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

$hoja->setCellValue("B12", "TIPO DE DECLARACION");
$hoja->setCellValue("C12", "PRESENTADA");
$hoja->setCellValue("D12", "OMISA");
$hoja->setCellValue("E12", "EN TIEMPO");
$hoja->setCellValue("F12", "EXTEMPORANEA");

$documento->getActiveSheet()->getStyle('B12:F12')
      ->getFont()
      ->setSize(11)
      ->setBold(true);



# DIMENCION DE columnas
$documento->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$documento->getActiveSheet()->getColumnDimension('C')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('D')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('E')->setWidth(28);
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

#ALINEACIÓN VERTICAL
    $documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

#Ajustar Texto
$documento->getActiveSheet()->getStyle('A1:Z9000')->getAlignment()->setWrapText(true);

 $documento->getActiveSheet()->getStyle('A1:Z9000')
      ->getFont()
      ->setName('Source Sans Pro');


$d=13;      
foreach ($tdel as $td) {
 $hoja->setCellValue('B'.$d, $td['tipo_decl']);
 $d++; 
}
$hoja->setCellValue("B".$d,'Total');
$documento->getActiveSheet()->getStyle("B".$d)
      ->getFont()
      ->setSize(11)
      ->setBold(true);

$s = 10;
$y = 10;
 //Numero de fila donde se va a comenzar a rellenar
 foreach ($reporv as $rep){$s++;}

$o=13; 
 $l=13;     
foreach ($tdel as $td) {
$hoja->setCellValue('C'.$o,"=COUNTIFS('DETALLE REPORTE'!M".$y.":M".$s.",B".$o.",'DETALLE REPORTE'!N".$y.":N".$s.",C12)");
$hoja->setCellValue('D'.$o,"=COUNTIFS('DETALLE REPORTE'!M".$y.":M".$s.",B".$o.",'DETALLE REPORTE'!N".$y.":N".$s.",D12)");
$hoja->setCellValue('E'.$o,"=COUNTIFS('DETALLE REPORTE'!M".$y.":M".$s.",B".$o.",'DETALLE REPORTE'!N".$y.":N".$s.",E12)");
$hoja->setCellValue('F'.$o,"=COUNTIFS('DETALLE REPORTE'!M".$y.":M".$s.",B".$o.",'DETALLE REPORTE'!N".$y.":N".$s.",F12)");
$o++;
}

$j= $o-1;
$hoja->setCellValue('C'.$d,"=SUM(C".$l.":C".$j.")");
$hoja->setCellValue('D'.$d,"=SUM(D".$l.":D".$j.")");
$hoja->setCellValue('E'.$d,"=SUM(E".$l.":E".$j.")");
$hoja->setCellValue('F'.$d,"=SUM(F".$l.":F".$j.")");

$documento->getActiveSheet()->getStyle('C'.$d.':F'.$d)
      ->getFont()
      ->setSize(11)
      ->setBold(true);

#LOGOTIPO
$logo = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
$logo->setName('Logo');
$logo->setDescription('Logo');
$logo->setPath('imagenseccion/archivos/tipo'.$rfc.'.png');
$logo->setHeight(350);
// $logo->setWidth(400);
$logo->setCoordinates('H6');

$logo->setWorksheet($documento->getActiveSheet());

######################## HOJA 2 ##########################

$documento->createSheet();
$documento->setActiveSheetIndex(1); 
$hoja = $documento->getActiveSheet();
$hoja->setTitle("DETALLE REPORTE"); 

#ENCABEZADOS
$hoja->setCellValue("B9", $encabezados[0]);
$hoja->setCellValue("C9", $encabezados[1]);
$hoja->setCellValue("D9", $encabezados[2]);
$hoja->setCellValue("E9", $encabezados[3]);
$hoja->setCellValue("F9", $encabezados[4]);
$hoja->setCellValue("G9", $encabezados[5]);
$hoja->setCellValue("H9", $encabezados[6]);
$hoja->setCellValue("I9", $encabezados[7]);
$hoja->setCellValue("J9", $encabezados[8]);
$hoja->setCellValue("K9", $encabezados[9]);
$hoja->setCellValue("L9", $encabezados[10]);
$hoja->setCellValue("M9", $encabezados[11]);
$hoja->setCellValue("N9", $encabezados[12]);
$hoja->setCellValue("O9", $encabezados[13]);
$hoja->setCellValue("P9", $encabezados[14]);     




$i = 10;
$x = 10;
$n = 1; //Numero de fila donde se va a comenzar a rellenar
 foreach ($reporv as $rep){

         $hoja->setCellValue('B'.$i, $n);
         $hoja->setCellValue('C'.$i, $rep['nombre'].' '.$rep['primer_ap'].' '.$rep['segundo_ap']);
         $hoja->setCellValue('D'.$i, $rep['rfc']);
         $hoja->setCellValue('E'.$i, $rep['curp']);
         $hoja->setCellValue('F'.$i, $rep['sexo']);
         $hoja->setCellValue('G'.$i, $rep['estado_civil']);
         $hoja->setCellValue('H'.$i, $rep['nacionalidad']);
         $hoja->setCellValue('I'.$i, $rep['puesto']);
         $hoja->setCellValue('J'.$i, $rep['nivel']);
         $hoja->setCellValue('K'.$i, $rep['adscripcion']);
         $hoja->setCellValue('L'.$i, $rep['declaracion']);
         $hoja->setCellValue('M'.$i, $rep['tipo_decl']);
         $hoja->setCellValue('N'.$i, $rep['estatus_decl']);
         $hoja->setCellValue('O'.$i, $rep['fecha_presenta']);
         $hoja->setCellValue('P'.$i, $rep['ejercicio']);

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

 #Combinar Celdas
    $hoja->mergeCells('B2:F3');
  #Llenar celda
    $hoja->setCellValue("B2", $tituloReporte);
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('B2')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $documento->getActiveSheet()->getStyle('B2')
      ->getFill()->getStartColor()->setARGB('D9D9D9');

 #Tipo de fuente Tamaño y negritas
    $documento->getActiveSheet()->getStyle('B2')
      ->getFont()
      ->setSize(20)
      ->setBold(true);
 #Borde
    $documento->getActiveSheet()->getStyle('B2:F3')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => $color ] ]] );

#FECHA
  #Combinar celda
    $hoja->mergeCells('B6:D6');
    $hoja->mergeCells('B8:C8');
  #Lenar celda celda  
    $hoja->setCellValue("B6", $fechaActual);
    $hoja->setCellValue("B8", 'Detalle Reporte');
  #Color de Fondo
    $documento->getActiveSheet()->getStyle('B6')
      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

    $documento->getActiveSheet()->getStyle('B6')
      ->getFill()->getStartColor()->setARGB('D9D9D9');
  #Tipo de fuente y Tamaño
    $documento->getActiveSheet()->getStyle('B8')
      ->getFont()
      ->setSize(11)
      ->setBold(true);

 

$writer = new Xlsx($documento);




#COLOR DE LETRA
$documento->getActiveSheet()->getStyle('B9:P9')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);






$documento->getActiveSheet()->getStyle('B9:P9')
->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);

$documento->getActiveSheet()->getStyle('B9:P9')
    ->getFill()->getStartColor()->setARGB($color);

    #ALINEACIÓN VERTICAL
    $documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$documento->getActiveSheet()->getStyle('A1:Z9000')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

#Ajustar Texto
$documento->getActiveSheet()->getStyle('A1:Z9000')->getAlignment()->setWrapText(true);

 $documento->getActiveSheet()->getStyle('A1:Z9000')
      ->getFont()
      ->setName('Source Sans Pro');

# DIMENCION DE columnas
$documento->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$documento->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$documento->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$documento->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$documento->getActiveSheet()->getColumnDimension('F')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('G')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('H')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('I')->setWidth(57);
$documento->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$documento->getActiveSheet()->getColumnDimension('K')->setWidth(57);
$documento->getActiveSheet()->getColumnDimension('L')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('M')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('N')->setWidth(22);
$documento->getActiveSheet()->getColumnDimension('O')->setWidth(28);
$documento->getActiveSheet()->getColumnDimension('P')->setWidth(20);
 
# Le pasamos la ruta de guardado
$writer->save('ReporteTipoDecla.xlsx');

header("Content-disposition: attachment; filename=ReporteTipoDecla.xlsx");
header("Content-type: MIME");
readfile("ReporteTipoDecla.xlsx");
}}}
?>