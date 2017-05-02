<?php
//linea cron
//*/5	*	*	*	*	php /home/gigacorp/public_html/consultorio/modulos/cron_recordatorio.php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');


//include_once "general/ControladorGeneral.php";
include_once "php_conexion.php";
include_once "funciones.php";
//require "../PHPMailer/_lib/class.phpmailer.php";
require "../PHPMailer/PHPMailerAutoload.php";

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "mail.sistemassmart.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25; //465
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "info@sistemassmart.com";
//Password to use for SMTP authentication
$mail->Password = "info247.";
//Set who the message is to be sent from
$mail->setFrom('info@sistemassmart.com', 'Sistemas Smart');
//Set an alternative reply-to address
$mail->addReplyTo('info@sistemassmart.com', 'Sistemas Smart');

//$objGeneral = new ControladorGeneral;

$horaActual = date("H")+3;        

 $strSql = "SELECT hc.horario nombre_horario, cm.* 
             FROM citas_medicas cm, 
                  horarios_cita hc 
            WHERE hc.id = cm.horario 
              AND cm.fecha_cita = date(sysdate())  
              AND cm.recordatorio = 'NO'
              AND hc.horario like '$horaActual%' ";
 
// $strSql = "SELECT hc.horario nombre_horario, cm.* 
//             FROM citas_medicas cm, 
//                  horarios_cita hc 
//            WHERE hc.id = cm.horario 
//              AND cm.fecha_cita = date(sysdate())"; 

$sql = mysql_query($strSql);	

while($row = mysql_fetch_array($sql))
{
    
    $nombrePaciente = consultar('nombre', 'pacientes', 'id = '.$row['id_paciente'] );
    $destinatario = consultar('email', 'pacientes', 'id = '.$row['id_paciente']);
    $nombreMedico = consultar('nombre', 'usuario', 'id = '.$row['id_medico']);
    $nombreConsultorio = consultar('nombre', 'consultorios', 'id = '.$row['consultorio']);
    
    if($nombreMedico)
    {
        $strCuerpo = " con el Dr ".$nombreMedico;
    }
    
    $asunto = 'Recordatorio Cita Medica';
    $cuerpo = "Estimado $nombrePaciente,<br><br> El presente es para recordarle que tiene una cita médica hoy a las ".
               $row['nombre_horario'].$strCuerpo.". <br><br>Atentamente,<br>".strtoupper($nombreConsultorio);
    
    if($destinatario && $asunto && $cuerpo)
    {
        $mail->addAddress($destinatario, $nombrePaciente);
        $mail->addAddress('lizzi.diaz@yahoo.com', 'Lizzi diaz');

        //Set the subject line
        $mail->Subject = $asunto;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        $mail->msgHTML($cuerpo, dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';

        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
            $strUpdate = "update citas_medicas set recordatorio = 'SI' where id = ".$row['id'];
            mysql_query($strUpdate);
        }
    
    }
    else
    {
        echo "Falta informacion para enviar el correo";
    }
}

echo 'nothing';

?>