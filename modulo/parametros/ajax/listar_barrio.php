<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Barrio.php";
$barrio = new Barrio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $barrio->tablaBarrio($_POST);
$count = $barrio->contarBarrio($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i+1,
        "municipio"     => $result->fields['municipio'],
        "barrio"        => $result->fields['barrio'],
        "id_municipio"  => $result->fields['id_municipio'],
        "id_barrio"     => $result->fields['id_barrio']
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