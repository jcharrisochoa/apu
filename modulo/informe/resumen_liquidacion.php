
<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../liquidacion/clase/Liquidacion.php";
require_once "../global/global.php";
$menu       = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$objMun     = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$objLiq     = new Liquidacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$municipio = $objMun->listarMunicipioContrato();

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
<link rel="stylesheet" href="../libreria/neon/assets/css/responsive-tables.css">
<script src="../libreria/neon/assets/js/responsive-tables.js"></script>
<script src="../libreria/neon/assets/js/jquery.sparkline.min.js"></script>
<script src="../libreria/neon/assets/js/chart.min.js"></script>
<script src="../libreria/custom/custom.js"></script>
<script type="text/javascript" src="informe/js/resumen_liquidacion.js"></script>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Informes</a>
    </li>
    <li class="active">
    <strong>Informe Liquidaci&oacute;n</strong>
    </li>
</ol>
</hr>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="municipio" class="control-label">Municipio</label> 
             <select id="municipio" name="municipio" title="Municipio" class="form-control" data-allow-clear="true" data-placeholder="Municipio">
            <option value="">-Todos</option>
            <?php
            while(!$municipio->EOF){
                echo "<option value=\"".$municipio->fields['id_municipio']."\">".strtoupper($municipio->fields['descripcion'])."</option>";
                $municipio->MoveNext();
            }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="periodo" class="control-label">Periodo</label> 
             <select id="periodo" name="periodo" title="Periodo" class="form-control" data-allow-clear="true" data-placeholder="Periodo">
            <option value="">-Todos</option>
            <?php
            foreach($objLiq->getPeriodo(2019) as $periodo){
                echo "<option value=\"".$periodo."\">".$periodo."</option>";
            }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">   
        <div class="form-group">
            <label for="mes" class="control-label">Mes</label>                  
            <select id="mes" name="mes" title="Mes" class="form-control" data-allow-clear="true" data-placeholder="Mes">
            <option value="">-Todos</option>
            <?php
            foreach($objLiq->getMes() as $mes){
                echo "<option value=\"".$mes->id."\">".$mes->descripcion."</option>";
            }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">   
        <div class="form-group">
        </br>  
        <button type="button" id="btn_generar_informe" class="btn btn-blue btn-icon icon-left">Generar<i class="entypo-search"></i></button>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="panel panel-default" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Recaudo Vs Facturación IAP</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            
            <!-- panel body -->
            <div class="panel-body">
                <canvas id="grafica_recaudo_vs_facturacion" width="600" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="panel panel-default" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Hist&oacute;rico Consumo</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            
            <!-- panel body -->
            <div class="panel-body">
                <canvas id="grafica_historico_consumo" width="600" height="400"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">Resumen Liquidación</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <table class="table table-bordered table-hover table-responsive" id="tbl_resumen">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center">#</th>
                                    <th rowspan="2" class="text-center">MUNICIPIO</th>
                                    <th colspan="2" class="text-center">LIQUIDACION TSYCC</th>                                    
                                    <th colspan="2" class="text-center">LIQUIDACION IAP</th>
                                </tr>
                                <tr>
                                    <th class="text-center">FACTURADO</th>                                    
                                    <th class="text-center">RECAUDADO</th>
                                    <th class="text-center">FACTURADO</th>
                                    <th class="text-center">RECAUDADO</th>
                                </tr>
                            </thead>                            
                            <tbody>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>               

                </div>
            </div>
        </div> 
    </div>
</div>


<!--Para habilitar la tabla , habilita el comentario del js (1)
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">Consumo</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <table class="table table-bordered table-hover table-responsive" id="tbl_consumo">
                            <thead>
                                <tr>
                                <td>#</td>
                                <?php
                                foreach($objLiq->getMes() as $mes){
                                    echo "<th class='text-center'>".substr($mes->descripcion,0,3)."</th>";
                                }
                                ?>
                                </tr>
                            </thead>                            
                            <tbody>
                            </tbody>
                        </table>
                    </div>               

                </div>
            </div>
        </div> 
    </div>
</div>-->