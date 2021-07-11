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
<script type="text/javascript" src="parametros/js/tipo_actividad.js"></script>
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
    <strong>Tipo Actividad</strong>
    </li>
</ol>
</hr>
<div class="row">
	<div class="col-md-12">
        <?php if($CREAR=="S"){ ?>
        <button type="button" id="btn_nuevo_tipo_actividad" style class="btn btn-green btn-icon icon-left">Nuevo<i class="entypo-plus"></i></button>
        <?php } 
        if($EDITAR=="S"){ ?>
        <button type="button" id="btn_editar_tipo_actividad" class="btn btn-orange btn-icon icon-left">Editar<i class="entypo-pencil"></i></button>
        <?php }  
        if($ELIMINAR=="S"){ ?>
        <button type="button" id="btn_eliminar_tipo_actividad" class="btn btn-red btn-icon icon-left">Eliminar<i class="entypo-trash"></i></button>
        <?php } ?>
    </div>
</div>
<br/>


<div class="table-responsive panel-shadow">
<table id="tbl_tipo_actividad" class="table table-bordered datatable table-responsive">
    <thead>
        <tr> 
            <th style="text-align: center">#</th>
            <th style="text-align: center">DESCRIPCION</th>
            <th style="text-align: center">ID_TIPO_ACTIVIDAD</th>
            <th style="text-align: center">INSTALACION</th>
            <th style="text-align: center">PREVENTIVO</th>
            <th style="text-align: center">CORRECTIVO</th>
        </tr>
    </thead>
</table>
</div>


<div class="modal fade" id="frm-tipo-actividad">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="frm-titulo-tipo-actividad">Titulo</h4>
				</div>
				
				<div class="modal-body">
                    <form id="form-tipo-actividad">
                        <input type="hidden" id="id_tipo_actividad" name="id_tipo_actividad" class="form-control clear" value="" />				
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
                                    <label for="slt_instalacion" class="control-label">Tipo Instalaci&oacute;n</label>								
                                    <select id="slt_instalacion" name="slt_instalacion" class="form-control requerido clear" placeholder="Tipo Instalaci&oacute;n" title="Tipo Instalaci&oacute;n">
                                    <option value="">-Seleccione-</option>
                                    <option value="N">NO</option>
                                    <option value="S">SI</option>
                                </select>
                                </div>							
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="slt_preventivo" class="control-label">Tipo Preventivo</label>								
                                    <select id="slt_preventivo" name="slt_preventivo" class="form-control requerido clear" placeholder="Tipo Preventivo" title="Tipo Preventivo">
                                    <option value="">-Seleccione-</option>
                                    <option value="N">NO</option>
                                    <option value="S">SI</option>
                                </select>
                                </div>							
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">							
                                <div class="form-group">
                                    <label for="slt_correctivo" class="control-label">Tipo Correctivo</label>								
                                    <select id="slt_correctivo" name="slt_correctivo" class="form-control requerido clear" placeholder="Tipo Correctivo" title="Tipo Correctivo">
                                    <option value="">-Seleccione-</option>
                                    <option value="N">NO</option>
                                    <option value="S">SI</option>
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
