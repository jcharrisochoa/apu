<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/EstadoLuminaria.php";
$estadoLuminaria = new EstadoLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $estadoLuminaria->tablaEstadoLuminaria($_POST);
$count = $estadoLuminaria->contarEstadoLuminaria($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i+1,
        "descripcion"     => $result->fields['descripcion'],
        "id_estado_luminaria"   => $result->fields['id_estado_luminaria']
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