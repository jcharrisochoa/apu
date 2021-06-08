<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../../pqr/clase/Encuesta.php";
$encuesta  = new Encuesta($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$objAtencion = new stdClass();
$objAtencion->data = "";
$objAtencion->mensaje = "";
$objAtencion->estado = true;
$data=array();
$result = $encuesta->grafica($_POST,"A");
if(!$result){

    $objAtencion->data=array();
    $objAtencion->mensaje = "Error consultando los datos de las encuesta";
    $objAtencion->estado = false;;
}
else{
    while(!$result->EOF){
        $serie = new stdClass();
        $serie->cantidad = $result->fields['cantidad'];
        $serie->calificacion = $result->fields['calificacion'];
        $serie->descripcion = $encuesta->calificacion($result->fields['calificacion']);
        $serie->porcentaje = $result->fields['porcentaje'];

        $data[] = $serie;
        $result->MoveNext();
    }
    $objAtencion->data = $data;
    $objAtencion->mensaje = "ok";
    $objAtencion->estado = true;
}

$objCalidad = new stdClass();
$objCalidad->data = "";
$objCalidad->mensaje = "";
$objCalidad->estado = true;
$data=array();
$result = $encuesta->grafica($_POST,"C");
if(!$result){

    $objCalidad->data=array();
    $objCalidad->mensaje = "Error consultando los datos de las encuesta";
    $objCalidad->estado = false;;
}
else{
    while(!$result->EOF){
        $serie = new stdClass();
        $serie->cantidad = $result->fields['cantidad'];
        $serie->calificacion = $result->fields['calificacion'];
        $serie->descripcion = $encuesta->calificacion($result->fields['calificacion']);
        $serie->porcentaje = $result->fields['porcentaje'];

        $data[] = $serie;
        $result->MoveNext();
    }
    $objCalidad->data = $data;
    $objCalidad->mensaje = "ok";
    $objCalidad->estado = true;
}


$objTiempo = new stdClass();
$objTiempo->data = "";
$objTiempo->mensaje = "";
$objTiempo->estado = true;
$data=array();
$result = $encuesta->grafica($_POST,"T");
if(!$result){

    $objTiempo->data=array();
    $objTiempo->mensaje = "Error consultando los datos de las encuesta";
    $objTiempo->estado = false;;
}
else{
    while(!$result->EOF){
        $serie = new stdClass();
        $serie->cantidad = $result->fields['cantidad'];
        $serie->calificacion = $result->fields['calificacion'];
        $serie->descripcion = $encuesta->calificacion($result->fields['calificacion']);
        $serie->porcentaje = $result->fields['porcentaje'];

        $data[] = $serie;
        $result->MoveNext();
    }
    $objTiempo->data = $data;
    $objTiempo->mensaje = "ok";
    $objTiempo->estado = true;
}

$a = new stdClass();
$a->atencion=$objAtencion;
$a->calidad=$objCalidad;
$a->tiempo=$objTiempo;

echo json_encode(array("response"=>$a));