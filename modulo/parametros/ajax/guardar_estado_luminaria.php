<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/EstadoLuminaria.php";
$estadoLuminaria  = new EstadoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_estado_luminaria = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $estadoLuminaria->nuevoEstadoLuminaria($_POST);
        if(!$result['estado']){
            $obj->id_estado_luminaria = "";
            $obj->mensaje = "Error Guardando estado luminaria".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_estado_luminaria = $result['data'];
            $obj->mensaje = "Estado Luminaria Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $estadoLuminaria->editarEstadoLuminaria($_POST);
        $obj->id_estado_luminaria = $_POST['id_estado_luminaria'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $estadoLuminaria->eliminarEstadoLuminaria($_POST['id_estado_luminaria']);
        $obj->id_estado_luminaria = $_POST['id_estado_luminaria'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));