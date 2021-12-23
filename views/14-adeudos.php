<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("14","menu3-1","menu1-2");}
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
		$arr=cargarform14($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform14($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		if(empty($html)){$arr["html"][0]="";}
		else{$arr["html"]=$html;}
		//print_r($arr);die;
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["declarante"][0]="";
			$arr["tipo_adeudo"][0]="";
			$arr["tipo_adeudo_descr"][0]="";
			$arr["otro_adeudo"][0]="";
			$arr["titular"][0]="";
			$arr["titular_descr"][0]="";
			$arr["tercero"][0]="";
			$arr["tercero_descr"][0]="";
			$arr["nombre_tercero"][0]="";
			$arr["rfc_tercero"][0]="";
			$arr["num_cta"][0]="";
			$arr["fecha_adquisicion"][0]="";
			$arr["monto_original"][0]="";
			$arr["tipo_moneda"][0]="";
			$arr["saldo"][0]="";
			$arr["otorgante"][0]="";
			$arr["otorgante_descr"][0]="";
			$arr["razon_social"][0]="";
			$arr["rfc_institucion"][0]="";
			$arr["ubicacion"][0]="";
			$arr["pais"][0]="";
			$arr["pais_descr"][0]="";
			$arr["observaciones"][0]="";
			$arr["total"]=1;
			$arr["html"][0]="";
		}
		$arr2=json_encode($arr);
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">var b=<?php echo $arr2;?></script>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#titular option[value="'+'<?php echo $arr["titular"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tercero option[value="'+'<?php echo $arr["tercero"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tipo_adeudo option[value="'+'<?php echo $arr["tipo_adeudo"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tipo_moneda option[value="'+'<?php echo $arr["tipo_moneda"][0] ?>'+'"]').attr('selected', 'selected');

	    		$('#otorgante option[value="'+'<?php echo $arr["otorgante"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#pais option[value="'+'<?php echo $arr["pais"][0] ?>'+'"]').attr('selected', 'selected');
			    var ubicacion=document.getElementsByName("ubicacion");
			    for (var i = ubicacion.length - 1; i >= 0; i--) {
			      if(ubicacion[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){
			      	ubicacion[i].checked="checked";
			        if(ubicacion[i].value == "M")ubicacion_m();
			        if(ubicacion[i].value == "E")ubicacion_e();
			      }
				}
				if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
				else $("#baja").prop("checked",false);
				tipo_adeudo();
				if('<?php echo $arr["movimiento"][0]; ?>'=='N'){
				    $("#ninguno").prop("checked",true);
				    $(".ningun_registro").hide();
				}
				else{
				    $("#ninguno").prop("checked",false);
				    $(".ningun_registro").show();
				}
				separador(document.getElementById("saldo"));
				separador(document.getElementById("monto_original"));
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
			<div class="subtitle">14. ADEUDOS / PASIVOS (SITUACIÓN ACTUAL)</div>
			<?php } else{ ?>
			<div class="subtitle">13. ADEUDOS / PASIVOS (SITUACIÓN ACTUAL)</div>
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
				<p class="not">Todos los datos a nombre de la pareja, dependientes económicos y/o terceros <br>o que sean en copropiedad con el declarante no serán públicos.</p>
				<div class="aviso_pendientes" id="pendientes"><?php echo $arr['html'][0] ?></div>
				<div class="subsubtitle">Adeudos del declarante, pareja y/o dependientes económicos</div>
				<div class="form-div-2-3">
					<label>Titular del adeudo:</label><span class="asterisk">*</span>
					<select id="titular" name="titular" required>
						<?php lista_valores("Titular"); ?>
					</select>
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
					<label>Tipo de adeudo:</label><span class="asterisk">*</span>
					<select id="tipo_adeudo" name="tipo_adeudo" required>
						<?php lista_valores("Tipo_Adeudo"); ?>
					</select>
				</div>
				<div style="display: none;" class="form-div-3" id="otro-ambito">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otro_adeudo" id="otro_adeudo" maxlength="50" value="<?php echo $arr['otro_adeudo'][0] ?>">
				</div>
			</div>
			<div class="forms-container ningun_registro">		
				<div class="form-div-3">
					<label>Fecha de adquisición del adeudo:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_adquisicion" id="fecha_adquisicion" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_adquisicion'][0] ?>" required>
				</div>
				<div class="form-div-3" id="num-cuenta">
					<label>Número de cuenta o contrato:</label><span class="asterisk">*</span>
					<input type="text" name="num_cta" id="num_cta" placeholder="No. de cuenta" maxlength="30" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['num_cta'][0] ?>" required>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Monto original del adeudo:</label><span class="asterisk">*</span>
					<input type="text" name="monto_original" id="monto_original" placeholder="Monto" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['monto_original'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>Tipo de moneda:</label><span class="asterisk">*</span>
					<select name="tipo_moneda" id="tipo_moneda" required>
						<?php monedas(); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Saldo:</label><span class="asterisk">*</span>
					<input type="text" name="saldo" id="saldo" placeholder="Saldo" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['saldo'][0] ?>" required>
				</div>
				<div class="form-div-3">
					<label>Otorgante del crédito:</label><span class="asterisk">*</span>
					<select id="otorgante" name="otorgante" id="otorgante" required>
						<?php lista_valores("Otorgante"); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Nombre, institución o razón social:</label>
					<input type="text" name="razon_social" id="razon_social" placeholder="Nombre" maxlength="60" value="<?php echo $arr['razon_social'][0] ?>">
				</div>
				<div class="form-div-3" id="rfc-inst">
					<label>RFC:</label>
					<input type="text" name="rfc_institucion" id="rfc_institucion" placeholder="RFC" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_institucion'][0] ?>">
					<div class="aviso_pendientes" id="aviso_rfc_institucion"></div>
				</div>
				<div class="form-div-3">
					<label>Localización del adeudo:</label><span class="asterisk">*</span><br>
					<input type="radio" name="ubicacion" id="dommex" value="M" checked required>MÉXICO
					<input type="radio" name="ubicacion" id="domext" value="E">EXTRANJERO
				</div>
				<div class="form-div-3" id="pais-inst">
					<label>País:</label><span class="asterisk">*</span>
					<select name="pais" id="pais" required>
							<?php paises(); ?>
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
					<input type="hidden" name="form14">
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
						<form action="13-inversiones.php#13" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-anterior">Anterior</button>
						</form>
						<form action="15-prestamo.php#15" method="POST" style="display: inline;">
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
				<form action="#14" method="POST">
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