<?php
    include("../include/conexion.php");
    include("../include/funciones.php");

    if(isset($_POST["nombre_dependencia"])){
        $nombre=escape($_POST['nombre_dependencia']);
        //print_r($_POST);die;
        $correo1=pg_escape_string($_POST["correo1"]);
        $correo2=pg_escape_string($_POST["correo2"]);
        $tel1=escape($_POST["tel1"]);
        $tel2=escape($_POST["tel2"]);
        $ext1=escape($_POST["ext1"]);
        $ext2=escape($_POST["ext2"]);
        $calle=escape($_POST["calle"]);
        $num_exterior=escape($_POST["no_ext"]);
        $num_interior=escape($_POST["no_int"]);
        $codigopostal=escape($_POST["cp"]);
        $estado=escape($_POST["estado"]);
        $municipio=escape($_POST["municipio"]);
        $colonia=escape($_POST["colonia2"]);
        $red1=escape($_POST["red1"]);
        $rednombre1=escape($_POST["redlink1"]);
        $red2=escape($_POST["red2"]);
        $rednombre2=escape($_POST["redlink2"]);

        $fecha=date('Y-m-d');

        $query="SELECT * FROM qsy_instalacion WHERE dependencia=1";
        $result=pg_query($conn,$query);
        $val=pg_fetch_row($result);
        /*24-08-2020 DMQ-Qualsys Se agregan 3 campos de dirección.*/
        if($val){
            $query="UPDATE qsy_instalacion SET nombre='$nombre',estado='$estado',municipio='$municipio',colonia='$colonia',codigopostal='$codigopostal',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',contacto1='$correo1',contacto2='$correo2',red_tipo1='$red1',red_nombre1='$rednombre1',red_tipo2='$red2',red_nombre2='$rednombre2',telefono1='$tel1',extension1='$ext1',telefono2='$tel2',extension2='$ext2' WHERE dependencia=1";
            $result=pg_query($conn,$query);
        }
        else{
            $query="INSERT INTO qsy_instalacion VALUES (1,'$fecha','$nombre','','MEX','$estado','$municipio','$colonia','$codigopostal','$calle','$num_exterior','$num_interior','','','$correo1','$correo2','$red1','$rednombre1','$red2','$rednombre2','$tel1','$ext1','$tel2','$ext2','A')";
            $result=pg_query($conn,$query);
        }
        /*Fin de actualización. */


    if(isset($_FILES["logo_nivel"]) && $_FILES["logo_nivel"]["error"]!=4 && $_FILES["logo_nivel"]["size"]<5240000){
        $nombre="../css/images/qsy_logo_nivel.png";
        move_uploaded_file($_FILES["logo_nivel"]["tmp_name"],$nombre);
    }
    if(isset($_FILES["logo_depend"]) && $_FILES["logo_depend"]["error"]!=4 && $_FILES["logo_depend"]["size"]<5240000){
        $nombre="../css/images/qsy_logo_depend.png";
        move_uploaded_file($_FILES["logo_depend"]["tmp_name"],$nombre);
    }

    }
?>