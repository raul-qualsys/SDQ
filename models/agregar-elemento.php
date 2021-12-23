<?php
    include("../include/conexion.php");

/* 20-08-2020 DMQ-Qualsys Modificación de catálogo de áreas */
    if(isset($_POST["area"]) && $_POST["area"]=="X"){
        $clave=$_POST["clave"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_corta=mb_strtoupper($_POST["descripcion_corta"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        $query="SELECT * FROM qsy_areas_adscripcion WHERE area_adscripcion='$clave' OR descr='$descr'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
        	print_r("La clave o el área ya existen.");
        }
        else{
        $query="INSERT INTO qsy_areas_adscripcion VALUES ('$clave','$fecha_efec','$descr','$descr_corta','$estatus')";
        $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["area"]) && $_POST["area"]!="X"){
        $clave=$_POST["clave"];
        $clave_ant=$_POST["area"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_corta=mb_strtoupper($_POST["descripcion_corta"]);
        $estatus=$_POST["estatus"];

        $query="SELECT * FROM qsy_areas_adscripcion WHERE area_adscripcion='$clave'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val && $clave!=$clave_ant){
            print_r("El área de adscripción ya existe en el catálogo.<br> Verifica la clave.");
        }
        else{
            $query="UPDATE qsy_areas_adscripcion SET area_adscripcion='$clave',descr='$descr',descr_corta='$descr_corta',estatus='$estatus' WHERE area_adscripcion='$clave_ant'";
            $result=pg_query($conn,$query);
        }
    }

/* Fin de actualización */

    if(isset($_POST["puesto"]) && $_POST["puesto"]=="X"){
        $clave=$_POST["clave"];
        $nivel=$_POST["nivel"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_corta=mb_strtoupper($_POST["descripcion_corta"]);
        $declara=$_POST["declara"];
        $estatus=$_POST["estatus"];
        $fecha_efec=$_POST["fecha_efectiva"];

        $query="SELECT MAX(id) as id FROM qsy_puestos";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        $id=$val["id"]+1;
        $query="INSERT INTO qsy_puestos VALUES ($id,'$clave','$fecha_efec','$nivel','$descr','$descr_corta','$declara','$estatus')";
        $result=pg_query($conn,$query);

    }
    if(isset($_POST["puesto"]) && $_POST["puesto"]!="X"){
        $id=$_POST["puesto"];
        $clave=$_POST["clave"];
        $nivel=$_POST["nivel"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_corta=mb_strtoupper($_POST["descripcion_corta"]);
        $declara=$_POST["declara"];
        $estatus=$_POST["estatus"];

        /* 27-08-2020 DMQ Cambio a inserción si la fecha efectiva cambia*/
        $fecha_efec=$_POST["fecha_efectiva"];
        $query="SELECT * from qsy_puestos WHERE id=$id and fecha_efec='$fecha_efec'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
            $query="UPDATE qsy_puestos SET puesto='$clave',nivel='$nivel',descr='$descr',descr_corta='$descr_corta',declaracion='$declara',estatus='$estatus' WHERE id=$id and fecha_efec='$fecha_efec'";
            $result=pg_query($conn,$query);
        }
        else{
            $query="INSERT INTO qsy_puestos VALUES ($id,'$clave','$fecha_efec','$nivel','$descr','$descr_corta','$declara','$estatus')";
            $result=pg_query($conn,$query);
        }
        /* Fin de actualización. */
    }

/* 20-08-2020 DMQ-Qualsys Modificación de catálogo de áreas */

    if(isset($_POST["dependencia"]) && $_POST["dependencia"]=="X"){
        //print_r($_POST);die;
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_corta=mb_strtoupper($_POST["descripcion_corta"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        if(isset($_POST["principal"])){
            $principal="X";
        }
        else $principal="";
        $query="SELECT * FROM qsy_dependencias WHERE descr='$descr'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
        	print_r("Ya existe una dependencia con ese nombre.");
        }
        else{
            if($principal=="X"){
                $query="UPDATE qsy_dependencias SET principal=''";
                $result=pg_query($conn,$query);
            }
            $query="SELECT MAX(dependencia) as id FROM qsy_dependencias WHERE dependencia != '999'";
            $result=pg_query($conn,$query);
            $val=pg_fetch_assoc($result);
            $id=$val["id"]+1;
            $query="INSERT INTO qsy_dependencias VALUES ('$id','$fecha_efec','$descr','$descr_corta','$estatus','$principal')";
            $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["dependencia"]) && $_POST["dependencia"]!="X"){
        //print_r($_POST);die;
        $id=$_POST["dependencia"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_corta=mb_strtoupper($_POST["descripcion_corta"]);
        $estatus=$_POST["estatus"];

        if(isset($_POST["principal"])){
            $principal="X";
        }
        else $principal="";

        $query="UPDATE qsy_dependencias SET descr='$descr',descr_corta='$descr_corta',estatus='$estatus' WHERE dependencia='$id'";
        $result=pg_query($conn,$query);
        if($principal=="X"){
            $query="UPDATE qsy_dependencias SET principal=''";
            $result=pg_query($conn,$query);
            $query="UPDATE qsy_dependencias SET principal='$principal' WHERE dependencia='$id'";
            $result=pg_query($conn,$query);
        }
    }
/* Fin de actualización */

    if(isset($_POST["pais"]) && $_POST["pais"]=="X"){
        $clave=$_POST["clave"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        $query="SELECT * FROM qsy_paises WHERE pais='$clave' OR descr='$descr'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
            print_r("El país ya existe en el catálogo.<br> Verifica el nombre y la clave.");
        }
        else{
        $query="INSERT INTO qsy_paises VALUES ('$clave','$fecha_efec','$descr','$estatus')";
        $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["pais"]) && $_POST["pais"]!="X"){
        $clave=$_POST["clave"];
        $clave_ant=$_POST["pais"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];
        $query="SELECT * FROM qsy_paises WHERE pais='$clave' OR descr='$descr'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val && $clave!=$clave_ant){
            print_r("El país ya existe en el catálogo.<br> Verifica el nombre y la clave.");
        }
        else{
            $query="UPDATE qsy_paises SET pais='$clave',descr='$descr',estatus='$estatus' WHERE pais='$clave_ant'";
            $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["nacionalidad"]) && $_POST["nacionalidad"]=="X"){
        $clave=$_POST["clave"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        $query="SELECT * FROM qsy_nacionalidades WHERE nacionalidad='$clave'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
            print_r("La nacionalidad ya existe en el catálogo.<br> Verifica el nombre y la clave.");
        }
        else{
        $query="INSERT INTO qsy_nacionalidades VALUES ('$clave','$fecha_efec','$descr','$estatus')";
        $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["nacionalidad"]) && $_POST["nacionalidad"]!="X"){
        $clave=$_POST["clave"];
        $clave_ant=$_POST["nacionalidad"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];

        $query="SELECT * FROM qsy_nacionalidades WHERE nacionalidad='$clave'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val && $clave!=$clave_ant){
            print_r("La nacionalidad ya existe en el catálogo.<br> Verifica el nombre y la clave.");
        }
        else{
            $query="UPDATE qsy_nacionalidades SET nacionalidad='$clave',descr='$descr',estatus='$estatus' WHERE nacionalidad='$clave_ant'";
            $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["moneda"]) && $_POST["moneda"]=="X"){
        $clave=$_POST["clave"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        $query="SELECT * FROM qsy_monedas WHERE moneda='$clave' OR descr='$descr'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
            print_r("La moneda ya existe en el catálogo.<br> Verifica el nombre y la clave.");
        }
        else{
        $query="INSERT INTO qsy_monedas VALUES ('$clave','$fecha_efec','$descr','$estatus')";
        $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["moneda"]) && $_POST["moneda"]!="X"){
        $clave=$_POST["clave"];
        $clave_ant=$_POST["moneda"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];

        $query="SELECT * FROM qsy_monedas WHERE moneda='$clave' OR descr='$descr'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val && $clave!=$clave_ant){
            print_r("La moneda ya existe en el catálogo.<br> Verifica el nombre y la clave.");
        }
        else{
            $query="UPDATE qsy_monedas SET moneda='$clave',descr='$descr',estatus='$estatus' WHERE moneda='$clave_ant'";
            $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["estado"]) && $_POST["estado"]=="X"){
        $clave=$_POST["estado"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        $query="SELECT * FROM qsy_estados WHERE descr='$descr'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
            print_r("La entidad federativa ya existe en el catálogo.<br> Verifica el nombre.");
        }
        else{
            $query="SELECT max(estado) as estado FROM qsy_estados";
            $result=pg_query($conn,$query);
            $val=pg_fetch_assoc($result);
            $clave=$val["estado"]+1;
            $query="INSERT INTO qsy_estados VALUES ('$clave','$fecha_efec','$descr','MEX','$estatus')";
            $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["estado"]) && $_POST["estado"]!="X"){
        $clave=$_POST["estado"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];

        $query="UPDATE qsy_estados SET descr='$descr',estatus='$estatus' WHERE estado='$clave'";
        $result=pg_query($conn,$query);
    }
    if(isset($_POST["municipio"]) && $_POST["municipio"]=="X"){
        $estado=$_POST["estado-mun"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        if($estado==""){
            print_r("Selecciona un estado antes de agregar un municipio nuevo.");            
        }
        else{
            $query="SELECT * FROM qsy_municipios WHERE estado='$estado' AND descr='$descr'";
            $result=pg_query($conn,$query);
            $val=pg_fetch_assoc($result);
            if($val){
                print_r("El municipio ya existe en el catálogo.<br> Verifica el estado.");
            }
            else{
                $query="SELECT max(municipio) as municipio FROM qsy_municipios WHERE estado='$estado'";
                $result=pg_query($conn,$query);
                $val=pg_fetch_assoc($result);
                $clave=$val["municipio"]+1;
                $query="INSERT INTO qsy_municipios VALUES ('$clave','$estado','$fecha_efec','$descr','$estatus')";
                $result=pg_query($conn,$query);
            }
        }
    }
    if(isset($_POST["municipio"]) && $_POST["municipio"]!="X"){
        $clave=$_POST["municipio"];
        $estado=$_POST["estado-mun"];
        $estado_ant=$_POST["estado-mun-ant"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $estatus=$_POST["estatus"];
        //print_r($_POST);
        $query="SELECT * FROM qsy_municipios WHERE municipio='$clave' AND estado='$estado_ant'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val && $estado!=$estado_ant){
            print_r("No es posible cambiar el Municipio de Entidad Federativa.");
        }
        else{
            $query="UPDATE qsy_municipios SET estado='$estado',descr='$descr',estatus='$estatus' WHERE municipio='$clave' AND estado='$estado_ant'";
            $result=pg_query($conn,$query);
        }
    }

    if(isset($_POST["banco"]) && $_POST["banco"]=="X"){
        $clave=$_POST["clave"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_larga=mb_strtoupper($_POST["descripcion_larga"]);
        $estatus=$_POST["estatus"];
        $fecha_efec=date("Y-m-d");
        $query="SELECT * FROM qsy_bancos WHERE banco_id='$clave'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val){
            print_r("El banco ya existe en el catálogo.<br> Verifica la clave.");
        }
        else{
        $query="INSERT INTO qsy_bancos VALUES ('$clave','$fecha_efec','$descr','$descr_larga','$estatus')";
        $result=pg_query($conn,$query);
        }
    }
    if(isset($_POST["banco"]) && $_POST["banco"]!="X"){
        $clave=$_POST["clave"];
        $clave_ant=$_POST["banco"];
        $descr=mb_strtoupper($_POST["descripcion"]);
        $descr_larga=mb_strtoupper($_POST["descripcion_larga"]);
        $estatus=$_POST["estatus"];
        $query="SELECT * FROM qsy_bancos WHERE banco_id='$clave'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_assoc($result);
        if($val && $clave!=$clave_ant){
            print_r("El banco ya existe en el catálogo.<br> Verifica la clave.");
        }
        else{
            $query="UPDATE qsy_bancos SET banco_id='$clave',descr='$descr',descr_larga='$descr_larga',estatus='$estatus' WHERE banco_id='$clave_ant'";
            $result=pg_query($conn,$query);
        }
    }

?>