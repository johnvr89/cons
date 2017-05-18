<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php 
    header('Content-Type: text/html; charset=UTF-8'); 
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
		mysql_query("DELETE FROM pacientes WHERE id='$id'");
		echo mensajes('Transaccion Exitosa','verde');
        //header('index.php');
	}
    
    
    function insertaDetallePaciente($nombreDetalle, $descripcion, $valor, $usu, $idPaciente)
    {

            $strObservacion = '';
            $strObservacion = consultar('valor', 'detalle_paciente', 'nombre = "'.$nombreDetalle.'" and descripcion = "'.$descripcion.'" and estado = "A" and paciente_id =' . $idPaciente);
            $idDetallePaciente = consultar('id', 'detalle_paciente', 'nombre = "'.$nombreDetalle.'" and descripcion = "'.$descripcion.'" and estado = "A" and paciente_id =' . $idPaciente);

            if($strObservacion)
            {
                if($strObservacion != $valor)
                {
                    //actualizo
                    $strSql = "update detalle_paciente set estado = 'I', user_modifica= '$usu' where id = $idDetallePaciente";
                    mysql_query($strSql);
                    if($valor)
                    {
                        //insert
                        $strSql = "Insert into detalle_paciente (id, paciente_id, nombre, descripcion, valor, estado, user_ingresa) 
                                                         values (null, $idPaciente, '$nombreDetalle', '$descripcion', '$valor' , 'A' , '$usu' ) ";
                        mysql_query($strSql);
                    }
                }
            }
            else
            {
                if($valor)
                {
                    //insert
                    $strSql = "Insert into detalle_paciente (id, paciente_id, nombre, descripcion, valor, estado, user_ingresa) 
                                                     values (null, $idPaciente, '$nombreDetalle', '$descripcion', '$valor' , 'A' , '$usu' ) ";
                    mysql_query($strSql);
                }
            }
        
    }
?>

<html>
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
    
    <style>
