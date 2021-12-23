<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc_v2("menu1-2");}
</script>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-e.php"); 
		include("sidebar-patrimonial.php");
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">I. DECLARACIÓN DE SITUACIÓN PATRIMONIAL</div>
			</div>
 			<div class="forms-container">
			<div class="subsubtitle">Declaración presentada</div>
				<div class="results">
					<table cellspacing="0" cellpadding="0" style="text-align: center;">
						<tr>
						<th style="width: 10%;">Ejercicio</th>
						<th style="width: 20%;">Declaración</th>
						<th style="width: 20%;">Tipo de Declaración</th>
						<th style="width: 25%;">Dec. Imprimible</th>
						<th style="width: 25%;">Acción</th>
						</tr>
					<?php
					declaracion_a_presentar($conn,$_POST["rfc"],$_POST["ejercicio"],$_POST["declaracion"],$_POST["tipo-declaracion"],$_POST["declara_completo"]);
					?>
 					</table>
				</div>
			</div>
			<form action="1-datos-generales.php" method="POST">
				<input type="hidden" name="declaracion" value="<?php echo $_POST["declaracion"] ?>">
				<input type="hidden" name="tipo-declaracion" value="<?php echo $_POST["tipo-declaracion"] ?>">
				<input type="hidden" name="ejercicio" value="<?php echo $_POST["ejercicio"] ?>">
				<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
				<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
				<center>
					<button style="width: auto;margin: 10px;">Volver a declaración</button>
				</center>
			</form>
		</div>
		<div id="aceptar-cambio2">
			<span id="mensaje2"></span>
			<form action="#" method="POST">
				<input type="hidden" name="declaracion" value="<?php echo $_POST["declaracion"] ?>">
				<input type="hidden" name="tipo-declaracion" value="<?php echo $_POST["tipo-declaracion"] ?>">
				<input type="hidden" name="ejercicio" value="<?php echo $_POST["ejercicio"] ?>">
				<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
				<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
				<button>Aceptar</button>
			</form>
		</div>
		<div id="finalizar-declaracion">
			<span>¿Desea enviar su declaración?<br>Una vez enviada, no podrá realizar cambios.</span>
				<form action="declaracion-presentada.php" id="envio-dec" method="POST">
					<input type="hidden" name="envio_declaracion" value="Aceptar">
					<input type="hidden" name="declaracion" value="<?php echo $_POST["declaracion"] ?>">
					<input type="hidden" name="tipo-declaracion" value="<?php echo $_POST["tipo-declaracion"] ?>">
					<input type="hidden" name="ejercicio" value="<?php echo $_POST["ejercicio"] ?>">
					<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
					<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
				<!-- 	<button class="seccion-siguiente">Siguiente</button> -->
					<button type="button" id="cancelar-envio" style="background-color: red;">Cancelar</button>
					<button id="aceptar-envio">Aceptar</button>
				</form>
		</div>
		<div id="aviso-agregar">
			<span id="msj"></span>
			<button id="quitar-agregar">Aceptar</button>
		</div>
	</div>
	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>