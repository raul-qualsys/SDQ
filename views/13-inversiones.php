<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("13","menu3-1","menu1-2");}
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
		$arr=cargarform13($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform13($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		if(empty($html)){$arr["html"][0]="";}
		else{$arr["html"]=$html;}
		//print_r($arr);die;
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["declarante"][0]="";
			$arr["tipo_inversion"][0]="";
			$arr["tipo_inver_descr"][0]="";
			$arr["bancaria"][0]="";
			$arr["bancaria_descr"][0]="";
			$arr["fondo"][0]="";
			$arr["fondo_descr"][0]="";
			$arr["org_privada"][0]="";
			$arr["org_descr"][0]="";
			$arr["monedas"][0]="";
			$arr["monedas_descr"][0]="";
			$arr["seguros"][0]="";
			$arr["seguros_descr"][0]="";
			$arr["valor_bursatil"][0]="";
			$arr["valor_descr"][0]="";
			$arr["afores"][0]="";
			$arr["afores_descr"][0]="";
			$arr["titular"][0]="";
			$arr["titular_descr"][0]="";
			$arr["tercero"][0]="";
			$arr["tercero_descr"][0]="";
			$arr["nombre_tercero"][0]="";
			$arr["rfc_tercero"][0]="";
			$arr["num_cta"][0]="";
			$arr["ubicacion"][0]="";
			$arr["razon_social"][0]="";
			$arr["rfc_institucion"][0]="";
			$arr["pais"][0]="";
			$arr["pais_descr"][0]="";
			$arr["saldo"][0]="";
			$arr["tipo_moneda"][0]="";
			$arr["observaciones"][0]="";
			$arr["total"]=1;
			$arr["html"][0]="";
		}
		$arr2=json_encode($arr);
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">var b=<?php echo $arr2;?></script>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#tipo_inversion option[value="'+'<?php echo $arr["tipo_inversion"][0] ?>'+'"]').attr('selected', 'selected');
				tipo_inversion();
	    		$('#bancaria option[value="'+'<?php echo $arr["bancaria"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#fondo option[value="'+'<?php echo $arr["fondo"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#org_privada option[value="'+'<?php echo $arr["org_privada"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#monedas option[value="'+'<?php echo $arr["monedas"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#seguros option[value="'+'<?php echo $arr["seguros"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#valor_bursatil option[value="'+'<?php echo $arr["valor_bursatil"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#afores option[value="'+'<?php echo $arr["afores"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tipo_moneda option[value="'+'<?php echo $arr["tipo_moneda"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#titular option[value="'+'<?php echo $arr["titular"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#tercero option[value="'+'<?php echo $arr["tercero"][0] ?>'+'"]').attr('selected', 'selected');
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
				if('<?php echo $arr["movimiento"][0]; ?>'=='N'){
				    $("#ninguno").prop("checked",true);
				    $(".ningun_registro").hide();
				}
				else{
				    $("#ninguno").prop("checked",false);
				    $(".ningun_registro").show();
				}
				separador(document.getElementById("saldo"));
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
			<div class="subtitle">13. INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES ACTIVOS</div>
			<?php } else{ ?>
			<div class="subtitle">12. INVERSIONES, CUENTAS BANCARIAS Y OTRO TIPO DE VALORES ACTIVOS</div>
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
				<div class="subsubtitle">Inversiones, cuentas bancarias y otro tipo de valores del declarante, pareja y/o dependientes económicos</div>
				<div class="form-div-3">
					<label>Tipo de inversión / activo:</label><span class="asterisk">*</span>
					<select id="tipo_inversion" name="tipo_inversion" required>
						<?php lista_valores("Tipo_Inversion"); ?>
					</select>
				</div>
				<div class="form-div-3 inversiones" id="bancaria-div">
					<label>Bancaria:</label><span class="asterisk">*</span>
					<select name="bancaria" id="bancaria" class="inversion">
						<?php lista_valores("Bancaria"); ?>
					</select>
				</div>
				<div class="form-div-3 inversiones" id="fondos-div">
					<label>Fondos de inversión:</label><span class="asterisk">*</span>
					<select name="fondo" id="fondo" class="inversion">
						<?php lista_valores("Fondo"); ?>
					</select>
				</div>
				<div class="form-div-3 inversiones" id="org-privada-div">
					<label>Organizaciones privadas:</label><span class="asterisk">*</span>
					<select name="org_privada" id="org_privada" class="inversion">
						<?php lista_valores("Org_Privada"); ?>
					</select>
				</div>
				<div class="form-div-3 inversiones" id="monedas-div">
					<label>Posesion de monedas y/o metales:</label><span class="asterisk">*</span>
					<select name="monedas" id="monedas" class="inversion">
						<?php lista_valores("Monedas"); ?>
					</select>
				</div>
				<div class="form-div-3 inversiones" id="seguros-div">
					<label>Seguros:</label><span class="asterisk">*</span>
					<select name="seguros" id="seguros" class="inversion">
						<?php lista_valores("Seguros"); ?>
					</select>
				</div>
				<div class="form-div-3 inversiones" id="valor-bursatil-div">
					<label>Valores bursátiles:</label><span class="asterisk">*</span>
					<select name="valor_bursatil" id="valor_bursatil" class="inversion">
						<?php lista_valores("Valor_Bursatil"); ?>
					</select>
				</div>
				<div class="form-div-3 inversiones" id="afores-div">
					<label>Afores y otros:</label><span class="asterisk">*</span>
					<select name="afores" id="afores" class="inversion">
						<?php lista_valores("Afores"); ?>
					</select>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-2-3">
					<label>Titular de la inversión, cuenta bancaria y otro tipo de valores/activos:</label><span class="asterisk">*</span>
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
					<input type="text" name="nombre_tercero" id="nombre_tercero" maxlength="100" placeholder="Nombre" value="<?php echo $arr['nombre_tercero'][0]?>">
				</div>
				<div class="form-div-3 tercero-options">
					<label>RFC:</label>
					<input type="text" name="rfc_tercero" id="rfc_tercero" placeholder="RFC" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_tercero'][0]?>">
					<div class="aviso_pendientes" id="aviso_rfc_tercero"></div>
				</div>
			</div>
			<div class="forms-container ningun_registro">
				<div class="form-div-3">
					<label>Localización de la inversión:</label><span class="asterisk">*</span><br>
					<input type="radio" name="ubicacion" id="dommex" value="M" checked required>MÉXICO
					<input type="radio" name="ubicacion" id="domext" value="E">EXTRANJERO
				</div>
				<div class="form-div-3">
					<label>Institución o razón social:</label><span class="asterisk">*</span>
					<input type="text" name="razon_social" id="razon_social" placeholder="Nombre" maxlength="60" value="<?php echo $arr['razon_social'][0]?>" required>
				</div>
				<div class="form-div-3" id="rfc-inst">
					<label>RFC:</label><span class="asterisk">*</span>
					<input type="text" name="rfc_institucion" id="rfc_institucion" placeholder="RFC" minlength="12" maxlength="13" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['rfc_institucion'][0]?>" required>
					<div class="aviso_pendientes" id="aviso_rfc_institucion"></div>
				</div>
				<div class="form-div-3" id="pais-inst">
					<label>País:</label><span class="asterisk">*</span>
					<select name="pais" id="pais" required>
							<?php paises(); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>No. de cuenta, contrato o póliza:</label><span class="asterisk">*</span>
					<input type="text" name="num_cta" id="num_cta" placeholder="No. de cuenta" maxlength="30" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['num_cta'][0]?>" required>
				</div>
				<div class="form-div-3">
					<label>Saldo:</label><span class="asterisk">*</span>
					<input type="text" name="saldo" id="saldo" placeholder="Saldo" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['saldo'][0]?>" required>
				</div>
				<div class="form-div-3">
					<label>Tipo de moneda:</label><span class="asterisk">*</span>
					<select name="tipo_moneda" id="tipo_moneda" required>
						<?php monedas(); ?>
					</select>
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
					<input type="hidden" name="form13">
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
						<form action="12-bienes-muebles.php#12" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-anterior">Anterior</button>
						</form>
						<form action="14-adeudos.php#14" method="POST" style="display: inline;">
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
				<form action="#13" method="POST">
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