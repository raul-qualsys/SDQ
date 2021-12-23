<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-ti.php"); 
		include("sidebar-ti.php"); 
		$alertaI=get_tiempos($conn,"A","I");
		$alertaM=get_tiempos($conn,"A","M");
		$alertaC=get_tiempos($conn,"A","C");
		$toleraI=get_tiempos($conn,"T","I");
		$toleraM=get_tiempos($conn,"T","M");
		$toleraC=get_tiempos($conn,"T","C");
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">TIEMPOS DE NOTIFICACIONES</div>
			</div>
			<div class="forms-container">
			</div>
			<div class="forms-container">
				<div>
					Los tiempos de alerta toman como base los 60 días indicados en el Diario Oficial de la Federación menos los días indicados en esta página para el envío de correos de declaraciones pendientes.
				</div>
				<br>
				<div>
					Los tiempos de tolerancia toman como base los 60 días indicados en el Diario Oficial de la Federación mas los días indicados en esta página para el envío de correos de declaraciones pendientes.
				</div>
				<div class="results">
					<form id="form-tiempos">
					<table cellspacing="0" cellpadding="0" style="text-align: center;">
					
						<tr>
							
						<th style="width: 34%;">Tipo de Declaración</th>
						<th style="width: 33%;">Tiempo de Alerta (días)</th>
						<th style="width: 33%;">Tiempo de Tolerancia (días)</th>
						</tr>
					<!-- foreach resultado -->
					<div class="results-search">
						<tr>
							<td>Inicial</td>
							<td>
								<input type="number" name="alertaI" class="input-tiempos" value="<?php echo $alertaI; ?>" required>
							</td>
							<td>
								<input type="number" name="toleraI" class="input-tiempos" value="<?php echo $toleraI; ?>" required>
							</td>
						</tr>
						<tr>
							<td>Modificación</td>
							<td>
								<input type="number" name="alertaM" class="input-tiempos" value="<?php echo $alertaM; ?>" required>
							</td>
							<td>
								<input type="number" name="toleraM" class="input-tiempos" value="<?php echo $toleraM; ?>" required>
							</td>
						</tr>
						<tr>
							<td>Conclusión</td>
							<td>
								<input type="number" name="alertaC" class="input-tiempos" value="<?php echo $alertaC; ?>" required>
							</td>
							<td>
								<input type="number" name="toleraC" class="input-tiempos" value="<?php echo $toleraC; ?>" required>
							</td>
						</tr>
						
					</div>
					<!-- fin foreach -->
					</table>
					<br><br>
					<a href="../menuti.php"><button id="cancelar" type="button">Cancelar</button></a>
					<button class="boton-modificar guardar">Guardar</button>
				</form>
				</div>
			</div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<form action="#" method="POST">
					<button class="seccion-siguiente">Aceptar</button>
				</form>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>