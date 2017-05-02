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
		mysql_query("DELETE FROM consultorios WHERE id='$id'");
		header('index.php');
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
</head>
<body>
    <div id="wrapper">
           <?php 
                $_SESSION['menu'] = 'ADMINISTRACION';
                include_once "../../menu/m_general.php"; 
           ?>
        <div id="page-wrapper" >
            <div id="page-inner">						               				
                 <!-- /. ROW  -->                
 <?php if(permiso($_SESSION['cod_user'],'4')==TRUE){ ?>
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
										
                                            <h3 align="center" class="modal-title" id="myModalLabel">Nueva Empresa</h3>
                                        </div>
										<div class="panel-body">
										<div class="row">                                       
                                        <div class="col-md-6">											
                                        <label>Nombre:</label>
                                        <input class="form-control" name="nombre" autocomplete="off" required ><br>
                                        <label>Ruc:</label>
                                        <input class="form-control" name="ruc" data-mask="(999)999-9999" autocomplete="off" required  ><br>                                                                             
                                        <label>Direccion:</label>
                                        <input class="form-control" name="direccion" autocomplete="off" required  ><br>
                                        <label>Telefono:</label>
                                        <input class="form-control" name="telefono" data-mask="(99)999-9999" autocomplete="off" required  ><br>
                                        <label>Fax:</label>
                                        <input class="form-control" name="fax" autocomplete="off" required ><br>

                                    </div>
                                    <div class="col-md-6">
                                        <label>Web:</label>
                                        <input class="form-control" name="web" autocomplete="off" required  ><br>
                                        <label>Pais:</label>
                                        <input class="form-control" name="pais" autocomplete="off" required  ><br>                                                                            
                                        <label>Ciudad:</label>
                                        <input class="form-control" name="ciudad" autocomplete="off" required  ><br>                                                                            
                                         <label>Estado</label>
                                         <select class="form-control" name="estado">
                                                <option value="A"  >Activo</option>
                                                <option value="I"  >Inactivo</option>
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
                             EMPRESAS
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
								<?php 
									if(!empty($_POST['nombre'])){

                                        $target_path = "../../img/empresa/";
                                        $target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
                                        if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
                                        { 
                                            echo mensajes('El archivo no ha sido subido.','rojo');
                                        }
                                        
                                        $arrayParametros ['id']         =limpiar($_POST['id']);
                                        $arrayParametros ['nombre']     =limpiar($_POST['nombre']);																											
										$arrayParametros ['direccion']  =limpiar($_POST['direccion']);																											
										$arrayParametros ['telefono']   =limpiar($_POST['telefono']);																											
										$arrayParametros ['fax']        =limpiar($_POST['fax']);																											
										$arrayParametros ['ruc']        =limpiar($_POST['ruc']);
                                        $arrayParametros ['web']        =limpiar($_POST['web']);
                                        $arrayParametros ['pais']       =limpiar($_POST['pais']);
                                        $arrayParametros ['ciudad']     =limpiar($_POST['ciudad']);
                                        $arrayParametros ['correo']     =limpiar($_POST['correo']);
                                        $arrayParametros ['estado']     =limpiar($_POST['estado']);
                                        $arrayParametros ['logo']       =limpiar($target_path);
                                       
										if(empty($_POST['id'])){
											$oConsultorio=new Proceso_Empresa($arrayParametros);
											$oConsultorio->crear();
											echo mensajes('Empresa "'.$arrayParametros ['nombre'].'" Creado con Exito','verde');
										}else{
                                            
											$id=limpiar($_POST['id']);
											$oConsultorio=new Proceso_Empresa($arrayParametros);
											$oConsultorio->actualizar();
											echo mensajes('Empresa "'.$arrayParametros ['nombre'].'" Actualizado con Exito','verde');
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
											if(!empty($_POST['buscar'])){
												$buscar=limpiar($_POST['buscar']);
												$pame=mysql_query("SELECT * FROM empresa WHERE nombre LIKE '%$buscar%' order by nombre");	
											}else{
												$pame=mysql_query("SELECT * FROM empresa order by nombre");
											}		
											while($row=mysql_fetch_array($pame)){
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
												<li><a href="#" data-toggle="modal" data-target="#eliminar<?php echo $row['id']; ?>" ><i class="fa fa-pencil"></i> Eliminar</a></li>																																				
											  </ul>
											</div>											
											</td>
                                        </tr> 
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
																	<h4>¿Esta Seguro de Realizar esta Acción?<br><br> 
																	una vez Eliminado el Consultorio <strong>[ <?php echo $row['nombre']; ?> ]</strong><br> 
																	no podran ser Recuperados sus datos.<br>
																	No recomendamos esta accion, sino la de "Activo" o No Activo, porque puede
																	afectar la  informcion en el Almacen de datos.
																	</h4>
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
										<!--  Modals-->
										 <div class="modal fade" id="actualizar<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<form name="form1" method="post" enctype="multipart/form-data" action="">
                                                    
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															
																<h3 align="center" class="modal-title" id="myModalLabel">Actualizar Empresa</h3>
															</div>
															<div class="panel-body">
															<div class="row">                                       
																<div class="col-md-6">
                                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
																	<label>Nombre:</label>
																	<input class="form-control" name="nombre" autocomplete="off" required value="<?php echo $row['nombre']; ?>"><br>
																	<label>Ruc:</label>
																	<input class="form-control" name="ruc" data-mask="(999)999-9999" autocomplete="off" required value="<?php echo $row['ruc']; ?>"><br>                                                                             
																	<label>Direccion:</label>
																	<input class="form-control" name="direccion" autocomplete="off" required value="<?php echo $row['direccion']; ?>"><br>
																	<label>Telefono:</label>
																	<input class="form-control" name="telefono" data-mask="(999)999-9999" autocomplete="off" required value="<?php echo $row['telefono']; ?>"><br>
																	<label>Fax:</label>
																	<input class="form-control" name="fax" autocomplete="off" required value="<?php echo $row['fax']; ?>"><br>
                                                                           
																</div>
																<div class="col-md-6">
																	<label>Web:</label>
																	<input class="form-control" name="web" autocomplete="off" required value="<?php echo $row['web']; ?>"><br>
                                                                    <label>Pais:</label>
																	<input class="form-control" name="pais" autocomplete="off" required value="<?php echo $row['pais']; ?>"><br>                                                                            
                                                                    <label>Ciudad:</label>
																	<input class="form-control" name="ciudad" autocomplete="off" required value="<?php echo $row['ciudad']; ?>"><br>                                                                            
																	<label>Estado</label>
																	<select class="form-control" name="estado">
                                                                        <option value="A" <?php if($row['estado']=='A'){ echo 'selected'; } ?>>Activo</option>
																		<option value="I" <?php if($row['estado']=='I'){ echo 'selected'; } ?>>}inactivo</option>
                                                                    </select><br>
                                                                    <label>Logo:</label>
                                                                    <input name="uploadedfile" type="file" />
                                                                    <?php if($row['logo']){ echo "<img src='".$row['logo']."' alt='Smiley face' height='100' width='100'> ;"; } ?>
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
										
											<?php } ?>
                                    </tbody>
									
                                </table>
								
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
			<?php }else{ echo mensajes("NO TIENES PERMISO PARA ENTRAR A ESTE FORMULARIO","rojo"); }?>
                <!-- /. ROW  -->                                     
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
</body>
</html>
