<?php
session_start();
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../luminaria/clase/Luminaria.php";
$luminaria = new Luminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $luminaria->listarLuminaria($_POST);
$count = $luminaria->contarLuminaria($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i+1, //$value['item'],
        "municipio"     => $result->fields['municipio'],
        "tipo"          => $result->fields['tipo'],
        "poste_no"      => $result->fields['poste_no'],
        "luminaria_no"  => $result->fields['luminaria_no'],
        "barrio"        => $result->fields['barrio'],
        "direccion"     => $result->fields['direccion'],
        "id_luminaria"  => $result->fields['id_luminaria'],
        //"id_municipio"          => $result->fields['id_municipio'],
        "id_barrio"             => $result->fields['id_barrio']
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