<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/TipoPQR.php";
require_once "../parametros/clase/TipoReporte.php";
require_once "../parametros/clase/MedioRecepcionPQR.php";
require_once "../parametros/clase/TipoIdentificacion.php";
require_once "../parametros/clase/EstadoPQR.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoPQR = new TipoPQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoRep = new TipoReporte($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMedioRcp = new MedioRecepcionPQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoId = new TipoIdentificacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjEstPQR = new EstadoPQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$municipiopqr      = $ObjMun->listarMunicipioContrato();
$tipoPQR        = $ObjTipoPQR->listarTipoPQR();
$tipoReporte    = $ObjTipoRep->listarTipoReporte();
$medioRecepcion = $ObjMedioRcp->listarMedioRecepcionPQR();
$tipoIdentificacion = $ObjTipoId->listarTipoIdentificacion();
$estadoPQR          = $ObjEstPQR->listarEstadoPQR();
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
<script type="text/javascript" src="pqr/js/pqr.js"></script>
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
    <strong>Registrar PQR</strong>
    </li>
</ol>
</hr>
<!--
<div class="row">
    <div class="form-group">
    <?php if($CREAR=="S"){ ?>
        <div class="col-md-2">
            <button type="button" id="btn_nueva_pqr" style class="btn btn-green btn-icon icon-left form-control">Nuevo<i class="entypo-plus"></i></button>
        </div>
    <?php } ?>
    <?php if($EDITAR=="S"){ ?>
        <div class="col-md-2">
            <button type="button" id="btn_editar_pqr" class="btn btn-orange btn-icon icon-left form-control">Editar<i class="entypo-pencil"></i></button>
        </div>
    <?php } ?>
    <?php if($ELIMINAR=="S"){ ?>
        <div class="col-md-2">
            <button type="button" id="btn_eliminar_pqr" class="btn btn-red btn-icon icon-left form-control">Eliminar<i class="entypo-trash"></i></button>
        </div>
    <?php } ?>
        <div class="col-md-2">
            <button type="button" id="btn_detalle_pqr" class="btn btn-blue btn-icon icon-left form-control">Detalle<i class="entypo-info"></i></button>
        <div>
        <div class="col-md-4">&nbsp;</div>
    </div>
</div>
-->

