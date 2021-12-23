<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");
if(isset($_GET["aviso"])){ ?>
	<script>
	$(document).ready(openDialog5);
	</script>
	<?php
	}
?>
<script type="text/javascript">
	window.onload = function (){menuopc("01","menu3-1","menu1-2");}
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
	$arr=cargarform1($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
	$html=checkform1($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
	include("sidebar-patrimonial.php"); 
	//print_r($arr);die;
	?>
	<script type="text/javascript">
  		$(document).ready(function(){
    		$('#edo_civil option[value="'+'<?php echo $arr["ecivil"] ?>'+'"]').attr('selected', 'selected');
    		$('#regimen_mat option[value="'+'<?php echo $arr["regimen"] ?>'+'"]').attr('selected', 'selected');
    		$('#pais option[value="'+'<?php echo $arr["pais"] ?>'+'"]').attr('selected', 'selected');
    		$('#nacionalidad option[value="'+'<?php echo $arr["nacionalidad"] ?>'+'"]').attr('selected', 'selected');
    		estado_civil();
    		regimen_matrimonial();
		    var servidor=document.getElementsByName("servidor");
		    for (var i = servidor.length - 1; i >= 0; i--) {
		      if(servidor[i].value=='<?php echo $arr['servidor']; ?>'){
		        servidor[i].checked="checked";
		      }
		    }
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
			<form  id="form-content">
				<div class="subtitle">1. DATOS GENERALES</div>
				<div class="forms-container">
					<div class="aviso_pendientes"><?php echo $html; ?></div>
					<div class="form-div-3">
						<label>Nombre(s):</label><span class="asterisk">*</span>
						<input type="text" placeholder="Nombre" name="nombre" maxlength="30" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" value="<?php echo $arr['nombre'] ?>" required>
					</div>
					<div class="form-div-3">
						<label>Primer Apellido:</label><span class="asterisk">*</span>
						<input type="text" placeholder="Primer apellido" name="primerApellido" maxlength="30" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" value="<?php echo $arr['apellido1'] ?>" required>
					</div>
					<div class="form-div-3">
						<label>Segundo Apellido:</label>
						<input type="text" placeholder="Segundo apellido" name="segundoApellido" maxlength="30" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" value="<?php echo $arr['apellido2'] ?>">
					</div>
					<div class="form-div-3">
						<label>CURP:</label><span class="asterisk">*</span>
						<input type="text" placeholder="CURP" name="curp" id="curp" minlength="18" maxlength="18" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $arr['curp'] ?>" required>
					<div class="aviso_pendientes" id="aviso_curp"></div>
					</div>
					<div class="form-div-3">
						<label>RFC:</label>
						<input type="text" placeholder="RFC" name="rfc_short" minlength="10" maxlength="10" value="<?php echo $arr['rfc'] ?>" class="no_editable" required readonly>
					</div>
					<div class="form-div-3">
						<label>Homoclave:</label>
						<input type="text" placeholder="HC" name="homoclave" minlength="3" maxlength="3" style="width: 20%;" value="<?php echo $arr['hc'] ?>" class="no_editable" required readonly>
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
						<label>Correo electrónico personal:</label>
						<input type="email" placeholder="Correo personal" name="email" maxlength="50" value="<?php echo $arr['correo2'] ?>" email required>
					</div>
					<div class="form-div-3">
						<label>Correo electrónico institucional:</label>
						<input type="email" placeholder="Correo institucional" name="email_trabajo"  maxlength="50" value="<?php echo $arr['correo1'] ?>" email=""> 
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
						<label>Teléfono de casa:</label>
						<input type="text" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" placeholder="Teléfono" name="tel_casa" minlength="10" maxlength="10" value="<?php echo $arr['tel1'] ?>">
					</div>
					<div class="form-div-3">
						<label>Teléfono celular:</label>
						<input type="text" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" placeholder="Celular" name="tel_celular" minlength="10" maxlength="10" value="<?php echo $arr['tel2'] ?>">
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
						<label>Estado civil:</label><span class="asterisk">*</span>
						<select id="edo_civil" name="edo_civil" required>
							<?php lista_valores("Estado Civil"); ?>
						</select>
					</div>
					<div class="form-div-3" id="div_regm" style="display: none;">
						<label>Régimen matrimonial:</label>
						<select id="regimen_mat" name="regimen">
							<?php lista_valores("Regimen_Matri"); ?>
						</select>
					</div>
					<div style="display: none;" class="form-div-3 otro" >
						<label>Especifique:</label>
						<input type="text" class="otro-regimen opcion-otro" name="otro" id="otro_regimen" maxlength="20" value="<?php echo $arr['otro'] ?>">
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
						<label>País de nacimiento:</label><span class="asterisk">*</span>
						<select name="pais" id="pais" required>
							<?php paises(); ?>
						</select>
					</div>
					<div class="form-div-3">
						<label>Nacionalidad:</label><span class="asterisk">*</span>
						<select name="nacionalidad" id="nacionalidad" required>
							<?php nacionalidades(); ?>
						</select>
  					</div>
				</div>
				<?php
				if($tipo_dec=="M"){
				?>
				<div class="forms-container">
					<div class="form-div-1">
						<label>¿Te desempeñaste como servidor público el año inmediato anterior?</label><span class="asterisk">*</span>
						<input type="radio" name="servidor" id="servidor_s" value="S">Sí
						<input type="radio" name="servidor" id="servidor_n" value="N">No
						<input type="radio" name="servidor" required style="display: none;">
					</div>
				</div>
				<?php
				}
				?>
				<div class="forms-container">
					<div class="form-div-1">
						<label>Observaciones:</label><br>
						<textarea name="observaciones" id="observaciones" rows="10" maxlength="1000"><?php echo $arr['observaciones'] ?></textarea>
						<div class="form-div-ob"><span id="contador">0</span>/1000</div>
					</div>
				</div>
				<div class="botones">
					<div class="botones-submit">
						<button id="cancelar" type="reset">Cancelar</button>
						<input type="hidden" name="form1">
						<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
						<input type="hidden" name="tipo-declaracion" value="P">
						<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
						<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
						<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
						<button type="submit" id="guardar">Guardar</button>
					</div>
			</form>
					<div class="botones-nav">
						<form action="2-domicilio-declarante.php#02" method="POST">
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
				<form action="1-datos-generales.php#01" method="POST">
					<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
					<input type="hidden" name="tipo-declaracion" value="P">
					<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
					<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
					<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
					<button>Aceptar</button>
				</form>
		</div>
		<div id="popup5" class="popup" style="top:15%;">
			<a onclick="closeDialog('popup5');" class="close"><img src="<?php echo HTTP_PATH ?>/images/cerrar.png"></a>
			<div class="aviso">
				<?php echo get_notificacion(6,$conn);?>
			</div>
			<center>
			<div onclick="closeDialog('popup5');">
				<form id="form-conformidad">
					<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
					<input type="hidden" name="tipo-declaracion" value="P">
					<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
					<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
					<input type="hidden" name="conformidad" id="conformidad" value="N">
					<button type="button" id="conformidad_s" class="conformidad">Sí</button>
					<button type="button" id="conformidad_n" class="conformidad">No</button>
				</form>
			</div>
			</center>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>