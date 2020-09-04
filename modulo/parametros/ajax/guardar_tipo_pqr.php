<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/TipoPQR.php";
$tipoPQR  = new TipoPQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_tipo_pqr = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $tipoPQR->nuevoTipoPQR($_POST);
        if(!$result['estado']){
            $obj->id_tipo_pqr = "";
            $obj->mensaje = "Error guardando Tipo PQR ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_tipo_pqr = $result['data'];
            $obj->mensaje = "Tipo PQR Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $tipoPQR->editarTipoPQR($_POST);
        $obj->id_tipo_pqr = $_POST['id_tipo_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $tipoPQR->eliminarTipoPQR($_POST['id_tipo_pqr']);
        $obj->id_tipo_pqr = $_POST['id_tipo_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));