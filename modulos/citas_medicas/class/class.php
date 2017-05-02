<?php
// Activar errores
/*
 * ini_set('display_errors', 'On');
ini_set('display_errors', 1);
*/

include_once '../general/ControladorGeneral.php';


class Proceso_Cita{
    
	var $id;	
	var $id_paciente;	
	var $id_medico;	
	var $id_consultorio;	
	var $fechai;		
	var $observaciones;
	var $fecha;	
	var $hora;
	var $horario;
	var $status;
	var $consulta;
	
	function __construct($id,$id_paciente,$id_medico,$id_consultorio,$fechai,$observaciones,$fecha,$hora,$horario,$status,$consulta){
		$this->id=$id;						
		$this->id_paciente=$id_paciente;						
		$this->id_medico=$id_medico;						
		$this->id_consultorio=$id_consultorio;						
		$this->fechai=$fechai;																												
		$this->observaciones=$observaciones;	
		$this->fecha=$fecha;	
		$this->hora=$hora;
		$this->horario=$horario;
		$this->status=$status;
		$this->consulta=$consulta;		
	}
	
	function crear(){
        
        $objGeneral = new ControladorGeneral;
        
		$id=$this->id;						
		$id_paciente=$this->id_paciente;					
		$id_medico=$this->id_medico;					
		$id_consultorio=$this->id_consultorio;					
		$fechai=$this->fechai;																						
		$observaciones=$this->observaciones;	
		$fecha=$this->fecha;	
		$hora=$this->hora;
		$horario=$this->horario;
		$status=$this->status;
		$consulta=$this->consulta;		
 
		$query = mysql_query("INSERT INTO citas_medicas (id_paciente, id_medico, consultorio, fechai, observaciones, fecha, hora, horario, status, consulta)	
							VALUES ('$id_paciente','$id_medico','$id_consultorio','$fechai','$observaciones','$fecha','$hora','$horario','$status','$consulta')");
        if($query)
        {
            //enviar notificacion
            $objPaciente = $objGeneral->getCorreoPorId($id_paciente, 'PACIENTE');
            $objMedico = $objGeneral->getCorreoPorId($id_medico, 'USUARIO');       

            $destinatarios = $objPaciente['email'].','.$objMedico['email'];
            $asunto = 'Cita medica realizada con exito';
            $cuerpo = 'Estimado Sr(a). '.$objPaciente['nombre'].', <br> <br> Se ha realizado con exito su cita para  '.$fechai. ' '.$horario.' con el Dr. '.$objMedico['nombre'].'.<br> <br> Saludos Cordiales.'  ;

            $objGeneral-> enviarCorreo($destinatarios, $asunto, $cuerpo);
        }
        

	}
	
	function actualizar(){
		$id=$this->id;						
		#$id_paciente=$this->id_paciente;					
		#$id_medico=$this->id_medico;					
		$fechai=$this->fechai;								
		$horario=$this->horario;								
		$observaciones=$this->observaciones;				
				
		mysql_query("UPDATE cita_medicas SET fechai='$fechai',												  
												  observaciones='$observaciones'
												  horario='$horario' 
												WHERE id='$id'");
	}
}
?>