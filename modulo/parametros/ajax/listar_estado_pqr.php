<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/EstadoPQR.php";
$estadoPQR = new EstadoPQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $estadoPQR->tablaEstadoPQR($_POST);
$count = $estadoPQR->contarEstadoPQR($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"                  => $i+1,
        "descripcion"           => $result->fields['descripcion'],
        "permitir_edicion"      => $result->fields['permitir_edicion'],
        "permitir_eliminar"     => $result->fields['permitir_eliminar'],
        "id_estado_pqr"         => $result->fields['id_estado_pqr']
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