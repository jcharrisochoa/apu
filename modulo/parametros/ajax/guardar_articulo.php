<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/Articulo.php";
$articulo  = new Articulo($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_articulo = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $articulo->nuevoArticulo($_POST);
        if(!$result['estado']){
            $obj->id_articulo = "";
            $obj->mensaje = "Error guardando el Servicio ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_articulo = $result['data'];
            $obj->mensaje = "Servicio Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $articulo->editarArticulo($_POST);
        $obj->id_articulo = $_POST['id_articulo'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $articulo->eliminarArticulo($_POST['id_articulo']);
        $obj->id_articulo = $_POST['id_articulo'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));