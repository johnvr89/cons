<?php
// Activar errores
/*
 ini_set('display_errors', 'On');
ini_set('display_errors', 1);
*/
include_once "../php_conexion.php";

//print_r($con);
class ControladorGeneral
{
    
    function enviarCorreo($toaddress, $subject, $message)
    {        
        //echo $toaddress. $subject. $message.'ggg';
//        
//        $toaddress = 'web-mxnpr@mail-tester.com';
//        $subject = 'Cita medica realizada con exito';
//        $message = 'Hola como estas';
        $fromaddress = 'noreply@sistemassmart.com';
        $fromname = 'Sistemas Smart';

        // Please use or modify for any purpose but leave this notice unchanged.
        $headers  = "MIME-Version: 1.0\n";
        //$headers .= "Content-type: text/plain; charset=iso-8859-1\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= "X-Priority: 3\n";
        $headers .= "X-MSMail-Priority: Normal\n";
        $headers .= "X-Mailer: php\n";
        $headers .= "From: \"".$fromname."\" <".$fromaddress.">\n";
        $message = wordwrap($message, 70, "\r\n");
        return mail($toaddress, $subject, $message, $headers);
    }
    
    function getCorreoPorId($id, $tipo)
    {
        if($tipo == 'PACIENTE')
        {
            $sql = "SELECT * FROM pacientes WHERE id ='$id'";
        }
        else
        {
            $sql = "SELECT * FROM usuario WHERE id='$id'";
        }

        $query = mysql_query($sql);

        return mysql_fetch_array($query);
        
    }

}

?>