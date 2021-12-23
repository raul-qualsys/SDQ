<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("04","menu3-1","menu1-2");}
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
		$arr=cargarform4($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform4($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		if(empty($html)){$arr["html"][0]="";}
		else{$arr["html"]=$html;}
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["orden_id"][0]="";
			$arr["orden_descr"][0]="";
			$arr["ambito_id"][0]="";
			$arr["ambito_descr"][0]="";
			$arr["dependencia"][0]="";
			$arr["dependencia_descr"][0]="";
			$arr["area_adscripcion"][0]="";
			$arr["area_descr"][0]="";
			$arr["nivel_empleo"][0]="";
			$arr["nivel_descr"][0]="";
			$arr["otro_empleo"][0]="";
			$arr["puesto"][0]="";
			$arr["puesto_descr"][0]="";
			$arr["honorarios"][0]="";
			$arr["funcion_principal"][0]="";
			$arr["fecha_inicio"][0]="";
			$arr["tel_oficina"][0]="";
			$arr["extension"][0]="";
			$arr["ubicacion"][0]="";
			$arr["calle"][0]="";
			$arr["num_exterior"][0]="";
			$arr["num_interior"][0]="";
			$arr["colonia"][0]="";
			$arr["colonia_desc"][0]="";
			$arr["municipio"][0]="";
			$arr["municipio_desc"][0]="";
			$arr["estado"][0]="";
			$arr["estado_desc"][0]="";
			$arr["pais"][0]="";
			$arr["pais_desc"][0]="";
			$arr["cp"][0]="";
			$arr["observaciones"][0]="";
			$arr["total"]=1;
			$arr["html"][0]="";
		}
		$arr2=json_encode($arr);
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">var b=<?php echo $arr2;?></script>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#nivel_gobierno option[value="'+'<?php echo $arr["orden_id"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#ambito_publico option[value="'+'<?php echo $arr["ambito_id"][0] ?>'+'"]').attr('selected', 'selected');
				$('#ente option[value="'+'<?php echo $arr["dependencia"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#area option[value="'+'<?php echo $arr["area_adscripcion"][0] ?>'+'"]').attr('selected', 'selected');
				$('#puesto option[value="'+'<?php echo $arr["puesto"][0] ?>'+'"]').attr('selected', 'selected');
	    		//$('#estado option[value="'+'<?php echo $arr["estado"][0] ?>'+'"]').attr('selected', 'selected');
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
			    var honorarios=document.getElementsByName("honorarios");
			    for (var i = honorarios.length - 1; i >= 0; i--) {
			      if(honorarios[i].value=='<?php echo $arr["honorarios"][0]; ?>'){
			        honorarios[i].checked="checked";
			      }
				}
				if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
				else $("#baja").prop("checked",false);
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
				<?php 
				if($tipo_dec=="C"){
				?>
				<div class="subtitle">4. DATOS DEL EMPLEO, CARGO O COMISIÓN QUE FINALIZA</div>
				<?php
				}
				else{
					?>
				<div class="subtitle">4. DATOS DEL EMPLEO, CARGO O COMISIÓN QUE INICIA</div>
					<?php
				}
				?>
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
					<label>Nivel / Orden de gobierno:</label><span class="asterisk">*</span>
					<select id="nivel_gobierno" name="nivel_gobierno" required>
							<?php lista_valores("Orden_ID"); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Ámbito público:</label><span class="asterisk">*</span>
					<select id="ambito_publico" name="ambito_publico" required>
							<?php lista_valores("Ambito_ID"); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Nombre del ente público:</label><span class="asterisk">*</span>
					<select name="ente" id="ente" required>
						<?php lista_dependencias(); ?>
					</select>
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-1" style="display: none;" id="otra_dependencia">
					<label>Otro ente público:</label><span class="asterisk">*</span>
					<input type="text" name="otro_ente" id="otro_ente" style="width: 40%;" placeholder="Ente público" maxlength="100" value="<?php echo $arr['dependencia_descr'][0] ?>">
				</div>
				<div class="form-div-1">
					<label>Área de adscripción:</label><span class="asterisk">*</span>
					<select name="area" id="area" style="display: none;width: 50%;">
						<?php lista_areas(); ?>
					</select>
					<input type="text" name="area2" id="area2" style="width: 40%;" placeholder="Área" maxlength="100" value="<?php echo $arr['area_descr'][0] ?>">
				</div>
				<div class="form-div-1">
					<label>Empleo, cargo o comisión:</label><span class="asterisk">*</span>
					<select name="puesto" id="puesto" style="display: none;width: 50%;">
						<?php lista_puestos(); ?>
					</select>
					<input type="text" name="puesto2" id="puesto2" style="width: 40%;" placeholder="Puesto" maxlength="100" value="<?php echo $arr['puesto_descr'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Nivel del empleo, cargo o comisión:</label><span class="asterisk">*</span>
					<input type="text" name="nivel_empleo" id="nivel" placeholder="Nivel del empleo" maxlength="10" value="<?php echo $arr['nivel_descr'][0] ?>">
				</div>
				<div class="form-div-3">
					¿Está contratado por honorarios?<span class="asterisk">*</span><br>
					<input type="radio" name="honorarios" value="" style="display: none;" required>
					<input type="radio" name="honorarios" value="S" checked>SÍ
					<input type="radio" name="honorarios" value="N">NO
				</div>
				<?php 
				if($tipo_dec=="C"){
				?>
				<div class="form-div-2">
					Fecha de conclusión del empleo, cargo o comisión:<span class="asterisk">*</span>
					<input type="date" name="fecha_empleo" id="fecha_empleo" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_inicio'][0] ?>" style="width:40%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" required>
				</div>
				<?php
				}
				else{
				?>
				<div class="form-div-2">
					Fecha de toma de posesión del empleo, cargo o comisión:<span class="asterisk">*</span>
					<input type="date" name="fecha_empleo" id="fecha_empleo" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_inicio'][0] ?>" style="width:40%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" required>
				</div>
				<?php
				}
				?>
				<div class="form-div-1">
					Especifique función principal:<span class="asterisk">*</span>
					<input type="text" name="funcion_principal" placeholder="Función principal" id="funcion" maxlength="100" value="<?php echo $arr['funcion_principal'][0] ?>" required>
				</div>
				<div class="form-div-3">
					Teléfono de oficina:
					<input type="text"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')"  name="telefono" placeholder="Teléfono" maxlength="10" value="<?php echo $arr['tel_oficina'][0] ?>" id="tel_oficina">
				</div>
				<div class="form-div-3">
					Extensión
					<input type="text" name="extension" id="extension" placeholder="ext." maxlength="15" value="<?php echo $arr['extension'][0] ?>" style="width:20%;">
				</div>
			</div>
				
			<div class="subsubtitle">
				Domicilio del empleo, cargo o comisión
			</div>
				<div class="forms-container">
					<div class="form-div-1">
						Ubicación:<span class="asterisk">*</span>
						<input type="radio" name="ubicacion" id="dommex" value="M" checked required>MÉXICO
						<input type="radio" name="ubicacion" id="domext" value="E">EXTRANJERO
					</div>
					<div class="form-div-3">
 					<label>País:</label><span class="asterisk">*</span>
						<select name="pais" id="pais" required>
							<?php paises(); ?>
						</select>
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
						<label id="cp-label" style="display: block;">Código postal<span class="asterisk ast">*</span></label>
						<input type="text" style="width:50%;display: inline-block;" placeholder="Código Postal" name="cp" id="cp" maxlength="5" value="<?php echo $arr['cp'][0]?>" required>
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
						<input type="text" placeholder="Estado" name="estado2" id="estado2" maxlength="50" value="<?php echo $arr['estado_desc'][0]?>">
					</div>
					<div class="form-div-3" id="municipio-div">
						<label id="municipio-label">Municipio / Alcaldía</label><span class="asterisk">*</span>
						<select name="municipio" id="municipio">
						</select>
					</div>
					<div class="form-div-3" id="colonia-div">
						<label>Colonia / Localidad</label><span class="asterisk ast">*</span>
						<input type="text" placeholder="Localidad" name="colonia" id="colonia" maxlength="100" value="<?php echo $arr['colonia_desc'][0]?>">
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
				<?php 
				if($tipo_dec=="M"){
				?>
				<div class="forms-container">
					<div  class="form-div-1">¿Cuenta con otro empleo, cargo o comisión en el sector público distinto al declarado?<span class="asterisk">*</span>
					<input type="radio" name="otro_empleo" value="S">Sí
					<input type="radio" name="otro_empleo" value="N" checked>No
					</div>					
				</div>
				<?php
				}
				?>
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
						<input type="hidden" name="form4">
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
						<form action="3-datos-curriculares-declarante.php#03" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-anterior">Anterior</button>
						</form>
						<form action="5-experiencia-laboral.php#05" method="POST" style="display: inline;">
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
				<form action="#04" method="POST">
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