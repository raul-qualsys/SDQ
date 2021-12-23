<?php
    include("../include/conexion.php");
    //print_r($_POST);die;
    if(isset($_POST["rfc"]) && !empty($_POST["rfc"])){
    $rfc=$_POST["rfc"];
    $e=$_POST["e"];
    $r=$_POST["r"];
    $c=$_POST["c"];
    $t=$_POST["t"];
    
    $query="SELECT * FROM qsy_roles WHERE rfc='$rfc'";
    $result=pg_query($conn,$query);
    $val=pg_fetch_all($result);
    print_r($val);
    if($val){
        if($e==='true'){
            $query1="UPDATE qsy_roles SET estatus='A' where rfc='$rfc' and rol= 'E' ";
        }
        else{
            $query1="UPDATE qsy_roles SET estatus='I' where rfc='$rfc' and rol= 'E' ";
        }
        if($r==='true'){
            $query2="UPDATE qsy_roles SET estatus='A' where rfc='$rfc' and rol= 'R' ";
        }
        else{
            $query2="UPDATE qsy_roles SET estatus='I' where rfc='$rfc' and rol= 'R' ";
        }
        if($c==='true'){
            $query3="UPDATE qsy_roles SET estatus='A' where rfc='$rfc' and rol= 'C' ";
        }
        else{
            $query3="UPDATE qsy_roles SET estatus='I' where rfc='$rfc' and rol= 'C' ";
        }
        if($t==='true'){
            $query4="UPDATE qsy_roles SET estatus='A' where rfc='$rfc' and rol= 'T' ";
        }
        else{
            $query4="UPDATE qsy_roles SET estatus='I' where rfc='$rfc' and rol= 'T' ";
        }
    }
    else{
        $fecha=date("Y-m-d");
        if($e==='true'){
            $query1="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','E','A')";
        }
        else{
            $query1="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','E','I')";
        }
        if($r==='true'){
            $query2="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','R','A')";
        }
        else{
            $query2="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','R','I')";
        }
        if($c==='true'){
            $query3="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','C','A')";
        }
        else{
            $query3="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','C','I')";
        }
        if($t==='true'){
            $query4="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','T','A')";
        }
        else{
            $query4="INSERT INTO qsy_roles VALUES ('$rfc','$fecha','T','I')";
        }
    }
    $result=pg_query($conn,$query1);
    $result=pg_query($conn,$query2);
    $result=pg_query($conn,$query3);
    $result=pg_query($conn,$query4);
    }
?>