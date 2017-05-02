<?php
session_start();
include_once "../php_conexion.php";
include_once "class/class.php";
include_once "../funciones.php";
include_once "../class_buscar.php";
include_once '../general/ControladorGeneral.php';

$objGeneral = new ControladorGeneral;

//$objGeneral->enviarCorreo($destinatario, $asunto, $cuerpo);
//die('hi');

// Activar errores
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
//print_r($con);


function image_gd($file) 
{ 
	//Separamos las extenciones de archivos para definir el tipo de ext. 
	$extension = explode(".",$file); 
	$ext = count($extension)-1; 
	//Determinamos las extenciones permitidas. 
	if(strtolower($extension[$ext]) == "jpg" || strtolower($extension[$ext]) == "jpeg") 
	{ 
	$image = ImageCreateFromJPEG($file); 
	} 
	else if(strtolower($extension[$ext]) == "gif"){ 
	$image = ImageCreateFromGIF($file); 
	} 
	else if(strtolower($extension[$ext]) == "png"){ 
	$image = ImageCreateFromPNG($file); 
	} 
	else 
	{ 
	echo "Error en la edicion de imagen, extencion no permitida"; 
	die(); 
	} 

	$thumb_name = substr($file,0,-4);//nombre del thumbnail 
	$width = imagesx($image);//ancho 
	$height = imagesy($image);//alto 
    //quemando altura
//	$nueva_anchura = 440; // Definimos el tamaño a 100 px 
//	$nueva_altura = ($nueva_anchura * $height) / $width ; // tamaño proporcional 
    
    //quemando anchura
//    $nueva_altura = 300;
//    $nueva_anchura = ($width * $nueva_altura) / $height;
    $nueva_anchura = 900;
    $nueva_altura = 500;

	if (function_exists("imagecreatetruecolor")) 
	{ 
	$thumb = ImageCreateTrueColor($nueva_anchura, $nueva_altura);//Color Real 
	} 
	//En caso de no encontrar la funcion, la saca en calidad media 
	if (!$thumb) $thumb = ImageCreate($nueva_anchura, $nueva_altura); 

	ImageCopyResized($thumb, $image, 0, 0, 0, 0, $nueva_anchura, $nueva_altura, $width, $height); 
	//header("Content-type: image/jpeg"); 
	ImageJPEG($thumb, "".$thumb_name.".jpg", 99); 
	imagedestroy($image);
	return $image; 
} 

if(!empty($_GET['id']))
{
    $idPaciente = $_GET['id'];
	$sql = mysqli_query($con,"select nombre from pacientes where id = $idPaciente");
	$rw = mysqli_fetch_array($sql);
	$nombrePaciente = $rw['nombre'];

}
else
{
    header('Location:error.php');
}

$usu = $_SESSION['cod_user'];


