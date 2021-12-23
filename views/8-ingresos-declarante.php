<!--
	31-08-2020
	DMQ-Qualsys
	Cambio para enviar si la declaración se debe presentar completa o no según la fecha efectiva
-->
<?php include("../include/header.php");?>
<script type="text/javascript">
	window.onload = function (){menuopc("08","menu3-1","menu1-2");}
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
		$arr=cargarform8($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		$html=checkform8($tipo_dec,$ejercicio,$_SESSION["rfc"],$conn);
		//print_r($arr);die;
		include("sidebar-patrimonial.php"); ?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#instrumento option[value="'+'<?php echo $arr["tipo_instrumento"] ?>'+'"]').attr('selected', 'selected');
				$('#tipo_bien option[value="'+'<?php echo $arr["tipo_bien"] ?>'+'"]').attr('selected', 'selected');
				tipo_instrumento();
				separador(document.getElementById("remunera_neta"));
				separador(document.getElementById("otros_ingresos"));
				separador(document.getElementById("activ_industrial"));
				separador(document.getElementById("activ_financiera"));
				separador(document.getElementById("serv_profesionales"));
				<?php
				if($tipo_dec=="M" || $tipo_dec=="C"){
				?>
				separador(document.getElementById("enajena_bienes"));
				<?php } ?>
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
			<form id="form-content" enctype="multipart/form-data">
			<?php 
			/* 21-08-2020 DMQ-Qualsys Configuración de acuerdo al puesto si no declara completo*/
			if($_POST["declara_completo"]=="P"){
			/* Fin de actualización */
			?>
				<div class="subtitle">6. INGRESOS NETOS DEL DECLARANTE, PAREJA Y/O DEPENDIENTES ECONÓMICOS</div>
			<?php
			}
			else{
				?>
				<div class="subtitle">8. INGRESOS NETOS DEL DECLARANTE, PAREJA Y/O DEPENDIENTES ECONÓMICOS</div>
			<?php
			}
			?>
			<div class="forms-container">
				<p class="not">Capturar cantidades libres de impuestos, sin comas, sin puntos, sin centavos y sin ceros a la izquierda.</p>
				<div class="aviso_pendientes"><?php echo $html; ?></div>
				<div style="width: 95%;">Subir declaración de ISR
					<input name="archivo" id="archivo" type="file" data-max-size="2048" accept="application/pdf" />
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-1">
					<?php
					if($tipo_dec=="I"){
					?>
					<label>I. Remuneración mensual neta del declarante por su cargo público (por concepto de sueldos, honorarios, compensaciones, bonos y otras prestaciones):</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="M"){
					?>
					<label>I. Remuneración anual neta del declarante por su cargo público (por concepto de sueldos, honorarios, compensaciones, bonos y otras prestaciones):</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="C"){
					?>
					<label>I. Remuneración neta del año en curso a la fecha de conclusión del empleo, cargo o comisión del declarante por su cargo público (por concepto de sueldos, honorarios, compensaciones, bonos y otras prestaciones):</label><span class="asterisk">*</span>
					<?php
					}
					?>
					<input type="text" placeholder="Remuneración mensual neta" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="remunera_neta" id="remunera_neta" value="<?php echo $arr['remunera_neta']; ?>" style="width:150px;" required>
				</div>
				<div class="form-div-1">
				<?php
				if($tipo_dec=="M" || $tipo_dec=="C"){
				?>
					<label>II. Otros ingresos mensuales del declarante (Suma del II.1 al II.5):</label>
				<?php
				}
				else{
				?>
					<label>II. Otros ingresos mensuales del declarante (Suma del II.1 al II.4):</label>
				<?php
				}
				?>
					<input type="text" placeholder="Otros ingresos" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="otros_ingresos" id="otros_ingresos" class="no_editable" value="<?php echo $arr['otros_ingresos'] ?>" style="width:150px;" required readonly>
				</div>
				<div class="form-div-1">
					<label>II.1.- Por actividad industrial, comercial y/o empresarial:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Total por actividad" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="activ_industrial" id="activ_industrial" value="<?php echo $arr['activ_industrial'] ?>" style="width:150px;" required>
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
					<label>II.2.- Por actividad financiera (rendimientos o ganancias):<span class="asterisk">*</span></label>
					<input type="text" placeholder="Financiera"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="activ_financiera" id="activ_financiera" value="<?php echo $arr['activ_financiera'] ?>" style="width:150px;" required>
				</div>
				<div class="form-div-3">
					<label>Tipo de instrumento que generó el rendimiento o ganancia:</label>
					<select id="instrumento" name="instrumento">
							<?php lista_valores("Tipo_Instrumento"); ?>
					</select>
				</div>
			</div>
			<div class="forms-container">
				<div style="display: none;" class="form-div-3" id="otro-ambito">
					<label>Especifique:</label>
					<input type="text" class="otro-regimen opcion-otro" name="otro_instrumento" maxlength="50" value="<?php echo $arr['otro_instrumento'] ?>">
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-1">
					<label>II.3.- Por servicios profesionales, consejos, consultorías y/o asesorías:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Servicios profesionales" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="serv_profesionales" id="serv_profesionales" value="<?php echo $arr['serv_profesionales'] ?>" style="width:150px;" required>
				</div>
				<div class="form-div-3">
					<label>Tipo de servicio prestado:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Servicio prestado" name="tipo_servicio" maxlength="50" value="<?php echo $arr['tipo_servicio'] ?>" required>
				</div>
				<?php
				if($tipo_dec=="M" || $tipo_dec=="C"){
				?>
				<div class="form-div-1">
					<label>II.4.- Por enajenación de bienes:</label><span class="asterisk">*</span>
					<input type="text" placeholder="Enajenación de bienes" name="enajena_bienes" id="enajena_bienes"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" value="<?php echo $arr['enajena_bienes'] ?>" style="width:150px;" required>
				</div>
				<div class="form-div-3">
					<label>Tipo de bien:</label>
					<select id="tipo_bien" name="tipo_bien">
							<?php lista_valores("Tipo_Bien"); ?>
					</select>
				</div>
				<?php
				}
				?>
				<!-- Enajenacion de bienes para conclusion y modificacion -->
				<div class="form-div-1">
					<?php
					if($tipo_dec=="M" || $tipo_dec=="C"){
					?>
					<label>II.5.- Otros ingresos no considerados a los anteriores:</label><span class="asterisk">*</span>
					<?php
					}
					else{
					?>
					<label>II.4.- Otros ingresos no considerados a los anteriores:</label><span class="asterisk">*</span>
					<?php	
					}
					?>
					<input type="text" placeholder="Otros ingresos" name="no_considerados" id="no_considerados" value="<?php echo $arr['no_considerados'] ?>" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" style="width:150px;" required>
				</div>
				<div class="form-div-2">
					<label>Tipo de ingreso (arrendamiento, regalía, sorteos, concursos, donaciones, seguros de vida, etc.):</label>
					<input type="text" placeholder="Tipo" name="tipo_ingreso" maxlength="30" value="<?php echo $arr['tipo_ingreso'] ?>">
				</div>
			</div>
			<div class="forms-container">
				<div class="form-div-2">
					<?php
					if($tipo_dec=="I"){
					?>
					<label>A. Ingreso mensual neto del declarante (Suma de numerales I y II):</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="M"){
					?>
					<label>A. Ingreso anual neto del declarante (Suma de numerales I y II):</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="C"){
					?>
					<label>A. Ingresos del declarante del año en curso a la fecha de conclusión del empleo, cargo o comisión (Suma de numerales I y II):</label><span class="asterisk">*</span>
					<?php
					}
					?>
					<input type="text" placeholder="Ingreso mensual"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="ingreso_neto" id="ingreso_neto" class="no_editable" value="<?php echo $arr['ingreso_neto'] ?>" style="width:150px;" required readonly>						
				</div>
				<div class="form-div-2">
					<?php
					if($tipo_dec=="I"){
					?>
					<label>B. Ingreso mensual neto de la pareja y/o dependientes económicos:</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="M"){
					?>
					<label>B. Ingreso anual neto de la pareja y/o dependientes económicos:</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="C"){
					?>
					<label>B. Ingresos del año en curso a la fecha de conclusión del empleo, cargo o comisión de la pareja y/o dependientes económicos:</label><span class="asterisk">*</span>
					<?php
					}
					?>
					<input type="text" placeholder="Pareja y/o dependientes"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="ingreso_pareja" id="ingreso_pareja" value="<?php echo $arr['ingreso_pareja'] ?>" style="width:150px;" required>
				</div>
				<div class="form-div-1">
					<?php
					if($tipo_dec=="I"){
					?>
					<label>C. Total de ingresos mensuales netos percibidos por el declarante, pareja y/o dependientes económicos (Suma de A y B):</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="M"){
					?>
					<label>C. Total de ingresos anuales netos percibidos por el declarante, pareja y/o dependientes económicos (Suma de A y B):</label><span class="asterisk">*</span>
					<?php
					}
					if($tipo_dec=="C"){
					?>
					<label>C. Total de ingresos netos del año en curso a la fecha de conclusión del empleo, cargo o comisión percibidos por el declarante, pareja y/o dependientes económicos (Suma de A y B):</label><span class="asterisk">*</span>
					<?php
					}
					?>
					<input type="text" placeholder="Total" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="ingreso_total" id="ingreso_total" class="no_editable" value="<?php echo $arr['total_ingresos'] ?>" style="width:150px;" required readonly>
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
						<input type="hidden" name="form8">
						<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
						<input type="hidden" name="tipo-declaracion" value="P">
						<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
						<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
						<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
						<button type="submit" id="guardar">Guardar</button>
					</div>
			</form>
					<div class="botones-nav">
						<?php 
						/* 21-08-2020 DMQ-Qualsys Configuración de acuerdo al puesto si no declara completo*/
						if($_POST["declara_completo"]=="P"){
						/* Fin de actualización */
							?>
							<form action="5-experiencia-laboral.php#05" method="POST" style="display: inline;">
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="P">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-anterior">Anterior</button>
							</form>
							<?php
							if($tipo_dec != 'M'){
								?>
								<form action="9-datos-servidor-anterior.php#09" method="POST" style="display: inline;">
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
							?>
							<?php
						}
						else{
							?>
							<form action="7-datos-dependiente.php#07" method="POST" style="display: inline;">
								<input type="hidden" name="declaracion" value="<?php echo $tipo_dec ?>">
								<input type="hidden" name="tipo-declaracion" value="P">
								<input type="hidden" name="ejercicio" value="<?php echo $ejercicio ?>">
								<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
								<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc']?>">
								<button class="seccion-anterior">Anterior</button>
							</form>
							<?php
							if($tipo_dec != 'M'){
							?>
							<form action="9-datos-servidor-anterior.php#09" method="POST" style="display: inline;">
							<?php
							} 
							else{
							?>
							<form action="10-bienes-inmuebles.php#10" method="POST" style="display: inline;">
							<?php
							}
							?>
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
				<form action="#08" method="POST">
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