<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../clase/PQR.php";
$objPQR  = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$obj = new stdClass();
$result = $objPQR->buscarUsuarioServicio($_POST['identificacion']);

if(!$result){
    $obj->id_usuario_servicio = "";
    $obj->id_tipo_identificacion = "";
    $obj->nombre = "";
    $obj->direccion = "";
    $obj->telefono = "";
    $obj->email = "";
    $obj->mensaje = "Error consultando el Usuario";
    $obj->estado = false;    
}
else{
    if($result->NumRows()==0){
        $obj->id_usuario_servicio = "";
        $obj->id_tipo_identificacion = "";
        $obj->nombre = "";
        $obj->direccion = "";
        $obj->telefono = "";
        $obj->email = "";
        $obj->mensaje = "No existe el Usuario con identificacion ".$_POST['identificacion'];
        $obj->estado = true;
    }
    else{
        $obj->id_usuario_servicio = $result->fields['id_usuario_servicio'];
        $obj->id_tipo_identificacion = $result->fields['id_tipo_identificacion'];
        $obj->nombre = $result->fields['nombre'];
        $obj->direccion = $result->fields['direccion'];
        $obj->telefono = $result->fields['telefono'];
        $obj->email = $result->fields['email'];
        $obj->mensaje = "Usuario encontrado";
        $obj->estado = true;
    }
}
echo json_encode($obj);