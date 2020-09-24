<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Actividad.php";
$actividad = new Actividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $actividad->listarServicioActividad($_POST);
$count = $actividad->contarServicioActividad($_POST);
$i=0; 
$lista = array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i,
        "codigo"        => $result->fields['id_articulo'],
        "descripcion"   => $result->fields['descripcion'],
        "cantidad"      => $result->fields['cantidad']
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