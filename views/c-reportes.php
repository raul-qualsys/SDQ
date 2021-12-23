<?php include("../include/header.php");?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-c.php"); 
		include("sidebar-contraloria.php"); 
//		consulta();
		?>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>


		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Reportes de Contraloría</div>
			</div>
			<div class="menu-options">
				<div class="catalogo-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/presentadas.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/c-reporte-general.php">Reporte General de Declaraciones</a>
					</div>
				</div>
				<div class="catalogo-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/presentadas.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/c-reporte-modificacion.php">Reporte de Declaraciones de Modificación</a>
					</div>
				</div>
				<div class="catalogo-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/presentadas.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/c-reporte-publico.php">Reporte de Declaraciones Versión Pública</a>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<?php include("../include/footer.php"); ?>
</body>
</html>