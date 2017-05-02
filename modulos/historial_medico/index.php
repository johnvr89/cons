<?php 
session_start();
include_once "../php_conexion.php";
include_once "class/class.php";
include_once "../funciones.php";
include_once "../class_buscar.php";
if(!empty($_GET['id']))
{
    $idPaciente = $_GET['id'];
}
else
{
    header('Location:error.php');
}

if($_SESSION['cod_user'])
{
    
}
else
{
    header('Location: ../../php_cerrar.php');
}

$usu = $_SESSION['cod_user'];
$pa = mysql_query("SELECT * FROM cajero WHERE usu='$usu'");
while($row = mysql_fetch_array($pa))
{
    $id_consultorio = $row['consultorio'];
    $oConsultorio = new Consultar_Deposito($id_consultorio);
    $nombre_Consultorio = $oConsultorio->consultar('nombre');
}
$sqlPaciente = "SELECT * FROM pacientes WHERE consultorio='$id_consultorio' and id='$idPaciente'";
$pa = mysql_query($sqlPaciente);
if($row = mysql_fetch_array($pa))
{
    $nombre = $row['nombre'];
    $direccion = $row['direccion'];
    $telefono = $row['telefono'];
}



$oPersona = new Consultar_Cajero($usu);
$cajero_nombre = $oPersona->consultar('nom');
$fecha = date('Y-m-d');
$hora = date('H:i:s');


######### TRAEMOS LOS DATOS DE LA EMPRESA #############
$pa = mysql_query("SELECT * FROM empresa WHERE id= " . $_SESSION['idEmpresa']);
if($row = mysql_fetch_array($pa))
{
    $nombre_empresa = $row['empresa'];
    $dir_empresa = $row['direccion'];
    $descripcion_empresa = $row['descripcion'];
    $tel_empresa = $row['telefono'] . '-' . $row['fax'];
    $pais_empresa = $row['pais'] . ' - ' . $row['ciudad'];
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
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
           <?php 
                $_SESSION['menu'] = 'PACIENTES';
                include_once "../../menu/m_general.php";
           ?>
        <div id="page-wrapper" >
            <div id="page-inner">						               
				<div class="alert alert-info" align="center">
                    <h5><strong>HISTORIAL MEDICO</strong><h5>
                    </div> 
                 <!-- /. ROW  -->
<?php if(permiso($_SESSION['cod_user'],'3')==TRUE){ ?>
                 				 
				 <center><button onclick="imprimir();" class="btn btn-default"><i class=" fa fa-print "></i> Imprimir!</button></center>
				 <div id="imprimeme"><br>
        <div class="table-responsive">
		<table width="100%" style="border: 1px solid #660000; -moz-border-radius: 12px;-webkit-border-radius: 12px;padding: 10px;">
                <?php                                           
                    $sqlConsulta = "SELECT * FROM consultas_medicas WHERE id_paciente ='$idPaciente'" ;
                    $pa=mysql_query($sqlConsulta);
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
                        <h2><strong><em><?php echo $_SESSION['nombreEmpresa']; ?></em></strong></h2>                    
                        <h3><strong><em><?php echo $nombre_Consultorio; ?></em></strong></h3>
                        <strong><?php echo $tel_empresa; ?></strong><br> 
                        <strong><?php echo $descripcion_empresa; ?></strong><br>
                        <?php echo $dir_empresa; ?><br>
                        </center>                                                    
                    </td>
                    <td>
                        <center>
                        <img src="<?php echo $_SESSION['logo']; ?>" width="125px" height="125px"><br><br>
                        </center> 
                    </td>
                 </tr>                          
                </table>
                </div>
                <hr/>
                    <!-- /. TABLA  -->
                <div style="font-size: 12px;">					
				<strong>PACIENTE: </strong><?php echo $nombre; ?><br>
                <strong>DIRECCION: </strong><?php echo $direccion; ?><br>
                <strong>TELEFONO: </strong><?php echo $telefono; ?><br>
                </div>
                <hr/>  
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->                    
                            <div class="table-responsive">								
                                <table class="table table-striped table-bordered table-hover" width="100%" style="border: 1px solid #660000; -moz-border-radius: 12px;-webkit-border-radius: 12px;padding: 10px;">                                    
									<thead>
                                        <tr>
                                            <th>TRATAMIENTO</th>
                                            <th>OBSERVACIONES</th>
                                            <th>MEDICO</th>
                                            <th>FECHA</th>   
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php											
											$sql = "SELECT * FROM consultas_medicas WHERE consultorio='$id_consultorio' and id_paciente='$idPaciente' ORDER BY fecha desc";
                                            $pa=mysql_query($sql);			
											while($row=mysql_fetch_array($pa)){
													$oPaciente=new Consultar_Paciente($row['id_paciente']);
													$url=$row['id'];
										?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row['tratamiento']; ?></td>                                                                                   
                                            <td><?php echo $row['observaciones']; ?></td>
                                            <td><?php $objUsuario = new ConsultarUsuarioPorId($row['id_medico']); echo $objUsuario->consultar('nombre');  ?></td> 
                                            <td><?php echo fecha($row['fecha']).' '.$row['hora']; ?></td>                                          
                                            <td>
											<a href="../imprimir/index_two.php?id=<?php echo $url; ?>" class="btn btn-primary btn-sm" title="Imprimir">
											<i class="fa fa-print" ></i>
										    </a>
											</td>                                                                                                                               
                                        </tr> 																														
										<?php }} ?>
                                    </tbody>									
                                </table><br>
									  <center> 
                                    <div style="font-size: 10px;">
                                        <strong><?php echo $nombre_empresa; ?></strong><br>
                                        <strong><?php echo $tel_empresa; ?></strong><br>
                                        <strong><?php echo $pais_empresa; ?></strong><br>
                                        <strong><?php echo $dir_empresa; ?></strong><br>
                                    </div>
                                    </center>
                            </div>                                                                     
                    <!--End Advanced Tables -->
                </div>
            </div>
                <!-- /. ROW  --> 
<?php }else{ echo mensajes("NO TIENE PERMISO PARA VISUALIZAR A ESTE FORMULARIO","rojo"); }?>
			</div>
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
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
