<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Liquidacion.php";
$liquidacion  = new Liquidacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_liquidacion = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $liquidacion->nuevaLiquidacion($_POST);
        if(!$result['estado']){
            $obj->id_liquidacion = "";
            $obj->mensaje = "Error Guardando la liquidacion ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_liquidacion = $result['data'];
            $obj->mensaje = "Liquidacion Registrada";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $liquidacion->editarLiquidacion($_POST);
        $obj->id_liquidacion = $_POST['id_liquidacion'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $liquidacion->eliminarLiquidacion($_POST['id_liquidacion']);
        $obj->id_liquidacion = $_POST['id_liquidacion'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));