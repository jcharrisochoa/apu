<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Liquidacion.php";
require_once "../../global/global.php";
$liquidacion = new Liquidacion($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $liquidacion->listarLiquidacion($_POST);
$count = $liquidacion->contarLiquidacion($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"                  => $i, //$value['item'],
        "id"                    => $result->fields['id_liquidacion'],
        "id_municipio"          => $result->fields['id_municipio'],
        "municipio"             => $result->fields['municipio'],
        "periodo"               => $result->fields['periodo_liquidacion'],
        "mes"                   => $result->fields['mes_liquidacion'],
        "nombre_mes"            => strtoupper(nombreMeses($result->fields['mes_liquidacion'])),
        "fch_ini_facturacion"   => $result->fields['fch_ini_facturacion'],
        "fch_fin_facturacion"   => $result->fields['fch_fin_facturacion'],
        "consumo"               => $result->fields['consumo'],
        "valor_tarifa"          => $result->fields['valor_tarifa'],
        "valor_consumo"         => $result->fields['valor_consumo'],
        "facturado_ap"          => $result->fields['facturacion_impuesto_ap'],
        "recaudo_ap"            => $result->fields['recaudo_impuesto_ap'],
        "factura_energia"       => $result->fields['facturacion_energia'],
        "facturado_tsycc"       => $result->fields['facturacion_tsycc'],
        "recaudo_tsycc"         => $result->fields['recaudo_tsycc'],
        "fch_registro"          => $result->fields['fch_registro'],
        "registra"              => $result->fields['nombre'],
        "actualiza"             => $result->fields['actualiza'],
        "id_registra"           => $result->fields['id_tercero_registra'],
        "id_actualiza"          => $result->fields['id_tercero_actualiza'],
        "diferencia_facturacion"=> $result->fields['facturacion_impuesto_ap'] -  $result->fields['facturacion_tsycc'],
        "diferencia_recaudo"    => $result->fields['recaudo_impuesto_ap'] -  $result->fields['recaudo_tsycc']
        );                    
    $i++;
    $result->MoveNext();
}
if(count($lista)==0){
    $lista=array();
}
echo json_encode(array(
    "draw"            => intval( $_POST['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $count ),
    "data"            => $lista
));
?>