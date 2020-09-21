<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/TipoLuminaria.php";
require_once "../parametros/clase/EstadoLuminaria.php";
require_once "../parametros/clase/Tercero.php";
require_once "../parametros/clase/TipoActividad.php";
require_once "../parametros/clase/Vehiculo.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoLum = new TipoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjEstLum = new EstadoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTercero = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoAct = new TipoActividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjVeh = new Vehiculo($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$municipio = $ObjMun->listarMunicipioContrato();
$tipoLuminaria = $ObjTipoLum->listarTipoLuminaria();
$estadoLuminaria = $ObjEstLum->listarEstadoLuminaria(""); 
$tercero = $ObjTercero->listarTecnico();
$vehiculo = $ObjVeh->listarVehiculo();
$tipoActividad = $ObjTipoAct->listarTipoActividad("S");
if(empty($_SESSION['id_tercero'])){
	?>
	<script> 
		window.location = "../../index.php";
	</script>
	<?php
}
else{
    $propiedades = $menu->propiedadEjecutable($_GET['id'],$_SESSION['id_tercero']);
    $CREAR      = $propiedades->fields['crear'];
    $EDITAR     = $propiedades->fields['actualizar'];
    $ELIMINAR   = $propiedades->fields['eliminar'];
    $IMPRIMIR   = $propiedades->fields['imprimir']; 
}
?>


<script src="../libreria/custom/custom.js"></script>
<script type="text/javascript" src="luminaria/js/luminaria.js"></script>
<style>
.datepicker.datepicker-dropdown {
    z-index: 100000 !important;
}
</style>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Luminaria</a>
    </li>
    <li class="active">
    <strong>Gestion Hoja de Vida de la Luminaria</strong>
    </li>
</ol>
</hr>
<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nueva_luminaria" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_luminaria" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_luminaria" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
        <button type="button" id="btn_detalle_luminaria" class="btn btn-blue btn-icon icon-left">Detalle<i class="entypo-info"></i></button>
    </div>
</div>
<br/>
    <!--Filtros-->
<div class="row">
	<div class="col-md-12">
        <div class="panel panel-default panel-shadow" data-collapsed="1">
            <div class="panel-heading">
                <div class="panel-title">Busqueda</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
                </div>
            </div>
            <div class="panel-body"> 
                <div class="row">            
                    <div class="form-group">
                        <div class="col-xs-12 col-md-3">
                            <label for="municipio" class="control-label">Municipio</label> 
                            <select id="municipio" name="municipio" title="Municipio" class="form-control" data-allow-clear="true" data-placeholder="MUNICIPIO">
                            <option value="">-Todos-</option>
                            <?php
                            while(!$municipio->EOF){
                                echo "<option value=\"".$municipio->fields['id_municipio']."\">".strtoupper($municipio->fields['descripcion'])."</option>";
                                $municipio->MoveNext();
                            }
                            ?>
                            </select>              
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <label for="barrio" class="control-label">Barrio</label> 
                            <select id="barrio" name="barrio" title="Barrio" class="form-control" data-allow-clear="true" data-placeholder="BARRIO">
                            <option value="">-Todos-</option>
                            </select>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <label for="direccion" class="control-label">Direcci&oacute;n</label> 
                            <input type="text" title="Direcci&oacute;n" id="direccion" name="direccion"  class=" form-control" placeholder="DIRECCION"/> 
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="poste_no" class="control-label">Poste</label> 
                            <input type="text" id="poste_no" name="poste_no"  class="form-control" placeholder="POSTE"/> 
                        </div>
                        <div class="col-xs-12 col-md-1"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 col-md-2">
                            <label for="luminaria_no" class="control-label">Luminaria</label> 
                            <input type="text" id="luminaria_no" name="luminaria_no"  class="form-control" placeholder="LUMINARIA"/> 
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <label for="Tipo" class="control-label">Tipo Luminaria</label>                     
                            <select id="tipo" name="tipo" title="Tipo" class="form-control" data-allow-clear="true" data-placeholder="TIPO">
                            <option value="">-Todos</option>
                            <?php
                            while(!$tipoLuminaria->EOF){
                                echo "<option value=\"".$tipoLuminaria->fields['id_tipo_luminaria']."\">".strtoupper($tipoLuminaria->fields['descripcion'])."</option>";
                                $tipoLuminaria->MoveNext();
                            }
                            ?>
                            </select>
                        </div>                    

                        <div class="col-xs-12 col-md-2">
                            <label for="fch_instalacion_ini" class="control-label">Fecha Inicial</label> 
                            <div class="input-group">
                                <input type="text" id="fch_instalacion_ini" name="fch_instalacion_ini" title="Fecha Instalacion Inicial"  class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="fch_instalacion_fin" class="control-label">Fecha Final</label> 
                            <div class="input-group">
                                <input type="text" id="fch_instalacion_fin" name="fch_instalacion_fin" title="Fecha Instalacion Final" class="form-control datepicker"  placeholder="YYYY-MM-DD"/>
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div> 
                        </div>                    

                        <div class="col-xs-12 col-md-2">
                            </br>
                            <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_buscar_luminaria" name="btn_buscar_luminaria">
                                Buscar
                                <i class="entypo-search"></i>
                            </button>
                        </div>

                        <div class="col-xs-12 col-md-1"></div>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div> 
    <!--Fin Filtros-->
