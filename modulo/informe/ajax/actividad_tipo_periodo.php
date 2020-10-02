<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../../actividad/clase/Actividad.php";
$actividad = new Actividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $actividad->totalActividadTipoPeriodo();

$lista = array();
$i=0;
while (!$result->EOF){
    $lista[$i]=array(                    
        "cantidad"        => $result->fields['cantidad'],
        "periodo"   => $result->fields['periodo'],
        "tipo"      => $result->fields['tipo']
        );                    
    $i++;
    $result->MoveNext();
}
echo json_encode($lista);