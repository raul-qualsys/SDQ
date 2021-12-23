<!--Denise Cigarroa Rodriguez 27/08/2020 
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

#######################################################################

$sql="SELECT distinct ejercicio
FROM qsy_indicadores_vw where ejercicio <> ''
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
order by ejercicio asc FETCH FIRST 3 ROWS ONLY";
$sqle=pg_query($conn,$sql);
$ejer=pg_fetch_all($sqle);  
$e='';
$m='';
$i='';
$c='';
foreach ($ejer as $ej) {
  $s=$ej['ejercicio'];
  $e .= $s.",";

$sqlm="SELECT count(*) as m FROM qsy_indicadores_vw
WHERE tipo_decl = 'MODIFICACIÓN' 
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
and ejercicio = '".$ej['ejercicio']."'";
$sqlm=pg_query($conn,$sqlm);
$mod=pg_fetch_all($sqlm); 
$n=($mod[0]['m']);
$m .= $n.",";

$sqli="SELECT count(*) as i FROM qsy_indicadores_vw
WHERE tipo_decl = 'INICIAL' 
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
and ejercicio = '".$ej['ejercicio']."'";
$sqli=pg_query($conn,$sqli);
$ini=pg_fetch_all($sqli); 
$y=($ini[0]['i']);
$i .= $y.","; 

$sqlc="SELECT count(*) as c FROM qsy_indicadores_vw
WHERE tipo_decl = 'CONCLUSIÓN' 
and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
and ejercicio = '".$ej['ejercicio']."'";
$sqlc=pg_query($conn,$sqlc);
$conc=pg_fetch_all($sqlc); 
$t=($conc[0]['c']);
$c .= $t.",";

}
$e=trim($e, ',');
$m=trim($m, ',');
$i=trim($i, ',');
$c=trim($c, ',');

//  Denise Cigarroa Rodriguez 27/08/2020 
  // se cambiaron los filtros para la generación del 
   // indicador y de los reportes del mismo
