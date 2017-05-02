<?php
	
	$DB_HOST="localhost";//Servidor donde se encuentra alojada nuestra base de datos
	$DB_NAME= "gigacorp_consultorio";// Nombre de la base de datos
	$DB_USER= "gigacorp_admin";//Usuario de la base de datos
	$DB_PASS= "Gatorade12";//Contraseña del usuario de la base de datos	
	
	
	//conexion solo para las fotos
	$con=@mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	
	$conexion = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	mysql_select_db($DB_NAME,$conexion);
	date_default_timezone_set("America/El_Salvador");
    mysql_query("SET NAMES utf8");
	mysql_query("SET CHARACTER_SET utf");
	$s='$';
	
	
	function limpiar($tags){
		$tags = strip_tags($tags);
		return $tags;
	}

	
?>