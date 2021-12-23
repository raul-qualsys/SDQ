<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-c.php"); 
		include("sidebar-contraloria.php"); 
//		consulta();
		?>

		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">DECLARACIONES PRESENTADAS</div>
			</div>
			<div class="forms-container">
			<div class="subsubtitle"><?php echo $_POST["apaterno"]." ".$_POST["amaterno"]." ".$_POST["nombre"]." - ".$_POST["rfc-datos"]; ?></div>
				<div class="results">
					<table cellspacing="0" cellpadding="0" style="text-align: center;">
<!-- 19-08-2020 DMQ-Qualsys Se cambió el tamaño de las columnas -->
						<tr>
						<th style="width: 20%;">Ejercicio</th>
						<th style="width: 20%;">Declaración</th>
						<th style="width: 20%;">Tipo de Declaración</th>
						<th style="width: 20%;">Dec. Imprimible</th>
						<th style="width: 20%;">Acuse</th>
						</tr>
<!--Fin de actualización-->
					<?php
					declaraciones_presentadas($conn,$_POST["rfc-datos"]);
					?>
					</table>
				</div>
			</div>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>