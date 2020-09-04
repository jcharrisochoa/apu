<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/TipoActividad.php";
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjTipoAct = new TipoActividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$municipio = $ObjMun->listarMunicipioContrato();
$tipoActividad = $ObjTipoAct->listarTipoActividad();
if(empty($_SESSION['id_tercero'])){
	?>
	<script> 
		window.location = "../../index.php";
	</script>
	<?php
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
    <strong>Listado Actividades</strong>
    </li>
</ol>
</hr>

<div class="row">
	<div class="col-md-12">
        <button type="button" id="btn_nueva_actividad" class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <button type="button" id="btn_editar_actividad" class="btn btn-blue btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <button type="button" id="btn_eliminar_actividad" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
    </div>
</div>
<br/>
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
                            <select id="municipio" name="municipio" title="Municipio" class="form-control requerido" data-allow-clear="true" data-placeholder="MUNICIPIO">
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

<!--Detalle Luminaria-->
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
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Poste.No</label></div>
                                        <div class="col-ms-12 col-md-4" style="color:#2ca02c; font-weight: bold;" id="td_poste_no"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo Reporte</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo_reporte"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Luminaria No</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_luminaria_no"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Fch Reporte</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_fch_reporte"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo Luminaria</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo_luminaria"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tipo Actividad</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_tipo"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Municipio</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_municipio"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Fch Actividad</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_fch_instalacion"></div>               
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Barrio</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_barrio"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Tècnico</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_usuario"></div>               
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Direcci&oacute;n</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_direccion"></div>
                                        <div class="col-ms-12 col-md-2"><label class="control-label">Estado</label></div>
                                        <div class="col-ms-12 col-md-4" id="td_estado"></div>               
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
<div class="modal fade" id="frm-actividad">
    <div class="modal-dialog">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-actividad">Titulo</h4>
            </div>
            
            <div class="modal-body">
            
                <div class="row">
                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label for="field-1" class="control-label">Name</label>
                            
                            <input type="text" class="form-control" id="field-1" placeholder="John">
                        </div>	
                        
                    </div>
                    
                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label for="field-2" class="control-label">Surname</label>
                            
                            <input type="text" class="form-control" id="field-2" placeholder="Doe">
                        </div>	
                    
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="form-group">
                            <label for="field-3" class="control-label">Address</label>
                            
                            <input type="text" class="form-control" id="field-3" placeholder="Address">
                        </div>	
                        
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-4">
                        
                        <div class="form-group">
                            <label for="field-4" class="control-label">City</label>
                            
                            <input type="text" class="form-control" id="field-4" placeholder="Boston">
                        </div>	
                        
                    </div>
                    
                    <div class="col-md-4">
                        
                        <div class="form-group">
                            <label for="field-5" class="control-label">Country</label>
                            
                            <input type="text" class="form-control" id="field-5" placeholder="United States">
                        </div>	
                    
                    </div>
                    
                    <div class="col-md-4">
                        
                        <div class="form-group">
                            <label for="field-6" class="control-label">Zip</label>
                            
                            <input type="text" class="form-control" id="field-6" placeholder="123456">
                        </div>	
                    
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="form-group no-margin">
                            <label for="field-7" class="control-label">Personal Info</label>
                            
                            <textarea class="form-control autogrow" id="field-7" placeholder="Write something about yourself"></textarea>
                        </div>	
                        
                    </div>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
                <button type="button" class="btn btn-blue btn-icon icon-left">Guardar<i class="entypo-floppy"></i></button>
            </div>
        </div>
    </div>
</div>
	
<!--fin-->
