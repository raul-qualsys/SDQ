<?php include("../include/header.php");
include("../include/conexion.php");

$sqlp='SELECT DISTINCT nivel FROM qsy_puestos order by nivel asc';
$result=pg_query($conn,$sqlp);
$reg=pg_fetch_all($result);
$ig=0;

$qlg3="SELECT DISTINCT descr
   FROM qsy_areas_adscripcion order by descr asc;";
   $resg3=pg_query($conn,$qlg3);
   $reg3=pg_fetch_all($resg3); 
   $ig3=0;

$qlg2="SELECT DISTINCT descr
   FROM qsy_puestos order by descr asc;";
   $resg2=pg_query($conn,$qlg2);
   $reg2=pg_fetch_all($resg2); 
   $ig2=0;

?>
<body> <?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php include("../controllers/permiso-c.php"); include("sidebar-contraloria.php");?>
    <div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Reporte de Declaraciones Versión Pública</div>
			</div>
			<form id="reporte">
				<div class="forms-container">
					<div class="form-div-3">
						<label>Declaración</label>
						<select name="decl">
                        <option></option>
                        <option>PATRIMONIAL</option>
                        <option>INTERESES</option>
                    </select>
					</div>
					<div class="form-div-3">
						<label>Año de Presentación</label>
						<select name="anio">
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
					<div class="form-div-3">
						<label>Area de Adscripción</label>
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
					<div class="form-div-3">
						<label>Nivel de Empleo</label>
						<select id="nivel"  name="nivel">
							<option></option>
              				<?php 
                          foreach($reg as $rep)
                          {
                           $a = implode($reg[$ig]);?>
                           <option><?php echo $a;?></option>
                          <?php 
                          $ig++;}
                          ?>
              			</select>
					</div>
                     <div class="form-div-3">
						<label>Puesto</label>
						<select name='ptos'>
							<option></option>
                         <?php 
                          foreach($reg2 as $rep2)
                          {
                           $a2 = implode($reg2[$ig2]);?>
                           <option><?php echo $a2;?></option>
                          <?php 
                          $ig2++;}
                          ?>
              			</select>
					</div>
				</div>
				
					<div class="forms-container">
					<div class="form-div-3"></div>
					<div class="form-div-3"></div>
					<div class="form-div-3">
            <input type="hidden" name="enlace" value="reporte-publicas-excel" id="enlace">
            <input type="hidden" name="enlace2" value="ReporteVPublica" id="enlace2">
						<input type="submit" name="generar" value="Generar" id="generar">
					</div>
				</div>
			</form>
		</div>
    <div id="aceptar-cambio">
      <span id="mensaje"></span>
          <button class="quitar-aviso">Aceptar</button>
    </div>
	</div><?php include("../include/footer.php"); ?>
</body>
</html>