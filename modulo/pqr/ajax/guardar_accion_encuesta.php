<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Encuesta.php";
$encuesta  = new Encuesta($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_encuesta = "";
$obj->mensaje = "";
$obj->estado = true;


switch ($_POST['accion']){
    case "nuevo":
        $result = $encuesta->nuevaEncuesta($_POST);
        if(!$result['estado']){
            $obj->id_encuesta = "";
            $obj->mensaje = "Error guardando la encuesta ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_encuesta = $result['data'];
            $obj->mensaje = "Encuesta Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $encuesta->editarEncuesta($_POST);
        $obj->id_encuesta = $_POST['id_encuesta'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $encuesta->eliminarEncuesta($_POST['id_encuesta']);
        $obj->id_encuesta = $_POST['id_encuesta'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));