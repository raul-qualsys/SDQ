<div id="showmenu" class="showbutton">
	<a href="#" onclick="showmenu()" class="showbuttona">
		<img class="showbut" id="frente" style="display: none;" src="<?php echo HTTP_PATH ?>/css/icons/frente.png">
	</a>
</div>
<div id="menu" class="menu" style="display: block;">
	<ul>
		<li class="liL0">
			<a href="#" onclick="hidemenu()">
				<img src="<?php echo HTTP_PATH ?>/css/icons/atras.png">
			</a>
			<a href="<?php echo HTTP_PATH ?>/menuti.php">
				<p>Tecnologías de Información</p>
			</a>
		</li>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/ti-usuario.php" onclick="hideimg(img111,img110,ul11)">
					<p>Administración de Empleados</p>
				</a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/ti-configuracion.php" onclick="hideimg(img121,img120,ul12)">
					<p>Notificaciones/Alertas</p>
				</a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/ti-roles.php" onclick="hideimg(img121,img120,ul12)">
					<p>Asignación de roles</p>
				</a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/ti-avisos.php" onclick="hideimg(img121,img120,ul12)">
					<p>Avisos del sistema</p>
				</a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/ti-sistema.php" onclick="hideimg(img121,img120,ul12)">
					<p>Configuración del sistema</p>
				</a>
			</li>
		</ul>
	</ul>
</div>