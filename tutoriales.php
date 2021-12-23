<?php include("include/header.php");?>
<body>
	<header id="main-header">
		<div class="logo">
			<a href="<?php echo HTTP_PATH ?>/inicio.php" style="text-decoration: none;">	
				<img class="imagen" src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_nivel.png" style="height:50px;width: auto;">
				<img src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_depend.png" style="height:50px;width: auto;">
			</a>
		</div>
	</header>
	<div id="main-content">
		<div id="overlay"></div>
		<div class="content2" id="content">
			<div class="title0">
				<div class="title1">Tutoriales</div>
			</div>
			<div class="notif-info">
				<div class="subtitle" style="font-size: 16px;font-weight: bold;">Declaración Patrimonial</div>
				<video height="360" style="margin-bottom: 20px;" controls>
					<source src="videos/DeclaracionPatrimonial.mp4" type="video/mp4">
					Tú navegador no soporta la función de video.
				</video>
				<div class="subtitle" style="font-size: 16px;font-weight: bold;">Declaración de Intereses</div>
				<video height="360" controls>
					<source src="videos/DeclaracionIntereses.mp4" type="video/mp4">
					Tú navegador no soporta la función de video.
				</video>
			</div>
		</div>
	</div>
	<div id="pass-exito" style="display: none;">
		<span>La contraseña se ha cambiado satisfactoriamente.</span>
		<button type="button" id="pass-aceptar">Aceptar</button>
	</div>

	<?php
		include("include/footer.php"); 
	?>
</body>
</html>