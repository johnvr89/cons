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
	$id_medico=$_SESSION['cod_user'];
	$usu=$_SESSION['cod_user'];
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom');
	
	$fecha=date('Y-m-d');
	$hora=date('H:m:s');
	
	$usu=$_SESSION['cod_user'];
	$pa=mysql_query("SELECT * FROM cajero WHERE usu='$usu'");				
	while($row=mysql_fetch_array($pa)){
		$id_consultorio=$row['consultorio'];
		$oConsultorio=new Consultar_Deposito($id_consultorio);
		$nombre_Consultorio=$oConsultorio->consultar('nombre');
	}
	
	if(!empty($_GET['del'])){
		$id=$_GET['del'];
		mysql_query("DELETE FROM citas_medicas WHERE id='$id'");
		header('index.php');
		
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>  
   
   <link rel="stylesheet" href="../../assets/css/bootstrap-select.min.css" />

</head>
<body>
    <div id="wrapper">

        <?php 
        $_SESSION['menu'] = 'CAJA';
        include_once "../../menu/m_general.php";
        ?>
        <div id="page-wrapper" >
            <div id="page-inner">
				 <?php 
				if(!empty($_GET['fechai']) and !empty($_GET['fechaf'])){
					$fechai=limpiar($_GET['fechai']);
					$fechaf=limpiar($_GET['fechaf']);
				}else{
					$fechai=date('Y-m-d');	
					$fechaf=date('Y-m-d');	
				}

			?>
				<div class="col-md-12 col-sm-6">
                    <div class="panel panel-default">                      
                        <div class="panel-body">

     					<form name="form1" action="" method="get" class="form-inline">
                           <div class="panel-body">
						   <div class="row"> 
                                <div class="col-md-4">
                                <strong>Paciente: </strong><br>
                                <select data-live-search="true" data-live-search-style="startsWith" class="selectpicker" name = 'idPaciente'>  
                                    <option value="TODOS" selected>TODOS</option>
                                    <?php 
                                        $strSql = "SELECT * FROM pacientes where consultorio = ".$_SESSION['idConsultorio']."  ORDER BY nombre";
                                        $sql=mysql_query($strSql);
                                        while($row=mysql_fetch_array($sql)){
                                            echo '<option value="'.$row['id'].'" >'.$row['nombre'].'</option>';

                                        }
                                    ?>
                                </select>
                                </div>
                                <div class="col-md-4">
                                	<strong>Medico: </strong><br>
                                    <select data-live-search="true" data-live-search-style="startsWith" class="selectpicker" name = 'idDoctor'>  
                                        <option value="TODOS" selected>TODOS</option>
                                        <?php 
                                            $strSql = "SELECT * FROM usuario where cargo= 'MEDICO' and consultorio = ".$_SESSION['idConsultorio']."  ORDER BY nombre";
                                            $sql=mysql_query($strSql);
                                            while($row=mysql_fetch_array($sql)){
                                                echo '<option value="'.$row['id'].'" >'.$row['nombre'].'</option>';

                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                	<strong>Forma de Pago</strong><br>
                                    <select class="form-control" name="forma_pago">
                                    	<option value="TODOS" selected>TODOS</option>
                                        <option value="TARJETA DE CREDITO" >TARJETA DE CREDITO</option>
                                        <option value="EFECTIVO" >EFECTIVO</option>
                                    </select>
                                    
                                </div>                               
                               
                                </div>                                
                               <div class="row"> 
                                    <div class="col-md-4">
                                        <strong>Fecha Inicial</strong><br>
                                        <input class="form-control" value="<?php echo $fechai; ?>" name="fechai" type="date" autocomplete="off" required>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Fecha Final</strong><br>
                                        <input class="form-control" value="<?php echo $fechaf; ?>" name="fechaf" type="date" autocomplete="off" required>
                                    </div>
                                </div>
                               <br>
                               <center><button type="submit" align="center" class="btn btn-primary"><i class="icon-search"></i> <strong>Consultar</strong></button></center>
                           </div>
			            </form>
                        </div>
                    </div>
                </div><br>								
					<center>
                <a href="#venta" class="btn btn-danger" title="Listado" data-toggle="modal">
					<i class="fa fa-shopping-cart" ></i><strong> Ver Caja</strong>
				</a></center><br> 
				
			
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                             REGISTROS
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
								
                              <table class="table table-striped table-bordered table-hover" id="dataTables-example" >
                                    
									<thead>
                                        <tr>
                                            <th>DOCUMENTO</th>
                                            <th>PACIENTE</th>                                                                                    
                                            <th>MEDICO</th>
                                            <th>PAGO</th>                                                                                     
                                            <th>FECHA REGISTRO</th>
                                            <th>VALOR</th>
                                            <th>ESTADO</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
										  <?php 
                                            
                                            $strWhere = '';
                                            if($_GET['idPaciente'] != 'TODOS')
                                            {
                                                $strWhere .= ' and f.paciente_id = '.$_GET['pacienteId'];
                                            }
                                            if($_GET['forma_pago']  != 'TODOS')
                                            {
                                                $strWhere .= " and f.tipo_pago = '".$_GET['forma_pago']."'";
                                            }
                                            if($_GET['idDoctor']  != 'TODOS')
                                            {
                                                $strWhere .= " and cm.id_medico = '".$_GET['idDoctor']."'";
                                            }
                                                                                                                                    
                                            
                                            $strSql = " SELECT f.*, (select nombre from usuario where id = cm.id_medico) nombre_medico
                                                        FROM factura f, consultas_medicas cm
                                                        WHERE cm.id = f.consulta_id
                                                        and f.consultorio_id='$id_consultorio'
                                                        and DATE(f.fecha_ingresa) between '".$_GET['fechai']."' AND '".$_GET['fechaf']."' ".$strWhere;
                                            
											$sql=mysql_query($strSql);
                                            
											while($row=mysql_fetch_array($sql)){
                                                $venta_total = $row['valor'] + $venta_total  ;
																					
												if($row['estado']== 'PROCESADO'){
													$status='<span class="label label-success">PROCESADO</span>';
												}
                                                else
                                                {
                                                    $status='<span class="label label-danger">PENDIENTE</span>';
                                                }
												
												############# CONSULTAS ######################
												$oPaciente=new Consultar_Paciente($row['paciente_id']);
												
												
										?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $oPaciente->consultar('nombre'); ?></td>
                                            <td><?php echo $row['nombre_medico'];  ?></td>
                                            <td><?php echo $row['tipo_pago']; ?></td>
                                            <td><?php echo $row['fecha_ingresa']; ?></td>
                                            <td><?php echo '$'.formato($row['valor']); ?></td>
                                            <td><?php echo $status; ?> </td>
                                            <td class="center">
											<a href="pro_contado.php?idFactura=<?php echo $row['id']; ?>&proceso=CONSULTA"  class="btn btn-info" title="Detalle">
											<i class="fa fa-list-alt" ></i>
										    </a>									
											</td>
                                        </tr> 
																				
										
											<?php } ?>
                                    </tbody>
									
                                </table>  
                                
                                <!-- Modal -->           			
                                <div class="modal fade" id="venta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form name="contado" action="pro_contado.php" method="get">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>													
                                                                <h3 align="center" class="modal-title" id="myModalLabel">Venta</h3>
                                                            </div>
                                                <div class="panel-body">
                                                <div class="row" align="center">                                       

                                                    <strong>Hola! <?php echo $cajero_nombre; ?></strong><br>

                                                        <h4><pre><h1 class="text-success" align="center"><?php echo $s.' '.formato($venta_total); ?></h1></pre>	</h4>



                                                </div> 
                                                </div> 
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <!--<button type="submit" class="btn btn-primary">Procesar</button>-->
                                                </div>										 
                                            </div>
                                        </div>
                                        </form>
                                </div>
                     <!-- End Modals-->                                      
								
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
			<!--  Modals-->
								 <div class="modal fade" id="contado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<form name="contado" action="pro_contado.php" method="get">
								<div class="modal-dialog">
									<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>													
														<h3 align="center" class="modal-title" id="myModalLabel">Seguridad</h3>
													</div>
										<div class="panel-body">
										<div class="row" align="center">                                       
																					
											<strong>Hola! <?php echo $cajero_nombre; ?></strong><br>
											 <div class="alert alert-danger">
												<h4>¿Esta Seguro de Procesar esta operación?<br> 
												una vez completada no podra ser editada.</h4>
											</div>											
											<input type="hidden" value="<?php echo $neto; ?>" name="valor_recibido">
											<input type="hidden" value="<?php echo $neto; ?>" name="neto">	
																					                                                                    
										</div> 
										</div> 
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Procesar</button>
                                        </div>										 
                                    </div>
                                </div>
								</form>
                            </div>
                     <!-- End Modals-->       	
                <!-- /. ROW  -->                                     
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->   
   
</body>
       <script src="../../assets/js/bootstrap-select.min.js"></script> 
</html>
