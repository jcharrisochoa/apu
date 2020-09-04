<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Luminaria.php";
$luminaria  = new Luminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_luminaria = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $luminaria->nuevaLuminaria($_POST);
        if(!$result['estado']){
            $obj->id_luminaria = "";
            $obj->mensaje = "Error Guardando el Punto Luminico ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_luminaria = $result['data'];
            $obj->mensaje = "Punto Luminico Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $luminaria->editarLuminaria($_POST);
        $obj->id_luminaria = $_POST['id_luminaria'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $luminaria->eliminarLuminaria($_POST['id_luminaria']);
        $obj->id_luminaria = $_POST['id_luminaria'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));