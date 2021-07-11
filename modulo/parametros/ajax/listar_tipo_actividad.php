<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/TipoActividad.php";
$tipoActividad = new TipoActividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $tipoActividad->tablaTipoActividad($_POST);
$count = $tipoActividad->contarTipoActividad($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i+1,
        "descripcion"     => $result->fields['descripcion'],
        "id_tipo_actividad"   => $result->fields['id_tipo_actividad'],
        "instalacion"   => $result->fields['instalacion'],
        "preventivo"   => $result->fields['preventivo'],
        "correctivo"   => $result->fields['correctivo'],
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