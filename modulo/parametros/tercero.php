<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/Tercero.php";
require_once "../parametros/clase/TipoIdentificacion.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTercero = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoId = new TipoIdentificacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$municipio = $ObjMun->listarMunicipio();
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
    $EDITAR     = $propiedades->fields['actualizar'];
    $ELIMINAR   = $propiedades->fields['eliminar'];
    $IMPRIMIR   = $propiedades->fields['imprimir']; 
}
?>
<script src="../libreria/custom/custom.js"></script>
<script type="text/javascript" src="parametros/js/tercero.js"></script>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Par&aacute;metros</a>
    </li>
    <li class="active">
    <strong>Registrar Empleados</strong>
    </li>
</ol>
</hr>
<input type="hidden" name="editar" id="editar" value="<?=$EDITAR?>" />
<input type="hidden" name="eliminar" id="eliminar" value="<?=$ELIMINAR?>" />

<div class="row">
    <div class="col-md-6">
        <?php if($CREAR=="S"){ ?>
        <div class="form-group">
            <button type="button" id="btn_nuevo_empleado" style class="btn btn-green btn-icon icon-left ">Nuevo<i class="entypo-plus"></i></button> 
        </div>
        <?php } ?>
    </div>   
    <div class="col-md-2">&nbsp;</div>     
</div>
<div class="row">
    <div class="col-md-6">        
        <div role="form" class="search-form-full">     
            <div class="form-group">
                <input type="text" class="form-control" name="txt_buscar_empleado" id="txt_buscar_empleado" placeholder="Buscar..." />
                <i class="entypo-search"></i>
            </div>            
        </div>        
    </div>
    <div class="col-md-6">&nbsp;</div>    
</div>

<div id="div_listado_tercero"></div>

<!--Formulario de Entrada-->
<div class="modal fade" id="frm-empleado" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-empleado">Titulo</h4>
            </div>
            
            <div class="modal-body">
                <form id="form-empleado" name="form-empleado" enctype="multipart/form-data">
                    <input type="hidden" id="id_tercero" name="id_tercero" class="form-control clear" value="" />
                    <div class="row">
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="txt_nombre" class="control-label">Nombre*</label>
                                <input type="text" class="form-control requerido clear" id="txt_nombre" name="txt_nombre" placeholder="Nombre" title="Nombre">
                            </div>
                        </div>
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="txt_apellido" class="control-label">Apellido*</label>
                                <input type="text" class="form-control requerido clear" id="txt_apellido" name="txt_apellido" placeholder="Apellido" title="Apellido">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="slt_tipo_identificacion" class="control-label">Tipo de Identificaci&oacute;n*</label>
                                <select id="slt_tipo_identificacion" name="slt_tipo_identificacion" class="form-control requerido clear" placeholder="Tipo Identififacion" title="Tipo Identififacion">
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
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="txt_identificacion" class="control-label">Identificaci&oacute;n*</label>
                                <input type="text" class="form-control text-right requerido clear" id="txt_identificacion" name="txt_identificacion" placeholder="0" title="Identificacion">
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
                                <label for="txt_direccion" class="control-label">Direcci&oacute;n*</label>
                                <input type="text" class="form-control requerido clear" id="txt_direccion" name="txt_direccion" placeholder="Direcci&oacute;n" title="Direcci&oacute;n">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="txt_telefono" class="control-label">Tel&eacute;fono</label>
                                <input type="text" class="form-control  clear" id="txt_telefono" name="txt_telefono" placeholder="Tel&eacute;fono" title="Tel&eacute;fono">
                            </div>
                        </div>
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="txt_email" class="control-label">E-mail</label>
                                <input type="text" class="form-control clear" id="txt_email" name="txt_email" placeholder="E-mail" title="E-mail">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="txt_usuario" class="control-label">Usuario</label>
                                <input type="text" class="form-control  clear" disabled id="txt_usuario" name="txt_usuario" placeholder="Usuario" title="Usuario">
                            </div>
                        </div>
                        <div class="col-md-3">                            
                            <div class="form-group">
                                <label for="txt_email" class="control-label">Clave</label>
                                <input type="password" class="form-control  clear" disabled id="txt_clave" name="txt_clave" placeholder="" title="Clave">
                            </div>
                        </div>
                        <div class="col-md-3">                            
                            <div class="form-group">
                                <label for="txt_clave_2" class="control-label">Repita Clave</label>
                                <input type="password" class="form-control clear" disabled id="txt_clave_2" name="txt_clave_2" placeholder="" title="Clave 2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <label for="slt_estado" class="control-label">Estado</label>
                                <select id="slt_estado" name="slt_estado" class="form-control requerido clear" placeholder="Estado" title="Estado">
                                    <option value="">-Seleccione-</option>
                                    <option value="A">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">&nbsp;</div>
                    </div>    
                    <div class="row">
                        <!--<div class="col-md-12">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn btn-blue btn-file">
                                    <span class="fileinput-new">Seleccione la foto</span>
                                    <span class="fileinput-exists">Cambiar</span>
                                    <i class="entypo-attach"></i>
                                    <input type="file" name="foto" id="foto" class="clear">
                                </span>
                                <span class="fileinput-filename"></span>
                                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                            </div>
                        </div>-->
                        <div class="col-md-12">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                    <img src="../libreria/neon/assets/images/agregar_foto.png" alt="..." id="img_thumbnail">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new">Seleccione la foto</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" name="foto" id="foto" class="clear" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="chk_usuario">Es usuario?</label>						
                            <div class="col-md-1">
                                <input type="checkbox" name="chk_usuario" class="icheck-11" id="chk_usuario"/>
                            </div>  
                            <div class="col-md-7"></div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="chk_empleado">Es Empleado?</label>						
                            <div class="col-md-1">
                                <input type="checkbox" name="chk_empleado" checked class="icheck-11" id="chk_empleado"/>
                            </div> 
                            <div class="col-md-7"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="chk_tecnico">Ejecuta Labor T&eacute;nica?</label>						
                            <div class="col-md-1">
                                <input type="checkbox" name="chk_tecnico" class="icheck-11" id="chk_tecnico"/>
                            </div>     
                            <div class="col-md-7"></div>                   
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_frm" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_guardar_frm">Guardar<i class="entypo-floppy"></i></button>
            </div>
        </div>
    </div>
