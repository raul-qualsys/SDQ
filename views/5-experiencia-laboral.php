<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("05","menu3-1","menu1-2");}
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
		$arr=cargarform5($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform5($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		if(empty($html)){$arr["html"][0]="";}
		else{$arr["html"]=$html;}
		//print_r($arr);die;
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["actividad_laboral"][0]="";
			$arr["otra_actividad"][0]="";
			$arr["orden_id"][0]="";
			$arr["orden_descr"][0]="";
			$arr["ambito_id"][0]="";
			$arr["ambito_descr"][0]="";
			$arr["dependencia"][0]="";
			$arr["dependencia_descr"][0]="";
			$arr["area_adscripcion"][0]="";
			$arr["area_descr"][0]="";
			$arr["puesto"][0]="";
			$arr["puesto_descr"][0]="";
			$arr["nombre_empresa"][0]="";
			$arr["rfc_empresa"][0]="";
			$arr["funcion_principal"][0]="";
			$arr["fecha_inicio"][0]="";
			$arr["fecha_fin"][0]="";
			$arr["sector"][0]="";
			$arr["sector_descr"][0]="";
			$arr["otro_sector"][0]="";
			$arr["ubicacion"][0]="";
			$arr["observaciones"][0]="";
			$arr["total"]=1;
			$arr["html"][0]="";
		}
		$arr2=json_encode($arr);
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">var b=<?php echo $arr2;?></script>
		<script type="text/javascript">
	  		$(document).ready(function(){
				$('#sector option[value="'+'<?php echo $arr["actividad_laboral"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#nivel_gobierno option[value="'+'<?php echo $arr["orden_id"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#ambito_publico option[value="'+'<?php echo $arr["ambito_id"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#ente option[value="'+'<?php echo $arr["dependencia"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#area option[value="'+'<?php echo $arr["area_adscripcion"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#puesto option[value="'+'<?php echo $arr["puesto"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#sector-pert option[value="'+'<?php echo $arr["sector"][0] ?>'+'"]').attr('selected', 'selected');
/*				  document.getElementById("area2").value='<?php echo $arr["area_descr"][0] ?>';
				  document.getElementById("puesto2").value='<?php echo $arr["puesto_descr"][0] ?>';
				  document.getElementById("ente2").value='<?php echo $arr["dependencia_descr"][0] ?>';
*/
			    var ubicacion=document.getElementsByName("ubicacion");
			    for (var i = ubicacion.length - 1; i >= 0; i--) {
			      if(ubicacion[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){
			        ubicacion[i].checked="checked";
			      }
				}
				if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
				else $("#baja").prop("checked",false);
				sector_laboral();
				otro_sector();
	    		ente();
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
			<div class="subtitle">5. EXPERIENCIA LABORAL (ÚLTIMOS CINCO EMPLEOS)</div>
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
				<?php 
				if($tipo_dec=="M" || $tipo_dec=="C"){

				?>
					<div class="form-div-4" style="text-align: center;">
						<input type="checkbox" name="baja" id="baja" >Baja
					</div>
				<?php
				}
				?>
			</div>
			<div class="forms-container">
				<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>
				<div class="form-div-3">
					<label>Ámbito / Sector laboral:</label><span class="asterisk">*</span>
					<select id="sector" name="sector" required>
							<?php lista_valores("Actividad_Laboral"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3 caso_ninguno" id="otro-ambito">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otro_ambito" id="otro_ambito" maxlength="50" value="<?php echo $arr['otra_actividad'][0] ?>">
				</div>
			</div>
			<div class="forms-container caso_ninguno">
				<div class="form-div-3 sector-publico">
					<label>Nivel / Orden de gobierno:</label><span class="asterisk">*</span>
					<select id="nivel_gobierno" name="nivel">
							<?php lista_valores("Orden_ID"); ?>
					</select>
				</div>
				<div class="form-div-3 sector-privado">
					<label>RFC:</label>
					<input type="text" name="rfc_empresa" id="rfc_empresa" placeholder="RFC" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_empresa'][0] ?>">
					<div class="aviso_pendientes" id="aviso_rfc_empresa"></div>
				</div>
				<div class="form-div-3 sector-publico">
					<label>Ámbito público:</label><span class="asterisk">*</span>
					<select id="ambito_publico" name="ambito_publico">
							<?php lista_valores("Ambito_ID"); ?>
					</select>
				</div>
				<div class="form-div-3 sector-privado">
					<label>Sector al que pertenece:</label><span class="asterisk">*</span>
					<select id="sector-pert" name="sector-pert">
							<?php lista_valores("Sector"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3" id="otro-sector" >
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otro_sector" id="otro_sector" maxlength="50" value="<?php echo $arr['otro_sector'][0] ?>">
				</div>
			</div>
			<div class="forms-container caso_ninguno">

				<div class="form-div-3">
					<label class="sector-publico">Nombre del ente público:<span class="asterisk">*</span></label>
					<label class="sector-privado">Nombre de la empresa:<span class="asterisk">*</span></label>
					<select name="ente" id="ente" class="sector-publico">
						<?php lista_dependencias(); ?>
					</select>
					<input type="text" name="ente2" id="ente2" class="sector-privado" placeholder="Nombre" maxlength="100" value="<?php echo $arr['dependencia_descr'][0] ?>">
				</div>
				<div class="form-div-3" style="display: none;" id="otra_dependencia">
					<label>Otro ente público:</label><span class="asterisk">*</span>
					<input type="text" name="otro_ente" id="otro_ente" placeholder="Ente público" maxlength="100" value="<?php echo $arr['dependencia_descr'][0] ?>">
				</div>
				<div class="form-div-3">
					<label class="sector-publico">Área de adscripción:<span class="asterisk">*</span></label>
					<label class="sector-privado">Área:<span class="asterisk">*</span></label>
					<select name="area" id="area" style="display: none;">
						<?php lista_areas(); ?>
					</select>
					<input type="text" name="area2" id="area2" placeholder="Área" maxlength="100" value="<?php echo $arr['area_descr'][0] ?>">
				</div>
				<div class="form-div-3">
					<label class="sector-publico">Empleo, cargo o comisión:<span class="asterisk">*</span></label>
					<label class="sector-privado">Puesto:<span class="asterisk">*</span></label>
					<select name="puesto" id="puesto" style="display: none;">
						<?php lista_puestos(); ?>
					</select>
					<input type="text" name="puesto2" id="puesto2" placeholder="Puesto" maxlength="100" value="<?php echo $arr['puesto_descr'][0] ?>">
				</div>
				<div class="form-div-1 sector-publico">
					<label>Especifique función principal:</label><span class="asterisk">*</span>
					<input type="text" name="funcion_principal" id="funcion_principal" placeholder="Función principal" maxlength="100" value="<?php echo $arr['funcion_principal'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Fecha de ingreso:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_ingreso" id="fecha_ingreso" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;"  max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_inicio'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Fecha de egreso:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_egreso" id="fecha_egreso" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_fin'][0] ?>">
					<label id="aviso_fecha">La fecha de egreso debe ser mayor a la fecha de ingreso.</label>
				</div>
			</div>
			<div class="forms-container caso_ninguno">
				<div class="form-div-1">
					<label>Lugar de ubicación:</label><span class="asterisk">*</span>
					<input type="radio" name="ubicacion" value="M" checked>México
					<input type="radio" name="ubicacion" value="E">Extranjero
				</div>
			</div>			
				<div class="forms-container">
					<div class="form-div-1">
						Observaciones<br>
						<textarea name="observaciones" id="observaciones" rows="10" maxlength="1000" ><?php echo $arr['observaciones'][0]?></textarea>
						<div class="form-div-ob"><span id="contador">0</span>/1000</div>
					</div>
				</div>
				<div class="botones">
					<div class="botones-submit">
						<button id="cancelar" type="reset">Cancelar</button>				
						<input type="hidden" name="form5">
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
						<form action="4-datos-empleo.php#04" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-anterior">Anterior</button>
						</form>
						<?php 
						/* 21-08-2020 DMQ-Qualsys Configuración de acuerdo al puesto si no declara completo*/
						if($_POST["declara_completo"]=="P"){
						/* Fin de actualización */
							?>
						<form action="8-ingresos-declarante.php#08" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-siguiente">Siguiente</button>
						</form>
						<?php
						}
						else{
						?>
						<form action="6-datos-pareja.php#06" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-siguiente">Siguiente</button>
						</form>						
						<?php
						}
						?>
					</div>
				</div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<form action="#05" method="POST">
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