<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../parametros/clase/PeriodoMantenimiento.php";
require_once "clase/Liquidacion.php";

$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjPeriodo = new PeriodoMantenimiento($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$liq = new Liquidacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$municipio = $ObjMun->listarMunicipioContrato();
$periodoMantenimiento = $ObjPeriodo->listarPeriodoMantenimiento();

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
<script type="text/javascript" src="liquidacion/js/liquidacion.js"></script>
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
    <strong>Gesti&oacute;n Hoja de Vida de Luminaria</strong>
    </li>
</ol>
</hr>
<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nueva_liquidacion" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_liquidacion" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_liquidacion" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
        <button type="button" id="btn_detalle_liquidacion" class="btn btn-blue btn-icon icon-left">Detalle<i class="entypo-info"></i></button>
        <?php if($IMPRIMIR=="S"){ ?>
        <button type="button" id="btn_exportar_liquidacion" class="btn btn-primary btn-icon icon-left">Descargar<i class="entypo-down"></i></button>
        <?php } ?>
    </div>
</div>
<br/>
    <!--Filtros-->
<div class="row">
	<div class="col-md-12">
        <div class="panel panel-default panel-shadow" data-collapsed="0">
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
                            <label for="periodo_liquidacion" class="control-label">Periodo Liq</label> 
                            <select id="periodo_liquidacion" name="periodo_liquidacion" title="Periodo Liquidación" class="form-control" data-allow-clear="true" data-placeholder="PERIODO">
                            <option value="">-Todos-</option>
                            <?php
                            foreach($liq->getPeriodo(2019) as $periodo){
                                echo "<option value=\"".$periodo."\">".$periodo."</option>";
                            }
                            ?>
                            </select>              
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <label for="mes_liquidacion" class="control-label">Mes Liq</label> 
                            <select id="mes_liquidacion" name="mes_liquidacion" title="Mes Liquidacion" class="form-control" data-allow-clear="true" data-placeholder="MES">
                            <option value="">-Todos-</option>
                            <?php
                            foreach($liq->getMes() as $mes){
                                echo "<option value=\"".$mes->id."\">".$mes->descripcion."</option>";
                            }
                            ?>
                            </select>              
                        </div>

                        <!--<div class="col-xs-12 col-md-3">
                            <label for="fch_facturado_ini" class="control-label">Periodo Facturado</label> 
                            <div class="row">
                                <div class="col-xs-6 col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="fch_facturado_ini" name="fch_facturado_ini" title="Fecha Inicial"  class="form-control datepicker"  placeholder="YYYY-MM-DD"/> 
                                        <div class="input-group-addon">
                                            <a href="#"><i class="entypo-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="fch_facturado_fin" name="fch_facturado_fin" title="Fecha Final" class="form-control datepicker"  placeholder="YYYY-MM-DD"/>
                                        <div class="input-group-addon">
                                            <a href="#"><i class="entypo-calendar"></i></a>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                        </div>-->    
                        

                        <div class="col-xs-12 col-md-3">
                            </br>
                            <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_buscar_liquidacion" name="btn_buscar_liquidacion">
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
<table id="tbl_liquidacion" class="table table-bordered datatable table-responsive">
    <thead>
        <tr> 
            <th style="text-align: center">#</th>
            <th style="text-align: center">MUNICIPIO</th>
            <th style="text-align: center">PERIODO LIQ</th>
            <th style="text-align: center">MES LIQ</th>
            <th style="text-align: center">CONSUMO</th>
            <th style="text-align: center">VALOR TARIFA</th>
            <th style="text-align: center">VALOR CONSUMO</th>
            <th style="text-align: center" title="Facturaci&oacute;n de Energ&iacute;a">F. ENERGIA</th>
            <th style="text-align: center" title="Facturaci&oacute;n de Impuesto AP">F. AP</th>
            <th style="text-align: center" title="Facturaci&oacute;n de Tasa Seguridad">F. TSYCC</th> 
            <th style="text-align: center" title="Diferencia en Facturaci&oacute;n">DIF FACT</th> 
            <th style="text-align: center" title="Recaudo Impuesto AP">R. AP</th>     
            <th style="text-align: center" title="Recaudo Tasa Seguridad">R. TSYCC</th>
            <th style="text-align: center" title="Diferencia en Recaudo">DIF REC</th>            
        </tr>
    </thead>
</table>
</div>

<!--Formulario de Entrada-->
<div class="modal fade" id="frm-liquidacion" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="frm-titulo-liquidacion">Titulo</h4>
            </div>
            
            <div class="modal-body">
                <form id="form-liquidacion">
                    <input type="hidden" id="id_liquidacion" name="id_liquidacion" class="form-control clear" value="" />
                    
                    <!--Municipio y Periodo-->
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
                                        
                                        <div class="col-md-3">                            
                                            <div class="form-group">
                                                <label for="slt_periodo_liquidacion requerido" class="control-label">Periodo Liquida.</label>
                                                <select id="slt_periodo_liquidacion" name="slt_periodo_liquidacion" class="form-control requerido clear" placeholder="PERIODO" title="Periodo Liquidaci&oacute;n">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    foreach($liq->getPeriodo(2019) as $periodo){
                                                        echo "<option value=\"".$periodo."\">".$periodo."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>                                        
                                        </div>

                                        <div class="col-md-3">                            
                                            <div class="form-group">
                                                <label for="slt_mes_liquidacion requerido" class="control-label">Mes Liquida.</label>
                                                <select id="slt_mes_liquidacion" name="slt_mes_liquidacion" class="form-control requerido clear" placeholder="MES" title="Mes Liquidaci&oacute;n">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    foreach($liq->getMes() as $mes){
                                                        echo "<option value=\"".$mes->id."\">".$mes->descripcion."</option>";
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
                    <!--Fin Municipio y Periodo-->

                    <!--Liquidacion AP-->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                                <!-- panel head -->
                                <div class="panel-heading">
                                    <div class="panel-title">Liquidaci&oacute;n Alumbrado P&uacute;blico</div>
                                    <div class="panel-options">
                                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    </div>
                                </div>
                                <div class="panel-body panel-encabezado">
                                    <div class="row">
                                    
                                        <div class="col-sm-12 col-md-8">
                                            <label for="" class="control-label">Periodo de Facturaci&oacute;n</label>
                                            <div class="row">
                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        
                                                        <div class="input-group">
                                                            <input type="text" id="txt_fecha_ini" name="txt_fecha_ini" class="form-control requerido clear datepicker" maxlength="10" placeholder="YYYY-MM-DD" title="Fecha Inicio"> 
                                                            <div class="input-group-addon">
                                                                <a href="#"><i class="entypo-calendar"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" id="txt_fecha_fin" name="txt_fecha_fin" class="form-control requerido clear datepicker" maxlength="10" placeholder="YYYY-MM-DD" title="Fecha fin"> 
                                                            <div class="input-group-addon">
                                                                <a href="#"><i class="entypo-calendar"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-4"></div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_valor_tarifa" class="control-label">Valor Tarifa</label>
                                                <div class="input-group">
										            <span class="input-group-addon">$</span>
                                                    <input type="text" id="txt_valor_tarifa" name="txt_valor_tarifa" class="form-control requerido clear text-right numeric-positive" maxlength="20" placeholder="0,0" title="Valor Tarifa Impuesto AP"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_consumo" class="control-label">Consumo (KV)</label>
                                                <input type="text" id="txt_consumo" name="txt_consumo" class="form-control requerido clear text-right" maxlength="15" placeholder="0" title="Consumo"> 
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_total_consumo" class="control-label">Total Consumo</label>
                                                <div class="input-group">
										            <span class="input-group-addon">$</span>
                                                    <input type="text" id="txt_total_consumo" name="txt_total_consumo" readonly class="form-control requerido clear text-right numeric-positive" maxlength="20" placeholder="0,0" title="Total Consumo"> 
                                                </div>
                                            </div>
                                        </div>

                                    </div>                                    

                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_valor_facturado_ap" class="control-label">Factruaci&oacute;n Impuesto AP</label>
                                                <div class="input-group">
										            <span class="input-group-addon">$</span>
                                                    <input type="text" id="txt_valor_facturado_ap" name="txt_valor_facturado_ap" class="form-control requerido clear text-right numeric-positive" maxlength="20" placeholder="0,0" title="Valor Facturado por Impuesto AP"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_valor_recaudo_ap" class="control-label">Recaudo Impuesto AP</label>
                                                <div class="input-group">
										            <span class="input-group-addon">$</span>
                                                    <input type="text" id="txt_valor_recaudo_ap" name="txt_valor_recaudo_ap" class="form-control requerido clear text-right numeric-positive" maxlength="20" placeholder="0,0" title="Valor Recaudado por Impuesto AP"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_valor_factura_energia_ap" class="control-label">Factruaci&oacute;n Energ&iacute;a AP</label>
                                                <div class="input-group">
										            <span class="input-group-addon">$</span>
                                                    <input type="text" id="txt_valor_factura_energia_ap" name="txt_valor_factura_energia_ap" class="form-control requerido clear text-right numeric-positive"  maxlength="20" placeholder="0,0" title="Valor facturaci&oacute;n energ&iacute;a AP"> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4 col-md-4"></div>
                                        <div class="col-sm-4 col-md-4 text-right ">
                                            <strong>Diferencia de Consumo</strong>
                                        </div>
                                        
                                        <div class="col-sm-4 col-md-4 text-right">                                                
                                            <div class="text-right badge badge-success" id="diferencia_consumo">0,0</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Liquidacion AP-->

                    <!--Liquidacion TSYCC-->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-shadow" data-collapsed="0" id="panel-encabezado"><!-- to apply shadow add class "panel-shadow" -->
                                <!-- panel head -->
                                <div class="panel-heading">
                                    <div class="panel-title">Liquidaci&oacute;n TSYCC</div>
                                    <div class="panel-options">
                                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    </div>
                                </div>
                                <div class="panel-body panel-encabezado">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_valor_facturado_tsycc" class="control-label">Factruaci&oacute;n TSYCC</label>
                                                <div class="input-group">
										            <span class="input-group-addon">$</span>
                                                    <input type="text" id="txt_valor_facturado_tsycc" name="txt_valor_facturado_tsycc" class="form-control requerido clear text-right numeric-positive" maxlength="20" placeholder="0,0" title="Valor Facturado TSYCC"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="txt_valor_recaudo_tsycc" class="control-label">Recaudo TSYCC</label>
                                                <div class="input-group">
										            <span class="input-group-addon">$</span>
                                                    <input type="text" id="txt_valor_recaudo_tsycc" name="txt_valor_recaudo_tsycc" class="form-control requerido clear text-right numeric-positive" maxlength="20" placeholder="0,0" title="Valor Recaudado TSYCC"> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Liquidacion TSYCC-->

                    <div class="row">
                        <div class="col-sm-12 col-md-4 text-right">
                            <label for="diferencia_facturacion" class="control-label">Dif. Facturaci&oacute;n AP vs TSYCC</label>
                            <div class="form-group">
                                <div class="text-right badge badge-success" id="diferencia_facturacion">0,0</div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 text-right">
                            <label for="diferencia_recaudo" class="control-label">Dif. Recaudo AP vs TSYCC</label>
                            <div class="form-group">
                                <div class="text-right badge badge-success" id="diferencia_recaudo">0,0</div>
                            </div>
                        </div>
                    </div>

                </form>             
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon icon-left" id="btn_cerrar_frm" data-dismiss="modal">Cerrar<i class="entypo-cancel"></i></button>
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_guardar_liquidacion">Guardar<i class="entypo-floppy"></i></button>
            </div>

        </div>
    </div>
</div>