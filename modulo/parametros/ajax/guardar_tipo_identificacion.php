<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/TipoIdentificacion.php";
$tipoIdentificacion  = new TipoIdentificacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_tipo_identificacion = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $tipoIdentificacion->nuevoTipoIdentificacion($_POST);
        if(!$result['estado']){
            $obj->id_tipo_identificacion = "";
            $obj->mensaje = "Error Guardando el Tipo de Identificacion ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_tipo_identificacion = $result['data'];
            $obj->mensaje = "Tipo Identificacion Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $tipoIdentificacion->editarTipoIdentificacion($_POST);
        $obj->id_tipo_identificacion = $_POST['id_tipo_identificacion'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $tipoIdentificacion->eliminarTipoIdentificacion($_POST['id_tipo_identificacion']);
        $obj->id_tipo_identificacion = $_POST['id_tipo_identificacion'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));