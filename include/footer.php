<!-- 	19-08-2020
		DMQ - Qualsys
		Cambio de diseño y distribución del footer
-->
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo HTTP_PATH ?>/css/footer.css">
	<title></title>
	<?php pg_close($conn) or die; ?>
</head>

<body>
		<footer id="main-footer">
		<div class="div-left">
			<div>
			<a>
				<img class="icon" src="<?php echo HTTP_PATH ?>/css/icons/llamada-respuesta.png">
				<p>Tel: <?php echo TEL1_EMPRESA; ?> Ext <?php echo EXT1_EMPRESA; ?></p>				
			</a>
			</div>
			<div>
			<a href="#">
				<img class="icon" src="<?php echo HTTP_PATH ?>/css/icons/sobre.png">
				<p><?php echo CORREO1_EMPRESA; ?></p>
			</a>
			</div>
		</div>
			
		<div class="div-center">
		<center>
			<a href="#">
				<img class="iconcenter" src="<?php echo HTTP_PATH ?>/css/icons/mapas-y-banderas.png">
				<p class="center">
					<?php echo CALLE_EMPRESA." ".EXT_EMPRESA." ".COL_COMPLETE." ".MUN_COMPLETE."<br>".CP_EMPRESA." ".EST_COMPLETE; ?>
				</p>	
			</a>
		</center>
		</div>
		
		<div class="div-right">
			<div>
				<a href="<?php echo RED1_LINK; ?>" target="_blank">
					<img class="icon" src="<?php echo HTTP_PATH."/css/icons/".RED1_IMAGEN.".png" ?>">
					<p>: <?php echo RED1_EMPRESA; ?></p>				
				</a>
			</div>
			<div>
				<a href="<?php echo RED2_LINK; ?>" target="_blank">
					<img class="icon" src="<?php echo HTTP_PATH."/css/icons/".RED2_IMAGEN.".png" ?>">
					<p>: <?php echo RED2_EMPRESA; ?></p>	
				</a>
			</div>
		</div>
	</footer>
</body>
</html>