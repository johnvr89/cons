<?php
$DB_HOST="localhost";//Servidor donde se encuentra alojada nuestra base de datos
$DB_NAME= "gigacorp_consultorio";// Nombre de la base de datos
$DB_USER= "gigacorp_admin";//Usuario de la base de datos
$DB_PASS= "Gatorade12";//Contraseña del usuario de la base de datos
	# conectare la base de datos
    $con=@mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if(!$con){
        die("imposible conectarsee: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Connect failed: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
?>
