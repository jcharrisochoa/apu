<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/TipoIdentificacion.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoId = new TipoIdentificacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$municipio      = $ObjMun->listarMunicipioContrato();
$tipoIdentificacion = $ObjTipoId->listarTipoIdentificacion();
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
    $EDITAR = $propiedades->fields['actualizar'];
    $ELIMINAR   = $propiedades->fields['eliminar'];
    $IMPRIMIR   = $propiedades->fields['imprimir']; 
}
?>
<script src="../libreria/custom/custom.js"></script>
<script type="text/javascript" src="pqr/js/encuesta.js"></script>
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
        <a href="#">PQR</a>
    </li>
    <li class="active">
    <strong>Encuenta Satisfacci&oacute;n</strong>
    </li>
</ol>
</hr>
<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nueva_encuesta" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_encuesta" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_encuesta" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
        <button type="button" id="btn_detalle_encuesta" class="btn btn-blue btn-icon icon-left">Detalle<i class="entypo-info"></i></button>
        <?php if($IMPRIMIR=="S"){ ?>
        <button type="button" id="btn_exportar_encuesta" class="btn btn-primary btn-icon icon-left">Descargar<i class="entypo-down"></i></button>
        <?php } ?>
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
                        <div class="col-xs-12 col-md-4">
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
                        <div class="col-xs-12 col-md-4">
                            <label for="barrio" class="control-label">Barrio</label> 
                            <select id="barrio" name="barrio" title="Barrio" class="form-control" data-allow-clear="true" data-placeholder="BARRIO">
                            <option value="">-Todos-</option>
                            </select>              
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <label for="identificacion" class="control-label">Identificaci&oacute;n</label> 
                            <input type="text" id="identificacion" name="identificacion"  class="form-control" placeholder="IDENTIFICACION"/> 
                        </div>                    
                    </div>
                </div>
                <div class="row">                                
                    <div class="form-group">
                        <div class="col-xs-12 col-md-3">
                            <label for="nombre" class="control-label">Nombre / Razon Social</label> 
                            <input type="text" id="nombre" name="nombre"  class="form-control" placeholder="NOMBRE, RAZON SOCIAL"/> 
                        </div>                    
                        <div class="col-xs-12 col-md-3">
                            <label for="direccion" class="control-label">Direcci&oacute;n</label> 
                            <input type="text" id="direccion" name="direccion"  class="form-control" placeholder="DIRECCION"/> 
                        </div> 
                        <div class="col-xs-12 col-md-2">
                            <label for="fch_encuesta_ini" class="control-label">Fecha Inicial</label> 
                            <div class="input-group">
                                <input type="text" id="fch_encuesta_ini" name="fch_encuesta_ini" title="Fecha encuesta Inicial"  class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="fch_encuesta_fin" class="control-label">Fecha Final</label> 
                            <div class="input-group">
                                <input type="text" id="fch_encuesta_fin" name="fch_encuesta_fin" title="Fecha encuesta Final" class="form-control datepicker"  placeholder="YYYY-MM-DD"/>
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-md-2">
                            </br>   
                            <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_buscar_encuesta" name="btn_buscar_encuesta">
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

<div class="table-responsive panel-shadow">
<table id="tbl_encuesta" class="table table-bordered datatable table-responsive">
    <thead>
        <tr> 
            <th style="text-align: center">#</th>
            <th style="text-align: center">MUNICIPIO</th>
            <th style="text-align: center">NOMBRE</th>
            <th style="text-align: center">BARRIO</th>
            <th style="text-align: center">DIRECCION</th>
            <th style="text-align: center">CALIDAD</th>
            <th style="text-align: center">TIEMPO</th>
            <th style="text-align: center">ATENCIÓN</th> 
            <th style="text-align: center">FCH_ENCUESTA</th>     
            <th style="text-align: center">REGISTRA</th>
            <th style="text-align: center">FCH_REGISTRO</th>                                
            <th style="text-align: center">ID_MUNICIPIO</th>
            <th style="text-align: center">ID_BARRIO</th>
            <th style="text-align: center">ID_TERCERO</th>
            <th style="text-align: center">ID_ENCUESTA</th>
            <th style="text-align: center">ID_USUARIO_SERVICIO</th>
        </tr>
    </thead>
