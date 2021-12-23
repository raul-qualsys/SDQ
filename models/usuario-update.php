<?php
    include("../include/conexion.php");
    include("../include/funciones.php");

    if(isset($_POST["nombre"])){
        $rfc=escape($_POST["rfc"]);
        $nombre=escape($_POST['nombre']);
        $apaterno=escape($_POST["apaterno"]);
        $amaterno=escape($_POST["amaterno"]);
        $rfc_nuevo=escape($_POST["rfc_nuevo"]);
        $curp=escape($_POST["curp"]);
        $emp_num=escape($_POST["emp_num"]);
        $tipo_empleado=escape($_POST["tipo_empleado"]);
        $correo1=pg_escape_string($_POST["email_trabajo"]);
        $correo2=pg_escape_string($_POST["email"]);
        $tel1=escape($_POST["tel_casa"]);
        $tel2=escape($_POST["tel_celular"]);
        $ecivil=escape($_POST["edo_civil"]);
        $pais=escape($_POST["pais"]);
        $nacionalidad=escape($_POST["nacionalidad"]);
        $sexo=escape($_POST["sexo"]);
        $fecha_nac=escape($_POST["fecha_nac"]);
        $extra="";
        if($fecha_nac!="")$extra.=",fecha_nacimiento='$fecha_nac'";
        $fecha_contrata=escape($_POST["fecha_contratacion"]);
        $fecha_baja=escape($_POST["fecha_baja"]);
        if($fecha_contrata!="")$extra.=",fecha_contrata='$fecha_contrata'";

        if($fecha_baja==""){
            $estatus="A";
            $extra.=",fecha_baja=null";
        }
        else {
            $estatus="B";
            $extra.=",fecha_baja='$fecha_baja'";
        }

        $calle=escape($_POST["calle"]);
        $num_exterior=escape($_POST["noext"]);
        $num_interior=escape($_POST["noint"]);
        $codigopostal=escape($_POST["cp"]);

        if($_POST["estado2"]!=""){
            $estado="";
            $estado_descr=escape($_POST["estado2"]);
        }
        else{
            $estado=escape($_POST["estado"]);
            $estado_descr=get_descr($conn,$estado,"descr","qsy_estados","estado");
        }
        if(isset($_POST["municipio"])){
            $municipio=escape($_POST["municipio"]);
            $municipio_descr=get_municipio($conn,$municipio,$estado);
        }
        else{
            $municipio="";
            $municipio_descr="";
        }
        if(isset($_POST["colonia2"])){
            $colonia=escape($_POST["colonia2"]);
            $colonia_descr=get_colonia($conn,$colonia,$codigopostal);
        }
        else{
            $colonia="";
            $colonia_descr=escape($_POST["colonia"]);
        }
        $pais=escape($_POST["pais"]);
        $pais_descr=get_descr($conn,$pais,"descr","qsy_paises","pais");


        $query="SELECT rfc FROM qsy_rh_empleados WHERE rfc='$rfc_nuevo'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_row($result);
        if($val && $rfc!=$rfc_nuevo){
            echo "El nuevo rfc pertenece a otro usuario.";
        }
        else{
        $query="UPDATE qsy_rh_empleados SET rfc='$rfc_nuevo',nombre='$nombre',primer_ap='$apaterno',segundo_ap='$amaterno',curp='$curp',email_institucional='$correo1',email_personal='$correo2',tel_casa='$tel1',celular_personal='$tel2',estado_civil='$ecivil',pais_nacimiento='$pais',nacionalidad = '$nacionalidad', sexo='$sexo',estatus_empleado='$estatus',tipo_empleado='$tipo_empleado',emp_num='$emp_num' $extra WHERE rfc='$rfc'";
        $result=pg_query($conn,$query);
        $query="UPDATE qsy_rh_direcciones SET rfc='$rfc_nuevo',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',colonia_descr='$colonia_descr',municipio='$municipio',municipio_descr='$municipio_descr',estado='$estado',estado_descr='$estado_descr',pais='$pais',pais_descr = '$pais_descr',codigopostal='$codigopostal' WHERE rfc='$rfc'";
        $result=pg_query($conn,$query);
        echo "Los datos han sido guardados.";
        }
    }

    if(isset($_POST["nivel_gobierno"])){
        $rfc=escape($_POST["rfc"]);
        $orden_id=escape($_POST["nivel_gobierno"]);
        $ambito_id=escape($_POST["ambito_publico"]);
        $dependencia=escape($_POST["ente"]);
        if(isset($_POST["area"]))
            $area_adscripcion=escape($_POST["area"]);
        else
            $area_adscripcion="";
        $id_puesto=escape($_POST["puesto"]);
            if($id_puesto=='')$id_puesto=0;
        $nivel_empleo=escape($_POST["nivel_empleo"]);
        $funcion_principal=escape($_POST["funcion_principal"]);
        $fecha_inicio=format_date($_POST["fecha_empleo"]);
        $tel_oficina=escape($_POST["telefono"]);
        $extension=escape($_POST["extension"]);

        $ubicacion="";
        $calle="";
        $num_exterior="";
        $num_interior="";
        $colonia="";
        $municipio="";
        $estado="";
        $pais="";
        $codigopostal="";

        $query="SELECT rfc FROM qsy_rh_empleos WHERE rfc='$rfc'";
        $result=pg_query($conn,$query);
        $val=pg_fetch_row($result);
        if($val){
            $query = "UPDATE qsy_rh_empleos SET orden_id='$orden_id',ambito_id='$ambito_id',dependencia='$dependencia',area_adscripcion='$area_adscripcion',nivel_empleo='$nivel_empleo',id_puesto=$id_puesto,funcion_principal='$funcion_principal',fecha_inicio=$fecha_inicio,tel_oficina='$tel_oficina',extension='$extension',ubicacion='$ubicacion',calle='$calle',num_exterior='$num_exterior',num_interior='$num_interior',colonia='$colonia',municipio='$municipio',estado='$estado',pais='$pais',codigopostal='$codigopostal' WHERE rfc='$rfc'";
            $result=pg_query($conn,$query);
            echo "Los datos han sido guardados.";
        }
        else{
            $date=date("Y-m-d");
            $query = "INSERT INTO qsy_rh_empleos VALUES ('$rfc','$date','$orden_id','$ambito_id','$dependencia','$area_adscripcion','$nivel_empleo',$id_puesto,'$funcion_principal',$fecha_inicio,'$tel_oficina','$extension','$ubicacion','$calle','$num_exterior','$num_interior','$colonia','$municipio','$estado','$pais','$codigopostal')";
            $result=pg_query($conn,$query) or die("Problema al insertar el empleo.");
            echo "Los datos han sido guardados.";
        }
    }
?>