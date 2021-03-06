<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "clase/Menu.php";
require_once "clase/Departamento.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$ObjDep = new Departamento($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$departamento = $ObjDep->listarDepartamento();

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
<script type="text/javascript" src="parametros/js/municipio.js"></script>
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
        <a href="#">Par&aacute;metros</a>
    </li>
    <li class="active">
    <strong>Municipio</strong>
    </li>
</ol>
</hr>
<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nuevo_municipio" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_municipio" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_municipio" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
    </div>
</div>
<br/>


<div class="table-responsive panel-shadow">
<table id="tbl_municipio" class="table table-bordered datatable table-responsive">
    <thead>
        <tr> 
            <th style="text-align: center">#</th>
            <th style="text-align: center">DEPARTAMENTO</th>
            <th style="text-align: center">MUNICIPIO</th>
            <th style="text-align: center">TIENE CONTRATO</th>
            <th style="text-align: center">LATITUD</th>
            <th style="text-align: center">LONGITUD</th>
            <th style="text-align: center">ID_MUNICIPIO</th>
            <th style="text-align: center">ID_DEPARTAMENTO</th>
        </tr>
    </thead>
</table>
</div>


<div class="modal fade" id="frm-municipio">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="frm-titulo-municipio">Titulo</h4>
				</div>
				
				<div class="modal-body">
                    <form id="form-municipio">
                        <input type="hidden" id="id_municipio" name="id_municipio" class="form-control clear" value="" />				
                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="slt_departamento" class="control-label">Departamento</label>								
                                    <select id="slt_departamento" name="slt_departamento" class="form-control requerido clear" placeholder="Departamento" title="Departamento">
                                    <option value="">-Seleccione-</option>
                                    <?php
                                    while(!$departamento->EOF){
                                        echo "<option value=\"".$departamento->fields['id_departamento']."\">".strtoupper($departamento->fields['descripcion'])."</option>";
                                        $departamento->MoveNext();
                                    }
                                    ?>
                                </select>
                                </div>							
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="txt_descripcion" class="control-label">Descripci&oacute;n</label>								
                                    <input type="text" class="form-control requerido clear" id="txt_descripcion" name="txt_descripcion" placeholder="Descripcion" title="Descripci&oacute;n" maxlength="45">
                                </div>							
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">							
                                <div class="form-group">
                                    <label for="txt_latitud" class="control-label">Latitud</label>								
                                    <input type="text" class="form-control clear" id="txt_latitud" name="txt_latitud" placeholder="Latitud" title="Latiud">
                                </div>							
                            </div>
                            <div class="col-md-6">							
                                <div class="form-group">
                                    <label for="txt_longitud" class="control-label">Latitud</label>								
                                    <input type="text" class="form-control clear" id="txt_longitud" name="txt_longitud" placeholder="Longitud" title="Longitud">
                                </div>							
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="slt_tiene_contrato" class="control-label">Tiene Contrato</label>								
                                    <select id="slt_tiene_contrato" name="slt_tiene_contrato" class="form-control requerido clear" placeholder="Tiene Contrato" title="Tiene Contrato">
                                    <option value="">-Seleccione-</option>
                                    <option value="S">SI</option>
                                    <option value="N">NO</option>
                                </select>
                                </div>							
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
