<!-- 
	Fecha: 19-08-2020 
	Versión: 2.0
	Autor: DMQ-Qualsys 
	Cambio de íconos a botones por cambio de estilos
-->
<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-c.php"); 
		include("sidebar-contraloria.php"); 
//		consulta();
 		if(isset($_GET["b"])){
 			$_POST["buscardeccont"]=$_GET["b"];
 		}
 		if(isset($_POST["buscardeccont"]))$buscar=$_POST["buscardeccont"];
 		else $buscar="";
		?>

		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">DECLARACIONES PRESENTADAS</div>
			</div>
			<div class="forms-container">
				<form id="search-ba" method="POST" action="c-declaraciones-presentadas.php">
					<div id="busqueda">
						<input type="text" name="buscardeccont" id="buscar" placeholder="Busque por Nombre, Apellidos o RFC" value="<?php echo $buscar; ?>">
						<button class="lupa">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
						<?php 
						if(isset($_POST['buscardeccont']) || isset($_GET["p"])){
						    if(isset($_POST['buscardeccont']))$busqueda = escape($_POST['buscardeccont']);
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
						            <th style='width: 25%;'>Ver Declaraciones</th>
						        </tr>
						        <?php
						            foreach ($val as $key => $registro) {
						            $rfc=$registro["rfc"];
						            $nombre=$registro["nombre"];
						            $apaterno=$registro["primer_ap"];
						            $amaterno=$registro["segundo_ap"];
						            ?>
						            <tr>
						                <td><?php echo $nombre ?></td>
						                <td><?php echo $apaterno." ".$amaterno ?></td>
						                <td><?php echo $rfc ?></td>
						                <td>
						                    <form class='usuarios' action='c-ver-declaraciones.php' method='POST'>
						                    <input type='hidden' name='rfc-datos' value='<?php echo $rfc ?>'>
						                    <input type='hidden' name='nombre' value='<?php echo $nombre ?>'>
						                    <input type='hidden' name='apaterno' value='<?php echo $apaterno ?>'>
						                    <input type='hidden' name='amaterno' value='<?php echo $amaterno ?>'>
						                    <!-- 19-08-2020 DMQ-Qualsys Cambio de ícono de botón manteniendo funcionalidad -->
						                    <button type='submit' class="boton-oculto"><img src="<?php echo HTTP_PATH ?>/images/ver.png" style="height: 20px;"></button>
											<!--Fin de actualización-->
						                    </form>
						                </td>
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
						        	<a href="c-declaraciones-presentadas.php?p=<?php echo $_GET['p']-1 ?>&b=<?php echo $busqueda ?>"><button class="seccion-anterior">Anterior</button></a>
						        	<?php 
						        }
					        	for($i=1;$i<=$paginas;$i++){
					        		if((!isset($_GET["p"]) && $i==1) || (isset($_GET["p"]) && $_GET["p"]==$i))
					        			$extra="pagina-seleccionada";
					        		else
					        			$extra="paginas-navegacion";
						        	if((isset($_GET["p"]) && (abs($i - $_GET["p"])<=3 || $i==1 || $i==$paginas)) || (!isset($_GET["p"]) && (($i<=4) || ($i==$paginas)))){
						        	?>
						        	<a href="c-declaraciones-presentadas.php?p=<?php echo $i ?>&b=<?php echo $busqueda ?>" style="text-decoration: none;">
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
						        	<a href="c-declaraciones-presentadas.php?p=<?php if(isset($_GET["p"])){echo $_GET['p']+1; }else echo 2;?>&b=<?php echo $busqueda ?>"><button class="seccion-siguiente">Siguiente</button></a>
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
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>