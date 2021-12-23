<div id="showmenu" class="showbutton">
	<a href="#" onclick="showmenu()" class="showbuttona">
		<img class="showbut" id="frente" style="display: none;" src="<?php echo HTTP_PATH ?>/css/icons/frente.png">
	</a>
</div>
<div id="menu" class="menu" style="display: block;">

	<nav>
		<ul>
			<li class="liL0">
				<a href="#" onclick="hidemenu()">
					<img src="<?php echo HTTP_PATH ?>/css/icons/atras.png">
				</a>
				<a href="declaracion-patrimonial.php"><p>Declaración Patrimonial</p></a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<div id="menu1-1">
					<a href="#"><p>Registro</p></a>
					<div onclick ="menu_dinamico('menu1-2','arbp1','abjp1');">
						<img class="img2" id="arbp1" src="../css/icons/arriba.png" style="display: none;">
						<img class="img1" id="abjp1" src="../css/icons/abajo.png">
					</div>
				</div>
				<ul style="display: none;" id="menu1-2">
					<li class="liL2">
						<div id="menu2-1">
							<a href="declaracion-patrimonial.php"><p>Declaración de Situación Patrimonial</p></a>
							<div onclick="menu_dinamico('menu3-1','arbp2','abjp2');">
								<?php if(isset($_POST["declaracion"])){ ?>
								<img class="img2" id="arbp2" src="../css/icons/arriba.png" style="display: none;">
								<img class="img1" id="abjp2" src="../css/icons/abajo.png">
								<?php } ?>
							</div>
						</div>
						<?php if(isset($_POST["declaracion"])){ ?>
						<ul id="menu3-1" style="display: none;">
							<li class="liL3">
								<div>
								<form method="post" action="1-datos-generales.php#01">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_datos_generales"); ?>
									<button class="button-menu" id="01" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc1">1. Datos Generales</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="2-domicilio-declarante.php#02">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_direcciones"); ?>
									<button class="button-menu" id="02" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc2">2. Domicilio del Declarante</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="3-datos-curriculares-declarante.php#03">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_escolaridades"); ?>
									<button class="button-menu" id="03" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc3">3. Datos Curriculares del Declarante</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="4-datos-empleo.php#04">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_comision_actual"); ?>
									<button class="button-menu" id="04" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc4">4. Datos del empleo, cargo o comisión</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="5-experiencia-laboral.php#05">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_experiencia"); ?>
									<button class="button-menu" id="05" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc5">5. Experiencia laboral (Últimos cinco empleos)</p></div>
									</button>
								</form>
								</div>
							</li>
							<?php 
							//print_r($_SESSION);
							if($_POST["declara_completo"]=="P"){
								?>
								<li class="liL3">
									<div>
									<form method="post" action="8-ingresos-declarante.php#08">
										<?php require("menu-hidden.php");
										$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_ingresos_netos"); ?>
										<button class="button-menu" id="08" >
											<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
											<div class="dec-option"><p id="opc8">6. Ingresos Netos del Declarante, Pareja y Dependientes</p></div>
										</button>
									</form>
									</div>
								</li>
								<?php
								if($_POST["declaracion"]!="M"){
								?>
								<li class="liL3">
									<div>
									<form method="post" action="9-datos-servidor-anterior.php#09">
										<?php require("menu-hidden.php");
										$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_ingresos_anio_anterior"); ?>
										<button class="button-menu" id="09" >
											<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
											<div class="dec-option"><p id="opc9">7. ¿Te desempeñaste como Servidor Público el año anterior?</p></div>
										</button>
									</form>
									</div>
								</li>
								<?php
								}
							}
							else{
							?>
							<li class="liL3">
								<div>
								<form method="post" action="6-datos-pareja.php#06">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_parejas"); ?>
									<button class="button-menu" id="06" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc6">6. Datos de la Pareja</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="7-datos-dependiente.php#07">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_dependientes"); ?>
									<button class="button-menu" id="07" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc7">7. Datos del Dependiente Económico</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="8-ingresos-declarante.php#08">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_ingresos_netos"); ?>
									<button class="button-menu" id="08" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc8">8. Ingresos Netos del Declarante, Pareja y Dependientes</p></div>
									</button>
								</form>
								</div>
							</li>
							<?php
							if($_POST["declaracion"]!="M"){
							?>
							<li class="liL3">
								<div>
								<form method="post" action="9-datos-servidor-anterior.php#09">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_ingresos_anio_anterior"); ?>
									<button class="button-menu" id="09" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc9">9. ¿Te desempeñaste como Servidor Público el año anterior?</p></div>
									</button>
								</form>
								</div>
							</li>
							<?php
							}
							?>
							<li class="liL3">
								<div>
								<form method="post" action="10-bienes-inmuebles.php#10">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_inmuebles"); ?>
									<button class="button-menu" id="10" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<?php
										if($_POST["declaracion"]!="M"){
										?>
										<div class="dec-option"><p id="opc10">10. Bienes Inmuebles</p></div>
										<?php } else{ ?>
										<div class="dec-option"><p id="opc10">9. Bienes Inmuebles</p></div>
										<?php }	?>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="11-vehiculos.php#11">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_vehiculos"); ?>
									<button class="button-menu" id="11" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<?php
										if($_POST["declaracion"]!="M"){
										?>
										<div class="dec-option"><p id="opc11">11. Vehículos</p></div>
										<?php } else{ ?>
										<div class="dec-option"><p id="opc11">10. Vehículos</p></div>
										<?php }	?>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="12-bienes-muebles.php#12">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_muebles"); ?>
									<button class="button-menu" id="12" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<?php
										if($_POST["declaracion"]!="M"){
										?>
										<div class="dec-option"><p id="opc12">12. Bienes Muebles</p></div>
										<?php } else{ ?>
										<div class="dec-option"><p id="opc12">11. Bienes Muebles</p></div>
										<?php }	?>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="13-inversiones.php#13">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_inversiones"); ?>
									<button class="button-menu" id="13" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<?php
										if($_POST["declaracion"]!="M"){
										?>
										<div class="dec-option"><p id="opc13">13. Inversiones, Cuentas Bancarias y Otros Valores Activos</p></div>
										<?php } else{ ?>
										<div class="dec-option"><p id="opc13">12. Inversiones, Cuentas Bancarias y Otros Valores Activos</p></div>
										<?php }	?>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="14-adeudos.php#14">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_adeudos"); ?>
									<button class="button-menu" id="14" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<?php
										if($_POST["declaracion"]!="M"){
										?>
										<div class="dec-option"><p id="opc14">14. Adeudos / Pasivos</p></div>
										<?php } else{ ?>
										<div class="dec-option"><p id="opc14">13. Adeudos / Pasivos</p></div>
										<?php }	?>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="15-prestamo.php#15">
									<?php require("menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"P","qsy_comodatos"); ?>
									<button class="button-menu" id="15" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<?php
										if($_POST["declaracion"]!="M"){
										?>
										<div class="dec-option"><p id="opc15">15. Préstamo o Comodato por Terceros</p></div>
										<?php } else{ ?>
										<div class="dec-option"><p id="opc15">14. Préstamo o Comodato por Terceros</p></div>
										<?php }	?>
									</button>
								</form>
								</div>
							</li>
							<?php
							}
							?>
						</ul>
						<?php } ?>
					</li>
				</ul>
			</li>
			<li class="liL1">
				<div>
					<a href="ver-declaraciones.php"><p>Declaraciones presentadas</p></a>
				</div>
			</li>
		</ul>
	</nav>
</div>

