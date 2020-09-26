<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Tercero.php";
$tercero = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$count = $tercero->contarEmpleado($_POST)['data'];
$_POST['length']=3;
$result = $tercero->listarEmpleado($_POST);
$i=1;
$next = $_POST['start']+$_POST['length'];
$back = ($_POST['start']==0)?0:$_POST['start']-$_POST['length'];

while(!$result["data"]->EOF){
    ?>
    <!-- Single Member -->
    <div class="member-entry">				
        <a href="#" class="member-img" data-value="<?=$result['data']->fields['id_tercero']?>">
            <img src="<?="parametros/ajax/descargar_foto.php?id_tercero=".$result["data"]->fields['id_tercero']."&rand=".rand()?>" class="img-rounded" />
        </a>
        
        <div class="member-details">
            <h4>
                <a href="#"><?=$result["data"]->fields['nombre']." ".$result["data"]->fields['apellido']?></a>
            </h4>        
            <!-- Details with Icons -->
            <div class="row info-list">
                
                <div class="col-sm-4">
                    <i class="entypo-user"></i>
                    <span class="badge badge-success chat-notifications-badge is-hidden" title="Ejecuta Labor Operativa">0</span>
                    <a href="#"><?=($result["data"]->fields['usuario']!="")?$result["data"]->fields['usuario']:"- -"?></a>
                </div>
                
                <div class="col-sm-4">
                    <i class="entypo-vcard"></i>
                    <a href="#"><?=($result["data"]->fields['identificacion']!="")?$result["data"]->fields['abreviatura']." ".number_format($result["data"]->fields['identificacion'],0,"","."):"- -"?></a>
                </div>
                
                <div class="col-sm-4">
                    <i class="entypo-location"></i>
                    <a href="#"><?=$result["data"]->fields['departamento']?>&nbsp;/&nbsp;<?=$result["data"]->fields['municipio']?></a>
                </div>
                
                <div class="clear"></div>
                
                <div class="col-sm-4">
                    <i class="entypo-home"></i>
                    <a href="#"><?=($result["data"]->fields['direccion']!="")?$result["data"]->fields['direccion']:"- -"?></a>
                </div>
                
                <div class="col-sm-4">
                    <i class="entypo-mail"></i>
                    <a href="#"><?=($result["data"]->fields['email']!="")?$result["data"]->fields['email']:"- -"?></a>
                </div>
                
                <div class="col-sm-4">
                    <i class="entypo-phone"></i>
                    <a href="#"><?=($result["data"]->fields['telefono']!="")?$result["data"]->fields['telefono']:"- -"?></a>
                </div>
                <div class="clear"></div>

                <div class="col-sm-4">
                    <i class="entypo-suitcase"></i>
                    <a href="#">Empleado:<?=($result["data"]->fields['es_empleado']=="S")?"SI":"NO"?></a>
                </div>
                
                <div class="col-sm-4">
                    <i class="entypo-tools"></i>
                    <a href="#">Labor Técnica:<?=($result["data"]->fields['ejecuta_labor_tecnica']=="S")?"SI":"NO"?></a>
                </div>
                <div class="col-sm-4"></div>
                <div class="clear"></div>

                <div class="col-sm-8"> </div>
                <div class="col-sm-4"> 
                    <?php if($_POST['editar']=="S" and $result["data"]->fields['super_usuario']=="N"){ ?>
                        <button type="button" id="" onclick="editarTercero(<?=$result['data']->fields['id_tercero']?>);" class="btn btn-default"><i class="entypo-pencil"></i></button>
                    <?php } ?>
                    <?php if($_POST['eliminar']=="S" and $result["data"]->fields['super_usuario']=="N"){ ?>
                        <button type="button" id="" onclick="eliminarTercero(<?=$result['data']->fields['id_tercero']?>,'<?=$result['data']->fields['nombre'].' '.$result['data']->fields['apellido']?>');" class="btn btn-default"><i class="entypo-trash"></i></button>
                    <?php } ?>
                    <button type="button" id="" on class="btn btn-default btn-perfil" data-value="<?=$result['data']->fields['id_tercero']?>"><i class="entypo-info"></i></button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $i++;
    $result["data"]->MoveNext();
}
?>
<!-- Pager for search results -->
<div class="row">
    <div class="col-md-12">
        <ul class="pager">
            <?php if($_POST['start']>0){ ?>
            <li><a href="#" onclick="listarEmpleado(<?=$back?>);"><i class="entypo-left-thin"></i>Atras</a></li>
            <?php } ?>
            <?php if($count>$next){ ?>
                <li><a href="#" onclick="listarEmpleado(<?=$next?>);">Siguiente<i class="entypo-right-thin"></i></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
$(function(){
    $(".btn-perfil").click(function(){
        verPerfil($(this).attr("data-value"));
    });
    $(".member-img").click(function(){
        verPerfil($(this).attr("data-value"));
       
    });
});