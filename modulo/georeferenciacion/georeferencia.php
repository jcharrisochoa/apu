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
if(empty($_SESSION['id_tercero'])){
	?>
	<script> 
		window.location = "../../index.php";
	</script>
	<?php
}
?>
<script type="text/javascript" src="georeferenciacion/js/georeferencia.js"></script>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Georeferenciaci&oacute;n</a>
    </li>
    <li class="active">
    <strong>Georeferencia</strong>
    </li>
</ol>
</hr>

<!--Filtros-->
<div class="row">
	<div class="col-md-12">
        <div class="panel panel-default">
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

                        <div class="col-xs-12 col-md-3">
                            <label for="barrio" class="control-label">Barrio</label> 
                            <select id="barrio" name="barrio" title="Barrio" class="form-control" data-allow-clear="true" data-placeholder="BARRIO">
                            <option value="">-Todos-</option>
                            </select>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <label for="direccion" class="control-label">Direcci&oacute;n</label> 
                            <input type="text" id="direccion" name="direccion"  class=" form-control" placeholder="DIRECCION"/> 
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
                            <input type="text" id="fch_instalacion_ini" name="fch_instalacion_ini" title="Fecha Instalacion Inicial"  class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                        </div>

                        <div class="col-xs-12 col-md-2">
                            <label for="fch_instalacion_fin" class="control-label">Fecha Final</label> 
                            <input type="text" id="fch_instalacion_fin" name="fch_instalacion_fin" title="Fecha Instalacion Final" class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                        </div>                    

                        <div class="col-xs-12 col-md-2">
                            <br>
                            <button type="button" class="btn btn-blue btn-icon icon-left btn-for" id="btn_buscar_luminaria">
                                <i class="glyphicon glyphicon-search"></i>BUSCAR
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

</br>

<div class="row">
    <div class="col-md-12" id="mapa"></div>
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
                        <div class="panel panel-default panel-shadow" data-collapsed="1" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Actividad(es)</div>
                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="panel-body panel-encabezado panel-collapse">
                                <div class="table-responsive">
                                    <table class="table table-bordered datatable" id="tbl_actividad_luminaria">
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
                <button type="button" class="btn btn-blue btn-icon icon-left btn-for" id="btn_cerrar_detalle_luminaria">Cerrar<i class="glyphicon glyphicon-ok"></i></button>
            
            </div>
        </div>
        </div>
        </div>
        <!--Fin Detalle-->
