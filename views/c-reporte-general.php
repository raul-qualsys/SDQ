  <!-- Denise Cigarroa 21/08/2020 
    En esta parte se modificaron los parametros de los filtros
    y se alinearon los parametros -->
  <?php include("../include/header.php");?>
  <?php include("../include/conexion.php");

  $qlg1="SELECT distinct descr FROM qsy_puestos 
         where descr <> ''
         ORDER BY descr  ASC;";
     $resg1=pg_query($conn,$qlg1);
     $reg1=pg_fetch_all($resg1); 
     $ig1=0;

     $qlg3="SELECT DISTINCT adscripcion FROM qsy_reporte_gral_vw  
     where adscripcion <> 'SIN REGISTRO' ORDER by adscripcion asc";
     $resg3=pg_query($conn,$qlg3);
     $reg3=pg_fetch_all($resg3); 
     $ig3=0;
     ?>
  <body>
    <?php include("../include/header-buttons.php");?>
  	<div id="main-content">
  		<?php include("../controllers/permiso-c.php"); include("sidebar-contraloria.php");
  		include("../include/conexion.php");?>

      <div id="overlay"></div>
  		<div class="header">
  			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
  		</div>
  		<div class="content" id="content">
  			<div class="title0">
  				<div class="title1">Reporte General de Declaraciones</div>
  			</div>
  			<form id="reporte">
  				<div class="forms-container">
            <div class="form-div-3">
              <label>Declaración:</label>
              <select name="declaracion">
                <option></option>
                <option>PATRIMONIAL</option>
                <option>INTERESES</option>
              </select>
            </div>
  					<div class="form-div-3">
              <label>Tipo de Declaración:</label>
              <select name="tip_declaracion">
                <option></option>
                <option>INICIAL</option>
                <option>MODIFICACIÓN</option>
                <option>CONCLUSIÓN</option>  
              </select>
            </div>
  					<div class="form-div-3">
              <label>Ejercicio:</label>
              <input type="text" placeholder="Ejercicio" name="ejerciciom" maxlength="4" minlength="4">
            </div>
  				</div>
  				<div class="forms-container">
            <div class="form-div-3">
              <label>Estatus de Declaración</label>
              <select name='stus_m'>
                <option></option>
                        <option>OMISA</option>
                        <option>PRESENTADA</option>
                        <option>EN TIEMPO</option>
                        <option>EXTEMPORANEA</option>
                      </select>
                    </div>
                    <div class="form-div-3">
                      <label>Servidor Público:</label>
                      <input type="text" placeholder="Nombre Servidor Público" name="servidor">
                    </div>
                    <div class="form-div-3">
                      <label>Estatus de Servidor Público:</label>
                      <select name="estatus_serv">
                        <option></option>
                  <option>ACTIVO</option>
                <option>BAJA</option>
              </select>
            </div>
          </div>
  				<div class="forms-container">
            <div class="form-div-3">
              <label>Puesto:</label>
                      <select name="puesto">
                          <option></option>
                           <?php 
                            foreach($reg1 as $rep1)
                            {
                             $a1 = implode($reg1
                              [$ig1]);?>
                             <option><?php echo $a1;?></option>
                            <?php 
                            $ig1++;}
                            ?>
                      </select>
            </div>
  					<div class="form-div-3">
              <label>Área de Adscripción:</label>
                      <select name="adscripcion">
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
                    <div class="form-div-3"></div>
                    <div class="form-div-3"></div>
                    <div class="form-div-3">
                      <input type="hidden" name="enlace" value="reporte-general-excel" id="enlace">
                      <input type="hidden" name="enlace2" value="ReporteGeneral" id="enlace2">
                      <input type="submit" name="generar" value="Generar" id="generar">
                    </div>
                  </div>
                </form>
              </div>
              <!-- 09-09-2020 DMQ-Qualsys Se cambiaron los avisos de los reportes. -->
              <div id="aceptar-cambio">
                <span id="mensaje"></span>
                    <button class="quitar-aviso">Aceptar</button>
              </div>
              <!-- Fin de actualización. -->
            </div><?php include("../include/footer.php"); ?>
          </body>
          </html>