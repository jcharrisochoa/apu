<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Login.php";
$login = new Login($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $login->cambiarClaveUsuario($_SESSION['id_tercero'],$_POST['clave']);
echo json_encode($result);