<!--Denise Cigarroa Rodriguez 26/08/2020 
  Se cambio totalmente la interfaz del indicador -->
<?php
    include("../../../include/conexion.php");
    include("../../../include/header.php");?>
  <body>
  <?php include("../../../include/header-buttons.php");?>
  <div id="main-content">
    <?php
    include("../../../controllers/permiso-c.php"); 
    include("../../sidebar-contraloria.php"); 

   #Campos filtros
   $qlg1="SELECT distinct ejercicio 
   FROM qsy_declaraciones ORDER BY
   ejercicio ASC;";
   $resg1=pg_query($conn,$qlg1);
   $reg1=pg_fetch_all($resg1); 
   $ig1=0;

   $qlg3="SELECT DISTINCT descr
   FROM qsy_areas_adscripcion
   ORDER by descr ASC";
   $resg3=pg_query($conn,$qlg3);
   $reg3=pg_fetch_all($resg3); 
   $ig3=0;

   $qlg2="SELECT distinct descr FROM qsy_puestos 
       where descr <> ''
       ORDER BY descr  ASC;";
   $resg2=pg_query($conn,$qlg2);
   $reg2=pg_fetch_all($resg2); 
   $ig2=0;

$sql1 = "SELECT distinct adscripcion 
FROM qsy_indicadores_vw FETCH FIRST 3 ROWS ONLY";
$result1=pg_query($conn,$sql1);
$resultado1=pg_fetch_all($result1); 
$a='';
foreach ($resultado1 as $res1) {
 $b = ($res1['adscripcion']);
 $a.="'".$b."'".',';
}
$a = trim($a, ',');
$p='';
$o='';
$e='';
$x='';
foreach($resultado1 as $restp){

    $sqlp = "SELECT COUNT (*) as p
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'PRESENTADA' and adscripcion='".$restp['adscripcion']."'";
    $resultp=pg_query($conn,$sqlp);
    $resultadop=pg_fetch_all($resultp);
    $z = $resultadop[0]['p'];
    $p.=$z.',';

    $sqlo = "SELECT COUNT (*) as o
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'OMISA' and adscripcion='".$restp['adscripcion']."'";
    $resulto=pg_query($conn,$sqlo);
    $resultado=pg_fetch_all($resulto);
    $l = $resultado[0]['o'];
    $o.=$l.',';

    $sqle = "SELECT COUNT (*) as e
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'EN TIEMPO' and adscripcion='".$restp['adscripcion']."'";
    $resulte=pg_query($conn,$sqle);
    $resultadoe=pg_fetch_all($resulte);
    $h = $resultadoe[0]['e'];
    $e.=$h.',';

    $sqlx = "SELECT COUNT (*) as x
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'EXTEMPORANEA' and adscripcion='".$restp['adscripcion']."'";
    $resultx=pg_query($conn,$sqlx);
    $resultadox=pg_fetch_all($resultx);
    $k = $resultadox[0]['x'];
    $x.=$k.',';
  }
  $p = trim($p, ',');
  $o = trim($o, ',');
  $e = trim($e, ',');
  $x = trim($x, ',');

  //  Denise Cigarroa Rodriguez 26/08/2020 
  // se cambiaron los filtros para la generación del 
   // indicador y de los reportes del mismo

if (isset($_POST['filtrar'])) {
    //Recogemos las claves enviadas
    $ejercicio = $_POST['ejercicio'];
    $declaracion = $_POST['declaracion'];
    $tipodecl = $_POST['tipodecl'];
    $estatusdecl = $_POST['estatusdecl'];
    $area = $_POST['area'];
    $puesto = $_POST['puesto']; 


  $sqv="SELECT *
  FROM qsy_indicadores_vw 
  WHERE (ejercicio ='".$ejercicio."' or '".$ejercicio."' = '')
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion like '%".$area."%' or '".$area."'='')
  and (puesto like '%".$puesto."%' or '".$puesto."'='')";
  $resulv=pg_query($conn,$sqv);
  $reporv=pg_fetch_all($resulv); 

  if (pg_num_rows($resulv)>0){
  $z=$reporv[0]['adscripcion']; 
  $z = $area;

  $p =$reporv[0]['puesto']; 
  $p = $puesto;
}

  $sql1="SELECT *
  FROM qsy_indicadores_vw 
  WHERE (ejercicio ='".$ejercicio."' or '".$ejercicio."' = '')
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')";
  $result1=pg_query($conn,$sql1);
  $reporte1=pg_fetch_all($result1); 