<div class="table-responsive panel-shadow">
<table id="tbl_luminaria" class="table table-bordered datatable table-responsive">
    <thead>
        <tr> 
            <th style="text-align: center">#</th>
            <th style="text-align: center">MUNICIPIO</th>
            <th style="text-align: center">POSTE</th>
            <th style="text-align: center">LUMINARIA</th>
            <th style="text-align: center">TIPO</th>
            <th style="text-align: center">DIRECCION</th>
            <th style="text-align: center">LATITUD</th>
            <th style="text-align: center">LONGITUD</th> 
            <th style="text-align: center">ID_LUMINARIA</th>     
            <th style="text-align: center">FCH_INSTALACION</th>
            <th style="text-align: center">FCH_REGISTRO</th>                                
            <th style="text-align: center">ID_MUNICIPIO</th>
            <th style="text-align: center">ID_BARRIO</th>
            <th style="text-align: center">ID_TERCERO_PROVEEDOR</th>
            <th style="text-align: center">ID_ESTADO_LUMINARIA</th>
            <th style="text-align: center">ID_TIPO_LUMINARIA</th>
        </tr>
    </thead>
</table>
</div>
<!--Detalle Luminaria-->
<div class="modal fade" id="modal-detalle-luminaria"> 
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="poste_no"></h4>
            </div>
    
            <div id="modal-body-orden" class="modal-body">
                <!--Informacion General-->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Informaci&oacute;n General</div>
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body panel-encabezado">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Poste.No</label></div>
                                        <div class="col-ms-12 col-md-4" style="color:#2ca02c; font-weight: bold;" id="td_poste_no"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Latitud</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_latitud"></div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Luminaria No</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_luminaria_no"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Longitud</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_longitud"></div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Fch Instalación</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_fch_instalacion"></div>

                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-ms-12 col-md-2"><label class="control-label">Municipio</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_municipio"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Usu.Registra</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_usuario"></div>               
                                        </div>
                                    </div>
                                <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-ms-12 col-md-2"><label class="control-label">Barrio</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_barrio"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Registro</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_fch_registro"></div>               
                                        </div>
                                </div>
                                <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-ms-12 col-md-2"><label class="control-label">Direcci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_direccion"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Proveedor</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_proveedor"></div>               
                                        </div>
                                </div>
                                <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-ms-12 col-md-2"><label class="control-label">Estado</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_estado"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label"></label></div>
                                        <div class="col-ms-12 col-md-4" id=""></div>               
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Historial de estados-->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Actividad(es)</div>
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body panel-encabezado panel-collapse">
                                <div class="table-responsive">
                                    <table class="table table-bordered datatable " id="tbl_actividad_luminaria">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>C&oacute;digo</th>
                                                <th>Tipo</th>
                                                <th>Descripci&oacute;n</th>
                                                <th>Direccion</th>
                                                <th>Reclamo</th>
                                                <th>Revision</th>
                                                <th>T&eacute;cnico</th> 
                                                <th>Estado</th>                                      
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue btn-icon icon-left" data-dismiss="modal">
                Cerrar<i class="entypo-cancel"></i>
                </button>
            </div>
        </div>
    </div>
