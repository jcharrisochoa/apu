<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/TipoLuminaria.php";
$tipoLuminaria  = new TipoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_tipo_luminaria = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $tipoLuminaria->nuevoTipoLuminaria($_POST);
        if(!$result['estado']){
            $obj->id_tipo_luminaria = "";
            $obj->mensaje = "Error Guardando tipo Luminaria ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_tipo_luminaria = $result['data'];
            $obj->mensaje = "Tipo Luminaria Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $tipoLuminaria->editarTipoLuminaria($_POST);
        $obj->id_tipo_luminaria = $_POST['id_tipo_luminaria'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $tipoLuminaria->eliminarTipoLuminaria($_POST['id_tipo_luminaria']);
        $obj->id_tipo_luminaria = $_POST['id_tipo_luminaria'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));