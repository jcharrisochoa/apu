<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Actividad.php";
$actividad  = new Actividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_actividad = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $actividad->nuevaActividad($_POST,$_SESSION);
        if(!$result['estado']){
            $obj->id_actividad = "";
            $obj->mensaje = "Error Guardando la Actividad ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_actividad = $result['data'];
            $obj->mensaje = "Actividad Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $actividad->editarActividad($_POST);
        $obj->id_actividad = $_POST['id_actividad'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $actividad->eliminarActividad($_POST['id_actividad']);
        $obj->id_actividad = $_POST['id_actividad'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));