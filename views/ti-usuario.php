<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-ti.php"); 
		include("sidebar-ti.php"); 
 		if(isset($_GET["b"])){
 			$_POST["buscaruserti"]=$_GET["b"];
 		}
 		if(isset($_POST["buscaruserti"]))$buscar=$_POST["buscaruserti"];
 		else $buscar="";
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">ADMINISTRACIÓN DE EMPLEADOS</div>
			</div>
			<div class="forms-container">
				<form id="search-ba" method="POST" action="ti-usuario.php">
					<div id="busqueda">
						<input type="text" name="buscaruserti" id="buscar" placeholder="Busque por Nombre, Apellido o RFC" value="<?php echo $buscar; ?>">
						<button class="lupa" title="Buscar">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
						<div style="text-align: right;display: inline-block;">
							<a href="ti-nuevo-usuario.php"><button class="boton-oculto" style="margin:1%;height: auto;width: auto;" type="button"><img src="<?php echo HTTP_PATH ?>/images/empleado_nuevo.png" style="height: 35px; width: auto;margin:0 10px;" title="Agregar empleado"></button></a>
						</div>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
						<?php 
						if(isset($_POST['buscaruserti']) || isset($_GET["p"])){
						    if(isset($_POST['buscaruserti']))$busqueda = escape($_POST['buscaruserti']);
						    else $busqueda = '';
						    $arreglo_buscar = explode(' ',$busqueda);
						    $query_for="1=1";
						    for($i=0;$i<count($arreglo_buscar);$i++){
						    	$query_for.=" AND (rfc LIKE '%".$arreglo_buscar[$i]."%' or nombre LIKE '%".$arreglo_buscar[$i]."%' or primer_ap LIKE '%".$arreglo_buscar[$i]."%'or segundo_ap LIKE '%".$arreglo_buscar[$i]."%') ";
						    }

						    $query = "SELECT nombre,primer_ap,segundo_ap,rfc FROM qsy_rh_empleados WHERE $query_for ORDER BY rfc";

						    $result=pg_query($conn,$query);
						    $total=pg_num_rows($result);
						    $elementos=10;
						    $paginas=ceil($total/$elementos);
						    $posicion=isset($_GET['p']) ? ($_GET['p']-1)*$elementos : 0;

						    $query = "SELECT nombre,primer_ap,segundo_ap,rfc FROM qsy_rh_empleados WHERE $query_for ORDER BY rfc LIMIT $elementos OFFSET $posicion";

						    $result=pg_query($conn,$query);
						    $val=pg_fetch_all($result);
						    if($val){
						    ?>
							<table cellspacing="0" cellpadding="0" class="search-table">
						        <tr>
						            <th style='width: 25%;'>Nombre</th>
						            <th style='width: 25%;'>Apellidos</th>
						            <th style='width: 25%;'>RFC</th>
						            <th style='width: 25%;padding-left: 1%;'>Contraseña</th>
						        </tr>
						            <?php
						            foreach ($val as $key => $registro) {
						            $rfc=$registro["rfc"];
						            $nombre=$registro["nombre"];
						            $apaterno=$registro["primer_ap"];
						            $amaterno=$registro["segundo_ap"];
						            ?>
						            <tr>
						                <form id='usuario<?php echo $rfc ?>' class='usuarios' method='POST'>  
						                <td><?php echo $nombre ?></td>
						                <td><?php echo $apaterno." ".$amaterno ?></td>
						                <td><?php echo $rfc ?></td>
						                <script>
						                    var rfc<?php echo $key ?>='<?php echo $rfc ?>';
						                </script>
					                    <!-- 19-08-2020 DMQ-Qualsys Cambio de ícono de botón manteniendo funcionalidad -->
						                <td>
						                	<button type='button' onclick='pass_update(rfc<?php echo $key ?>);' class='guardar-pass boton-oculto' style='margin:2% 0;'><img src="<?php echo HTTP_PATH ?>/images/editar.png" style="height: 20px;"></button></td>
						                </form>
										<!--Fin de actualización-->
						            </tr>
						            <?php
						        }
						        ?>
							</table>
					        <div style="text-align: center;">
					        	<?php 
					        	/* 21-08-2020 DMQ-Qualsys Cambio de estilo en botones */
						       if(isset($_GET["p"]) && $_GET["p"]!=1){
						        	 ?>
						        	<a href="ti-usuario.php?p=<?php echo $_GET['p']-1 ?>&b=<?php echo $busqueda ?>"><button class="seccion-anterior">Anterior</button></a>
						        	<?php 
						        }
					        	for($i=1;$i<=$paginas;$i++){
					        		if((!isset($_GET["p"]) && $i==1) || (isset($_GET["p"]) && $_GET["p"]==$i))
					        			$extra="pagina-seleccionada";
					        		else
					        			$extra="paginas-navegacion";
						        	if((isset($_GET["p"]) && (abs($i - $_GET["p"])<=3 || $i==1 || $i==$paginas)) || (!isset($_GET["p"]) && (($i<=4) || ($i==$paginas)))){
						        	?>
						        	<a href="ti-usuario.php?p=<?php echo $i ?>&b=<?php echo $busqueda ?>" style="text-decoration: none;">
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
						        	<a href="ti-usuario.php?p=<?php if(isset($_GET["p"])){echo $_GET['p']+1; }else echo 2;?>&b=<?php echo $busqueda ?>"><button class="seccion-siguiente">Siguiente</button></a>
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
			<button onclick='hidecambio();'>Aceptar</button>
		</div>
	</div>
	<div id="cambio-password">
		<h2>Cambio de contraseña</h2>
		<form id="pass-change">
			<div class="pass-campo"><span>Nueva contraseña:</span></div>
			<div class="pass-campo"><input type="password" name="new-pass" class="reset-pass"></div>
			<div class="pass-campo"><span>Confirmar contraseña:</span></div>
			<div class="pass-campo"><input type="password" name="new-pass2" class="reset-pass"></div>				
			<input type="hidden" name="rfc" id="rfc" >
			<input type="hidden" name="rfc_modif" id="rfc_modif" value="<?php echo $_SESSION['rfc'] ?>">
			<input type="hidden" name="passti" id="pass" value="../models/cambio-password.php">
			<button type="button" id="cancelar-cambiopass" style="background-color: red;">Cancelar</button>
			<button type="button" id="confirmar-cambiopass" class="guardar">Guardar</button>
		</form>
		<div id="pass-aviso" style="display: none;"></div>
	</div>
	<div id="pass-exito" style="display: none;">
		<span>La contraseña se ha cambiado satisfactoriamente.</span>
		<button type="button" id="pass-aceptar">Aceptar</button>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>