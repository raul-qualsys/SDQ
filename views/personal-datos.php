<?php include("../include/header.php");?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php 
		include("../controllers/permiso-rh.php"); 
		if(isset($_POST["rfc-datos"])){
			$datos=datos_empleado($_POST["rfc-datos"],$conn);
		}
		else{
			header("Location:../menurecursos.php");
		}
		include("sidebar-rh.php");//print_r($datos); 
		?>
		<script type="text/javascript">
	  		$(document).ready(function(){
	    		$('#tipo_empleado option[value="'+'<?php echo $datos["tipo_empleado"] ?>'+'"]').attr('selected', 'selected');
	    		$('#edo_civil option[value="'+'<?php echo $datos["ecivil"] ?>'+'"]').attr('selected', 'selected');
	    		$('#sexo option[value="'+'<?php echo $datos["sexo"] ?>'+'"]').attr('selected', 'selected');
	    		$('#pais option[value="'+'<?php echo $datos["pais"] ?>'+'"]').attr('selected', 'selected');
	    		$('#nacionalidad option[value="'+'<?php echo $datos["nacionalidad"] ?>'+'"]').attr('selected', 'selected');
	    		$('#estado option[value="'+'<?php echo $datos["estado"] ?>'+'"]').attr('selected', 'selected');
	    		lista_municipios("<?php echo $datos["municipio"] ?>");
	    		tipo_ubicacion();
	    	});
		</script>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">Datos Generales</div>
			</div>
			<form action="personal-datos.php" method="POST" style="width: 30%;display: inline-block;">
					<input type="hidden" name="rfc-datos" value="<?php echo $_POST["rfc-empleo"] ?>">
					<button type="submit" class="tab-disabled" disabled>Datos generales</button>
			</form>
			<form action="personal-empleo.php" method="POST" style="width: 30%;display: inline-block;">
					<input type="hidden" name="rfc-empleo" value="<?php echo $_POST["rfc-datos"] ?>">
					<button type="submit" class="tab-enabled">Datos de empleo</button>
			</form>
			<form id="form-user-data" method="POST">
				<div class="forms-rh">
					<table class="container-table">
						<tr>
							<td class="label-table">Nombre(s)</td>
							<td class="input-table"><input type="text" placeholder="Nombre" name="nombre" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" maxlength="30" value="<?php echo $datos["nombre"] ?>"></td>
						</tr>
						<tr>
							<td class="label-table">Primer apellido</td>
							<td  class="input-table"><input type="text" placeholder="Primer Apellido" name="apaterno" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" maxlength="30" value="<?php echo $datos["apellido1"] ?>"></td>
						</tr>
						<tr>
							<td class="label-table">Segundo apellido</td>
							<td  class="input-table"><input type="text" placeholder="Segundo Apellido" name="amaterno" onkeypress="valida(event,'upper')" onpaste="valida(event,'upper')" maxlength="30" value="<?php echo $datos["apellido2"] ?>"></td>
						</tr>
						<tr>
							<td class="label-table">RFC con Homoclave</td>
							<td>
								<input type="text" placeholder="RFC" name="rfc_nuevo" id="rfc_declarante" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" maxlength="13" value="<?php echo $datos["rfc"] ?>" required>
								<div class="aviso_pendientes" id="aviso_rfc_declarante"></div>
							</td>
						</tr>
						<tr>
							<td class="label-table">CURP</td>
							<td>
								<input type="text" placeholder="CURP" name="curp" id="curp" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" maxlength="18" value="<?php echo $datos["curp"] ?>">
								<div class="aviso_pendientes" id="aviso_curp"></div>
							</td>
						</tr>
						<tr>
							<td class="label-table">No. de Empleado</td>
							<td class="input-table"><input type="text" placeholder="No. de empleado" id="emp_num" name="emp_num" onkeypress="valida(event,'clave')" onpaste="valida(event,'clave')" value="<?php echo $datos["emp_num"] ?>" maxlength="12"></td>
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
							<td class="label-table">Correo electrónico institucional</td>
							<td class="input-table">
								<input type="text" placeholder="Correo institucional" name="email_trabajo" maxlength="50" value="<?php echo $datos["correo1"] ?>">
							</td>
						</tr>
						<tr>
							<td class="label-table">Correo electrónico personal</td>
							<td class="input-table">
								<input type="text" placeholder="Correo personal" name="email" maxlength="50" value="<?php echo $datos["correo2"] ?>">
							</td>
						</tr>
						<tr>
							<td class="label-table">Teléfono de casa</td>
							<td class="input-table">
								<input type="text" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" placeholder="Teléfono" name="tel_casa" minlength="10" maxlength="10" value="<?php echo $datos["tel1"] ?>">
							</td>
						</tr>
						<tr>
							<td class="label-table">Número celular personal</td>
							<td class="input-table">
								<input type="text" onkeypress="valida(event,'int')" onpaste="valida(event,'int')" placeholder="Celular" name="tel_celular" minlength="10" maxlength="10" value="<?php echo $datos["tel2"] ?>">
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Estado civil</label></td>
							<td class="input-table">
								<select id="edo_civil" name="edo_civil" onchange="seleccionar();" value="<?php echo $datos["ecivil"] ?>">
									<?php lista_valores("Estado Civil"); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Sexo</label></td>
							<td class="input-table">
								<select id="sexo" name="sexo" onchange="seleccionar();" value="<?php echo $datos["sexo"] ?>">
									<option value="">::</option>
									<option value="H">Hombre</option>
									<option value="M">Mujer</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Fecha de nacimiento</label><span class="asterisk">*</span></td>
							<td class="input-table">
								<input type="date" name="fecha_nac" style="width:40%; display: block;border-radius:5px; background-color: #F2F2F2; font-family:contenidos;"  value="<?php echo $datos["fecha_nac"] ?>" required>
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Fecha de contratación</label></td>
							<td class="input-table">
								<input type="date" name="fecha_contratacion" id="fecha_ingreso" style="width:40%; display: block;border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" value="<?php echo $datos["fecha_contra"] ?>" max="<?php echo date('Y-m-d'); ?>">
							</td>
						</tr>
						<tr>
							<td class="label-table"><label>Fecha de baja</label></td>
							<td class="input-table">
								<input type="date" name="fecha_baja" id="fecha_egreso" style="width:40%; display: block;border-radius:5px; background-color: #F2F2F2; font-family:contenidos;" value="<?php echo $datos["fecha_baja"] ?>" max="<?php echo date('Y-m-d'); ?>">
								<label id="aviso_fecha">La fecha de baja debe ser mayor a la fecha de contrtación.</label>
							</td>
						</tr>
						<tr>
							<td class="label-table">País</td>
							<td class="input-table">
								<select name="pais" id="pais" value="<?php echo $datos["pais"] ?>">
									<?php paises(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Nacionalidad</td>
							<td class="input-table">
								<select name="nacionalidad" id="nacionalidad" value="<?php echo $datos["nacionalidad"] ?>">
									<?php nacionalidades(); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Código postal</td>
							<td class="input-table">
						<input type="text" style="width:30%;display: inline-block;" placeholder="Código Postal" name="cp" id="cp" value="<?php echo $datos["codigopostal"] ?>" maxlength="5"><img src="../images/lupa.png" id="buscar-direccion" ></img>
						<span id="msj-cp" style="font-weight: bold;color: red;"></span>
							</td>
						</tr>
						<tr>
							<td class="label-table">Entidad Federativa</td>
							<td class="input-table">
								<div id="estado-div">
									<select name="estado" id="estado" value="<?php echo $datos["estado"] ?>">
										<?php entidades(); ?>
									</select>
								</div>
								<div id="estado2-div" style="display: none;">
									<input type="text" placeholder="Estado" name="estado2" id="estado2" value="<?php echo $datos["estado_descr"] ?>" maxlength="50">
								</div>
								</td>
						</tr>
						<tr id="municipio-tr">								
							<td class="label-table"><div>Municipio/Alcaldía</div></td>
							<td class="input-table">
								<select name="municipio" id="municipio" value="<?php echo $datos["municipio"] ?>">
								</select>
							</td>
						</tr>
						<tr>
							<td class="label-table">Colonia/Localidad</td>
							<td class="input-table">
								<div id="colonia-div">
									<input type="text" placeholder="Localidad" name="colonia" id="colonia" value="<?php echo $datos["colonia_descr"] ?>" maxlength="100">
								</div>
								<div id="colonia2-div" style="display: none;">
									<select name="colonia2" id="colonia-select" value="<?php echo $datos["colonia"] ?>">
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="label-table">Calle</td>
							<td class="input-table"><input type="text" placeholder="Calle" name="calle" value="<?php echo $datos["calle"] ?>" maxlength="50"></td>
						</tr>
						<tr>
							<td class="label-table">No. exterior</td>
							<td  class="input-table"><input type="text" placeholder="No. exterior" name="noext" value="<?php echo $datos["num_exterior"] ?>" maxlength="30"></td>
						</tr>
						<tr>
							<td class="label-table">No. interior</td>
							<td class="input-table">
								<input type="text" placeholder="No. interior" name="noint" value="<?php echo $datos["num_interior"] ?>" maxlength="30">
							</td>
						</tr>
					</table>
				</div>
<!-- 				<div class="user-photo botones-usuario">
					<img src="<?php echo HTTP_PATH ?>/css/icons/usuario-masculino.png">
					<button type="button" id="cambiar-foto">Cambiar foto</button>
				</div> -->
				<div class="botones">
					<div class="botones-submit">
						<a href="<?php echo HTTP_PATH ?>/views/admin-personal.php" style="text-decoration: none;">
							<button id="cancelar" type="button">Cancelar</button>
						</a>
						<input type="hidden" name="rfc" value="<?php echo $datos["rfc"] ?>">
						<button type="submit" id="guardar-usuario" class="guardar">Guardar</button>
					</div>
				</div>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				
				<?php 
				if(isset($_POST["rfc-datos"])){
				?>
					<input type="hidden" name="rfc-datos" value="<?php echo $_POST["rfc-datos"] ?>">
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