if (pg_num_rows($result1)<=0)
 {
    echo'<script type="text/javascript">
        alert("No se encontraron resultados");
        window.location.href="declaracion_area.php";
        </script>';
   }
   else{

$sql1 = "SELECT distinct adscripcion 
FROM qsy_indicadores_vw
WHERE (ejercicio ='".$ejercicio."' or '".$ejercicio."' = '')
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')";
$result1=pg_query($conn,$sql1);
$resultado1=pg_fetch_all($result1); 
$a='';
foreach ($resultado1 as $res1) {
 $b = ($res1['adscripcion']);
 $a.="'".$b."'".',';
}
$a = trim($a, ',');
$p='';
$o='';
$e='';
$x='';
foreach($resultado1 as $restp){

    $sqlp = "SELECT COUNT (*) as p
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'PRESENTADA' and adscripcion='".$restp['adscripcion']."'
    and (ejercicio ='".$ejercicio."' or '".$ejercicio."' = '')
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')";
    $resultp=pg_query($conn,$sqlp);
    $resultadop=pg_fetch_all($resultp);
    $z = $resultadop[0]['p'];
    $p.=$z.',';

    $sqlo = "SELECT COUNT (*) as o
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'OMISA' and adscripcion='".$restp['adscripcion']."'
    and (ejercicio ='".$ejercicio."' or '".$ejercicio."' = '')
    and (declaracion ='".$declaracion."' or '".$declaracion."'='')
    and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
    and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
    and (adscripcion = '".$area."' or '".$area."'='')
    and (puesto = '".$puesto."' or '".$puesto."'='')";
    $resulto=pg_query($conn,$sqlo);
    $resultado=pg_fetch_all($resulto);
    $l = $resultado[0]['o'];
    $o.=$l.',';

    $sqle = "SELECT COUNT (*) as e
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'EN TIEMPO' and adscripcion='".$restp['adscripcion']."'
    and (ejercicio ='".$ejercicio."' or '".$ejercicio."' = '')
    and (declaracion ='".$declaracion."' or '".$declaracion."'='')
    and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
    and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
    and (adscripcion = '".$area."' or '".$area."'='')
    and (puesto = '".$puesto."' or '".$puesto."'='')";
    $resulte=pg_query($conn,$sqle);
    $resultadoe=pg_fetch_all($resulte);
    $h = $resultadoe[0]['e'];
    $e.=$h.',';

    $sqlx = "SELECT COUNT (*) as x
    FROM qsy_indicadores_vw
    WHERE estatus_decl = 'EXTEMPORANEA' and adscripcion='".$restp['adscripcion']."'
    and (ejercicio ='".$ejercicio."' or '".$ejercicio."' = '')
    and (declaracion ='".$declaracion."' or '".$declaracion."'='')
    and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
    and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
    and (adscripcion = '".$area."' or '".$area."'='')
    and (puesto = '".$puesto."' or '".$puesto."'='')";
    $resultx=pg_query($conn,$sqlx);
    $resultadox=pg_fetch_all($resultx);
    $k = $resultadox[0]['x'];
    $x.=$k.',';
  }
  $p = trim($p, ',');
  $o = trim($o, ',');
  $e = trim($e, ',');
  $x = trim($x, ',');

   }
$opcionejercicio = $ejercicio;
$opciondecl = $declaracion;
$opciontdecl = $tipodecl;
$opcionesdecl = $estatusdecl;
$opcionarea = $area;
$opcionpuesto = $puesto;
}

