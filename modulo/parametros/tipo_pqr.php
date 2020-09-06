<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "clase/Menu.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
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
<script type="text/javascript" src="parametros/js/tipo_pqr.js"></script>
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
    <strong>Tipo PQR</strong>
    </li>
</ol>
</hr>
<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nuevo_tipo_pqr" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_tipo_pqr" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_tipo_pqr" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
    </div>
</div>
<br/>


<div class="table-responsive panel-shadow">
<table id="tbl_tipo_pqr" class="table table-bordered datatable table-responsive">
    <thead>
        <tr> 
            <th style="text-align: center">#</th>
            <th style="text-align: center">DESCRIPCION</th>
            <th style="text-align: center">DIAS LIMITE DE RESPUESTA</th>
            <th style="text-align: center">ESTADO</th>
            <th style="text-align: center">ID_TIPO_PQR</th>
        </tr>
    </thead>
</table>
</div>


<div class="modal fade" id="frm-tipo-pqr">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="frm-titulo-tipo-pqr">Titulo</h4>
				</div>
				
				<div class="modal-body">
                    <form id="form-tipo-pqr">
                        <input type="hidden" id="id_tipo_pqr" name="id_tipo_pqr" class="form-control clear" value="" />				
                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="txt_descripcion" class="control-label">Descripci&oacute;n</label>								
                                    <input type="text" class="form-control requerido clear" id="txt_descripcion" name="txt_descripcion" placeholder="Descripcion" title="Descripci&oacute;n" maxlength="45">
                                </div>							
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="txt_dia" class="control-label">D&iacute;s L&iacute;mite Repuesta</label>								
                                    <input type="text" class="form-control requerido clear" id="txt_dia" name="txt_dia" placeholder="D&iacute;a" title="D&iacute;a" maxlength="2">
                                </div>							
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="slt_estado" class="control-label">Estado</label>								
                                    <select id="slt_estado" name="slt_estado" class="form-control requerido clear" placeholder="Estado" title="Estado">
                                    <option value="">-Seleccione-</option>
                                    <option value="A">ACTIVO</option>
                                    <option value="I">INACTIVO</option>
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