<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nueva_pqr" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_pqr" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_pqr" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
        <button type="button" id="btn_detalle_pqr" class="btn btn-blue btn-icon icon-left">Detalle<i class="entypo-info"></i></button>
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
                            while(!$municipiopqr->EOF){
                                echo "<option value=\"".$municipiopqr->fields['id_municipio']."\">".strtoupper($municipiopqr->fields['descripcion'])."</option>";
                                $municipiopqr->MoveNext();
                            }
                            ?>
                            </select>              
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <label for="tipo_pqr" class="control-label">Tipo PQR</label> 
                            <select id="tipo_pqr" name="tipo_pqr" title="Tipo PQR" class="form-control" data-allow-clear="true" data-placeholder="TIPO PQR">
                            <option value="">-Todos-</option>
                            <?php
                            while(!$tipoPQR->EOF){
                                echo "<option value=\"".$tipoPQR->fields['id_tipo_pqr']."\">".strtoupper($tipoPQR->fields['descripcion'])."</option>";
                                $tipoPQR->MoveNext();
                            }
                            ?>
                            </select>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <label for="tipo_reporte" class="control-label">Tipo Reporte</label> 
                            <select id="tipo_reporte" name="tipo_reporte" title="Tipo Reporte" class="form-control" data-allow-clear="true" data-placeholder="TIPO REPORTE">
                            <option value="">-Todos-</option>
                            <?php
                            while(!$tipoReporte->EOF){
                                echo "<option value=\"".$tipoReporte->fields['id_tipo_reporte']."\">".strtoupper($tipoReporte->fields['descripcion'])."</option>";
                                $tipoReporte->MoveNext();
                            }
                            ?>
                            </select>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <label for="estado_pqr" class="control-label">Estado PQR</label> 
                            <select id="estado_pqr" name="estado_pqr" title="Estado PQR" class="form-control" data-allow-clear="true" data-placeholder="ESTADO PQR">
                            <option value="">-Todos-</option>
                            <?php
                            while(!$estadoPQR->EOF){
                                echo "<option value=\"".$estadoPQR->fields['id_estado_pqr']."\">".strtoupper($estadoPQR->fields['descripcion'])."</option>";
                                $estadoPQR->MoveNext();
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">            
                    
                    <div class="form-group">
                        <div class="col-xs-12 col-md-4">
                            <label for="radicado" class="control-label">Radicado</label> 
                            <input type="text" id="radicado" name="radicado"  class="form-control" placeholder="RADICADO"/> 
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <label for="identificacion" class="control-label">Identificacion</label> 
                            <input type="text" id="identificacion" name="identificacion"  class="form-control" placeholder="IDENTIFICACION"/> 
                        </div>                    

                        <div class="col-xs-12 col-md-4">
                            <label for="nombre" class="control-label">Nombre / Razon Social (Reporta)</label> 
                            <input type="text" id="nombre" name="nombre"  class="form-control" placeholder="NOMBRE, RAZON SOCIAL"/> 
                        </div>                    
                    </div> 
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                            <label for="direccion" class="control-label">Direcci&oacute;n del Reporte</label> 
                            <input type="text" id="direccion" name="direccion"  class="form-control" placeholder="DIRECCION"/> 
                    </div> 
                    <div class="form-group">                                    
                        <div class="col-xs-12 col-md-2">
                            <label for="fch_pqr_ini" class="control-label">Fecha Inicial</label> 
                            <div class="input-group">
                                <input type="text" id="fch_pqr_ini" name="fch_pqr_ini" title="Fecha PQR Inicial"  class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="fch_pqr_fin" class="control-label">Fecha Final</label> 
                            <div class="input-group">
                                <input type="text" id="fch_pqr_fin" name="fch_pqr_fin" title="Fecha PQR Final" class="form-control datepicker"  placeholder="YYYY-MM-DD"/>
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-md-4">
                        </br>   
                            <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_buscar_pqr" name="btn_buscar_pqr">
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
    <table id="tbl_lista_pqr" class="table table-bordered datatable table-responsive">
        <thead>
            <tr> 
                <th style="text-align: center">#</th>
                <th style="text-align: center">RADICADO</th>
                <th style="text-align: center">MUNICIPIO</th>
                <th style="text-align: center">TIPO</th>
                <th style="text-align: center">REPORTE</th>
                <th style="text-align: center">CLIENTE</th>
                <th style="text-align: center">FECHA</th>
                <th style="text-align: center">DIR.REPORTE</th>
                <th style="text-align: center">BARRIO</th>
                <th style="text-align: center">REGISTRA</th> 
                <th style="text-align: center">ESTADO</th> 
                <th style="text-align: center">id_luminaria</th>
                <th style="text-align: center">poste_no</th>
                <th style="text-align: center">luminaria_no</th>
                <th style="text-align: center">id_municipio</th>
                <th style="text-align: center">id_tipo_pqr</th>
                <th style="text-align: center">id_tipo_reporte</th>
                <th style="text-align: center">id_medio_recepcion_pqr</th>
                <th style="text-align: center">id_estado_pqr</th>
                <th style="text-align: center">id_usuario_servicio</th>
                <th style="text-align: center">id_tipo_identificacion</th>
                <th style="text-align: center">tipo_identificacion</th>
                <th style="text-align: center">identificacion</th>
                <th style="text-align: center">direccion</th>
                <th style="text-align: center">telefono</th>
                <th style="text-align: center">email</th>
                <th style="text-align: center">permitir_edicion</th>
                <th style="text-align: center">permitir_eliminar</th>     
                <th style="text-align: center">id_barrio_reporte</th>  
                <th style="text-align: center">medio</th>        
            </tr>
        </thead>
    </table>
</div>

<!--Formulario de Entrada-->
<div class="modal fade" id="frm-pqr" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-pqr">Titulo</h4>
            </div>
            
            <div class="modal-body">
                <form id="form-pqr" name="form-pqr" enctype="multipart/form-data">
                    <input type="hidden" id="id_pqr" name="id_pqr" class="form-control clear" value="" />
                    <input type="hidden" id="id_luminaria" name="id_luminaria" class="form-control clear" value="" />
                    <input type="hidden" id="id_usuario_servicio" name="id_usuario_servicio" class="form-control requerido clear" value=""/>	
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                                <!-- panel head -->
                                <div class="panel-heading">
                                    <div class="panel-title"><i class="entypo-info"></i>&nbsp;Datos Generales de la PQR</div>
                                    <div class="panel-options">
                                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    </div>
                                </div>
                                <div class="panel-body panel-encabezado">

                                    <div class="row">
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_municipio" class="control-label">Municipio*</label>                    
                                                <select id="slt_municipio" name="slt_municipio" class="form-control requerido clear" placeholder="Municipio" title="Municipio">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    $municipiopqr->moveFirst();
                                                    while(!$municipiopqr->EOF){
                                                        echo "<option value=\"".$municipiopqr->fields['id_municipio']."\">".strtoupper($municipiopqr->fields['descripcion'])."</option>";
                                                        $municipiopqr->MoveNext();
                                                    }
                                                    ?>
                                                </select>
                                            </div>	                            
                                        </div>
                                        
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_tipo_pqr" class="control-label">Tipo PQR*</label>
                                                <select id="slt_tipo_pqr" name="slt_tipo_pqr" class="form-control requerido clear" placeholder="Tipo PQR" title="Tipo PQR">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    $tipoPQR->moveFirst();
                                                    while(!$tipoPQR->EOF){
                                                        echo "<option value=\"".$tipoPQR->fields['id_tipo_pqr']."\">".strtoupper($tipoPQR->fields['descripcion'])."</option>";
                                                        $tipoPQR->MoveNext();
                                                    }
                                                    ?>
                                                </select>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_tipo_reporte" class="control-label">Tipo Reporte*</label>
                                                <select id="slt_tipo_reporte" name="slt_tipo_reporte" class="form-control requerido clear" placeholder="Tipo Reporte" title="Tipo Reporte">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    $tipoReporte->moveFirst();
                                                    while(!$tipoReporte->EOF){
                                                        echo "<option value=\"".$tipoReporte->fields['id_tipo_reporte']."\">".strtoupper($tipoReporte->fields['descripcion'])."</option>";
                                                        $tipoReporte->MoveNext();
                                                    }
                                                    ?>
                                                </select>
                                            </div>                        
                                        </div>
                                        <div class="col-md-6">                        
                                            <div class="form-group">
                                                <label for="slt_medio_recepcion" class="control-label">Medio Recepci&oacute;n*</label>
                                                <select id="slt_medio_recepcion" name="slt_medio_recepcion" class="form-control requerido clear" placeholder="Medio Recepcion PQR" title="Medio Recepcion PQR">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    while(!$medioRecepcion->EOF){
                                                        echo "<option value=\"".$medioRecepcion->fields['id_medio_recepcion_pqr']."\">".strtoupper($medioRecepcion->fields['descripcion'])."</option>";
                                                        $medioRecepcion->MoveNext();
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="fch_pqr" class="control-label">Fecha PQR*</label> 
                                            <div class="input-group">
                                                <input type="text" id="fch_pqr" name="fch_pqr"  value="<?=date("Y-m-d")?>" class="form-control datepicker requerido" readonly  placeholder="YYYY-MM-DD" title=Fecha PQR/> 
                                                <div class="input-group-addon">
                                                    <a href="#"><i class="entypo-calendar"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_estado_pqr" class="control-label">Estado PQR*</label>
                                                <select id="slt_estado_pqr" name="slt_estado_pqr" class="form-control requerido clear" placeholder="Estado PQR" title="Estado PQR">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    $estadoPQR->moveFirst();
                                                    while(!$estadoPQR->EOF){
                                                        echo "<option value=\"".$estadoPQR->fields['id_estado_pqr']."\">".strtoupper($estadoPQR->fields['descripcion'])."</option>";
                                                        $estadoPQR->MoveNext();
                                                    }
                                                    ?>
                                                </select>
                                            </div>                        
                                        </div>
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
                                        <div class="form-group">
                                            <label class="col-sm-11 control-label" for="chk_actualizar_datos">
                                            Para realizar la creación o actualizaci&oacute;n  del cliente que reporta la PQR, es obligatorio llenar todos los campos solicitados en éste apartado
                                            ¿Desea actualizar Informaci&oacute;n del usuario?</label>						
                                            <div class="col-sm-1">
                                                <input type="checkbox" name="chk_actualizar_datos" class="icheck-11" id="chk_actualizar_datos"/>
                                            </div>                            
                                        </div>
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
                                    <div class="panel-title"><i class="entypo-lamp"></i>&nbsp;Datos del Punto Reportado</div>
                                    <div class="panel-options">
                                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    </div>
                                </div>
                                <div class="panel-body panel-encabezado">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_luminaria" class="control-label">Punto Lum&iacute;nico</label>
                                                <div class="input-group"> 
                                                    <input type="text" class="form-control clear" id="txt_luminaria" name="txt_luminaria" placeholder="Luminaria No" title="Luminaria No">
                                                    <div class="input-group-btn">	    
                                                        <button id="btn_buscar_punto_luminico" class="btn btn-gold ">
                                                            <i class="entypo-lamp"></i>
                                                        </button>
                                                        <button id="btn_cancelar_punto_luminico" class="btn btn-default ">
                                                            <i class="entypo-cancel"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_poste" class="control-label">Poste</label>
                                                <input type="text" class="form-control clear" readonly="" id="txt_poste" name="txt_poste" placeholder="Poste No" title="Poste No">
                                            </div>
                                        </div>
                                        <!--<div class="col-md-3">
                                            <div class="form-group">
                                                <label for="txt_luminaria" class="control-label">Luminaria</label>
                                                <input type="text" class="form-control clear" readonly="" id="txt_luminaria" name="txt_luminaria" placeholder="Luminaria No" title="Luminaria No">
                                            </div>
                                        </div>-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="slt_barrio_reporte" class="control-label">Barrio*</label>                    
                                                <select id="slt_barrio_reporte" name="slt_barrio_reporte" class="form-control requerido clear" placeholder="Barrio" title="Barrio del Reporte">
                                                    <option value="">-Seleccione-</option>
                                                </select>
                                            </div>	
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_direccion_reporte" class="control-label">Direcci&oacute;n*</label>
                                                <input type="text" class="form-control requerido clear" id="txt_direccion_reporte" name="txt_direccion_reporte" placeholder="Direcci&oacute;n" title="Direcci&oacute;n del Reporte">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn btn-blue btn-file">
                                    <span class="fileinput-new">Seleccione el archivo</span>
                                    <span class="fileinput-exists">Cambiar</span>
                                    <i class="entypo-attach"></i>
                                    <input type="file" name="archivo" id="archivo" class="clear">
                                </span>
                                <span class="fileinput-filename"></span>
                                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="archivo-agregado" >
                        <!--archivos Agregados-->
                            <div class="col-md-12">
                                <div class="panel panel-default panel-shadow" data-collapsed="1">
                                    <div class="panel-heading">
                                        <div class="panel-title">Archivos Cargados</div>                
                                        <div class="panel-options">
                                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                            <!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
                                        </div>
                                    </div>
                                    <div class="panel-body" id="panel-archivo">
                                                    
                                    </div>
                                </div>
                            </div>
                        <!--Fin archivos-->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group no-margin">
								<label for="txt_comentario" class="control-label">Comentarios/Asunto*</label>								
								<textarea class="form-control autogrow requerido clear" id="txt_comentario" name="txt_comentario" placeholder="Comentarios" title="Comentarios"></textarea>
							</div>	
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_frm" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_guardar_pqr">Guardar<i class="entypo-floppy"></i></button>
            </div>

        </div>
    </div>
</div>
<!--fin entrada-->

<!--Detalle-->
<div class="modal fade" id="modal-detalle-pqr" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-titulo-detalle-pqr">Detalle PQR</h4>
            </div>
            
            <div class="modal-body">
                <!--Informacion PQR-->
                <div class="row">                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">Informaci&oacute;n PQR</div>                
                                <div class="panel-options">
                                    <a href="#" data-rel="reload" id="a_cerrar_pqr" onclick="cerrarPQR();" title="Finalizar PQR"><i class="entypo-lock-open"></i></a>
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
                                </div>
                            </div>
                            <div class="panel-body" id="panel-comentario">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Municipio</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_municipio"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Tipo PQR</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_tipo_pqr"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Tipo Reporte</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_tipo_reporte"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Medio Recepci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_medio_recepcion"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Fecha PQR</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_fecha_pqr"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Estado PQR</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_estado_pqr"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Fecha Cierre</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_fecha_cierre_pqr"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Usuario Cierre</label></div>
                                        <div class="col-ms-12 col-md-3" id="td_usuario_cierre"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Poste</label></div>
                                        <div class="col-ms-12 col-md-3" style="color:#2ca02c; font-weight: bold;" id="td_poste"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Luminaria</label></div>
                                        <div class="col-ms-12 col-md-3"  style="color:#2ca02c; font-weight: bold;" id="td_luminaria"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Direcci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-3" style="color:#2ca02c; font-weight: bold;" id="td_direccion_reporte"></div>
                                        <div class="col-ms-12 col-md-3"><label class="control-label">Barrio</label></div>
                                        <div class="col-ms-12 col-md-3"  style="color:#2ca02c; font-weight: bold;" id="td_barrio_reporte"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="txt_comentario" class="control-label">Asunto</label>
                                        <div id="td_comentario" class="scrollable" data-height="100" data-scroll-position="right" data-rail-color="#ccc" data-rail-opacity=".9" data-rail-width="8" data-rail-radius="10" data-autohide="0">
                                    
                                        </div>                                    
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--Fin Info PQR-->

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

                <!--Comentarios Agregados-->
                <div class="row" id="comentario-agregado" >                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="1">
                            <div class="panel-heading">
                                <div class="panel-title">Comentarios</div>                
                                <div class="panel-options">
                                    <a href="#" data-rel="reload" onclick="listarComentario();"><i class="entypo-arrows-ccw"></i></a>
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
                                </div>
                            </div>
                            <div class="panel-body" id="panel-comentario">
                                <div class="row">
                                    <div class="col-md-12">   
                                        <div id="lista-comentario" class="scrollable" data-height="150" data-scroll-position="right" data-rail-color="#ccc" data-rail-opacity=".9" data-rail-width="8" data-rail-radius="10" data-autohide="0">
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="entypo-chat"></i></span>
                                                <input type="text" class="form-control" id="txt_agregar_comentario" name="txt_agregar_comentario" placeholder="Nuevo Comentario" title="Nuevo Comentario">	                                    
                                                <div class="input-group-btn">					
                                                    <span class="input-group-btn">
                                                        <button type="button" id="btn_agregar_comentario" class="btn btn-green">Enviar</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <!--Fin Comentario-->

                <!--archivos Agregados-->
                <div class="row">                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="1">
                            <div class="panel-heading">
                                <div class="panel-title">Archivos Cargados</div>                
                                <div class="panel-options">
                                    <a href="#" data-rel="reload" onclick="listarArchivo('detalle-panel-archivo',false);"><i class="entypo-arrows-ccw"></i></a>
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>                                    
                                    <!--<a href="#" data-rel="close" class="bg"><i class="entypo-cancel"></i></a>-->
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="detalle-panel-archivo" class="scrollable" data-height="150" data-scroll-position="right" data-rail-color="#ccc" data-rail-opacity=".9" data-rail-width="8" data-rail-radius="10" data-autohide="0">
                                        </div>
                                    </div>
                                </div>   
                                <div class="row">
                                    <form id="form-pqr-archivo" name="form-pqr-archivo" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <span class="btn btn-blue btn-file">
                                                        <span class="fileinput-new">Seleccione el archivo</span>
                                                        <span class="fileinput-exists">Cambiar</span>
                                                        <i class="entypo-attach"></i>
                                                        <input type="file" name="archivo" id="archivo" class="clear">
                                                    </span>
                                                    <span class="fileinput-filename"></span>
                                                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="form-control btn btn-green" id="btn_agregar_archivo">Cargar</i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>   
                            </div>
                        </div>
                    </div>                    
                </div>
                <!--Fin archivos-->            

                <!--Informacion Actividad-->
                <div class="row">                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="1">
                            <div class="panel-heading">
                                <div class="panel-title">Actividades Relacionadas</div>                
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body" id="panel-comentario">
                                <div class="table-responsive">
                                    <table class="table table-bordered datatable " id="tbl_actividad_pqr">
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
                <!--Fin Info Actividad-->

            <div class="modal-footer">
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_cerrar_detalle" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
            </div>
        </div>
    </div>
</div>
<!--fin detalle-->