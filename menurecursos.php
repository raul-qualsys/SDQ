<?php include("include/header.php");?>
<body>
	<?php include("include/header-buttons.php");?>
	<div id="main-content">
		<?php 
		include("controllers/permiso-rh.php"); 
		include("views/sidebar-rh.php"); 
		?>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>


		<div class="content" id="content">
			<div class="menu-options">
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/admin_personal.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/admin-personal.php">Administración de Personal</a>
						<p>Administre los datos personales de los empleados así como su relación con la dependencia.</p>	
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/mantenimiento.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/catalogos-sistema.php">Mantenimiento de Catálogos</a>
						<p>Administre los elementos pertenecientes a los catálogos del sistema.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<?php include("include/footer.php"); ?>
</body>
</html>