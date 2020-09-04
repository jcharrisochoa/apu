<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/TipoReporte.php";
$tipoReporte  = new TipoReporte($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();

$obj->id_tipo_reporte = "";
$obj->mensaje = "";
$obj->estado = true;

switch ($_POST['accion']){
    case "nuevo":
        $result = $tipoReporte->nuevoTipoReporte($_POST);
        if(!$result['estado']){
            $obj->id_tipo_reporte = "";
            $obj->mensaje = "Error Guardando el tipo de reporte ".$result['data'];
            $obj->estado = $result['estado'];
        }
        else{
            $obj->id_tipo_reporte = $result['data'];
            $obj->mensaje = "Tipo Reporte Registrado";
            $obj->estado = $result['estado'];
        }
        break;
    case "editar":
        $result = $tipoReporte->editarTipoReporte($_POST);
        $obj->id_tipo_reporte = $_POST['id_tipo_reporte'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
    case "eliminar":
        $result = $tipoReporte->eliminarTipoReporte($_POST['id_tipo_reporte']);
        $obj->id_tipo_reporte = $_POST['id_tipo_reporte'];
        $obj->mensaje = $result['data'];
        $obj->estado = $result['estado'];
        break;
}
echo json_encode(array("response"=>$obj));