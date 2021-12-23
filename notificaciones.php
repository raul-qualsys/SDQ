<?php include("include/header.php");
?>
<body>
	<?php include("include/header-buttons.php");?>
	<div id="main-content">
		<div id="overlay"></div>
		<div class="content2" id="content">
			<div class="title0">
				<div class="title1"> Notificaciones</div>
			</div>
			<div class="notif-info">
				<center>
						
					<?php notificar_declaraciones($conn,$_SESSION["rfc"],"P")?>
					<?php notificar_declaraciones($conn,$_SESSION["rfc"],"I")?>
				</center>
			</div>
		</div>

	</div>
	<?php
		include("include/footer.php"); 
	?>

</body>
</html>