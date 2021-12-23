<?php
/*25-08-2020 DMQ-Qualsys Cambio de valor por default de dependencia. */
    include("../include/conexion.php");
    if(isset($_POST["dependencia"])){
    	$dependencia=$_POST["dependencia"];
		$query="SELECT * FROM qsy_dependencias WHERE dependencia='$dependencia' AND principal='X'";
        $result=pg_query($conn,$query);
		$val=pg_fetch_all($result);
		if($val)echo '1';
		else{
			$query="SELECT * FROM qsy_dependencias WHERE dependencia='$dependencia' AND otra='X'";
	        $result=pg_query($conn,$query);
			$val=pg_fetch_all($result);
			if($val)echo '2';
			else echo '3';
		}
    }
/*Fin de actualización. */ 
?>