<?php
class Proceso_Consultorios{
	var $id;	
	var $nombre;	
	var $direccion;	
	var $telefono;	
	var $encargado;	
	var $estado;
    var $empresa;
	
	function __construct($id,$nombre,$direccion,$telefono,$encargado,$estado, $empresa, $unidades){
		$this->id=$id;						
		$this->nombre=$nombre;						
		$this->direccion=$direccion;						
		$this->telefono=$telefono;						
		$this->encargado=$encargado;						
		$this->estado=$estado;	
        $this->empresa=$empresa;
        $this->unidades=$unidades;
	}
	
	function crear(){
		$id=$this->id;						
		$nombre=$this->nombre;					
		$direccion=$this->direccion;					
		$telefono=$this->telefono;					
		$encargado=$this->encargado;					
		$estado=$this->estado;	
        $empresa=$this->empresa;
        $unidades=$this->unidades;
        
        ($empresa) ? $empresa: 0;
        
        $sql = "INSERT INTO consultorios (nombre,direccion,telefono,encargado,estado, empresa_id, unidades_odontologicas) VALUES ('$nombre','$direccion','$telefono','$encargado','$estado', $empresa, $unidades )";
			
		mysql_query($sql);
	}
	
	function actualizar(){
		$id=$this->id;						
		$nombre=$this->nombre;	
		$direccion=$this->direccion;					
		$telefono=$this->telefono;					
		$encargado=$this->encargado;					
		$estado=$this->estado;
        $empresa=$this->empresa;
        $unidades=$this->unidades;
        
        ($empresa) ? $empresa: 0;
				
        $sql = "UPDATE consultorios SET unidades_odontologicas = $unidades,nombre='$nombre',direccion='$direccion',telefono='$telefono',encargado='$encargado', estado='$estado', empresa_id = $empresa WHERE id='$id'";
        
		mysql_query($sql);
	}
}
?>