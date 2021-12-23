<?php include("../include/header.php");?>
	<script>
/* 03-09-2020 DMQ Aviso para recordar guardado antes de salir.
		var aviso=0;
		$(".editar-aviso").on("change",function(){
			aviso=1;
			console.log(aviso);
		});*/
window.addEventListener('beforeunload', (event) => {
  event.returnValue = 'Asegúrate de haber guardado los cambios.';
});	</script>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-ti.php"); 
		include("sidebar-ti.php");
		$arreglo=get_avisos();
		//print_r($arreglo);
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Avisos del sistema</div>
			</div>
			<?php
			foreach ($arreglo as $key => $aviso) {
			?>
			<div class="forms-avisos">
				<div class="subsubtitle"><?php echo $aviso["tipo"]." - ".$aviso["nombre"]?></div>
				<div contenteditable class="editar-aviso" id="aviso_editado_<?php echo $aviso['id']?>"><?php echo $aviso["texto"]?></div>
				<div class="guardar-aviso"><button onclick="guardaraviso(<?php echo $aviso['id']?>)" class="guardar">Guardar</button></div>
			</div>
			<?php
			}
			?>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<button class="quitar-aviso">Aceptar</button>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>