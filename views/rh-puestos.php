<?php include("../include/header.php");
?>
<script type="text/javascript">
	window.onload = function (){menuopc("sub3-9p","menu3-1n2","menu1-2n");}
</script>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-rh.php"); 
		include("sidebar-rh.php"); 
 		if(isset($_GET["b"])){
 			$_POST["buscarpuesto"]=$_GET["b"];
 		}
 		if(isset($_POST["buscarpuesto"])){
 			$buscar=$_POST["buscarpuesto"];
 		}
 		else $buscar="";
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">PUESTOS</div>
			</div>
			<div class="forms-container">
				<form id="search-ba" method="POST" action="rh-puestos.php">
					<div id="busqueda">
						<input type="text" name="buscarpuesto" id="buscar" placeholder="Busque por Nombre o Clave de Puesto" value="<?php echo $buscar; ?>">
						<button class="lupa" id="lupa" title="Buscar">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
						<div style="display: inline-block;text-align: right;">
							<button id="nuevo_elemento" class="boton-oculto" style="margin:1% 2%;height: auto;" type="button"><img src="<?php echo HTTP_PATH ?>/images/puesto_nuevo.png" style="height: 40px; width: auto;" title="Nuevo puesto"></button>
						</div>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
					<?php 
					if(isset($_POST['buscarpuesto']) || isset($_GET["p"])){
					    if(isset($_POST['buscarpuesto']))$busqueda = escape($_POST['buscarpuesto']);
					    else $busqueda = '';

					    $query = "SELECT id,puesto,descr,descr_corta,nivel,declaracion,fecha_efec,estatus FROM qsy_puestos WHERE (descr LIKE '%".$busqueda."%' OR puesto LIKE '%".$busqueda."%') order by descr,fecha_efec desc,descr_corta";
					    //print_r($query);die;
					    $result=pg_query($conn,$query);
					    $total=pg_num_rows($result);
					    $elementos=10;
					    $paginas=ceil($total/$elementos);
					    $posicion=isset($_GET['p']) ? ($_GET['p']-1)*$elementos : 0;
					    $query = "SELECT id,puesto,descr,descr_corta,nivel,declaracion,fecha_efec,estatus FROM qsy_puestos WHERE (descr LIKE '%".$busqueda."%' OR puesto LIKE '%".$busqueda."%') order by descr,fecha_efec desc,descr_corta LIMIT $elementos OFFSET $posicion";

					    $result=pg_query($conn,$query);
					    $val=pg_fetch_all($result);
					    if($val){
					    	?>
							<table cellspacing="0" cellpadding="0" class="search-table">
					        <tr>
					            <th style='width: 15%;'>Clave</th>
					            <th style='width: 45%;'>Puesto</th>
					            <th style='width: 20%;'>Fecha efectiva</th>
					            <th style='width: 10%;'>Estatus</th>
					            <th style='width: 10%;padding-left: 3%;'>Editar</th>
							</tr>
				            <?php 
				            foreach ($val as $key => $registro) {
				            $idpuesto=$registro["id"];
				            $id=$registro["puesto"];
				            $puesto=$registro["descr"];
				            $puesto_corto=$registro["descr_corta"];
				            $nivel=$registro["nivel"];
				            $declaracion=$registro["declaracion"];
				            $fecha_efec=$registro["fecha_efec"];
				            $estatus=$registro["estatus"];
				            ?>
		                        <form id="puesto-form<?php echo $key ?>">
		                        <tr>
		                        	<td>
		                        		<span><?php echo $id ?></span>
		                        	</td>
		                            <td><span id='nombre-input<?php echo $key ?>'><?php echo $puesto ?></span>
		                                <input type='text' name='nombre_puesto' value='<?php echo $puesto ?>' id='nombre<?php echo $key ?>' style='display: none;'>
	                                    <input type='hidden' name='idpuesto' id='id<?php echo $key ?>' value="<?php echo $idpuesto ?>">
	                                    <input type='hidden' name='fecha_efec' id='fecha<?php echo $key ?>' value="<?php echo $fecha_efec ?>">
	                                    <input type='hidden' name='clave' id='clave<?php echo $key ?>' value="<?php echo $id ?>">
	                                    <input type='hidden' name='desc_corta' id='desc_corta<?php echo $key ?>' value="<?php echo $puesto_corto ?>">
	                                    <input type='hidden' name='nivel' id='nivel<?php echo $key ?>' value="<?php echo $nivel ?>">
	                                    <input type='hidden' name='declaracion' id='declaracion<?php echo $key ?>' value="<?php echo $declaracion ?>">
	                                    <input type='hidden' name="estatus" id='estatus<?php echo $key ?>' value="<?php echo $estatus ?>">
		                            </td>
		                            <td>
		                            	<span>
		                            		<?php echo $fecha_efec; ?>
		                            	</span>
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
					                    <button class="boton-oculto boton-modificar puesto-modificar" id="<?php echo $key ?>" type="button"><img src="<?php echo HTTP_PATH ?>/images/editar.png" style="height: 20px;"></button>
		                            </td>
		                        </tr>
		                        </form>
			                    <?php 
					        }
						    ?>
							</table>
					        <div style="text-align: center;">
					        	<?php 
					        	/* 21-08-2020 DMQ-Qualsys Cambio de estilo en botones */
						       if(isset($_GET["p"]) && $_GET["p"]!=1){
						        	 ?>
						        	<a href="rh-puestos.php?p=<?php echo $_GET['p']-1 ?>&b=<?php echo $busqueda ?>"><button class="seccion-anterior">Anterior</button></a>
						        	<?php 
						        }
					        	for($i=1;$i<=$paginas;$i++){
					        		if((!isset($_GET["p"]) && $i==1) || (isset($_GET["p"]) && $_GET["p"]==$i))
					        			$extra="pagina-seleccionada";
					        		else
					        			$extra="paginas-navegacion";
						        	if((isset($_GET["p"]) && (abs($i - $_GET["p"])<=3 || $i==1 || $i==$paginas)) || (!isset($_GET["p"]) && (($i<=4) || ($i==$paginas)))){
						        	?>
						        	<a href="rh-puestos.php?p=<?php echo $i ?>&b=<?php echo $busqueda ?>" style="text-decoration: none;">
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
						        	<a href="rh-puestos.php?p=<?php if(isset($_GET["p"])){echo $_GET['p']+1; }else echo 2;?>&b=<?php echo $busqueda ?>"><button class="seccion-siguiente">Siguiente</button></a>
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
			<div style="text-align: center;"><span>Puestos</span></div>
			<form id="form-nuevo-elemento">
			<table>
				<tr>
					<td class="label-table" style="width: 30%;">ID:</td>
					<td style="width: 70%;"><span id="id_puesto"></span>
					</td>
				</tr>
				<tr>
					<td class="label-table" style="width: 30%;">Clave:</td>
					<td style="width: 70%;">
						<input type="text" placeholder="Clave" name="clave" id="clave" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" maxlength="10" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Puesto:</td>
					<td>
						<input type="text" placeholder="Puesto" name="descripcion" id="descripcion" onkeypress="valida(event,'string')" onpaste="valida(event,'string')" maxlength="100" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Descripción corta:</td>
					<td>
						<input type="text" placeholder="Descripción" name="descripcion_corta" id="descripcion_corta" onkeypress="valida(event,'string')" onpaste="valida(event,'string')" maxlength="15" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Fecha efectiva:</td>
					<td>
						<input type="date" name="fecha_efectiva" id="fecha_efectiva" style="background-color: #F2F2F2; font-family:contenidos;" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Nivel:</td>
					<td>
						<input type="text" placeholder="Nivel" name="nivel" id="nivel" onkeypress="valida(event,'float')" onpaste="valida(event,'float')" maxlength="7" max="999.999" required>
					</td>
				</tr>
				<tr>
					<td class="label-table">Tipo de Declaración:</td>
					<td style="text-align: left;">
						<input type="radio" name="declara" value="C" checked>Completa
						<input type="radio" name="declara" value="P">Parcial
						<input type="radio" name="declara" value="N">No Aplica
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
				<input type="hidden" name="puesto" id="validar" value="X">
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