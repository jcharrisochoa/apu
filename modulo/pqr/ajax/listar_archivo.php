<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->listarArchivoPQR($_POST['id_pqr']);
$data=array();
while (!$result->EOF){
    $data[] = array(
        "id_archivo_pqr"=> $result->fields['id_archivo_pqr'],
        "nombre_archivo"=> str_replace(' ','',$result->fields['nombre_archivo']),
        "tipo"          => $result->fields['tipo'],
        "fch_registro"  => $result->fields['fch_registro'],
        "usuario"       => $result->fields['usuario']
    );

    $result->MoveNext();
}
echo json_encode($data);