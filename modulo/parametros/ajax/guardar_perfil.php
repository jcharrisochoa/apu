<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Menu.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "agregar":
        $result = $menu->agregarPerfil($_POST);
        if(!$result['estado']){
            $obj->mensaje = "Error agregando el perfil ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->mensaje = "Perfil agregado";
            $obj->estado = $result['estado'];
        }
        break;
    case "retirar":
        $result = $menu->retirarPerfil($_POST);
        if(!$result['estado']){
            $obj->mensaje = "Error retirando el perfil ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->mensaje = "Perfil Retirado";
            $obj->estado = $result['estado'];
        }
        break;
}
echo json_encode(array("response"=>$obj));