<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("07","menu3-1","menu1-2");}
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
			$arr=cargarformi7($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
			$html=checkformi7($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
			if(empty($html)){$arr["html"][0]="";}
			else{$arr["html"]=$html;}
			if($arr["total"]==0){
				$arr["secuencia"][0]=1;
				$arr["movimiento"][0]="";
				$arr["declarante"][0]="";
				$arr["tipo_fideicomiso"][0]="";
				$arr["tipo_descr"][0]="";
				$arr["como_participa"][0]="";
				$arr["participa_descr"][0]="";
				$arr["rfc_fideicomiso"][0]="";
				$arr["fideicomitente"][0]="";
				$arr["fideicomitente_descr"][0]="";
				$arr["nom_fideicomitente"][0]="";
				$arr["rfc_fideicomitente"][0]="";
				$arr["nom_fiduciario"][0]="";
				$arr["rfc_fiduciario"][0]="";
				$arr["fideicomisario"][0]=0;
				$arr["fideicomisario_descr"][0]="";
				$arr["nom_fideicomisario"][0]="";
				$arr["rfc_fideicomisario"][0]="";
				$arr["sector"][0]="";
				$arr["sector_descr"][0]="";
				$arr["otro_sector"][0]="";
				$arr["ubicacion"][0]="";
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
		    		$('#tipo_fideicomiso option[value="'+'<?php echo $arr["tipo_fideicomiso"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#como_participa option[value="'+'<?php echo $arr["como_participa"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#fideicomitente option[value="'+'<?php echo $arr["fideicomitente"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#fideicomisario option[value="'+'<?php echo $arr["fideicomisario"][0] ?>'+'"]').attr('selected', 'selected');
		    		$('#sector-pert option[value="'+'<?php echo $arr["sector"][0] ?>'+'"]').attr('selected', 'selected');

				    var ubicacion=document.getElementsByName("ubicacion");
				    for (var i = ubicacion.length - 1; i >= 0; i--) {
				      if(ubicacion[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){ubicacion[i].checked="checked";}
					}
					if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
					else $("#baja").prop("checked",false);
					if('<?php echo $arr["movimiento"][0]; ?>'=='N'){
					    $("#ninguno").prop("checked",true);
					    $(".ningun_registro").hide();
					}
					else{
					    $("#ninguno").prop("checked",false);
					    $(".ningun_registro").show();
					}
					otro_sector();
					como_participa();
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
					<div class="subtitle">7. FIDEICOMISOS<br><p class="subtitle2">(HASTA LOS 2 ULTIMOS  AÑOS)</p>
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
						<p class="not">Todos los datos de la participación en empresas, sociedades o asociaciones de la pareja o dependientes económicos no serán públicos.</p>
						<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>
						<div class="form-div-3">
							<label>Participación en fideicomisos:</label><span class="asterisk">*</span>
							<select id="declarante" name="declarante" required>
								<?php lista_valores("Declarante"); ?>
							</select>
						</div>
						<div class="form-div-3">
							<label>Tipo de fideicomiso:</label><span class="asterisk">*</span>
							<select id="tipo_fideicomiso" name="tipo_fideicomiso" required>
								<?php lista_valores("Tipo_Fideicomiso"); ?>
							</select>
						</div>
						<div class="form-div-3">
							<label>RFC del fideicomiso:</label><span class="asterisk">*</span>
							<input type="text" placeholder="RFC" name="rfc_fideicomiso" id="rfc_fideicomiso" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_fideicomiso'][0] ?>" required>
							<div class="aviso_pendientes" id="aviso_rfc_fideicomiso"></div>
						</div>
						<div class="form-div-3">
							<label>Tipo de participación:</label><span class="asterisk">*</span>
							<select id="como_participa" name="como_participa" required>
								<?php lista_valores("Como_Participa"); ?>
							</select>
						</div>
					</div>
					<div class="forms-container ningun_registro" id="fideicomitente-div" style="display: none;">
						<div class="form-div-1">
							<label>Fideicomitente:</label><span class="asterisk">*</span>
							<select id="fideicomitente" name="fideicomitente" style="width:28%;">
								<?php lista_valores("Fideicomitente"); ?>
							</select>
						</div>
						<div class="form-div-2-3">
							<label>Nombre o razón social del fideicomitente:</label><span class="asterisk">*</span>
							<input type="text" placeholder="Nombre o Razón social" name="nom_fideicomitente" id="nom_fideicomitente" maxlength="100" value="<?php echo $arr['nom_fideicomitente'][0] ?>">
						</div>
						<div class="form-div-3">
							<label>RFC del fideicomitente:</label><span class="asterisk">*</span>
							<input type="text" placeholder="RFC" name="rfc_fideicomitente" id="rfc_fideicomitente" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_fideicomitente'][0] ?>">
							<div class="aviso_pendientes" id="aviso_rfc_fideicomitente"></div>
						</div>
					</div>
					<div class="forms-container ningun_registro" id="fiduciario-div" style="display: none;">
						<div class="form-div-3">
							<label>Nombre o razón social del fiduciario:</label><span class="asterisk">*</span>
							<input type="text" placeholder="Nombre o Razón social" name="nom_fiduciario" id="nom_fiduciario" maxlength="100" value="<?php echo $arr['nom_fiduciario'][0] ?>">
						</div>
						<div class="form-div-3">
							<label>RFC del fiduciario:</label><span class="asterisk">*</span>
							<input type="text" placeholder="RFC" name="rfc_fiduciario" id="rfc_fiduciario" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_fiduciario'][0] ?>">
							<div class="aviso_pendientes" id="aviso_rfc_fiduciario"></div>
						</div>
					</div>
					<div class="forms-container ningun_registro" id="fideicomisario-div" style="display: none;">
						<div class="form-div-1">
							<label>Fideicomisario:</label><span class="asterisk">*</span>
							<select id="fideicomisario" name="fideicomisario" style="width:28%;">
								<?php lista_valores("Fideicomisario"); ?>
							</select>
						</div>
						<div class="form-div-2-3">
							<label>Nombre o razón social del fideicomisario:</label><span class="asterisk">*</span>
							<input type="text" placeholder="Nombre del fideicomisario" name="nom_fideicomisario" id="nom_fideicomisario" maxlength="100" value="<?php echo $arr['nom_fideicomisario'][0] ?>">
						</div>
						<div class="form-div-3">
							<label>RFC del fideicomisario:</label><span class="asterisk">*</span>
							<input type="text" placeholder="RFC" name="rfc_fideicomisario" id="rfc_fideicomisario" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_fideicomisario'][0] ?>">
							<div class="aviso_pendientes" id="aviso_rfc_fideicomisario"></div>
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
						<div class="form-div-3">
							<label>Lugar donde se ubica:</label><span class="asterisk">*</span><br> 
							<input type="radio" name="ubicacion" value="M" id="01mexico" checked required>México
							<input type="radio" name="ubicacion" value="E" id="01extranjero">Extranjero
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
							<input type="hidden" name="formi7">
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
							<form action="6-beneficios-privados.php#06" method="POST" style="display: inline;">
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="I">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-anterior-i">Anterior</button>
							</form>
	 						<form action="finalizar-declaracion-i.php" method="POST" style="display: inline;">
	 							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="I">
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
					<form action="#07" method="POST">
						<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
						<input type="hidden" name="tipo-declaracion" value="I">
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
			include("../../include/footer.php");
		?>
	</body>
</html>