<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/EstadoPQR.php";
$estadoPQR  = new EstadoPQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_estado_pqr = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $estadoPQR->nuevoEstadoPQR($_POST);
        if(!$result['estado']){
            $obj->id_estado_pqr = "";
            $obj->mensaje = "Error guardando Estado PQR ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_estado_pqr = $result['data'];
            $obj->mensaje = "Estado PQR Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $estadoPQR->editarEstadoPQR($_POST);
        $obj->id_estado_pqr = $_POST['id_estado_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $estadoPQR->eliminarEstadoPQR($_POST['id_estado_pqr']);
        $obj->id_estado_pqr = $_POST['id_estado_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));