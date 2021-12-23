<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("15","menu3-1","menu1-2");}
</script>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php 
		include("../controllers/permiso-e.php"); 
		$tipo_dec=htmlspecialchars($_POST["declaracion"] ?? '');
		$ejercicio=htmlspecialchars($_POST["ejercicio"] ?? '');
		$tipo_declaracion="INICIAL";
		if($tipo_dec=="I"){
			$tipo_declaracion="INICIAL";
		}
		else if($tipo_dec=="M"){
			$tipo_declaracion="MODIFICACIÓN";
		}
		else if($tipo_dec=="C"){
			$tipo_declaracion="CONCLUSIÓN";
		}
		else{
			$tipo_dec="I";
		}
		setDeclaracion($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$arr=cargarform15($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform15($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		if(empty($html)){$arr["html"][0]="";}
		else{$arr["html"]=$html;}
		//print_r($arr);die;
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["declarante"][0]="";
			$arr["tipo_comodato"][0]="";
			$arr["tipo_inmueble"][0]="";
			$arr["otro_inmueble"][0]="";
			$arr["ubicacion"][0]="";	
			$arr["calle"][0]="";
			$arr["num_exterior"][0]="";
			$arr["num_interior"][0]="";
			$arr["colonia"][0]="";
			$arr["colonia_descr"][0]="";
			$arr["municipio"][0]="";
			$arr["municipio_descr"][0]="";
			$arr["estado"][0]="";
			$arr["estado_descr"][0]="";
			$arr["pais"][0]="";
			$arr["pais_descr"][0]="";
			$arr["cp"][0]="";
			$arr["tipo_vehiculo"][0]="";
			$arr["otro_vehiculo"][0]="";
			$arr["marca"][0]="";
			$arr["modelo"][0]="";
			$arr["anio"][0]=0;
			$arr["serie"][0]="";
			$arr["dueno"][0]="";
			$arr["dueno_descr"][0]="";
			$arr["nombre_dueno"][0]="";
			$arr["rfc_dueno"][0]="";
			$arr["relacion"][0]="";
			$arr["relacion_descr"][0]="";
			$arr["otra_relacion"][0]="";
			$arr["observaciones"][0]="";
			$arr["total"]=1;
			$arr["html"][0]="";
		}
		$arr2=json_encode($arr);
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">var b=<?php echo $arr2;?></script>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#tipo_comodato option[value="'+'<?php echo $arr["tipo_comodato"][0] ?>'+'"]').attr('selected', 'selected');

	    		$('#tipo_inmueble option[value="'+'<?php echo $arr["tipo_inmueble"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tipo_vehiculo option[value="'+'<?php echo $arr["tipo_vehiculo"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#pais2 option[value="'+'<?php echo $arr["pais"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#estado3 option[value="'+'<?php echo $arr["estado"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#dueno option[value="'+'<?php echo $arr["dueno"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#relacion option[value="'+'<?php echo $arr["relacion"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#estado option[value="'+'<?php echo $arr["estado"][0] ?>'+'"]').attr('selected', 'selected');
	    		lista_municipios("<?php echo $arr["municipio"][0] ?>");

			    var ubicacion=document.getElementsByName("ubicacion");
			    for (var i = ubicacion.length - 1; i >= 0; i--) {
			      if(ubicacion[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){
			        ubicacion[i].checked="checked";
			        if(ubicacion[i].value == "M"){ubicacion_m();ubicacion_m1();}
			        if(ubicacion[i].value == "E"){ubicacion_e();ubicacion_e1();}
			      }
				}

			    var ubicacion2=document.getElementsByName("ubicacion2");
			    for (var i = ubicacion2.length - 1; i >= 0; i--) {
			      if(ubicacion2[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){
			        ubicacion2[i].checked="checked";
			        if(ubicacion2[i].value == "M"){ubicacion_m1();}
			        if(ubicacion2[i].value == "E"){ubicacion_e1();}
			      }
				}

	    		$('#pais option[value="'+'<?php echo $arr["pais"][0] ?>'+'"]').attr('selected', 'selected');
				if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
				else $("#baja").prop("checked",false);

				otra_relacion2();
				if('<?php echo $arr["tipo_comodato"][0] ?>'=="V"){
				otro_vehiculo();
				}
				if('<?php echo $arr["tipo_comodato"][0] ?>'=="I"){
				otro_inmueble();
				}
				if('<?php echo $arr["movimiento"][0]; ?>'=='N'){
				    $("#ninguno").prop("checked",true);
				    $(".ningun_registro").hide();
				}
				else{
				    $("#ninguno").prop("checked",false);
				    $(".ningun_registro").show();
				}
				//otro();
		        $("#contador").html(document.getElementById("observaciones").value.length);
			})
			.ready(comodato);
		</script>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">I. DECLARACIÓN DE SITUACIÓN PATRIMONIAL</div>
				<div class="tipo_decl">EJERCICIO <?php echo $ejercicio; ?> - <?php echo $tipo_declaracion; ?></div>
			</div>
			<form id="form-content">
			<?php
			if($_POST["declaracion"]!="M"){
			?>
			<div class="subtitle">15. PRÉSTAMO O COMODATO POR TERCEROS (SITUACIÓN ACTUAL)</div>
			<?php } else{ ?>
			<div class="subtitle">14. PRÉSTAMO O COMODATO POR TERCEROS (SITUACIÓN ACTUAL)</div>
			<?php }	?>
			<div class="buttons-container">			
				<div class="form-div-4" style="text-align: center;">
						<button type="button" class="boton-nav" id="registro-mas">+</button>
						<label> / </label>
						<button type="button" class="boton-nav"  onclick="registro_menos(b)">-</button>
				</div>
				<div class="form-div-4" style="text-align: center;">
					<button type="button" class="boton-nav" name="atras" onclick="cambio_pagina_ant(b)"><img src="<?php echo HTTP_PATH ?>/images/atras.png"></button>
						<label>
						 <div id="variable" style="display: inline;">1</div>
						  de 
						  <div id="total" style="display: inline;"><?php echo $arr["total"]; ?></div>
						</label>
					<button type="button" class="boton-nav" name="adelante" onclick="cambio_pagina_sig(b)"><img src="<?php echo HTTP_PATH ?>/images/adelante.png"></button>
				</div>
				<div class="form-div-4" style="text-align: center;">
				<?php 
				if($tipo_dec=="M" || $tipo_dec=="C"){
				?>
					<input type="checkbox" name="baja" id="baja" >Baja
					<div class="space-between"></div>
				<?php } ?>
					<input type="checkbox" name="ninguno" id="ninguno" >Ninguna
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>
				<div class="form-div-3">
					<label>Tipo de bien:</label><span class="asterisk">*</span>
					<select id="tipo_comodato" name="tipo_comodato" required>
						<?php lista_valores("Tipo_Comodato"); ?>
					</select>
				</div>
				<div class="form-div-3 oculto" id="div-inmueble">
					<label>Inmueble:</label><span class="asterisk">*</span>
					<select id="tipo_inmueble" name="tipo_inmueble">
						<?php lista_valores("Tipo_Inmueble"); ?>
					</select>
				</div>
				<div class="form-div-3 oculto" id="div-vehiculo">
					<label>Vehículo:</label><span class="asterisk">*</span>
					<select id="tipo_vehiculo" name="tipo_vehiculo">
						<?php lista_valores("Tipo_Vehiculo"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3" id="otro-ambito">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" id="otro" name="otro" maxlength="50" value="<?php if($arr['otro_inmueble'][0]=='') echo $arr['otro_vehiculo'][0]; else  echo $arr['otro_inmueble'][0]; ?>">
				</div>
			</div>
			<div class="forms-container oculto" id="container-inmueble">
				<div class="subsubtitle">Ubicación del inmueble</div>
				<div class="forms-container">
					<div class="form-div-1">
						<label>Ubicación:</label><span class="asterisk">*</span>
						<input type="radio" name="ubicacion" id="dommex" value="M" checked>MÉXICO
						<input type="radio" name="ubicacion" id="domext" value="E">EXTRANJERO
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
	 					<label>País:</label><span class="asterisk">*</span>
							<select name="pais" id="pais">
							<?php paises(); ?>
							</select>
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
						<label id="cp-label" style="display: block;">Código postal<span class="asterisk ast">*</span></label>
						<input type="text" style="width:50%;display: inline-block;" placeholder="Código Postal" name="cp" id="cp" maxlength="5" value="<?php echo $arr['cp'][0]?>" >
						<img src="../images/lupa.png" id="buscar-direccion"></img>
						<span id="msj-cp" style="font-weight: bold;color: red;"></span>
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3" id="estado-div">
						<label>Entidad Federativa</label><span class="asterisk">*</span>
						<select name="estado" id="estado" value="<?php echo $arr['estado'][0]?>">
							<?php entidades(); ?>
						</select>
					</div>
					<div class="form-div-3" id="estado2-div" style="display: none;">
						<label>Estado / Provincia</label>
						<input type="text" placeholder="Estado" name="estado2" id="estado2" maxlength="50" value="<?php echo $arr['estado_descr'][0]?>">
					</div>
					<div class="form-div-3" id="municipio-div">
						<label id="municipio-label">Municipio / Alcaldía</label><span class="asterisk">*</span>
						<select name="municipio" id="municipio">
						</select>
					</div>
					<div class="form-div-3" id="colonia-div">
						<label>Colonia / Localidad</label><span class="asterisk ast">*</span> 
						<input type="text" placeholder="Localidad" name="colonia" id="colonia" maxlength="100" value="<?php echo $arr['colonia_descr'][0]?>">
					</div>
					<div class="form-div-3" id="colonia2-div" style="display: none;">
						<label>Colonia / Localidad</label><span class="asterisk">*</span>
						<select name="colonia2" id="colonia-select">
						</select>
					</div>
					<div class="form-div-3">
						Calle<span class="asterisk ast">*</span>
						<input type="text" placeholder="Calle" name="calle" id="calle" maxlength="50" value="<?php echo $arr['calle'][0]?>" >
					</div>
					<div class="form-div-3">
						Número exterior<span class="asterisk ast">*</span>
						<input type="text" placeholder="Exterior" name="noexterior" id="num_exterior" maxlength="15" value="<?php echo $arr['num_exterior'][0]?>" >
					</div>
					<div class="form-div-3">
						Número interior
						<input type="text" placeholder="Interior" name="nointerior" id="num_interior" maxlength="15" value="<?php echo $arr['num_interior'][0]?>">
					</div>
				</div>
			</div>
			<div class="forms-container oculto" id="container-vehiculo">
				<div class="form-div-3">
					<label>Marca:</label><span class="asterisk">*</span>
					<input type="text" name="marca" id="marca" placeholder="Marca" maxlength="30" value="<?php echo $arr['marca'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Modelo:</label><span class="asterisk">*</span>
					<input type="text" name="modelo" id="modelo" placeholder="Modelo" maxlength="30" value="<?php echo $arr['modelo'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Año:</label><span class="asterisk">*</span>
					<input type="text" name="anio" id="anio" placeholder="Año" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" min="0" max="<?php echo date('Y')+1; ?>" value="<?php echo $arr['anio'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Número de serie o registro:</label><span class="asterisk">*</span>
					<input type="text" name="serie" id="serie" placeholder="No. de serie" maxlength="30" value="<?php echo $arr['serie'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Lugar de registro:</label><span class="asterisk">*</span><br>
					<input type="radio" name="ubicacion2" id="01mexico" value="M" checked>MÉXICO
					<input type="radio" name="ubicacion2" id="01extranjero" value="E">EXTRANJERO
				</div>
				<div class="form-div-3">
					<label>País</label><span class="asterisk">*</span>
					<select name="pais2" id="pais2">
							<?php paises(); ?>
					</select>
				</div>
				<div class="form-div-3" id="ent_fed">
					<label>Entidad Federativa:</label><span class="asterisk">*</span>
					<select name="estado3" id="estado3">
							<?php entidades(); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Titular:</label><span class="asterisk">*</span>
					<select name="dueno" id="dueno">
						<?php lista_valores("Dueno"); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Nombre o razón social del titular:</label><span class="asterisk">*</span>
					<input type="text" name="nombre_dueno" id="nombre_dueno" placeholder="Nombre" maxlength="100" value="<?php echo $arr['nombre_dueno'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>RFC:</label><span class="asterisk">*</span>
					<input type="text" name="rfc_dueno" id="rfc_dueno" placeholder="RFC" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_dueno'][0] ?>">
					<div class="aviso_pendientes" id="aviso_rfc_dueno"></div>
				</div>
				<div class="form-div-3">
					<label>Relación con el titular:</label><span class="asterisk">*</span>
					<select id="relacion" name="relacion">
						<?php lista_valores("Relacion"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3" id="otra-rel">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otra_relacion" id="otra_relacion" maxlength="50" value="<?php echo $arr['otra_relacion'][0] ?>">
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-1">
					<label>Observaciones:</label><br>
					<textarea name="observaciones" id="observaciones" rows="10" maxlength="1000" ><?php echo $arr['observaciones'][0] ?></textarea>
					<div class="form-div-ob"><span id="contador">0</span>/1000</div>
				</div>
			</div>
			<div class="botones">
				<div class="botones-submit">
					<button id="cancelar" type="reset">Cancelar</button>				
					<input type="hidden" name="form15">
					<input type="hidden" name="movimiento" id="movimiento" value="<?php echo $arr['movimiento'][0]?>">
					<input type="hidden" name="secuencia" id="secuencia" value="<?php echo $arr['secuencia'][0]?>">
					<input type="hidden" name="total_reg" id="total_reg" value="<?php echo $arr['total']?>">
					<input type="hidden" name="total_reg2" id="total_reg2" value="<?php echo $arr['total2']?>">
					<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
					<input type="hidden" name="tipo-declaracion" value="P">
					<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
					<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
					<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
					<button type="submit" id="guardar">Guardar</button>
				</div>
			</form>
				<div class="botones-nav">
						<form action="14-adeudos.php#14" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-anterior">Anterior</button>
						</form>
 						<form action="finalizar-declaracion.php" method="POST" style="display: inline;">
 							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-siguiente">Finalizar</button>
 						</form>
 				</div>
			</div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
			<form action="#15" method="POST">
				<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
				<input type="hidden" name="tipo-declaracion" value="P">
				<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
				<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
				<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
				<button>Aceptar</button>
			</form>
		</div>
		<div id="aviso-agregar">
			<span id="msj"></span>
			<button id="quitar-agregar">Aceptar</button>
		</div>
	</div>
	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>