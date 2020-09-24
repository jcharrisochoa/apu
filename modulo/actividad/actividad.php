<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/TipoActividad.php";
require_once "../parametros/clase/TipoLuminaria.php";
require_once "../parametros/clase/Tercero.php";
require_once "../parametros/clase/Vehiculo.php";
require_once "../parametros/clase/EstadoActividad.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoAct = new TipoActividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTercero = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoLum = new TipoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjVeh = new Vehiculo($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjEst = new EstadoActividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$vehiculo = $ObjVeh->listarVehiculo();
$municipio = $ObjMun->listarMunicipioContrato();
$tipoActividad = $ObjTipoAct->listarTipoActividad("");
$tercero = $ObjTercero->listarTecnico();
$tipoLuminaria = $ObjTipoLum->listarTipoLuminaria();
$estadoActividad = $ObjEst->listarEstadoActividad();

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
<script type="text/javascript" src="actividad/js/actividad.js"></script>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Actividad</a>
    </li>
    <li class="active">
    <strong>Registrar Actividad</strong>
    </li>
</ol>
</hr>

<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nueva_actividad" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_actividad" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_actividad" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
        <button type="button" id="btn_detalle_actividad" class="btn btn-blue btn-icon icon-left">Detalle<i class="entypo-info"></i></button>

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

                        <div class="col-xs-12 col-md-2">
                            <label for="barrio" class="control-label">Barrio</label> 
                            <select id="barrio" name="barrio" title="Barrio" class="form-control" data-allow-clear="true" data-placeholder="BARRIO">
                            <option value="">-Todos-</option>
                            </select>
                        </div>

                        <div class="col-xs-12 col-md-2">   
                            <label for="tipo_actividad" class="control-label">Tipo Actividad</label>                  
                            <select id="tipo_actividad" name="tipo_actividad" title="Tipo" class="form-control" data-allow-clear="true" data-placeholder="TIPO ACTIVIDAD">
                            <option value="">-Todos</option>
                            <?php
                            while(!$tipoActividad->EOF){
                                echo "<option value=\"".$tipoActividad->fields['id_tipo_actividad']."\">".strtoupper($tipoActividad->fields['descripcion'])."</option>";
                                $tipoActividad->MoveNext();
                            }
                            ?>
                            </select>
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="poste_no" class="control-label">Poste</label> 
                            <input type="text" id="poste_no" name="poste_no"  class="form-control" placeholder="POSTE"/> 
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="luminaria_no" class="control-label">Luminaria</label> 
                            <input type="text" id="luminaria_no" name="luminaria_no"  class="form-control" placeholder="LUMINARIA"/> 
                        </div>
                        
                        <div class="col-xs-12 col-md-1"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 col-md-2">
                            <label for="fch_actividad_ini" class="control-label">Fecha Inicial</label> 
                            <div class="input-group">
                                <input type="text" id="fch_actividad_ini" name="fch_actividad_ini" title="Fecha Actividad Inicial"  class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="fch_actividad_fin" class="control-label">Fecha Final</label> 
                            <div class="input-group">
                                <input type="text" id="fch_actividad_fin" name="fch_actividad_fin" title="Fecha Actividad Final" class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="entypo-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6"></div>
                        <div class="col-xs-12 col-md-2">
                            <br/>
                            <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_buscar_actividad" name="btn_buscar_actividad">
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
<!--fin Filtros-->

<div class="table-responsive"> 
    <table id="tbl_actividad" class="table table-bordered datatable">
        <thead>
            <tr> 
                <th style="text-align: center">#</th>
                <th style="text-align: center">MUNICIPIO</th>
                <th style="text-align: center">ACTIVIDAD No</th>
                <th style="text-align: center">TIPO</th>
                <th style="text-align: center">BARRIO</th>
                <th style="text-align: center">DIRECCION</th>
                <th style="text-align: center">FCH ACTIVIDAD</th>     
                <th style="text-align: center"></th> 
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
            </tr>
        </thead>
    </table>
</div>

<!--Detalle Actividad-->
<div class="modal fade" id="modal-detalle-actividad"> 
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="actividad"></h4>
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
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Municipio</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_municipio"></div>
                                        <div class="col-ms-12 col-md-6"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">PQR No</label></div>
                                        <div class="col-ms-12 col-md-4 text-info" id="td_pqr"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Luminaria No</label></div>
                                        <div class="col-ms-12 col-md-4 text-info" id="td_luminaria_no"></div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Fch Reporte</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_fch_reporte"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Poste.No</label></div>
                                        <div class="col-ms-12 col-md-4 text-info" id="td_poste_no"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo Reporte</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo_reporte"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo Luminaria</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo_luminaria"></div>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo PQR</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo_pqr"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Barrio</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_barrio"></div>
                                                       
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo Actividad</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Direcci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_direccion"></div>               
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">                                        
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tècnico</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_usuario"></div>  
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Fch Actividad</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_fch_instalacion"></div>             
                                    </div>
                                </div>                                
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Veh&iacute;culo</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_vehiculo"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Estado</label></div>
                                        <div class="col-ms-12 col-md-4" style="color:#2ca02c; font-weight: bold;" id="td_estado"></div>               
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Observacion</label></div>
                                        <div class="col-ms-12 col-md-10" id="td_observacion"></div>         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Servicio(s) Realizado(s)</div>
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body panel-encabezado">
                                <div class="table-responsive"> 
                                    <table id="tbl_servicio" class="table table-bordered datatable">
                                        <thead>
                                            <tr> 
                                                <th style="text-align: center">#</th>
                                                <th style="text-align: center">CODIGO</th>
                                                <th style="text-align: center">DESCRIPCION</th>
                                                <th style="text-align: center">CANTIDAD</th>
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
            <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_cerrar_detalle_actividad">
                Cerrar
                <i class="entypo-cancel"></i>
            </button>
            </div>
        </div>
    </div>
