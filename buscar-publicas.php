<?php include("include/header.php");?>
<body>
	<header id="main-header">
		<div class="logo">
			<a href="<?php echo HTTP_PATH ?>/inicio.php" style="text-decoration: none;">	
				<img class="imagen" src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_nivel.png" style="height:50px;width: auto;">
				<img src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_depend.png" style="height:50px;width: auto;">
			</a>
		</div>
	</header>
	<div id="main-content">
		<?php
 		if(isset($_GET["b"])){
 			$_POST["buscarvp"]=$_GET["b"];
 		}
 		if(isset($_POST["buscarvp"]))$buscar=$_POST["buscarvp"];
 		else $buscar="";
		?>
		<div id="overlay"></div>
		<div class="content2 busquedavp" id="content">
			<div class="title0">
				<div class="title1">Versiones públicas</div>
			</div>
			<div class="forms-container">
				<div style="margin-left: 40px;margin-bottom: 10px;">Ingresa el nombre completo del declarante (Nombre(s), primer apellido y segundo apellido):</div>
				<form id="search-ba" method="POST" action="buscar-publicas.php">
					<div id="busqueda">
						<input type="text" name="buscarvp" id="buscar" placeholder="Busque por Nombre de Declarante" value="<?php echo $buscar; ?>">
						<button class="lupa">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
						<?php 
						if(isset($_POST['buscarvp']) || isset($_GET["p"])){
						    if(isset($_POST['buscarvp']))$busqueda = escape($_POST['buscarvp']);
						    else $busqueda = '';
						    $arreglo_buscar = explode(' ',$busqueda);
						    $query_for="1=1";
						    for($i=0;$i<count($arreglo_buscar);$i++){
						    	$arreglo_buscar[$i]=str_replace(array("Á","É","Í","Ó","Ú","A","E","I","O","U"), "%", $arreglo_buscar[$i]);
						    	$query_for.=" AND (nombre LIKE '%".$arreglo_buscar[$i]."%' or primer_ap LIKE '%".$arreglo_buscar[$i]."%' or segundo_ap LIKE '%".$arreglo_buscar[$i]."%')";
						    }

						    $query = "SELECT nombre,primer_ap,segundo_ap,rfc FROM qsy_rh_empleados WHERE $query_for ORDER BY rfc";

						    $result=pg_query($conn,$query);
						    $val=pg_fetch_all($result);
						    if($val){
						    	?>
					    	<center>
						        <?php
						            foreach ($val as $key => $registro) {
						            $rfc=$registro["rfc"];
						            $nombre=$registro["nombre"];
						            $apaterno=$registro["primer_ap"];
						            $amaterno=$registro["segundo_ap"];
						            $nombre_completo=$nombre." ".$apaterno." ".$amaterno;
									$nombre_completo = str_replace("Á","A",$nombre_completo);
									$nombre_completo = str_replace("É","E",$nombre_completo);
									$nombre_completo = str_replace("Í","I",$nombre_completo);
									$nombre_completo = str_replace("Ó","O",$nombre_completo);
									$nombre_completo = str_replace("Ú","U",$nombre_completo);
									$busqueda = str_replace("Á","A",$busqueda);
									$busqueda = str_replace("É","E",$busqueda);
									$busqueda = str_replace("Í","I",$busqueda);
									$busqueda = str_replace("Ó","O",$busqueda);
									$busqueda = str_replace("Ú","U",$busqueda);
						            if(trim($busqueda)==trim($nombre_completo)){
						            ?>
									<div class="forms-container">
										<div class="subsubtitle"><?php echo $apaterno." ".$amaterno." ".$nombre; ?></div>
										<div class="results">
											<table cellspacing="0" cellpadding="0" style="text-align: center;">
												<tr>
												<th style="width: 25%;">Ejercicio</th>
												<th style="width: 25%;">Declaración</th>
												<th style="width: 25%;">Tipo de Declaración</th>
												<th style="width: 25%;">Declaración</th>
												</tr>
											<!-- foreach resultado -->
											<?php
											declaraciones_publicas($conn,$rfc);
											?>
											<!-- fin foreach -->
											</table>
										</div>
									</div>
						        <?php
						        	}
						        }
								?>
							</center>
							<?php
						    }
						    else{
						    ?>
						    <center>
							<table cellspacing="0" cellpadding="0" class="search-table" style="width: 80%;text-align: center;">
						        <tr>
						            <th style='width: 50%;'>Nombre</th>
						            <th style='width: 50%;'>Versión pública</th>
						        </tr>
							</table>
						    </center>
							<?php
						    }
						}
						 ?>
				</div>
			</div>
		</div>
	</div>

	<?php
		include("include/footer.php"); 
	?>
</body>
</html>