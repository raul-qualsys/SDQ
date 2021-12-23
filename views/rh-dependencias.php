<?php include("../include/header.php");
?>
<script type="text/javascript">
	window.onload = function (){menuopc("sub3-3p","menu3-1n2","menu1-2n");}
</script>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-rh.php"); 
		include("sidebar-rh.php"); 
 		if(isset($_GET["b"])){
 			$_POST["buscardepend"]=$_GET["b"];
 		}
 		if(isset($_POST["buscardepend"])){
 			$buscar=$_POST["buscardepend"];
 		}
 		else $buscar="";
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">DEPENDENCIAS</div>
			</div>
			<div class="forms-container">
				<form id="search-ba" method="POST" action="rh-dependencias.php">
					<div id="busqueda">
						<input type="text" name="buscardepend" id="buscar" placeholder="Busque por Nombre de Dependencia" value="<?php echo $buscar; ?>">
						<button class="lupa" id="lupa" title="Buscar">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
						<div style="display:inline-block;text-align: right;">
							<button id="nuevo_elemento" class="boton-oculto" style="margin:1% 2%;height: auto;" type="button"><img src="<?php echo HTTP_PATH ?>/images/depend_nueva.png" style="height: 40px; width: auto;" title="Nueva dependencia"></button>
						</div>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
						<?php 
						if(isset($_POST['buscardepend']) || isset($_GET["p"])){
						    if(isset($_POST['buscardepend']))$busqueda = escape($_POST['buscardepend']);
						    else $busqueda = '';

						    $query = "SELECT dependencia,descr,descr_corta,estatus,principal,otra FROM qsy_dependencias WHERE (descr LIKE '%".$busqueda."%') order by descr";
						    $result=pg_query($conn,$query);
						    $total=pg_num_rows($result);
						    $elementos=10;
						    $paginas=ceil($total/$elementos);
						    $posicion=isset($_GET['p']) ? ($_GET['p']-1)*$elementos : 0;

						    $query = "SELECT dependencia,descr,descr_corta,estatus,principal,otra FROM qsy_dependencias WHERE (descr LIKE '%".$busqueda."%') ORDER BY descr LIMIT $elementos OFFSET $posicion";

						    $result=pg_query($conn,$query);
						    $val=pg_fetch_all($result);
						    if($val){
						    	?>
					    	<!-- 20-08-2020 DMQ-Qualsys Estandarización de catálogos -->
							<table cellspacing="0" cellpadding="0" class="search-table">
						        <tr>
						            <th style='width: 50%;'>Dependencia</th>
						            <th style='width: 30%;'>Estatus</th>
						            <th style='width: 20%;padding-left: 3%;'>Editar</th>
						        </tr>
						        <?php
						            foreach ($val as $key => $registro) {
						            $id=$registro["dependencia"];
						            $dependencia=$registro["descr"];
						            $depend_corta=$registro["descr_corta"];
						            $estatus=$registro["estatus"];
						            $principal=$registro["principal"];
						            $otra=$registro["otra"];
						            ?>
			                            	<?php 
			                            	if($principal=="X")
			                            		$clase="clase-principal";
			                            	else
			                            		$clase="";
			                            	?>
			                        <tr class="<?php echo $clase ?>">    
			                            <td>
			                            		
			                            	<span id='nombre-input<?php echo $key ?>'><?php echo $dependencia ?></span>
			                            	<?php 
			                            	if($principal=="X"){
			                            		?>
			                            	<img src="<?php echo HTTP_PATH ?>/images/palomita.png" style="height: 13px;">
				                            	<?php
			                            	}
			                            	?>
			                                <input type='text' name='nombre_depend' value='<?php echo $dependencia ?>' id='nombre<?php echo $key ?>' style='display: none;'>
		                                    <input type='hidden' name='clave' id='clave<?php echo $key ?>' value="<?php echo $id ?>">
		                                    <input type='hidden' name='desc_corta' id='desc_corta<?php echo $key ?>' value="<?php echo $depend_corta ?>">
		                                    <input type='hidden' name="estatus" id='estatus<?php echo $key ?>' value="<?php echo $estatus ?>">
		                                    <input type='hidden' name="principal" id='principal<?php echo $key ?>' value="<?php echo $principal ?>">
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
			                            	<?php if($otra=="X"){
			                            		?>
						                    <button class="boton-oculto" type="button"><img src="<?php echo HTTP_PATH ?>/images/editar.png" style="height: 20px;"></button>
			                           		<?php
			                            	}else{ ?>
						                    <button class="boton-oculto boton-modificar depend-modificar" id="<?php echo $key ?>" type="button"><img src="<?php echo HTTP_PATH ?>/images/editar.png" style="height: 20px;"></button>
						                	<?php } ?>
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
							        	<a href="rh-dependencias.php?p=<?php echo $_GET['p']-1 ?>&b=<?php echo $busqueda ?>"><button class="seccion-anterior">Anterior</button></a>
							        	<?php 
							        }
						        	for($i=1;$i<=$paginas;$i++){
						        		if((!isset($_GET["p"]) && $i==1) || (isset($_GET["p"]) && $_GET["p"]==$i))
						        			$extra="pagina-seleccionada";
						        		else
						        			$extra="paginas-navegacion";
							        	if((isset($_GET["p"]) && (abs($i - $_GET["p"])<=3 || $i==1 || $i==$paginas)) || (!isset($_GET["p"]) && (($i<=4) || ($i==$paginas)))){
							        	?>
							        	<a href="rh-dependencias.php?p=<?php echo $i ?>&b=<?php echo $busqueda ?>" style="text-decoration: none;">
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
							        	<a href="rh-dependencias.php?p=<?php if(isset($_GET["p"])){echo $_GET['p']+1; }else echo 2;?>&b=<?php echo $busqueda ?>"><button class="seccion-siguiente">Siguiente</button></a>
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
			<div style="text-align: center;"><span>Dependencias</span></div>
			<form id="form-nuevo-elemento">
			<table>
				<tr>
					<td class="label-table">Dependencia:</td>
					<td>
						<input type="text" placeholder="Dependencia" name="descripcion" id="descripcion"  onkeypress="valida(event,'string')" onpaste="valida(event,'string')" maxlength="200" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Descripción corta:</td>
					<td>
						<input type="text" placeholder="Descripción" name="descripcion_corta" id="descripcion_corta" onkeypress="valida(event,'string')" onpaste="valida(event,'string')" maxlength="35" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Dependencia principal:</td>
					<td style="text-align: left;">
						<input type="checkbox" name="principal" id="check-depend">
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
				<input type="hidden" name="dependencia" id="validar" value="X">
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