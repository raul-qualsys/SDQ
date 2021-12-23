<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("menu2-1n2","menu3-1n2","menu1-2n");}
</script>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-rh.php"); 
		include("sidebar-rh.php"); 
		?>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>


		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Catálogos</div>
			</div>
			<div class="menu-options">
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/areas.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/rh-areas.php">Áreas de Adscripción</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/banco.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/cat-bancos.php">Bancos</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/dependencias.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/rh-dependencias.php">Dependencias</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/estado.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/cat-estados.php">Entidades Federativas</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/moneda.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/cat-monedas.php">Monedas</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/municipio.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/cat-municipios.php">Municipios</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/nacionalidad.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/cat-nacionalidades.php">Nacionalidades</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/pais.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/cat-paises.php">Países</a>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/puestos.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/rh-puestos.php">Puestos</a>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<?php include("../include/footer.php"); ?>
</body>
</html>