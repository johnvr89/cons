<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	if($_SESSION['cod_user']){
	}else{
		header('Location: ../../php_cerrar.php');
	}	
	
   
	$usu=$_SESSION['cod_user'];
    
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom');

    $idFactura = $_GET['idFactura'];         
    $total = $_GET['total'];
    $tipoPago = $_GET['tipo_pago']; 
    $proceso = $_GET['proceso'];
    
    if($idFactura)
    {
        $sql = "SELECT * FROM factura WHERE id = ".$idFactura;
        $pa=mysql_query($sql);			
        while($row=mysql_fetch_array($pa)){
            $idPaciente = $row['paciente_id'];
            $fechaIngresa = $row['fecha_ingresa'];
            $nombrePaciente = consultar('nombre','pacientes',' id='.$idPaciente);
            $idConsulta = $row['consulta_id'];
            
            if($idConsulta)
            {
                $idCita = consultar('cita_id','consultas_medicas',' id='.$idConsulta);
            }
        }
        //SI EL PROCESO ES DE CONSULTA NO EJECUTO NINGUNA SENTENCIA
        if($proceso != 'CONSULTA')
        {
            
            ######## GUARDAMOS LA INFORMACION DE LA FACTURA EN LA TABLA COMPRA
            $sql = "Update factura Set estado='PROCESADO', tipo_pago = '$tipoPago', valor = $total Where id='$idFactura'" ;
            mysql_query($sql);
            $sql = "Update citas_medicas Set status='PROCESADO' Where id='$idCita'";
            mysql_query($sql);
            $sql = "Update consultas_medicas  Set status='PROCESADO' Where id='$idConsulta'";
            mysql_query($sql);
        }
        
    }
    
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Consultorio</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../../assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
   
        <!-- CUSTOM STYLES-->
    <link href="../../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="../../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
	
	<script>
		function imprimir(){
		  var objeto=document.getElementById('imprimeme');  //obtenemos el objeto a imprimir
		  var ventana=window.open('','_blank');  //abrimos una ventana vacía nueva
		  ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
		  ventana.document.close();  //cerramos el documento
		  ventana.print();  //imprimimos la ventana
		  ventana.close();  //cerramos la ventana
		}
	</script>
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
font-size: 16px;"> Fecha de Acceso : <?php echo fecha(date('Y-m-d')); ?> &nbsp; <a href="../../php_cerrar.php" class="btn btn-danger square-btn-adjust">Salir</a> </div>
        </nav>   
           <?php include_once "../../menu/m_caja.php"; ?>
        <div id="page-wrapper" >
            <div id="page-inner">
				<center><button onclick="imprimir();" class="btn btn-default"><i class=" fa fa-print "></i> Imprimir</button></center>
				 <div id="imprimeme">
				<table width="100%">
                                        	<tr>
                                                <td>
                                                    <center>
                                                    	<strong><?php echo $_SESSION['nombreConsultorio']; ?></strong><br>
                                                        <img src="<?php echo $_SESSION['logo']; ?>" width="125px" height="100px"><br><br><br>
                                                        <strong><?php echo $_SESSION['nombreEmpresa']; ?></strong><br>
                                                    </center>
                                                </td>
                                                <td><br>
                                                    <strong>PACIENTE: </strong><?php echo $nombrePaciente; ?><br>
                                                    <strong>FECHA: </strong><?php echo $fechaIngresa; ?>  <br>
                                                    <strong>USUARIO/A: </strong><?php echo $cajero_nombre; ?>
                                                </td>
                                            </tr>
                                        </table>
				
                 <!-- /. ROW  -->
                 <hr /><br><br>
                <!-- /. ROW  -->
			<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             DETALLES DEL SERVICIO
                        </div><br>
						<div class="table-responsive">
                        <div class="panel-body">                                                                 	                                                                                               
                          <table class="table table-striped table-bordered table-hover" width="100%" rules="all"  border="1">
                                        	<tr>
												<td align = 'center'><strong>CANTIDAD</strong></td>                                              
                                                <td align = 'center'><strong>SERVICIO</strong></td>
                                                <td><div align="right"><strong>COSTO UNIT</strong></div></td>
                                                <td><div align="right"><strong>TOTAL</strong></div></td>
                                            </tr>
                                            <?php 
												$item=0;
												$neto=0;
												$neto_full=0;
                                                
                                                $sql = "SELECT * FROM detalle WHERE factura_id = ".$idFactura;
                                                
                                                $pa=mysql_query($sql);			
                                                while($row=mysql_fetch_array($pa))
                                                {
                                                    $nombreTarifa = '';
                                                    $nombreTarifa = consultar('nombre','tarifas',' id='.$row['tarifa_id']);
																				
																																																											
											?>
                                                <tr>
                                                 <td align = 'center'><?php echo $row['cantidad']; ?></td>                           	                                
                                                 <td align = 'center'> <?php echo $nombreTarifa; ?> </td>
                                                 <td align = 'right' >$<?php echo $row['valor']; ?></td>
                                                 <td align = 'right' >$<?php echo $total = $row['valor'] * $row['cantidad']; ?></td>
                                                </tr>	
                              
											<?php 
                                            
                                            $granTotal =  $total + $granTotal;
                                            
                                                } ?>
                                            <tr>
                                              <td colspan="3"><div align="right"><strong>Total</strong></div></td>
                                              <td><div align="right"><strong>$ <?php echo formato($granTotal); ?></strong></div></td>
                                            </tr>
                                        </table>                                                                                                                          
                        </div>
                    </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
                <!-- /. ROW  -->  
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                     <br>
										<center>
                                        	<?php echo $nombre_empresa; ?><br>
                                            <?php echo $tel_empresa; ?><br>
                                            <?php echo $pais_empresa; ?><br>
                                            <?php echo $dir_empresa; ?><br>
                                        </center>
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
			</div>
			<?php 
		
	?>
         <!-- /. PAGE WRAPPER  -->
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
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
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
         <!-- CUSTOM SCRIPTS -->
    <script src="../../assets/js/custom.js"></script>
    
   
</body>
</html>