</div>   
<!--Fin Detalle-->

<!--Formulario de Entrada-->
<div class="modal fade" id="frm-luminaria" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-luminaria">Titulo</h4>
            </div>
            
            <div class="modal-body">
                <form id="form-luminaria">
                    <input type="hidden" id="id_luminaria" name="id_luminaria" class="form-control clear" value="" />
                    <div class="row">
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="slt_municipio" class="control-label">Municipio</label>                    
                                <select id="slt_municipio" name="slt_municipio" class="form-control requerido clear" placeholder="Municipio" title="Municipio">
                                    <option value="">-Seleccione-</option>
                                    <?php
                                    $municipio->moveFirst();
                                    while(!$municipio->EOF){
                                        echo "<option value=\"".$municipio->fields['id_municipio']."\">".strtoupper($municipio->fields['descripcion'])."</option>";
                                        $municipio->MoveNext();
                                    }
                                    ?>
                                </select>
                            </div>	                            
                        </div>
                        
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="slt_barrio" class="control-label">Barrio</label>
                                <select id="slt_barrio" name="slt_barrio" class="form-control requerido clear" placeholder="Barrio" title="Barrio">
                                    <option value="">-Seleccione-</option>
                                </select>
                            </div>	
                        
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-12">                            
                            <div class="form-group">
                                <label for="txt_direccion" class="control-label">Direcci&oacute;n</label>                            
                                <input type="text" class="form-control requerido clear" id="txt_direccion" name="txt_direccion" placeholder="Direcci&oacute;n" title="Direcci&oacute;n">
                            </div>	                            
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-4">                            
                            <div class="form-group">
                                <label for="field-4" class="control-label">Latitud</label>                            
                                <input type="text" class="form-control clear" id="txt_latitud" name="txt_latitud" placeholder="Latitud">
                            </div>	                            
                        </div>
                        
                        <div class="col-md-4">                        
                            <div class="form-group">
                                <label for="longitud" class="control-label">Longitud</label>                            
                                <input type="text" class="form-control clear" id="txt_longitud" name="txt_longitud" placeholder="Longitud">
                            </div>	                        
                        </div>
                        
                        <div class="col-md-4">                        
                            <div class="form-group">
                                <label for="fch_instalacion" class="control-label">Instalaci&oacute;n</label>            
                                <div class="input-group">
                                    <input type="text" id="txt_fch_instalacion" name="txt_fch_instalacion" readonly title="Fecha Instalaci&oacute;n" 
                                    class="form-control datepicker requerido clear"  placeholder="YYYY-MM-DD" data-date-format="yyyy-mm-dd"/>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="entypo-calendar"></i></a>
                                    </div>
                                </div>
                            </div>	                    
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slt_tipo_luminaria" class="control-label">Tipo Luminaria</label>
                                <select id="slt_tipo_luminaria" name="slt_tipo_luminaria" class="form-control requerido clear" placeholder="Tipo Luminaria" title="Tipo Luminaria">
                                    <option value="">-Seleccione-</option>
                                    <?php
                                    $tipoLuminaria->moveFirst();
                                    while(!$tipoLuminaria->EOF){
                                        echo "<option value=\"".$tipoLuminaria->fields['id_tipo_luminaria']."\">".strtoupper($tipoLuminaria->fields['descripcion'])."</option>";
                                        $tipoLuminaria->MoveNext();
                                    }
                                    ?>
                                </select>
                            </div>	
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="txt_poste_no" class="control-label">Poste No.</label>            
                                <input type="text" class="form-control requerido clear" id="txt_poste_no" name="txt_poste_no" placeholder="# Poste" Title="Poste No">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="txt_luminaria_no" class="control-label">Luminaria No.</label>            
                                <input type="text" class="form-control requerido clear" id="txt_luminaria_no" name="txt_luminaria_no" placeholder="# Luminaria" title="Luminaria No">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!--<div class="form-group">
                                <label for="slt_proveedor" class="control-label">Proveedor</label>
                                <select id="slt_proveedor" name="slt_proveedor" class="form-control clear" placeholder="Proveedor">
                                    <option value="">-Seleccione-</option>
                                </select>
                            </div>	-->
                            <div class="form-group">
                                <label for="slt_tercero" class="control-label">T&eacute;cnico / Instalador</label>
                                <select id="slt_tercero" name="slt_tercero" class="form-control requerido clear" placeholder="T&eacute;cnico" title="T&eacute;cnico">
                                    <option value="">-Seleccione-</option>
                                    <?php
                                    while(!$tercero->EOF){
                                        echo "<option value=\"".$tercero->fields['id_tercero']."\">".strtoupper($tercero->fields['nombre']." ".$tercero->fields['apellido'])."</option>";
                                        $tercero->MoveNext();
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slt_estado" class="control-label">Estado</label>
                                <select id="slt_estado" name="slt_estado" class="form-control requerido clear" placeholder="Estado" title="Estado">
                                    <option value="">-Seleccione-</option>
                                    <?php
                                    while(!$estadoLuminaria->EOF){
                                        echo "<option value=\"".$estadoLuminaria->fields['id_estado_luminaria']."\">".strtoupper($estadoLuminaria->fields['descripcion'])."</option>";
                                        $estadoLuminaria->MoveNext();
                                    }
                                    ?>
                                </select>
                            </div>	
                        </div>
                    </div> 
                    <div class="row" id="div_check_crear_actividad">
                        <div class="col-md-6">
                            <input tabindex="5" type="checkbox" class="icheck" id="chk_crear_actividad" name="chk_crear_actividad">
							<label for="chk_crear_actividad">¿Generar actividad de Instalaci&oacute;n?</label>
                        </div>
                        <div class="col-md-6"></div>
                    </div> 
                    <div class="row" id="div_oculto">
                        <div class="col-md-6">                           
                            <div class="form-group">
                                <label for="slt_tipo_actividad" class="control-label">Tipo Actividad</label>
                                <select id="slt_tipo_actividad" name="slt_tipo_actividad" disabled class="form-control clear" placeholder="Tipo Actividad" title="Tipo Actividad">
                                    <option value="">-Seleccione-</option>
                                    <?php
                                    while(!$tipoActividad->EOF){
                                        echo "<option value=\"".$tipoActividad->fields['id_tipo_actividad']."\">".strtoupper($tipoActividad->fields['descripcion'])."</option>";
                                        $tipoActividad->MoveNext();
                                    }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slt_vehiculo" class="control-label">Veh&iacute;culo</label>
                                <select id="slt_vehiculo" name="slt_vehiculo" disabled class="form-control clear" placeholder="Veh&iacute;culo" title="Veh&iacute;culo">
                                    <option value="">-Seleccione-</option> 
                                    <?php
                                    while(!$vehiculo->EOF){
                                        echo "<option value=\"".$vehiculo->fields['id_vehiculo']."\">".strtoupper($vehiculo->fields['descripcion'])."</option>";
                                        $vehiculo->MoveNext();
                                    }
                                    ?>                                   
                                </select>
                            </div>	
                        </div>
                    </div> 
                </form>             
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_frm" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_guardar_luminaria">Guardar<i class="entypo-floppy"></i></button>
            </div>

        </div>
    </div>
</div>
