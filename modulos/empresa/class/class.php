<?php

class Proceso_Empresa{
	var $id;	
	var $nombre;	
	var $direccion;	
	var $telefono;	
	var $fax;	
	var $ruc;
    var $web;
    var $pais;
    var $ciudad;
    var $estado;
	
	function __construct($arrayParametros){

        $this->id       = $arrayParametros ['id'];
        $this->nombre   =$arrayParametros ['nombre'];
        $this->direccion=$arrayParametros ['direccion'];
        $this->telefono =$arrayParametros ['telefono'];
        $this->fax      =$arrayParametros ['fax'];
        $this->ruc      =$arrayParametros ['ruc'];
        $this->web      =$arrayParametros ['web'];
        $this->pais     =$arrayParametros ['pais'];
        $this->ciudad   =$arrayParametros ['ciudad'];
        $this->correo   =$arrayParametros ['correo'];
        $this->estado   =$arrayParametros ['estado'];
        $this->logo     =$arrayParametros ['logo'];
        
	}
	
	function crear(){

        $sql = "INSERT INTO  empresa  (
                             id  ,
                             nombre  ,
                             ruc  ,
                             direccion  ,
                             pais  ,
                             ciudad  ,
                             telefono  ,
                             fax  ,
                             web  ,
                             correo  ,
                             estado 
                            )
                            VALUES (
                            '',  
                            '$this->nombre',  
                            '$this->ruc',  
                            '$this->direccion',  
                            '$this->pais',  
                            '$this->ciudad',  
                            '$this->telefono',  
                            '$this->fax',  
                            '$this->web',  
                            '$this->correo',  
                            'A' )";
							
		mysql_query($sql);
	}
	
	function actualizar(){
       
        $sql = "UPDATE empresa 
               SET nombre   = '$this->nombre',
                   ruc      = '$this->ruc',  
                   direccion= '$this->direccion',  
                   pais     = '$this->pais',  
                   ciudad   = '$this->ciudad',  
                   telefono = '$this->telefono',  
                   fax      = '$this->fax',  
                   web      = '$this->web',  
                   correo   = '$this->correo',
                   estado   = '$this->estado',
                   logo     = '$this->logo'
                   where id = $this->id";       
        
		mysql_query($sql);
	}
}
?>