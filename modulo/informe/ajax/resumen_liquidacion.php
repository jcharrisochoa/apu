
<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../../liquidacion/clase/Liquidacion.php";
require_once "../../global/global.php";

$liquidacion  = new Liquidacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);

$objHistorico = new stdClass();
$objHistorico->data = "";
$objHistorico->mensaje = "";
$objHistorico->estado = true;
$data=array();
$result = $liquidacion->resumen($_POST);
if(!$result){

    $objHistorico->data=array();
    $objHistorico->mensaje = "Error Consultando las liquidaciones";
    $objHistorico->estado = false;
}
else{
    while(!$result->EOF){
        $item = new stdClass();
        $item->id_municipio         = $result->fields['id_municipio'];
        $item->municipio           = $result->fields['municipio'];
        $item->facturacion_ap      = $result->fields['facturacion_ap'];
        $item->facturacion_tsycc   = $result->fields['facturacion_tsycc'];
        $item->recaudo_ap          = $result->fields['recaudo_ap'];
        $item->recaudo_tsycc       = $result->fields['recaudo_tsycc'];

        $data[] = $item;
        $result->MoveNext();
    }
    $objHistorico->data = $data;
    $objHistorico->mensaje = "ok";
    $objHistorico->estado = true;
}
echo json_encode($objHistorico);