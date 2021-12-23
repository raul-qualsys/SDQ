				<div class="forms-container">
					<div class="aviso_pendientes"><?php echo $html; ?></div>
					<div class="form-div-1">
						Ubicación:
						<input type="radio" name="ubicacion" id="dommex" value="M" checked required>MÉXICO
						<input type="radio" name="ubicacion" id="domext" value="E">EXTRANJERO
					</div>
					<div class="form-div-3">
						País<span class="asterisk">*</span>
						<select name="pais" id="pais" required>
							<?php paises(); ?>
						</select>
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3">
						<label id="cp-label" style="display: block;">Código postal<span class="asterisk ast">*</span></label>
						<input type="text" style="width:50%;display: inline-block;" placeholder="Código Postal" name="cp" id="cp" maxlength="5" value="<?php echo $arr['cp']?>" required>
						<img src="../images/lupa.png" id="buscar-direccion"></img>
						<span id="msj-cp" style="font-weight: bold;color: red;"></span>
					</div>
				</div>
				<div class="forms-container">
					<div class="form-div-3" id="estado-div">
						<label>Entidad Federativa</label><span class="asterisk">*</span>
						<select name="estado" id="estado" value="<?php echo $arr['estado']?>">
							<?php entidades(); ?>
						</select>
					</div>
					<div class="form-div-3" id="estado2-div" style="display: none;">
						<label>Estado / Provincia</label>
						<input type="text" placeholder="Estado" name="estado2" id="estado2" maxlength="50" value="<?php echo $arr['estado_desc']?>">
					</div>
					<div class="form-div-3" id="municipio-div">
						<label id="municipio-label">Municipio / Alcaldía</label><span class="asterisk">*</span>
						<select name="municipio" id="municipio">
						</select>
					</div>
					<div class="form-div-3" id="colonia-div">
						<label>Colonia / Localidad</label><span class="asterisk ast">*</span>
						<input type="text" placeholder="Localidad" name="colonia" id="colonia" maxlength="100" value="<?php echo $arr['colonia_desc']?>">
					</div>
					<div class="form-div-3" id="colonia2-div" style="display: none;">
						<label>Colonia / Localidad</label><span class="asterisk">*</span>
						<select name="colonia2" id="colonia-select">
						</select>
					</div>
					<div class="form-div-3">
						Calle<span class="asterisk ast">*</span>
						<input type="text" placeholder="Calle" name="calle" id="calle" maxlength="50" value="<?php echo $arr['calle']?>" required>
					</div>
					<div class="form-div-3">
						Número exterior<span class="asterisk ast">*</span>
						<input type="text" placeholder="Exterior" name="noexterior" id="num_exterior" maxlength="15" value="<?php echo $arr['num_exterior']?>" required>
					</div>
					<div class="form-div-3">
						Número interior
						<input type="text" placeholder="Interior" name="nointerior" maxlength="15" value="<?php echo $arr['num_interior']?>">
					</div>
				</div>
