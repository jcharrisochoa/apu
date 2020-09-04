<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
if(empty($_SESSION['id_tercero'])){
	?>
	<script> 
		window.location = "../../index.php";
	</script>
	<?php
}
?>
<script src="../libreria/custom/custom.js"></script>
<script type="text/javascript" src="parametros/js/medio_recepcion_pqr.js"></script>
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
    <strong>Medio Recepcion PQR</strong>
    </li>
</ol>
</hr>
<div class="row">
	<div class="col-md-12">
        <button type="button" id="btn_nuevo_medio_recepcion_pqr" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <button type="button" id="btn_editar_medio_recepcion_pqr" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <button type="button" id="btn_eliminar_medio_recepcion_pqr" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
    </div>
</div>
<br/>


<div class="table-responsive panel-shadow">
<table id="tbl_medio_recepcion_pqr" class="table table-bordered datatable table-responsive">
    <thead>
        <tr> 
            <th style="text-align: center">#</th>
            <th style="text-align: center">DESCRIPCION</th>
            <th style="text-align: center">ID_MEDIO_RECEPCION_PQR</th>
        </tr>
    </thead>
</table>
</div>


<div class="modal fade" id="frm-medio-recepcion-pqr">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="frm-titulo-medio-recepcion-pqr">Titulo</h4>
				</div>
				
				<div class="modal-body">
                    <form id="form-medio-recepcion-pqr">
                        <input type="hidden" id="id_medio_recepcion_pqr" name="id_medio_recepcion_pqr" class="form-control clear" value="" />				
                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="txt_descripcion" class="control-label">Descripci&oacute;n</label>								
                                    <input type="text" class="form-control requerido clear" id="txt_descripcion" name="txt_descripcion" placeholder="Descripcion" title="Descripci&oacute;n" maxlength="45">
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