?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Declaraciones por area</title>
        <link rel="stylesheet" type="text/css" href="../../../css/main.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <style type="text/css">
          .principal
          {
            display: flex;
            align-items: center;
            justify-content:space-between;
            z-index: -1;
          }
           .mystyle
            {
               width:50%;
               height: 75%;
               margin-top: 4%;
               margin-left: 49%;
               position:absolute;
               float: right;
               z-index: 1;
            }
            .myform
            {
               width:80%;
               height: 80%;
               margin-top: 35%;
               margin-left: 15%;
               position:absolute;
               z-index: 1;
            }

            .myformex
            {

               margin-top: 5%;
               margin-left: 2.5%;
               position:absolute;
               z-index: 1;
            }

            .myformpd
            {
               margin-top: 5%;
               margin-left: 8%;
               position:absolute;
               z-index: 1;
            }
          .btnexc{
              width:100%;
            }
            .btnpdf{
              width:100%;
            }
        </style>
        <script src="imagenseccion/librerias/htmlToCanvas.js"></script>
        <script src="imagenseccion/js/funciones.js"></script>
        <script type="text/javascript">
           setTimeout(function hola(){
            <?php  $rfc = $_GET['rfc'];?>
            var a= "area<?php echo $rfc;?>";
        tomarImagenPorSeccion('container',a);

    },1500)
        </script>
        <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [<?php echo $a?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        series: [{
            name: 'Presentadas',
            data: [<?php echo $p?>]
        },{
            name: 'Omisas',
            data: [<?php echo $o?>]
        },{
            name: 'En Tiempo',
            data: [<?php echo $e?>]
        },{
            name: 'Extemporáneas',
            data: [<?php echo $x?>]
        }]
    });
}); 
    </script>
  </head>
  <body onunload="hola()">
        
        <script src="../../../highcharts-4.1.5/js/highcharts.js"></script>
       <div class="header">
      <h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
    </div>
        <div class="forms-container principal">
          <div class="myform">
            <div class="forms-container">
                <div class="form-div-1">
                  <h2>Declaraciones por Área</h2>
                </div></div>
  <!--Denise Cigarroa Rodriguez 26/08/2020 
  se cambiaron los campos a filtrar para la generación del inicador y de los reportes del mismo -->
            <form action="" method="POST">
            <div class="forms-container">
                <div class="form-div-5">
                    <label>Ejercicio Fiscal:</label>
                    <select name="ejercicio" id="ejercicio">
                        <option></option>
                        <?php 
                        $ac= intval(date("Y"));
                        $i = 2010;
                         while ($i<=$ac) {?>
                          <option><?php echo $i;?></option>
                           <?php
                              $i++;}
                          ?>
                </select>
                </div>
              <div class="form-div-5">
                    <label>Declaración:</label>
                    <select name="declaracion" id="declaracion">
                       <option></option>
                        <option>INTERESES</option>
                        <option>PATRIMONIAL</option>
                    </select>
                </div>
            </div>
            <div class="forms-container">
                <div class="form-div-5">
                    <label>Tipo de Declaración:</label>
                    <select name="tipodecl" id="tipodecl">
                       <option></option>
                        <option>INICIAL</option>
                        <option>MODIFICACIÓN</option>
                        <option>CONCLUSIÓN</option>
                    </select>
                </div>
              <div class="form-div-5">
                    <label>Estatus Declaración:</label>
                    <select name="estatusdecl" id="estatusdecl">
                       <option></option>
                        <option>PRESENTADA</option>
                        <option>OMISA</option>
                        <option>EXTEMPORANEA</option>
                        <option>EN TIEMPO</option>
                    </select>
                </div>
            </div>
            <div class="forms-container">
                <div class="form-div-5">
                    <label>Area de Adscripción:</label>
                    <select name="area" id="area">
                        <option></option>
                        <?php 
                          foreach($reg3 as $rep3)
                          {
                           $a3 = implode($reg3[$ig3]);?>
                           <option><?php echo $a3;?></option>
                          <?php 
                          $ig3++;}
                          ?>
                    </select>
                </div>
                <div class="form-div-5">
            <label>Puesto:</label>
                    <select name="puesto" id="puesto">
                        <option></option>
                         <?php 
                          foreach($reg2 as $rep2)
                          {
                           $a2 = implode($reg2
                            [$ig2]);?>
                           <option><?php echo $a2;?></option>
                          <?php 
                          $ig2++;}
                          ?>
                    </select>
          </div>
          
             
              </div>
              <div class="forms-container">
                <div class="form-div-5">
            <input type="submit" name="filtrar" value="Generar" id="filtrar"> 
          </div>
              </div> 
            </form>
            <div class="principal">
            <form class="myformex" action="reporte-area-excel.php?rfc=<?php echo $rfc;?>" method="POST" id="formexc" target="_blank">
              <div class="forms-container">
                <div class="form-div-5">
              <input type="hidden" name="ejercicio_ex" id="ejercicio_ex">
              <input type="hidden" name="declaracion_ex" id="declaracion_ex">
              <input type="hidden" name="tipodecl_ex" id="tipodecl_ex">
              <input type="hidden" name="estatusdecl_ex" id="estatusdecl_ex">
              <input type="hidden" name="area_ex" id="area_ex">
              <input type="hidden" name="puesto_ex" id="puesto_ex">
                    
                      <button type="submit" name="excel" id="excel"><img class="btnexc" src="../../../images/excel.png" alt="Excel"></button>
                     </div></div>
          </form>
          <form class="myformpd" action="reporte-area-pdf.php?rfc=<?php echo $rfc;?>" method="POST" id="formpdf" target="_blank">

            <div class="forms-container">
                <div class="form-div-5">
            <input type="hidden" name="ejercicio_pd" id="ejercicio_pd">
            <input type="hidden" name="declaracion_pd" id="declaracion_pd">
            <input type="hidden" name="tipodecl_pd" id="tipodecl_pd">
            <input type="hidden" name="estatusdecl_pd" id="estatusdecl_pd">
            <input type="hidden" name="area_pd" id="area_pd">
            <input type="hidden" name="puesto_pd" id="puesto_pd">
            <button type="submit" name="pdf" id="pdf"><img class="btnpdf" src="../../../images/pdf.png" alt="Pdf"></button>
          </div></div>
          </form></div>
          </div></div>
          <div id="container" class="mystyle"></div>
        </div>
        <?php
    include("../../../include/footer.php"); 
  ?>
        <SCRIPT>
         // seleccionar opción predeterminada
         document.getElementById('ejercicio').value = "<?php echo $opcionejercicio; ?>";
         document.getElementById('declaracion').value = "<?php echo $opciondecl; ?>";
         document.getElementById('tipodecl').value = "<?php echo $opciontdecl; ?>"; 
         document.getElementById('estatusdecl').value = "<?php echo $opcionesdecl; ?>";
         document.getElementById('area').value = "<?php echo $opcionarea; ?>";
         document.getElementById('puesto').value = "<?php echo $opcionpuesto; ?>";
          // EXCEL
         document.getElementById('ejercicio_ex').value = "<?php echo $opcionejercicio; ?>";
         document.getElementById('declaracion_ex').value = "<?php echo $opciondecl;?>";
         document.getElementById('tipodecl_ex').value = "<?php echo $opciontdecl; ?>";
         document.getElementById('estatusdecl_ex').value = "<?php echo $opcionesdecl; ?>";
         document.getElementById('area_ex').value = "<?php echo $opcionarea; ?>";
         document.getElementById('puesto_ex').value = "<?php echo $opcionpuesto; ?>";
         // PDF
         document.getElementById('ejercicio_pd').value = "<?php echo $opcionejercicio; ?>";
         document.getElementById('declaracion_pd').value = "<?php echo $opciondecl;?>";
         document.getElementById('tipodecl_pd').value = "<?php echo $opciontdecl; ?>";
         document.getElementById('estatusdecl_pd').value = "<?php echo $opcionesdecl; ?>";
         document.getElementById('area_pd').value = "<?php echo $opcionarea; ?>";
         document.getElementById('puesto_pd').value = "<?php echo $opcionpuesto; ?>";
      </SCRIPT>
    </body>
</html>

