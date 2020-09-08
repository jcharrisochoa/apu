<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/PQR.php";
$pqr  = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_pqr = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $pqr->nuevaPQR($_POST,$_FILES);
        if(!$result['estado']){
            $obj->id_pqr = "";
            $obj->mensaje = "Error guardando la pqr ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_pqr = $result['data'];
            $obj->mensaje = "PQR Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $pqr->editarPQR($_POST,$_FILES);
        $obj->id_pqr = $_POST['id_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $pqr->eliminarPQR($_POST['id_pqr']);
        $obj->id_pqr = $_POST['id_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));