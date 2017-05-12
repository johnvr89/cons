<?php
class Proceso_Paciente{
	var $id;	
	var $documento;
	var $seguro;
	var $nombre;
    var $direccion;		
	#var $departamento;	
	#var $municipio;		
	var $telefono;		
	var $edad;		
	var $sexo;		
	var $email;	
	var $estado;
	var $id_consultorio;
	
	function __construct($id,$documento,$seguro,$nombre,$direccion,$telefono,$edad,$sexo,$email,$estado,$id_consultorio){
		$this->id=$id;		
		$this->documento=$documento;
		$this->seguro=$seguro;		
		$this->nombre=$nombre;		
		$this->direccion=$direccion;	
		#$this->departamento=$departamento;
		#$this->municipio=$municipio;
		$this->telefono=$telefono;
		$this->edad=$edad;	
		$this->sexo=$sexo;	
		$this->email=$email;			
		$this->estado=$estado;	
		$this->id_consultorio=$id_consultorio;	
	}
	
	function crear(){
		$id=$this->id;		
		$documento=$this->documento;
		$seguro=$this->seguro;		
		$nombre=$this->nombre;		
		$direccion=$this->direccion;	
		#$departamento=$this->departamento;
		#$municipio=$this->municipio;
		$telefono=$this->telefono;	
		$edad=$this->edad;	
		$sexo=$this->sexo;	
		$email=$this->email;	
		
		$id_consultorio=$this->id_consultorio;	
        
        //si tiene cedula verifico si el paciente ya existe
        if($documento)
        {
            $documento = consultar('documento', 'pacientes', " estado =  's' and documento = ".$documento);
            if($documento)
            {
                $strMensaje = 'Paciente Ya existe.';
                return $strMensaje;
            }
        }
							
		$insert = mysql_query("INSERT INTO pacientes (documento,seguro, nombre, direccion, telefono, edad, sexo, email, estado, consultorio) 
					VALUES ('$documento','$seguro','$nombre','$direccion','$telefono','$edad','$sexo','$email','s','$id_consultorio')");
        
        if($insert)
        {
            return 'OK';
        }
        
	}
	
	function actualizar(){
		$id=$this->id;		
		$documento=$this->documento;
		$seguro=$this->seguro;		
		$nombre=$this->nombre;		
		$direccion=$this->direccion;	
		#$departamento=$this->departamento;
		#$municipio=$this->municipio;
		$telefono=$this->telefono;	
		$edad=$this->edad;	
		$sexo=$this->sexo;	
		$email=$this->email;	
		$estado=$this->estado;	
        
        
		$sql = "UPDATE pacientes SET
										documento='$documento',
										seguro='$seguro',  
										nombre='$nombre',
										direccion='$direccion',
										telefono='$telefono',
										edad='$edad',
										sexo='$sexo',										
										email='$email',
										estado='$estado'
									WHERE id='$id'";

		mysql_query($sql);
        
        
	}
}
?>