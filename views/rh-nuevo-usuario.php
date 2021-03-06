<?php include("../include/header.php"); ?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-rh.php"); 
 		include("sidebar-rh.php"); 
		?>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">NUEVO EMPLEADO</div>
			</div>
			<div style="width: 30%;display: inline-block;">
				<button class="tab-disabled" id="boton-personal">Datos generales</button>
			</div>
			<div style="width: 30%;display: inline-block;">
				<button class="tab-enabled" id="boton-empleo">Datos de empleo</button>
			</div>
			<div class="forms-container">
			<form id="nuevo-usuario">
				<div class="forms-registro">
					<div class="datos-personal">
					<table class="container-table">
						<tr>
							<td class="label-table">Nombre(s)<span class="asterisk">*</span></td>
							<td class="input-table"><input type="text" placeholder="Nombre" name="nombre" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" maxlength="30" required></td>
						</tr>
					</div>
						<tr>
							<td class="label-table">Primer apellido<span class="asterisk">*</span></td>
							<td  class="input-table"><input type="text" placeholder="Primer apellido" name="apellido1" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" maxlength="30" required></td>
						</tr>
						<tr>
							<td class="label-table">Segundo apellido</td>
							<td class="input-table">
								<input type="text" placeholder="Segundo apellido" name="apellido2" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" maxlength="30">
							</td>
						</tr>
						<tr>
							<td class="label-table">RFC con Homoclave<span class="asterisk">*</span></td>
							<td>
								<input type="text" placeholder="RFC" name="rfc" id="rfc_empleado" minlength="10" maxlength="10" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" style="width:25%;display: inline" required>
								-
								<input type="text" placeholder="HC" name="homoclave" id="hc_empleado" minlength="3" maxlength="3" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" style="width: 10%;display: inline;" required>
								<div class="aviso_pendientes" id="aviso_rfc"></div>
							</td>
						</tr>
						<tr>
							<td class="label-table">CURP<span class="asterisk">*</span></td>
							<td class="input-table">
								<input type="text" placeholder="CURP" name="curp" id="curp" minlength="18" maxlength="18" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" required>
								<div class="aviso_pendientes" id="aviso_curp"></div>
							</td>
						</tr>
						<tr>
							<td class="label-table">No. de Empleado</td>
							<td class="input-table"><input type="text" placeholder="No. de empleado" id="emp_num" name="emp_num" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" maxlength="12"></td>
						</tr>
						<tr>
							<td class="label-table">Tipo de Empleado</td>
							<td class="input-table">
								<select id="tipo_empleado" name="tipo_empleado">
									<option value="">::</option>
									<option value="E">Empleado</option>
									<option value="O">Outsourcing</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Correo electr??nico institucional</td>
							<td class="input-table">
								<input type="text" placeholder="Correo institucional" name="email_trabajo" maxlength="50">
							</td>
						</tr>
						<tr>
							<td class="label-table">Correo electr??nico personal<span class="asterisk">*</span></td>
							<td class="input-table">
								<input type="text" placeholder="Correo personal" name="email" id="email1" maxlength="50" required>
							</td>
						</tr>
						<tr>
							<td class="label-table">Confirma el correo personal<span class="asterisk">*</span></td>
							<td class="input-table">
								<input type="text" placeholder="Confirmar correo" name="email2" id="email2" maxlength="50" required><span id="confirmar_correo" style="display: none;color: red;font-weight: bold;">Los correos no son iguales.</span>
							</td>
						</tr>
						<tr>
							<td class="label-table">Tel??fono de casa</td>
							<td class="input-table">
								<input type="text" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" placeholder="Celular" name="tel_casa" minlength="10" maxlength="10">
							</td>
						</tr>
						<tr>
							<td class="label-table">N??mero celular personal</td>
							<td class="input-table">
								<input type="text" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" placeholder="Celular" name="tel_celular" minlength="10" maxlength="10">
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Estado civil</label></td>
							<td class="input-table">
								<select id="edo_civil" name="edo_civil" onchange="seleccionar();">
									<?php lista_valores("Estado Civil"); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Sexo</label></td>
							<td class="input-table">
								<select id="sexo" name="sexo" onchange="seleccionar();">
									<option value="">::</option>
									<option value="H">Hombre</option>
									<option value="M">Mujer</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Fecha de nacimiento</label><span class="asterisk">*</span></td>
							<td class="input-table">
								<input type="date" name="fecha_nac" style="width:20%; display: block;border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>" required>
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Fecha de contrataci??n</label></td>
							<td class="input-table">
								<input type="date" name="fecha_contratacion" style="width:20%; display: block;border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" max="<?php echo date('Y-m-d'); ?>">
							</td>
						</tr>
						<tr>
							<td class="label-table">Contrase??a<span class="asterisk">*</span></td>
							<td class="input-table">
								<input type="text" placeholder="Contrase??a" name="password" id="password" style="display: inline;" required><button style="display: inline-block;width: auto;" type="button" id="generar_pass">Generar contrase??a</button>
							</td>
						</tr>
