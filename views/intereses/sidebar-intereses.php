<div id="showmenu" class="showbutton">
	<a href="#" onclick="showmenu()" class="showbuttona">
		<img class="showbut" id="frente" style="display: none;" src="<?php echo HTTP_PATH ?>../css/icons/frente.png">
	</a>
</div>
<div id="menu" class="menu" style="display: block;">

	<nav>
		<ul>
			<li class="liL0">
				<a href="#" onclick="hidemenu()">
					<img src="<?php echo HTTP_PATH ?>../css/icons/atras.png">
				</a>
				<a href="declaracion-intereses.php"><p>Declaración de Intereses</p></a>
			</li>
		</ul>
		<ul>
			<li class="liL1"><!-- primer nivel -->
				<div id="menu1-1">
					<a href="#"><p>Registro</p></a>
					<div onclick ="menu_dinamico('menu1-2','arb1i','abj1i');">
						<img class="img2" id="arb1i" src="../../css/icons/arriba.png" style="display: none;">
						<img class="img1" id="abj1i" src="../../css/icons/abajo.png">
					</div>
				</div>
				<ul style="display: none;" id="menu1-2"><!-- menu 2 -->
					<li class="liL2">
						<div id="menu2-1">
							<a href="declaracion-intereses.php"><p>Declaración de Intereses</p></a>
							<div onclick="menu_dinamico('menu3-1','arb2i','abj2i');">
								<?php if(isset($_POST["declaracion"])){ ?>
								<img class="img2" id="arb2i" src="../../css/icons/arriba.png" style="display: none;">
								<img class="img1" id="abj2i" src="../../css/icons/abajo.png">
								<?php } ?>
							</div>
						</div>
						<?php if(isset($_POST["declaracion"])){ ?>
						<ul id="menu3-1" style="display: none;"><!-- menu 3 -->
							<li class="liL3">
								<div>
								<form method="post" action="1-participacion-empresa.php#01">
									<?php require("../menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"I","qsy_participa_empresas"); ?>
									<button class="button-menu" id="01" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc1">1. Participación en empresas, sociedades (Hasta 2 años)</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="2-participacion-decisiones.php#02">
									<?php require("../menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"I","qsy_decisiones"); ?>
									<button class="button-menu" id="02" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc2">2. ¿Participa en la toma de decisiones de estas inst.?</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="3-apoyos-publicos.php#03">
									<?php require("../menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"I","qsy_apoyos"); ?>
									<button class="button-menu" id="03" >
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc3">3. Apoyos o beneficios públicos (Hasta 2 años)</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="4-representacion.php#04">
									<?php require("../menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"I","qsy_representaciones"); ?>
									<button class="button-menu" id="04">
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc4">4. Representación (Hasta los 2 últimos años)</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="5-clientes-principales.php#05">
									<?php require("../menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"I","qsy_clientes"); ?>
									<button class="button-menu" id="05">
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc5">5. Clientes principales (Hasta los 2 últimos años)</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="6-beneficios-privados.php#06">
									<?php require("../menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"I","qsy_beneficios_privados"); ?>
									<button class="button-menu" id="06">
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc6">6. Beneficios privados (Hasta los 2 últimos años)</p></div>
									</button>
								</form>
								</div>
							</li>
							<li class="liL3">
								<div>
								<form method="post" action="7-fideicomisos.php#07">
									<?php require("../menu-hidden.php");
									$estatus=estatus_form($_SESSION["rfc"],$_POST["declaracion"],$_POST["ejercicio"],"I","qsy_fideicomisos"); ?>
									<button class="button-menu" id="07">
										<div class="dec-estatus" ><img src="<?php echo $estatus; ?>"></div>
										<div class="dec-option"><p id="opc7">7. Fideicomisos</p></div>
									</button>
								</form>
								</div>
							</li>
						</ul>
						<?php } ?>
					</li>
<!-- 					<li class="liL2">
						<div>
							<a href="#"><p>Transmitir declaración</p></a>
						</div>
					</li> -->
				</ul>
			</li>
			<li class="liL1"><!-- primer nivel -->
				<div>
					<a href="ver-declaraciones-i.php"><p>Declaraciones presentadas</p></a>
				</div>
			</li>
		</ul>
	</nav>
</div>

