<?php

class Proceso_Tarifas{
	var $id;	
	var $nombre;	
	var $valor;	
	var $config;	
	var $estado;
    var $idConsultorio;
    	
	function __construct($id,$nombre,$valor,$config,$estado, $idConsultorio){
		$this->id=$id;						
		$this->nombre=$nombre;						
		$this->valor=$valor;						
		$this->config=$config;						
		$this->estado=$estado;	
        $this->idConsultorio=$idConsultorio;	
	}
	
	function crear(){
		$id=$this->id;						
		$nombre=$this->nombre;					
		$valor=$this->valor;					
		$config=$this->config;					
		$estado=$this->estado;	
        $idConsultorio=$this->idConsultorio;	
							
		mysql_query("INSERT INTO tarifas (nombre,valor,config, estado, id_consultorio)	VALUES ('$nombre','$valor','$config','$estado', '$idConsultorio')");
	}
	
	function actualizar(){
		$id=$this->id;		
		$nombre=$this->nombre;					
		$valor=$this->valor;					
		$config=$this->config;					
		$estado=$this->estado;			
				
		mysql_query("UPDATE tarifas SET nombre='$nombre', valor='$valor', config='$config', estado='$estado' WHERE id='$id'");
	}
}
?>