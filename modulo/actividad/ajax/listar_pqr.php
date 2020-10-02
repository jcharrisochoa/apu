<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../../pqr/clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->listarPQR($_POST);
$count = $pqr->contarPQR($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"                  => $i+1,
        "id_pqr"                => $result->fields['id_pqr'],
        "nombre"                => $result->fields['nombre_usuario_servicio'],
        "direccion"             => $result->fields['direccion_reporte'],
        "barrio"                => $result->fields['barrio_reporte'],
        "tipo_reporte"          => $result->fields['tipo_reporte'],
        "tipo_pqr"              => $result->fields['tipo_pqr'],
        "fecha_reporte"         => $result->fields['fch_pqr'],
        "estado"                => $result->fields['estado'],
        "id_luminaria"          => $result->fields['id_luminaria'],
        "luminaria_no"          => $result->fields['luminaria_no'],
        "poste_no"              => $result->fields['poste_no'],
        "id_tipo_luminaria"     => $result->fields['id_tipo_luminaria'],
        "id_barrio_luminaria"   => $result->fields['id_barrio_luminaria'],
        "direccion_luminaria"   => $result->fields['direccion_luminaria']
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