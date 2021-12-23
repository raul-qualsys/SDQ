<?php include("../../include/header.php");?>
<body>
	<?php include("../../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../../controllers/permiso-e.php"); 
		include("sidebar-intereses.php");
		if(isset($_POST["ejercicio"])){
    		$_SESSION["ejercicio"]=$_POST["ejercicio"];
    		$_SESSION["tipo_declaracion"]=$_POST["declaracion"];
		}
		?>
		<div id="overlay" style="display: inline-block;"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>


		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Declaración presentada</div>
			</div>
			<div class="menu-options">
				<div class="subtitle">¡TU DECLARACIÓN HA SIDO PRESENTADA EXITOSAMENTE!</div>
				<div class="catalogo-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/home.png">
					<div class="menu-option-text">
						<a href="<?php echo HTTP_PATH ?>/inicio.php">
							<button class="button-descarga">Inicio</button>
						</a>
					</div>
				</div>
				<div class="catalogo-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/download.png">
					<div class="menu-option-text">
						<form action="<?php echo HTTP_PATH ?>/views/reportes/acuse_recibo.php" method="POST" target="_blank">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION["rfc"]; ?>">
							<input type="hidden" name="ejercicio" value="<?php echo $_SESSION["ejercicio"]; ?>">
							<input type="hidden" name="tipo_decl" value="<?php echo $_SESSION["tipo_declaracion"]; ?>">
							<input type="hidden" name="declaracion" value="I">
							<button class="button-descarga">Descargar acuse</button>
						</form>
					</div>
				</div>
				<div class="catalogo-option-div">
					<img src="<?php echo HTTP_PATH ?>/images/download.png">
					<div class="menu-option-text">
						<form action="<?php echo HTTP_PATH ?>/views/reportes/reporte_declaracion_intereses.php" method="POST" target="_blank">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION["rfc"]; ?>">
							<input type="hidden" name="ejercicio" value="<?php echo $_SESSION["ejercicio"]; ?>">
							<input type="hidden" name="tipo_decl" value="<?php echo $_SESSION["tipo_declaracion"]; ?>">
							<input type="hidden" name="declaracion" value="I">
							<button class="button-descarga">Descargar declaracion</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="aviso-agregar" style="display: inline-block;">
			<span id="msj">Se ha enviado el acuse de recibo a tu correo electrónico.</span>
			<button id="quitar-agregar">Aceptar</button>
		</div>
	</div>
		
	<?php include("../../include/footer.php"); ?>
</body>
</html>