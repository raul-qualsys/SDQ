<?php
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

/* 09-09-2020 DMQ-Qualsys Se cambiaron los avisos de los reportes. */
ini_set('memory_limit', '1024M');
if (isset($_POST['enlace'])) {
//     //Recogemos las claves enviadas
    $decl = $_POST['decl'];
    $anio = $_POST['anio'];
    $area = $_POST['area'];
    $nivel = $_POST['nivel'];
    $ptos = $_POST['ptos'];


if (empty($anio) AND empty($area) AND empty($nivel) AND empty($ptos) AND empty($decl)){
    echo "F";
  }
/* Fin de actualización. */
  else{
$sqv="SELECT * FROM qsy_presentadas_vw
where (declaracion LIKE (UPPER('%".$decl."%')) or '".$decl."'='')
and (yearpres LIKE (UPPER('%".$anio."%')) or '".$anio."'='')
and (adscripcion LIKE (UPPER('%".$area."%')) or '".$area."' ='')
and (nivel LIKE (UPPER('%".$nivel."%')) or '".$nivel."' = '')
and (puesto LIKE (UPPER('%".$ptos."%')) or '".$ptos."' = '')
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')";
  $resulv=pg_query($conn,$sqv);
  $reporv=pg_fetch_all($resulv);
if (pg_num_rows($resulv)>0){

  $z=$reporv[0]['adscripcion'];
  $p=$reporv[0]['puesto'];

  $z = $area;
  $p = $ptos;
}
$sqlp="SELECT * FROM qsy_presentadas_vw
where (declaracion LIKE (UPPER('%".$decl."%')) or '".$decl."'='')
and (yearpres LIKE (UPPER('%".$anio."%')) or '".$anio."'='')
and (adscripcion = '".$area."' or '".$area."' ='')
and (nivel LIKE (UPPER('%".$nivel."%')) or '".$nivel."' = '')
and (puesto = '".$ptos."' or '".$ptos."' = '')
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')";
$result=pg_query($conn,$sqlp);
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

 #OBTENER GETACTIVESHEET
  $hoja =  $hoja = $documento->getActiveSheet();
  
  
  #ALINEACIÓN VERTICAL
$hoja->getStyle('A1:Z9000')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$hoja->getStyle('A1:Z9000')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

$hoja->getStyle('A1:Z9000')
      ->getFont()
      ->setName('Source Sans Pro');

$hoja->mergeCells('A2:C2');
$hoja->setCellValue("A2",'TITULO');
$hoja->mergeCells('D2:F2');
$hoja->setCellValue("D2",'NOMBRE CORTO');
$hoja->mergeCells('G2:L2');
$hoja->setCellValue("G2",'DESCRIPCIÓN');
#Color de Fondo
$hoja->getStyle('A2:L2')
  ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$hoja->getStyle('A2:L2')
  ->getFill()->getStartColor()->setARGB('2E2E2E');
#Tipo de fuente y Tamaño
$hoja->getStyle('A2:L2')
  ->getFont()
  ->setName('Arial')
  ->setSize(11)
  ->setBold(true);
$hoja->getStyle('A2:L2')
  ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

$hoja->mergeCells('A3:C3');
$hoja->setCellValue("A3",'Declaraciones de situación patrimonial y conflicto de intereses');
$hoja->mergeCells('D3:F3');
$hoja->setCellValue("D3",'A121Fr13_Declaraciones_de-situación-patrimonial-y-conflicto-de-intereses');
$hoja->mergeCells('G3:I3');
$hoja->setCellValue("G3",'Se publicará la versión pública de la declaración de situación patrimonial y conflicto de intereses de los(as) servidores(as) públicos(as), integrantes, miembros del sujeto obligado y/o toda persona que desempeñe un empleo, cargo o comisión y/o ejerza actos de autoridad, y que tiene la obligación de presentar declaración de situación patrimonial y conflicto de intereses en sus tres modalidades: inicio, modificación y de conclusión, de conformidad con la normatividad que resulte aplicable.');
#Color de Fondo
$hoja->getStyle('A3:I3')
  ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$hoja->getStyle('A3:I3')
  ->getFill()->getStartColor()->setARGB('D8D8D8');
#Tipo de fuente y Tamaño
$hoja->getStyle('A3:I3')
  ->getFont()
  ->setName('Arial')
  ->setSize(10);
  
$hoja->mergeCells('A6:R6');
$hoja->setCellValue("A6",'Tabla Campos');
#Color de Fondo
$hoja->getStyle('A6:R6')
  ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$hoja->getStyle('A6:R6')
  ->getFill()->getStartColor()->setARGB('2E2E2E');
#Tipo de fuente y Tamaño
$hoja->getStyle('A6:R6')
  ->getFont()
  ->setName('Arial')
  ->setSize(11)
  ->setBold(true);
$hoja->getStyle('A6:R6')
  ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
$hoja->setCellValue("A7",'Declaración');
$hoja->setCellValue("B7",'Ejercicio');
$hoja->setCellValue("C7",'Fecha de inicio del periodo que se informa');
$hoja->setCellValue("D7",'Fecha de término del periodo que se informa');
$hoja->setCellValue("E7",'Tipo de integrante del sujeto obligado (catálogo)');
$hoja->setCellValue("F7",'Clave o nivel del puesto');
$hoja->setCellValue("G7",'Denominación del puesto');
$hoja->setCellValue("H7",'Denominación del cargo');
$hoja->setCellValue("I7",'Área de adscripción');
$hoja->setCellValue("J7",'Nombre(s) del(la) servidor(a) público(a)');
$hoja->setCellValue("K7",'Primer apellido del(la) servidor(a) público(a)');
$hoja->setCellValue("L7",'Segundo apellido del(la) servidor(a) público(a)');
$hoja->setCellValue("M7",'Modalidad de la Declaración (catálogo)');
$hoja->setCellValue("N7",'Hipervínculo a la versión pública');
$hoja->setCellValue("O7",'Área(s) responsable(s) que genera(n), posee(n), publica(n) y actualizan la información');
$hoja->setCellValue("P7",'Fecha de validación');
$hoja->setCellValue("Q7",'Fecha de actualización');
$hoja->setCellValue("R7",'Nota');

#Color de Fondo
$hoja->getStyle('A7:R7')
  ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$hoja->getStyle('A7:R7')
  ->getFill()->getStartColor()->setARGB('D8D8D8');
#Tipo de fuente y Tamaño
$hoja->getStyle('A7:R7')
  ->getFont()
  ->setName('Arial')
  ->setSize(10);

#Bordes
    $documento->getActiveSheet()->getStyle('A7:R7')->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ]] );

