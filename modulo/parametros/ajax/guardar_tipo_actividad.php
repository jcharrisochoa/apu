<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/TipoActividad.php";
$tipoActividad  = new TipoActividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_tipo_actividad = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $tipoActividad->nuevoTipoActividad($_POST);
        if(!$result['estado']){
            $obj->id_tipo_actividad = "";
            $obj->mensaje = "Error Guardando el Tipo de Actividad ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_tipo_actividad = $result['data'];
            $obj->mensaje = "Tipo Actividad Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $tipoActividad->editarTipoActividad($_POST);
        $obj->id_tipo_actividad = $_POST['id_tipo_actividad'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $tipoActividad->eliminarTipoActividad($_POST['id_tipo_actividad']);
        $obj->id_tipo_actividad = $_POST['id_tipo_actividad'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));