<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("03","menu3-1","menu1-2");}
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
			$arr=cargarformi3($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
			$html=checkformi3($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
			if(empty($html)){$arr["html"][0]="";}
			else{$arr["html"]=$html;}
			if($arr["total"]==0){
				$arr["secuencia"][0]=1;
				$arr["movimiento"][0]="";
				$arr["beneficiario"][0]="";
				$arr["beneficiario_descr"][0]="";
				$arr["otro_beneficiario"][0]="";
				$arr["nombre_prog"][0]="";
				$arr["instit_otorgante"][0]="";
				$arr["orden_id"][0]="";
				$arr["orden_descr"][0]="";
				$arr["tipo_apoyo"][0]="";
				$arr["otro_apoyo"][0]="";
				$arr["forma_recep"][0]="";
				$arr["forma_descr"][0]="";
				$arr["monto_mensual"][0]=0;
				$arr["apoyo_descr"][0]="";	
				$arr["observaciones"][0]="";
				$arr["total"]=1;
				$arr["html"][0]="";
			}
			$arr2=json_encode($arr);
			include("sidebar-intereses.php"); ?>
			<script type="text/javascript">var b=<?php echo $arr2;?></script>
			<script type="text/javascript">
		  		$(document).ready(function(){
		    		$('#beneficiario option[value="'+'<?php echo $arr["beneficiario"][0] ?>'+'"]').attr('selected', 'selected');

		    		$('#orden option[value="'+'<?php echo $arr["orden_id"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#tipo_apoyo option[value="'+'<?php echo $arr["tipo_apoyo"][0] ?>'+'"]').attr('selected', 'selected');
				    var forma_recep=document.getElementsByName("forma_recep");
				    for (var i = forma_recep.length - 1; i >= 0; i--) {
				      if(forma_recep[i].value=='<?php echo $arr["forma_recep"][0]; ?>'){
				      	forma_recep[i].checked="checked";
				        if(forma_recep[i].value == "M")recepcion_m();
				        if(forma_recep[i].value == "E")recepcion_e();
				      }
					}
					if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
					else $("#baja").prop("checked",false);
					otro_beneficiario();
					otro_apoyo();
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
					<div class="subtitle">3. APOYOS O BENEFICIOS PÚBLICOS<br><p class="subtitle2">(HASTA LOS 2 ÚLTIMOS AÑOS)</p>
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
						<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>
						<div class="form-div-3">
							<label>Beneficiario de algún programa público:</label><span class="asterisk">*</span>
							<select id="beneficiario" name="beneficiario" required>
								<?php lista_valores("Beneficiario"); ?>
							</select>
						</div>
						<div class="form-div-3" id="div_beneficiario" style="display:none;">
							<label>Especifique:</label>
							<input type="text" name="otro_beneficiario" id="otro_beneficiario" maxlength="30" value="<?php echo $arr['otro_beneficiario'][0]; ?>">
						</div>
					</div>
					<div class="forms-container ningun_registro">
						<div class="form-div-3">
		            		<label>Nombre del programa:</label><span class="asterisk">*</span>
		            		<input type="text" placeholder="Programa" name="nombre_prog" id="nombre_prog" maxlength="100" value="<?php echo $arr['nombre_prog'][0]; ?>" required>
		            	</div>
					</div>
		            <div class="forms-container ningun_registro">
		            	<div class="form-div-3">
		            		<label>Institución que otorga el apoyo:</label><span class="asterisk">*</span>
		            		<input type="text" placeholder="Institucion" name="instit_otorgante" id="instit_otorgante" maxlength="100" value="<?php echo $arr['instit_otorgante'][0]; ?>" required>
		            	</div>
		            	<div class="form-div-3">
		            		<label>Nivel u orden de gobierno:</label><span class="asterisk">*</span>
		            		<select id="orden" name="orden" required>
								<?php lista_valores("Orden_ID"); ?>
		            		</select>
		            	</div>
		            </div>
		            <div class="forms-container ningun_registro">
		            	<div class="form-div-3">
		            		<label>Tipo de apoyo:</label><span class="asterisk">*</span>
		            		<select id="tipo_apoyo" name="tipo_apoyo" required>
								<?php lista_valores("Tipo_Apoyo"); ?>
		            		</select>
		            	</div>
		            	<div class="form-div-3" id="div_apoyo" style="display:none;">
		            		<label>Especifique:</label>
							<input type="text" name="otro_apoyo" id="otro_apoyo" maxlength="100" value="<?php echo $arr['otro_apoyo'][0]; ?>">
		            	</div>
	            	</div>
	            	<div class="forms-container ningun_registro">
	                	<div class="form-div-3">
		            		<label>Forma de recepción de apoyo:</label><span class="asterisk">*</span><br>
		            		<input type="radio" name="forma_recep" value="M" id="01monetario" checked required>Monetario
		            		<input type="radio" name="forma_recep" value="E" id="01especie">Especie
		            	</div>
						<div class="form-div-3" id="01apoyo" style="display:none;">
							<label>Especifique el apoyo:</label><span class="asterisk">*</span>
							<input type="text" name="apoyo_descr" id="apoyo_descr" maxlength="200" value="<?php echo $arr['apoyo_descr'][0]; ?>">
						</div>
		            </div>
					<div class="forms-container ningun_registro">
						<div class="form-div-3">
							<label>Monto aproximado del apoyo mensual:</label><span class="asterisk">*</span>
							<input type="text" name="monto_mensual" id="monto_mensual" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['monto_mensual'][0]; ?>" required>
						</div>
					</div>
					<div class="forms-container">
						<div class="form-div-1">
							<label>Observaciones:</label><br>
							<textarea name="observaciones" id="observaciones" rows="10" maxlength="1000" ><?php echo $arr['observaciones'][0]; ?></textarea>
							<div class="form-div-ob"><span id="contador">0</span>/1000</div>
						</div>
					</div>
					<div class="botones">
						<div class="botones-submit">
							<button id="cancelar" type="reset">Cancelar</button>				
							<input type="hidden" name="formi3">
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
							<form action="2-participacion-decisiones.php#02" method="POST" style="display: inline;"> 
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="I">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-anterior-i">Anterior</button>
							</form>
							<form action="4-representacion.php#04" method="POST" style="display: inline;">
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
					<form action="#03" method="POST">
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