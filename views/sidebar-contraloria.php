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
			<a href="<?php echo HTTP_PATH ?>/menucontraloria.php">
				<p>Auditoría</p>
			</a>
		</li>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/c-estatus-declaraciones.php">
					<p>Estatus de Declaraciones</p>
				</a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/c-declaraciones-presentadas.php">
					<p>Declaraciones Presentadas</p>
				</a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/c-reportes.php">
					<p>Reportes</p>
				</a>
			</li>
		</ul>
		<ul>
			<li class="liL1">
				<a href="<?php echo HTTP_PATH ?>/views/dash-indicadores.php">
					<p>Indicadores</p>
				</a>
			</li>
		</ul>
	</ul>
</div>
