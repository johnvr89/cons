<?php
class Proceso_Consulta{
	var $id;	
	var $id_paciente;	
	var $id_medico;	
	var $id_consultorio;	
	var $sintomas;
	var $examen;	
	var $diagnostico;	
	var $tratamiento;	
	var $reseta;	
	var $observaciones;
	var $fecha;
	var $hora;
	var $status;
	var $med1;
	var $indi1;
	var $med2;
	var $indi2;
	var $med3;
	var $indi3;
	var $med4;
	var $indi4;
	var $med5;
	var $indi5;
	var $med6;
	var $indi6;
	var $med7;
	var $indi7;
	var $med8;
	var $indi8;
	var $med9;
	var $indi9;
	var $med10;
	var $indi10;
	
	function __construct($id,$id_paciente,$id_medico,$id_consultorio,$sintomas,$examen,$diagnostico,$tratamiento,$reseta,$observaciones,$fecha,$hora,$status,
		                 $med1,$indi1,$med2,$indi2,$med3,$indi3,$med4,$indi4,$med5,$indi5,$med6,$indi6,$med7,$indi7,$med8,$indi8,$med9,$indi9,$med10,$indi10, $idCita, $arrayDetalles){
		$this->id=$id;						
		$this->id_paciente=$id_paciente;						
		$this->id_medico=$id_medico;						
		$this->id_consultorio=$id_consultorio;						
		$this->sintomas=$sintomas;
		$this->examen=$examen;							
		$this->diagnostico=$diagnostico;						
		$this->tratamiento=$tratamiento;						
		$this->reseta=$reseta;						
		$this->observaciones=$observaciones;	
		$this->fecha=$fecha;	
		$this->hora=$hora;	
		$this->status=$status;
		$this->med1=$med1;
		$this->indi1=$indi1;
		$this->med2=$med2;
		$this->indi2=$indi2;
		$this->med3=$med3;
		$this->indi3=$indi3;
		$this->med4=$med4;
		$this->indi4=$indi4;
		$this->med5=$med5;
		$this->indi5=$indi5;
		$this->med6=$med6;
		$this->indi6=$indi6;
		$this->med7=$med7;
		$this->indi7=$indi7;
		$this->med8=$med8;
		$this->indi8=$indi8;
		$this->med9=$med9;
		$this->indi9=$indi9;
		$this->med10=$med10;
		$this->indi10=$indi10;	
        $this->idCita=$idCita;	
        $this->arrayDetalles=$arrayDetalles;	
	}
	
