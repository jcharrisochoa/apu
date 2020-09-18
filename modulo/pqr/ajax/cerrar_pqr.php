<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->cerrarPQR($_POST['id_pqr'],$_SESSION['id_tercero']);
echo json_encode($result);
