<?php

include_once "../../php_conexion.php";
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
$idPaciente = (isset($_REQUEST['idPaciente']) !=NULL)?$_REQUEST['idPaciente']:'';
if($action == 'ajax'){
	//Elimino producto
	if (isset($_REQUEST['id']))
    {
		$id_slide=intval($_REQUEST['id']);
        $update = "update slider set estado = 'I' where id='$id_slide'";
		if ($delete=mysqli_query($con,$update)){
			$message= "Datos eliminados satisfactoriamente";
		} else {
			$error= "No se pudo eliminar los datos";
		}
	}	
	
	$tables=" slider";
	$sWhere=" where categoria = 'PACIENTE' AND estado = 'A' AND codigo_referencia = $idPaciente ";	
	$sWhere.=" order by orden";
    
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = 12; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	
	//Count the total number of row in your table*/
    $sql = "SELECT count(*) AS numrows FROM $tables  $sWhere ";
	$count_query   = mysqli_query($con, $sql);
	
	if ($row= mysqli_fetch_array($count_query))
	{
		$numrows = $row['numrows'];		
	}
	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	$reload = './productslist.php';
	//main query to fetch the data
	$sql = "SELECT * FROM  $tables  $sWhere LIMIT $offset,$per_page";
	$query = mysqli_query($con,$sql);
	
	if (isset($message)){
		?>
		<div class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong>Aviso!</strong> <?php echo $message;?>
		</div>
		
		<?php
	}
	if (isset($error)){
		?>
		<div class="alert alert-danger alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong>Error!</strong> <?php echo $error;?>
		</div>
		
		<?php
	}
	//loop through fetched data
	if ($numrows>0)	{
		?>
		
		 <div class="row">
			<?php
				while($row = mysqli_fetch_array($query)){
					$url_image  = '';
                    $titulo     = '';
                    $descripcion= '';
                    $id_slide   = '';
                        
                    $url_image  =$row['url_image'];
					$titulo     =$row['titulo'];
					$descripcion=$row['descripcion'];
					$id_slide   =$row['id'];
				
					?>
					
					  <div class="col-sm-6 col-md-4">
						<div class="thumbnail">
                          <img src="img/slider/<?php echo $url_image;?>"  style="width:100%;cursor:pointer" onclick="onClick(this)" class="w3-hover-opacity">                            
						  <div class="caption">
							<h3><?php echo $titulo;?></h3>
							<p><?php echo $descripcion;?></p>
							<p class='text-right'>
                                <a href="#" class="btn btn-info"  data-toggle="modal" data-target="#actualizar<?php echo $id_slide; ?>"><i class='glyphicon glyphicon-edit'></i> Editar </a>
                                <button type="button" class="btn btn-danger" onclick="eliminar_slide('<?php echo $id_slide;?>');" role="button"><i class='glyphicon glyphicon-trash'></i> Eliminar</button>
                            </p>
						  </div>
						</div>
					  </div>
                      
                      <!-- Creo los modals de los edit -->
                     <div class="modal fade" id="actualizar<?php echo $id_slide; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <form name="form1" method="post" action="" enctype="multipart/form-data" >
                        <input type="hidden" class="form-control" id="accion" name = "accion" value="actualizar">
                        <input type="hidden" name="id" value="<?php echo $id_slide; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                        <h3 align="center" class="modal-title" id="myModalLabel">Actualizar Fotos </h3>
                                    </div>
                                    <div class="panel-body">
                                    <div class="row">                                       
                                        <div class="col-md-6">											
                                            <label>Título:</label>
                                            <input class="form-control" name="titulo" autocomplete="off" required value="<?php echo $titulo; ?>"><br>											
                                            <label>Descripción:</label>
                                            <textarea class="form-control " rows="5" id="descripcion" required name="descripcion"><?php echo $descripcion;?></textarea>
                                            <br>                                                                                         <label>Estado</label>
                                            <select class="form-control" name="estado">
                                                <option value="A" <?php if($row['estado']=='A'){ echo 'selected'; } ?>>Activo</option>
                                                <option value="I" <?php if($row['estado']=='I'){ echo 'selected'; } ?>>Inactivo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="max-width: 100%;" >
                                                    <img class="img-rounded" src="./img/slider/<?php echo $url_image; ?>" />
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 250px; max-height: 250px;">
                                                    
                                                </div>
                                                <div>
                                                    <span class="btn btn-info btn-file"><span class="fileinput-new">Selecciona una imagen</span>
                                                    <span class="fileinput-exists" onclick="upload_image();">Cambiar imagen</span><input type="file" name="fileToUpload" id="fileToUpload" onchange="upload_image();"></span>
                                                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Cancelar</a>
                                                </div>
                                            </div>                                            
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
                      
					
					<?php
				}
			?>
		  </div>
		
		<div class="table-pagination text-right">
			
			<?php echo paginate($reload, $page, $total_pages, $adjacents);?>
		</div>
		<?php
	}
}
?>