</table>
</div>

<!--Formulario de Entrada-->
<div class="modal fade" id="frm-encuesta" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-encuesta">Titulo</h4>
            </div>
            
            <div class="modal-body">
                <form id="form-encuesta" name="form-encuesta" enctype="multipart/form-data">
                    <input type="hidden" id="id_encuesta" name="id_encuesta" class="form-control clear" value="" />
                    <input type="hidden" id="id_usuario_servicio" name="id_usuario_servicio" class="form-control requerido clear" value=""/>	
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                                <!-- panel head -->
                                <div class="panel-heading">
                                    <div class="panel-title"><i class="entypo-users"></i>&nbsp;Informaci&oacute;n del Usuario / cliente</div>
                                    <div class="panel-options">
                                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    </div>
                                </div>
                                <div class="panel-body panel-encabezado">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_identificacion" class="control-label">Identificaci&oacute;n  (Sin D&iacute;gito de Verificaci&oacute;n)</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control clear" id="txt_identificacion" name="txt_identificacion" placeholder="Identifiaci&oacute;n" title="Identifiaci&oacute;n">	                                    
                                                    <div class="input-group-btn">					
                                                        <button type="button" id="btn_buscar_usuario_servicio" class="btn btn-blue"><i class="entypo-search"></i></button>
                                                        <button id="btn_cancelar_usuario_servicio" class="btn btn-default "><i class="entypo-cancel"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="slt_tipo_identificacion" class="control-label">Tipo de Identificaci&oacute;n</label>
                                                <select id="slt_tipo_identificacion" name="slt_tipo_identificacion" class="form-control clear" placeholder="Tipo Identififacion" title="Tipo Identififacion">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    while(!$tipoIdentificacion->EOF){
                                                        echo "<option value=\"".$tipoIdentificacion->fields['id_tipo_identificacion']."\">".strtoupper($tipoIdentificacion->fields['descripcion'])."</option>";
                                                        $tipoIdentificacion->MoveNext();
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_nombre" class="control-label">Nombre / Raz&oacute;n social*</label>
                                                <input type="text" class="form-control requerido clear" id="txt_nombre" name="txt_nombre" placeholder="Nombre / Raz&oacute;n social" title="Nombre / Raz&oacute;n social">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_direccion" class="control-label">Direcci&oacute;n*</label>
                                                <input type="text" class="form-control requerido clear" id="txt_direccion" name="txt_direccion" placeholder="Direcci&oacute;n" title="Direcci&oacute;n">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_municipio" class="control-label">Municipio*</label>                    
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
                                                <label for="slt_barrio" class="control-label">Barrio*</label>
                                                <select id="slt_barrio" name="slt_barrio" title="Barrio" class="form-control requerido clear" data-allow-clear="true" data-placeholder="BARRIO">
                                                <option value="">-Todos-</option>
                                                </select>
                                            </div>                        
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_telefono" class="control-label">Tel&eacute;fono*</label>
                                                <input type="text" class="form-control requerido clear" id="txt_telefono" name="txt_telefono" placeholder="Tel&eacute;fono" title="Tel&eacute;fono">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_email" class="control-label">Correo Electr&oacute;nico</label>
                                                <input type="text" class="form-control clear" id="txt_email" name="txt_email" placeholder="Correo Electr&oacute;nico" title="Correo Electr&oacute;nico">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="fch_encuesta" class="control-label">Fecha Encuesta*</label> 
                                            <div class="input-group">
                                                <input type="text" id="fch_encuesta" name="fch_encuesta"  value="<?=date("Y-m-d")?>" class="form-control datepicker requerido" readonly  placeholder="YYYY-MM-DD" title="Fecha Encuesta"/> 
                                                <div class="input-group-addon">
                                                    <a href="#"><i class="entypo-calendar"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">&nbsp;</div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                                <!-- panel head -->
                                <div class="panel-heading">
                                    <div class="panel-title"><i class="entypo-users"></i>&nbsp;Criterios</div>
                                    <div class="panel-options">
                                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    </div>
                                </div>
                                <div class="panel-body panel-encabezado">
                                    <div class="row header">
                                        <div class="col-sm-12 col-md-4 text-left"><label class="control-label">Items</label></div>
                                        <div class="col-sm-12 col-md-2 text-center"><label class="control-label">Malo</label></div>
                                        <div class="col-sm-12 col-md-2 text-center"><label class="control-label">Regular</label></div>
                                        <div class="col-sm-12 col-md-2 text-center"><label class="control-label">Bueno</label></div>
                                        <div class="col-sm-12 col-md-2 text-center"><label class="control-label">Excelente</label></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            Calidad del Servicio
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r1" id="c1" value="M" >
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r1" id="c2" value="R">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r1" id="c3" value="B">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r1" id="c4" value="E" checked>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            Tiempo de Respuesta
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r2" id="t1" value="M">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r2" id="t2" value="R">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r2" id="t3" value="B">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r2" id="t4" value="E" checked>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            Atenci&oacute;n del Personal T&eacute;cnico.
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r3" id="a1" value="M">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r3" id="a2" value="R">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r3" id="a3" value="B">
                                        </div>
                                        <div class="col-sm-12 col-md-2 text-center">
                                            <input type="radio" name="r3" id="a4" value="E"checked>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group no-margin">
								<label for="txt_comentario" class="control-label">Comentarios</label>								
								<textarea class="form-control autogrow clear" id="txt_comentario" name="txt_comentario" placeholder="Comentarios" title="Comentarios"></textarea>
							</div>	
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_frm" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_guardar_encuesta">Guardar<i class="entypo-floppy"></i></button>
            </div>

        </div>
    </div>
</div>
<!--fin entrada-->

<!--Detalle-->
<div class="modal fade" id="modal-detalle-encuesta" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-titulo-detalle-encuesta">Detalle PQR</h4>
            </div>
            
            <div class="modal-body">
            <!--Informacion Usuario-->
            <div class="row">                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">Informaci&oacute;n Cliente</div>                
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
                                </div>
                            </div>
                            <div class="panel-body" id="panel-comentario">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Tipo Identificaci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_tipo_identificacion"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Identificaci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_identificacion"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Nombre</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_nombre"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Direcci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_direccion"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Municipio</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_municipio"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Barrio</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_barrio"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Tel&eacute;fono</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_telefono"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">E-mail</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_email"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Fin Info Usuario-->

                <!--Informacion PQR-->
                <div class="row">                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">Informaci&oacute;n Encuesta</div>                
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
                                </div>
                            </div>
                            <div class="panel-body" id="panel-comentario">
                               
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Fecha Encuesta</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_fecha_encuesta" style="color:#2ca02c; font-weight: bold;"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Usuario Registra</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_usuario_registra"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Fecha Registro</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_fecha_registro"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Usuario Actualiza</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_usuario_actualiza"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Fecha Actualizaci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-3"  id="td_fch_actualiza"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">&nbsp;</label></div>
                                        <div class="col-ms-12 col-md-3"  id=""></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-5"><label class="control-label">Calidad del Servicio</label></div>
                                        <div class="col-ms-12 col-md-7 text-left" id=""><span class="badge" id="td_calidad_servicio">1</span></div>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-5"><label class="control-label">Tiempo de Respuesta</label></div>
                                        <div class="col-ms-12 col-md-7 text-left" id=""><span class="badge" id="td_tiempo_respuesta">1</span></div>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-5"><label class="control-label">Atenci&oacute;n del Personal T&eacute;cnico.</label></div>
                                        <div class="col-ms-12 col-md-7 text-left"  id=""><span class="badge" id="atencion_del_personal">1</span></div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Fin Info PQR-->

                <!--Comentarios Agregados-->
                <div class="row" id="comentario-agregado" >                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="1">
                            <div class="panel-heading">
                                <div class="panel-title">Comentarios</div>                
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body" id="panel-comentario">
                                <div class="row">
                                    <div class="col-md-12">   
                                        <div id="td_comentario" class="scrollable" data-height="150" data-scroll-position="right" data-rail-color="#ccc" data-rail-opacity=".9" data-rail-width="8" data-rail-radius="10" data-autohide="0">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Fin Comentario-->

            <div class="modal-footer">
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_cerrar_detalle" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
            </div>
        </div>
    </div>
</div>
<!--fin detalle-->