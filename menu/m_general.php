<!-- /. NAV TOP  -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="../../img/favicon2.ico" >
        <title><?php echo $_SESSION['nombreEmpresa']; ?></title>
        

        
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
        
        <!-- METISMENU SCRIPTS -->
        <script src="../../assets/js/jquery.metisMenu.js"></script>

        <!-- VALIDACIONES -->
        <script src="../../assets/js/jasny-bootstrap.min.js"></script>

        
        <script src="../../assets/js/custom.js"></script>   
        
        <!-- CUSTOM SCRIPTS -->        

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
        <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
            Consultorio: <?php echo $nombre_Consultorio; ?> :: Fecha de Acceso : <?php echo fecha(date('Y-m-d')); ?> &nbsp; 
            <a href="../../php_cerrar.php" class="btn btn-danger square-btn-adjust">Salir</a> 
        </div>
        </nav>
            <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
                    <?php
                        
                        $subir2Nivel = '../../';
                        $subir1Nivel = '../';
                        
                        //logica para sombrear el menú activo
                        $strHtml = "class='active-menu'";
                        if($_SESSION['menu'] == 'PACIENTES')
                        {
                            $strMenuActivoPaciente = $strHtml;
                        }
                        else if($_SESSION['menu']  == 'CITAS')
                        {
                            $strMenuActivoCita = $strHtml;
                        }
                        else if($_SESSION['menu']  == 'CONSULTAS')
                        {
                            $strMenuActivoConsulta = $strHtml;
                        }
                        else if($_SESSION['menu']  == 'CAJA')
                        {
                            $strMenuActivoCaja = $strHtml;
                        }
                        else if($_SESSION['menu']  == 'ADMINISTRACION')
                        {
                            $strMenuActivoAdministracion = $strHtml;
                        }
                        else if($_SESSION['menu']  == 'PRINCIPAL')
                        {
                            //aqui cuando es el menu principal se validad porque la página esta en otro directorio
                            $strMenuActivoPrincipal = $strHtml;
                            $subir2Nivel = '';
                            $subir1Nivel = '';
                        }                        

						if (file_exists($subir2Nivel."img/usuario/".$_SESSION['cod_user'].".jpg")){
						echo '<img src="'.$subir2Nivel.'img/usuario/'.$_SESSION['cod_user'].'.jpg" class="user-image img-responsive"/>';
						}else{
						echo '<img src="'.$subir2Nivel.'img/usuario/default.png" class="user-image img-responsive"/>';
						}                        
                        
					?>
                    <!--<img src="assets/img/find_user.png" class="user-image img-responsive"/>-->
					</li>
				
					
                    <li>
                        <a <?php echo $strMenuActivoPrincipal ?>   href='<?php echo $subir2Nivel ?>principal.php'><i class="fa fa-home fa-3x"></i> Inicio</a>
                    </li>
                   <li>
                        <a <?php echo $strMenuActivoPaciente ?> href="<?php echo $subir2Nivel ?>modulos/pacientes/index.php"><i class="fa fa-user fa-3x"></i> Pacientes</a>
                    </li>
					<li>
                        <a  <?php echo $strMenuActivoCita ?> href="<?php echo $subir2Nivel ?>modulos/citas_medicas/index.php"><i class="fa fa-edit fa-3x"></i> Citas</a>
                    </li>	
                      
                    <li>
                        <a <?php echo $strMenuActivoConsulta ?>   href="<?php echo $subir2Nivel ?>modulos/consultas_medicas/index.php"><i class="fa fa-qrcode fa-3x"></i> Consultas</a>
                    </li>					
                    <li>
                        <a <?php echo $strMenuActivoCaja ?>  href="<?php echo $subir2Nivel ?>modulos/caja/index.php"><i class="fa fa-bar-chart-o fa-3x"></i> Caja </a>
                    </li>				
					
					                   
                    <li>
                        <a href="#" <?php echo $strMenuActivoAdministracion ?>  ><i class="fa fa-cog fa-3x"></i> Administracion<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo $subir2Nivel ?>modulos/empresa/index.php"> Empresa</a>
                            </li>
							<li>
                                <a href="<?php echo $subir2Nivel ?>modulos/cargos/index.php"> Cargos</a>
                            </li>
                            <li>
                                <a href="<?php echo $subir2Nivel ?>modulos/usuarios/index.php"> Usuarios</a>
                            </li>
							<li>
                                <a href="<?php echo $subir2Nivel ?>modulos/consultorios/index.php"> Consultorio</a>
                            </li>
							<li>
                                <a href="<?php echo $subir2Nivel ?>modulos/tarifas/index.php"> Tarifas</a>
                            </li>
                            <li>
                                <a href="<?php echo $subir2Nivel ?>modulos/seguros/index.php"> Seguros</a>
                            </li>
                            <!--<li>
                                <a href="#"> Localidad<span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="../departamentos/index.php"> Estado</a>
                                    </li>
                                    <li>
                                        <a href="../municipios/index.php"> Ciudad</a>
                                    </li>                                   
                               </ul>                              
                            </li>-->
                        </ul>
                      </li>                   
                </ul>
               
            </div>
            
        </nav>         
       
        <script src="../../assets/js/jquery-1.10.2.js"></script>
        <script src="../../assets/js/bootstrap.min.js"></script>
  
        <script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="../../assets/js/dataTables/dataTables.bootstrap.js"></script>

        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });            
         
        </script>
