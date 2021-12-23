<!--
Fecha:
Versión:
Autor:
Descripción:
-->
<?php include("../include/header.php");
	clearstatcache();
?>
<body>
	<?php include("../include/header-buttons.php");?>
	<div id="main-content">
		<?php
		include("../controllers/permiso-ti.php"); 
		include("sidebar-ti.php"); 
		$query="SELECT * FROM qsy_instalacion WHERE dependencia=1";
		$result=pg_query($conn,$query);
		$val=pg_fetch_assoc($result);
		//print_r($val);
		if(!$val){
			$val["nombre"]='';
			$val["codigopostal"]='';
			$val["estado"]='';
			$val["municipio"]='';
			$val["colonia"]='';
			$val["contacto1"]='';
			$val["telefono1"]='';
			$val["extension1"]='';
			$val["contacto2"]='';
			$val["telefono2"]='';
			$val["extension2"]='';
			$val["red_nombre1"]='';
			$val["red_nombre2"]='';
			$val["red_tipo1"]='';
			$val["red_tipo2"]='';
			/*24-08-2020 DMQ-Qualsys Se agregan 3 campos de dirección.*/
			$val["calle"]='';
			$val["num_interior"]='';
			$val["num_exterior"]='';
			/* Fin de actualización */
		}

		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#estado option[value="'+'<?php echo $val["estado"] ?>'+'"]').attr('selected', 'selected');
				$('#red1 option[value="'+'<?php echo $val["red_tipo1"] ?>'+'"]').attr('selected', 'selected');
				$('#red2 option[value="'+'<?php echo $val["red_tipo2"] ?>'+'"]').attr('selected', 'selected');
	    		lista_municipios("<?php echo $val["municipio"] ?>");
	    	});
		</script>
		<div id="overlay"></div>
		<div class="header">
			<h1 id="header2"><?php echo NOMBRE_SIS; ?></h1>
		</div>
		<div class="content" id="content">
			<div class="title0">
				<div class="title1">CONFIGURACIÓN DEL SISTEMA</div>
			</div>
			<form id="form-config" enctype="multipart/form-data">
			<div class="forms-container">
				<div>
					Nombre de la dependencia:
					<input type="text" name="nombre_dependencia" maxlength="50" value="<?php echo $val['nombre']; ?>">
				</div>
				<div class="subsubtitle">Domicilio</div>
				<div class="form-div-1">
					Código postal
					<div>
						<input type="text" style="width:30%;display: inline-block;" placeholder="Código Postal" name="cp" id="cp" maxlength="5" value="<?php echo $val['codigopostal']; ?>"><img src="../images/lupa.png" id="buscar-direccion"></img>
						<span id="msj-cp" style="font-weight: bold;color: red;"></span>
					</div>
				</div>
				<div class="form-div-3">
					Estado:
					<div id="estado-div">
						<select name="estado" id="estado" value="<?php echo $val['estado']; ?>">
							<?php entidades(); ?>
						</select>
					</div>
					<div id="estado2-div" style="display: none;">
						<input type="text" placeholder="Estado" name="estado2" id="estado2" maxlength="50">
					</div>
				</div>
				<div class="form-div-3">
					Municipio:
					<div id="municipio-div">
						<select name="municipio" id="municipio">
						</select>
					</div>
				</div>
				<div class="form-div-3">
					Colonia:
<!-- 					<div id="colonia-div">
						<input type="text" placeholder="Localidad" name="colonia" id="colonia" maxlength="100">
					</div> -->
					<div id="colonias-div">
						<input type="text" name="colonia2" maxlength="100" value="<?php echo $val['colonia']; ?>">
					</div>
				</div>
				<!--24-08-2020 DMQ-Qualsys Se agregan 3 campos de dirección.-->
				<div class="form-div-3">
					Calle:
					<input type="text" name="calle" maxlength="50" value="<?php echo $val['calle']; ?>">
				</div>
				<div class="form-div-3">
					No. exterior:
					<input type="text" name="no_ext" maxlength="30" value="<?php echo $val['num_exterior']; ?>">
				</div>
				<div class="form-div-3">
					No. interior:
					<input type="text" name="no_int" maxlength="30" value="<?php echo $val['num_interior']; ?>">
				</div>
				<!-- Fin de actualización. -->
				<div class="subsubtitle">Contacto</div>
				<div class="form-div-3">
					Correo electrónico:
					<input type="text" name="correo1" maxlength="30" value="<?php echo $val['contacto1']; ?>">
				</div>
				<div class="form-div-3">
					Teléfono:
					<input type="text" name="tel1" maxlength="10" value="<?php echo $val['telefono1']; ?>">
				</div>
				<div class="form-div-3">
					Extensión:
					<input type="text" name="ext1" maxlength="15" value="<?php echo $val['extension1']; ?>">
				</div>
				<div class="form-div-3">
					Correo electrónico alterno:
					<input type="text" name="correo2" maxlength="30" value="<?php echo $val['contacto2']; ?>">
				</div>
				<div class="form-div-3">
					Teléfono alterno:
					<input type="text" name="tel2" maxlength="10" value="<?php echo $val['telefono2']; ?>">
				</div>
				<div class="form-div-3">
					Extensión alterna:
					<input type="text" name="ext2" maxlength="15" value="<?php echo $val['extension2']; ?>">
				</div>
				<div class="subsubtitle">Redes</div>
				<div class="form-div-3">
					Red de contacto:
					<select id="red1" name="red1">
						<?php lista_valores("RED_TIPO"); ?>
					</select>
				</div>
				<div class="form-div-2-3">
					Enlace:
					<input type="text" name="redlink1" maxlength="50" value="<?php echo $val['red_nombre1']; ?>">
				</div>
				<div class="form-div-3">
					Red de contacto 2:
					<select id="red2" name="red2">
						<?php lista_valores("RED_TIPO"); ?>
					</select>
				</div>
				<div class="form-div-2-3">
					Enlace:
					<input type="text" name="redlink2" maxlength="50" value="<?php echo $val['red_nombre2']; ?>">
				</div>
				<div class="subsubtitle">Logos</div>
				<div>Las imágenes se deben subir en formato <b>.png</b>.</div><br> <br>
				<center>
				<div style="display: inline-block;width: 40%;vertical-align: top;">
					Nivel de gobierno:<br>
					<br><br>
					<img src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_nivel.png" style="height:70px;width: auto;" id="img_nivel">
					<br><br>
					<button type="button" id="cambiar-logo" style="width: auto;display: block;">Cambiar imagen</button>
						<div id="archivo-cargado1" hidden></div>
					<input type="file" name="logo_nivel" id="logo_nivel" hidden>
				</div>
				<div style="display: inline-block;width: 40%;vertical-align: top;">
					Dependencia:<br>
					<br><br>
					<img src="<?php echo HTTP_PATH ?>/css/images/qsy_logo_depend.png" style="height:70px;width: auto;" id="img_depend">
					<br><br>
					<button type="button" id="cambiar-logo-d" style="width: auto;display: block;">Cambiar imagen</button>
						<div id="archivo-cargado2" hidden></div>
					<input type="file" name="logo_depend" id="logo_depend" hidden>
				</div>
				</center>
				<br><br><br>
				<a href="../menuti.php"><button id="cancelar" type="button">Cancelar</button></a>
				<button type="submit" class="guardar">Guardar</button>
			</div>
			</form>
		</div>
		<div id="aceptar-cambio">
			<span id="mensaje"></span>
				<form action="#" method="POST">
					<button>Aceptar</button>
				</form>
		</div>
	</div>

	<?php
		include("../include/footer.php"); 
	?>

</body>
</html>