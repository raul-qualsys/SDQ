<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-rh.php"); 
 		include("sidebar-rh.php"); 
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">SUBIR PLANTILLA</div>
			</div>
			<div class="forms-container">
			<form id="plantilla-usuarios" enctype="multipart/form-data">
				<div class="forms-registro" style="margin: 10px;">
					<input type="file" name="imported">
				</div>
				<div class="botones">
					<div class="botones-submit">
						<a href="<?php echo HTTP_PATH ?>/views/admin-personal.php" style="text-decoration: none;">
							<button id="cancelar" type="button">Cancelar</button>
						</a>
						<input type="hidden" name="user" value="<?php echo $_SESSION['rfc']; ?>">
						<input type="hidden" name="perfil" value="RH">
						<button type="submit" id="guardar-user" style="width: auto;">Cargar archivo</button>
					</div>
				</div>
			</form>
			</div>
			<div id="confirmacion"></div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<button class="quitar-aviso">Aceptar</button>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>