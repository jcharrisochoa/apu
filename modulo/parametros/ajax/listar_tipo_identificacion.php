<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/TipoIdentificacion.php";
$tipoIdentificacion = new TipoIdentificacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $tipoIdentificacion->tablaTipoIdentificacion($_POST);
$count = $tipoIdentificacion->contarTipoIdentificacion($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i+1,
        "descripcion"     => $result->fields['descripcion'],
        "abreviatura"     => $result->fields['abreviatura'],
        "id_tipo_identificacion"   => $result->fields['id_tipo_identificacion']
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