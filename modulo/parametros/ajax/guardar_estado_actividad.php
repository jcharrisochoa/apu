<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/EstadoActividad.php";
$estadoActividad  = new EstadoActividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_estado_actividad = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $estadoActividad->nuevoEstadoActividad($_POST);
        if(!$result['estado']){
            $obj->id_estado_actividad = "";
            $obj->mensaje = "Error guardando estado actividad ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_estado_actividad = $result['data'];
            $obj->mensaje = "Estado Actividad Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $estadoActividad->editarEstadoActividad($_POST);
        $obj->id_estado_actividad = $_POST['id_estado_actividad'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $estadoActividad->eliminarEstadoActividad($_POST['id_estado_actividad']);
        $obj->id_estado_actividad = $_POST['id_estado_actividad'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));