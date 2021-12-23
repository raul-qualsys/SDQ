<?php include("include/header.php");
?>
<body>
	<?php include("include/header-buttons.php");

		if (isset($_POST['email-inst'])) {
			//print_r($_POST);
		    actualizacion_usuario($conn,$_SESSION["rfc"],pg_escape_string($_POST['email-inst']),pg_escape_string($_POST['email-per']),pg_escape_string($_POST['tel-casa']),pg_escape_string($_POST['tel-cel']));
		}
		$datos=datos_empleado($_SESSION["rfc"],$conn);
		if(isset($_FILES["file"])){
			$_FILES["file"]["name"]="profiles/".$_SESSION["rfc"].".png";
			move_uploaded_file($_FILES["file"]["tmp_name"],$_FILES["file"]["name"]);
		}
		if(file_exists("profiles/".$_SESSION['rfc'].".png")){
			$link="profiles/".$_SESSION['rfc'].".png";
		}
		else{
			$link=HTTP_PATH."/css/icons/usuario-masculino.png";
		}
		?>
	<div id="main-content">
		<div id="overlay"></div>
		<div class="content2" id="content">
			<div class="title0">
				<div class="title1"> <?php echo $datos["nombre"] ?> <?php echo $datos["apellido1"] ?> <?php echo $datos["apellido2"] ?> - Información General</div>
			</div>
			<form action="usuario.php" method="POST" enctype="multipart/form-data">
			<div class="user-info">
				<p>Nombre: <?php echo $datos["nombre"] ?> <?php echo $datos["apellido1"] ?> <?php echo $datos["apellido2"] ?></p>
				<p>RFC: <?php echo $datos["rfc"] ?></p>
				<p>CURP: <?php echo $datos["curp"] ?></p>
				<p>Correo institucional: <span class="user-edited"><?php echo $datos["correo1"] ?></span>
					<input style="width: 40%;display: none;" class="user-edit" type="text" name="email-inst" value="<?php echo $datos["correo1"] ?>" maxlength="50">
				</p>
				<p>Correo personal: <span class="user-edited"><?php echo $datos["correo2"] ?></span>
					<input style="width: 40%;display: none;"  class="user-edit" type="text" name="email-per" value="<?php echo $datos["correo2"] ?>" maxlength="50">
				</p>
				<p>Teléfono de casa: <span class="user-edited"><?php echo $datos["tel1"] ?></span>
					<input style="width: 40%;display: none;" class="user-edit" type="text" name="tel-casa" value="<?php echo $datos["tel1"] ?>" maxlength="10">
				</p>
				<p>Número celular: <span class="user-edited"><?php echo $datos["tel2"] ?></span>
					<input style="width: 40%;display: none;" class="user-edit" type="text" name="tel-cel" value="<?php echo $datos["tel2"] ?>" maxlength="10">
				</p>
			</div>
			<div class="user-photo botones-usuario">
					<img src="<?php echo $link ?>">
					<button type="button" id="cambiar-foto" hidden>Cambiar foto</button>
					<div id="archivo-cargado" hidden></div>
					<input id='file' type='file' name='file'hidden/>
			</div>
			<div class="botones">
				<div class="botones-usuario">
<!-- 					<input type="hidden" name="rfc" value="<?php echo $datos["rfc"] ?>"> -->
					<button type="button" id="editar-info">Editar información</button>
					<button type="submit" id="guardar-info" style="display: none;">Guardar información</button>
					<button type="button" id="cambio-pass">Cambiar contraseña</button>
				</div>
			</div>
			</form>
		</div>

	</div>
	<div id="cambio-password">
		<h2>Cambio de contraseña</h2>
		<form id="pass-change">
			<div class="pass-campo"><span>Contraseña anterior:</span></div>
			<div class="pass-campo"><input type="password" name="old-pass" class="reset-pass"></div>
			<div class="pass-campo"><span>Nueva contraseña:</span></div>
			<div class="pass-campo"><input type="password" name="new-pass" class="reset-pass"></div>
			<div class="pass-campo"><span>Confirmar contraseña:</span></div>
			<div class="pass-campo"><input type="password" name="new-pass2" class="reset-pass"></div>
			<input type="hidden" name="rfc" value="<?php echo $_SESSION["rfc"] ?>">
			<input type="hidden" name="passuser" id="pass" value="models/cambio-password.php">
			<button type="button" id="cancelar-cambiopass">Cancelar</button>
			<button type="button" id="confirmar-cambiopass">Aceptar</button>
		</form>
		<div id="pass-aviso" style="display: none;"></div>
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