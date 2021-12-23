<?php include("../include/header.php");?>
<body> <?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php include("../controllers/permiso-c.php"); include("sidebar-contraloria.php");?>
		<div class="header">
			<h1 id="header2">SISTEMA DE DECLARACIÓN PATRIMONIAL</h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Reporte General de Declaraciones</div>
			</div>
			<?php echo $_POST["rfc"]." ".$_POST["ejercicio"]." ".$_POST["declaracion"]." ".$_POST["tipo_decl"]; ?>
		</div>
	</div><?php include("../include/footer.php"); ?>
</body>
</html>