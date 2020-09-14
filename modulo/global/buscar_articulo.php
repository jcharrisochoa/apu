<?php
session_start();
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/Articulo.php";
$articulo = new Articulo($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $articulo->buscarArticulo($_POST['id_articulo']);

$obj = new stdClass();

if(!$result){
    $obj->id_articulo = "";
    $obj->descripcion = "";
    $obj->clase = "";
    $obj->mensaje = "Error consultando el art&iacute;culo o servicio";
    $obj->estado = false;    
}
else{
    if($result->NumRows()==0){
        $obj->id_articulo = "";
        $obj->descripcion = "";
        $obj->clase = "";
        $obj->mensaje = "No existe el art&iacute;culo o servicio con c&oacute;digo ".$_POST['id_articulo'];
        $obj->estado = true;
    }
    else{
        $obj->id_articulo = $result->fields['id_articulo'];
        $obj->descripcion = $result->fields['descripcion'];
        $obj->clase = $result->fields['clase'];
        $obj->mensaje = "Art&iacute;culo o servicio encontrado";
        $obj->estado = true;
    }
}
echo json_encode($obj);
