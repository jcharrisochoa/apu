<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Tercero.php";
$tercero  = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_tercero = "";
$obj->mensaje = "";
$obj->estado = true;


switch ($_POST['accion']){
    case "nuevo":
        $result = $tercero->nuevaTercero($_POST,$_FILES);
        if(!$result['estado']){
            $obj->id_tercero = "";
            $obj->mensaje = "Error guardando el tercero ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_tercero = $result['data'];
            $obj->mensaje = "Tercero Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $tercero->editarTercero($_POST,$_FILES);
        $obj->id_tercero = $_POST['id_tercero'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $tercero->eliminarTercero($_POST['id_tercero']);
        $obj->id_tercero = $_POST['id_tercero'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));