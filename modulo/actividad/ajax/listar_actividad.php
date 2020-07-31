<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Actividad.php";
$actividad = new Actividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $actividad->listarActividad($_POST);
$count = $actividad->contarActividad($_POST);
$i=0;
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i, //$value['item'],
        "municipio"     => $result->fields['municipio'],
        "id_actividad"  => $result->fields['id_actividad'],
        "tipo"          => $result->fields['tipo'],
        "barrio"        => $result->fields['barrio'],
        "direccion"     => $result->fields['direccion'],
        "fch_actividad" => $result->fields['fch_actividad'],
        "poste_no"      => $result->fields['poste_no'],
        "luminaria_no"  => $result->fields['luminaria_no'],
        "tecnico"       => $result->fields['tecnico'],
        "fch_reporte"   => $result->fields['fch_reporte'],
        "tipo_reporte"  => $result->fields['tipo_reporte'],
        "estado_actividad"  => $result->fields['estado_actividad'],
        "observacion"  => $result->fields['observacion'],
        "tipo_luminaria"  => $result->fields['tipo_luminaria']

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