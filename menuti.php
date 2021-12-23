<?php include("include/header.php");?>
<body>
	<?php include("include/header-buttons.php");?>
	<div id="main-content">
		<?php 
		include("controllers/permiso-ti.php"); 
		include("views/sidebar-ti.php"); 
		?>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>


		<div class="content" id="content">
			<div class="menu-options">
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/admin_personal.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/ti-usuario.php">Administración de Empleados</a>
						<p>Administre la información y estatus de los usuarios del sistema.</p>	
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/alertas.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/ti-configuracion.php">Notificaciones/Alertas</a>
						<p>Revise el tiempo asignado a las notificaciones de las declaraciones pendientes de los empleados.</p>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/roles.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/ti-roles.php">Asignación de roles</a>
						<p>Administre los roles que tiene cada empleado.</p>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/avisos.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/ti-avisos.php">Avisos del sistema</a>
						<p>Administre los avisos configurados en el sistema.</p>
					</div>
				</div>
				<div class="menu-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/config.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/views/ti-sistema.php">Configuración del sistema</a>
						<p>Configure la información general de la empresa en el sistema.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<?php include("include/footer.php"); ?>
</body>
</html>