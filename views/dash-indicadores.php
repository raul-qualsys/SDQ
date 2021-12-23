<!--Denise Cigarroa Rodriguez 24/08/2020 
	dash-indicadores sera menú hacia los distintos indicadores -->
<?php include("../include/header.php");?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-c.php"); 
		include("sidebar-contraloria.php"); 

		$rfc = $_SESSION['rfc'];

		?>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="menu-options">
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/grafico-torta.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/reportes/reportes_graficos/cumplimiento_declaraciones.php?rfc=<?php echo $rfc;?>">Cumplimiento de Declaraciones</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/grafico-horizontal.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/reportes/reportes_graficos/declaracion_area.php?rfc=<?php echo $rfc;?>">Declaraciones por Área</a>
						
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/grafico-vertical.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/reportes/reportes_graficos/tipos_declaraciones.php?rfc=<?php echo $rfc;?>">Tipo de Declaraciones</a>
						
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/grafico-estadistico.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/reportes/reportes_graficos/comparativo_ejercicios.php?rfc=<?php echo $rfc;?>">Comparativo Ejercicios Fiscales</a>
						
					</div>
				</div>
			</div>
		</div>
	<?php include("../include/footer.php"); ?>
</body>
</html>