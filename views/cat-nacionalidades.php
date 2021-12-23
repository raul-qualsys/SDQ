<?php include("../include/header.php");
?>
<script type="text/javascript">
	window.onload = function (){menuopc("sub3-7p","menu3-1n2","menu1-2n");}
</script>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-rh.php"); 
		include("sidebar-rh.php"); 
 		if(isset($_GET["b"])){
 			$_POST["buscarcat"]=$_GET["b"];
 		}
 		if(isset($_POST["buscarcat"])){
 			$buscar=$_POST["buscarcat"];
 		}
 		else $buscar="";
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Nacionalidades</div>
			</div>
			<div class="forms-container">
				<form method="POST" action="cat-nacionalidades.php">
					<div id="busqueda">
						<input type="text" name="buscarcat" id="buscar" placeholder="Búsqueda" value="<?php echo $buscar; ?>">
						<button class="lupa" id="lupa" title="Buscar">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
						<div style="display: inline-block;text-align: right;">
							<button id="nuevo_elemento" class="boton-oculto" style="margin:1% 2%;height: auto;" type="button"><img src="<?php echo HTTP_PATH ?>/images/agregarnacion.png" style="height: 40px; width: auto;" title="Agregar nacionalidad"></button>
						</div>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
						<?php 
						if(isset($_POST['buscarcat']) || isset($_GET["p"])){
						    if(isset($_POST['buscarcat']))$busqueda = escape($_POST['buscarcat']);
						    else $busqueda = '';

						    $query = "SELECT nacionalidad,descr,estatus FROM qsy_nacionalidades WHERE (descr LIKE '%".$busqueda."%' or nacionalidad LIKE '%".$busqueda."%') ORDER BY descr";
						    $result=pg_query($conn,$query);
						    $total=pg_num_rows($result);
						    $elementos=10;
						    $paginas=ceil($total/$elementos);
						    $posicion=isset($_GET['p']) ? ($_GET['p']-1)*$elementos : 0;
						    $query = "SELECT nacionalidad,descr,estatus FROM qsy_nacionalidades WHERE (descr LIKE '%".$busqueda."%' or nacionalidad LIKE '%".$busqueda."%') ORDER BY descr LIMIT $elementos OFFSET $posicion";

						    $result=pg_query($conn,$query);
						    $val=pg_fetch_all($result);
						    if($val){
						    	?>
							<table cellspacing="0" cellpadding="0" class="search-table">
						        <tr>
						            <th style='width: 10%;'>Clave</th>
						            <th style='width: 40%;'>Nacionalidad</th>
						            <th style='width: 30%;'>Estatus</th>
						            <th style='width: 20%;padding-left: 3%;'>Editar</th>
						        </tr>
						        <?php
						            foreach ($val as $key => $registro) {
						            $id=$registro["nacionalidad"];
						            $nombre=$registro["descr"];
						            $estatus=$registro["estatus"];
						            ?>
			                        <tr>
			                        	<td><?php echo $id ?></td>
			                            <td>
			                            	<span id='nombre-input<?php echo $key ?>'><?php echo $nombre ?></span>
		                                    <input type='hidden' name='clave' id='clave<?php echo $key ?>' value="<?php echo $id ?>">
			                                <input type='text' name='nombre' value='<?php echo $nombre ?>' id='nombre<?php echo $key ?>' style='display: none;'>
		                                    <input type='hidden' name="estatus" id='estatus<?php echo $key ?>' value="<?php echo $estatus ?>">
			                            </td>
			                            <td>
			                            	<?php
			                            	if($estatus=="A"){
			                            		?>
			                            		<span>Activo</span>
			                            		<?php
			                            	}
			                            	else{
			                            		?>
			                            		<span>Inactivo</span>
			                            		<?php
			                            	}
			                            	?>
			                            </td>
			                            <td>
						                    <button class="boton-oculto boton-modificar pais-modificar" id="<?php echo $key ?>" type="button"><img src="<?php echo HTTP_PATH ?>/images/editar.png" style="height: 20px;"></button>
			                            </td>
			                        </tr>
			                        <?php
						        }
						        ?>
								</table>
								<!-- Fin de actualización -->
						        <div style="text-align: center;">
						        	<?php 
						        	/* 21-08-2020 DMQ-Qualsys Cambio de estilo en botones */
							       if(isset($_GET["p"]) && $_GET["p"]!=1){
							        	 ?>
							        	<a href="cat-nacionalidades.php?p=<?php echo $_GET['p']-1 ?>&b=<?php echo $busqueda ?>"><button class="seccion-anterior">Anterior</button></a>
							        	<?php 
							        }
						        	for($i=1;$i<=$paginas;$i++){
						        		if((!isset($_GET["p"]) && $i==1) || (isset($_GET["p"]) && $_GET["p"]==$i))
						        			$extra="pagina-seleccionada";
						        		else
						        			$extra="paginas-navegacion";
							        	if((isset($_GET["p"]) && (abs($i - $_GET["p"])<=3 || $i==1 || $i==$paginas)) || (!isset($_GET["p"]) && (($i<=4) || ($i==$paginas)))){
							        	?>
							        	<a href="cat-nacionalidades.php?p=<?php echo $i ?>&b=<?php echo $busqueda ?>" style="text-decoration: none;">
							        		<button class="<?php echo $extra ?>"><?php echo $i ?></button>
							        	</a>
							        	<?php
							        	 	}
							        	 if((isset($_GET["p"]) && $_GET["p"]>=6 && $i==$_GET["p"]-4)){
							        	 	?>
							        	 	...
							        	 	<?php
							        	 }
							        	 if((isset($_GET["p"]) && $_GET["p"]<=($paginas-5) && $i==$_GET["p"]+4) || (!isset($_GET["p"]) && 1<=($paginas-5) && $i==5)){
							        	 	?>
							        	 	...
							        	 	<?php
							        	 }
							        	} 
							        if((!isset($_GET["p"]) || $_GET["p"]!=$paginas) && $paginas!=1){
							        	 ?>
							        	<a href="cat-nacionalidades.php?p=<?php if(isset($_GET["p"])){echo $_GET['p']+1; }else echo 2;?>&b=<?php echo $busqueda ?>"><button class="seccion-siguiente">Siguiente</button></a>
							        	<?php 
							        }
						        	/* Fin de actualización */
							        ?>
						        </div>
							<?php
						    }
						    else{
						    	?>
						    	<div>No hay resultados</div>
						    	<?php
						    } 
						}
						?>
				</div>
			</div>
		</div>
		<div id="aceptar-cambio">
			<span>Los cambios se han guardado correctamente.</span>
			<button onclick='hidecambio();' id="otra-busqueda">Aceptar</button>
		</div>
		<div id="nuevo-elemento">
			<div style="text-align: center;"><span>Nacionalidad</span></div>
			<form id="form-nuevo-elemento">
			<table>
				<tr>
					<td class="label-table" style="width: 30%;">Clave:</td>
					<td style="width: 70%;">
						<input type="text" placeholder="Clave" name="clave" id="clave" maxlength="3" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Nombre:</td>
					<td>
						<input type="text" placeholder="Nombre" name="descripcion" id="descripcion" onkeypress="valida(event,'string')" onpaste="valida(event,'string')" maxlength="100" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Estatus:</td>
					<td style="text-align: left;">
						<input type="radio" name="estatus" value="A" checked>Activo
						<input type="radio" name="estatus" value="I">Inactivo
					</td>
				</tr>
			</table>
			<div style="text-align: center;">				
				<div id="mensaje" style="font-weight: bold;color: #ff0000;"></div>
				<button type="reset" id="cancelar_elemento" style="background-color: red;">Cancelar</button>
				<input type="hidden" name="nacionalidad" id="validar" value="X">
				<button id="guardar_elemento" class="guardar">Aceptar</button>
			</div>
			</form>
		</div>
</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>