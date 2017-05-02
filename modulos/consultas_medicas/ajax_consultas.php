<?php

include_once "../php_conexion.php";
include_once '../general/ControladorGeneral.php';
include_once "../funciones.php";

$strAccion  = $_POST['action'];
$id         = $_POST['id'];

if($strAccion == 'delete')
{
    if($id)
    {
        $strSql = "update detalle set estado = 'I' where id=" . $id;
        $sql = mysql_query($strSql);
        if($sql)
        {
            echo 'correcto.';
        }
        recalcularFactura($id);
    }
    
}
?>