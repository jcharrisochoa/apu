<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/MedicionLuminaria.php";
$medicion = new MedicionLuminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $medicion->listarMedicionLuminaria($_POST);
$count = $medicion->contarMedicionLuminaria($_POST);
$i=0; 
$lista = array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"              => $i, //$value['item'],
        "clase_iluminacion" => $result->fields['clase_iluminacion'],
        "fch_visita"        => $result->fields['fch_visita'],
        "hm"                => $result->fields['hm'],
        "sm"                => $result->fields['sm'],
        "wm"                => $result->fields['wm'],
        "ilum_lux"          => $result->fields['ilum_lux'],
        "uniformidad"       => $result->fields['uniformidad'],
        "cumple_retilap"    => ($result->fields['cumple_retilap']=="S")?"SI":"NO",
        "id_clase_iluminacion" => $result->fields['id_clase_iluminacion'],
        "id_medicion" => $result->fields['id_medicion']
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