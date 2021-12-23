<?php include("../include/header.php");?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php 
		include("../controllers/permiso-rh.php"); 
		if(isset($_POST["rfc-empleo"])){
			$datos=datos_empleo($_POST["rfc-empleo"],$conn);
		}
		else{
			header("Location:../menurecursos.php");
		}
		include("sidebar-rh.php"); 
		?>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#nivel_gobierno option[value="'+'<?php echo $datos["orden_id"] ?>'+'"]').attr('selected', 'selected');
	    		$('#ambito_publico option[value="'+'<?php echo $datos["ambito_id"] ?>'+'"]').attr('selected', 'selected');
				$('#ente option[value="'+'<?php echo $datos["dependencia"] ?>'+'"]').attr('selected', 'selected');
	    		$('#area1 option[value="'+'<?php echo $datos["area_adscripcion"] ?>'+'"]').attr('selected', 'selected');
				$('#puesto1 option[value="'+'<?php echo $datos["id_puesto"] ?>'+'"]').attr('selected', 'selected');
	    	});
		</script>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Datos de Empleo</div>
			</div>
			<form action="personal-datos.php" method="POST" style="width: 30%;display: inline-block;">
					<input type="hidden" name="rfc-datos" value="<?php echo $_POST["rfc-empleo"] ?>">
					<button type="submit" class="tab-enabled">Datos generales</button>
			</form>
			<form action="personal-empleo.php" method="POST" style="width: 30%;display: inline-block;">
					<input type="hidden" name="rfc-empleo" value="<?php echo $_POST["rfc-datos"] ?>">
					<button type="submit" class="tab-disabled" disabled>Datos de empleo</button>
			</form>
			<form id="form-user-data" method="POST">
				<div class="forms-rh">
					<table class="container-table">
						<tr>
							<td class="label-table">Nivel/Orden de Gobierno<span class="asterisk">*</span></td>
							<td class="input-table">
								<select id="nivel_gobierno" name="nivel_gobierno" required>
										<?php lista_valores("Orden_ID"); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Ámbito público<span class="asterisk">*</span></td>
							<td  class="input-table">
								<select id="ambito_publico" name="ambito_publico" required>
										<?php lista_valores("Ambito_ID"); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Nombre del Ente Público<span class="asterisk">*</span></td>
							<td class="input-table">
								<select name="ente" id="ente" style="font-size:12px;" required>
									<?php lista_dependencias(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Área de Adscripción</td>
							<td class="input-table">
								<select name="area" id="area1">
									<?php lista_areas(); ?>
								</select>
<!-- 								<input type="text" name="area2" id="area2" placeholder="Área" value="<?php ?>"> -->
							</td>
						</tr>
						<tr>
							<td class="label-table">Empleo, Cargo o Comisión</td>
							<td class="input-table">
								<select name="puesto" id="puesto1">
									<?php lista_puestos(); ?>
								</select>
<!-- 								<input type="text" name="puesto2" id="puesto2" placeholder="Puesto" value="<?php ?>"> -->
							</td>
						</tr>
						<tr>
							<td class="label-table">Nivel de Empleo, Cargo o Comisión</td>
							<td class="input-table">
								<input type="text" name="nivel_empleo" id="nivel" placeholder="Nivel del empleo" maxlength="10" value="<?php echo $datos['nivel_empleo']?>">
							</td>
						</tr>
<!-- 						<tr>
							<td class="label-table">¿Está contratado por honorarios?<span class="asterisk">*</span></td>
							<td class="input-table">
								<input type="radio" style="width:10%;" name="honorarios" value="S" checked>SÍ<br>
								<input type="radio" style="width:10%;" name="honorarios" value="N">NO
							</td>
						</tr> -->
						<tr>
							<td class="label-table">Fecha de inicio en el puesto</td>
							<td class="input-table">
								<input type="date" name="fecha_empleo" id="fecha_empleo" value="<?php echo $datos['fecha_inicio'] ?>" style="width:40%; display: block; border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>">
							</td>
						</tr>
						<tr>
							<td class="label-table">Especifique función principal</td>
							<td class="input-table">
								<input type="text" name="funcion_principal" placeholder="Función principal" id="funcion" maxlength="100" value="<?php echo $datos['funcion_principal'] ?>">					
							</td>
						</tr>
						<tr>
							<td class="label-table">Teléfono de oficina</td>
							<td class="input-table">
								<input type="text" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" name="telefono" placeholder="Teléfono" minlength="10" maxlength="10" value="<?php echo $datos['tel_oficina'] ?>" id="tel_oficina">
							</td>
						</tr>
						<tr>
							<td class="label-table">Extensión</td>
							<td class="input-table">
								<input type="text" name="extension" id="extension" placeholder="ext." value="<?php echo $datos['extension'] ?>" style="width:20%;" maxlength="15">
							</td>
						</tr>
					</table>
				</div>
<!-- 				<div class="user-photo botones-usuario">
					<img src="<?php echo HTTP_PATH ?>/css/icons/usuario-masculino.png">
				</div> -->
				<div class="botones">
					<div class="botones-submit">
						<a href="<?php echo HTTP_PATH ?>/views/admin-personal.php" style="text-decoration: none;">
							<button id="cancelar" type="button">Cancelar</button>
						</a>
						<input type="hidden" name="rfc" value="<?php echo $_POST["rfc-empleo"] ?>">
						<button type="submit" id="guardar-usuario" class="guardar">Guardar</button>
					</div>
				</div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				
				<?php 
				if(isset($_POST["rfc-empleo"])){
				?>
					<input type="hidden" name="rfc-empleo" value="<?php echo $_POST["rfc-empleo"] ?>">
				<?php
				}
				?>
				<button type="button" class="quitar-aviso">Aceptar</button>
		</div>
		</form>

	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>