<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Encuesta.php";
$encuesta = new Encuesta($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $encuesta->listarEncuesta($_POST);
$count = $encuesta->contarEncuesta($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i+1,
        "municipio"     => $result->fields['municipio'],
        "nombre"        => $result->fields['nombre_usuario_servicio'],
        "barrio"        => $result->fields['barrio'],
        "direccion"     => $result->fields['direccion'],
        "calidad"       => $result->fields['calidad_servicio'],
        "tiempo"        => $result->fields['tiempo_atencion'],
        "atencion"      => $result->fields['atencion_grupo_trabajo'],
        "fch_encuesta"          => $result->fields['fch_encuesta'],
        "usuario"               => $result->fields['usuario'],
        "fch_registro"          => $result->fields['fch_registro'],
        "id_municipio"          => $result->fields['id_municipio'],
        "id_barrio"             => $result->fields['id_barrio'],
        "id_tercero_registra"   => $result->fields['id_tercero_registra'],
        "id_usuario_servicio"   => $result->fields['id_usuario_servicio'],
        "id_encuesta"           => $result->fields['id_encuesta'],
        "id_tipo_identificacion"    => $result->fields['id_tipo_identificacion'],
        "identificacion"            => $result->fields['identificacion'],
        "comentario"                => $result->fields['comentario'],
        "telefono"                  => $result->fields['telefono'],
        "email"                     => $result->fields['correo_electronico'],
        "abreviatura"               => $result->fields['abreviatura'],
        "fch_actualiza"             => $result->fields['fch_actualiza'],
        "usuario_actualiza"         => $result->fields['usuario_actualiza']
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