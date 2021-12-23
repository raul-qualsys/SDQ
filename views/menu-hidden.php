<!--
31-08-2020
DMQ-Qualsys

Cambio para enviar estatus si es que declara completo o parcial
-->
<?php if(isset($_SESSION["rfc"]) && isset($_POST["declaracion"]) && isset($_POST["ejercicio"]) && isset($_POST["declara_completo"])){ ?>
<input type="hidden" name="rfc" value="<?php echo $_SESSION['rfc'] ?>">
<input type="hidden" name="declaracion" value="<?php echo $_POST['declaracion'] ?>">
<input type="hidden" name="ejercicio" value="<?php echo $_POST['ejercicio'] ?>">
<input type="hidden" name="declara_completo" value="<?php echo $_POST['declara_completo'] ?>">
<?php } ?>
