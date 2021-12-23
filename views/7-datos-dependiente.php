<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("07","menu3-1","menu1-2");}
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
		$arr=cargarform7($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform7($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		if(empty($html)){$arr["html"][0]="";}
		else{$arr["html"]=$html;}
		//print_r($arr);die;
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["nombre"][0]="";
			$arr["primer_apellido"][0]="";
			$arr["segundo_apellido"][0]="";
			$arr["fecha_nac"][0]="";
			$arr["rfc_dependiente"][0]="";
			$arr["relacion_depend"][0]="";
			$arr["relacion_descr"][0]="";
			$arr["otra_relacion"][0]="";
			$arr["extranjero"][0]="";
			$arr["curp"][0]="";
			$arr["mismo_domicilio"][0]="";
			$arr["residencia"][0]="";
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
			$arr["actividad_laboral"][0]="";
			$arr["otro_ambito"][0]="";
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
			$arr["puesto_id"][0]="";
			$arr["puesto_descr"][0]="";
			$arr["nombre_empresa"][0]="";
			$arr["rfc_empresa"][0]="";
			$arr["funcion_principal"][0]="";
			$arr["fecha_inicio"][0]="";
			$arr["sector"][0]="";
			$arr["sector_descr"][0]="";
			$arr["otro_sector"][0]="";
			$arr["sueldo_mensual"][0]=0;
			$arr["proveedor"][0]="";
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
	    		$('#puesto option[value="'+'<?php echo $arr["puesto_id"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#sector-pert option[value="'+'<?php echo $arr["sector"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#relacion_depend option[value="'+'<?php echo $arr["relacion_depend"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#residencia option[value="'+'<?php echo $arr["residencia"][0] ?>'+'"]').attr('selected', 'selected');
	    		residencia();
	    		$('#pais option[value="'+'<?php echo $arr["pais"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#estado option[value="'+'<?php echo $arr["estado"][0] ?>'+'"]').attr('selected', 'selected');
	    		lista_municipios("<?php echo $arr["municipio"][0] ?>");

			    var extranjero=document.getElementsByName("extranjero");
			    for (var i = extranjero.length - 1; i >= 0; i--) {
			      if(extranjero[i].value=='<?php echo $arr["extranjero"][0]; ?>'){extranjero[i].checked="checked";}
				}
			    var mismo_domicilio=document.getElementsByName("mismo_domicilio");
			    for (var i = mismo_domicilio.length - 1; i >= 0; i--) {
			      if(mismo_domicilio[i].value=='<?php echo $arr["mismo_domicilio"][0]; ?>'){mismo_domicilio[i].checked="checked";}
				}
			    var proveedor=document.getElementsByName("proveedor");
			    for (var i = proveedor.length - 1; i >= 0; i--) {
			      if(proveedor[i].value=='<?php echo $arr["proveedor"][0]; ?>'){proveedor[i].checked="checked";}
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
				sector_laboral();
				otro_sector();
				otra_relacion();
				ente();
				separador(document.getElementById("sueldo_mensual"));
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
			<div class="subtitle">7. DATOS DEL DEPENDIENTE ECONÓMICO</div>
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
					<p class="not">Todos los datos relativos a menores de edad no serán públicos.</p>
				<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>
				<div class="form-div-3">
					<label>Nombre(s):</label><span class="asterisk">*</span>
					<input type="text" placeholder="Nombre" name="nombre" id="nombre" maxlength="30" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" value="<?php echo $arr['nombre'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>Primer Apellido:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Primer apellido" name="primer_apellido" id="primer_apellido" maxlength="30" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" value="<?php echo $arr['primer_apellido'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>Segundo Apellido:</label>
					<input type="text" placeholder="Segundo apellido" name="segundo_apellido" id="segundo_apellido" maxlength="30" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" value="<?php echo $arr['segundo_apellido'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Fecha de nacimiento:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_nac" id="fecha_nac" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_nac'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>CURP:</label><span class="asterisk">*</span>
					<input type="text" placeholder="CURP" name="curp" id="curp" minlength="18" maxlength="18" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['curp'][0] ?>" required>
					<div class="aviso_pendientes" id="aviso_curp"></div>
				</div>
				<div class="form-div-3">
					<label>RFC:</label>
					<input type="text" placeholder="RFC" name="rfc_dependiente" id="rfc_dependiente" minlength="13" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_dependiente'][0] ?>">
					<div class="aviso_pendientes" id="aviso_rfc_dependiente"></div>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>¿Es ciudadano extranjero?</label><span class="asterisk">*</span><br>
					<input type="radio" name="extranjero" value="S">Sí
					<input type="radio" name="extranjero" value="N" checked required>No
				</div>
				<div class="form-div-2-3">
					<label>¿Habita en el domicilio del declarante?</label><span class="asterisk">*</span><br>
					<input type="radio" name="mismo_domicilio" id="dom_s" value="S">Sí
					<input type="radio" name="mismo_domicilio" id="dom_n" value="N" checked required>No
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Relación con el declarante:</label><span class="asterisk">*</span>
					<select id="relacion_depend" name="relacion_depend">
							<?php lista_valores("Relacion_Depend"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3" id="otra-relacion">
					Especifique:
					<input type="text" class="otro-regimen opcion-otro" id="otra_relacion" name="otra_relacion" maxlength="100" value="<?php echo $arr['otra_relacion'][0] ?>">
				</div>
				<div class="form-div-3">
					<label>Lugar donde reside:</label><span class="asterisk">*</span>
					<select id="residencia" name="residencia" required>
							<?php lista_valores("Residencia"); ?>
					</select>
				</div>
			</div>
			<div class="subsubtitle ningun_registro">
				<label>Domicilio del dependiente:</label>
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
			<div class="subsubtitle ningun_registro">
				<label>Actividad laboral:</label>
			</div>
			<div class="ningun_registro">
				<div class="forms-container">
					<div class="form-div-3">
						<label>Ámbito / Sector laboral:</label><span class="asterisk">*</span>
						<select id="sector" name="sector" required>
								<?php lista_valores("Actividad_Laboral"); ?>
						</select>
					</div>
					<div style="display: none;" class="form-div-3 caso_ninguno" id="otro-ambito">
						<label>Especifique:</label>
						<input type="text" class="otro-regimen opcion-otro" name="otro_ambito" id="otro_ambito" maxlength="50" value="<?php echo $arr['otro_ambito'][0] ?>">
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
						<label>RFC:</label><span class="asterisk">*</span>
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
						<input type="date" name="fecha_ingreso" id="fecha_ingreso" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_inicio'][0] ?>">
					</div>
					<div class="form-div-3">
						<label>Salario mensual neto:</label><span class="asterisk">*</span>
						<input type="text" name="sueldo_mensual" id="sueldo_mensual" placeholder="Sueldo" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['sueldo_mensual'][0] ?>" required>
					</div>
				</div>
				<div class="forms-container caso_ninguno">
					<div class="form-div-1 sector-privado">
						<label>¿Es proveedor o contratista del gobierno?</label><span class="asterisk">*</span><br>
						<input type="radio" name="proveedor" value="S">Sí
						<input type="radio" name="proveedor" value="N" required checked>No
					</div>
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
						<input type="hidden" name="form7">
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
						<form action="6-datos-pareja.php#06" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-anterior">Anterior</button>
						</form>
						<form action="8-ingresos-declarante.php#08" method="POST" style="display: inline;">
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
				<form action="#07" method="POST">
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