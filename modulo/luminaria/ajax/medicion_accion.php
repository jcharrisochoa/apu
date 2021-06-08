<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/MedicionLuminaria.php";
$medicion  = new MedicionLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_medicion= "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $medicion->nuevaMedicion($_POST);
        if(!$result['estado']){
            $obj->id_medicion = "";
            $obj->mensaje = "Error Guardando la medición ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_medicion = $result['data'];
            $obj->mensaje = "Dato Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    /*case "editar":
        $result = $luminaria->editarLuminaria($_POST);
        $obj->id_luminaria = $_POST['id_luminaria'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;*/
    case "eliminar":
        $result = $medicion->eliminarMedicion($_POST['id_medicion']);
        $obj->id_medicion = $_POST['id_medicion'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));