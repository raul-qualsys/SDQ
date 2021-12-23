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
				<a href="<?php echo HTTP_PATH ?>/menurecursos.php">
				<p>Recursos Humanos</p></a>
			</li>
		</ul>
		<ul>
			<li class="liL1"><!-- primer nivel -->
				<div id="menu1-1">
					<a href="<?php echo HTTP_PATH ?>/views/admin-personal.php"><p>Administración de Personal</p></a>
				</div>
			</li>
			<li class="liL1">
				<div id="menu1-1n">
					<a href="<?php echo HTTP_PATH ?>/views/catalogos-sistema.php"><p>Mantenimiento de Catálogos</p></a>
					<div onclick ="menu_dinamico('menu1-2n','arb4','abj4');">
						<img class="img2" id="arb4" src="<?php echo HTTP_PATH ?>/css/icons/arriba.png" style="display: none;">
						<img class="img1" id="abj4" src="<?php echo HTTP_PATH ?>/css/icons/abajo.png">
					</div>
				</div>
				<ul style="display: none;" id="menu1-2n"><!-- menu 2 -->
					<li class="liL2">
						<div id="menu2-1n2">
							<a href="<?php echo HTTP_PATH ?>/views/catalogos-sistema.php"><p>Catálogos de Sistema</p></a>
							<div onclick="menu_dinamico('menu3-1n2','arb5','abj5');">
								<img class="img2" id="arb5" src="<?php echo HTTP_PATH ?>/css/icons/arriba.png" style="display: none;">
								<img class="img1" id="abj5" src="<?php echo HTTP_PATH ?>/css/icons/abajo.png">
							</div>
						</div>
						<ul id="menu3-1n2" style="display: none;"><!-- menu 3 -->
							<li class="liL3">
								<div id="sub3-1p">
									<a href="<?php echo HTTP_PATH ?>/views/rh-areas.php">
										<p>Áreas de Adscripción</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-2p">
									<a href="<?php echo HTTP_PATH ?>/views/cat-bancos.php">
										<p>Bancos</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-3p">
									<a href="<?php echo HTTP_PATH ?>/views/rh-dependencias.php">
										<p>Dependencias</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-4p">
									<a href="<?php echo HTTP_PATH ?>/views/cat-estados.php">
										<p>Entidades federativas</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-5p">
									<a href="<?php echo HTTP_PATH ?>/views/cat-monedas.php">
										<p>Monedas</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-6p">
									<a href="<?php echo HTTP_PATH ?>/views/cat-municipios.php">
										<p>Municipios</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-7p">
									<a href="<?php echo HTTP_PATH ?>/views/cat-nacionalidades.php">
										<p>Nacionalidades</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-8p">
									<a href="<?php echo HTTP_PATH ?>/views/cat-paises.php">
										<p>Países</p>
									</a>
								</div>
							</li>
							<li class="liL3">
								<div id="sub3-9p">
									<a href="<?php echo HTTP_PATH ?>/views/rh-puestos.php">
										<p>Puestos</p>
									</a>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
	</nav>
</div>

