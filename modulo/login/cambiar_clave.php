<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Tercero.php";
$objTercero = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

if(empty($_SESSION['id_tercero'])){
	?>
	<script> 
		window.location = "../../index.php";
	</script>
	<?php
}
else{
    $tercero = $objTercero->buscarTercero($_SESSION['id_tercero']);
}
?>
<script src="../libreria/custom/custom.js"></script>
<script type="text/javascript" src="login/js/cambiar_clave.js"></script>
<ol class="breadcrumb" >
    <li>
        <a href="index.php"><i class="glyphicon glyphicon-home"></i>Inicio</a>
    </li>
    <li>
        <a href="#">Usuario</a>
    </li>
    <li class="active">
    <strong>Cambiar Clave</strong>
    </li>
</ol>
</hr>

<div class="profile-env">
			
    <header class="row">	
                
        <div class="col-sm-2">					
            <a href="#" class="profile-picture">
                <img src="<?="parametros/ajax/descargar_foto.php?id_tercero=".$tercero->fields['id_tercero']."&rand=".rand()?>" class="img-responsive img-circle" />
            </a>					
        </div>
        
        <div class="col-sm-7">					
            <ul class="profile-info-sections">
                <li>
                    <div class="profile-name">
                        <strong>
                            <a href="#"><?=$_SESSION['nombre']." ".$_SESSION['apellido']?></a>
                            <a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a>
                            <!-- User statuses available classes "is-online", "is-offline", "is-idle", "is-busy" -->						
                        </strong>
                        <span><a href="#"><i class="entypo-user"></i><?=$tercero->fields['usuario']?></a></span>
                        <span><a href="#"><i class="entypo-vcard"></i><?=$tercero->fields['abreviatura']." ".number_format($tercero->fields['identificacion'],0,'',',')?></a></span>
                    </div>
                </li>
            </ul>					
        </div>        
    </header>
			
    <section class="profile-info-tabs">				
        <div class="row">					
            <div class="col-sm-offset-2 col-sm-10">						
                <ul class="user-details">
                    <li>
                        <a href="#">
                            <i class="entypo-home"></i>
                            <?=$tercero->fields['municipio'].", ".$tercero->fields['direccion']?>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="entypo-phone"></i>
                            <?=$tercero->fields['telefono']?>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="entypo-mail"></i>
                            <?=$tercero->fields['email']?>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="entypo-suitcase"></i>
                            <span>
                            <?=($tercero->fields['es_empleado']=="S")?"Empleado":"Usuario" ?>
                            </span>
                        </a>
                    </li>
                </ul>		
                			
                
                <!-- tabs for the profile links -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#profile-save" id="btn_guardar">Guardar</a></li>
                </ul>
                
            </div>					
        </div>				
    </section>
		
</div>
<div class="row">
    <form role="form" class="form-horizontal">
			
        <div class="form-group">
            <label for="clave1" class="col-sm-3 control-label">Digita tu nueva clave</label>            
            <div class="col-sm-5">
                <input type="password" class="form-control requerido clear" id="clave1" placeholder="Digita tu Clave" title="Nueva Clave">
            </div>
        </div>
        
        <div class="form-group">
            <label for="clave2" class="col-sm-3 control-label">Repite tu nueva clave</label>            
            <div class="col-sm-5">
                <input type="password" class="form-control requerido clear" id="clave2" placeholder="Repite tu Clave" title="Repetir Nueva Clave">
            </div>
        </div>

       <!--<div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                <button type="button" class="btn btn-blue btn-icon icon-left" id="btn_guardar">Guardar<i class="entypo-floppy"></i></button>
            </div>
        </div>-->
    </form>
</div>
		
