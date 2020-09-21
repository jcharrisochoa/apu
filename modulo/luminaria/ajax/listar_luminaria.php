<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Luminaria.php";
$luminaria = new Luminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $luminaria->listarLuminaria($_POST);
$count = $luminaria->contarLuminaria($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i, //$value['item'],
        "municipio"     => $result->fields['municipio'],
        "poste_no"      => $result->fields['poste_no'],
        "luminaria_no"  => $result->fields['luminaria_no'],
        "tipo"          => $result->fields['tipo'],
        "barrio"        => $result->fields['barrio'],
        "direccion"     => $result->fields['direccion'],
        "latitud"       => $result->fields['longitud'],
        "longitud"      => $result->fields['latitud'],
        "id_luminaria"  => $result->fields['id_luminaria'],
        "fch_instalacion"  => $result->fields['fch_instalacion'],
        "fch_registro"  => $result->fields['fch_registro'],
        "usuario"       => $result->fields['usuario'],
        "estado"        => $result->fields['estado'],
        "proveedor"     => $result->fields['proveedor'],
        "id_municipio"          => $result->fields['id_municipio'],
        "id_barrio"             => $result->fields['id_barrio'],
        "id_tercero_proveedor"  => $result->fields['id_tercero_proveedor'],
        "id_estado_luminaria"   => $result->fields['id_estado_luminaria'],
        "id_tipo_luminaria"     => $result->fields['id_tipo_luminaria'],
        "id_tercero"            => $result->fields['id_tercero']

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