if(isset($_POST["titulo"]))
{
    $target_path = "img/slider/";
    
    $titulo         = mysqli_real_escape_string($con,(strip_tags($_POST['titulo'], ENT_QUOTES)));
	$descripcion    = mysqli_real_escape_string($con,(strip_tags($_POST['descripcion'], ENT_QUOTES)));
    $estado         = mysqli_real_escape_string($con,(strip_tags($_POST['estado'], ENT_QUOTES)));
    $id             = mysqli_real_escape_string($con,(strip_tags($_POST['id'], ENT_QUOTES)));
    $nombreArchivo  = $_FILES['fileToUpload']['name'];
    $nombreArchivoEdit  = $_FILES['fileToUploadEdit']['name'];
	//ingreso la imagen

    $target_path = $target_path . basename( $nombreArchivo);
    
    $boolImage = false;
    if($_FILES['fileToUpload']['name'])
    {
        $boolImage = true;
        if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) 
        {
            //redimensiona la imagen
            image_gd($target_path);
        }
        else
        {            
            ?>
            <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Ha ocurrido un error al subir el archivo. </strong>
            </div>
            <?php
        }
    }

    $sql = '';
    if($_POST["accion"]== 'insertar')
    {
        //valido si se subió la imagen correctamente
        if($boolImage)
        {
            $sql = "insert into slider (codigo_referencia, categoria, titulo, descripcion, url_image, estado) 
                                values ($idPaciente, 'PACIENTE','$titulo', '$descripcion', '$nombreArchivo', 'A')"; 
            //envio un correo cuando se ingresa un nuevoo paciente.

            
        }
        else
        {
            ?>
            <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Ha ocurrido un error al subir el archivo. </strong>
            </div>
            <?php            
        }
    }
    else
    {        
        if($boolImage)
        {
            $sql = "update slider set titulo = '$titulo', descripcion = '$descripcion', estado = '$estado', url_image = '$nombreArchivo' where id = $id ";
        }
        else
        {
            $sql = "update slider set titulo = '$titulo', descripcion = '$descripcion', estado = '$estado' where id = $id ";
        }
    }
    
    if($sql)
    {
        $query = mysqli_query($con, $sql);
        if(!$query)
        {
            echo("Error description: " . mysqli_error($con));
        }
    }
    else
    {
        ?>
        <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <center><strong>Transacción exitosa :)</strong></center>
        </div>
        <?php
    }
}	

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
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">

        <link href="css/preview-image.css" rel="stylesheet">        
        
    </head>
    <body>
        <div id="wrapper">
        <!--   Imagen   -->
        <div id="modal01" class="w3-modal" onclick="this.style.display='none'">
          <span class="w3-closebtn w3-hover-red w3-text-white w3-xxlarge w3-container w3-display-topright">&times;</span>
          <div class="w3-modal-content w3-animate-zoom">
            <img id="img01" style="width:100%">
          </div>
        </div>
        <!--      -->    
        <!--   Nuevo   -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <!-- <form class="form-horizontal" id="editar_slide" > -->
			<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data"  >
				<input type="hidden" class="form-control" id="accion" name = "accion" value="insertar">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 align="center" class="modal-title" id="myModalLabel">Nueva Imagen</h3>
                    </div>
                    
                    <div class="panel-body">
                        <div class="col-md-7">
                            <div class="form-group">
                              <label for="titulo" class="col-sm-3 control-label">Titulo</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" id="titulo" value="" required name="titulo">
                                <input type="hidden" class="form-control" id="id_slide" value="<?php echo intval($id_slide);?>" name="id_slide">
                              </div>
                            </div>

                           <div class="form-group">
                              <label for="descripcion" class="col-sm-3 control-label">Descripci&oacuten</label>
                              <div class="col-sm-9">
                                <textarea class="form-control " rows="5" id="descripcion" required name="descripcion"></textarea>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="orden" class="col-sm-3 control-label">Orden</label>
                              <div class="col-sm-9">
                                <input type="number" class="form-control" id="orden" name="orden" value="1">
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="estado" class="col-sm-3 control-label">Estado</label>
                              <div class="col-sm-9">
                                <select class="form-control" id="estado" required name="estado">
                                  <option value="A" >Activo</option>
                                  <option value="I" >Inactivo</option>
                               </select>
                              </div>
                            </div>                        

                        </div>
                        <div class="col-md-5">
                         <h3 ><span class="glyphicon glyphicon-picture"></span> Imagen</h3>
                         <form class="form-vertical">
                            <div class="form-group">
                                <div class="col-sm-12">
                                 <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="max-width: 90%;" >
                                          <img class="img-rounded" src="./img/slider/demo.png" />
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 250px; max-height: 250px;"></div>
                                        <div>
                                        <span class="btn btn-info btn-file"><span class="fileinput-new">Selecciona una imagen</span>
                                        <span class="fileinput-exists" onclick="upload_image();">Cambiar imagen</span><input type="file" name="fileToUpload" id="fileToUpload" required onchange="upload_image();"></span>
                                        <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Cancelar</a>
                                        </div>
                                    </div>
                                    <div class="upload-msg"></div>

                                </div>
                                <!--<p class="text-primary text-center">Tamaño recomendado es de 900 x 500 pixeles.</p>-->
                              </div>
                         </form>
                        </div>                        
                        
                    </div> 
                    <div class="modal-footer">
                        <div class="form-group">
                          <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success">Guardar</button>
                          </div>
                        </div>
                    </div>
					<!--<div id="loader_new" class="text-center"> <span><img src="./img/ajax-loader.gif"></span></div>-->
                    <div class="outer_div_new"></div>
                    </div>
               </div>
            </form>
        </div>
        
        <!--   Nuevo   -->            
            
            
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
                        <h3>Fotos del Paciente <? echo $nombrePaciente; ?> </h3>
                    </div>
                    <div class="responsive">

                      <!-- Main component for a primary marketing message or call to action -->
                      <div class="row" align="left">
                            <div class="row">
                              <div class="col-xs-12 text-right">
                                  <!--<a href='slidesadd.php' class="btn btn-default" ><span class="glyphicon glyphicon-plus"></span> Agregar Foto</a> -->
                                  <a href="" class="btn btn-default" data-toggle="modal" data-target="#myModal" title="Agregar Foto" title="Agregar"><i class="fa fa-plus"> </i> <strong>Nuevo</strong></a>
                              </div>

                            </div>

                          <br>
                          <div id="loader" class="text-center"> <span><img src="./img/ajax-loader.gif"></span></div>
                          <div class="outer_div"></div>

                      </div>
                    </div>
                </div>
            </div>               
        </div>
    </body>
</html>
<script>
	$(document).ready(function(){
		load(1);
	});
	function load(page){
		var idPaciente = <? echo $idPaciente ?>;
		var parametros = {"action":"ajax","idPaciente":idPaciente,"page":page};
		$.ajax({
			url:'./ajax/slider_ajax.php',
			data: parametros,
			 beforeSend: function(objeto){
			$("#loader").html("<img src='./img/ajax-loader.gif'>");
		  },
			success:function(data){
				$(".outer_div").html(data).fadeIn('slow');
				$("#loader").html("");
			}
		})
	}
	function eliminar_slide(id){
		page=1;
        var idPaciente = <? echo $idPaciente ?>;
		var parametros = {"action":"ajax","page":page,"id":id, "idPaciente":idPaciente};
		if(confirm('Esta acción  eliminará de forma permanente el slide \n\n Desea continuar?')){
		$.ajax({
			url:'./ajax/slider_ajax.php',
			data: parametros,
			 beforeSend: function(objeto){
			$("#loader").html("<img src='./img/ajax-loader.gif'>");
		  },
			success:function(data){
				$(".outer_div").html(data).fadeIn('slow');
				$("#loader").html("");
			}
		})
	}
	}


        function eliminar(id){
            var parametros = {"action":"delete","id":id};
                    $.ajax({
                        url:'ajax/upload2.php',
                        data: parametros,
                         beforeSend: function(objeto){
                        $(".upload-msg2").text('Cargando...');
                      },
                        success:function(data){
                            $(".upload-msg2").html(data);

                        }
                    })

            }

    $("#editar_slide").submit(function(e) {

          $.ajax({
              url: "ajax/editar_slide.php",
              type: "POST",
              data: $("#editar_slide").serialize(),
               beforeSend: function(objeto){
                $("#loader").html("Cargando...");
              },
              success:function(data){
                    $(".outer_div_new").html(data).fadeIn('slow');
                    $("#loader_new").html("");
					//$('#myModal').modal('toggle');
                }
        });
         e.preventDefault();
    });

function onClick(element) {
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
}
</script>