</div>

<!--Detalle-->
<div class="modal fade" id="modal-detalle-tercero" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-titulo-detalle-tercero">Perfil Tercero</h4>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Perfil Disponible</h4>     
                        <div role="form" class="search-form-full">     
                            <div class="form-group">
                                <input type="text" class="form-control" name="txt_menu_disponible" id="txt_menu_disponible" placeholder="Buscar..." />
                                <i class="entypo-search"></i>
                            </div>            
                        </div>
                        <div class="scrollable" data-height="300" data-scroll-position="right" data-rail-color="#ccc" data-rail-opacity=".9" data-rail-width="8" data-rail-radius="10" data-autohide="0">        
                            <table class="table table-bordered" id="tbl_perfil_disponible">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">M&oacute;dulo</th>
                                        <th class="text-center" title="Crear"><i class="entypo-plus"></i></th>
                                        <th class="text-center" title="Editar"><i class="entypo-pencil"></i></th>
                                        <th class="text-center" title="Eliminar"><i class="entypo-trash"></i></th>
                                        <th class="text-center" title="Imprimir"><i class="entypo-print"></i></th>
                                        <th class="text-center" title="Acci&oacute;n"><i class="entypo-check"></i></th>
                                    </tr>
                                </thead>
                                
                                <tbody id="lista-perfil-disponible" >
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">Arlind</td>
                                        <td class="text-center alert alert-success"><input type="checkbox" /></td>
                                        <td class="text-center alert alert-warning"><input type="checkbox" /></td>
                                        <td class="text-center alert alert-danger"><input type="checkbox" /></td>
                                        <td class="text-center alert alert-info"><input type="checkbox" /></td>
                                        <td class="text-center"><input type="checkbox" /></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                        <div class="text-right"><button type="button" class="btn btn-green btn-icon icon-left" id="btn_agregar_perfil">Agregar<i class="entypo-plus"></i></button></div>
                    </div>
                    
                    <div class="col-md-6">
                        <h4>Perfil Agregado</h4>     
                        <div role="form" class="search-form-full">     
                            <div class="form-group">
                                <input type="text" class="form-control" name="txt_menu_asignado" id="txt_menu_asignado" placeholder="Buscar..." />
                                <i class="entypo-search"></i>
                            </div>            
                        </div>  
                        <div id="" class="scrollable" data-height="300" data-scroll-position="right" data-rail-color="#ccc" data-rail-opacity=".9" data-rail-width="8" data-rail-radius="10" data-autohide="0">        
                            <table class="table table-bordered" id="tbl_perfil_agregado">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">M&oacute;dulo</th>
                                        <th class="text-center" title="Crear"><i class="entypo-plus"></i></th>
                                        <th class="text-center" title="Editar"><i class="entypo-pencil"></i></th>
                                        <th class="text-center" title="Eliminar"><i class="entypo-trash"></i></th>
                                        <th class="text-center" title="Imprimir"><i class="entypo-print"></i></th>
                                        <th class="text-center" title="Acci&oacute;n"><i class="entypo-check"></i></th>
                                    </tr>
                                </thead>
                                
                                <tbody id="lista-perfil-asignado"></tbody>
                            </table>
                        </div>
                        <div class="text-right"><button type="button" class="btn btn-danger btn-icon icon-left" id="btn_retirar_perfil">Retirar<i class="entypo-trash"></i></button></div>
                    </div> 
                       
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_cerrar_detalle" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
            </div>
        </div>
    </div>
</div>
<!--fin detalle-->