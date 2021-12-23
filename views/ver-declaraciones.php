<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-e.php"); 

		$datos=datos_declarante($_SESSION["rfc"],$conn);

		include("sidebar-patrimonial.php"); ?>

		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">DECLARACIONES PRESENTADAS</div>
			</div>
			<div class="forms-container">
			<div class="subsubtitle"><?php echo $datos["apaterno"]." ".$datos["amaterno"]." ".$datos["nombre"]." - ".$datos["rfc"]; ?></div>
				<div class="results">
					<table cellspacing="0" cellpadding="0" style="text-align: center;">
						<tr>
						<th style="width: 10%;">Ejercicio</th>
						<th style="width: 20%;">Declaración</th>
						<th style="width: 20%;">Tipo de Declaración</th>
						<th style="width: 25%;">Declaración</th>
						<th style="width: 25%;">Acuse</th>
						</tr>
					<!-- foreach resultado -->
					<?php
					declaraciones_presentadas($conn,$datos["rfc"],"P");
					?>
					<!-- fin foreach -->
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