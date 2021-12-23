<?php

	$host="localhost";
	$db="SDQ";
	$port=5432;
	$user="sdpq";
	$pass="5DPq1";

	$conn=pg_connect("host=".$host." dbname=".$db." port=".$port." user=".$user." password=".$pass) or die("Error de conexión". pg_last_error());

?>
