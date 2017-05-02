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
						
	$titulo='Crear Usuario';
	$boton='Ingresar Usuario';
	$existe=false;
	
	$doc='';		
	#$salario='0';
	$nombre='';		
	$estado='';
	$cargo='';		
	$nota='';
	#$consultorio='';
	
	if(!empty($_GET['id'])){
		$doc=limpiar($_GET['id']);
		$sql=mysql_query("SELECT * FROM usuario WHERE doc='$doc'");
		if($row=mysql_fetch_array($sql)){
			$nombre=$row['nombre'];			
			$clave=$row['con'];
			#$salario=$row['salario'];
			$estado=$row['estado'];			
			$nota=$row['nota'];
			$cargo=$row['tipo'];		
            $email = $row['email'];
			$consultoriox=$row['consultorio'];		
			$existe=true;					
			$boton='Actualizar Usuario';
			$titulo='Modificar Usuario';
		}else{
			header('Location: usuario.php');
		}
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
           <?php include_once "../../menu/m_departamento.php"; ?>
        <div id="page-wrapper" >
            <div id="page-inner">						                				
                  <div class="panel-body" align="right">                                                                                 
                            <a href="index.php" class="btn btn-primary" title="Listado">
							<i class="fa fa-list" ></i>
							</a>
                            				

                  </div>
				  
					 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                             <?php echo $titulo ?>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
					<?php 
			if(!empty($_POST['doc']) and !empty($_POST['nombre'])){
				$nombre=limpiar($_POST['nombre']);			
				$salario='0';
				$doc=limpiar($_POST['doc']);			
				$cargo=limpiar($_POST['cargo']);
				$consultorio=limpiar($_POST['consultorio']);
				$nota='Ninguna';				
				$estado=limpiar($_POST['estado']);
				$clave=limpiar($_POST['clave']);
                $email = limpiar($_POST['email']);
				
				if($cargo=='admin'){
					$tipo='Admin';
					$ncargo='Administrador';
				}else{
					$tipo=$cargo;
					$ncargo=consultar('nombre','tipo_usuario'," id='".$tipo."'");
				}
				
				if($existe==false){
					$sqll=mysql_query("SELECT id FROM usuario WHERE doc='$doc' or nombre='$nombre'");
					if($roww=mysql_fetch_array($sqll)){
						echo mensajes("El Documento o Nombre del Usuario ya se encuentra registrado en la base de datos","rojo");
					}else
					{					
						mysql_query("INSERT INTO usuario (doc,nombre,cargo,nota,salario,estado,tipo,con) 
						VALUES ('$doc','$nombre','$ncargo','$nota','$salario','$estado','$tipo','$doc')");
						
						if($tipo=='Admin'){
							$sql=mysql_query("SELECT * FROM permisos_tmp");
							while($row=mysql_fetch_array($sql)){
								$id=$row['id'];
								mysql_query("INSERT INTO permisos (permiso,usu,estado) VALUES ('$id','$doc','s')");
							}
						}else{
							$sql=mysql_query("SELECT * FROM tipo_permisos WHERE tipo='$tipo'");
							while($row=mysql_fetch_array($sql)){
								$id=$row['permiso'];
								$es=$row['estado'];
								mysql_query("INSERT INTO permisos (permiso,usu,estado) VALUES ('$id','$doc','$es')");
							}
						}
						
						echo mensajes("Se ha Registrado el Usuario con Exito","verde");
					}
				}elseif($existe==true){
					mysql_query("UPDATE usuario SET nombre='$nombre', cargo='$ncargo', nota='$nota', salario='$salario', estado='$estado', tipo='$tipo', consultorio='$consultorio', con='$clave', email = '$email'
					WHERE doc='$doc'");					
					mysql_query("UPDATE cajero SET consultorio='$consultorio' WHERE usu='$doc'");
					}
					echo mensajes("Se ha Actualizado el Usuario con Exito","verde");
					//subir la imagen del articulo
					$nameimagen = $_FILES['imagen']['name'];
					$tmpimagen = $_FILES['imagen']['tmp_name'];
					$extimagen = pathinfo($nameimagen);
					$ext = array("png","jpg");
					$urlnueva = "../../img/usuario/".$doc.".jpg";			
						if(is_uploaded_file($tmpimagen)){
						if(array_search($extimagen['extension'],$ext)){
							copy($tmpimagen,$urlnueva);	
						}else{
							echo mensajes("Error al Cargar la Imagen","rojo");	
							}
						}else{
						echo mensajes("Error al Cargar la Imagen","rojo");
					
				}
			}
		?>
					<form name="forms" method="post" enctype="multipart/form-data" action="">
				    <div class="row">                                       
					<div class="col-md-6">											
								<label>Documento:</label>
								<input class="form-control" name="doc" <?php if($existe==TRUE){ echo 'readonly'; }else{ echo 'required'; } ?>   value="<?php echo $doc; ?>" data-mask="99999999-9" autocomplete="off" required><br>
								<label>Nombre:</label>
								<input class="form-control" name="nombre" value="<?php echo $nombre; ?>" autocomplete="off" required><br>	
								<strong>Fotografia</strong><br>
								<?php
											if (file_exists("../../img/usuario/".$doc.".jpg")){
												echo '<img src="../../img/usuario/'.$doc.'.jpg" width="150px" height="150px" class="img-polaroid">';
											}else{ 
												echo '<img src="../../img/usuario/default.png" width="150px" height="150px" class="img-polaroid">';
											}
								?><br>
							
								<input type="file" name="imagen" id="imagen">																	
					</div>
					<div class="col-md-6">								
								<label>Tipo de Usuario</label>
								<select class="form-control" name="cargo" autocomplete="off" required>
								<option value="admin" <?php if($cargo=='admin'){ echo 'selected'; } ?>>Administrador Principal</option>
									<?php 
										$sql=mysql_query("SELECT id,nombre FROM tipo_usuario ORDER BY nombre");
											while($row=mysql_fetch_array($sql)){
											if($row['id']==$cargo){
												echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].'</option>';
											}else{
												echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
												}
											}
																		?>											
								</select><br>
								<label>Consultorio;</label>
								<select class="form-control" name="consultorio" autocomplete="off" required>
									<!--<?php 
										$can=mysql_query("SELECT * FROM consultorios WHERE estado='s'");
										while($row=mysql_fetch_array($can)){
									?>
											<option value="<?php echo $row['id']; ?>" <?php if($consultorio==$row['id']){ echo 'selected'; } ?>><?php echo $row['nombre']; ?></option>
									<?php } ?>-->
									<?php
										$pa=mysql_query("SELECT * FROM consultorios WHERE estado='s'");				
										while($row=mysql_fetch_array($pa)){
											if($row['id']==$consultoriox){
												echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].'</option>';	
											}else{
												echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
											}
										}
                                  	?>												
							    </select><br>
							
								<label>Estado</label>
								<select class="form-control" name="estado" autocomplete="off" required>
								<option value="s" <?php if($estado=='s'){ echo 'selected'; } ?>>ACTIVO</option>
                                <option value="n" <?php if($estado=='n'){ echo 'selected'; } ?>>NO ACTIVO</option>													
								</select><br>
								<label>Clave:</label>
								<input class="form-control" name="clave" value="<?php echo $clave; ?>" autocomplete="off" ><br>
								<br>
                                <div class="input-group">
                                  <span class="input-group-addon">@</span>
                                  <input class="form-control" name="email" autocomplete="off" value="<?php echo $email; ?>"><br>											
                                </div><br>                                    
								<div align="right"> 								
								<!--<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>-->
								<button type="submit" class="btn btn-primary"><?php echo $boton; ?></button>
								</div> 
																	
					</div>                                                                        
					</div>
				    </form>
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
