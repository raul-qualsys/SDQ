<!--
	18-08-2020
	DMQ - Qualsys
	Cambio de estilos y flexbox en login
-->
<?php include("include/header.php");
	
	session_start();
	
	if(isset($_SESSION["rfc"])){
		header("Location: inicio.php");
	}
	if(!empty($_POST))
	{
		$usuario = $_POST['user_text'];
		$hc = $_POST['hc_text'];
		$password = $_POST['pass_text'];
		$passHash = password_hash($password, PASSWORD_BCRYPT);

		$error = '';
		$rfc=$usuario . $hc;


		$sql = "SELECT rfc, secuencia,contrasena FROM qsy_seguridad WHERE rfc = '$rfc'";
		$result=pg_query($conn,$sql);

		$val=pg_fetch_all($result);
		$rows = pg_num_rows($result);
		if($rows > 0) {
			$row = pg_fetch_assoc($result);
			$contra=$row['contrasena'];
			$contrasena=password_verify($password,$contra);

			if($contrasena){
			$_SESSION['rfc'] = $row['rfc'];
			$_SESSION['tipo_usuario'] = $row['secuencia'];

			header("location: inicio.php?aviso=1");
			}
			else{
				$error = "La contraseña es incorrecta.";
			}
		}
		else {
			$error = "El RFC es incorrecto.";
		}
	}
	?>
<body>
	<header id="main-header">
		<div class="logo">
			<a href="<?php echo HTTP_PATH ?>/inicio.php" style="text-decoration: none;">	
				<img class="imagen" src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_nivel.png" style="height:50px;width: auto;">
				<img src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_depend.png" style="height:50px;width: auto;">
			</a>
		</div>
	</header>
<!-- 18-08-2020 DMQ - Qualsys Cambio de flexbox en login -->
	<div id="main-content">
		<div class="main-title">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
	</div>
	<div style="display:inline-block;margin-top:180px;width: 30%;position: fixed;background-color: transparent;z-index: 2;">
		<div style="display:inline-block;width:100%;vertical-align: top;font-family: contenidos">
			<center>
			<img src="css/icons/video.png" style="width: 50px;"><br>
			<div style="display:inline-block;padding-left:1%;vertical-align: top;text-align: center;">
				<a href="tutoriales.php" class="extra-login" target="_blank">Videos de llenado</a>
			</div>
			</center>
		</div>
		<div style="display:inline-block;width:100%;text-align: right;margin-top:100px;">
			<center>
			<img src="css/icons/presentadas.png" style="width: 50px;"><br>
			<a href="buscar-publicas.php" class="extra-login" target="_blank">Consultar versiones públicas</a>
			</center>
		</div>
	</div>
	<div id="main-content-login">
		<div class="login-container">
			<div id="login-box">
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
				<div style="padding:3% 6%;">
					<label>Usuario (RFC):</label>
					<input type="text" id="user_text" name="user_text" style="width:80%;background-color:white;" placeholder="10 caracteres" minlength="10" maxlength="10" required>
				</div>
				<div style="padding:3% 6%;">
					<label>Homoclave:</label>
					<input type="text" id="hc_text" name="hc_text" style="width:50%;background-color:white;" placeholder="3 caracteres" minlength="3" maxlength="3" required>
				</div>
				<div style="padding:3% 6% 0;">
					<label>Contraseña:</label>
					<input type="password" id="pass_text" name="pass_text" style="width:80%;background-color:white;display: inline-block;" placeholder="Contraseña" required>
				</div>
				<div style="display:inline-block;padding-left:6%;vertical-align: top;text-align: center;">
					<a href="recuperar-password.php" class="extra-login">Olvidé mi contraseña</a>
				</div>
				<div>
					<center>						
						<button id="login-button">Ingresar</button>
					</center>
					<input type="hidden" name="aviso">
<!-- 					<button id="login-button">Ingresar</button> -->
					<?php 
					if(isset($error)){
					?>
					<div id="pass-aviso"><?php echo $error; ?></div>
					<?php }?>
				</div>
			</div>
			</form>
		</div>
	</div>
<!-- Fin de actualización -->
	<?php
		include("include/footer.php"); 
	?>

</body>
</html>