</div>
<!--Fin Detalle-->

<!--Formulario de Entrada-->
<div class="modal fade" id="frm-actividad" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-actividad">Titulo</h4>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title"><i class="entypo-briefcase"></i>&nbsp;Informaci&oacute;n General de la actividad</div>
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body panel-encabezado">
                                <form id="form-actividad">
                                    <input type="hidden" id="id_luminaria" name="id_luminaria" class="form-control clear" value="" />
                                    <input type="hidden" id="id_pqr" name="id_pqr" class="form-control clear" value="" />
                                    <input type="hidden" id="id_actividad" name="id_actividad" class="form-control clear" value="" />
                                    <div class="row">
                                        <div class="col-md-12">                            
                                            <div class="form-group">
                                                <label for="slt_municipio" class="control-label text-danger">Municipio*</label>                    
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
                                    </div>
                                    <div class="row">                
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="txt_pqr" class="control-label">PQR / Reporte No.</label>     
                                                <div class="input-group">                       
                                                    <input type="text" class="form-control clear" id="txt_pqr" name="txt_pqr" placeholder="PQR / Reporte" title="PQR/Reporte">
                                                    <div class="input-group-btn">					
                                                        <button type="button" id="btn_buscar_pqr" class="btn btn-blue btn-icon icon-left">Buscar<i class="entypo-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>	                            
                                        </div>

                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="txt_luminaria" class="control-label">Punto Lum&iacute;nico</label>     
                                                <div class="input-group">                       
                                                    <input type="text" class="form-control clear" id="txt_luminaria" name="txt_luminaria" placeholder="Punto Luminico" title="Punto Luminico">
                                                    <div class="input-group-btn">					
                                                        <button type="button" id="btn_buscar_luminaria" class="btn btn-blue btn-icon icon-left">Buscar<i class="entypo-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>	                            
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="txt_fch_pqr" class="control-label">Fecha PQR/Reporte</label>
                                                <input type="text" id="txt_fch_pqr" name="txt_fch_pqr" class="form-control clear" readonly placeholder="Fecha PQR" title="Fecha PQR">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_poste" class="control-label">Poste No</label>
                                                <input type="text" id="txt_poste" name="txt_poste" class="form-control clear" placeholder="00-0000" title="Poste No">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_tipo_reporte" class="control-label">Tipo Reporte</label>
                                                <input type="text" id="txt_tipo_reporte" name="txt_tipo_reporte" class="form-control clear" readonly placeholder="Tipo Reporte" title="Tipo Reporte">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="slt_tipo_luminaria" class="control-label text-danger">Tipo Luminaria*</label>
                                                <select id="slt_tipo_luminaria" name="slt_tipo_luminaria" class="form-control requerido clear" placeholder="Tipo Luminaria" title="Tipo Luminaria">
                                                    <option value="">-Seleccione-</option>
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_tipo_pqr" class="control-label">Tipo PQR</label>
                                                <input type="text" id="txt_tipo_pqr" name="txt_tipo_pqr" class="form-control  clear" readonly placeholder="Tipo PQR" title="Tipo PQR">
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="slt_barrio" class="control-label text-danger">Barrio*</label>
                                                <!--<input type="text" id="txt_barrio" name="txt_barrio" class="form-control requerido clear" readonly placeholder="Barrio" title="Barrio">-->
                                                <select id="slt_barrio" name="slt_barrio" class="form-control requerido clear" placeholder="Barrio" title="Barrio">
                                                    <option value="">-Seleccione-</option>
                                                </select>
                                            </div>
                                        </div>                                          
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_tipo_actividad" class="control-label text-danger">Tipo Actividad*</label>
                                                <select id="slt_tipo_actividad" name="slt_tipo_actividad" class="form-control requerido clear" placeholder="Tipo Actividad" title="Tipo Actividad">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    $tipoActividad->moveFirst();
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
                                                <label for="txt_direccion" class="control-label text-danger">Direcci&oacute;n*</label>
                                                <input type="text" id="txt_direccion" name="txt_direccion" class="form-control requerido clear" placeholder="Direcci&oacute;n" title="Direcci&oacute;n">
                                            </div>
                                        </div>                   
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_tercero" class="control-label text-danger">T&eacute;cnico / Reparador*</label>
                                                <select id="slt_tercero" name="slt_tercero" class="form-control requerido clear" placeholder="T&eacute;cnico" title="T&eacute;cnico">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    $tercero->moveFirst();
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
                                                <label for="txt_fch_ejecucion" class="control-label text-danger">Fecha Ejecuci&oacute;n*</label>
                                                <div class="input-group">
                                                    <input type="text" id="txt_fch_ejecucion" name="txt_fch_ejecucion"  value="<?=date("Y-m-d")?>" class="form-control datepicker requerido clear" readonly  placeholder="YYYY-MM-DD" title="Fecha Ejecuci&oacute;n"/> 
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="entypo-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">                                        
                                        <div class="col-md-6">                            
                                            <div class="form-group">
                                                <label for="slt_vehiculo" class="control-label">Veh&iacute;culo</label>
                                                <select id="slt_vehiculo" name="slt_vehiculo" title="Vehiculo" class="form-control clear" data-allow-clear="true" data-placeholder="Vehiculo">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="slt_estado_actividad" class="control-label text-danger">Estado Actividad*</label>
                                                <select id="slt_estado_actividad" name="slt_estado_actividad" class="form-control requerido clear" placeholder="Estado Actividad" title="Estado Actividad">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    while(!$estadoActividad->EOF){
                                                        echo "<option value=\"".$estadoActividad->fields['id_estado_actividad']."\">".strtoupper($estadoActividad->fields['descripcion'])."</option>";
                                                        $estadoActividad->MoveNext();
                                                    }
                                                    ?>
                                                </select>    
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group no-margin">
                                                <label for="txt_observacion" class="control-label text-danger">Observaci&oacute;n*</label>								
                                                <textarea class="form-control autogrow requerido clear" id="txt_observacion" name="txt_observacion" placeholder="Observaci&oacute;n" title="Observaci&oacute;n"></textarea>
                                            </div>	
                                        </div>
                                    </div>

                                </form>  
                            </div>
                        </div>
                    </div>
                </div> 
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title"><i class="entypo-tools">&nbsp;</i>Accion(es) / Serv&iacute;cio(s) Relacionado(s)</div>
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body panel-encabezado">

                                <div class="row articulo">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="txt_codigo" class="control-label">C&oacute;digo</label>
                                            <div class="input-group">                                                
                                                <input type="text" id="txt_codigo" name="txt_codigo" class="form-control clear clear-articulo" placeholder="C&oacute;digo" title="C&oacute;digo">
                                                <input type="hidden" id="item" name="item" value=""/>									
                                                <span class="input-group-btn">
                                                    <button class="btn btn-blue" type="button"><i class="entypo-search"></i></button>
                                                </span>
									        </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="txt_descripcion" class="control-label">Descripci&oacute;n</label>
                                            <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control clear clear-articulo" readonly placeholder="Descripci&oacute;n" title="Descripci&oacute;n">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="txt_cantidad" class="control-label">Cantidad</label>
                                            <input type="text" id="txt_cantidad" name="txt_cantidad" class="form-control clear clear-articulo" style="text-align: right" placeholder="0,00" title="Cantidad">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">    
                                            <!--<label for="btn_agregar" class="control-label">Acciones</label>-->
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-green"  id="btn_agregar" name="btn_agregar">
                                                    <i class="entypo-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" id="btn_eliminar" name="btn_eliminar">
                                                    <i class="entypo-minus"></i>
                                                </button>
                                                <button type="button" class="btn btn-default" id="btn_cancelar" name="btn_cancelar">
                                                    <i class="entypo-cancel"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="col-md-1">  
                                        <div class="form-group">    
                                            <label for="btn_eliminar" class="control-label">(-)</label> 
                                            <button type="button" class="form-control btn btn-danger" id="btn_eliminar" name="btn_eliminar">
                                                <i class="entypo-minus"></i>
                                            </button>
                                        </div>
                                    </div>   
                                    <div class="col-md-1">  
                                        <div class="form-group">    
                                            <label for="btn_eliminar" class="control-label">(c)</label> 
                                            <button type="button" class="form-control btn btn-default" id="btn_cancelar" name="btn_cancelar">
                                                <i class="entypo-cancel"></i>
                                            </button>
                                        </div>
                                    </div> -->              
                                </div>

                                <!--class="scrollable" data-height="150" data-scroll-position="right" data-rail-color="#ccc" data-rail-opacity=".9" data-rail-width="8" data-rail-radius="10" data-autohide="0"-->

                                        <div class="table-responsive"> 
                                            <table id="tbl_articulo_actividad" class="table table-bordered datatable">
                                                <thead>
                                                    <tr> 
                                                        <th style="text-align: center">#</th>
                                                        <th style="text-align: center">CODIGO</th>
                                                        <th style="text-align: center">DESCRIPCION</th>
                                                        <th style="text-align: center">CANTIDAD</th>
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
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_frm" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_guardar_frm">Guardar<i class="entypo-floppy"></i></button>
            </div>
        </div>
    </div>
</div>
	
<!--fin-->
