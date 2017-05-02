<?php

include_once "../php_conexion.php";
include_once '../general/ControladorGeneral.php';
include_once "../funciones.php";

$strAccion  = $_POST['action'];
$id         = $_POST['idConsultorio'];
$fecha      = $_POST['fecha'];
$idMedico   = $_POST['medico'];
$idRegistro = $_POST['idRegistro'];

if($strAccion == 'horario')
{
    //echo $fecha.'--';
    $fechaActual= date("Y-m-d");
    $horaActual = date("H");
    
    //obtengo el numero de equipos de unidades odontologicas que hay
    $numeroEquipos = 0;
    $strSql = 'SELECT * FROM consultorios WHERE id = ' . $id;
    $sql = mysql_query($strSql);
    while($row = mysql_fetch_array($sql))
    {
        $numeroEquipos = $row['unidades_odontologicas'];
    }

    if($numeroEquipos == 0)
    {
        die('Debe ingresar el numero de unidades odontologicas del consultorio.');
    }
    

    $strSql = 'SELECT * FROM horarios_cita WHERE id_consultorio = ' . $id. ' order by horario asc ';

    $sqlHorario = mysql_query($strSql);
    $json = array();
    while($row = mysql_fetch_array($sqlHorario))
    {
        //verifico si el horario esta disponible
        $numeroCitas = 0;

        $strSql = "SELECT * FROM citas_medicas WHERE consultorio = " . $id . " and status != 'ELIMINADO' and fecha_cita ='" . $fecha . "' and  horario = " . $row['id'];

        $sql = mysql_query($strSql);
        $numeroCitas = 0;
        $medicoOcupado = 'NO';
                    
        while($rowCita = mysql_fetch_array($sql))
        {
            $numeroCitas++;
            if($idMedico > 0)
            {
//                echo $rowCita['id_medico'].' '.$idMedico;
                if($rowCita['id_medico'] == $idMedico)
                {
                    $medicoOcupado = 'SI';
                }
            }
        } 

        if($numeroCitas < $numeroEquipos)
        {
            //validacion para no mostrar horarios pasados
            if($fecha == $fechaActual)
            {
                $horario = substr($row['horario'], 0, 2) ;
                if($horario <=  $horaActual)
                {
                    $medicoOcupado = 'SI';
                }
            }
                            
            
            if($medicoOcupado == 'NO')
            {                
                    $json[] = array(
                    'id' => $row['id'],
                    'reg' => $row['horario']
                    );
            }
        }
    }

    echo json_encode($json);

}
else if($strAccion == 'crear')
{
    
    $idPaciente     = $_POST['paciente'];
    $idHorario      = $_POST['horario'];    
    $strObservacion = $_POST['observacion'];
    $idMotivo       = $_POST['idMotivo'];
    $status         = 'PENDIENTE';
    $consulta       = 'PENDIENTE';
    
    //antes de crear verifico si el paciente ya tiene cita para ese horario
    $idCita = consultar('id','citas_medicas', "id_paciente = $idPaciente and consultorio = $id and fecha_cita = '$fecha' and horario = $idHorario and status = 'PENDIENTE'");

    if(!$idCita)
    {
        $strSql = "INSERT INTO citas_medicas (id_paciente, id_medico, consultorio, fecha_cita, horario, observaciones, status, consulta, id_motivo)	
                              VALUES ('$idPaciente','$idMedico','$id','$fecha', '$idHorario', '$strObservacion', '$status','$consulta','$idMotivo')";
        $query = mysql_query($strSql);
        if($query)
        {
            $objGeneral = new ControladorGeneral;
            //enviar notificacion
            $objPaciente = $objGeneral->getCorreoPorId($idPaciente, 'PACIENTE');
            $objMedico = $objGeneral->getCorreoPorId($idMedico, 'USUARIO');
            $strHorario = consultar('horario','horarios_cita',' id='.$idHorario);

            $destinatarios = $objPaciente['email'].','.$objMedico['email'];
            $asunto = 'Cita medica realizada con exito';
            $cuerpo = 'Estimado Sr(a). '.$objPaciente['nombre'].', <br> <br> Se ha realizado con exito su cita para  '.$fecha. ' '.$strHorario.' con el Dr. '.$objMedico['nombre'].'.<br> <br> Saludos Cordiales.'  ;

            $objGeneral-> enviarCorreo($destinatarios, $asunto, $cuerpo);
            
            $json['status']     = 'OK';
            $json['message']    = 'OK';
            echo json_encode($json);            
        }
    }
    else
    {
        $json['status']     = 'ERROR';
        $json['message']    = 'El paciente ya tiene una cita para ese horario.';
        echo json_encode($json);
    }
    
}
else if($strAccion == 'medicos')
{
    
    $idHorario   = $_POST['horario'];

    $strSql = "SELECT * FROM citas_medicas WHERE consultorio = " . $id . " and fecha_cita ='" . $fecha . "' and  horario = " . $idHorario;

    $sql = mysql_query($strSql);

    while($rowCita = mysql_fetch_array($sql))
    {
//                echo $rowCita['id_medico'].' '.$idMedico;
        if($rowCita['id_medico'])
        {
            if($idsMedicos)
            {
                $idsMedicos = $rowCita['id_medico'].','.$idsMedicos;
            }else
            {
                $idsMedicos = $rowCita['id_medico'];
            }
        }

    }    

    //si hay medicos los filtro
    if($idsMedicos)
    {
        $strSqlMedicos = "SELECT * FROM usuario where cargo = 'MEDICO' and consultorio = $id AND estado = 's' and id NOT IN ($idsMedicos)";
    }
    else
    {
        //si no hay medicos los envio
        $strSqlMedicos = "SELECT * FROM usuario where cargo = 'MEDICO' and consultorio = $id AND estado = 's' ";        
    }
    
    $sqlMedicos = mysql_query($strSqlMedicos);

    while($rowMedicos = mysql_fetch_array($sqlMedicos))
    {
        
        $json[] = array(
            'id' => $rowMedicos['id'],
            'reg' => $rowMedicos['nombre']
        );
    }  
    
    echo json_encode($json);
    
}
else if($strAccion == 'delete')
{
    if($idRegistro)
    {
        $strSql = "update citas_medicas set status = 'ELIMINADO' where id=" . $idRegistro;
        $sql = mysql_query($strSql);
        if($sql)
        {
            $mensaje = 'OK';
        }
    }
    echo json_encode($mensaje);
    
}
else
{
    echo 'No se ha indicado ninguna accion';
}
?>