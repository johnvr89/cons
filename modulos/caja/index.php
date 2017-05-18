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
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom');
	$fecha=date('Y-m-d');
	$hora=date('H:i:s');
	
	
	$pa=mysql_query("SELECT * FROM cajero WHERE usu='$usu'");				
	while($row=mysql_fetch_array($pa)){
		$id_consultorio=$row['consultorio'];
		$oConsultorio=new Consultar_Deposito($id_consultorio);
		$nombre_Consultorio=$oConsultorio->consultar('nombre');
	}
	
	$paz=mysql_query("SELECT * FROM tarifas WHERE config='df'");				
	while($row=mysql_fetch_array($paz)){
		$preciodf=$row['valor'];
		
	}

	if(!empty($_GET['del'])){
		$id=$_GET['del'];
		mysql_query("DELETE FROM caja_tmp WHERE paciente='$id'");
		header('location:index.php');
	}
	
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

</head>
<body>
    <div id="wrapper">
           <?php 
                $_SESSION['menu'] = 'CAJA';
                include_once "../../menu/m_general.php";
           ?>
        <div id="page-wrapper" >
		<?php if(permiso($_SESSION['cod_user'],'5')==TRUE){ ?>
            <div id="page-inner">
			<div class="row">
			  <div class="col-md-4"></div>
			 <div class="col-md-4">
			  <a href="operaciones.php" class="btn btn-primary btn-lg btn-block" title="Listado">
					<i class="fa fa-search" ></i><strong> Ver Pagos</strong>
				</a>
			 </div> 
			 <div class="col-md-4"></div>
			</div>		
			<hr />													
<!--###################################################################################################################################### -->				

				<div class="alert alert-success" align="center">				
                    <form name="form2" action="" method="post">
                        <strong>CODIGO/NOMBRE PACIENTE</strong><br>
                        
                            

                            
                        <input type="text" autofocus list="browsers"  name="buscar" autocomplete="off" class="form-control" required>
                        <datalist id="browsers">
                            <?php

                            $strSql = "SELECT * 
                                        FROM factura f
                                       WHERE date_format(f.fecha_ingresa,'%Y%m%d')=date_format(curdate(),'%Y%m%d')  
                                         AND f.ESTADO = 'PENDIENTE'
                                         AND f.consultorio_id = $id_consultorio";
                            
                            $pa = mysql_query($strSql);
                            while($row = mysql_fetch_array($pa))
                            {
                                $oPaciente = new Consultar_Paciente($row['paciente_id']);
                                echo '<option value="' . $row['id'] . '"  label = "' . $oPaciente->consultar('nombre') . '"  >';
                            }
                            ?> 
                        </datalist>
                    </form>
                </div> 

                 <!-- /. ROW  -->										 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->                 
                        <div class="panel-body">
                            <div class="table-responsive">
                        <?php
                            //solo para obtener el nombre del paciente
                            if($_POST['buscar'])
                            {
                                $idPaciente = consultar('paciente_id','factura',' id='.$_POST['buscar']);
                                if($idPaciente)
                                {
                                    $nombrePaciente = consultar('nombre','pacientes',' id='.$idPaciente);
                                }
                            }
                        
                        ?>      
                        <h3 align="center"><span class="label label-success"><?php echo $nombrePaciente; ?></span></h3>

                        <table class="table table-striped">
                            <tr class="well-dos">
                            	<td><strong>CANT</strong></td>
                                <td><strong>SERVICIO</strong></td>
                                <td><strong>V. UNIT</strong></td>
                                <td><strong>TOTAL</strong></td>
                            </tr>
                            <?php 
								$neto=0;
                                $item=0;

                                $idFactura = $_POST['buscar'];

                                $sql = "SELECT * FROM detalle WHERE factura_id = ".$idFactura;
                                
                                $pa=mysql_query($sql);			
                                while($row=mysql_fetch_array($pa)){
                                    $nombreTarifa = '';
                                    $sqlTarifa = "SELECT * FROM tarifas WHERE id = ".$row['tarifa_id'];
                                    $objQuey = mysql_query($sqlTarifa);
                                    while($rowTarifa=mysql_fetch_array($objQuey)){
                                        $nombreTarifa = $rowTarifa['nombre'];
                                    }
                                    
                            ?>
                            <tr>
							 <td><?php echo $row['cantidad']; ?></td>                           	                                
                             <td><span class="label label-primary">  <?php echo $nombreTarifa; ?> </span></td>
                             <td>$<?php echo $row['valor']; ?></td>
                             <td>$<?php echo $total = $row['valor'] * $row['cantidad']; ?></td>
                            </tr>														
                            
                            <?php  $totalFact = $totalFact + $total; } ?>
                        </table>
								
                            </div>
                            
                        
                    </div>
                    <!--End Advanced Tables -->
					<div class="span4">
                    	<table class="table table-bordered">
                            <tr>
                                <td>
                                	<center><strong>TOTAL</strong>
                                	<pre><h2 class="text-success" align="center">$ <?php echo formato($totalFact); ?></h2></pre>
                                    
                                </td>
                            </tr>
                    	</table>
                        <?php if($totalFact<>0){ ?>
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                	<div align="center">
                                        <a href="#contado" role="button" class="btn btn-primary btn-lg" data-toggle="modal">
                                            <i class="icon-shopping-cart icon-white"></i> <strong>Realizar Pago</strong>
                                        </a>
                                	</div>
                                </td>
                            </tr>
                    	</table>
                        <?php } ?>
                    </div>
                </div>
            </div>
                <!-- /. ROW  -->
					<!--  Modals-->
								 <div class="modal fade" id="contado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<form name="contado" action="pro_contado.php" method="get">
								<div class="modal-dialog">
									<div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>													
                                                <h3 align="center" class="modal-title" id="myModalLabel">Pago</h3>
                                            </div>
										<div class="panel-body">
										<div class="row" align="center">                                       
																					
											 <div class="alert alert-danger">
												<h4> $ <?php echo $totalFact; ?></h4>
											</div>											
											<input type="hidden" value="<?php echo $totalFact; ?>" name="total">
                                            <input type="hidden" value="<?php echo $idFactura; ?>" name="idFactura">
											
                                            <label for="sel1">Forma de pago:</label>
                                            <select class="form-control" id="tipo_pago" name='tipo_pago' style="width: 80%;" >
                                              <option>EFECTIVO</option>
                                              <option>CHEQUE</option>
                                              <option>TARJETA DE CREDITO</option>
                                            </select>
																					                                                                    
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
<?php }else{ echo mensajes("NO TIENES PERMISO PARA ENTRAR A ESTE FORMULARIO","rojo"); }?>					 
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
    </div>
         <!-- /. PAGE WRAPPER  -->
   
</body>
</html>
