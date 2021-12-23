<?php include("../include/header.php");
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-c.php"); 
		include("sidebar-contraloria.php"); 

		if(isset($_POST["ejercicio"])){$ejercicio=$_POST["ejercicio"];}
		else if(isset($_GET["ejercicio"])){$ejercicio=$_GET["ejercicio"];}
		else $ejercicio="";
		if(isset($_POST["declaracion"])){$dec=$_POST["declaracion"];}
		else if(isset($_GET["declaracion"])){$dec=$_GET["declaracion"];}
		else $dec="";
		if(isset($_POST["area_ads"])){$area_ads=$_POST["area_ads"];}
		else if(isset($_GET["area_ads"])){$area_ads=$_GET["area_ads"];}
		else $area_ads="";
 		if(isset($_GET["b"])){
 			$_POST["buscarusercont"]=$_GET["b"];
 		}
 		if(isset($_POST["buscarusercont"]))$buscar=$_POST["buscarusercont"];
 		else $buscar="";

		?>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#declaracion option[value="'+'<?php echo $dec ?>'+'"]').attr('selected', 'selected');
	    		$('#areas option[value="'+'<?php echo $area_ads ?>'+'"]').attr('selected', 'selected');
	    	});
	    </script>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">ESTATUS DE DECLARACIONES</div>
			</div>
			<div class="forms-container">
				<form id="search-ba" method="POST" action="c-estatus-declaraciones.php">
					<center>
					<div class="form-div-3">
						Ejercicio: 
						<select name="ejercicio" id="ejercicio">
							<?php lista_ejercicios($ejercicio); ?>
						</select>
					</div>
					<div class="form-div-3">
						Área de adscripción:
						<select name="area_ads" id="areas">
						<?php lista_areas(); ?>
						</select>
					</div>
					<div class="form-div-3">
						Declaración: 
						<select name="declaracion" id="declaracion">
							<option value="">::</option>
							<option value="P">Patrimonial</option>
							<option value="I">Intereses</option>
						</select>
					</div>
					</center>
					<div id="busqueda" style="margin-top:20px;">
						<input type="text" name="buscarusercont" id="buscar" placeholder="Busque por Nombre, Apellidos o RFC" value="<?php echo $buscar; ?>">
						<button class="lupa">
							<img src="<?php echo HTTP_PATH ?>/images/lupa.png">
						</button>
					</div>
				</form>
			</div>
			<div class="forms-container">
				<div class="results">
					<?php 
					if(isset($_POST['buscarusercont']) || isset($_GET["p"])){
					    if(isset($_POST['buscarusercont']))$busqueda = escape($_POST['buscarusercont']);
					    else $busqueda = '';
					    $arreglo_buscar = explode(' ',$busqueda);
					    $query_for="1=1";
					    for($i=0;$i<count($arreglo_buscar);$i++){
					    	$query_for.=" AND (a.rfc LIKE '%".$arreglo_buscar[$i]."%' or a.nombre LIKE '%".$arreglo_buscar[$i]."%' or a.primer_ap LIKE '%".$arreglo_buscar[$i]."%'or a.segundo_ap LIKE '%".$arreglo_buscar[$i]."%') ";
					    }

					    //$dec = htmlspecialchars($_POST['declaracion']); 
					    if($dec=="I")$declaracion="INTERESES";
					    if($dec=="P")$declaracion="PATRIMONIAL";
					    //$area_ads = htmlspecialchars($_POST['area_ads']); 
					    if($ejercicio==""){
					        $query_ejercicio="";
					    }
					    else{
					        $query_ejercicio=" AND ejercicio=$ejercicio";
					    }
					    if($area_ads==""){
					        $query_area="";
					    }
					    else{
					        $query_area=" AND c.area_adscripcion='$area_ads' ";
					    }

						/* 21-08-2020 DMQ-Qualsys Cambio de consulta para no mostrar usuarios que no declaran. */
						//$query = "SELECT a.nombre,a.primer_ap,a.segundo_ap,a.rfc,c.descr FROM qsy_rh_empleados a,qsy_rh_empleos b,qsy_areas_adscripcion c,qsy_puestos d WHERE $query_for AND a.rfc=b.rfc and b.area_adscripcion=c.area_adscripcion AND b.id_puesto=d.id AND d.declaracion!='N' $query_area ORDER BY rfc";

					    $query = "SELECT a.nombre,a.primer_ap,a.segundo_ap,a.rfc,b.id_puesto,c.descr FROM qsy_rh_empleados a,qsy_rh_empleos b,qsy_areas_adscripcion c WHERE $query_for AND a.rfc=b.rfc and b.area_adscripcion=c.area_adscripcion $query_area ORDER BY rfc";

					    $result=pg_query($conn,$query);
					    $total=pg_num_rows($result);
					    $elementos=10;
					    $paginas=ceil($total/$elementos);
					    $posicion=isset($_GET['p']) ? ($_GET['p']-1)*$elementos : 0;

						//$query = "SELECT a.nombre,a.primer_ap,a.segundo_ap,a.rfc,c.descr FROM qsy_rh_empleados a,qsy_rh_empleos b,qsy_areas_adscripcion c,qsy_puestos d WHERE $query_for AND a.rfc=b.rfc and b.area_adscripcion=c.area_adscripcion AND b.id_puesto=d.id AND d.declaracion!='N' $query_area ORDER BY rfc LIMIT $elementos OFFSET $posicion";
					    $query = "SELECT a.nombre,a.primer_ap,a.segundo_ap,a.rfc,b.id_puesto,c.descr FROM qsy_rh_empleados a,qsy_rh_empleos b,qsy_areas_adscripcion c WHERE $query_for AND a.rfc=b.rfc and b.area_adscripcion=c.area_adscripcion $query_area ORDER BY rfc LIMIT $elementos OFFSET $posicion";

						/* Fin de actualización */

					    $result=pg_query($conn,$query);
					    $val=pg_fetch_all($result);
					    if($val){
					   	?>
						<table cellspacing="0" cellpadding="0" class="search-table">
					        <tr>
					            <th style='width: 25%;'>Nombre</th>
					            <th style='width: 13%;'>RFC</th>
					            <th style='width: 21%;'>Área de Adscripción</th>
					            <th style='width: 11%;'>Declaración</th>
					            <th style='width: 10%;text-align:center;'>Inicial</th>
					            <th style='width: 10%;text-align:center;'>Modificación</th>
					            <th style='width: 10%;text-align:center;'>Conclusión</th>
					        </tr>
					        <?php
					        foreach ($val as $key => $registro){
					            $rfc=$registro["rfc"];
					            $nombre=$registro["nombre"];
					            $apaterno=$registro["primer_ap"];
					            $amaterno=$registro["segundo_ap"];
					            $area_ads=$registro["descr"];
					            $puesto=$registro["id_puesto"];
					            $estatusI=HTTP_PATH."/images/ico-blanco.png";
					            $estatusM=HTTP_PATH."/images/ico-blanco.png";
					            $estatusC=HTTP_PATH."/images/ico-blanco.png";

					            $sql="SELECT fecha_contrata,fecha_baja FROM qsy_rh_empleados where rfc='$rfc'";
					            $result=pg_query($conn,$sql);
					            $val=pg_fetch_assoc($result);
					            if($val){
					                $fecha_contrata=$val["fecha_contrata"];
					                $fecha_baja=$val["fecha_baja"];
					                $fecha_actual=date("Y-m-d");
					                if($fecha_contrata!=""){
					                    $fecha_limite_c = strtotime ('+60 day',strtotime($fecha_contrata)) ;
					                    $fecha_limite_c = date ( 'Y-m-d' , $fecha_limite_c );
					                    $anio_c=date("Y",strtotime($fecha_contrata));
					                }
					                else{
					                    $fecha_limite_c ="";
					                    $anio_c=0;
					                }
					                if($fecha_baja!=""){
					                    $fecha_limite_b = strtotime ('+60 day',strtotime($fecha_baja)) ;
					                    $fecha_limite_b = date ( 'Y-m-d' , $fecha_limite_b );
					                    $anio_b=date("Y",strtotime($fecha_baja));
					                    $fecha_baja2="'".$fecha_baja."'";
					                }
					                else{
					                    $fecha_limite_b ="";
					                    $anio_b=0;
					                    $fecha_baja2='null';
					                }
					                $mes_actual=date("m");
					                $anio_actual=date("Y");
					                /* 01-09-2020 DMQ-Qualsys Consultas para enviar en blanco el estatus según la fecha efectiva del puesto */
					                if($ejercicio>=$anio_c){
										$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$fecha_contrata' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1)) AND (declaracion='C' OR declaracion='P')";
										$result=pg_query($conn,$sql);
										$valor=pg_fetch_assoc($result);
										if($valor){
						                    if($fecha_contrata <= $fecha_actual && $fecha_actual <= $fecha_limite_c){$estatusI=HTTP_PATH."/images/ico-aviso.png";}
						                    else if($fecha_actual > $fecha_limite_c){
						                    	$estatusI=HTTP_PATH."/images/ico-error.png";
						                	    if($ejercicio>$anio_c){$estatusI=HTTP_PATH."/images/ico-blanco.png";
						                		}
						                	}
										}
										$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= '$ejercicio-05-31' AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1)) AND (declaracion='C' OR declaracion='P')";
										$result=pg_query($conn,$sql);
										$valor=pg_fetch_assoc($result);
										if($valor){
						                    if($mes_actual == "05" && $anio_actual>$anio_c && $ejercicio==$anio_actual){$estatusM=HTTP_PATH."/images/ico-aviso.png";}
						                    else if(($mes_actual > "05" && $anio_actual>$anio_c) || ($mes_actual <= "05" && $anio_actual>($anio_c+1))){
						                    	$estatusM=HTTP_PATH."/images/ico-error.png";
						                    	if($ejercicio==$anio_c || ("$ejercicio-05-01" > $fecha_baja && $fecha_baja!="")){$estatusM=HTTP_PATH."/images/ico-blanco.png";
						                		}
						                    }
						                }
					                }
					                if($ejercicio>=$anio_b){
										$sql="SELECT declaracion FROM qsy_puestos a,qsy_rh_empleos b WHERE b.rfc='$rfc' AND b.id_puesto=a.id AND a.fecha_efec=(SELECT max(fecha_efec) FROM qsy_puestos WHERE fecha_efec<= $fecha_baja2 AND id=(SELECT id_puesto FROM qsy_rh_empleos WHERE rfc='$rfc' limit 1)) AND (declaracion='C' OR declaracion='P')";
										$result=pg_query($conn,$sql);
										$valor=pg_fetch_assoc($result);
										if($valor){
						                    if($fecha_baja <= $fecha_actual && $fecha_actual <= $fecha_limite_b){$estatusC=HTTP_PATH."/images/ico-aviso.png";}
						                    else if($fecha_actual > $fecha_limite_b && $fecha_limite_b!=""){
						                    	if($anio_b<$ejercicio){}
						                    	else $estatusC=HTTP_PATH."/images/ico-error.png";
						                    }
						                }
					                }
					            }

					            /* Fin de actualización. */
					            $estatusPI=$estatusI;
					            $estatusPM=$estatusM;
					            $estatusPC=$estatusC;
					            $estatusII=$estatusI;
					            $estatusIM=$estatusM;
					            $estatusIC=$estatusC;


					            $sql="SELECT tipo_decl,estatus_decl,declaracion FROM qsy_declaraciones where rfc='$rfc' and ejercicio='$ejercicio'";
					            $result=pg_query($conn,$sql);
					            $val=pg_fetch_all($result);
					            if($val){
					                foreach ($val as $key => $valor) {
					                    if($dec=="P" || $dec==""){
					                        if($valor["tipo_decl"]=="I" && $valor["declaracion"]=="P"){$estatusPI=getstatus($valor["estatus_decl"],$estatusI,"P");}
					                        if($valor["tipo_decl"]=="M" && $valor["declaracion"]=="P"){$estatusPM=getstatus($valor["estatus_decl"],$estatusM,"P");}
					                        if($valor["tipo_decl"]=="C" && $valor["declaracion"]=="P"){$estatusPC=getstatus($valor["estatus_decl"],$estatusC,"P");}
					                    }
					                    if($dec=="I" || $dec==""){
					                        if($valor["tipo_decl"]=="I" && $valor["declaracion"]=="I"){$estatusII=getstatus($valor["estatus_decl"],$estatusII,"I");}
					                        if($valor["tipo_decl"]=="M" && $valor["declaracion"]=="I"){$estatusIM=getstatus($valor["estatus_decl"],$estatusM,"I");}
					                        if($valor["tipo_decl"]=="C" && $valor["declaracion"]=="I"){$estatusIC=getstatus($valor["estatus_decl"],$estatusC,"I");}
					                    }
					                }
					            }
					            if($dec=="P" || $dec==""){
					            	?>
					                <tr>
					                    <td><?php echo $apaterno." ".$amaterno." ".$nombre ?></td>
					                    <td><?php echo $rfc ?></td>
					                    <td><?php echo $area_ads ?></td>
					                    <td>Patrimonial</td>
					                    <td style='text-align: center;'>
					                       <img class='icons-dec' src='<?php echo $estatusPI ?>'>
					                    </td>
					                    <td style='text-align: center;'>
					                       <img class='icons-dec' src='<?php echo $estatusPM ?>'>
					                    </td>
					                    <td style='text-align: center;'>
					                       <img class='icons-dec' src='<?php echo $estatusPC ?>'>
					                    </td>
					                </tr>
					                <?php
					            }
					            if($dec=="I" || $dec==""){
					            	?>
					                <tr>
					                    <td><?php echo $apaterno." ".$amaterno." ".$nombre ?></td>
					                    <td><?php echo $rfc ?></td>
					                    <td><?php echo $area_ads ?></td>
					                    <td>Intereses</td>
					                    <td style='text-align: center;'>
					                       <img class='icons-dec' src='<?php echo $estatusII ?>'>
					                    </td>
					                    <td style='text-align: center;'>
					                       <img class='icons-dec' src='<?php echo $estatusIM ?>'>
					                    </td>
					                    <td style='text-align: center;'>
					                       <img class='icons-dec' src='<?php echo $estatusIC ?>'>
					                    </td>
					                </tr>
					                <?php
					            }
					        }
					        ?>
						</table>
				        <div style="text-align: center;">
				        	<?php 
				        	/* 21-08-2020 DMQ-Qualsys Cambio de estilo en botones */
					       if(isset($_GET["p"]) && $_GET["p"]!=1){
					        	 ?>
					        	<a href="c-estatus-declaraciones.php?p=<?php echo $_GET['p']-1 ?>&b=<?php echo $busqueda ?>&ejercicio=<?php echo $ejercicio ?>&declaracion=<?php echo $dec ?>&area_ads=<?php echo $area_ads ?>"><button class="seccion-anterior">Anterior</button></a>
					        	<?php 
					        }
				        	for($i=1;$i<=$paginas;$i++){
				        		if((!isset($_GET["p"]) && $i==1) || (isset($_GET["p"]) && $_GET["p"]==$i))
				        			$extra="pagina-seleccionada";
				        		else
				        			$extra="paginas-navegacion";
					        	if((isset($_GET["p"]) && (abs($i - $_GET["p"])<=3 || $i==1 || $i==$paginas)) || (!isset($_GET["p"]) && (($i<=4) || ($i==$paginas)))){
					        	?>
								<a href="c-estatus-declaraciones.php?p=<?php echo $i ?>&b=<?php echo $busqueda ?>&ejercicio=<?php echo $ejercicio ?>&declaracion=<?php echo $dec ?>&area_ads=<?php echo $area_ads ?>" style="text-decoration: none;">
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
					        	<a href="c-estatus-declaraciones.php?p=<?php if(isset($_GET["p"])){echo $_GET['p']+1; }else echo 2;?>&b=<?php echo $busqueda ?>&ejercicio=<?php echo $ejercicio ?>&declaracion=<?php echo $dec ?>&area_ads=<?php echo $area_ads ?>"><button class="seccion-siguiente">Siguiente</button></a>
					        	<?php 
					        }
				        	/* Fin de actualización */
					        ?>
				        </div>
						<?php
					    }
					    else {
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