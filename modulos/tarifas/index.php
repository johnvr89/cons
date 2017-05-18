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
    $idConsultorio = $_SESSION['idConsultorio'];
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
	
	if(!empty($_GET['del'])){
		$id=$_GET['del'];
		mysql_query("DELETE FROM tarifas WHERE id='$id'");
		header('index.php');
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
										
                                            <h3 align="center" class="modal-title" id="myModalLabel">Nueva Tarifa</h3>
                                        </div>
										<div class="panel-body">
										<div class="row">                                       
											<div class="col-md-6">											
												<label>Nombre:</label>
												<input class="form-control" name="nombre" autocomplete="off" required><br>
												<label>Valor:</label>
												<input class="form-control" name="valor" autocomplete="off" required><br>
											</div>
											<div class="col-md-6">
												<label>Configurar:</label>
												<select class="form-control" name="config" autocomplete="off" required>
												<option value="no">--SELECCIONAR--</option>
													<option value="df">Tarifa por Defaul</option>
													<option value="gn">General</option>													
												</select><br>
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
                             TARIFAS
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
								<?php 
									if(!empty($_POST['nombre'])){ 												
										$nombre=limpiar($_POST['nombre']);																											
										$valor=limpiar($_POST['valor']);																											
										$config=limpiar($_POST['config']);																											
										$estado=limpiar($_POST['estado']);
										
										if(empty($_POST['id'])){
											$oTarifa=new Proceso_Tarifas('',$nombre,$valor,$config,$estado, $idConsultorio);
											$oTarifa->crear();
											echo mensajes('Tarifa "'.$nombre.'" Creado con Exito','verde');
										}else{
											$id=limpiar($_POST['id']);
											$oTarifa=new Proceso_Tarifas($id,$nombre,$valor,$config,$estado);
											$oTarifa->actualizar();
											echo mensajes('Tarifa "'.$nombre.'" Actualizado con Exito','verde');
										}
									}
								?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    
									<thead>
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>VALOR</th>
                                            <th>CONFIGURACION</th>
                                            <th>ESTADO</th>                                                                                      
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php 
											if(!empty($_POST['buscar'])){
												$buscar=limpiar($_POST['buscar']);
												$pame=mysql_query("SELECT * FROM tarifas WHERE nombre LIKE '%$buscar%' and  id_consultorio = $idConsultorio  ORDER BY nombre");	
											}else{
												$pame=mysql_query("SELECT * FROM tarifas where id_consultorio = $idConsultorio ORDER BY nombre");
												
											}		
											while($row=mysql_fetch_array($pame)){
											$valor=$row['valor'];
										?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row['nombre']; ?></td>
                                            <td>$<?php echo formato($valor); ?></td>
                                            <td><?php echo config($row['config']); ?></td>                                           
                                            <td><?php echo estado($row['estado']); ?></td>                                                                                   
                                            <td class="center">
											<div class="btn-group">
											  <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle"><i class="fa fa-cog"></i> <span class="caret"></span></button>
											  <ul class="dropdown-menu">
												<li><a href="#" data-toggle="modal" data-target="#actualizar<?php echo $row['id']; ?>"><i class="fa fa-edit"></i> Editar</a></li>
												<li class="divider"></li>
												<!--<li><a href="index.php?del=<?php echo $row['id']; ?>" ><i class="fa fa-pencil"></i> Eliminar</a></li>-->																																				
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
															
																<h3 align="center" class="modal-title" id="myModalLabel">Actualizar Tarifa</h3>
															</div>
															<div class="panel-body">
															<div class="row">                                       
																<div class="col-md-6">											
																	<label>Nombre:</label>
																	<input class="form-control" name="nombre" autocomplete="off" required value="<?php echo $row['nombre']; ?>"><br>											
																	<label>Valor:</label>
																	<input class="form-control" name="valor" autocomplete="off" required value="<?php echo $row['valor']; ?>"><br>
																</div>
																<div class="col-md-6">
																	<label>Estado</label>
																	 <select class="form-control" name="config">
																			<option value="df" <?php if($row['config']=='df'){ echo 'selected'; } ?>>Tarifa por Defaul</option>
																			<option value="gn" <?php if($row['config']=='gn'){ echo 'selected'; } ?>>General</option>
																	</select><br>		
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
																										
																<strong>Hola! <?php echo $cajero_nombre; ?></strong><br><br>
																<div class="alert alert-danger">
																	<h4>¿Esta Seguro de Realizar esta Acción?<br> 
																	una vez Eliminada la tarifa [ <?php echo $row['nombre']; ?> ] no podra ser Recuperada.</h4>
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
