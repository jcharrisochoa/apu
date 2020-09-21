<?php
session_start();
$url = file_get_contents("../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../luminaria/clase/Luminaria.php";
$luminaria = new Luminaria($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $luminaria->buscarLuminaria($_POST['id_municipio'],$_POST['luminaria_no']);

$obj = new stdClass();

if(!$result){
    $obj->id_luminaria      = "";
    $obj->luminaria_no      = "";
    $obj->tipo_luminaria    = "";
    $obj->id_tipo_luminaria = "";
    $obj->poste_no          = "";
    $obj->direccion         = "";
    $obj->barrio            = "";
    $obj->id_barrio         = "";
    $obj->mensaje           = "Error consultando la luminaria ".$_POST['luminaria_no'];
    $obj->estado            = false;    
}
else{
    if($result->NumRows()==0){
        $obj->id_luminaria      = "";
        $obj->luminaria_no      = "";
        $obj->tipo_luminaria    = "";
        $obj->id_tipo_luminaria = "";
        $obj->poste_no          = "";
        $obj->direccion         = "";
        $obj->barrio            = "";
        $obj->id_barrio        = "";
        $obj->mensaje           = "No existe la luminaria ".$_POST['luminaria_no'];
        $obj->estado            = true;
    }
    else{
        $obj->id_luminaria      = $result->fields['id_luminaria'];
        $obj->luminaria_no      = $_POST['luminaria_no'];
        $obj->tipo_luminaria    = $result->fields['tipo_luminaria'];
        $obj->id_tipo_luminaria = $result->fields['id_tipo_luminaria'];
        $obj->poste_no          = $result->fields['poste_no'];
        $obj->direccion         = $result->fields['direccion'];
        $obj->barrio            = $result->fields['barrio'];
        $obj->id_barrio         = $result->fields['id_barrio'];
        $obj->mensaje           = "Luminaria encontrada";
        $obj->estado            = true;
    }
}
echo json_encode($obj);
