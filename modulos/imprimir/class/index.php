<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	if(!empty($_GET['id'])){
		$factura=$_GET['id'];
	}else{
		header('Location:error.php');
	}
	if($_SESSION['cod_user']){
	}else{
		header('Location: ../../php_cerrar.php');
	}
	
	$usu=$_SESSION['cod_user'];
	
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom');
	$fecha=date('Y-m-d');
	$hora=date('H:i:s');
	
	$usu=$_SESSION['cod_user'];
	$pa=mysql_query("SELECT * FROM cajero WHERE usu='$usu'");				
	while($row=mysql_fetch_array($pa)){
		$id_consultorio=$row['consultorio'];
		$oConsultorio=new Consultar_Deposito($id_consultorio);
		$nombre_Consultorio=$oConsultorio->consultar('nombre');
	}
	
		$pa=mysql_query("SELECT * FROM empresa WHERE id=".$_SESSION['idEmpresa']);				
        if($row=mysql_fetch_array($pa)){
			$nombre_empresa=$row['empresa'];
			$nit_empresa=$row['nit'];
			$dir_empresa=$row['direccion'];
            
            if($row['fax'])
            {
                $tel_empresa=$row['telefono'].'-'.$row['fax'];
            }
            $tel_empresa = $row['telefono'];
			
			$pais_empresa=$row['pais'].' - '.$row['ciudad'];
		}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Consultorio Medico</title>
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
font-size: 16px;">Consultorio: <?php echo $nombre_Consultorio; ?> :: Fecha de Acceso : <?php echo fecha(date('Y-m-d')); ?> &nbsp; <a href="../../php_cerrar.php" class="btn btn-danger square-btn-adjust">Salir</a> </div>
        </nav>   
           <?php include_once "../../menu/m_consulta_medica.php"; ?>
        <div id="page-wrapper" >
            <div id="page-inner">
				<div class="alert alert-info" align="center">
                    <h5><strong>DETALLE DE CONSULTA</strong><h5>
                    </div> 
                 <!-- /. ROW  -->
