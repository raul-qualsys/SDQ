<?php include("../../include/header.php"); ?>
<script type="text/javascript">
	window.onload = function (){menuopc_v2("menu1-2");}
</script>
<body>
	<?php include("../../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../../controllers/permiso-e.php"); 
		include("sidebar-intereses.php");
		$datos=datos_declarante($_SESSION["rfc"],$conn);
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">I. DECLARACIÓN DE INTERESES</div>
			</div>
 			<div class="forms-container">
					<?php
					declaraciones_pendientes($conn,$datos["rfc"],"I");
					?>
			</div>
 			<div class="forms-container">
			<div class="subsubtitle">Declaraciones presentadas</div>
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
					declaraciones_presentadas($conn,$datos["rfc"],"I");
					?>
 					</table>
				</div>
			</div>
		</div>
		<div id="aceptar-cambio">
			<span>Primero debes enviar la Declaración Patrimonial correspondiente.</span>
			<button onclick='hidecambio();'>Aceptar</button>
		</div>
	</div>

	<?php
		include("../../include/footer.php"); 
	?>

</body>
</html>