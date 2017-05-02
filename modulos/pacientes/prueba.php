      <!--   Nuevo   -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form class="form-horizontal" id="editar_slide">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 align="center" class="modal-title" id="myModalLabel">Editar slide</h3>
                    </div>
                    <div class="panel-body">                        
                        
                    </div> 
                    <div class="modal-footer">


                    </div>										 
                    </div>
               </div>
            </form>
        </div>
        
        <!--   Nuevo   -->





<?php
$title="Configuración de Slider";
/* Llamar la Cadena de Conexion*/ 
//include ("config/conexion.php");
include_once "../php_conexion.php";
$active="active";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../images/ico/favicon.ico">
    <title><?php echo $title;?></title>
    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Custom styles for this template -->
    <link href="css/navbar-fixed-top.css" rel="stylesheet">
  </head>
  <body>
	<?php include("top_menu.php");?>
	
    <div class="container">
		
      <!-- Main component for a primary marketing message or call to action -->
      <div class="row">
	   	  
		 <ol class="breadcrumb">
		  <li><a href="sliderslist.php">Slider</a></li>
		  <li class="active">Listado</li>
		</ol>
			<div class="row">
			  <div class="col-xs-12 text-right">
				  <a href='slidesadd.php' class="btn btn-default" ><span class="glyphicon glyphicon-plus"></span> Agregar Slide</a>
			  </div>
			  
			</div>
		  
		  <br>
		  <div id="loader" class="text-center"> <span><img src="./img/ajax-loader.gif"></span></div>
		  <div class="outer_div"></div><!-- Datos ajax Final -->
				  
	  </div>

    </div> <!-- /container -->
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
<script>
	$(document).ready(function(){
		load(1);
	});
	function load(page){
		var parametros = {"action":"ajax","page":page};
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
		var parametros = {"action":"ajax","page":page,"id":id};
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
</script>