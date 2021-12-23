<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("05","menu3-1","menu1-2");}
</script>
	<body>
		<?php include("../../include/header-buttons.php");?>
		<div id="main-content">
			<?php 
			include("../../controllers/permiso-e.php"); 
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
			//print_r($tipo_declaracion);die;
			setDeclaracionI($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
			$arr=cargarformi5($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
			$html=checkformi5($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
			if(empty($html)){$arr["html"][0]="";}
			else{$arr["html"]=$html;}
			if($arr["total"]==0){
				$arr["secuencia"][0]=1;
				$arr["movimiento"][0]="";
				$arr["actividad"][0]="";
				$arr["declarante"][0]="";
				$arr["nombre_empresa"][0]="";
				$arr["rfc_empresa"][0]="";
				$arr["cliente"][0]="";
				$arr["cliente_descr"][0]="";
				$arr["nombre_cliente"][0]="";
				$arr["rfc_cliente"][0]="";
				$arr["sector"][0]="";
				$arr["sector_descr"][0]="";
				$arr["otro_sector"][0]="";
				$arr["monto_mensual"][0]=0;
				$arr["ubicacion"][0]="";	
				$arr["estado"][0]="";
				$arr["estado_descr"][0]="";
				$arr["pais"][0]="";
				$arr["pais_descr"][0]="";
				$arr["observaciones"][0]="";
				$arr["total"]=1;
				$arr["html"][0]="";
			}
			$arr2=json_encode($arr);
			include("sidebar-intereses.php"); ?>
			<script type="text/javascript">var b=<?php echo $arr2;?></script>
			<script type="text/javascript">
		  		$(document).ready(function(){
		    		$('#declarante option[value="'+'<?php echo $arr["declarante"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#sector-pert option[value="'+'<?php echo $arr["sector"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#cliente option[value="'+'<?php echo $arr["cliente"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#estado option[value="'+'<?php echo $arr["estado"][0] ?>'+'"]').attr('selected', 'selected');

				    var actividad=document.getElementsByName("actividad");
				    for (var i = actividad.length - 1; i >= 0; i--) {
				      if(actividad[i].value=='<?php echo $arr["actividad"][0]; ?>'){actividad[i].checked="checked";}
					}
				    var ubicacion=document.getElementsByName("ubicacion");
				    for (var i = ubicacion.length - 1; i >= 0; i--) {
				      if(ubicacion[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){
				      	ubicacion[i].checked="checked";
				        if(ubicacion[i].value == "M")ubicacion_m1();
				        if(ubicacion[i].value == "E")ubicacion_e1();
				      }
					}
		    		$('#pais option[value="'+'<?php echo $arr["pais"][0] ?>'+'"]').attr('selected', 'selected');
					if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
					else $("#baja").prop("checked",false);
					otro_sector();
					if('<?php echo $arr["movimiento"][0]; ?>'=='N'){
					    $("#ninguno").prop("checked",true);
					    $(".ningun_registro").hide();
					}
					else{
					    $("#ninguno").prop("checked",false);
					    $(".ningun_registro").show();
					}
					separador(document.getElementById("monto_mensual"));
			        $("#contador").html(document.getElementById("observaciones").value.length);
				});
			</script>
			<div id="overlay"></div>
			<div class="header">
				<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
			</div>
			<div class="content" id="content">
				<div class="title0">
					<div class="title1">II. DECLARACIÓN DE INTERESES</div>
				<div class="tipo_decl">EJERCICIO <?php echo $ejercicio; ?> - <?php echo $tipo_declaracion; ?></div>
				</div>
				<form id="form-content-i">
					<div class="subtitle">5. CLIENTES PRINCIPALES<br><p class="subtitle2">(HASTA LOS 2 ULTIMOS AÑOS)</p>
					</div>
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
						<p class="not">Todos los datos de los clientes principales de la pareja o dependiente económicos no serán públicos.</p>
						<p class="not">Se manifestará el beneficio o ganancia directa del declarante si supera mensualmente 250 unidades de medida y actualización (UMA).</p>
					</div>
					<div class="forms-container ningun_registro">
						<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>
						<div class="form-div-1">
			     	 		<label>¿Realiza alguna actividad lucrativa independiente al empleo, cargo o comisión?</label><span class="asterisk">*</span><br> 
							<input type="radio" name="actividad" value="S" checked required>Sí
							<input type="radio" name="actividad" value="N">No 
						</div>
						<div class="form-div-3">
							<label>Participante:</label><span class="asterisk">*</span>
							<select id="declarante" name="declarante" required>
								<?php lista_valores("Declarante"); ?>
							</select>
						</div>
					</div>
					<div class="forms-container ningun_registro">
						<div class="form-div-2-3">
							<label>Nombre de la empresa o servicio que proporciona:</label><span class="asterisk">*</span>
							<input type="text" placeholder="Empresa o Servicio" name="nombre_empresa" id="nombre_empresa" maxlength="100" value="<?php echo $arr['nombre_empresa'][0] ?>" required>
						</div>
						<div class="form-div-3">
							<label>RFC:</label><span class="asterisk">*</span>
							<input type="text" placeholder="RFC" name="rfc_empresa" id="rfc_empresa"minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_empresa'][0] ?>" required>
							<div class="aviso_pendientes" id="aviso_rfc_empresa"></div>
						</div>
					</div>
					<div class="forms-container ningun_registro">
						<div class="form-div-3">
							<label>Cliente principal:</label><span class="asterisk">*</span>
							<select id="cliente" name="cliente" required>
								<?php lista_valores("Cliente"); ?>
							</select>
						</div>
						<div class="form-div-3">
							<label>Nombre o razón social del cliente:</label><span class="asterisk">*</span>
							<input type="text" placeholder="Nombre o Razón social" name="nombre_cliente" id="nombre_cliente" maxlength="100" value="<?php echo $arr['nombre_cliente'][0] ?>" required>
						</div>
						<div class="form-div-3">
							<label>RFC:</label><span class="asterisk">*</span>
							<input type="text" placeholder="RFC" name="rfc_cliente" id="rfc_cliente" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_cliente'][0] ?>" required>
							<div class="aviso_pendientes" id="aviso_rfc_cliente"></div>
						</div>
					</div>
					<div class="forms-container ningun_registro">
						<div class="form-div-3">
							<label>Sector Productivo al que pertenece:</label><span class="asterisk">*</span>
							<select id="sector-pert" name="sector-pert" required>
								<?php lista_valores("Sector"); ?>
							</select>
						</div>
						<div style="display: none;" class="form-div-3" id="otro-sector" >
							<label>Especifique:</label>
							<input type="text" class="otro-regimen opcion-otro" name="otro_sector" id="otro_sector" maxlength="50" value="<?php echo $arr['otro_sector'][0]; ?>">
						</div>
					</div>
					<div class="forms-container ningun_registro">
						<div class="form-div-1">
							<label>Monto aproximado del beneficio o ganancia mensual que obtiene del cliente principal:</label><span class="asterisk">*</span>
							<input type="text" placeholder="Monto del beneficio" name="monto_mensual" id="monto_mensual" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['monto_mensual'][0] ?>" style="width: 20%;" required>
						</div>
					</div>
					<div class="forms-container ningun_registro">
						<div class="form-div-3">
							<label>Lugar donde se ubica:</label><span class="asterisk">*</span><br> 
							<input type="radio" name="ubicacion" value="M" id="01mexico" checked required>México
							<input type="radio" name="ubicacion" value="E" id="01extranjero">Extranjero
							
						</div>
						<div class="form-div-3"  id="01pais" style="display:none;">
							<label>País donde se localiza:</label><span class="asterisk">*</span>
							<select name="pais" id="pais">
								<?php paises(); ?>
							</select>
						</div>
						<div class="form-div-3" id="ent_fed">
							<label>Entidad Federativa:</label><span class="asterisk">*</span>
							<select name="estado" id="estado" >
								<?php entidades(); ?>
							</select>
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
							<input type="hidden" name="formi5">
							<input type="hidden" name="movimiento" id="movimiento" value="<?php echo $arr['movimiento'][0]?>">
							<input type="hidden" name="secuencia" id="secuencia" value="<?php echo $arr['secuencia'][0]?>">
							<input type="hidden" name="total_reg" id="total_reg" value="<?php echo $arr['total']?>">
							<input type="hidden" name="total_reg2" id="total_reg2" value="<?php echo $arr['total2']?>">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="I">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button type="submit" id="guardari">Guardar</button>
						</div>
				</form>
						<div class="botones-nav">
							<form action="4-representacion.php#04" method="POST" style="display: inline;"> 
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="I">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-anterior-i">Anterior</button>
							</form>
							<form action="6-beneficios-privados.php#06" method="POST" style="display: inline;">
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="I">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-siguiente-i">Siguiente</button>
							</form>
						</div>
					</div>
			</div>
			<div id="aceptar-cambio">
				<span id="mensaje"></span>
					<form action="#05" method="POST">
						<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
						<input type="hidden" name="tipo-declaracion" value="I">
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
			include("../../include/footer.php");
		?>
	</body>
</html>