<?php 

//// Activar errores
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);

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
	
	$fechay=date('Y-m-d');
	$horay=date('H:m:s');
	
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
		mysql_query("DELETE FROM citas_medicas WHERE status='PENDIENTE' and id='$id'");
		header('index.php');
		
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
	<!-- BOOTSTRAP STYLES-->
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../../assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CALENDARIO STYLES-->
   <link href="../../assets/todo/bootstrap-datetimepicker.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="../../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="../../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/bootstrap-select.min.css" />
    
    
</head>
<body>
    
    <div id="wrapper">
           <?php 
                $_SESSION['menu'] = 'CITAS'; 
                include_once "../../menu/m_general.php";
           ?>
        <div id="page-wrapper" >
            <div id="page-inner">						                				
<?php if(permiso($_SESSION['cod_user'],'2')==TRUE){ ?>					 
				  <!--  Modals-->
                     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form name="form1" method="post" action="">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										
                                            <h3 align="center" class="modal-title" id="myModalLabel">Nueva Cita <div id="contenidoAjax">  </div>  </h3>
                                        </div>
										<div class="panel-body">
										<div class="row">       
                                                <div class="alert alert-warning alert-dismissable fade in" id="error" style="display: none;" >
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <strong>Advertencia!</strong> Ingrese los campos obligatorios (*).
                                                </div>
											<div class="col-md-6">
												<!--<form name="form1" method="post" action="">
												  <label>
												  <input type="text" autofocus class="form-control" id="id_paciente" name="id_paciente" list="characters" placeholder="Codigo o Nombre" autocomplete="off">
													  <datalist id="characters">
													  <?php $can=mysql_query("SELECT * FROM pacientes");while($dato=mysql_fetch_array($can)){echo '<option value="'.$dato['nombre'].'">';}?>
													</datalist>
													</label>
												</form><br>-->
								
												<label>Paciente: *</label>												
                                                <select name = 'id_paciente' id = "id_paciente"  data-live-search="true" data-live-search-style="startsWith" data-width="100%" class="selectpicker"  title="Seleccione...">  
                                                    <option value="" selected></option>
                                                    <?php 
                                                        $strSql = "SELECT * FROM pacientes where consultorio = ".$_SESSION['idConsultorio']." and estado='s'  ORDER BY nombre";
                                                        $sql=mysql_query($strSql);
                                                        while($row=mysql_fetch_array($sql)){
                                                            echo '<option data-icon="glyphicon-user" value="'.$row['id'].'" >'.$row['nombre'].'</option>';

                                                        }
                                                    ?>
                                                </select>                                                
                                                <br>
                                                <br>												        			
												 <label>Fecha Proxima Cita: *</label>
												<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
													<input class="form-control" size="16" type="text" name="fechai" id="fechai" onchange="obtenerHorarios();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" required>
                                                     
													<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
													<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
												</div>
												<input type="hidden" id="dtp_input2" name="fechai" /><br/>
                                                <label>Horario: *</label>
                                                <br>
                                                <select id = "horario" name="horario" onchange="obtenerMedicos()" >  </select>   
                                                <!--<div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                                                    <input class="form-control" type="text" name="horario"  required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                </div>-->
                                                <input type="hidden" id="dtp_input3" name="horario" /><br/>                                                    
											</div>
											<div class="col-md-6">
                                                <!--<input type="date" class="form-control" name="fechai" min="1"  autocomplete="off" required><br>-->																			
                                                <label>Medico:</label>                                                
                                                <select name="medico" id="medico" onclick="validaCombo()" data-live-search="true" data-live-search-style="startsWith" data-width="100%" class="selectpicker"  title="Seleccione..." >																					
                                                <option value="<?php echo $row['empresa_id']; ?>" selected disabled><?php echo $row['empresa']; ?></option>
                                                <?php
                                                    $sql = "SELECT * FROM usuario WHERE estado = 's'and consultorio='$id_consultorio' and cargo = 'MEDICO'";
                                                    $p=mysql_query($sql);				
                                                    while($r=mysql_fetch_array($p))
                                                    {
                                                        echo '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
                                                    }
                                                ?>												
                                                </select>  <br>
                                                <label>Motivo:</label>                                                
                                                <select name="motivo" id="motivo" onclick="validaCombo()" data-live-search="true" data-live-search-style="startsWith" data-width="100%" class="selectpicker"  title="Seleccione...">																					
                                                <option value="<?php echo $row['empresa_id']; ?>" selected disabled><?php echo $row['empresa']; ?></option>
                                                <?php
                                                    $sql = "SELECT * FROM tarifas WHERE estado = 's'and id_consultorio='$id_consultorio' ";
                                                    $p=mysql_query($sql);				
                                                    while($r=mysql_fetch_array($p))
                                                    {
                                                        echo '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
                                                    }
                                                ?>												
                                                </select>  <br>                                                    
                                                <label>Observaciones:</label>
                                                <textarea class="form-control" name="observaciones" id ="observaciones" rows="3"></textarea><br>
                                            </div>                                                                        
										</div> 
										</div> 
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" onclick="borrar()">Borrar</button>
                                            <button type="button" onclick="crear()" class="btn btn-primary">Guardar</button>
                                                                                        
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
                            CITAS
							<ul class="nav pull-right">
								<a href="" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal" title="Agregar Paciente" title="Agregar"><i class="fa fa-plus"> </i> <strong>Nueva</strong></a>								                            																										                            
							</ul>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
								<?php 
									if(!empty($_POST['id_paciente'])){ 	
                                        
										$id_paciente    = limpiar($_POST['id_paciente']);																											
										$fechai         = limpiar($_POST['fechai']);
										$horario        = $_POST['horario'];		
										$observaciones  = limpiar($_POST['observaciones']);
										$fecha          = date('Y-m-d');
										$hora           = date('H:m:s');
										$status         = 'PENDIENTE';
										$consulta       = 'PENDIENTE';
										$id_medico      = limpiar($_POST['medico']);
																						
										if($_POST['id']){
                                            
											$id=limpiar($_POST['id']);
											$oConsulta=new Proceso_Cita($id,$fechai,$observaciones,$horario);
											$oConsulta->actualizar();
											echo mensajes('Cita Medica Actualizada con Exito','verde');
                                            /*
											$oConsulta=new Proceso_Cita('',$id_paciente,$id_medico,$id_consultorio,$fechai,$observaciones,$fecha,$hora,$horario,$status,$consulta);
											$oConsulta->crear();
											echo mensajes('Cita Medica Guardada con Exito','verde');
                                             * 
                                             */
										}
									}
								?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    
									<thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PACIENTE</th>                                                                                                                              
                                            <th>FECHA DE PROXIMA CITA</th>                                                                                      
                                            <th>MOTIVO</th>
                                            <th>MED/ASIST.</th>
                                            <th>STATUS</th>                                                                                      
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php 
											if(!empty($_POST['buscar'])){
												$buscar=limpiar($_POST['buscar']);
												$pame=mysql_query("SELECT * FROM citas_medicas WHERE consultorio='$id_consultorio' and status <> 'ELIMINADO' and  fecha_cita >= date(sysdate()) order by fecha_cita desc  ");	
											}else{
												$sql = "SELECT * FROM citas_medicas WHERE consultorio='$id_consultorio' and status <> 'ELIMINADO' and  fecha_cita >= date(sysdate()) order by fecha_cita desc ";                                          
                                                $pame=mysql_query($sql);		 
                                                
											}		
											while($row=mysql_fetch_array($pame)){
											$oPaciente=new Consultar_Paciente($row['id_paciente']);
							                #$oMedico=new Consultar_Medico($row['id_medico']);
											$url=$row['id'];
											
											############# STATUS FULL ######################
											if($row['status']=='PENDIENTE'){
												
												 $status='<span class="label label-warning">PENDIENTE</span>';
											}else{
												$status='<span class="label label-success">ATENDIDO</span>';
												
											}
											$pamela=strftime( "%Y-%m-%d-%H-%M-%S", time() );										
											if($row['fechai']==$pamela){
													$status='si';
												}																								
												elseif($row['fechai']>$pamela){
													$status='<span class="label label-danger">PENDIENTE</span>';
												}
												$horario=$row['horario'];
										?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row['id']; ?></td>
                                            <td><i class="fa fa-user fa-2x"></i> <?php echo $oPaciente->consultar('nombre'); ?></td>                                                                                   
                                            <td><?php echo fecha($row['fecha_cita']).' '.$strHorario = consultar('horario','horarios_cita',' id='.$row['horario']); ?></td>
                                            <td><?php if ($row['id_motivo'] > 0) {echo consultar('nombre','tarifas',' id='.$row['id_motivo']);} ?></td>  
                                            <td><?php if ($row['id_medico'] > 0) {echo consultar('nombre','usuario',' id='.$row['id_medico']);} ?></td>
											<td><?php echo $status; ?></td>  
                                            <td class="center">
											<div class="btn-group">
											  <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle"><i class="fa fa-cog"></i> <span class="caret"></span></button>
											  <ul class="dropdown-menu pull-right">
												<!--<li><a href="#" data-toggle="modal" data-target="#actualizar<?php echo $row['id']; ?>"><i class="fa fa-edit"></i> Editar</a></li> 
												<li class="divider"></li> -->
												<li><a  href="#" data-toggle="modal" onclick="eliminarRegistro(<?php echo $row['id']; ?>);" ><i class="fa fa-pencil"></i> Eliminar</a></li>                                                
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
															
																<h3 align="center" class="modal-title" id="myModalLabel">Modificar Cita</h3>
															</div>
															<div class="panel-body">
															<div class="row">                                       
																<div class="col-md-6">
																	<label>Paciente:</label>
																		<select class="form-control" name="id_paciente" disabled >
																			<option value="x">---SELECCIONE---</option>
																			<?php
																				$p=mysql_query("SELECT * FROM pacientes WHERE estado='s'");				
																				while($r=mysql_fetch_array($p)){
																					if($r['id']==$row['id_paciente']){
																						echo '<option value="'.$r['id'].'" selected>'.$r['nombre'].'</option>';
																					}else{
																						echo '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
																					}
																				}
																			?>
																		</select><br>
																		<label>Horario:</label>
																		<div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
																			<input class="form-control" type="text" name="horario" value="<?php echo $row['horario']; ?>" required>
																			<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
																			<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
																		</div>
																		<input type="hidden" id="dtp_input3" name="horario" /><br/>																																																						
																		</div>
																<div class="col-md-6">
																		<label>Fecha:</label>
																		<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
																			<input class="form-control" size="16" type="text" name="fechai" value="<?php echo $row['fechai']; ?>" required>
																			<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
																			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
																		</div>
																		<input type="hidden" id="dtp_input2" name="fechai" /><br/>  																		
																		<label>Observaciones:</label>
																		<textarea class="form-control" name="observaciones" value="<?php echo $row['observaciones']; ?>" rows="3"><?php echo $row['observaciones']; ?></textarea><br>											
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
													<?php if(permiso($_SESSION['cod_user'],'2')==TRUE){ ?>	
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
																	una vez Eliminada la cita Medica con fecha <strong>[ <?php echo fecha($row['fecha']); ?> ]</strong><br>
																	con paciente <strong>[ <?php echo $oPaciente->consultar('nombre'); ?> ]</strong>
																	no podran ser Recuperados sus datos.<br>
																	Tenga en cuenta que si la cita fue procesada no podra ser eliminada.
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
														<?php }else{ echo mensajes("NO TIENES PERMISO PARA REALIZAR ESTA ACCION","rojo"); }?>
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
<?php }else{ echo mensajes("NO TIENES PERMISO PARA ENTRAR A ESTE FORMULARIO","rojo"); }?>				
        </div>               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
     <!-- /. WRAPPER  -->
	<!-- CALENDARIO SCRIPTS -->
    <script src="../../assets/todo/bootstrap-datetimepicker.js"></script>
    <script src="../../assets/todo/locales/bootstrap-datetimepicker.es.js"></script>
	<!-- VALIDACIONES -->
	<script src="../../assets/js/jasny-bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
                
    </script>
         <!-- CUSTOM SCRIPTS -->
    <script src="../../assets/js/custom.js"></script>
    <script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	$('.form_date').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	$('.form_time').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
    
        

    function obtenerMedicos(){
        
        var medico = document.getElementById("medico").value;
        
        //alert('obteniendo m '+medico);
        if (medico == '')
        {
            var horario = document.getElementById("horario").value;        
            var fecha   = document.getElementById("fechai").value;
            var idConsultorio = <?php echo $_SESSION['idConsultorio']; ?>;        
            var parametros = {"action":"medicos","idConsultorio":idConsultorio, "fecha": fecha, "horario": horario};
            $.ajax({
                url:'ajax_horario.php',
                method: 'POST',
                data: parametros,
                success:function(data){

                var options = '<option></option>';
                $('#medico').html(options);
                var json = data,
                    obj = JSON.parse(json);

                for (var x = 0; x < obj.length; x++) {

                    options += '<option value="' + obj[x]['id'] + '">' + obj[x]['reg'] + '</option>';
                }                

                $('#medico').html(options);

                }
            })
            document.getElementById("horario").disabled = true;
        }
        else
        {
            document.getElementById("medico").disabled = true;
        }
    }


    function obtenerHorarios(){
    
        var $contenidoAjax = $('div#contenidoAjax').html('<p><img id="cargador" src="../../img/ajax-loader.gif" /></p>');
        
		var fecha =  document.getElementById("fechai").value;
        var idConsultorio = <?php echo $_SESSION['idConsultorio']; ?>;
        var medico = document.getElementById("medico").value;
		var parametros = {"action":"horario","idConsultorio":idConsultorio, "fecha": fecha, "medico":medico};
		$.ajax({
			url:'ajax_horario.php',
            method: 'POST',
			data: parametros,
			success:function(data){
                
            var options = '<option></option>';
            $('#horario').html(options);
            var json = data, obj = JSON.parse(json);
            
            for (var x = 0; x < obj.length; x++) {
            
                options += '<option value="' + obj[x]['id'] + '">' + obj[x]['reg'] + '</option>';
            }                
            
            $('#horario').html(options);
            document.getElementById("horario").disabled = false;
            $contenidoAjax.html('');
			}
		})
	}  
    
    
    function crear(){
        var $contenidoAjax = $('div#contenidoAjax').html('<p><img id="cargador" src="../../img/ajax-loader.gif" /></p>');
        
        var paciente = document.getElementById("id_paciente").value;
        var horario = document.getElementById("horario").value;        
        var fecha = document.getElementById("fechai").value;
        var medico = document.getElementById("medico").value;
        var observacion = document.getElementById("observaciones").value;		
        var idMotivo = document.getElementById("motivo").value;	
        var idConsultorio = <?php echo $_SESSION['idConsultorio']; ?>;
        
        if(horario == '' || paciente == '')
        {
            $('#error').show();
            return false;
        }
        
		var parametros = {"action":"crear","idConsultorio":idConsultorio, "fecha": fecha, "horario": horario, "paciente":paciente, "medico":medico, "observacion":observacion, "idMotivo":idMotivo};
		$.ajax({
			url:'ajax_horario.php',
            method: 'POST',
			data: parametros,
			success:function(data){
                var json = data, obj = JSON.parse(json);
                var status = obj['status'];
                $contenidoAjax.html(data);
                $('#myModal').modal('hide');
                if (status == 'OK')
                {
                    alert('Transaccion Exitosa');
                }
                else
                {
                    alert(obj['message']);
                }
                location.reload();
			}
		})
	}
    
    function validaCombo()
    {
        var horario = document.getElementById("horario").value;        
        var fecha = document.getElementById("fechai").value;
        if(fecha && horario == '' )
        {
            alert('Debe seleccionar el horario.');
        }
        
	}
    
    function borrar()
    {
        document.getElementById("id_paciente").value = '';
        document.getElementById("horario").value = '';        
        document.getElementById("fechai").value = '';
        document.getElementById("medico").value = '';
        document.getElementById("observaciones").value = '';		
        document.getElementById("motivo").value  = '';
        
        document.getElementById("horario").disabled = false;
        document.getElementById("medico").disabled = false;
        
    }
    
    
    function eliminarRegistro(id)
    {

        var parametros = {"action":"delete","idRegistro":id};
        $.ajax({
            url:'ajax_horario.php',
            method: 'POST',
            data: parametros,
            success:function(data){
                alert('Transaccion Exitosa');
                location.reload();
            }
        })
        
    }    
  
  
</script>   
</body>
    <script src="../../assets/js/bootstrap-select.min.js"></script> 
</html>
