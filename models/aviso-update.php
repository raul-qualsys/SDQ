<?php
    include("../include/conexion.php");
    if(isset($_POST["texto"])){
        $id=pg_escape_string($_POST["id"]);
        $texto=pg_escape_string($_POST["texto"]);

		$query="UPDATE qsy_texto_notificaciones SET texto='$texto' WHERE id=$id";
        $result=pg_query($conn,$query);
        echo "Los datos han sido guardados.";
    }
    else echo "No se guardaron los cambios.";

?>