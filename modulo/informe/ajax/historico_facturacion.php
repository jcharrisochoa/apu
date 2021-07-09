
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
$result = $liquidacion->historicoFacturacion($_POST);
if(!$result){

    $objHistorico->data=array();
    $objHistorico->mensaje = "Error Consultando el historico de consumo";
    $objHistorico->estado = false;
}
else{
    while(!$result->EOF){
        $serie = new stdClass();
        $serie->mes             = $result->fields['mes_liquidacion'];
        $serie->nombre_mes      = substr((nombreMeses($result->fields['mes_liquidacion'])),0,3);
        $serie->consumo         = $result->fields['consumo'];
        $serie->valor_consumo   = $result->fields['valor_consumo'];
        $serie->factura_energia = number_format($result->fields['factura_energia'],2,".",",");
        $serie->facturacion_ap  = $result->fields['facturacion_ap'];
        $serie->recaudo_ap      = $result->fields['recaudo_ap'];

        $data[] = $serie;
        $result->MoveNext();
    }
    $objHistorico->data = $data;
    $objHistorico->mensaje = "ok";
    $objHistorico->estado = true;
}
echo json_encode($objHistorico);