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
<script type="text/javascript" src="informe/js/cuadro_resumen.js"></script>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Informes</a>
    </li>
    <li class="active">
    <strong>Resumen de Actividades</strong>
    </li>
</ol>
</hr>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="periodo" class="control-label">Periodo</label> 
             <select id="periodo" name="periodo" title="Periodo" class="form-control" data-allow-clear="true" data-placeholder="Periodo">
            <option value="">-Todos</option>
            <?php
            $ini = 2010;
            while($ini<=date("Y")){
                echo "<option value=\"".$ini."\">".$ini."</option>";
                $ini++;
            }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">   
        <div class="form-group">
            <label for="tipo_actividad" class="control-label">Tipo Actividad</label>                  
            <select id="tipo_actividad" name="tipo_actividad" title="Tipo" class="form-control" data-allow-clear="true" data-placeholder="Tipo Actividad">
            <option value="">-Todos</option>
            <?php
            while(!$tipoActividad->EOF){
                echo "<option value=\"".$tipoActividad->fields['id_tipo_actividad']."\">".strtoupper($tipoActividad->fields['descripcion'])."</option>";
                $tipoActividad->MoveNext();
            }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">   
        <div class="form-group">
        </br>  
        <button type="button" id="btn_generar_cuadro_resumen" class="btn btn-blue btn-icon icon-left">Generar<i class="entypo-search"></i></button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">					
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Actividades del Periodo</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload" onclick="tablaResumenActividadPeriodo();"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>            
            <!-- panel body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">   
                        <div id="div_actividad_periodo">
                        
                        </div>
                    </div>
                </div>
            </div>					
        </div>
    </div>
    
    <!--<div class="col-md-6">
        <div class="panel panel-primary" data-collapsed="0">					
           
            <div class="panel-heading">
                <div class="panel-title">Actividad por Tecnolog&iacute;a</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                </div>
            </div>            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">   
                        <div id="div_actividad_tecnologia">
                        
                        </div>
                    </div>
                </div>
            </div>					
        </div>
    </div>-->
</div>