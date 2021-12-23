	<body>
		<?php
		  	session_start();
	  		if(empty($_SESSION["rfc"])){ 
	    	header("Location: ".HTTP_PATH."/login.php");
	  		}
	  		$nombre=nombre_empleado($_SESSION["rfc"],$conn);
	  		//print_r($datos);
		?>
		<header id="main-header">
			<div class="logo">
				<a href="<?php echo HTTP_PATH ?>/inicio.php" style="text-decoration: none;">
					<img class="imagen" src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_nivel.png" style="height:50px;width: auto;">
					<img src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_depend.png" style="height:50px;width: auto;">
				</a>
			</div>
			<div class="buttons">
				<a href="<?php echo HTTP_PATH ?>/include/logout.php">	
					<div class="button">
						<img class="icon" src="<?php echo HTTP_PATH ?>/css/icons/cerrar-sesion.png" alt="cerrarsesion">
						<p>Cerrar sesión</p>
					</div>
				</a>
				<a href="<?php echo HTTP_PATH ?>/usuario.php">	
					<div class="button">
						<img class="icon" src="<?php echo HTTP_PATH ?>/css/icons/usuario-masculino.png" alt="usuario">
						<p><?php echo $nombre ?></p>
					</div>
				</a>
				<?php 
					//$es_declarante=es_declarante($_SESSION["rfc"],$conn);
					//if($es_declarante=="S"){
					$rfc = htmlspecialchars($_SESSION["rfc"] ?? '');

					$rol=array();
					$sql = "SELECT rol FROM qsy_roles WHERE rfc='$rfc' and estatus='A' order by rol";
					$result=pg_query($conn,$sql);
				    $val=pg_fetch_all($result);
				    if($val){
					foreach ($val as $key => $registro) {

					$rol[$key]=$registro["rol"];
					}
					foreach($rol as $valor){
						if($valor=="E"){
				 ?>
				<a href="<?php echo HTTP_PATH ?>/notificaciones.php">	
					<?php 
						$c1=aviso_notificaciones($conn,$_SESSION["rfc"],"P");
						$c2=aviso_notificaciones($conn,$_SESSION["rfc"],"I");
						$c3=$c1+$c2;
						//print_r($c3);
					?>
					<div class="button">
						<?php if($c3!=0){ ?>
						<div style="position: fixed;background-color: red;color:#FFFFFF;
							margin: auto auto auto 4%;padding: 0 .2%;border-radius: 5px;font-family: contenidos;font-size: 12px;"><?php echo $c3; ?></div>
						<?php } ?>
						<img class="icon" src="<?php echo HTTP_PATH ?>/css/icons/packard-bell.png" alt="home">
						<p>Notificaciones</p>		
					</div>
				</a>
				<?php 
				//} 
						}	
					} 
				}
				?>
				<a href="<?php echo HTTP_PATH ?>/inicio.php">	
					<div class="button">
						<img class="icon" src="<?php echo HTTP_PATH ?>/css/icons/casa.png" alt="home">
						<p>Home</p>		
					</div>
				</a>
			</div>
		</header>
	</body>