# DIMENCION DE columnas
$hoja->getColumnDimension('A')->setWidth(20);
$hoja->getColumnDimension('B')->setWidth(15);
$hoja->getColumnDimension('C')->setWidth(38.30);
$hoja->getColumnDimension('D')->setWidth(38.15);
$hoja->getColumnDimension('E')->setWidth(41.15);
$hoja->getColumnDimension('F')->setWidth(21);
$hoja->getColumnDimension('G')->setWidth(43);
$hoja->getColumnDimension('H')->setWidth(55);
$hoja->getColumnDimension('I')->setWidth(55);
$hoja->getColumnDimension('J')->setWidth(35);
$hoja->getColumnDimension('K')->setWidth(40);
$hoja->getColumnDimension('L')->setWidth(40);
$hoja->getColumnDimension('M')->setWidth(36);
$hoja->getColumnDimension('N')->setWidth(100);
$hoja->getColumnDimension('O')->setWidth(73);
$hoja->getColumnDimension('P')->setWidth(23);
$hoja->getColumnDimension('Q')->setWidth(23);

$hoja->getRowDimension(1)->setVisible(false);
$hoja->getRowDimension(4)->setVisible(false);
$hoja->getRowDimension(5)->setVisible(false);

$i = 8;//Numero de fila donde se va a comenzar a rellenar
$n = 1; 
 
 foreach ($reporte as $rep){
/* 04-09-2020 DMQ-Qualsys Envío de variable de declaración completa en enlace de versión pública. */
      if($rep['tipo_decl']=="INICIAL"){
        $tipo_decl="I";
        $fecha_declaracion=$rep["fecha_contrata"];
      }
      else if($rep['tipo_decl']=="MODIFICACIÓN"){
        $tipo_decl="M";
        $fecha_declaracion=$rep['ejercicio']."-05-31";
      }
      else{
        $tipo_decl="C";
        $fecha_declaracion=$rep["fecha_baja"];
      }
      $sql="SELECT a.declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='".$rep['rfc']."' AND b.id_puesto=a.id AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '".$fecha_declaracion."' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='".$rep['rfc']."' limit 1))";
      $result=pg_query($sql);
      $val2=pg_fetch_assoc($result);
      if($val2)$completa=$val2["declaracion"];
      else $completa="N";
        
        if ($rep['declaracion'] == 'PATRIMONIAL')
        {
        $link=HTTP_PATH."/views/reportes/declaracion_patrimonial_vp.php?rfc=".$rep['rfc']."&ejercicio=".$rep['ejercicio']."&tipo_decl=".$tipo_decl."&declara_completo=".$completa;
        }
      elseif ($rep['declaracion'] == 'INTERESES') 
      {
        $link=HTTP_PATH."/views/reportes/declaracionvp_intereses.php?rfc=".$rep['rfc']."&ejercicio=".$rep['ejercicio']."&tipo_decl=".$tipo_decl."&declara_completo=".$completa;
      }
/* Fin de actualización */
      $fp = $rep['fecha_presenta'];
      $vfp=substr($fp, 3, 2);
      $vap=substr($rep['yearpres'],1,4);
      $pf = '';
      $ff = '';

      if ($vfp>=01 and $vfp<=03){
        $pf = '01/01/'.$vap;
        $ff = '31/03/'.$vap;
      }

      elseif ($vfp>03 and $vfp<=06){
        $pf = '01/04/'.$vap;
        $ff = '30/06/'.$vap;
      }

      elseif ($vfp>06 and $vfp<=10) {
        $pf = '01/07/'.$vap;
        $ff = '30/09/'.$vap;
      }

      elseif ($vfp>10 and $vfp<=12) {
        $pf = '01/10/'.$vap;
        $ff = '31/12/'.$vap;
      }
      

         $hoja->setCellValue('A'.$i, $rep['declaracion']);
         $hoja->setCellValue('B'.$i, $rep['ejercicio']);
         $hoja->setCellValue('C'.$i, $pf);
         $hoja->setCellValue('D'.$i, $ff);
         $hoja->setCellValue('E'.$i, 'Servidor(a) público(a)');
         $hoja->setCellValue('F'.$i, $rep['nivel']);
         $hoja->setCellValue('G'.$i, $rep['puesto']);
         $hoja->setCellValue('H'.$i, $rep['puesto']);
         $hoja->setCellValue('I'.$i, $rep['adscripcion']);
         $hoja->setCellValue('J'.$i, $rep['nombre']);
         $hoja->setCellValue('K'.$i, $rep['primer_ap']);
         $hoja->setCellValue('L'.$i, $rep['segundo_ap']);
         $hoja->setCellValue('M'.$i, $rep['tipo_decl']);
         $hoja->setCellValue('N'.$i, $link);
         $hoja->getCell('N'.$i)->getHyperlink()->setUrl($link);
         // $hoja->setCellValue('N'.$i, $link);
         $hoja->setCellValue('O'.$i, 'ÓRGANO INTERNO DE CONTROL');
         $hoja->setCellValue('P'.$i, $rep['fecha_presenta']);
         $hoja->setCellValue('Q'.$i, $rep['fecha_presenta']);

          $documento->getActiveSheet()->getStyle('A'.$i.':R'.$i)->getBorders()->applyFromArray( 
      ['left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ], 
      'bottom' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ], 
      'top' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ], 
      'right' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '2E2E2E' ] ]] );

          $documento->getActiveSheet()->getStyle('A'.$i.':R'.$i)
      ->getFont()
      ->setName('Arial')
      ->setSize(10);


$i++;         
}

$writer = new Xlsx($documento); 
# Le pasamos la ruta de guardado
$writer->save('ReporteVPublica.xlsx');

header("Content-disposition: attachment; filename=ReporteVPublica.xlsx");
header("Content-type: MIME");
readfile("ReporteVPublica.xlsx");

}
else{
/* 09-09-2020 DMQ-Qualsys Se cambiaron los avisos de los reportes. */
  echo "N";
/* Fin de actualización */
}}}
?>