<!-- 						<tr>
							<td class="label-table">Confirmar contrase??a<span class="asterisk">*</span></td>
							<td>
								<input type="password" placeholder="Contrase??a" name="password2" id="password2" required>
							</td>
						</tr> -->
						<tr>
							<td class="label-table">Pa??s</td>
							<td class="input-table">
								<select name="pais" id="pais">
									<?php paises(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Nacionalidad</td>
							<td class="input-table">
								<select name="nacionalidad" id="nacionalidad">
									<?php nacionalidades(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">C??digo postal</td>
							<td class="input-table">
							<input type="text" style="width:30%;display: inline-block;" placeholder="C??digo Postal" name="cp" id="cp" maxlength="5"><img src="../images/lupa.png" id="buscar-direccion"></img>
							<span id="msj-cp" style="font-weight: bold;color: red;"></span>
							</td>
						</tr>
						<tr>
							<td class="label-table">Entidad Federativa</td>
							<td class="input-table">
								<div id="estado-div">
									<select name="estado" id="estado">
										<?php entidades(); ?>
									</select>
								</div>
								<div id="estado2-div" style="display: none;">
									<input type="text" placeholder="Estado" name="estado2" id="estado2" maxlength="50">
								</div>
								</td>
						</tr>
						<tr id="municipio-tr">								
							<td class="label-table"><div>Municipio/Alcald??a</div></td>
							<td class="input-table">
								<select name="municipio" id="municipio">
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Colonia/Localidad</td>
							<td class="input-table">
								<div id="colonia-div">
									<input type="text" placeholder="Localidad" name="colonia" id="colonia" maxlength="100">
								</div>
								<div id="colonia2-div" style="display: none;">
									<select name="colonia2" id="colonia-select">
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="label-table">Calle</td>
							<td class="input-table"><input type="text" placeholder="Calle" name="calle" maxlength="50"></td>
						</tr>
						<tr>
							<td class="label-table">No. exterior</td>
							<td  class="input-table"><input type="text" placeholder="No. exterior" name="noext" maxlength="30"></td>
						</tr>
						<tr>
							<td class="label-table">No. interior</td>
							<td class="input-table">
								<input type="text" placeholder="No. interior" name="noint" maxlength="30">
							</td>
						</tr>
					</table>
					</div>
					<div style="display: none;" class="datos-empleo">
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
							<td class="label-table">??mbito p??blico<span class="asterisk">*</span></td>
							<td  class="input-table">
								<select id="ambito_publico" name="ambito_publico" required>
										<?php lista_valores("Ambito_ID"); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Nombre del Ente P??blico<span class="asterisk">*</span></td>
							<td class="input-table">
								<select name="ente" id="ente" style="font-size:12px;" required>
									<?php lista_dependencias(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">??rea de Adscripci??n</td>
							<td class="input-table">
								<select name="area" id="area1">
									<?php lista_areas(); ?>
								</select>
<!-- 								<input type="text" name="area2" id="area2" placeholder="??rea" value="<?php ?>"> -->
							</td>
						</tr>
						<tr>
							<td class="label-table">Empleo, Cargo o Comisi??n</td>
							<td class="input-table">
								<select name="puesto" id="puesto1">
									<?php lista_puestos(); ?>
								</select>
<!-- 								<input type="text" name="puesto2" id="puesto2" placeholder="Puesto" value="<?php ?>"> -->
							</td>
						</tr>
						<tr>
							<td class="label-table">Nivel de Empleo, Cargo o Comisi??n</td>
							<td class="input-table">
								<input type="text" name="nivel_empleo" id="nivel" maxlength="10" placeholder="Nivel del empleo">
							</td>
						</tr>
						<tr>
							<td class="label-table">Especifique funci??n principal</td>
							<td class="input-table">
								<input type="text" name="funcion_principal" placeholder="Funci??n principal" id="funcion" maxlength="100">
							</td>
						</tr>
						<tr>
							<td class="label-table">Tel??fono de oficina</td>
							<td class="input-table">
								<input type="text"  onkeypress="valida(event,'int')" onpaste="valida(event,'int')"  name="telefono" placeholder="Tel??fono" minlength="10" maxlength="10" id="tel_oficina">
							</td>
						</tr>
						<tr>
							<td class="label-table">Extensi??n</td>
							<td class="input-table">
								<input type="text" name="extension" id="extension" placeholder="ext." style="width:20%;" maxlength="15">
							</td>
						</tr>
					</table>
					</div>
				</div>
				<div class="botones">
					<div class="botones-submit">
						<a href="<?php echo HTTP_PATH ?>/views/admin-personal.php" style="text-decoration: none;">
							<button id="cancelar" type="button">Cancelar</button>
						</a>
						<input type="hidden" name="user" value="<?php echo $_SESSION['rfc']; ?>">
						<input type="hidden" name="perfil" value="RH">
						<button type="submit" id="guardar-user" class="guardar">Guardar</button>
					</div>
<!-- 					<div class="botones-nav">
						<button class="seccion-anterior datos-empleo">Anterior</button>
						<button class="seccion-siguiente datos-personal">Siguiente</button>
					</div> -->
				</div>
			</form>
			</div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<button class="quitar-aviso">Aceptar</button>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>