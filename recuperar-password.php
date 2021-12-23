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
			<form id="form-pass-change" method="POST">
				<div class="forms-rh">
					Por favor, ingresa tu RFC y el correo registrado en el sistema para realizar el cambio de contraseña.<br><br>
					<table class="container-table">
						<tr>
							<td class="label-table">RFC (13 dígitos):</td>
							<td class="input-table">
								<input type="text" placeholder="RFC" name="rfc" minlength="13" maxlength="13" required="">
							</td>
						</tr>
						<tr>
							<td class="label-table">Correo electrónico:</td>
							<td class="input-table">
								<input type="text" placeholder="Correo institucional" name="email" required>
							</td>
						</tr>
					</table>
				</div>
				<div></div>
				<div class="botones">
					<div class="botones-submit">
						<input type="hidden" name="pass_change" value="1">
						<button type="submit" id="enviar-pass" style="width: 50%;">Cambiar contraseña</button>
					</div>
				</div>
		</div>
		</form>
	</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<a href="login.php"><button type="button" class="quitar-aviso">Aceptar</button></a>
		</div>
	<?php
		include("include/footer.php"); 
	?>
</body>
</html>