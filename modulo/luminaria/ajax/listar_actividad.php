<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../../actividad/clase/Actividad.php";
$actividad = new Actividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $actividad->listarActividad($_POST);
$count = $actividad->contarActividad($_POST);
$i=0;
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"          => $i, //$value['item'],
        /*"municipio"     => $result->fields['municipio'],*/
        "codigo"  => $result->fields['id_actividad'],
        "tipo"          => $result->fields['tipo'],
        "descripcion"  => $result->fields['observacion'],
        "direccion"     => $result->fields['direccion'],
        "fch_reclamo"   => $result->fields['fch_reporte'],
        "fch_ejecucion" => $result->fields['fch_actividad'],
        "tecnico"       => $result->fields['tecnico'],
        "estado_actividad"  => $result->fields['estado_actividad']
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