.checkbox {
  padding-left: 20px; }
  .checkbox label {
    display: inline-block;
    position: relative;
    padding-left: 5px; }
    .checkbox label::before {
      content: "";
      display: inline-block;
      position: absolute;
      width: 17px;
      height: 17px;
      left: 0;
      margin-left: -20px;
      border: 1px solid #cccccc;
      border-radius: 3px;
      background-color: #fff;
      -webkit-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
      -o-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
      transition: border 0.15s ease-in-out, color 0.15s ease-in-out; }
    .checkbox label::after {
      display: inline-block;
      position: absolute;
      width: 16px;
      height: 16px;
      left: 0;
      top: 0;
      margin-left: -20px;
      padding-left: 3px;
      padding-top: 1px;
      font-size: 11px;
      color: #555555; }
  .checkbox input[type="checkbox"] {
    opacity: 0; }
    .checkbox input[type="checkbox"]:focus + label::before {
      outline: thin dotted;
      outline: 5px auto -webkit-focus-ring-color;
      outline-offset: -2px; }
    .checkbox input[type="checkbox"]:checked + label::after {
      font-family: 'FontAwesome';
      content: "\f00c"; }
    .checkbox input[type="checkbox"]:disabled + label {
      opacity: 0.65; }
      .checkbox input[type="checkbox"]:disabled + label::before {
        background-color: #eeeeee;
        cursor: not-allowed; }
  .checkbox.checkbox-circle label::before {
    border-radius: 50%; }
  .checkbox.checkbox-inline {
    margin-top: 0; }

.checkbox-primary input[type="checkbox"]:checked + label::before {
  background-color: #428bca;
  border-color: #428bca; }
.checkbox-primary input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-danger input[type="checkbox"]:checked + label::before {
  background-color: #d9534f;
  border-color: #d9534f; }
.checkbox-danger input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-info input[type="checkbox"]:checked + label::before {
  background-color: #5bc0de;
  border-color: #5bc0de; }
.checkbox-info input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-warning input[type="checkbox"]:checked + label::before {
  background-color: #f0ad4e;
  border-color: #f0ad4e; }
.checkbox-warning input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-success input[type="checkbox"]:checked + label::before {
  background-color: #5cb85c;
  border-color: #5cb85c; }
.checkbox-success input[type="checkbox"]:checked + label::after {
  color: #fff; }

    </style>   
    
    
</head>
<body>
    <div id="wrapper">
           <?php 
                $_SESSION['menu'] = 'PACIENTES';
                include_once "../../menu/m_general.php"; 
           ?>
        <div id="page-wrapper" >
            <div id="page-inner">
				<div class="panel-body">                                              
<?php if(permiso($_SESSION['cod_user'],'1')==TRUE){ ?>	                               
				  <!--  Modals-->
								 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<form name="form1" method="post" action="">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													
														<h3 align="center" class="modal-title" id="myModalLabel">Nuevo Paciente</h3>
													</div>
										<div class="panel-body">
										<div class="row">											
											<div class="col-md-12">
											<br>																																																									
											<input class="form-control" title="Se necesita un nombre"  name="nombre" placeholder="Nombre Completo" autocomplete="off" required autofocus><br>
											<input class="form-control" title="Se necesita una Direccion" name="direccion" placeholder="Direcci&oacute;n"  autocomplete="off" ><br>											
											</div>
											<div class="col-md-6">
                                                <input class="form-control" name="documento" placeholder="C&eacute;dula" data-mask="9999999999" autocomplete="off" ><br>																																																																
											
                                                <input class="form-control" name="telefono" title="Se necesita un Telefono" data-mask="9999999999" placeholder="Tel&eacute;fono" autocomplete="off" required><br>
												<select class="form-control" name="sexo" autocomplete="off" required>
													<option value="" selected disabled>--SEXO--</option>
													<option value="m">Masculino</option>
													<option value="f">Femenino</option>													
												</select>                                             
											</div>
											<div class="col-md-6">
												<!--<input class="form-control" name="edad" title="Se necesita una Edad" pattern="^[0-9.!#$%&'*+/=?^_`{|}~-]*$" placeholder="Edad" autocomplete="off" required><br>
												<input placeholder="Fecha de Nacimiento" type="text" onfocus="(this.type='date')"  class="form-control" name="edad" min="1"  autocomplete="off" required><br>-->									
												<select class="form-control" name="seguro" autocomplete="off" >																					
												<option value="" selected disabled>--SEGURO--</option>
													<?php
															$p=mysql_query("SELECT * FROM seguros WHERE estado='s'");				
															while($r=mysql_fetch_array($p)){
																echo '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
															}
														?>												
												</select><br>	
                                                <input class="form-control" name="email" placeholder="Email" autocomplete="off"><br>		
												<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
													<input class="form-control" size="16" type="text" placeholder="Fecha de Nacimiento" onfocus="(this.type='')"  name="edad" id="edad" onchange="calcularEdad(this.value)" >
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
													<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                                                    
												</div>
                                                <br><div id="contenidoAjax">  </div>
												<input type="hidden" id="dtp_input2" name="edad" /><br/>    																																 

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
							PACIENTES
							<ul class="nav pull-right">
								<a href="" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal" title="Agregar Paciente" title="Agregar"><i class="fa fa-plus"> </i> <strong>Nuevo</strong></a>								                            																										                            
							</ul>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
								<?php 
									if(!empty($_POST['nombre'])){ 
										$documento=limpiar($_POST['documento']);
										$seguro=limpiar($_POST['seguro']);		
										$nombre=limpiar($_POST['nombre']);		
										$direccion=limpiar($_POST['direccion']);

										$telefono=limpiar($_POST['telefono']);
										$edad=limpiar($_POST['edad']);			
										$sexo=limpiar($_POST['sexo']);															
										$email=limpiar($_POST['email']);															
										$estado=limpiar($_POST['estado']);
                                        
										
										if(empty($_POST['id'])){
											$oPaciente=new Proceso_Paciente('',$documento,$seguro,$nombre,$direccion,$telefono,$edad,$sexo,$email,$estado,$id_consultorio);
											$strMensaje = $oPaciente->crear();
                                            if($strMensaje == 'OK')
                                            {
                                                echo mensajes('Paciente "'.$nombre.'" Creado con Exito','verde');
                                            }
                                            else
                                            {
                                                echo mensajes($strMensaje,'rojo');
                                            }
											
										}else{
											$id=limpiar($_POST['id']);
											$oPaciente=new Proceso_Paciente($id,$documento,$seguro,$nombre,$direccion,$telefono,$edad,$sexo,$email,$estado,$id_consultorio);
											$oPaciente->actualizar();
											echo mensajes('Paciente "'.$nombre.'" Actualizado con Exito','verde');
										}
									}
                                    
									if(!empty($_POST['id'])){
                                            
											$id=limpiar($_POST['id']);
											#$sangre=limpiar($_POST['sangre']);						
											$vih=limpiar($_POST['vih']);
											$peso=limpiar($_POST['peso']);					
											#$talla=limpiar($_POST['talla']);
											$alergia=limpiar($_POST['alergia']);
											$motivo=limpiar($_POST['motivo']);				
											$medicamento=limpiar($_POST['medicamento']);
											$enfermedad=limpiar($_POST['enfermedad']);
											$enfermedadf=limpiar($_POST['enfermedadf']);
											$entrada=limpiar($_POST['entrada']);

                                            insertaDetallePaciente('DIABETES', 'DIABETES', ($_POST['diabetes']) ? 'S':'N', $usu, $id);
                                            insertaDetallePaciente('TUBERCULOSIS', 'TUBERCULOSIS', ($_POST['tuberculosis']) ? 'S':'N', $usu, $id);
                                            insertaDetallePaciente('ETS', 'ETS', ($_POST['ets']) ? 'S':'N', $usu, $id);
                                            insertaDetallePaciente('HIPERTENSO', 'HIPERTENSO', ($_POST['hipertenso']) ? 'S':'N', $usu, $id);
                                            insertaDetallePaciente('ASMA', 'ASMA', ($_POST['asma']) ? 'S':'N', $usu, $id);
                                            insertaDetallePaciente('HEMORRAGIA', 'HEMORRAGIA', ($_POST['hemorragia']) ? 'S':'N', $usu, $id);
                                            
                                            insertaDetallePaciente('ODONTOGRAMA', '18', $_POST['18'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '17', $_POST['17'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '16', $_POST['16'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '15', $_POST['15'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '14', $_POST['14'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '13', $_POST['13'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '12', $_POST['12'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '11', $_POST['11'], $usu, $id);
                                            
                                            insertaDetallePaciente('ODONTOGRAMA', '48', $_POST['48'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '47', $_POST['47'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '46', $_POST['46'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '45', $_POST['45'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '44', $_POST['44'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '43', $_POST['43'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '42', $_POST['42'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '41', $_POST['41'], $usu, $id);                                            
                                            ////////
                                            insertaDetallePaciente('ODONTOGRAMA', '28', $_POST['28'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '27', $_POST['27'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '26', $_POST['26'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '25', $_POST['25'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '24', $_POST['24'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '23', $_POST['23'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '22', $_POST['22'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '21', $_POST['21'], $usu, $id);
                                            
                                            insertaDetallePaciente('ODONTOGRAMA', '38', $_POST['38'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '37', $_POST['37'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '36', $_POST['36'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '35', $_POST['35'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '34', $_POST['34'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '33', $_POST['33'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '32', $_POST['32'], $usu, $id);
                                            insertaDetallePaciente('ODONTOGRAMA', '31', $_POST['31'], $usu, $id);       
                                            
											mysql_query("UPDATE pacientes SET
																			vih='$vih',
																			peso='$peso',
																			alergia='$alergia',
																			motivo='$motivo',
																			medicamento='$medicamento',
																			enfermedad='$enfermedad',
																			enfermedadf='$enfermedadf',
																			entrada='$entrada'																			
																	WHERE id=$id
											");	
											echo mensajes('Expediente Registrado con Exito','verde');
									}
								?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    
									<thead>
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>DIRECCION</th>
                                            <th>EDAD</th>
                                            <th>TELEFONO</th>                                           
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php 
											if(!empty($_POST['buscar'])){
												$buscar=limpiar($_POST['buscar']);
												$pame=mysql_query("SELECT * FROM pacientes WHERE and consultorio='$id_consultorio' nombre LIKE '%$buscar%' ORDER BY nombre");	
											}else{
												$pame=mysql_query("SELECT * FROM pacientes WHERE consultorio='$id_consultorio' ORDER BY nombre");
											}		
											while($row=mysql_fetch_array($pame)){
                                                $url = '';
                                                $url=$row['id'];

                                            
										?>
                                        <tr class="odd gradeX">
                                            <td><i class="fa fa-user fa-2x"></i> <?php echo $row['nombre']; ?></td>
                                            <td><?php echo $row['direccion']; ?></td>
                                            <td><?php echo $edadCalculada =CalculaEdad($row['edad']); ?></td>
                                            <td><?php echo $row['telefono']; ?></td>                                           
                                            <td class="center">
											<div class="btn-group">
											  <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle"><i class="fa fa-cog"></i> <span class="caret"></span></button>
											  <ul class="dropdown-menu pull-right">
												<li><a  href="../perfil_paciente/index.php?id=<?php echo $url; ?>"><i class="fa fa-user"></i> Perfil</a></li>
												<li class="divider"></li>
												<li><a  href="sliderslist.php?id=<?php echo $url; ?>"><i class="fa fa-camera"></i> Fotos</a></li>
												<li class="divider"></li>												
												<li><a href="#" data-toggle="modal" data-target="#cuadro<?php echo $row['id']; ?>"><i class="fa fa-list"></i> Cuadro Clinico</a></li>
												<li class="divider"></li>
												<li><a  href="../historial_medico/index.php?id=<?php echo $url; ?>"><i class="fa fa-list-alt"></i> Historial</a></li>
												<li class="divider"></li>
												<li><a  href="#" data-toggle="modal" data-target="#actualizar<?php echo $row['id']; ?>"><i class="fa fa-edit"></i> Editar</a></li>
												<li class="divider"></li>
												<li><a href="#" data-toggle="modal" data-target="#eliminar<?php echo $row['id']; ?>" ><i class="fa fa-pencil"></i> Eliminar</a></li>																																				
											  </ul>
											</div>																				
											</td>
											
									    <!--  Modals-->
										 <div class="modal fade" id="actualizar<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<form name="form1" method="post" action="">
												<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															
																<h3 align="center" class="modal-title" id="myModalLabel">Actualizar</h3>
															</div>
										<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
											<br>
											<div class="input-group">
												  <span class="input-group-addon">Nombre</span>
												  <input class="form-control" title="Se necesita un nombre"  name="nombre" placeholder="Nombre Completo" value="<?php echo $row['nombre']; ?>" autocomplete="off" required><br>											
											</div><br>
											<div class="input-group">
												  <span class="input-group-addon">Direccion</span>
												  <input class="form-control" title="Se necesita un nombre"  name="direccion" placeholder="Dirección" value="<?php echo $row['direccion']; ?>" autocomplete="off" ><br>											
											</div><br>											
											</div>
											<div class="col-md-6">											

												<div class="input-group">
												  <span class="input-group-addon">C&eacute;dula:</span>
												  <input class="form-control" name="documento" value="<?php echo $row['documento']; ?>" data-mask="9999999999" autocomplete="off" ><br>											
											    </div><br>	
												<div class="input-group">
												  <span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
												 <input class="form-control" name="telefono" data-mask="9999999999" autocomplete="off" required value="<?php echo $row['telefono']; ?>"><br>
												</div><br>
												<div class="input-group">
												  <span class="input-group-addon">Sexo</span>
												  <select class="form-control" name="sexo" autocomplete="off" required>
													<option value="m" <?php if($row['sexo']=='m'){ echo 'selected'; } ?>>Masculino</option>
													<option value="f" <?php if($row['sexo']=='f'){ echo 'selected'; } ?>>Femenino</option>												
												</select>												
												</div><br>	
												<div class="input-group">
												  <span class="input-group-addon">Estado</span>
												  <select class="form-control" name="estado" autocomplete="off" required>
													<option value="s" <?php if($row['estado']=='s'){ echo 'selected'; } ?>>Activo</option>
													<option value="n" <?php if($row['estado']=='n'){ echo 'selected'; } ?>>No Activo</option>													
												</select>												
												</div>                                                
											</div>
											<div class="col-md-6">
											<div class="input-group">
												  <span class="input-group-addon">Seguro:</span>
												 <select class="form-control" name="seguro" onchange="pais(this.value);" autocomplete="off">			
                                                 <option value="" selected></option>
													<?php
											$p=mysql_query("SELECT * FROM seguros WHERE estado='s'");				
														while($r=mysql_fetch_array($p)){// echo $r['id'].'---'.$row['seguros'].'<br>';
                                                                if($r['id'] == $row['seguro']){
                                                                    echo '<option value="'.$r['id'].'" selected>'.$r['nombre'].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
                                                                }
														}
													?>										
												</select>											
												</div><br>																							
                                                <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                    <input class="form-control" size="16" type="text" value="<?php if ($edadCalculada != 'Pendiente') {echo $row['edad'];} ?>"  placeholder="Fecha de Nacimiento" onfocus="(this.type='')"  name="edad" id="edad" onchange="calcularEdad(this.value)" >
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
													<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                                                    
												</div>
                                                <br>
												
												<div class="input-group">
												  <span class="input-group-addon">@</span>
												  <input class="form-control" name="email" autocomplete="off" value="<?php echo $row['email']; ?>"><br>												
												</div><br>

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
					 <!--  Modals-->
								 <div class="modal fade" id="cuadro<?php echo $row['id']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                     
                                     <?php

                                                                                     
                                                $cbDiabetes = '';
                                                $cbTuberculosis= '';
                                                $cbEts= '';
                                                $cbHipertenso= '';
                                                $cbAsma= '';
                                                $cbHemorragia= '';

                                                $o18= '';
                                                $o17= '';
                                                $o16= '';
                                                $o15= '';
                                                $o14= '';
                                                $o13= '';
                                                $o12= '';
                                                $o11= '';

                                                $o48= '';
                                                $o47= '';
                                                $o46= '';
                                                $o45= '';
                                                $o44= '';
                                                $o43= '';
                                                $o42= '';
                                                $o41= '';

                                                $o28= '';
                                                $o27= '';
                                                $o26= '';
                                                $o25= '';
                                                $o24= '';
                                                $o23= '';
                                                $o22= '';
                                                $o21= '';

                                                $o38= '';
                                                $o37= '';
                                                $o36= '';
                                                $o35= '';
                                                $o34= '';
                                                $o33= '';
                                                $o32= '';
                                                $o31= '';                                                
                                                
                                     
                                            //obtengo los datos del odontograma
                                            $strSql = "SELECT * FROM detalle_paciente WHERE paciente_id='$url' and estado = 'A' ";
                                            $objSql = mysql_query($strSql);
                                            
                                            
                                            
                                            while($rowDetalle=mysql_fetch_array($objSql)){

                                                //verifico los checked
                                                if($rowDetalle['descripcion']== 'DIABETES' &&  $rowDetalle['valor']== 'S')
                                                {
                                                    $cbDiabetes = 'checked';
                                                }
                                                if($rowDetalle['descripcion']== 'TUBERCULOSIS' &&  $rowDetalle['valor']== 'S')
                                                {
                                                    $cbTuberculosis = 'checked';
                                                }
                                                if($rowDetalle['descripcion']== 'ETS' &&  $rowDetalle['valor']== 'S')
                                                {
                                                    $cbEts = 'checked';
                                                }
                                                if($rowDetalle['descripcion']== 'HIPERTENSO' &&  $rowDetalle['valor']== 'S')
                                                {
                                                    $cbHipertenso = 'checked';
                                                }
                                                if($rowDetalle['descripcion']== 'ASMA' &&  $rowDetalle['valor']== 'S')
                                                {
                                                    $cbAsma = 'checked';
                                                }
                                                if($rowDetalle['descripcion']== 'HEMORRAGIA' &&  $rowDetalle['valor']== 'S')
                                                {
                                                    $cbHemorragia = 'checked';
                                                }                                                
                                                
                                                //verifico los odontogramas
                                                if($rowDetalle['descripcion']== '18' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o18 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '17' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o17 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '16' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o16 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '15' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o15 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '14' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o14 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '13' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o13 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '12' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o12 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '11' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o11 = $rowDetalle['valor'];
                                                }           
                                                
                                                /////////////
                                                
                                                if($rowDetalle['descripcion']== '48' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o48 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '47' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o47 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '46' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o46 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '45' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o45 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '44' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o44 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '43' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o43 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '42' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o42 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '41' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o41 = $rowDetalle['valor'];
                                                }   
                                                
                                                /////////////
                                                                                                
                                                if($rowDetalle['descripcion']== '28' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o28 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '27' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o27 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '26' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o26 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '25' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o25 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '24' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o24 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '23' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o23 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '22' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o22 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '21' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o21 = $rowDetalle['valor'];
                                                }   
                                                
                                                /////////////
                                                
                                                                                                
                                                if($rowDetalle['descripcion']== '38' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o38 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '37' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o37 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '36' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o36 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '35' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o35 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '34' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o34 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '33' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o33 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '32' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o32 = $rowDetalle['valor'];
                                                }
                                                if($rowDetalle['descripcion']== '31' && $rowDetalle['nombre']== 'ODONTOGRAMA')
                                                {
                                                    $o31 = $rowDetalle['valor'];
                                                }                                                   
                                                
                                            }
                                            
                                            ?>
                                     
                                     
										<form name="form1" method="post" action="">
										<input type="hidden" value="<?php echo $row['id']; ?>" name="id">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>													
														<h3 align="center" class="modal-title" id="myModalLabel"><?php echo $row['nombre']; ?></h3>
                                                        <!--<div class="alert alert-info" align="center"><strong><?php echo $row['nombre']; ?></strong></div>-->
													</div>
										<?php if(permiso($_SESSION['cod_user'],'3')==TRUE){ ?>
										<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
											<div class="alert alert-info" align="center"><strong>ANTECEDENTES</strong></div>
											</div>
											<div class="col-md-6">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="diabetes<?php echo $row['id']; ?>" name="diabetes" type="checkbox" value = 'S' <?php echo $cbDiabetes; ?> >
                                                    <label for="diabetes<?php echo $row['id']; ?>">
                                                        Diabetes
                                                    </label>
                                                </div>
                                                <div class="checkbox checkbox-primary">
                                                    <input id="tuberculosis<?php echo $row['id']; ?>" name="tuberculosis" type="checkbox" value = 'S' <?php echo $cbTuberculosis; ?>>
                                                    <label for="tuberculosis<?php echo $row['id']; ?>">
                                                        Tuberculosis
                                                    </label>
                                                </div>
                                                <div class="checkbox checkbox-primary">
                                                    <input id="ets<?php echo $row['id']; ?>" name="ets" type="checkbox" value = 'S' <?php echo $cbEts; ?>>
                                                    <label for="ets<?php echo $row['id']; ?>">
                                                        E.T.S.
                                                    </label>
                                                </div>
												<span class="input-group-addon">Alergias:</span>
                                                <textarea class="form-control" name="alergia"  value="<?php echo $row['alergia']; ?>" rows="2"><?php echo $row['alergia']; ?></textarea><br>																

											</div>
											<div class="col-md-6">	
                                                
                                                <div class="checkbox checkbox-primary">
                                                    <input id="hipertenso<?php echo $row['id']; ?>" name="hipertenso" type="checkbox" value = 'S' <?php echo $cbHipertenso; ?>>
                                                    <label for="hipertenso<?php echo $row['id']; ?>">
                                                        Hipertenso
                                                    </label>
                                                </div>
                                                <div class="checkbox checkbox-primary">
                                                    <input id="asma<?php echo $row['id']; ?>" name="asma" type="checkbox" value = 'S' <?php echo $cbAsma; ?>>
                                                    <label for="asma<?php echo $row['id']; ?>">
                                                        Asma
                                                    </label>
                                                </div>
                                                <div class="checkbox checkbox-primary">
                                                    <input id="hemorragia<?php echo $row['id']; ?>" name="hemorragia" type="checkbox" value = 'S' <?php echo $cbHemorragia; ?>>
                                                    <label for="hemorragia<?php echo $row['id']; ?>">
                                                        Hemorragia
                                                    </label>
                                                </div>									
												<span class="input-group-addon">Otras Enfermedades:</span>
                                                <textarea class="form-control" name="enfermedad" value="<?php echo $row['enfermedad']; ?>"  rows="2"><?php echo $row['enfermedad']; ?></textarea><br>                                                
											</div>
                                            <div class="col-md-12">
											<div class="alert alert-info" align="center"><strong>ODONTOGRAMA</strong></div>
											</div>
                                            <div class="col-md-6">	
                                            
                                                    <button title="<?php echo $o18; ?>" type="button" class="<?php echo ($o18) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal18<?php echo $row['id']; ?>">18</button>
                                                    <button title="<?php echo $o17; ?>" type="button" class="<?php echo ($o17) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal17<?php echo $row['id']; ?>">17</button>
                                                    <button title="<?php echo $o16; ?>" type="button" class="<?php echo ($o16) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal16<?php echo $row['id']; ?>">16</button>
                                                    <button title="<?php echo $o15; ?>" type="button" class="<?php echo ($o15) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal15<?php echo $row['id']; ?>">15</button>
                                                    <button title="<?php echo $o14; ?>" type="button" class="<?php echo ($o14) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal14<?php echo $row['id']; ?>">14</button>
                                                    <button title="<?php echo $o13; ?>" type="button" class="<?php echo ($o13) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal13<?php echo $row['id']; ?>">13</button>
                                                    <button title="<?php echo $o12; ?>" type="button" class="<?php echo ($o12) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal12<?php echo $row['id']; ?>">12</button>
                                                    <button title="<?php echo $o11; ?>" type="button" class="<?php echo ($o11) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal11<?php echo $row['id']; ?>">11</button>
                                                    <br>
                                                    <button title="<?php echo $o48; ?>" type="button" class="<?php echo ($o48) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal48<?php echo $row['id']; ?>">48</button>
                                                    <button title="<?php echo $o47; ?>" type="button" class="<?php echo ($o47) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal47<?php echo $row['id']; ?>">47</button>
                                                    <button title="<?php echo $o46; ?>" type="button" class="<?php echo ($o46) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal46<?php echo $row['id']; ?>">46</button>
                                                    <button title="<?php echo $o45; ?>" type="button" class="<?php echo ($o45) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal45<?php echo $row['id']; ?>">45</button>
                                                    <button title="<?php echo $o44; ?>" type="button" class="<?php echo ($o44) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal44<?php echo $row['id']; ?>">44</button>
                                                    <button title="<?php echo $o43; ?>" type="button" class="<?php echo ($o43) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal43<?php echo $row['id']; ?>">43</button>
                                                    <button title="<?php echo $o42; ?>" type="button" class="<?php echo ($o42) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal42<?php echo $row['id']; ?>">42</button>
                                                    <button title="<?php echo $o41; ?>" type="button" class="<?php echo ($o41) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal41<?php echo $row['id']; ?>">41</button>      
                                                    
                                                    <!-- Modal -->
                                                    <div id="myModal18<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal18<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">18</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="18" id ="18" value=""  rows="4"><?php echo $o18; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal18<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal17<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal17<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">17</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="17" id ="17" value=""  rows="4"><?php echo $o17; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal17<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>    
                                                    
                                                    <div id="myModal16<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal16<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">16</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="16" id ="16" value=""  rows="4"><?php echo $o16; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal16<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal15<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal15<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">15</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="15" id ="15" value=""  rows="4"><?php echo $o15; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal15<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal14<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal14<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">14</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="14" id ="14" value=""  rows="4"><?php echo $o14; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal14<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal13<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal13<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">13</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="13" id ="13" value=""  rows="4"><?php echo $o13; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal13<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal12<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal12<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">12</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="12" id ="12" value=""  rows="4"><?php echo $o12; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal12<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal11<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal11<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">11</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="11" id ="11" value=""  rows="4"><?php echo $o11; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal11<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <!-- la otra fila -->
                                                    
                                                    <!-- Modal -->
                                                    <div id="myModal48<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal48<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">48</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="48" id ="48" value=""  rows="4"><?php echo $o48; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal48<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal47<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal47<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">47</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="47" id ="47" value=""  rows="4"><?php echo $o47; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal47<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>    
                                                    
                                                    <div id="myModal46<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal46<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">46</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="46" id ="46" value=""  rows="4"><?php echo $o46; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal46<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal45<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal45<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">45</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="45" id ="45" value=""  rows="4"><?php echo $o45; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal45<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal44<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal44<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">44</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="44" id ="44" value=""  rows="4"><?php echo $o44; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal44<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal43<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal43<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">43</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="43" id ="43" value=""  rows="4"><?php echo $o43; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal43<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal42<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal42<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">42</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="42" id ="42" value=""  rows="4"><?php echo $o42; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal42<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal41<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal41<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">41</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="41" id ="41" value=""  rows="4"><?php echo $o41; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal41<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div> 
                                                
                                            </div>
                                            <div class="col-md-6">	
                                            
                                                    <button title="<?php echo $o21; ?>" type="button" class="<?php echo ($o21) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal21<?php echo $row['id']; ?>">21</button>
                                                    <button title="<?php echo $o22; ?>" type="button" class="<?php echo ($o22) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal22<?php echo $row['id']; ?>">22</button>
                                                    <button title="<?php echo $o23; ?>" type="button" class="<?php echo ($o23) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal23<?php echo $row['id']; ?>">23</button>
                                                    <button title="<?php echo $o24; ?>" type="button" class="<?php echo ($o24) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal24<?php echo $row['id']; ?>">24</button>
                                                    <button title="<?php echo $o25; ?>" type="button" class="<?php echo ($o25) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal25<?php echo $row['id']; ?>">25</button>
                                                    <button title="<?php echo $o26; ?>" type="button" class="<?php echo ($o26) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal26<?php echo $row['id']; ?>">26</button>
                                                    <button title="<?php echo $o27; ?>" type="button" class="<?php echo ($o27) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal27<?php echo $row['id']; ?>">27</button>
                                                    <button title="<?php echo $o28; ?>" type="button" class="<?php echo ($o28) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal28<?php echo $row['id']; ?>">28</button>
                                                    <br>
                                                    <button title="<?php echo $o31; ?>" type="button" class="<?php echo ($o31) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal31<?php echo $row['id']; ?>">31</button>
                                                    <button title="<?php echo $o32; ?>" type="button" class="<?php echo ($o32) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal32<?php echo $row['id']; ?>">32</button>
                                                    <button title="<?php echo $o33; ?>" type="button" class="<?php echo ($o33) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal33<?php echo $row['id']; ?>">33</button>
                                                    <button title="<?php echo $o34; ?>" type="button" class="<?php echo ($o34) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal34<?php echo $row['id']; ?>">34</button>
                                                    <button title="<?php echo $o35; ?>" type="button" class="<?php echo ($o35) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal35<?php echo $row['id']; ?>">35</button>
                                                    <button title="<?php echo $o36; ?>" type="button" class="<?php echo ($o36) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal36<?php echo $row['id']; ?>">36</button>
                                                    <button title="<?php echo $o37; ?>" type="button" class="<?php echo ($o37) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal37<?php echo $row['id']; ?>">37</button>
                                                    <button title="<?php echo $o38; ?>" type="button" class="<?php echo ($o38) ? 'btn-primary': 'btn-default'; ?> btn-xs" data-toggle="modal" data-target="#myModal38<?php echo $row['id']; ?>">38</button>      
                                                    
                                                    <div id="myModal21<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal21<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">21</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="21" id ="21" value=""  rows="4"><?php echo $o21; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal21<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>   
                                                    
                                                    <div id="myModal22<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal22<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">22</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="22" id ="22" value=""  rows="4"><?php echo $o22; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal22<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal23<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal23<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">23</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="23" id ="23" value=""  rows="4"><?php echo $o23; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal23<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal24<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal24<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">24</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="24" id ="24" value=""  rows="4"><?php echo $o24; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal24<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal25<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal25<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">25</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="25" id ="25" value=""  rows="4"><?php echo $o25; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal25<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal26<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal26<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">26</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="26" id ="26" value=""  rows="4"><?php echo $o26; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal26<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal27<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal27<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">27</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="27" id ="27" value=""  rows="4"><?php echo $o27; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal27<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal28<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal28<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">28</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="28" id ="28" value=""  rows="4"><?php echo $o28; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal28<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <!-- otra linea -->
                                                    
                                                    <div id="myModal31<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal31<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">31</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="31" id ="31" value=""  rows="4"><?php echo $o31; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal31<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal32<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal32<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">32</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="32" id ="32" value=""  rows="4"><?php echo $o32; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal32<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal33<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal33<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">33</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="33" id ="33" value=""  rows="4"><?php echo $o33; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal33<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal34<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal34<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">34</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="34" id ="34" value=""  rows="4"><?php echo $o34; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal34<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal35<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal35<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">35</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="35" id ="35" value=""  rows="4"><?php echo $o35; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal35<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal36<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal36<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">36</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="36" id ="36" value=""  rows="4"><?php echo $o36; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal36<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal37<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal37<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">37</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="37" id ="37" value=""  rows="4"><?php echo $o37; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal37<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div id="myModal38<?php echo $row['id']; ?>" class="modal fade" role="dialog" tabindex="-1" data-focus-on="input:first" style="display: none;">
                                                      <div class="modal-dialog modal-sm">
                                                        <!-- Modal content-->
                                                        <div class="modal-content" >
                                                          <div class="modal-header">
                                                            <button type="button" class="close" onClick="closeModal('#myModal38<?php echo $row['id']; ?>')" >&times;</button>
                                                            <center><h4 class="modal-title">38</h4></center>
                                                          </div>
                                                          <div class="modal-body">
                                                                <span class="input-group-addon">Descripcion:</span>
                                                                <textarea class="form-control" name="38" id ="38" value=""  rows="4"><?php echo $o38; ?></textarea><br>
                                                          </div>
                                                          <div class="modal-footer">                                                            
                                                            <button type="button"  onClick="closeModal('#myModal38<?php echo $row['id']; ?>')" class="btn btn-primary">Ok</button>                                                                                                                        
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>                                                    
                                                
                                            </div>                                            

										</div> 
										</div> 
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
										<?php }else{ echo mensajes("NO TIENES PERMISO PARA ENTRAR A ESTE FORMULARIO","rojo"); }?>
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
																	<h4>¿Esta Seguro de Realizar esta Acción?<br><br> 
																	una vez Eliminado el paciente [ <?php echo $row['nombre']; ?> ]<br> 
																	no podran ser Recuperados sus datos.<br>
																	No recomendamos esta accion, sino la de "Activo" o No Activo, porque de este
																	depende mucha informcion en el Almacen de datos.
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

    <!-- CALENDARIO SCRIPTS -->
    <script src="../../assets/todo/bootstrap-datetimepicker.js"></script>
    <script src="../../assets/todo/locales/bootstrap-datetimepicker.es.js"></script>
	<!-- VALIDACIONES -->
	<script src="../../assets/js/jasny-bootstrap.min.js"></script>
        <script>
            
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
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
    
    function closeModal(modal)
    {
        $(""+modal+"").modal("hide");
    }
    
    
    function calcularEdad(Fecha)
    {        
        var res = Fecha.split("/");
               
        var dia = res[0];
        var mes = res[1];
        var ano = res[2];

        fecha_hoy = new Date();
        ahora_ano = fecha_hoy.getYear();
        ahora_mes = fecha_hoy.getMonth();
        ahora_dia = fecha_hoy.getDate();
        edad = (ahora_ano + 1900) - ano;

        if ( ahora_mes < (mes - 1)){
          edad--;
        }
        if (((mes - 1) == ahora_mes) && (ahora_dia < dia)){ 
          edad--;
        }
        if (edad > 1900){
            edad -= 1900;
        }
        
        if(edad > 17)
        {
            var $contenidoAjax = $('div#contenidoAjax').html('<span class="label label-info"> '+ edad + ' a&ntilde;os</span>');    
        }
        else
        {
            var $contenidoAjax = $('div#contenidoAjax').html('<span class="label label-danger"> '+ edad + ' a&ntilde;os. Menor de edad</span>');
        }
        
    }
    
</script>
       
</body>
</html>

