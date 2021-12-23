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
	<div id="main-content" style="text-align: center;">
		<div id="overlay"></div>
		<div class="header3">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="conten" id="content">
			<div class="title0">
				<div class="title1">Cambio de contraseña</div>
			</div>
			<center>
				
		<div id="cambio-password2">
			<form id="pass-change">
				<div class="pass-campo"><span>Nueva contraseña:</span></div>
				<div class="pass-campo"><input type="password" name="new-pass" class="reset-pass"></div>
				<div class="pass-campo"><span>Confirmar contraseña:</span></div>
				<div class="pass-campo"><input type="password" name="new-pass2" class="reset-pass"></div>
				<input type="hidden" name="rfc" value="<?php echo $_GET["rfc"] ?>">
				<input type="hidden" name="pass" value="<?php echo $_GET["pass"] ?>">
				<input type="hidden" name="passmail" id="pass" value="models/cambio-password.php">
				<button type="button" id="confirmar-cambiopass">Aceptar</button>
			</form>
			<div id="pass-aviso" style="display: none;"></div>
		</div>
			</center>
			<center>
				
		<div id="pass-exito2" style="display: none;">
			<span>La contraseña se ha cambiado satisfactoriamente.</span>
			<a href="login.php"><button type="button" id="pass-aceptar">Aceptar</button></a>
		</div>
			</center>

	</div>

	<?php
		include("include/footer.php"); 
	?>

</body>
</html>