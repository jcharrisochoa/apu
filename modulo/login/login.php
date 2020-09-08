<?php
session_start();
include("../../libreria/adodb/adodb.inc.php");
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "clase/Login.php";
$login = new Login($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

# Response Data Array
$resp = array();
// This array of data is returned for demo purpose, see assets/js/neon-forgotpassword.js
//$resp['submitted_data'] = $_POST;

$result = $login->login($_POST["username"],$_POST["password"]);
if(!$result['estado']){
	$resp['login_status']  = 'error';
	$resp['login_message'] = $result['mensaje'];
}
else{
	if($result['data']->NumRows()==0){
		$resp['login_status']  = 'invalid';
		$resp['login_message'] = "";
	}
	else{
		$resp['login_status'] 	= 'success';	
		$resp['login_message'] 	= "";
		$resp['redirect_url'] 	= 'modulo/index.php';

		$_SESSION['id_tercero'] = $result['data']->fields['id_tercero'];
		$_SESSION['usuario'] 	= $result['data']->fields['usuario'];
		$_SESSION['nombre'] 	= $result['data']->fields['nombre'];
		$_SESSION['apellido'] 	= $result['data']->fields['apellido'];

	}
}
echo json_encode($resp);