<?php if(permiso($_SESSION['cod_user'],'3')==TRUE){ ?>
				 <center><button onclick="imprimir();" class="btn btn-default"><i class=" fa fa-print "></i> Imprimir</button></center>
				 
				<table width="100%">
				<?php											
					$pa=mysql_query("SELECT * FROM consultas_medicas WHERE id='$factura'");				
						while($row=mysql_fetch_array($pa)){
							$oPaciente=new Consultar_Paciente($row['id_paciente']);
							#$oPaciente=new Consultar_Paciente($row['id_paciente']);
				?>
                 <tr>
                    <td>
						<center>
	                    <img src="../../img/logo.jpg" width="75px" height="75px"><br>
	                    <!--<strong><?php echo $nombre_empresa; ?></strong><br>-->
	                    </center>                                                    
                    </td>
                    <td>
						<center>
	                    <h2><strong><?php echo $nombre_Consultorio; ?></strong></h2>
	                    <strong>ORTODISTA Y TRAUMATOLOGA</strong><br>
	                    			<strong>JVPM 7511</strong><br>
	                    		Post-grado Hospital Docente Universitario
	                   				 Dr. Dario Contreras, R.D.<br>
	                    <!--<strong><?php echo $nombre_empresa; ?></strong><br>-->
	                    </center>                                                    
                    </td>
                    <td>
                    	<center>
	                    <img src="../../img/logo_dos.png" width="75px" height="75px"><br>
	                    </center> 
                    </td>
                 </tr>                       	
                </table><br><br>
                <hr/>
                    <!-- /. TABLA  -->
                    	<strong>EXPEDIENTE: </strong><?php echo $factura; ?><br>				
                        <strong>PACIENTE: </strong><?php echo $oPaciente->consultar('nombre'); ?><br>
                        <strong>DIRECCION: </strong><?php echo $oPaciente->consultar('direccion'); ?><br>
                        <strong>TELEFONO: </strong><?php echo $oPaciente->consultar('telefono'); ?><br>
                        <strong>FECHA: </strong><?php echo fecha($fecha); ?> ||  
                        <strong>HORA: </strong><?php echo date($hora); ?><br>
                        <strong>USUARIO: </strong><?php echo $cajero_nombre; ?><br> 
						<div align="right">
                        <strong>MEDICO: </strong><?php echo $nombre_Consultorio; ?><br><br>
						<div>
						<hr/>
			
            <div class="row">           
                <div class="col-md-12">
                    <!-- Advanced Tables -->                    
                            <div class="table-responsive">
                            <?php											
								$pa=mysql_query("SELECT * FROM medicamentos WHERE consulta='$factura'");				
									while($row=mysql_fetch_array($pa)){
										$oPaciente=new Consultar_Paciente($row['paciente']);
										#$oPaciente=new Consultar_Paciente($row['id_paciente']);
							?>			
							<div id="imprimeme">
											<br><br><br><br><br>
											<div class="col-md-6 col-sm-12 col-xs-12">
											<div class="panel panel-primary" align="left" style="font-size: 12px">
											  <div class="panel-heading">
											  </div>
											  <div class="panel-body">
											  <ul>
												<li><strong><?php echo $row['med1']; ?></strong></li>
												<?php echo $row['indi1']; ?><br>
												
												<li><strong><?php echo $row['med2']; ?></strong></li>
												<?php echo $row['indi2']; ?><br>
											
												<li><strong><?php echo $row['med3']; ?></strong></li>
												<?php echo $row['indi3']; ?><br>
												
												<li><strong><?php echo $row['med4']; ?></strong></li>
												<?php echo $row['indi4']; ?><br>
												
												<li><strong><?php echo $row['med5']; ?></strong></li>
												<?php echo $row['indi5']; ?><br>
												
												<li><strong><?php echo $row['med6']; ?></strong></li>
												<?php echo $row['indi6']; ?><br>
												
												<li><strong><?php echo $row['med7']; ?></strong></li>
												<?php echo $row['indi7']; ?><br>
												
												<li><strong><?php echo $row['med8']; ?></strong></li>
												<?php echo $row['indi8']; ?><br>
												
												<li><strong><?php echo $row['med9']; ?></strong></li>
												<?php echo $row['indi9']; ?><br>
												
												<li><strong><?php echo $row['med10']; ?></strong></li>
												<?php echo $row['indi10']; ?>
											
												</ul>
											  </div>
											</div>
											 <table width="310px" border="0" style="font-size: 12px">
											 <tr>
						                        <td></td>
						                        <td></td>
						                        <td></td>
						                        <td></td>
						                        </tr>						                   					                                                                          
						                        <tr>
						                        <td colspan="3" align="right"> <?php echo $oPaciente->consultar('nombre'); ?></center></td>
						                        </tr><br>
						                        <tr>
						                        <td colspan="0" align="center"><?php echo CalculaEdad($oPaciente->consultar('edad')); ?> Años</td>
						                        <td colspan="3" align="right"> <?php echo fecha($fecha); ?></td>
						                        </tr>						                                                              						                                                           
						                     </table>
							                </div> 
							</div>   
											                            																												
										<?php }} ?>                                    																		
									 <br>									 
                                                                                               
                    <!--End Advanced Tables -->
                </div>
            </div>
                <!-- /. ROW  --> 
			</div>
									 <hr/>
									<center>
										<strong><?php echo $nombre_empresa; ?></strong><br>
										<strong><?php echo $tel_empresa; ?></strong><br>
										<strong><?php echo $pais_empresa; ?></strong><br>
										<strong><?php echo $dir_empresa; ?></strong><br>
									</center>
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
			 <?php }else{ echo mensajes("NO TIENES PERMISO PARA ENTRAR A ESTE FORMULARIO","rojo"); }?>
            </div>
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