if (isset($_POST['filtrar'])) {
    //Recogemos las claves enviadas
    $ejercicio = $_POST['ejercicio'];
    /*Denise Cigarroa Rodriguez 9/09/2020 
    se agrego nuevo dato de entrada para el comparativo de 
    ejercicios fiscales*/
      if(!empty($_POST['check_list'])){
// Bucle para almacenar y mostrar los valores de la casilla de verificación comprobación individual.
        $jr = '';
        foreach($_POST['check_list'] as $selected){
        $jr.= "'".$selected."',";
        }
        }
        $jr=trim($jr, ',');
##############################################################################
    $declaracion = $_POST['declaracion'];
    $tipodecl = $_POST['tipodecl'];
    $estatusdecl = $_POST['estatusdecl'];
    $area = $_POST['area'];
    $puesto = $_POST['puesto'];

    if (empty($ejercicio) AND empty($declaracion) AND empty($tipodecl)
      AND empty($estatusdecl) AND empty($area) AND empty($puesto))
  {
    echo'<script type="text/javascript">
        alert("DEBE AGREGAR AL MENOS UN CRITERIO");
        window.location.href="#";
        </script>';
  }
  else{

    $sqv="SELECT *
  FROM qsy_indicadores_vw 
  WHERE (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
  and (ejercicio in (".$jr."))
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion like '%".$area."%' or '".$area."'='')
  and (puesto like '%".$puesto."%' or '".$puesto."'='')
  order by ejercicio asc";
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
  WHERE (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
  and (ejercicio in (".$jr."))
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')
  order by ejercicio asc";
  $result1=pg_query($conn,$sql1);
  $reporte1=pg_fetch_all($result1); 
   
if (pg_num_rows($result1)<=0)
 {
    echo'<script type="text/javascript">
        alert("No se encontraron resultados");
        window.location.href="comparativo_ejercicios.php";
        </script>';
   }
   else{

$sql="SELECT distinct ejercicio 
FROM qsy_indicadores_vw 
where (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA') 
  and (ejercicio in (".$jr."))
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')
  order by ejercicio asc";
$sqle=pg_query($conn,$sql);
$ejer=pg_fetch_all($sqle);  

$e='';
$m='';
$i='';
$c='';

foreach ($ejer as $ej) {
  $s=$ej['ejercicio'];
  $e .= $s.",";

$sqlm="SELECT count(*) as m FROM qsy_indicadores_vw
WHERE tipo_decl = 'MODIFICACIÓN' 
  and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
  and ejercicio = '".$ej['ejercicio']."'
  and (ejercicio in (".$jr."))
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')";
$sqlm=pg_query($conn,$sqlm);
$mod=pg_fetch_all($sqlm); 
$n=($mod[0]['m']);
$m .= $n.",";

$sqli="SELECT count(*) as i FROM qsy_indicadores_vw
WHERE tipo_decl = 'INICIAL' 
  and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA')
  and ejercicio = '".$ej['ejercicio']."'
  and (ejercicio in (".$jr."))
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')";
$sqli=pg_query($conn,$sqli);
$ini=pg_fetch_all($sqli); 
$y=($ini[0]['i']);
$i .= $y.",";

$sqlc="SELECT count(*) as c FROM qsy_indicadores_vw
WHERE tipo_decl = 'CONCLUSIÓN' 
  and (estatus_decl = 'PRESENTADA' or estatus_decl = 'EXTEMPORANEA') 
  and ejercicio = '".$ej['ejercicio']."'
  and (ejercicio in (".$jr."))
  and (declaracion ='".$declaracion."' or '".$declaracion."'='')
  and (tipo_decl ='".$tipodecl."' or '".$tipodecl."'='')
  and (estatus_decl ='".$estatusdecl."' or '".$estatusdecl."'='')
  and (adscripcion = '".$area."' or '".$area."'='')
  and (puesto = '".$puesto."' or '".$puesto."'='')";
$sqlc=pg_query($conn,$sqlc);
$conc=pg_fetch_all($sqlc); 
$t=($conc[0]['c']);
$c .= $t.",";
}
$e=trim($e, ',');
$m=trim($m, ',');
$i=trim($i, ',');
$c=trim($c, ',');
}
/*Denise Cigarroa Rodriguez 9/09/2020 
se agrego variable para la generación de reportes*/
$opcionejercicio = $ejercicio;
$opcion_pd = $jr;
$opciondecl = $declaracion;
$opciontdecl = $tipodecl;
$opcionesdecl = $estatusdecl;
$opcionarea = $area;
$opcionpuesto = $puesto;
   }}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Comparativo Ejercicios</title>
        <link rel="stylesheet" type="text/css" href="../../../css/main.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <style type="text/css">
/*Denise Cigarroa Rodriguez 9/09/2020 
  se modificaron los estilos para la nueva interfaz del indicador*/
      #l1, #l2, #l3, #l4
      {color: #000;}

        .selectBox { 
            position: relative; 
        } 
  
        .selectBox select { 
            font-weight: bold; 
        } 
  
        .overSelect { 
            position: absolute; 
            left: 0; 
            right: 0; 
            top: 0; 
            bottom: 0; 
        } 
  
        #checkBoxes { 
            display: none; 
            width: 80%;
            background-color: #BCC2C1; 
        } 
  
        #checkBoxes label { 
            display: block; 
        } 
  
        #checkBoxes label:hover { 
            background-color: #4F615E; 
        }

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
            var a= "ejercicios<?php echo $rfc;?>";
        tomarImagenPorSeccion('container',a);

    },1500)
        </script>
    </head>
    <body onunload="hola()">
       <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [<?php echo $e;?>],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total de declaraciones'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',

            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Inicial',
            data: [<?php echo $i;?>]

        }, {
            name: 'Modificación',
            data: [<?php echo $m;?>]

        }, {
            name: 'Conclusión',
            data: [<?php echo $c;?>]

        }]
    });
});
    </script> 
        <script src="../../../highcharts-4.1.5/js/highcharts.js"></script>
        <!-- Denise Cigarroa Rodriguez 9/09/2020 
  se agrego script para el funcionamiento de select con checkbox -->
        <script type="text/javascript">
          var show = true; 
  
        function showCheckboxes() { 
            var checkboxes =  
                document.getElementById("checkBoxes"); 
  
            if (show) { 
                checkboxes.style.display = "block"; 
                show = false; 
            } else { 
                checkboxes.style.display = "none"; 
                show = true; 
            } 
        }

        $(function(){
    $(document).on('change','#ejercicio',function(){ //detectamos el evento change
      var value = $(this).val();//sacamos el valor del select
      $('#0').val(value);
      $('#1').val(value - 1);
      $('#2').val(value - 2);
      $('#3').val(value - 3);
      $('#4').val(value - 4);


      $('#l1').text(value - 1);
      $('#l2').text(value - 2);
      $('#l3').text(value - 3);
      $('#l4').text(value - 4);//le agregamos el valor al input (notese que el input debe tener un ID para que le caiga el valor)
    });
  });
        </script>
        <div class="header">
      <h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
    </div>
        <div class="forms-container principal">
          <div class="myform">
            <div class="forms-container">
                <div class="form-div-1">
                  <h2>Comparativo Ejercicios Fiscales</h2>
                </div></div>
            <form action="" method="POST">
            <div class="forms-container">
                <div class="form-div-5">
                    <label>Ejercicio Fiscal:</label>
                    <select name="ejercicio" id="ejercicio">
                        <option></option>
                        <?php 
                        $ac= intval(date("Y"));
                        $i = 2010;
                        $ii= 2010;
                         while ($i<=$ac) {?>
                          <option><?php echo $i;?></option>
                           <?php
                              $i++;
                              }
                          ?>
                </select>
                </div>
                <div class="form-div-5">
                  <label>Ejercicio a comparar</label>
              <!--Denise Cigarroa Rodriguez 9/09/2020 
                Se agrego nuevo campo de comparación ejercicios fiscales-->
                    <div class="multipleSelection"> 
            <div class="selectBox" 
                onclick="showCheckboxes()"> 
                <select> 
                    <option></option> 
                </select> 
                <div class="overSelect"></div> 
            </div> 
  
            <div id="checkBoxes"> 
               <input type="hidden" id="0" name="check_list[]"> 
               <label><input type="checkbox" id="1" name="check_list[]"><span id="l1"></span></label>    
               <label><input type="checkbox" id="2" name="check_list[]"><span id="l2"></span></label> 
               <label><input type="checkbox" id="3" name="check_list[]"><span id="l3"></span></label> 
               <label><input type="checkbox" id="4" name="check_list[]"><span id="l4"></span></label> 
            </div> 
        </div>
      </div>
      <!-- ################################################################3 -->
            </div>
            <div class="forms-container">
              <div class="form-div-5">
                    <label>Declaración:</label>
                    <select name="declaracion" id="declaracion">
                       <option></option>
                        <option>INTERESES</option>
                        <option>PATRIMONIAL</option>
                    </select>
                </div>
                <div class="form-div-5">
                    <label>Tipo de Declaración:</label>
                    <select name="tipodecl" id="tipodecl">
                       <option></option>
                        <option>INICIAL</option>
                        <option>MODIFICACIÓN</option>
                        <option>CONCLUSIÓN</option>
                    </select>
                </div>
            </div>
            <div class="forms-container">
              <div class="form-div-5">
                    <label>Estatus Declaración:</label>
                    <select name="estatusdecl" id="estatusdecl">
                       <option></option>
                        <option>PRESENTADA</option>
                        <option>EXTEMPORANEA</option>
                    </select>
                </div>
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
          
             
              </div>
              <div class="forms-container">
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
                <div class="form-div-5">
            <input type="submit" name="filtrar" value="Generar" id="filtrar" style="margin-top: 6%;"> 
          </div>
              </div> 
            </form>
            <div class="principal">
              <!--Denise Cigarroa Rodriguez 9/09/2020 
                Se agrego nuevo campo de filtro ejercicios fiscales-->
            <form class="myformex" action="reporte-ejercicios-excel.php?rfc=<?php echo $rfc;?>" method="POST" id="formexc" target="_blank">
              <div class="forms-container">
                <div class="form-div-5">
              <input type="hidden" name="ejercicio_ex" id="ejercicio_ex">
              <input type="hidden" id="check_list_ex" name="check_list_ex"> 
              <input type="hidden" name="declaracion_ex" id="declaracion_ex">
              <input type="hidden" name="tipodecl_ex" id="tipodecl_ex">
              <input type="hidden" name="estatusdecl_ex" id="estatusdecl_ex">
              <input type="hidden" name="area_ex" id="area_ex">
              <input type="hidden" name="puesto_ex" id="puesto_ex">
                    
                      <button type="submit" name="excel" id="excel"><img class="btnexc" src="../../../images/excel.png" alt="Excel"></button>
                     </div></div>
          </form>
          <!--Denise Cigarroa Rodriguez 9/09/2020 
                Se agrego nuevo campo de filtro ejercicios fiscales-->
          <form class="myformpd" action="reporte-ejercicios-pdf.php?rfc=<?php echo $rfc;?>" method="POST" id="formpdf" target="_blank">

            <div class="forms-container">
                <div class="form-div-5">
            <input type="hidden" name="ejercicio_pd" id="ejercicio_pd">
            <input type="hidden" id="check_list_pd" name="check_list_pd"> 
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
          // Denise Cigarroa Rodriguez 9/09/2020 
          // Se agrego nuevo campo de para asignar valor ejercicios fiscales
         document.getElementById('ejercicio_ex').value = "<?php echo $opcionejercicio; ?>";
         document.getElementById('check_list_ex').value = "<?php echo $opcion_pd; ?>";
         document.getElementById('declaracion_ex').value = "<?php echo $opciondecl;?>";
         document.getElementById('tipodecl_ex').value = "<?php echo $opciontdecl; ?>";
         document.getElementById('estatusdecl_ex').value = "<?php echo $opcionesdecl; ?>";
         document.getElementById('area_ex').value = "<?php echo $opcionarea; ?>";
         document.getElementById('puesto_ex').value = "<?php echo $opcionpuesto; ?>";
         // PDF
         // Denise Cigarroa Rodriguez 9/09/2020 
          // Se agrego nuevo campo de para asignar valor ejercicios fiscales
         document.getElementById('ejercicio_pd').value = "<?php echo $opcionejercicio; ?>";
         document.getElementById('check_list_pd').value = "<?php echo $opcion_pd; ?>";
         document.getElementById('declaracion_pd').value = "<?php echo $opciondecl;?>";
         document.getElementById('tipodecl_pd').value = "<?php echo $opciontdecl; ?>";
         document.getElementById('estatusdecl_pd').value = "<?php echo $opcionesdecl; ?>";
         document.getElementById('area_pd').value = "<?php echo $opcionarea; ?>";
         document.getElementById('puesto_pd').value = "<?php echo $opcionpuesto; ?>";
      </SCRIPT>
  </body>
</html>
