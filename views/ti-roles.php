<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-ti.php"); 
		include("sidebar-ti.php"); 
 		if(isset($_GET["b"])){
 			$_POST["buscar"]=$_GET["b"];
 		}
 		if(isset($_POST["buscar"]))$buscar=$_POST["buscar"];
 		else $buscar="";
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">ASIGNACIÓN DE ROLES</div>
			</div>
			<div class="forms-container">
				<form id="search-ba" method="POST" action="ti-roles.php">
					<div id="busqueda">
						<input type="text" name="buscar" id="buscar" placeholder="Busque por Nombre, Apellido o RFC" value="<?php echo $buscar; ?>">
						<button class="lupa">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
						<?php 
						if(isset($_POST['buscar']) || isset($_GET["p"])){
						    if(isset($_POST['buscar']))$busqueda = escape($_POST['buscar']);
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
						            <th style='width: 18%;'>Nombre</th>
						            <th style='width: 18%;'>Apellidos</th>
						            <th style='width: 16%;'>RFC</th>
						            <th style='width: 8%;text-align: center;'>Empleado</th>
						            <th style='width: 8%;text-align: center;'>RH</th>
						            <th style='width: 8%;text-align: center;'>Control</th>
						            <th style='width: 8%;text-align: center;'>TI</th>
						            <th style='width: 16%;text-align: center;'></th>
						        </tr>
						        <?php
						        $i=0;
						        foreach ($val as $key => $registro) {
						            $rfc=$registro["rfc"];
						            $nombre=$registro["nombre"];
						            $apaterno=$registro["primer_ap"];
						            $amaterno=$registro["segundo_ap"];
						            //$query = "SELECT rol,estatus FROM qsy_roles WHERE (rfc LIKE '%".$rfc."%')";
						            $query = "SELECT r.rol,r.estatus,e.rfc FROM qsy_roles r RIGHT JOIN qsy_rh_empleados e ON r.rfc=e.rfc WHERE (e.rfc LIKE '%".$rfc."%')";
						            $result=pg_query($conn,$query);
						            $val=pg_fetch_all($result);

						            if($val){
						                $checked=array("","","","");
						                foreach ($val as $key => $registro) {
						                    $rol=$registro["rol"];
						                    $estatus=$registro["estatus"];
						                    if($rol=="E" && $estatus=="A"){$checked[0]="checked";}
						                    if($rol=="R" && $estatus=="A"){$checked[1]="checked";}
						                    if($rol=="C" && $estatus=="A"){$checked[2]="checked";}
						                    if($rol=="T" && $estatus=="A"){$checked[3]="checked";}
						                }     
						    			?>
						                <tr>
						                    <form id='roles<?php echo $rfc ?>' class='roles' action='../models/checkbox-roles.php' method='POST'>  
							                <td><?php echo $nombre ?></td>
							                <td><?php echo $apaterno." ".$amaterno ?></td>
							                <td><?php echo $rfc ?></td>
						                    <td><input style='width:100%;margin:auto;' id='checkbox-form<?php echo $rfc ?>0' type='checkbox' name='empleado' <?php echo $checked[0] ?> ></td>
						                    <td><input style='width:100%;margin:auto;' id='checkbox-form<?php echo $rfc ?>1' type='checkbox' name='rh' <?php echo $checked[1] ?> ></td>
						                    <td><input style='width:100%;margin:auto;' id='checkbox-form<?php echo $rfc ?>2' type='checkbox' name='contralor' <?php echo $checked[2] ?>></td>
						                    <td><input style='width:100%;margin:auto;' id='checkbox-form<?php echo $rfc ?>3' type='checkbox' name='ti' <?php echo $checked[3] ?>></td>
						                    <script>
						                        var rfc<?php echo $i ?>='<?php echo $rfc ?>';
						                        var e<?php echo $i ?>='checkbox-form<?php echo $rfc ?>0';
						                        var r<?php echo $i ?>='checkbox-form<?php echo $rfc ?>1';
						                        var c<?php echo $i ?>='checkbox-form<?php echo $rfc ?>2';
						                        var t<?php echo $i ?>='checkbox-form<?php echo $rfc ?>3';
						                        console.log(<?php echo json_encode($val) ?>);
						                    </script>
						                    <td>
						                    	<button type='button' onclick='rol_update(rfc<?php echo $i ?>,e<?php echo $i ?>,r<?php echo $i ?>,c<?php echo $i ?>,t<?php echo $i ?>);' class='guardar-roles guardar' id='a1' name='a1' style='margin:2% 0;'>Guardar</button>
						                    </td>
						                    </form>
						                </tr>
						                <?php
						            }
						            $i++;
						        }
						        ?>
								</table>
						        <div style="text-align: center;">
						        	<?php 
						        	/* 21-08-2020 DMQ-Qualsys Cambio de estilo en botones */
							       if(isset($_GET["p"]) && $_GET["p"]!=1){
							        	 ?>
							        	<a href="ti-roles.php?p=<?php echo $_GET['p']-1 ?>&b=<?php echo $busqueda ?>"><button class="seccion-anterior">Anterior</button></a>
							        	<?php 
							        }
						        	for($i=1;$i<=$paginas;$i++){
						        		if((!isset($_GET["p"]) && $i==1) || (isset($_GET["p"]) && $_GET["p"]==$i))
						        			$extra="pagina-seleccionada";
						        		else
						        			$extra="paginas-navegacion";
							        	if((isset($_GET["p"]) && (abs($i - $_GET["p"])<=3 || $i==1 || $i==$paginas)) || (!isset($_GET["p"]) && (($i<=4) || ($i==$paginas)))){
							        	?>
							        	<a href="ti-roles.php?p=<?php echo $i ?>&b=<?php echo $busqueda ?>" style="text-decoration: none;">
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
							        	<a href="ti-roles.php?p=<?php if(isset($_GET["p"])){echo $_GET['p']+1; }else echo 2;?>&b=<?php echo $busqueda ?>"><button class="seccion-siguiente">Siguiente</button></a>
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

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>