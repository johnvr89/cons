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
	$pa=mysql_query("SELECT * FROM cajero WHERE usu='$usu'");				
	while($row=mysql_fetch_array($pa)){
		$id_consultorio=$row['consultorio'];
		$oConsultorio=new Consultar_Deposito($id_consultorio);
		$nombre_Consultorio=$oConsultorio->consultar('nombre');
	}
	
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom');
	$fecha=date('Y-m-d');
	$hora=date('H:i:s');
	
	######### TRAEMOS LOS DATOS DE LA EMPRESA #############
		$pa=mysql_query("SELECT * FROM empresa WHERE id=1");				
        if($row=mysql_fetch_array($pa)){
			$nombre_empresa=$row['empresa'];
		}
	
	if(!empty($_GET['del'])){
		$id=$_GET['del'];
		mysql_query("DELETE FROM seguros WHERE id='$id'");
		echo mensajes('Transaccion Exitosa','verde');
        //header('index.php');
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

</head>
<body>
    <div id="wrapper">
           <?php 
                $_SESSION['menu'] = 'ADMINISTRACION';
                include_once "../../menu/m_general.php"; 
           ?>
        <div id="page-wrapper" >
            <div id="page-inner">						                				 
                 <div class="panel-body" align="right">                                                                                 
                            <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus fa-2x"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-circle"><i class="fa fa-question fa-2x"></i>
                            </button>                                                                                 
                  </div>
				  <!--  Modals-->
                     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form name="form1" method="post" action="">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										
                                            <h3 align="center" class="modal-title" id="myModalLabel">Nuevo Seguro</h3>
                                        </div>
										<div class="panel-body">
										<div class="row">                                       
											<div class="col-md-6">											
												<label>Nombre:</label>
												<input class="form-control" name="nombre" autocomplete="off" required><br>											
											</div>
											<div class="col-md-6">																							
												 <label>Estado</label>
												<select class="form-control" name="estado" autocomplete="off" required>
													<option value="s">Activo</option>
													<option value="n">No Activo</option>													
												</select>
											</div>                                                                        
										</div> 
										</div> 
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>										 
                                    </div>
                                </div>
								</form>
                            </div>
                     <!-- End Modals-->
					 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                             LISTADO DE SEGUROS
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
								<?php 
									if(!empty($_POST['nombre'])){ 												
										$nombre=limpiar($_POST['nombre']);																											
										$estado=limpiar($_POST['estado']);
										
										if(empty($_POST['id'])){
											$oSeguro=new Proceso_Seguro('',$nombre,$estado);
											$oSeguro->crear();
											echo mensajes('Estado "'.$nombre.'" Creado con Exito','verde');
										}else{
											$id=limpiar($_POST['id']);
											$oSeguro=new Proceso_Seguro($id,$nombre,$estado);
											$oSeguro->actualizar();
											echo mensajes('Estado "'.$nombre.'" Actualizado con Exito','verde');
										}
									}
								?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    
									<thead>
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>ESTADO</th>                                                                                      
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php 
											$pamex=mysql_query("SELECT * FROM seguros ORDER BY nombre");		
											while($row=mysql_fetch_array($pamex)){
										?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row['nombre']; ?></td>
                                            <td><?php echo estado($row['estado']); ?></td>                                                                                   
                                            <td class="center">
											<div class="btn-group">
											  <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle"><i class="fa fa-cog"></i> <span class="caret"></span></button>
											  <ul class="dropdown-menu">
												<li><a href="#" data-toggle="modal" data-target="#actualizar<?php echo $row['id']; ?>"><i class="fa fa-edit"></i> Editar</a></li>
												<li class="divider"></li>
												<li><a href="#" data-toggle="modal" data-target="#eliminar<?php echo $row['id']; ?>"><i class="fa fa-pencil"></i> Eliminar</a></li>																																				
											  </ul>
											</div>											
											</td>
                                        </tr> 
										
										<!--  Modals-->
										 <div class="modal fade" id="actualizar<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<form name="form1" method="post" action="">
												<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															
																<h3 align="center" class="modal-title" id="myModalLabel">Actualizar Departamento</h3>
															</div>
															<div class="panel-body">
															<div class="row">                                       
																<div class="col-md-6">											
																	<label>Nombre:</label>
																	<input class="form-control" name="nombre" autocomplete="off" required value="<?php echo $row['nombre']; ?>"><br>											
																</div>
																<div class="col-md-6">																							
																	 <label>Estado</label>
																	 <select class="form-control" name="estado">
																			<option value="s" <?php if($row['estado']=='s'){ echo 'selected'; } ?>>Activo</option>
																			<option value="n" <?php if($row['estado']=='n'){ echo 'selected'; } ?>>No Activo</option>
																	</select>												
																</div>                                                                        
															</div> 
															</div> 
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
																<button type="submit" class="btn btn-primary">Guardar</button>
															</div>										 
														</div>
													</div>
													</form>
												</div>
										 <!-- End Modals-->
										 <!-- Modal -->           			
												<div class="modal fade" id="eliminar<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
													<form name="contado" action="index.php?del=<?php echo $row['id']; ?>" method="get">
													<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
													<div class="modal-dialog">
														<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>													
																			<h3 align="center" class="modal-title" id="myModalLabel">Seguridad</h3>
																		</div>
															<div class="panel-body">
															<div class="row" align="center">                                       
																									
																
																<div class="alert alert-danger">
																	<h4>Esta Seguro de Eliminar <strong> <?php echo $row['nombre']; ?> </strong> ?</h4>
																</div>																																																																																																								
															</div> 
															</div> 
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
																<a href="index.php?del=<?php echo $row['id']; ?>"  class="btn btn-danger" title="Eliminar">
																	<i class="fa fa-times" ></i> <strong>Eliminar</strong>
																</a>																
															</div>										 
														</div>
													</div>
													</form>
												</div>
										 <!-- End Modals-->      
										
											<?php } ?>
                                    </tbody>
									
                                </table>
								
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
                <!-- /. ROW  -->                                     
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
     <!-- /. WRAPPER  -->
   
</body>
</html>