	function crear(){
		$id=$this->id;						
		$id_paciente=$this->id_paciente;					
		$id_medico=$this->id_medico;					
		$id_consultorio=$this->id_consultorio;					
		$sintomas=$this->sintomas;
		$examen=$this->examen;						
		$diagnostico=$this->diagnostico;					
		$tratamiento=$this->tratamiento;					
		$reseta=$this->reseta;					
		$observaciones=$this->observaciones;	
		$fecha=$this->fecha;	
		$hora=$this->hora;	
		$status=$this->status;
		$med1=$this->med1;
		$indi1=$this->indi1;	
		$med2=$this->med2;
		$indi2=$this->indi2;	
		$med3=$this->med3;
		$indi3=$this->indi3;	
		$med4=$this->med4;
		$indi4=$this->indi4;	
		$med5=$this->med5;
		$indi5=$this->indi5;	
		$med6=$this->med6;
		$indi6=$this->indi6;	
		$med7=$this->med7;
		$indi7=$this->indi7;	
		$med8=$this->med8;
		$indi8=$this->indi8;	
		$med9=$this->med9;
		$indi9=$this->indi9;	
		$med10=$this->med10;
		$indi10=$this->indi10;	
							
		mysql_query("INSERT INTO consultas_medicas (id_paciente, id_medico,consultorio,sintomas,examen,diagnostico,tratamiento,reseta,observaciones,fecha,hora,status)	
							VALUES ('$id_paciente','$id_medico','$id_consultorio','$sintomas','$examen','$diagnostico','$tratamiento','$reseta','$observaciones','$fecha','$hora','$status')");
		mysql_query("Update citas_medicas Set consulta='CONSULTADO' Where id_paciente='$id_paciente'");	

		$cans=mysql_query("SELECT MAX(id) AS id FROM consultas_medicas");
			if($dat=mysql_fetch_array($cans))
			$id_consulta =$dat['id'];
			{
				$xSQL="INSERT INTO medicamentos (consulta,paciente,med1,indi1,med2,indi2,med3,indi3,med4,indi4,med5,indi5,med6,indi6,med7,indi7,med8,indi8,med9,indi9,med10,indi10,fecha,consultorio)	
			                        VALUES ('$id_consulta','$id_paciente','$med1','$indi1','$med2','$indi2','$med3','$indi3','$med4','$indi4','$med5','$indi5','$med6','$indi6','$med7','$indi7','$med8','$indi8','$med9','$indi9','$med10','$indi10','$fecha','$id_consultorio')";
				mysql_query($xSQL);
			}	
	}
	
	function crearFactura()
    {
		$id=$this->id;						
		$id_paciente=$this->id_paciente;					
		$id_medico=$this->id_medico;					
		$id_consultorio=$this->id_consultorio;					
		$sintomas=$this->sintomas;
		$examen=$this->examen;						
		$diagnostico=$this->diagnostico;					
		$tratamiento=$this->tratamiento;					
		$reseta=$this->reseta;					
		$observaciones=$this->observaciones;	
		$fecha=$this->fecha;	
		$hora=$this->hora;	
		$status=$this->status;
		$med1=$this->med1;
		$indi1=$this->indi1;	
		$med2=$this->med2;
		$indi2=$this->indi2;	
		$med3=$this->med3;
		$indi3=$this->indi3;	
		$med4=$this->med4;
		$indi4=$this->indi4;	
		$med5=$this->med5;
		$indi5=$this->indi5;	
		$med6=$this->med6;
		$indi6=$this->indi6;	
		$med7=$this->med7;
		$indi7=$this->indi7;	
		$med8=$this->med8;
		$indi8=$this->indi8;	
		$med9=$this->med9;
		$indi9=$this->indi9;	
		$med10=$this->med10;
		$indi10=$this->indi10;	
        $idCita=$this->idCita;
        $arrayDetalles=$this->arrayDetalles;

		mysql_query("INSERT INTO consultas_medicas (id_paciente, id_medico,consultorio,sintomas,examen,diagnostico,tratamiento,reseta,observaciones,fecha,hora,status, cita_id)	
							VALUES ('$id_paciente','$id_medico','$id_consultorio','$sintomas','$examen','$diagnostico','$tratamiento','$reseta','$observaciones','$fecha','$hora','$status', $idCita)");
		mysql_query("Update citas_medicas Set consulta='CONSULTADO' Where id='$idCita'");	

		$cans=mysql_query("SELECT MAX(id) AS id FROM consultas_medicas");
        if($dat=mysql_fetch_array($cans))
        $id_consulta =$dat['id'];
        {
            $xSQL="INSERT INTO medicamentos (consulta,paciente,med1,indi1,med2,indi2,med3,indi3,med4,indi4,med5,indi5,med6,indi6,med7,indi7,med8,indi8,med9,indi9,med10,indi10,fecha,consultorio)	
                                VALUES ('$id_consulta','$id_paciente','$med1','$indi1','$med2','$indi2','$med3','$indi3','$med4','$indi4','$med5','$indi5','$med6','$indi6','$med7','$indi7','$med8','$indi8','$med9','$indi9','$med10','$indi10','$fecha','$id_consultorio')";
            mysql_query($xSQL);
            
        }	
        
        
        $valorTotal = 0;
       //creo la factura y los detalles
        $sqlFactura = "INSERT INTO factura(id, cod_factura, valor, valor_diferencia, paciente_id, consulta_id, consultorio_id, tipo_pago, valor_impuesto, estado, user_ingresa) 
                       VALUES (null,0,0,0,$id_paciente, $id_consulta,$id_consultorio,null,0,'PENDIENTE', '".$_SESSION['cod_user']."')";
        $resultado = mysql_query($sqlFactura);
        $idFactura  = mysql_insert_id();        
        if (!$resultado) {
            die('Error: ' . mysql_error().$sqlFactura);
        }        
        
        //mysqli_insert_id()
        for($i = 0; $i < count($arrayDetalles); ++$i)
        {
            
            $arrDetalle = explode('|',$arrayDetalles[$i]) ;
            
            //consulto el valor de la tarifa
            $sqlTarifa = "SELECT valor from tarifas where id =".$arrDetalle[0];
            $resultado = mysql_query($sqlTarifa);
            if (!$resultado) 
            {
                die('Error: ' . mysql_error().$sqlTarifa);
            }
            while($rows=mysql_fetch_array($resultado))
            { 
                $valor = $rows[0];
            } 
            
            $sqlDetalle = "INSERT INTO detalle(id, factura_id, tarifa_id, valor, cantidad, user_ingresa, estado) VALUES (null, $idFactura, $arrDetalle[0] , $valor , $arrDetalle[1], 'javera', 'A')";
            $resultado = mysql_query($sqlDetalle);
            if (!$resultado) 
            {
                die('Error: ' . mysql_error().$sqlDetalle);
            }
            $valorTotal = $valorTotal + $valor;
        }
        
//        //actualizo el valor total de la factura
//        $sqlFactura = "UPDATE factura set valor = $valorTotal where id = $idFactura ";
//        $resultado = mysql_query($sqlFactura);
//        if (!$resultado) 
//        {
//            die('Error: ' . mysql_error().$sqlFactura);
//        }
        
	}    
    
	function actualizar(){
		$id=$this->id;												
		$sintomas=$this->sintomas;
		$examen=$this->examen;						
		$diagnostico=$this->diagnostico;					
		$tratamiento=$this->tratamiento;					
		$reseta=$this->reseta;					
		$observaciones=$this->observaciones;				
				
		mysql_query("UPDATE consultas_medicas SET sintomas='$sintomas',
													examen='$examen',
													diagnostico='$diagnostico',
													tratamiento='$tratamiento',
													reseta='$reseta',
													observaciones='$observaciones' 
													WHERE id='$id'");
	}
}
?>