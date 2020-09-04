<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Municipio.php";
$municipio  = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_municipio = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $municipio->nuevoMunicipio($_POST);
        if(!$result['estado']){
            $obj->id_municipio = "";
            $obj->mensaje = "Error guardando el Municipio ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_municipio = $result['data'];
            $obj->mensaje = "Municipio Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $municipio->editarMunicipio($_POST);
        $obj->id_municipio = $_POST['id_municipio'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $municipio->eliminarMunicipio($_POST['id_municipio']);
        $obj->id_municipio = $_POST['id_municipio'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));