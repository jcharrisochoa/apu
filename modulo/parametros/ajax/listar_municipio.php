<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Municipio.php";
$municipio = new Municipio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $municipio->tablaMunicipio($_POST);
$count = $municipio->contarMunicipio($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i+1,
        "departamento"     => $result->fields['departamento'],
        "municipio"     => $result->fields['municipio'],
        "tiene_contrato"     => $result->fields['tiene_contrato'],
        "latitud"   => $result->fields['latitud'],
        "longitud"   => $result->fields['longitud'],
        "id_municipio"   => $result->fields['id_municipio'],
        "id_departamento"   => $result->fields['id_departamento']
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