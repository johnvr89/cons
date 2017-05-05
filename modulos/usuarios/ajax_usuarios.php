<?php

include_once "../php_conexion.php";
include_once '../general/ControladorGeneral.php';
include_once "../funciones.php";

$strAccion  = $_POST['action'];

if($strAccion == 'getConsultorio')
{
    $idEmpresa = $_POST['idEmpresa'];

    $strSql = 'SELECT * FROM consultorios WHERE empresa_id = ' . $idEmpresa;

    $sqlHorario = mysql_query($strSql);
    $json = array();
    while($row = mysql_fetch_array($sqlHorario))
    {
        $json[] = array(
            'id' => $row['id'],
            'reg' => $row['nombre']
        );
    }
    echo json_encode($json);
}

?>