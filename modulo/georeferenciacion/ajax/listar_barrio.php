<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../../parametros/clase/Barrio.php";
$objBarrio = new Barrio($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

if(!empty($_POST['id_municipio'])){
    $barrio = $objBarrio->listarBarrioMunicipio($_POST['id_municipio']);
    $data[] = array("id_barrio"=> "","descripcion"   => "-Seleccione-");
    while(!$barrio->EOF){
        $data[] = array(
            "id_barrio"     => $barrio->fields['id_barrio'],
            "descripcion"   => strtoupper($barrio->fields['descripcion'])
        );
        $barrio->MoveNext();
    }
}
else{
    $data[] = array(
        "id_barrio"=> "",
        "descripcion"  => "-Seleccione-")
    ;
}
echo json_encode(array("lista"=>$data));