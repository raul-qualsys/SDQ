<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("09","menu3-1","menu1-2");}
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
		$arr=cargarform9($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform9($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		//print_r($arr);die;
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#instrumento option[value="'+'<?php echo $arr["tipo_instrumento"] ?>'+'"]').attr('selected', 'selected');
				$('#tipo_bien option[value="'+'<?php echo $arr["tipo_bien"] ?>'+'"]').attr('selected', 'selected');
			    var servidor=document.getElementsByName("servidor");
			    for (var i = servidor.length - 1; i >= 0; i--) {
			      if(servidor[i].value=='<?php echo $arr["servidor_anio_prev"]; ?>'){
			        servidor[i].checked="checked";
			        if(servidor[i].value=="S"){$(".servidor-content").show();}
			        if(servidor[i].value=="N"){$(".servidor-content").hide();}
			      }
				}
				tipo_instrumento();
				separador(document.getElementById("remunera_neta"));
				separador(document.getElementById("otros_ingresos"));
				separador(document.getElementById("activ_industrial"));
				separador(document.getElementById("activ_financiera"));
				separador(document.getElementById("serv_profesionales"));
				separador(document.getElementById("enajena_bienes"));
				separador(document.getElementById("no_considerados"));
				separador(document.getElementById("ingreso_neto"));
				separador(document.getElementById("ingreso_pareja"));
				separador(document.getElementById("ingreso_total"));
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
			/* 21-08-2020 DMQ-Qualsys Configuración de acuerdo al puesto si no declara completo*/
			if($_POST["declara_completo"]=="P"){
			/* Fin de actualización */
			?>
			<div class="subtitle">7. ¿TE DESEMPEÑASTE COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR?
			<?php 
			}
			else{
			?>
			<div class="subtitle">9. ¿TE DESEMPEÑASTE COMO SERVIDOR PÚBLICO EN EL AÑO INMEDIATO ANTERIOR?
			<?php
			}
			?>
				<span class="asterisk">*</span>
					<input type="radio" name="servidor" id="servidor_1" class="servidor" value="S" required checked>Sí
					<input type="radio" name="servidor" id="servidor_2" class="servidor" value="N">No
			</div>
			<div class="forms-container servidor-content">
				<p class="not">Capturar cantidades libres de impuestos, sin comas, sin puntos, sin centavos y sin ceros a la izquierda.</p>
				<div class="aviso_pendientes"><?php echo $html; ?></div>
				<div class="form-div-2">
					<label>Fecha de inicio:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_inicio" id="fecha_ingreso" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_inicio']; ?>">
				</div>
				<div class="form-div-2">
					<label>Fecha de conclusión:</label><span class="asterisk">*</span>
					<input type="date" name="fecha_fin" id="fecha_egreso" style="width:80%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $arr['fecha_fin']; ?>"><label id="aviso_fecha">La fecha de conclusión debe ser mayor a la fecha de inicio.</label>
				</div>
				<div class="subsubtitle">
					<label>Ingresos netos recibidos durante el tiempo en el que se desempeñó como servidor público en el año inmediato anterior</label>
				</div>
				<div class="form-div-1">
					<label>I.- Remuneración neta del declarante recibida durante el tiempo en el que se desempeñó como servidor público en el año inmediato anterior (por concepto de sueldo, honorarios, compensaciones, bonos, aguinaldos y otras prestaciones) después de impuestos:</label><span class="asterisk">*</span>
						<input type="text" placeholder="Remuneración neta"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="remunera_neta" id="remunera_neta" value="<?php echo $arr['remunera_neta']; ?>" style="width:150px;">
				</div>
				<div class="form-div-1">
					<label>II.- Otros ingresos del declarante, recibidos durante el tiempo en el que se desempeñó como servidor público en el año inmediato anterior:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Otros ingresos" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="otros_ingresos" id="otros_ingresos" class="no_editable" value="<?php echo $arr['otros_ingresos'] ?>" style="width:150px;" readonly>
				</div>
				<div class="form-div-1">
					<label>II.1.- Por actividad industrial, comercial y/o empresarial:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Total por actividad" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="activ_industrial" id="activ_industrial" value="<?php echo $arr['activ_industrial'] ?>" style="width:150px;">
				</div>
				<div class="form-div-3">
					<label>Nombre o razón social:</label>
					<input type="text" placeholder="Nombre" name="razon_social" maxlength="100" value="<?php echo $arr['razon_social'] ?>">
				</div>
				<div class="form-div-3">
					<label>Tipo de negocio:</label>
					<input type="text" placeholder="Tipo de negocio" name="tipo_neg" maxlength="50" value="<?php echo $arr['tipo_negocio'] ?>">
				</div>
				<div class="form-div-1">
					<label>II.2.- Por actividad financiera (rendimientos o ganancias):</label><span class="asterisk">*</span>
					<input type="text" placeholder="Financiera"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="activ_financiera" id="activ_financiera" value="<?php echo $arr['activ_financiera'] ?>" style="width:150px;">
				</div>
				<div class="form-div-3">
					<label>Tipo de instrumento que generó el rendimiento o ganancia:</label>
					<select id="instrumento" name="instrumento">
							<?php lista_valores("Tipo_Instrumento"); ?>
					</select>
				</div>
			</div>
			<div class="forms-container servidor-content">
				<div style="display: none;" class="form-div-3" id="otro-ambito">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otro_instrumento" maxlength="50" value="<?php echo $arr['otro_instrumento'] ?>">
				</div>
			</div>
			<div class="forms-container servidor-content">
				<div class="form-div-1">
					<label>II.3.- Por servicios profesionales, consejos, consultorías y/o asesorías:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Servicios profesionales" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="serv_profesionales" id="serv_profesionales" value="<?php echo $arr['serv_profesionales'] ?>" style="width:150px;">	
				</div>
				<div class="form-div-3">
					<label>Tipo de servicio prestado:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Servicio prestado" name="tipo_servicio" maxlength="50" value="<?php echo $arr['tipo_servicio'] ?>">				
				</div>
				<div class="form-div-1">
					<label>II.4.- Por enajenación de bienes:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Enajenación de bienes" name="enajena_bienes" id="enajena_bienes"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['enajena_bienes'] ?>" style="width:150px;">
				</div>
				<div class="form-div-3 ">
					<label>Tipo de bien enajenado:</label>
					<select id="tipo_bien" name="tipo_bien">
							<?php lista_valores("Tipo_Bien"); ?>
					</select>
				</div>
				<div class="form-div-1">
					<label>II.5.- Otros ingresos no considerados a los anteriores:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Otros ingresos" name="no_considerados" id="no_considerados" value="<?php echo $arr['no_considerados'] ?>"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')"  style="width:150px;">
				</div>
				<div class="form-div-2">
					<label>Tipo de ingreso (arrendamiento, regalía, sorteos, concursos, donaciones, seguros de vida, etc.):</label>
					<input type="text" placeholder="Tipo" name="tipo_ingreso" maxlength="30" value="<?php echo $arr['tipo_ingreso'] ?>">
				</div>
			</div>
			<div class="forms-container servidor-content">
				<div class="form-div-2">
					<label>A. Ingreso mensual neto del declarante (Suma de numerales I y II):</label><span class="asterisk">*</span>
					<input type="text" placeholder="Ingreso neto"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="ingreso_neto" id="ingreso_neto" class="no_editable" value="<?php echo $arr['ingreso_neto'] ?>" style="width:150px;" readonly>
				</div>
				<div class="form-div-2">
					<label>B. Ingreso mensual neto de la pareja y/o dependientes económicos:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Pareja y/o dependientes"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="ingreso_pareja" id="ingreso_pareja" value="<?php echo $arr['ingreso_pareja'] ?>" style="width:150px;">
				</div>
				<div class="form-div-1">
					<label>C. Total de ingresos mensuales netos percibidos por el declarante, pareja y/o dependientes económicos (Suma de A y B):</label><span class="asterisk">*</span>
					<input type="text" placeholder="Total" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="ingreso_total" id="ingreso_total" class="no_editable" value="<?php echo $arr['total_ingresos'] ?>" style="width:150px;" readonly>
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-1">
					<label>Observaciones:</label><br>
					<textarea name="observaciones" id="observaciones" rows="10" maxlength="1000" ><?php echo $arr['observaciones'] ?></textarea>
					<div class="form-div-ob"><span id="contador">0</span>/1000</div>
				</div>
			</div>
				<div class="botones">
					<div class="botones-submit">
						<button id="cancelar" type="reset">Cancelar</button>				
						<input type="hidden" name="form9">
						<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
						<input type="hidden" name="tipo-declaracion" value="P">
						<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
						<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
						<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
						<button type="submit" id="guardar">Guardar</button>
					</div>
			</form>
					<div class="botones-nav">
						<form action="8-ingresos-declarante.php#08" method="POST" style="display: inline;">
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
							<form action="finalizar-declaracion.php" method="POST" style="display: inline;">
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="P">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-siguiente">Finalizar</button>
							</form>
						<?php 
						}
						else{
						?>
							<form action="10-bienes-inmuebles.php#10" method="POST" style="display: inline;">
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
				<form action="#09" method="POST">
					<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
					<input type="hidden" name="tipo-declaracion" value="P">
					<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
					<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
					<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
				<!-- 	<button class="seccion-siguiente">Siguiente</button> -->
					<button>Aceptar</button>
				</form>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>