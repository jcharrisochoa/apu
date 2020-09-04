<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../../luminaria/clase/Luminaria.php";
$objLum = new Luminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $objLum->listarLuminaria($_POST);
while (!$result->EOF){
    $lista[]=array(                    
        "id_luminaria"  => $result->fields['id_luminaria'],
        "municipio"     => $result->fields['municipio'],
        "poste_no"      => $result->fields['poste_no'],
        "luminaria_no"  => $result->fields['luminaria_no'],
        "tipo"          => $result->fields['tipo'],
        "barrio"        => $result->fields['barrio'],
        "direccion"     => $result->fields['direccion'],
        "latitud"       => $result->fields['longitud'],
        "longitud"      => $result->fields['latitud'],
        "fch_instalacion"  => $result->fields['fch_instalacion'],
        "fch_registro"  => $result->fields['fch_registro'],
        "usuario"       => $result->fields['usuario'],
        "estado"        => $result->fields['estado'],
        "proveedor"     => $result->fields['proveedor']
        );                    
    $result->MoveNext();
}
if(count($lista)==0){
    $lista=array();
}
echo json_encode(array("puntos" => $lista));
?>