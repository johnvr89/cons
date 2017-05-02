<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	#$documento=limpiar($_SESSION['cod_user']);
	if($_SESSION['cod_user']){
	}else{
		header('Location: ../../php_cerrar.php');
	}
	$id_medico  = $_SESSION['cod_user'];
	$usu        = $_SESSION['cod_user'];
	$oPersona   = new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom');
	
	
	$usu=$_SESSION['cod_user'];
	$pa=mysql_query("SELECT * FROM cajero WHERE usu='$usu'");				
	while($row=mysql_fetch_array($pa)){
		$id_consultorio=$row['consultorio'];
		$oConsultorio=new Consultar_Deposito($id_consultorio);
		$nombre_Consultorio=$oConsultorio->consultar('nombre');
	}
	
	######### TRAEMOS LOS DATOS DE LA EMPRESA #############
		$pa=mysql_query("SELECT * FROM empresa WHERE id=1");				
        if($row=mysql_fetch_array($pa)){
			$nombre_empresa=$row['empresa'];
		}
	
	if(!empty($_GET['del'])){
		$id=$_GET['del'];
		mysql_query("DELETE FROM consultas_medicas WHERE id='$id'");
		header('index.php');
		
	}
    
        $sqlSelect = "SELECT * FROM tarifas WHERE estado = 's' and id_consultorio ='$id_consultorio'";
        $objQuery=mysql_query($sqlSelect);
        $strOption = '<option> </option>';
        while($r=mysql_fetch_array($objQuery))
        {
            $strDatosOption = $strDatosOption.$r['id'].','.$r['nombre'].'|';
            $strOption = $strOption."<option value='".$r['id']."'>".$r['nombre']."</option>";
        }    
     
?>
<!DOCTYPE html>

<head>
    <title> Consultorio </title>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>

       <script type="text/javascript">
          
           //var optionSelected = '<option> </option>';
           $(document).ready(function(){
                var optionSelected="<?php echo $strOption; ?>";
                var i=1;
                $("#add_row").click(function(){
                   
                $('#addr'+i).html("<td>"+ (i+1) +"</td><td> <input  name='cantidad"+i+"' type='number' placeholder='Cantidad' value='1' class='form-control input-md'> </td><td>  <select class='form-control' name='procedimiento"+i+"' autocomplete='off' style='width:100%'> "+optionSelected+" </select></td>");

                $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
                i++; 
            });
               $("#delete_row").click(function(){
                   if(i>1){
                   $("#addr"+(i-1)).html('');
                   i--;
                   }
               });

          });
          
        </script>
        
</head>
<body>
    <div id="wrapper">
           <?php 
                $_SESSION['menu'] = 'CONSULTAS';
                include_once "../../menu/m_general.php"; 
           ?>        
        
        <div id="page-wrapper" >
            <div id="page-inner">						                
