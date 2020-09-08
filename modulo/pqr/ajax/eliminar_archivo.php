<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->eliminarArchivo($_POST['id_archivo_pqr']);
$response = array();
if(!$result){
    $response = array("estado"=>false,"mensaje"=>"Error eliminando el archivo.");
}
else{
    $response = array("estado"=>true,"mensaje"=>"Archivo Eliminado.");
}
echo json_encode($response);