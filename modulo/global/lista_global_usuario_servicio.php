<?php
session_start();
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../parametros/clase/UsuarioServicio.php";
$luminaria = new UsuarioServicio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $luminaria->tablaUsuarioServicio($_POST);
$count = $luminaria->contarUsuarioServicio($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"                      => $i+1,
        "abreviatura"       => $result->fields['abreviatura'],
        "identificacion"            => $result->fields['identificacion'],
        "nombre"                    => $result->fields['nombre'],
        "direccion"                 => $result->fields['direccion'],
        "telefono"                  => $result->fields['telefono'],
        "id_usuario_servicio"       => $result->fields['id_usuario_servicio'],
        "id_tipo_identificacion"        => $result->fields['id_tipo_identificacion']
        );                    
    $i++;
    $result->MoveNext();
}
if(count($lista)==0){
    $lista=array();
}
echo json_encode(array(
    "draw"            => intval( $_POST['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $count ),
    "data"            => $lista
));
?>