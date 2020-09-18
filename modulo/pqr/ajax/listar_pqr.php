<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->listarPQR($_POST);
$count = $pqr->contarPQR($_POST);
$i=0;
$lista=array();
while (!$result->EOF){
    $lista[$i]=array(                    
        "item"              => $i+1, //$value['item'],
        "id_pqr"            => $result->fields['id_pqr'],
        "municipio"         => $result->fields['municipio'],
        "tipo_pqr"          => $result->fields['tipo_pqr'],
        "tipo_reporte"      => $result->fields['tipo_reporte'],
        "nombre"            => $result->fields['nombre_usuario_servicio'],
        "fch_pqr"           => $result->fields['fch_pqr'],
        "direccion_reporte"     => $result->fields['direccion_reporte'],
        "barrio_reporte"        => $result->fields['barrio_reporte'],
        "usuario"               => $result->fields['usuario'],
        "estado"                => $result->fields['estado'],
        "id_luminaria"          => $result->fields['id_luminaria'],
        "poste_no"              => $result->fields['poste_no'],
        "luminaria_no"          => $result->fields['luminaria_no'],
        "id_municipio"          => $result->fields['id_municipio'],
        "id_tipo_pqr"           => $result->fields['id_tipo_pqr'],
        "id_tipo_reporte"       => $result->fields['id_tipo_reporte'],
        "id_medio_recepcion_pqr"    => $result->fields['id_medio_recepcion_pqr'],
        "id_estado_pqr"         => $result->fields['id_estado_pqr'],
        "id_usuario_servicio"   => $result->fields['id_usuario_servicio'],
        "id_tipo_identificacion"=> $result->fields['id_tipo_identificacion'],
        "tipo_identificacion"   => $result->fields['abreviatura'],
        "identificacion"        => $result->fields['identificacion'],
        "direccion"             => $result->fields['direccion_usuario_servicio'],
        "telefono"              => $result->fields['telefono_usuario_servicio'],
        "email"                 => $result->fields['email'],
        "comentario"            => $result->fields['comentario'],
        "permitir_edicion"      => $result->fields['permitir_edicion'],
        "permitir_eliminar"     => $result->fields['permitir_eliminar'],        
        "id_barrio_reporte"     => $result->fields['id_barrio_reporte'],
        "medio_recepcion"       => $result->fields['medio_recepcion'],
        "fch_cierre"            => $result->fields['fch_cierre'],
        "tercero_cierra"        => $result->fields['tercero_cierra']

        /*"longitud"      => $result->fields['latitud'],
        "id_pqr"  => $result->fields['id_pqr'],
        "fch_instalacion"  => $result->fields['fch_instalacion'],
        "fch_registro"  => $result->fields['fch_registro'],
        "usuario"       => $result->fields['usuario'],
        "estado"        => $result->fields['estado'],
        "proveedor"     => $result->fields['proveedor'],
        "id_municipio"          => $result->fields['id_municipio'],
        "id_barrio"             => $result->fields['id_barrio'],
        "id_tercero_proveedor"  => $result->fields['id_tercero_proveedor'],
        "id_estado_pqr"   => $result->fields['id_estado_pqr'],
        "id_tipo_pqr"   => $result->fields['id_tipo_pqr']*/
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