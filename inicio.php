<!-- 	21-08-2020 
		DMQ - Qualsys 
		Cambio visual a nueva propuesta.
-->
<?php include("include/header.php");
if(isset($_GET["aviso"])){ ?>
	<script>
	$(document).ready(openDialog3);
	</script>
	<?php
	}
	include("include/header-buttons.php");
			$rfc = htmlspecialchars($_SESSION["rfc"] ?? '');

			$rol=array();
		if($rfc){
			$sql = "SELECT rol FROM qsy_roles WHERE rfc='$rfc' and estatus='A' order by rol";
			$result=pg_query($conn,$sql);
		    $val=pg_fetch_all($result);
		    if($val){
				foreach ($val as $key => $registro) {
					$rol[$key]=$registro["rol"];
				}
		    }
			$datos=datos_empleado($rfc,$conn);
		}
		$es_declarante=es_declarante($_SESSION["rfc"],$conn);
	?>
	<div id="main-content">
			<?php 
			$counter=0;
			$nombre="main-user-center";
			foreach($rol as $valor){
				if($valor=="E"){
					/* 21-08-2020 DMQ-Qualsys Configuración de inicio si no declara */
					if($es_declarante=="C" || $es_declarante=="P"){
						$nombre="main-user";
					}
					/* Fin de declaración */
				}
				if($valor=="C" || $valor=="T" || $valor=="R"){
					$counter++;
				}
			}
			if($counter > 0){
				$clase="content";
				$titulo="header";
			?>
		<div id="menu" class="menu" style="display: block;text-align: center;">
			<ul>
				<li class="liL0">
					<p>Menú Principal</p>
				</li>
			<?php 
			foreach($rol as $valor){
				if($valor=="C"){
				 ?>
				<ul>
					<li class="liL1" style="height:50px;text-align: center;">
						<a href="<?php echo HTTP_PATH ?>/menucontraloria.php">
							<p>Auditoría</p>
						</a>
					</li>
				</ul>
				<?php
				}
				if($valor=="R"){
				?>
				<ul>
					<li class="liL1" style="height:50px;text-align: center;">
						<a href="<?php echo HTTP_PATH ?>/menurecursos.php">
							<p>Recursos Humanos</p>
						</a>
					</li>
				</ul>
				<?php
				}
				if($valor=="T"){
				?>
				<ul>
					<li class="liL1" style="height:60px;text-align: center;">
						<a href="<?php echo HTTP_PATH ?>/menuti.php">
							<p>Tecnologías de la Información</p>
						</a>
					</li>
				</ul>
				<?php } 
			} ?>
			</ul>
		</div>
		<?php 
			}
			else {
				$clase="content2";
				$titulo="main-title";
			}
		?>
		<div class="<?php echo $titulo;?>">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>

		<div id="overlay"></div>
			<div id="popup" class="popup">
    			<a onclick="closeDialog('popup');" class="close"><img src="<?php echo HTTP_PATH ?>/images/cerrar.png"></a>
    			<div class="aviso">			
    				<?php echo get_notificacion(5,$conn);?>
    			</div>
    			<center>
    				<span  onclick="closeDialog('popup');"><a href="<?php echo HTTP_PATH ?>/views/declaracion-patrimonial.php">Sí protesto</a></span>
    			</center>
			</div>
			<center>
			<div  id="popup3" class="popup" style="top:4%">
    			<a onclick="closeDialog('popup3');" class="close"><img src="<?php echo HTTP_PATH ?>/images/cerrar.png"></a>
				<h2>Aviso de Privacidad.</h2>
				<div id="aviso2">
    			<p>
    				<?php echo get_notificacion(4,$conn);?>
				</p>
				</div>
				<div style="width:100%;margin:auto;text-align:center">
					<button id="aviso-button" onclick="closeDialog('popup3');">Continuar</button>
				</div>
			</div>
			</center>
			<div id="popup4" class="popup">
    			<a onclick="closeDialog('popup4');" class="close"><img src="<?php echo HTTP_PATH ?>/images/cerrar.png"></a>
    			<div class="aviso">
    				<?php echo get_notificacion(5,$conn);?>
    			</div>
				<center>	
					<span onclick="closeDialog('popup4');"><a href="<?php echo HTTP_PATH ?>/views/intereses/declaracion-intereses.php">Sí protesto</a></span>
				</center>
			</div>

		<div class="<?php echo $clase;?>" id="content">
			<div class="<?php echo $nombre;?>">
				<h2 class="nombre_inicio"><?php echo $datos["nombre"] ?> <?php echo $datos["apellido1"] ?> <?php echo $datos["apellido2"] ?></h2>
				<p style="padding-top: 0;margin-top: 0;"><?php echo $datos["correo2"] ?></p><br>
			</div>
			<?php 
			foreach($rol as $valor){

				if($valor=="E"){
					/* 21-08-2020 DMQ-Qualsys Configuración de inicio si no declara */
					if($es_declarante=="C" || $es_declarante=="P"){
				 ?>
			<div class="main-div-buttons">
				 <h2 class="subtitulos">Presenta tu Declaración</h2>
				<div class="main-button">
					<form>
						<button class="boton-principal" onclick="openDialog('popup')" type="button">
							<img src="<?php echo HTTP_PATH ?>/images/patrimonial.png">
							<br>Patrimonial								
						</button>						
					</form>
				</div>
				<div class="main-button">
					<form>
						<button class="boton-principal" onclick="openDialog('popup4')" type="button">
							<img src="<?php echo HTTP_PATH ?>/images/intereses.png">
							<br>Intereses
						</button>
					</form>
				</div>
			</div>
				<?php
					}
					/* Fin de declaración */
				}
			}
			?>
		</div>
	</div>
	<?php include("include/footer.php"); ?>
</body>
</html>