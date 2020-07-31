<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/TipoLuminaria.php";
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoLum = new TipoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$municipio = $ObjMun->listarMunicipioContrato();
$tipoLuminaria = $ObjTipoLum->listarTipoLuminaria();
if($_SESSION['nombre']==""){
	?>
	<script> window.location = "../../index.php";</script>
	<?
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />

	<link rel="icon" href="../../libreria/neon/assets/images/favicon.ico">

	<title>AP-Atl&aacute;ntico | Luminaria</title>

    <!--<link rel="stylesheet" type="text/css" href="../../libreria/css/jquery-ui.min.css">

    <script type="text/javascript" src="../../libreria/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/jquery-ui.min.js"></script>-->

    <link rel="stylesheet" type="text/css" href="../../libreria/css/custom.css"/>            
    <link rel="stylesheet" type="text/css" href="../../libreria/css/blue.css"/>
    <link rel="stylesheet" type="text/css" href="../../libreria/css/bootstrap-switch.min.css"/>    
    <link rel="stylesheet" type="text/css" href="../../libreria/css/select2.css"/>    
    <link rel="stylesheet" type="text/css" href="../../libreria/css/select2-bootstrap.css"/>            
    <link rel="stylesheet" type="text/css" href="../../libreria/css/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../../libreria/css/responsive.bootstrap.css"/>  
    <!--<link rel="stylesheet" type="text/css" href="../../libreria/DataTables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="../../libreria/DataTables/Responsive-2.2.5/css/responsive.dataTables.min.css"/>  -->        
    <link rel="stylesheet" type="text/css" href="../../libreria/css/neon-forms.css"/>
    <link rel="stylesheet" type="text/css" href="../../libreria/css/neon-theme.css"/>         
    <link rel="stylesheet" type="text/css" href="../../libreria/css/neon-core.css"/>
    <link rel="stylesheet" type="text/css" href="../../libreria/css/bootstrap.min.css"/>            
    <link rel="stylesheet" type="text/css" href="../../libreria/font-awesome/css/font-awesome.css"/> 
    <link rel="stylesheet" type="text/css" href="../../libreria/entypo/css/entypo.css"/>             
    <link rel="stylesheet" type="text/css" href="../../libreria/css/jquery-ui-1.10.3.custom.min.css"/>
    <link rel="stylesheet" type="text/css" href="../../libreria/css/bootstrap-colorselector.css"/>
    <link rel="stylesheet" type="text/css" href="../../libreria/leaflet/leaflet.css"/>

    <script type="text/javascript" src="../../libreria/js/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/jquery.mask.js"></script>
    <script type="text/javascript" src="../../libreria/js/bootstrap-colorselector.js"></script>
    <script type="text/javascript" src="../../libreria/js/main-gsap.js"></script>
    <script type="text/javascript" src="../../libreria/js/jquery-ui-1.10.3.minimal.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/joinable.js"></script>
    <script type="text/javascript" src="../../libreria/js/resizeable.js"></script>
    <script type="text/javascript" src="../../libreria/js/neon-api.js"></script>
    <script type="text/javascript" src="../../libreria/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/TableTools.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="../../libreria/js/jquery.dataTables.columnFilter.js"></script>
    <script type="text/javascript" src="../../libreria/js/lodash.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="../../libreria/js/select2.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/bootstrap-datepicker.js"></script> 
    <script type="text/javascript" src="../../libreria/js/jquery.inputmask.bundle.min.js"></script>
    <script type="text/javascript" src="../../libreria/js/bootstrap-switch.min.js"></script>            
    <script type="text/javascript" src="../../libreria/js/neon-custom.js"></script>
    <script type="text/javascript" src="../../libreria/js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="../../libreria/js/custom.js"></script>
    <script type="text/javascript" src="../../libreria/leaflet/leaflet.js"></script>
    <script type="text/javascript" src="js/georeferencia.js"></script>
</head>
<body class="page-body  page-fade">
    <div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	    <div class="sidebar-menu">
		    <div class="sidebar-menu-inner">			
			    <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="../index.php">
                            <img src="../../libreria/neon/assets/images/logo@2x.png" width="120" alt="" />
                        </a>
                    </div>
                    
                    <!-- logo collapse icon -->
                    <div class="sidebar-collapse">
                        <a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>
                                    
                    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                    <div class="sidebar-mobile-menu visible-xs">
                        <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>
			    </header>
				<!--Menu-->					
                <ul id="main-menu" class="main-menu">
                    <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                    <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                    <li class="active opened active has-sub">
                        <a href="../index.php">
                            <i class="entypo-gauge"></i>
                            <span class="title">Operaciones</span>
                        </a>
                        <ul class="visible">
                            <li class="active">
                                <a href="../luminaria/luminaria.php">
                                    <span class="Inventario de Luminaria">Luminarias</span>
                                </a>
                            </li>
                            <li>
                                <a href="georeferencia.php">
                                    <span class="Georeferencia">Georeferencia</span>
                                </a>
                            </li>
                            <li>
                                <a href="../actividad/actividad.php">
                                    <span class="Actividades en Terreno">Actividades</span>
                                </a>
                            </li>						
                        </ul>
                    </li>
                </ul>			
		    </div>
	    </div>

        <div class="main-content">                    
            <div class="row">            
                <!-- Profile Info and Notifications -->
                <div class="col-md-6 col-sm-8 clearfix">            
                    <ul class="user-info pull-left pull-none-xsm">            
                        <!-- Profile Info -->
                        <li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="../../libreria/neon/assets/images/thumb-1@2x.png" alt="" class="img-circle" width="44" />
                                <?=$_SESSION['nombre']?>
                            </a>            
                            <ul class="dropdown-menu">            
                                <!-- Reverse Caret -->
                                <li class="caret"></li>            
                                <!-- Profile sub-links -->
                                <li>
                                    <a href="extra-timeline.html">
                                        <i class="entypo-user"></i>
                                        Cambiar Clave
                                    </a>
                                </li>            
                                <li>
                                    <a href="extra-calendar.html">
                                        <i class="entypo-calendar"></i>
                                        Salir
                                    </a>
                                </li>
                            </ul>
                        </li>            
                    </ul>            
                </div>            
            
                <!-- Raw Links -->
                <div class="col-md-6 col-sm-4 clearfix hidden-xs">		
                    <ul class="list-inline links-list pull-right">		
                        <li>
                            <a href="../../index.php">
                                Salir <i class="entypo-logout right"></i>
                            </a>
                        </li>
                    </ul>		
                </div>		
            </div>	

            <ol class="breadcrumb" >
                <li>
                    <a href="../index.php"><i class="glyphicon glyphicon-home"></i> Inicio</a>
                </li>
                <li>
                    <a href="#">georeferencia</a>
                </li>
                <li class="active">
                <strong>Mapa de Luminarias</strong>
                </li>
            </ol>
            </hr>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs 12 col-md-2">
                        <select id="municipio" name="municipio" title="Municipio" class="form-control" data-allow-clear="true" data-placeholder="MUNICIPIO">
                        <option value="">-Seleccione-</option>
                        <?php
                        while(!$municipio->EOF){
                            echo "<option value=\"".$municipio->fields['id_municipio']."\">".strtoupper($municipio->fields['descripcion'])."</option>";
                            $municipio->MoveNext();
                        }
                        ?>
                        </select>              
                    </div>

                    <div class="col-xs 12 col-md-2">
                        <select id="barrio" name="barrio" title="Barrio" class="form-control" data-allow-clear="true" data-placeholder="BARRIO">
                        <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xs 12 col-md-2">                    
                        <select id="tipo" name="tipo" title="Tipo" class="form-control" data-allow-clear="true" data-placeholder="TIPO">
                        <option value=""></option>
                        <?php
                        while(!$tipoLuminaria->EOF){
                            echo "<option value=\"".$tipoLuminaria->fields['id_tipo_luminaria']."\">".strtoupper($tipoLuminaria->fields['descripcion'])."</option>";
                            $tipoLuminaria->MoveNext();
                        }
                        ?>
                        </select>
                    </div>

                    <div class="col-xs 12 col-md-2">
                        <input type="text" id="direccion" name="direccion"  class=" form-control" placeholder="DIRECCION"/> 
                    </div>

                    <div class="col-xs 12 col-md-2">
                        <input type="text" id="poste_luminaria" name="poste_luminaria"  class="form-control" placeholder="POSTE/LUMINARIA"/> 
                    </div>

                    <div class="col-xs 12 col-md-1">
                        <button type="button" class="btn btn-blue btn-icon icon-left btn-for" id="btn_buscar_luminaria">
                        <i class="glyphicon glyphicon-search"></i>BUSCAR</button> 
                    </div>
                    <div class="col-xs 12 col-md-1"></div>

                </div>
            </div>
            </hr>
            <div class="row">
                <div class="col-md-12"></div>
            </div>
            <div class="row" id="mapa">
            </div>
    
        </div>
    </div>
</body>
</html>