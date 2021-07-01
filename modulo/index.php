<?php
session_start();
include("../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "parametros/clase/Menu.php";
require_once "parametros/clase/Municipio.php";
require_once "parametros/clase/TipoLuminaria.php";
$ObjMun 	= new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoLum = new TipoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$municipio 	= $ObjMun->listarMunicipioContrato();
$tipoLuminaria = $ObjTipoLum->listarTipoLuminaria();
$ObjMenu 	= new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
if(empty($_SESSION['id_tercero'])){
	?>
	<script> 
		window.location = "../index.php";
	</script>
	<?php
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Asociacion de Alumbrado Público del Atlántico" />
	<meta name="author" content="jcharris" />

	<link rel="icon" href="../libreria/neon/assets/images/asoatlantico.ico">

	<title>AP-Atl&aacute;ntico</title>

	<link rel="stylesheet" href="../libreria/neon/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/css/font-icons/entypo/css/entypo.css"/>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic"/>
	<link rel="stylesheet" href="../libreria/neon/assets/css/bootstrap.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/css/neon-core.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/css/neon-theme.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/css/neon-forms.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/css/custom.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/css/skins/blue.css"/>
	
	<!--<link rel="stylesheet" href="../libreria/neon/assets/css/datepicker.css">-->
	<!-- Imported styles on this page -->
	<!--<link rel="stylesheet" href="../libreria/neon/assets/js/datatables/datatables.css">-->
	<link rel="stylesheet" href="../libreria/neon/assets/js/datatables/DataTables-1.10.9/css/jquery.dataTables.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/js/select2/select2-bootstrap.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/js/select2/select2.css"/>
	<link rel="stylesheet" href="../libreria/neon/assets/js/icheck/skins/square/_all.css"/>
	<link rel="stylesheet" href="../libreria/leaflet/leaflet.css"/>
	<!--jQuery-->
	<script src="../libreria/neon/assets/js/jquery-1.11.3.min.js"></script>
	<!-- Bottom scripts (common) -->
	<script src="../libreria/neon/assets/js/gsap/TweenMax.min.js"></script>
	<script src="../libreria/neon/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="../libreria/neon/assets/js/bootstrap.js"></script>
	<script src="../libreria/neon/assets/js/joinable.js"></script>
	<script src="../libreria/neon/assets/js/resizeable.js"></script>
	<script src="../libreria/neon/assets/js/neon-api.js"></script>
	<script src="../libreria/neon/assets/js/fileinput.js"></script>
	<!-- Imported scripts on this page -->
	<script src="../libreria/neon/assets/js/bootstrap-switch.min.js"></script>
	<script src="../libreria/neon/assets/js/select2/select2.min.js"></script>
	<script src="../libreria/neon/assets/js/jquery.blockUI.js"></script>
	<script src="../libreria/neon/assets/js/jquery.mask.js"></script>
	<script src="../libreria/neon/assets/js/jquery.inputmask.bundle.js"></script>
	<script src="../libreria/neon/assets/js/jquery.numeric.js"></script>
	<script src="../libreria/neon/assets/js/bootstrap-datepicker.js"></script>
	<script src="../libreria/neon/assets/js/datatables/DataTables-1.10.9/js/jquery.dataTables.min.js"></script>
	<script src="../libreria/neon/assets/js/icheck/icheck.min.js"></script>
	<script src="../libreria/neon/assets/js/morris.min.js"></script>
	<script src="../libreria/neon/assets/js/raphael-min.js"></script>
	
	<!-- JavaScripts initializations and stuff -->
	<script src="../libreria/neon/assets/js/neon-custom.js"></script>
	<script src="../libreria/neon/assets/js/toastr.js"></script>
	<script src="../libreria/neon/assets/js/jquery.number.min.js"></script>
	<script src="../libreria/leaflet/leaflet.js"></script>
	<script src="../libreria/custom/custom.js"></script>
	<script src="index.js"></script>

</head>
<body class="page-body  page-fade">
	<div class="page-container ">
		<div class="sidebar-menu">

			<div class="sidebar-menu-inner">
				<header class="logo-env">
					<!-- logo -->
					<div class="logo">
						<a href="index.php">
							<img src="../libreria/img/logo.png" width="120" alt="" />
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

				<ul id="main-menu" class="main-menu">
					<?php
					$result = $ObjMenu->menuTercero($_SESSION['id_tercero']);
					$tmp_id_menu_padre = "";
					$sw = false;
					while(!$result->EOF){
						if($tmp_id_menu_padre != $result->fields['id_menu_padre']){
							if($sw){
								echo "</ul></li>";
							}
							$sw = true;
							$tmp_id_menu_padre = $result->fields['id_menu_padre'];
							?>
							<li class="has-sub">
								<a href="index.php">
									<i class="<?=$result->fields['icono_padre']?>"></i>
									<span class="title"><?=$result->fields['menu_padre']?></span>
								</a>
								<ul>	
							<?php								
						}
						?>
						<li>
							<a href="#" onclick="cargarModulo('<?=$result->fields['ruta_pagina']?>?id=<?=$result->fields['id_menu']?>');">
								<i class="<?=$result->fields['icono']?>"></i>
								<span class="title"><?=$result->fields['nombre']?></span>
							</a>
						</li>
						<?php
						$result->MoveNext();
					}
					echo "</ul></li>";
					?>
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
								<img src="<?="parametros/ajax/descargar_foto.php?id_tercero=".$_SESSION['id_tercero']."&rand=".rand()?>" alt="" class="img-circle" width="44" />
								<?=$_SESSION['nombre']." ".$_SESSION['apellido']?>
							</a>

							<ul class="dropdown-menu">

								<!-- Reverse Caret -->
								<li class="caret"></li>

								<!-- Profile sub-links -->
								<li>
									<a href="#" onclick="cargarModulo('login/cambiar_clave.php');" >
										<i class="entypo-user"></i>
										Cambiar Clave
									</a>
								</li>

								<li>
									<a href="../index.php">
										<i class="entypo-logout"></i>
										Salir
									</a>
								</li>

								<!--<li>
									<a href="extra-calendar.html">
										<i class="entypo-calendar"></i>
										Calendar
									</a>
								</li>

								<li>
									<a href="#">
										<i class="entypo-clipboard"></i>
										Tasks
									</a>
								</li>-->
							</ul>
						</li>

					</ul>
				</div>

				<div class="col-md-6 col-sm-4 clearfix hidden-xs">		
					<ul class="list-inline links-list pull-right">			
						<li class="sep"></li>					
						<li>
							<a href="#" data-toggle="chat" data-collapse-sidebar="1">
								<i class="entypo-chat"></i>
								<?=date("Y/m/d")?>			
								<span class="badge badge-success chat-notifications-badge is-hidden">0</span>
							</a>
						</li>			
						<li class="sep"></li>			
						<li>
							<a href="../index.php">
								Salir <i class="entypo-logout right"></i>
							</a>
						</li>
					</ul>		
				</div>
		
			</div>

			<div class="row" id="contenido">
			<!---	<div class="row">
					<div class="col-md-12">
						<p>GRAFICOS SOLO DEL PERIODO ACTUAL /// HACER UNA CARPETA SOLO PARA EL DASHBOARD</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="panel panel-primary" data-collapsed="0">					
							<div class="panel-heading">
								<div class="panel-title">Actividades del Periodo</div>                
								<div class="panel-options">
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								</div>
							</div>            
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">   
										<div id="chart_actividad_periodo_actual">
										
										</div>
									</div>
								</div>
							</div>					
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-primary" data-collapsed="0">					
							<div class="panel-heading">
								<div class="panel-title">Actividades del Periodo</div>                
								<div class="panel-options">
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								</div>
							</div>            
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">   
										<div id="chart_actividad_tipo_periodo">
										
										</div>
									</div>
								</div>
							</div>					
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-primary" data-collapsed="0">					
							<div class="panel-heading">
								<div class="panel-title">Actividades del Periodo</div>                
								<div class="panel-options">
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								</div>
							</div>            
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">   
										<div id="chart_actividad_municipio">
										
										</div>
									</div>
								</div>
							</div>					
						</div>
					</div>
				</div>
			-->
			</div>
		
		</div>
	</div>

<!--Mensaje Global-->
<div class="modal fade" id="modal-mensaje-global">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="modal-title-global">Atenci&oacute;n</h4>
				</div>				
				<div class="modal-body" id="modal-text-global"></div>				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-blue" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
<!--Fin-->

<!--Mensaje Confirmacion-->
<div class="modal fade" id="modal-conf">
    <div class="modal-dialog modal-dialog-centered" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title-conf">Atenci&oacute;n</h4>
            </div>
            <div class="modal-body" id="modal-body-conf">

			</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btn_si">Si</button>            
                <button type="button" class="btn btn-blue" data-dismiss="modal" id="btn_no">No</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Confirm-->

<!--Listado de Puntos Luminicos-->
<div class="modal fade custom-width" id="frm-punto-luminico" role="dialog" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-Punto Luminico">Listado de Puntos Lum&iacute;nicos</h4>
            </div>            
            <div class="modal-body">
				<!--Filtros-->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-shadow" data-collapsed="0">
							<div class="panel-heading">
								<div class="panel-title">Filtros</div>                
								<div class="panel-options">
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
									<!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
								</div>
							</div>
							<div class="panel-body"> 

								<div class="row">            
									<div class="form-group">
										<div class="col-xs-12 col-md-4">
											<label for="flt_municipio" class="control-label">Municipio</label> 
											<select id="flt_municipio" name="flt_municipio" title="Municipio" class="form-control" data-allow-clear="true" data-placeholder="MUNICIPIO">
											<option value="">-Todos-</option>
											<?php
											while(!$municipio->EOF){
												echo "<option value=\"".$municipio->fields['id_municipio']."\">".strtoupper($municipio->fields['descripcion'])."</option>";
												$municipio->MoveNext();
											}
											?>
											</select>              
										</div>

										<div class="col-xs-12 col-md-4">
											<label for="flt_barrio" class="control-label">Barrio</label> 
											<select id="flt_barrio" name="flt_barrio" title="Barrio" class="form-control" data-allow-clear="true" data-placeholder="BARRIO">
											<option value="">-Todos-</option>
											</select>
										</div>

										<div class="col-xs-12 col-md-4">
											<label for="flt_tipo" class="control-label">Tipo Luminaria</label>                     
											<select id="flt_tipo" name="flt_tipo" title="Tipo" class="form-control" data-allow-clear="true" data-placeholder="TIPO">
											<option value="">-Todos</option>
											<?php
											while(!$tipoLuminaria->EOF){
												echo "<option value=\"".$tipoLuminaria->fields['id_tipo_luminaria']."\">".strtoupper($tipoLuminaria->fields['descripcion'])."</option>";
												$tipoLuminaria->MoveNext();
											}
											?>
											</select>
										</div>
									</div>
								</div>

								<div class="row">            
									<div class="form-group">
										<div class="col-xs-12 col-md-6">
											<label for="flt_direccion" class="control-label">Direcci&oacute;n</label> 
											<input type="text" title="Direcci&oacute;n" id="flt_direccion" name="flt_direccion"  class=" form-control" placeholder="DIRECCION"/> 
										</div>

										<div class="col-xs-12 col-md-2">
											<label for="flt_poste_no" class="control-label">Poste #</label> 
											<input type="text" id="flt_poste_no" name="flt_poste_no"  class="form-control" placeholder="POSTE"/> 
										</div>

										<div class="col-xs-12 col-md-2">
											<label for="flt_luminaria_no" class="control-label">Luminaria #</label> 
											<input type="text" id="flt_luminaria_no" name="flt_luminaria_no"  class="form-control" placeholder="LUMINARIA"/> 
										</div>

										<div class="col-xs-12 col-md-2">											
											<label for="btn_buscar_luminaria" class="control-label">&nbsp;</label> 
											<button type="button" class="btn btn-blue btn-icon icon-left form-control" id="btn_buscar_luminaria" name="btn_buscar_luminaria">
												Buscar
												<i class="entypo-search"></i>
											</button>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div> 
				<!--Fin Filtros-->
				<!--Listado-->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-shadow" data-collapsed="0">
							<div class="panel-heading">
								<div class="panel-title">Listado</div>                
								<div class="panel-options">
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
									<!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
								</div>
							</div>
							<div class="panel-body"> 								
								<div class="table-responsive panel-shadow">
								<table id="tbl_punto_luminico" class="table table-bordered datatable table-responsive">
									<thead>
										<tr> 
											<th style="text-align: center">#</th>
											<th style="text-align: center">MUNICIPIO</th>
											<th style="text-align: center">TIPO</th>
											<th style="text-align: center">POSTE NO</th>
											<th style="text-align: center">LUMINARIA NO</th>
											<th style="text-align: center">BARRIO</th>
											<th style="text-align: center">DIRECCION</th>
											<th style="text-align: center">ID_LUMINARIA</th>
										</tr>
									</thead>
								</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--fin-->
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_listado_punto_luminico" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
            </div>

        </div>
    </div>
</div>
<!--Fin Listado-->

<!--Listado Usuarios Servicio-->
<div class="modal fade custom-width" id="frm-usuario-servicio" role="dialog" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-usuario-servicio">Listado Usuarios</h4>
            </div>            
            <div class="modal-body">
				<!--Filtros-->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-shadow" data-collapsed="0">
							<div class="panel-heading">
								<div class="panel-title">Filtros</div>                
								<div class="panel-options">
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
									<!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
								</div>
							</div>
							<div class="panel-body"> 

								<div class="row">            
									<div class="form-group">					

										<div class="col-xs-12 col-md-2">
											<label for="flt_identificacion" class="control-label">Identificaci&oacute;n</label> 
											<input type="text" id="flt_identificacion" name="flt_identificacion"  class="form-control" placeholder="IDENTIFICACION"/> 
										</div>

										<div class="col-xs-12 col-md-4">
											<label for="flt_nombre" class="control-label">Nombre</label> 
											<input type="text" id="flt_nombre" name="flt_nombre"  class="form-control" placeholder="NOMBRE"/> 
										</div>

										<div class="col-xs-12 col-md-4">
											<label for="flt_direccion_usuario" class="control-label">Direcci&oacute;n</label> 
											<input type="text" title="Direcci&oacute;n" id="flt_direccion_usuario" name="flt_direccion_usuario"  class=" form-control" placeholder="DIRECCION"/> 
										</div>

										<div class="col-xs-12 col-md-2">											
											<label for="btn_filtrar_usuario_servicio" class="control-label">&nbsp;</label> 
											<button type="button" class="btn btn-blue btn-icon icon-left form-control" id="btn_filtrar_usuario_servicio" name="btn_filtrar_usuario_servicio">
												Buscar
												<i class="entypo-search"></i>
											</button>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div> 
				<!--Fin Filtros-->
				<!--Listado-->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-shadow" data-collapsed="0">
							<div class="panel-heading">
								<div class="panel-title">Listado</div>                
								<div class="panel-options">
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
									<!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
								</div>
							</div>
							<div class="panel-body"> 								
								<div class="table-responsive panel-shadow">
								<table id="tbl_usuario_servicio" class="table table-bordered datatable table-responsive">
									<thead>
										<tr> 
											<th style="text-align: center">#</th>
											<th style="text-align: center">TIPO IDENTIFICACION</th>
											<th style="text-align: center">IDENTIFICACION</th>
											<th style="text-align: center">NOMBRE</th>
											<th style="text-align: center">DIRECCION</th>
											<th style="text-align: center">TELEFONO</th>
											<th style="text-align: center">ID_USUARIO_SERVICIO</th>
											<th style="text-align: center">ID_TIPO_IDENTIFICACION</th>
										</tr>
									</thead>
								</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--fin-->
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_listado_usuario_servicio" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
            </div>

        </div>
    </div>
</div>
<!--Fin Listado-->
</body>
</html>