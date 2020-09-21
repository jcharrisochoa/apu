<?php
session_start();
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../pqr/clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->buscarPQR($_POST['id_municipio'],$_POST['id_pqr']);

$obj = new stdClass();

if(!$result){
    $obj->id_pqr                = "";
    $obj->fch_pqr               = "";
    $obj->tipo_pqr              = "";
    $obj->tipo_reporte          = "";
    $obj->id_luminaria          = "";
    $obj->luminaria_no          = "";
    $obj->tipo_luminaria        = "";
    $obj->id_tipo_luminaria     = "";
    $obj->poste_no              = "";
    $obj->direccion             = "";
    $obj->barrio                = "";
    $obj->id_barrio             = "";
    $obj->mensaje               = "Error consultando la pqr ".$_POST['id_pqr'];
    $obj->estado                = false;    
}
else{
    if($result->NumRows()==0){
        $obj->id_pqr            = "";
        $obj->fch_pqr           = "";
        $obj->tipo_pqr          = "";
        $obj->tipo_reporte      = "";
        $obj->id_luminaria      = "";
        $obj->luminaria_no      = "";
        $obj->tipo_luminaria    = "";
        $obj->id_tipo_luminaria    = "";
        $obj->poste_no          = "";
        $obj->direccion         = "";
        $obj->barrio            = "";
        $obj->id_barrio         = "";
        $obj->mensaje           = "No existe la pqr ".$_POST['id_pqr'];
        $obj->estado            = true;
    }
    else{
        $obj->id_pqr            = $result->fields['id_pqr'];
        $obj->fch_pqr           = $result->fields['fch_pqr'];;
        $obj->tipo_pqr          = $result->fields['tipo_pqr'];
        $obj->tipo_reporte      = $result->fields['tipo_reporte'];
        $obj->id_luminaria      = $result->fields['id_luminaria'];
        $obj->luminaria_no      = $result->fields['luminaria_no'];
        $obj->tipo_luminaria    = $result->fields['tipo_luminaria'];
        $obj->id_tipo_luminaria    = $result->fields['id_tipo_luminaria'];
        $obj->poste_no          = $result->fields['poste_no'];
        $obj->direccion         = $result->fields['direccion'];
        $obj->barrio            = $result->fields['barrio'];
        $obj->id_barrio         = $result->fields['id_barrio'];
        $obj->mensaje           = "PQR encontrada";
        $obj->estado            = true;
    }
}
echo json_encode($obj);
