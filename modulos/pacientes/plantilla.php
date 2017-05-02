<?php
session_start();
include_once "../php_conexion.php";
include_once "class/class.php";
include_once "../funciones.php";
include_once "../class_buscar.php";
if(!empty($_GET['id']))
{
    $factura = $_GET['id'];
}
else
{
    header('Location:error.php');
}


$usu = $_SESSION['cod_user'];


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Consultorio Medico</title>
        <!-- BOOTSTRAP STYLES-->
        <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
        <link href="../../assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
        <link href="../../assets/css/custom.css" rel="stylesheet" />
        <!-- GOOGLE FONTS-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <!-- TABLE STYLES-->
        <link href="../../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />        
        <!-- JQUERY SCRIPTS -->
        <script src="../../assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS -->
        <script src="../../assets/js/bootstrap.min.js"></script>
        <!-- METISMENU SCRIPTS -->
        <script src="../../assets/js/jquery.metisMenu.js"></script>
        <!-- DATA TABLE SCRIPTS -->
        <script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="../../assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- VALIDACIONES -->
        <script src="../../assets/js/jasny-bootstrap.min.js"></script>
        <!-- CUSTOM SCRIPTS -->
        <script src="../../assets/js/custom.js"></script>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="../images/ico/favicon.ico">
         <!-- 

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link href="css/navbar-fixed-top.css" rel="stylesheet">
        
        -->

    </head>
    <body>
        <div id="wrapper">
            <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../usuarios/perfil.php"><?php echo $_SESSION['user_name']; ?></a> 
                </div>
                <div style="color: white;
                     padding: 15px 50px 5px 50px;
                     float: right;
                     font-size: 16px;">Consultorio: <?php echo $nombre_Consultorio; ?> :: Fecha de Acceso : <?php echo fecha(date('Y-m-d')); ?> &nbsp; <a href="../../php_cerrar.php" class="btn btn-danger square-btn-adjust">Salir</a> </div>
            </nav>   
            <?php include_once "../../menu/m_pacientes.php"; ?>
            <div id="page-wrapper" >
                <div id="page-inner">						                
                    <div class="alert alert-info" align="center">
                        <h3>PLANTILLA</h3>
                    </div>
                    <div>
                        aqui va el contenido.
                    </div>

                </div>
            </div>               
        </div>
    </body>
</html>
