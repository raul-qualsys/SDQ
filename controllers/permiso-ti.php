<?php
		$rfc = htmlspecialchars($_SESSION["rfc"] ?? '');
		if($rfc){
			$sql = "SELECT rfc FROM qsy_roles WHERE rol = 'T' and rfc='$rfc' and estatus='A'";
			$result=pg_query($conn,$sql);
			$val=pg_fetch_all($result);
			if(empty($val)){
				header("location:".HTTP_PATH. "/inicio.php");
			}
		}
		else{
			header("location: ".HTTP_PATH. "/inicio.php");
		}
?>