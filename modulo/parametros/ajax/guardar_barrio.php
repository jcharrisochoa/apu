<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Barrio.php";
$barrio  = new Barrio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_barrio = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $barrio->nuevoBarrio($_POST);
        if(!$result['estado']){
            $obj->id_barrio = "";
            $obj->mensaje = "Error Guardando el barrio ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_barrio = $result['data'];
            $obj->mensaje = "Barrio Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $barrio->editarBarrio($_POST);
        $obj->id_barrio = $_POST['id_barrio'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $barrio->eliminarBarrio($_POST['id_barrio']);
        $obj->id_barrio = $_POST['id_barrio'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));