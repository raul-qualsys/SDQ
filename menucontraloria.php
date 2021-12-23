<?php include("include/header.php");?>
<body>
	<?php include("include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("controllers/permiso-c.php"); 
		include("views/sidebar-contraloria.php"); 
//		consulta();
		?>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>


		<div class="content" id="content">
			<div class="menu-options">
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/estatus_declaraciones.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/c-estatus-declaraciones.php">Estatus de Declaraciones</a>
						<p>Verifique el estado de entrega de los distintos tipos de declaraciones por la plantilla completa o por empleado.</p>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/dec_presentadas.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/c-declaraciones-presentadas.php">Declaraciones presentadas</a>
						<p>Revise la información de las declaraciones presentadas por cada empleado.</p>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/reportes.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/c-reportes.php">Reportes</a>
						<p>Consulte el Reporte General de Declaraciones, el Reporte de Declaraciones de Modificación y el Reporte de Versiones Públicas de los empleados.</p>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/indicadores.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/dash-indicadores.php">Indicadores</a>
						<p>Consulte los indicadores por cumplimiento de declaraciones, declaraciones por área de adscripción, tipos de declaraciones y comparativo de ejercicios fiscales de los empleados.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include("include/footer.php"); ?>
</body>
</html>