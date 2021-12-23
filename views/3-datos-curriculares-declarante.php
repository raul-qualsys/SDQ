<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("03","menu3-1","menu1-2");}
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
		$arr=cargarform3($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform3($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$arr["html"]=$html;
		if($arr["total"]==0){
			$arr["secuencia"][0]=1;
			$arr["movimiento"][0]="";
			$arr["nivel_escolar"][0]="";
			$arr["institucion"][0]="";
			$arr["carrera"][0]="";
			$arr["estatus_estudio"][0]="";
			$arr["doc_obtenido"][0]="";
			$arr["fecha_doc"][0]="";
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
	    		$('#nivel_escolar option[value="'+'<?php echo $arr["nivel_escolar"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#estatus_estudio option[value="'+'<?php echo $arr["estatus_estudio"][0] ?>'+'"]').attr('selected', 'selected');
	    		$('#doc_obtenido option[value="'+'<?php echo $arr["doc_obtenido"][0] ?>'+'"]').attr('selected', 'selected');
			    var ubicacion=document.getElementsByName("ubicacion");
			    for (var i = ubicacion.length - 1; i >= 0; i--) {
			      if(ubicacion[i].value=='<?php echo $arr["ubicacion"][0]; ?>'){
			        ubicacion[i].checked="checked";
			      }
				}
				if('<?php echo $arr["movimiento"][0] ?>'=='B')$("#baja").prop("checked",true);
				else $("#baja").prop("checked",false);

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
			<form  id="form-content" enctype="multipart/form-data">
			<div class="subtitle">3. DATOS CURRICULARES DEL DECLARANTE</div>

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
					<label>Nivel:</label><span class="asterisk">*</span>
					<select id="nivel_escolar" name="nivel_escolar" required>
							<?php lista_valores("Nivel_Escolar"); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Institución Educativa:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Institución" name="institucion" id="institucion" maxlength="100" value="<?php echo $arr['institucion'][0]?>" required>
				</div>
				<div class="form-div-3" id="area-conocimiento">
					<label>Carrera o Área de Conocimiento:</label>
					<input type="text" placeholder="Carrera" name="carrera" id="carrera" maxlength="50" value="<?php echo $arr['carrera'][0]?>">
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-3">
					<label>Estatus:</label><span class="asterisk">*</span>
					<select id="estatus_estudio" name="estatus_estudio" required>
							<?php lista_valores("Estatus_Estudio"); ?>
					</select>
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-3">
					<label>Documento obtenido:</label><span class="asterisk">*</span>
					<select id="doc_obtenido" name="doc_obtenido" required>
							<?php lista_valores("Doc_Obtenido"); ?>
					</select>
				</div>
				<div class="form-div-3">
					<label>Documento:</label>
					<input name="archivo" id="archivo" type="file" value="../files/certificados/MEQD9103233A9_P_1.pdf" data-max-size="2048" accept="application/pdf" />
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-3">
					<label>Fecha de obtención del documento:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_doc" id="fecha_doc" style="width:80%; display: block;border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_doc'][0]?>" required>
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-1">
					<label>Lugar donde se ubica la institución educativa:</label><span class="asterisk">*</span>
					<input type="radio" name="ubicacion" value="M" checked required>EN MÉXICO
					<input type="radio" name="ubicacion" value="E">EN EL EXTRANJERO
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
						<input type="hidden" name="form3">
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
						<form action="2-domicilio-declarante.php#02" method="POST" style="display: inline;">
							<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
							<input type="hidden" name="tipo-declaracion" value="P">
							<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
							<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
							<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
							<button class="seccion-anterior">Anterior</button>
						</form>
						<form action="4-datos-empleo.php#04" method="POST" style="display: inline;">
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
				<form action="#03" method="POST">
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