
<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Menu.php";
require_once "../parametros/clase/Municipio.php";
require_once "../global/global.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjMun = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$municipio = $ObjMun->listarMunicipioContrato();

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
<script src="../libreria/custom/custom.js"></script>
<script type="text/javascript" src="informe/js/resumen_encuesta.js"></script>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Informes</a>
    </li>
    <li class="active">
    <strong>Informe Encueta Satisfacci&oacute;n</strong>
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
            $ini = 2010;
            while($ini<=date("Y")){
                echo "<option value=\"".$ini."\">".$ini."</option>";
                $ini++;
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
            foreach(Meses() as $mes){
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
		
    <div class="col-md-4">    
        <div class="panel panel-default" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Calidad</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            
            <!-- panel body -->
            <div class="panel-body">
                <div class="text-center">
                    <span class="pie-large-calidad"></span>                    
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <table class="table table-hover" id="tbl_calidad">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CALIFICACION</th>
                                <th>CANTIDAD</th>
                                <th>PORCENTAJE</th>
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

    <div class="col-md-4">    
        <div class="panel panel-default" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Tiempo</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            
            <!-- panel body -->
            <div class="panel-body">
                <div class="text-center">
                    <span class="pie-large-tiempo"></span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <table class="table table-hover" id="tbl_tiempo">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CALIFICACION</th>
                                <th>CANTIDAD</th>
                                <th>PORCENTAJE</th>
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

    <div class="col-md-4">    
        <div class="panel panel-default" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Atenci&oacute;n</div>                
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            
            <!-- panel body -->
            <div class="panel-body">
                <div class="text-center">
                    <span class="pie-large-atencion"></span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <table class="table table-hover" id="tbl_atencion">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CALIFICACION</th>
                                <th>CANTIDAD</th>
                                <th>PORCENTAJE</th>
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

</div>

<div class="panel panel-primary" data-collapsed="0">					
    <!-- panel head -->
    <div class="panel-heading">
        <div class="panel-title">Encuesta(s) del Periodo</div>                
        <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
            <a href="#" data-rel="reload" onclick="tablaResumenEncuesta();"><i class="entypo-arrows-ccw"></i></a>
        </div>
    </div>            
    <!-- panel body -->  
    <div class="panel-body">             
        <div class="row">
            <div class="col-md-12">
                <div id="div_encuesta_periodo"></div>
            </div>
        </div>
    </div>
</div>
    <!--<span class="badge badge-danger">M</span>    -->
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

    
