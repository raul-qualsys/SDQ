<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("10","menu3-1","menu1-2");}
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
		$arr=cargarform10($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform10($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		if(empty($html)){$arr["html"][0]="";}
		else{$arr["html"]=$html;}
		//print_r($arr);die;
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["declarante"][0]="";
			$arr["tipo-inmueble"][0]="";
			$arr["otro_inmueble"][0]="";
			$arr["titular"][0]="";
			$arr["titular_descr"][0]="";
			$arr["pct_propiedad"][0]="0.00";
			$arr["sup_terreno"][0]="0.00";
			$arr["sup_construc"][0]="0.00";
			$arr["adquisicion"][0]="";
			$arr["adquisicion_descr"][0]="";
			$arr["forma_pago"][0]="";
			$arr["forma_descr"][0]="";
			$arr["tercero"][0]="";
			$arr["tercero_descr"][0]="";
			$arr["nombre_tercero"][0]="";
			$arr["rfc_tercero"][0]="";
			$arr["transmisor"][0]="";
			$arr["transmisor_descr"][0]="";
			$arr["nombre_transmisor"][0]="";
			$arr["rfc_transmisor"][0]="";
			$arr["relacion"][0]="";
			$arr["relacion_descr"][0]="";
			$arr["otra_relacion"][0]="";
			$arr["valor_adquisicion"][0]="0.00";
			$arr["tipo_moneda"][0]="";
			$arr["fecha_adquisicion"][0]="";
			$arr["registro_publico"][0]="";
			$arr["valor_conforme_a"][0]="";
			$arr["conforme_descr"][0]="";
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
			$arr["causa_baja"][0]="";
			$arr["otra_causa"][0]="";
			$arr["observaciones"][0]="";
			$arr["total"]=1;
			$arr["html"][0]="";
		}
		$arr2=json_encode($arr);
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">var b=<?php echo $arr2;?></script>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#tipo_inmueble option[value="'+'<?php echo $arr["tipo_inmueble"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#titular option[value="'+'<?php echo $arr["titular"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tercero option[value="'+'<?php echo $arr["tercero"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#adquisicion option[value="'+'<?php echo $arr["adquisicion"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#forma_pago option[value="'+'<?php echo $arr["forma_pago"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#transmisor option[value="'+'<?php echo $arr["transmisor"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#relacion option[value="'+'<?php echo $arr["relacion"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tipo_moneda option[value="'+'<?php echo $arr["tipo_moneda"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#valor_conforme_a option[value="'+'<?php echo $arr["valor_conforme_a"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#causa_baja option[value="'+'<?php echo $arr["causa_baja"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#pais option[value="'+'<?php echo $arr["pais"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#estado option[value="'+'<?php echo $arr["estado"][0] ?>'+'"]').attr('selected', 'selected');
	    		lista_municipios("<?php echo $arr["municipio"][0] ?>");

			    var ubicacion=document.getElementsByName("ubicacion");
			    for (var i = ubicacion.length - 1; i >= 0; i--) {
			      if(ubicacion[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){
			        ubicacion[i].checked="checked";
			        if(ubicacion[i].value == "M")ubicacion_m();
			        if(ubicacion[i].value == "E")ubicacion_e();
			      }
				}
				if('<?php echo $arr["movimiento"][0]; ?>'=='B'){
				    $("#baja").prop("checked",true);
				    $(".motivo_baja").show();
				}
				else{
				    $("#baja").prop("checked",false);
				    $(".motivo_baja").hide();
				}
				otra_relacion2();
				otro_inmueble();
				causa_baja();
				if('<?php echo $arr["movimiento"][0]; ?>'=='N'){
				    $("#ninguno").prop("checked",true);
				    $(".ningun_registro").hide();
				}
				else{
				    $("#ninguno").prop("checked",false);
				    $(".ningun_registro").show();
				}
				separador_punto(document.getElementById("valor_adquisicion"));
				separador_sup(document.getElementById("sup_terreno"));
				separador_sup(document.getElementById("sup_construc"));
		        $("#contador").html(document.getElementById("observaciones").value.length);
			});
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
			<div class="subtitle">10. BIENES INMUEBLES (SITUACIÓN ACTUAL)</div>
			<?php } else{ ?>
			<div class="subtitle">9. BIENES INMUEBLES (SITUACIÓN ACTUAL)</div>
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
				<p class="not">Todos los datos de bienes declarados a nombre de la pareja, dependientes económicos y/o terceros <br>o que sean en copropiedad con el declarante no serán públicos.</p>
				<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>

				<div class="subsubtitle">Bienes del declarante, pareja y/o dependientes económicos</div>
			</div>
			<div class="forms-container motivo_baja">
				<div class="form-div-3">
					<label>Motivo de baja:</label>
					<select id="causa_baja" name="causa_baja">
						<?php lista_valores("Causa_Baja"); ?>
					</select>					
				</div>
				<div style="display: none;" class="form-div-3" id="otro-motivo">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" id="otra_causa" name="otra_causa" maxlength="50" value="<?php echo $arr['otra_causa'][0]?>">
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Tipo de inmueble:</label><span class="asterisk">*</span>
					<select id="tipo_inmueble" name="tipo_inmueble" required>
							<?php lista_valores("Tipo_Inmueble"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3" id="otro-ambito">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otro_inmueble" id="otro_inmueble" maxlength="50" value="<?php echo $arr['otro_inmueble'][0] ?>">
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Titular del inmueble:</label><span class="asterisk">*</span>
					<select id="titular" name="titular" required>
							<?php lista_valores("Titular"); ?>
					</select>
				</div>
				<div class="form-div-2-3">
					<label>Porcentaje de propiedad del declarante conforme a contrato:</label><span class="asterisk">*</span>
					<input type="text" placeholder="%"  onkeypress="valida(event,'float')" onpaste="valida(event,'float')" name="pct_propiedad" id="pct_propiedad" min="0" max="100.00" value="<?php echo $arr['pct_propiedad'][0] ?>" style="width:20%;" required>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Superficie del terreno (m<sup>2</sup>):</label><span class="asterisk">*</span>
					<input type="text" placeholder="Metros cuadrados"  onkeypress="valida(event,'float')" onpaste="valida(event,'float')" name="sup_terreno" id="sup_terreno" min="0" value="<?php echo $arr['sup_terreno'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>Superficie de construcción (m<sup>2</sup>):</label><span class="asterisk">*</span>
					<input type="text" placeholder="Metros cuadrados" onkeypress="valida(event,'float')" onpaste="valida(event,'float')" name="sup_construc" id="sup_construc" min="0" value="<?php echo $arr['sup_construc'][0] ?>" required>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Tercero:</label>
					<select id="tercero" name="tercero">
						<?php lista_valores("Tercero"); ?>
					</select>
				</div>
				<div class="form-div-3 tercero-options">
					<label>Nombre del tercero o terceros:</label>
					<input type="text" name="nombre_tercero" id="nombre_tercero" placeholder="Nombre" maxlength="100" value="<?php echo $arr['nombre_tercero'][0] ?>">
				</div>
				<div class="form-div-3 tercero-options">
					<label>RFC:</label>
					<input type="text" name="rfc_tercero" id="rfc_tercero" placeholder="RFC" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_tercero'][0] ?>">
					<div class="aviso_pendientes" id="aviso_rfc_tercero"></div>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Forma de adquisición:</label><span class="asterisk">*</span>
					<select id="adquisicion" name="adquisicion" required>
							<?php lista_valores("Adquisicion"); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Forma de pago:</label><span class="asterisk">*</span>
					<select id="forma_pago" name="forma_pago" required>
						<?php lista_valores("Forma_Pago"); ?>
					</select>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Transmisor de la propiedad:</label>
					<select id="transmisor" name="transmisor">
						<?php lista_valores("Tercero"); ?>
					</select>					
				</div>
				<div class="form-div-3 transmisor-options">
					<label>Nombre o razón social del transmisor:</label> 
					<input type="text" name="nombre_transmisor" id="nombre_transmisor" placeholder="Nombre" maxlength="100" value="<?php echo $arr['nombre_transmisor'][0] ?>">
				</div>
				<div class="form-div-3 transmisor-options">
					<label>RFC:</label>
					<input type="text" name="rfc_transmisor" id="rfc_transmisor" placeholder="RFC" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_transmisor'][0] ?>">
					<div class="aviso_pendientes" id="aviso_rfc_transmisor"></div>
				</div>
				<div class="form-div-2-3 transmisor-options">
					<label>Relación del transmisor de la propiedad con el titular:</label>
					<select id="relacion" name="relacion">
						<?php lista_valores("Relacion"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3" id="otra-rel">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otra_relacion" id="otra_relacion" maxlength="50" value="<?php echo $arr['otra_relacion'][0] ?>">
				</div>				
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Valor de adquisición:</label><span class="asterisk">*</span>
					<input type="text" name="valor_adquisicion" id="valor_adquisicion" placeholder="Valor" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" min="0" value="<?php echo $arr['valor_adquisicion'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>Tipo de moneda:</label><span class="asterisk">*</span>
					<select name="tipo_moneda" id="tipo_moneda" required>
						<?php monedas(); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Fecha de adquisición del inmueble:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_adquisicion" id="fecha_adquisicion" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_adquisicion'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>El valor de adquisición es conforme a:</label><span class="asterisk">*</span>
					<select id="valor_conforme_a" name="valor_conforme_a" required>
						<?php lista_valores("Valor_Conforme_A"); ?>
					</select>					
				</div>
				<div class="form-div-2-3">
					<label>Identificador de registro público de la propiedad:</label><span class="asterisk">*</span>
					<input type="text" name="registro_publico" id="registro_publico" placeholder="Folio, número o identificador" maxlength="50" value="<?php echo $arr['registro_publico'][0] ?>" style="width: 40%;" required>
				</div>
			</div>
			<div class="subsubtitle ningun_registro">Ubicación del inmueble</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-1">
					<label>Ubicación:</label><span class="asterisk">*</span>
					<input type="radio" name="ubicacion" id="dommex" value="M" checked required>MÉXICO
					<input type="radio" name="ubicacion" id="domext" value="E">EXTRANJERO
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
 					<label>País:</label><span class="asterisk">*</span>
						<select name="pais" id="pais" required>
							<?php paises(); ?>
						</select>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label id="cp-label" style="display: block;">Código postal<span class="asterisk ast">*</span></label>
					<input type="text" style="width:50%;display: inline-block;" placeholder="Código Postal" name="cp" id="cp" maxlength="5" value="<?php echo $arr['cp'][0]?>" required>
					<img src="../images/lupa.png" id="buscar-direccion"></img>
					<span id="msj-cp" style="font-weight: bold;color: red;"></span>
				</div>
			</div>
			<div class="forms-container ningun_registro">
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
					<input type="text" placeholder="Calle" name="calle" id="calle" maxlength="50" value="<?php echo $arr['calle'][0]?>" required>
				</div>
				<div class="form-div-3">
					Número exterior<span class="asterisk ast">*</span>
					<input type="text" placeholder="Exterior" name="noexterior" id="num_exterior" maxlength="15" value="<?php echo $arr['num_exterior'][0]?>" required>
				</div>
				<div class="form-div-3">
					Número interior
					<input type="text" placeholder="Interior" name="nointerior" id="num_interior" maxlength="15" value="<?php echo $arr['num_interior'][0]?>">
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-1">
					<label>Observaciones:</label><br>
					<textarea name="observaciones" id="observaciones" rows="10" maxlength="1000" ><?php echo $arr['observaciones'][0]?></textarea>
					<div class="form-div-ob"><span id="contador">0</span>/1000</div>
				</div>
			</div>
			<div class="botones">
					<div class="botones-submit">
						<button id="cancelar" type="reset">Cancelar</button>				
						<input type="hidden" name="form10">
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
						<?php if($tipo_dec != 'M'){
						?>
						<form action="9-datos-servidor-anterior.php#09" method="POST" style="display: inline;">
						<?php
						} 
						else{
						?>
						<form action="8-ingresos-declarante.php#08" method="POST" style="display: inline;">
						<?php
						}
						?>
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="P">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-anterior">Anterior</button>
							</form>
							<form action="11-vehiculos.php#11" method="POST" style="display: inline;">
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="P">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-siguiente">Siguiente</button>
							</form>
					</div>
				</div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<form action="#10" method="POST">
					<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
					<input type="hidden" name="tipo-declaracion" value="P">
					<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
					<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
					<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
				<!-- 	<button class="seccion-siguiente">Siguiente</button> -->
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