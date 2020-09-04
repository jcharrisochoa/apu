<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/MedioRecepcionPQR.php";
$medioRecepcionPQR  = new MedioRecepcionPQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_medio_recepcion_pqr = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $medioRecepcionPQR->nuevoMedioRecepcionPQR($_POST);
        if(!$result['estado']){
            $obj->id_medio_recepcion_pqr = "";
            $obj->mensaje = "Error guardando Medio Recepcion PQR ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_medio_recepcion_pqr = $result['data'];
            $obj->mensaje = "Medio Recepcion PQR Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $medioRecepcionPQR->editarMedioRecepcionPQR($_POST);
        $obj->id_medio_recepcion_pqr = $_POST['id_medio_recepcion_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $medioRecepcionPQR->eliminarMedioRecepcionPQR($_POST['id_medio_recepcion_pqr']);
        $obj->id_medio_recepcion_pqr = $_POST['id_medio_recepcion_pqr'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));