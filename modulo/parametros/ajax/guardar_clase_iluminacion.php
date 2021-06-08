<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/ClaseIluminacion.php";
$claseIluminacion  = new ClaseIluminacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_clase_iluminacion = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $claseIluminacion->nuevoClaseIluminacion($_POST);
        if(!$result['estado']){
            $obj->id_clase_iluminacion = "";
            $obj->mensaje = "Error Guardando el Tipo de Identificacion ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_clase_iluminacion = $result['data'];
            $obj->mensaje = "Clase Iluminacion Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $claseIluminacion->editarClaseIluminacion($_POST);
        $obj->id_clase_iluminacion = $_POST['id_clase_iluminacion'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $claseIluminacion->eliminarClaseIluminacion($_POST['id_clase_iluminacion']);
        $obj->id_clase_iluminacion = $_POST['id_clase_iluminacion'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));