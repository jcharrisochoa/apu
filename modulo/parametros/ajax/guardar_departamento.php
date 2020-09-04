<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Departamento.php";
$departamento  = new Departamento($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_departamento = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $departamento->nuevoDepartamento($_POST);
        if(!$result['estado']){
            $obj->id_departamento = "";
            $obj->mensaje = "Error guardando el departamento ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_departamento = $result['data'];
            $obj->mensaje = "Departamento Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $departamento->editarDepartamento($_POST);
        $obj->id_departamento = $_POST['id_departamento'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $departamento->eliminarDepartamento($_POST['id_departamento']);
        $obj->id_departamento = $_POST['id_departamento'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));