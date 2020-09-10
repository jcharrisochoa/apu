<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->listarComentarioPQR($_POST['id_pqr']);
$data=array();
while(!$result->EOF){
        $data[] = array(
                "usuario"        => $result->fields['nombre'],
                "fch_registro"   => $result->fields['fch_registro'],
                "comentario"     => $result->fields['comentario']
        );
        $result->MoveNext();
}

echo json_encode($data);