<?php if(permiso($_SESSION['cod_user'],'3')==TRUE){ ?>
							 <div class="table-responsive">
							 <?php 
                                    
									if(!empty($_POST['id_paciente'])){
                                       $i = 0;     
                                       
                                       $arrayDetalles = array();
                                       $band = true;
                                       while ($band == true)
                                       {
                                           if(empty($_POST['procedimiento'.$i]) && empty($_POST['cantidad'.$i]) )
                                           {
                                                $band = false; 
                                           }
                                           else
                                           {
                                                array_push($arrayDetalles, $_POST['procedimiento'.$i].'|'.$_POST['cantidad'.$i] );
                                           }
                                           $i++;
                                       }
                                       
                                        $idCita = limpiar($_POST['id_cita']);                                        
										$id_paciente=limpiar($_POST['id_paciente']);
                                        $idMedico = limpiar($_POST['idMedico']);
										$sintomas=limpiar($_POST['sintomas']);
										$examen=limpiar($_POST['examen']);																												
										$diagnostico=limpiar($_POST['diagnostico']);																											
										$tratamiento=limpiar($_POST['tratamiento']);																											
										$reseta='ok';																											
										$observaciones=limpiar($_POST['observaciones']);
										$fecha=date('Y-m-d');
										$hora=date('H:i:s');
										$status='PENDIENTE';
										#lOS MEDICAMENTOS
										$med1=limpiar($_POST['med1']);										
										$indi1=limpiar($_POST['indi1']);
										$med2=limpiar($_POST['med2']);										
										$indi2=limpiar($_POST['indi2']);
										$med3=limpiar($_POST['med3']);										
										$indi3=limpiar($_POST['indi3']);
										$med4=limpiar($_POST['med4']);										
										$indi4=limpiar($_POST['indi4']);
										$med5=limpiar($_POST['med5']);										
										$indi5=limpiar($_POST['indi5']);
										$med6=limpiar($_POST['med6']);										
										$indi6=limpiar($_POST['indi6']);
										$med7=limpiar($_POST['med7']);										
										$indi7=limpiar($_POST['indi7']);
										$med7=limpiar($_POST['med7']);										
										$indi7=limpiar($_POST['indi7']);
										$med8=limpiar($_POST['med8']);										
										$indi8=limpiar($_POST['indi8']);
										$med9=limpiar($_POST['med9']);										
										$indi9=limpiar($_POST['indi9']);
										$med10=limpiar($_POST['med10']);										
										$indi10=limpiar($_POST['indi10']);	
																						
										if(empty($_POST['id'])){
											$oConsulta=new Proceso_Consulta('',$id_paciente,$idMedico,$id_consultorio,$sintomas,$examen,$diagnostico,$tratamiento,$reseta,$observaciones,$fecha,$hora,$status,
																				$med1,$indi1,$med2,$indi2,$med3,$indi3,$med4,$indi4,$med5,$indi5,$med6,$indi6,$med7,$indi7,$med8,$indi8,$med9,$indi9,$med10,$indi10, $idCita, $arrayDetalles);
											$oConsulta->crearFactura();
											echo mensajes('Consulta Medica Creada con Exito','verde');
										}else{
                                            
                                            //print_r($_POST);
                                            
                                            $registros = $_POST['countTable'];
                                            $idFactura  = $_POST['idFactura'];
                                            for ($i=0; $registros > $i; $i++)
                                            {
                                                $id     = $_POST['id'.$i];
                                                $cant   = $_POST['cant'.$i];
                                                $valor  = $_POST['valor'.$i];
                                                
                                                //echo $idFactura.'*'.$id.'-'.$cant.'-'.$valor;
                                                if($id)
                                                {
                                                    //se debe actualizar
                                                    $sqlUpdate = "update detalle set cantidad = $cant, tarifa_id = $valor where id = $id  ";
                                                    mysql_query($sqlUpdate);
                                                }
                                                else
                                                {
                                                    //se debe ingresar
                                                    if($cant && $valor)
                                                    {
                                                        $costo = consultar('valor', 'tarifas', 'id = '.$valor);
                                                        
                                                        $sqlInsert = "insert into detalle (factura_id, tarifa_id, valor, cantidad, user_ingresa, estado) 
                                                                           values ($idFactura, $valor, $costo, $cant, '$usu', 'A' )";
                                                        mysql_query($sqlInsert);
                                                    }
                                                }
                                                
                                            }
                                            
                                            recalcularFactura($idFactura);
                                            
											$id=limpiar($_POST['id']);
											$oConsulta=new Proceso_Consulta($id,$sintomas,$examen,$diagnostico,$tratamiento,$reseta,$observaciones);
											$oConsulta->actualizar();
											echo mensajes('Consulta Medica Actualizada con Exito','verde');
										}
									}
								?>
                                 
                                 <table class="table table-striped table-bordered table-hover" id="dataTables-example1">                                    
									<thead>
                                        <tr>
                                            <th># CITA</th>
                                            <th>CONSULTAS DE HOY</th>                                                                                                                                                                                                                                                                                                                                                                                             
                                            <th>HORA</th>                                                                                                                                                                                                                                                                                                                                                                                             
                                            <th>PAGO</th>                                           
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
                                            $pame = mysql_query("SELECT * FROM citas_medicas WHERE  fecha_cita = date(sysdate()) AND consultorio='$id_consultorio' and status='PENDIENTE' and consulta='PENDIENTE' ORDER BY id ASC");
                                            while($row = mysql_fetch_array($pame))
                                            {
                                                $oPaciente = new Consultar_Paciente($row['id_paciente']);
                                                $url = $row['id'];
                                                $id_paciente = $row['id_paciente'];
                                                ?>
                                        <tr>                                           
                                            <td><?php echo $row['id']; ?></td>                                                                                     
                                            <td>
											<a href="#new<?php echo $row['id_paciente']; ?>" role="button" class="btn btn-info btn-xs"  data-toggle="modal">
											<strong><?php echo $oPaciente->consultar('nombre'); ?></strong>
                                            </a>
                                                
											 <!--  Modals-->
										 <div class="modal fade" id="new<?php echo $row['id_paciente']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<form name="form1" method="post" action="">
                                                <input type="hidden" name="id_cita" value="<?php echo $row['id']; ?>">
												<input type="hidden" name="id_paciente" value="<?php echo $row['id_paciente']; ?>">
                                                <input type="hidden" name="idMedico" value="<?php echo $row['id_medico']; ?>">
												<div class="modal-dialog">
												<div class="modal-content">
                                                    
												<div class="panel-body">
												<div class="row">
												<ul class="nav nav-tabs nav-justified">
	                                            <li class="active"><a href="#datos" data-toggle="tab"><i class="glyphicon glyphicon-book" ></i> CONSULTA</a></li>
	                                            <li class="" ><a href="#tipo" data-toggle="tab"><i class="glyphicon glyphicon-book" ></i> RECETA/MEDICAMENTOS</a></li> 
                                                
	                                            </ul><br>
	                                            <div class="tab-content">
	                                            <div class="tab-pane fade active in" id="datos">
                                                    
	                                            <div class="col-md-6">
													<label>Nombre:</label> <?php echo $oPaciente->consultar('nombre'); ?><br>													
													<label>Dirección:</label> <?php echo $oPaciente->consultar('direccion'); ?><br>												
													<label>Telefono:</label> <?php echo $oPaciente->consultar('telefono'); ?><br>
													</div>
													<div class="col-md-6">
														<?php
														   // primero conectamos siempre a la base de datos mysql
															$sql = "SELECT * FROM citas_medicas WHERE consultorio='$id_consultorio' and id_paciente='$id_paciente'";  // sentencia sql
															$result = mysql_query($sql);
															$numero = mysql_num_rows($result); // obtenemos el número de filas
																		
														?>
														<label>Fecha de Nacimiento:</label> <?php echo fecha($oPaciente->consultar('edad')); ?><br>														
														<label>Edad:</label> <?php echo CalculaEdad($oPaciente->consultar('edad')); ?> Años<br>
                                                        <label>Visitas: <a href="../historial_medico/index.php?id=<?php echo $row['id_paciente']; ?>" target="_blank"><span class="label label-success"><?php echo "$numero" ?></span></a></label> <br><br>
													</div> 	
                                                    
                                                    
													<div class="col-md-6">																						
														<span class="input-group-addon">Tratamiento:</span>
														<textarea class="form-control" name="tratamiento" rows="3" style='width:100%' required></textarea><br>
														<span class="input-group-addon">Observaciones:</span>
														<textarea class="form-control" name="observaciones" style='width:100%' rows="3"></textarea><br>	
													</div>
													<div class="col-md-6">																				
                                                        <span class="input-group-addon">Medico:</span>
                                                        <select class="form-control" name="medico" autocomplete="off" required style='width:100%'> 
                                                        <option></option>
                                                        <?php
                                                            $sql = "SELECT * FROM usuario WHERE estado = 's'and consultorio='$id_consultorio' and cargo = 'MEDICO'";
                                                            $p=mysql_query($sql);				
                                                            while($r=mysql_fetch_array($p))
                                                            {
                                                                if($r['id'] == $row['id_medico'])
                                                                {
                                                                    echo '<option selected value="'.$r['id'].'">'.$r['nombre'].'</option>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
                                                                }
                                                            }
                                                        ?>												
                                                        </select>
													</div>
                                                   <div class="col-md-12">
                                                       <br>
                                                       <div class="alert alert-info" align="center"><strong>Procedimientos Realizados</strong></div>
                                                       
                                                         <table class="table table-bordered table-hover" id="tab_logic" width = '100%'>
                                                           <thead>
                                                               <tr >
                                                                   <th class="text-center" width = '5%'>
                                                                       #
                                                                   </th>
                                                                   <th class="text-center" width = '15%'>
                                                                       Cantidad
                                                                   </th>
                                                                   <th class="text-center" width = '80%'>
                                                                       Procedimiento
                                                                   </th>
                                                               </tr>
                                                           </thead>
                                                           <tbody>
                                                               <tr id='addr0'>
                                                                   <td>
                                                                   1
                                                                   </td>
                                                                   <td>
                                                                    <input type="number" name='cantidad0' value='1'  placeholder='Cantidad' class="form-control"/>
                                                                   </td>
                                                                   <td>                                                                       
                                                                    <select class="form-control" name="procedimiento0" required placeholder='Procedimiento' autocomplete="off" style='width:100%'>
                                                                    <option value="<?php echo $row['id_motivo']; ?>" selected><?php if($row['id_motivo']){ echo  $nombreMotivo = consultar('nombre', 'tarifas', 'id = '.$row['id_motivo']);}  ?></option>
                                                                        <?php echo $strOption ?>
                                                                    </select>
                                                                   </td>
                                                               </tr>
                                                               <tr id='addr1'></tr>
                                                           </tbody>
                                                       </table>                                                                                                             
                                                       <p align="center"><a id="add_row" class="btn btn-primary"><b>+</b></a><a id='delete_row' class="btn btn-danger"><b>-</b></a></p>
                                                    </div>
													</div>
                                                    
													<div class="tab-pane fade" id="tipo">
                                                    <div class="col-md-6">											
													<input class="form-control" name="med1" placeholder="Medicamento 1" autocomplete="off" >
                                                    <textarea class="form-control" name="indi1" placeholder="Indicación" rows="2" ></textarea><br>
                                                    <input class="form-control" name="med2" placeholder="Medicamento 2" autocomplete="off">
                                                    <textarea class="form-control" name="indi2" placeholder="Indicación" rows="2" ></textarea><br>
                                                    <input class="form-control" name="med3" placeholder="Medicamento 3" autocomplete="off">
                                                    <textarea class="form-control" name="indi3" placeholder="Indicación" rows="2" ></textarea><br>
                                                    <input class="form-control" name="med4" placeholder="Medicamento 4" autocomplete="off">
                                                    <textarea class="form-control" name="indi4" placeholder="Indicación" rows="2"></textarea><br>
                                                    <input class="form-control" name="med5" placeholder="Medicamento 5" autocomplete="off">
                                                    <textarea class="form-control" name="indi5" placeholder="Indicación" rows="2"></textarea><br>
													</div>
													<div class="col-md-6">																				
													<input class="form-control" name="med6" placeholder="Medicamento 6" autocomplete="off">
                                                    <textarea class="form-control" name="indi6" placeholder="Indicación" rows="2"></textarea><br>
                                                    <input class="form-control" name="med7" placeholder="Medicamento 7" autocomplete="off">
                                                    <textarea class="form-control" name="indi7" placeholder="Indicación" rows="2" ></textarea><br>
                                                    <input class="form-control" name="med8" placeholder="Medicamento 8" autocomplete="off">
                                                    <textarea class="form-control" name="indi8" placeholder="Indicación" rows="2"></textarea><br>
                                                    <input class="form-control" name="med9" placeholder="Medicamento 9" autocomplete="off">
                                                    <textarea class="form-control" name="indi9" placeholder="Indicación" rows="2"></textarea><br>
                                                    <input class="form-control" name="med10" placeholder="Medicamento 10" autocomplete="off">
                                                    <textarea class="form-control" name="indi10" placeholder="Indicación" rows="2"></textarea><br>																							
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
                                            </div>
											</form>
										</div>
								 <!-- End Modals-->                                                
                                                
											</td>
											<td><?php echo consultar('horario','horarios_cita',' id='.$row['horario']); ?></td>   
                                            <td><?php echo status($row['status']); ?></td>

                                        </tr>
										<?php } ?>
                                    </tbody>									
                                </table>
								 </div>					 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                             ULTIMAS CONSULTAS
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">								
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example" >
                                    
									<thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PACIENTE</th>                                                                                                                              
                                            <th>FECHA</th>                                                                                      
                                            <th>MEDICO</th>                                                                                      
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php 											
											$pame=mysql_query("SELECT * FROM consultas_medicas WHERE consultorio='$id_consultorio' ORDER BY id DESC LIMIT 10");		 													
											while($row=mysql_fetch_array($pame)){
											$oPaciente=new Consultar_Paciente($row['id_paciente']);
							                #$oMedico=new Consultar_Medico($row['id_medico']);
											$url=$row['id'];
										?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row['id']; ?></td>
                                            <td><i class="fa fa-user fa-2x"></i> <?php echo $oPaciente->consultar('nombre'); ?></td>                                                                                   
                                            <td><?php echo fecha($row['fecha']).' '.$row['hora']; ?></td>
											<td><?php echo consultar('nombre','usuario',' id='.$row['id_medico']); ?></td>  
                                            <td class="center">
											<div class="btn-group">
											  <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle"><i class="fa fa-cog"></i> <span class="caret"></span></button>
											  <ul class="dropdown-menu  pull-right">
												<li><a href="#" data-toggle="modal" data-target="#actualizar<?php echo $row['id']; ?>"><i class="fa fa-edit"></i> Editar</a></li>
												<!--<li class="divider"></li>
												<li><a href="index.php?del=<?php echo $row['id']; ?>" ><i class="fa fa-pencil"></i> Eliminar</a></li>-->																																				
											  </ul>
											</div>
											<a href="../imprimir/index.php?id=<?php echo $url; ?>" class="btn btn-primary btn-sm" title="Imprimir">
											<i class="fa fa-print" ></i>
										    </a>
                                                
								<!--  Modals-->
										 <div class="modal fade" id="actualizar<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<form name="form2" id ="form2" method="post" action="">
												<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>															
																<h4 align="center" class="modal-title" id="myModalLabel">MODIFICAR</h4>
															</div>
															<div class="panel-body">
															<div class="row">
																<div class="col-md-12">
																<div class="alert alert-info" align="center"><strong><?php echo $oPaciente->consultar('nombre'); ?></strong></div>
																<input type="hidden" name="nombre">																				
																</div>
																<div class="col-md-6">
																		<br>
                                                                        <span class="input-group-addon">Tratamiento</span>
																		<textarea class="form-control" name="tratamiento" value="<?php echo $row['tratamiento']; ?>" rows="2"><?php echo $row['tratamiento']; ?></textarea><br>																	
																	    <span class="input-group-addon">Medicamento</span>
																		<textarea class="form-control" name="reseta" value="<?php echo $row['reseta']; ?>" rows="2"><?php echo $row['reseta']; ?></textarea><br>
																		<!--<span class="input-group-addon">Motivo de consulta</span>
																		<textarea class="form-control" name="sintomas" value="<?php echo $row['sintomas']; ?>" rows="2"><?php echo $row['sintomas']; ?></textarea><br>
																		<span class="input-group-addon">Examen Físico</span>
																		<textarea class="form-control" name="examen" value="<?php echo $row['examen']; ?>" rows="2"><?php echo $row['examen']; ?></textarea><br>																	
																		<span class="input-group-addon">Diagnostico</span>
																		<textarea class="form-control" name="diagnostico" value="<?php echo $row['diagnostico']; ?>" rows="2"><?php echo $row['diagnostico']; ?></textarea><br>-->
																</div>
																<div class="col-md-6">
																		<br>
																		<span class="input-group-addon">Observaciones</span>
																		<textarea class="form-control" name="observaciones" value="<?php echo $row['observaciones']; ?>" rows="2"><?php echo $row['observaciones']; ?></textarea>											
																</div>
                                                                <br>
                                                                
                                                                <div class="col-md-12">
                                                                    <br>
                                                                    <div class="alert alert-info" align="center"><strong>Procedimientos Realizados</strong></div>
                                                                    <INPUT  type="hidden" id ="countTable" name ="countTable" value="0">
                                                                    <INPUT  type="hidden" id ="id_paciente" name ="id_paciente" value="<?php echo $row['id_paciente']; ?>">
                                                                    <INPUT type="button" value="Agregar" onclick="addRow('dataTable<?php echo $row['id']; ?>');" />
                                                                    <INPUT type="button" value="Eliminar" onclick="deleteRow('dataTable<?php echo $row['id']; ?>');" />

                                                                    <TABLE class="table table-bordered table-hover" id="dataTable<?php echo $row['id']; ?>" width="100%" border="1">
                                                                        
                                                                    <thead>
                                                                        <tr >
                                                                            <th class="text-center" width = '5%'>
                                                                                
                                                                            </th>
                                                                            <th class="text-center" width = '5%'>         
                                                                                #
                                                                            </th>                                                                        
                                                                            <th class="text-center" width = '10%'>
                                                                                Cantidad
                                                                            </th>                                                                            
                                                                            <th class="text-center" width = '80%'>
                                                                                Procedimiento
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>                                                                        
                                                                       <?php                                                                       
                                                                       $strSqlMotivo = "SELECT d.* FROM factura f, detalle d WHERE f.id = d.factura_id and d.estado = 'A' and f.consulta_id = ".$row['id'];
                                                                       
                                                                       $arraySql = mysql_query($strSqlMotivo);	
                                                                       $cont = 0;
                                                                       while($rowMotivo = mysql_fetch_array($arraySql)){ 
                                                                           
                                                                        $nombreTarifa = consultar('nombre', 'tarifas', 'id ='.$rowMotivo['tarifa_id']);
                                                                           
                                                                        echo "<INPUT  type='hidden' name ='idFactura' value = '".$rowMotivo['factura_id']."'>";
                                                                        
                                                                        echo "<TR>
                                                                              <TD><INPUT type='checkbox' name='cb$cont' NAME='chk'></TD>
                                                                              <TD><INPUT type='text' readonly name='id$cont' value = ".$rowMotivo['id']."  > </TD>
                                                                              <TD> <INPUT type='text' name='cant$cont' value = ".$rowMotivo['cantidad']."> </TD>
                                                                              <TD> 
                                                                                <select type='text' name='valor$cont' >                                                                                     
                                                                                <option value = ".$rowMotivo['tarifa_id']." selected>".$nombreTarifa. " </option>
                                                                                 $strOption 
                                                                                </select> 
                                                                              </TD>
                                                                             </TR>";
                                                                            $cont ++;
                                                                         } ?>
                                                                        </tbody>
                                                                    </TABLE>
                                                                </div>
                                                                
															</div> 
															</div> 
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
																<button type="button" class="btn btn-primary" onclick = "actualiza('dataTable<?php echo $row['id']; ?>')" >Guardar</button>
															</div>										 
														</div>
													</div>
													</form>
												</div>
										 <!-- End Modals-->                                                
                                                
											</td>								
                                        </tr> 																								

										
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
<?php }else{ echo mensajes("NO TIENES PERMISO PARA ENTRAR A ESTE FORMULARIO","rojo"); }?>				
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
     <!-- /. WRAPPER  -->    
               
     <script language="javascript">
         
     $(document).ready(function () {
                $('#dataTables-example1').dataTable();
            });       
            
            
            
          function addRow(tableID) {
              

               var opciones ="<?php echo $strDatosOption; ?>";
               
               var res = opciones.split("|");
               
               var i;
               var text = "";              


               var table = document.getElementById(tableID); 

               var rowCount = table.rows.length;

               var row = table.insertRow(rowCount); 
               
               var cell1 = row.insertCell(0);
               var element1 = document.createElement("input");
               element1.type = "checkbox";
               element1.name = "cb"+rowCount;
               cell1.appendChild(element1);

               var cell2 = row.insertCell(1);
               var element2 = document.createElement("input");
               element2.hidden = true;               
               element2.type = "text";
               element2.name = "id"+rowCount;
               cell2.appendChild(element2);

               var cell3 = row.insertCell(2);
               var element3 = document.createElement("input");
               element3.type = "text";
               element3.name = "cant"+rowCount;
               cell3.appendChild(element3);

               var cell4 = row.insertCell(3);
               var element4 = document.createElement("select");      
               element4.name = "valor"+rowCount;
               element4.id = "valor"+rowCount;
               cell4.appendChild(element4);
               
                var option1 = document.createElement("option"); 
                option1.value = '';

                var t = document.createTextNode('');
                option1.appendChild(t);

                document.getElementById("valor"+rowCount).appendChild(option1);  
        
               for (i = 0; i < res.length; i++) {                    
        
        
                    if(res[i])
                    {
                        var resOption = res[i].split(",");
                        
                        var option1 = document.createElement("option"); 
                        option1.value = resOption[0];

                        var t = document.createTextNode(resOption[1]);
                        option1.appendChild(t);

                        document.getElementById("valor"+rowCount).appendChild(option1);                        
                    }
                }               

          }

 

          function deleteRow(tableID) {
               try {                   

               var table = document.getElementById(tableID);
               var rowCount = table.rows.length; 

               for(var i=0; i<rowCount; i++) {

                    var row = table.rows[i];
                    
                    var chkbox = row.cells[0].childNodes[0];
                    if(null != chkbox && true == chkbox.checked) {
                            
                            var objId = row.cells[1].childNodes[0];
                            
                            if(objId)
                            {
                                var id = objId.value;
                                deleteAjax(id);
                                console.log(id);
                            }                         
                         
                         table.deleteRow(i);
                         rowCount--;
                         i--;

                    }
               }
               }catch(e) {
                    alert(e);
               }
          }
          
          
          function deleteAjax (id)
          {
                var parametros = {"action":"delete","id":id};
                $.ajax({
                    url:'ajax_consultas.php',
                    method: 'POST',
                    data: parametros,
                    success:function(data){

                        null;
                    }
                })
          }
          

          function actualiza(tableID)
          {
               var table = document.getElementById(tableID);
               var rowCount = table.rows.length; 
               document.getElementById('countTable').value = rowCount;
              //verifico si existe algo que guardar en la matriz
              //guardaGrid(tableID);
              //return false;
              document.getElementById("form2").submit();

          }
            
            
        </script>
</body>
</html>
