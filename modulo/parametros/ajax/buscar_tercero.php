<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Tercero.php";
$tercero = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $tercero->buscarTercero($_POST['id_tercero']);

$obj = new stdClass();
/*
t.id_tercero,t.id_tipo_identificacion,t.identificacion,t.nombre,t.apellido,t.razon_social,t.id_municipio,
t.direccion,t.email,t.telefono,t.es_cliente,t.es_proveedor,t.es_empleado,t.es_usuario,t.usuario,t.id_tercero_registra,
t.fch_registro,m.descripcion as municipio,d.descripcion as departamento,ti.abreviatura,t.ejecuta_labor_tecnica,t.estado,
*/
if(!$result){
    $obj->id_tercero                = "";
    $obj->id_tipo_identificacion    = "";
    $obj->identificacion            = "";
    $obj->nombre                    = "";
    $obj->apellido                  = "";
    $obj->razon_social              = "";
    $obj->id_municipio              = "";
    $obj->direccion                 = "";
    $obj->email                     = "";
    $obj->telefono                  = "";
    $obj->es_cliente                = "";
    $obj->es_proveedor              = "";
    $obj->es_empleado               = "";
    $obj->es_usuario                = "";
    $obj->usuario                   = "";
    $obj->fch_registro              = "";
    $obj->municipio                 = "";
    $obj->departamento              = "";
    $obj->abreviatura               = "";
    $obj->ejecuta_labor_tecnica     = "";
    $obj->estado_tercero                    = "";
    $obj->mensaje                   = "Error consultando el tercero ".$_POST['id_tercero'];
    $obj->estado                    = false;    
}
else{
    if($result->NumRows()==0){
        $obj->id_tercero                = "";
        $obj->id_tipo_identificacion    = "";
        $obj->identificacion            = "";
        $obj->nombre                    = "";
        $obj->apellido                  = "";
        $obj->razon_social              = "";
        $obj->id_municipio              = "";
        $obj->direccion                 = "";
        $obj->email                     = "";
        $obj->telefono                  = "";
        $obj->es_cliente                = "";
        $obj->es_proveedor              = "";
        $obj->es_empleado               = "";
        $obj->es_usuario                = "";
        $obj->usuario                   = "";
        $obj->fch_registro              = "";
        $obj->municipio                 = "";
        $obj->departamento              = "";
        $obj->abreviatura               = "";
        $obj->ejecuta_labor_tecnica     = "";
        $obj->estado_tercero                    = "";
        $obj->mensaje                   = "No existe el tercero ";
        $obj->estado                    = true;
    }
    else{
        $obj->id_tercero                = $result->fields['id_tercero'];
        $obj->id_tipo_identificacion    = $result->fields['id_tipo_identificacion'];
        $obj->identificacion            = $result->fields['identificacion'];
        $obj->nombre                    = $result->fields['nombre'];
        $obj->apellido                  = $result->fields['apellido'];
        $obj->razon_social              = $result->fields['razon_social'];
        $obj->id_municipio              = $result->fields['id_municipio'];
        $obj->direccion                 = $result->fields['direccion'];
        $obj->email                     = $result->fields['email'];
        $obj->telefono                  = $result->fields['telefono'];
        $obj->es_cliente                = $result->fields['es_cliente'];
        $obj->es_proveedor              = $result->fields['es_proveedor'];
        $obj->es_empleado               = $result->fields['es_empleado'];
        $obj->es_usuario                = $result->fields['es_usuario'];
        $obj->usuario                   = $result->fields['usuario'];
        $obj->fch_registro              = $result->fields['fch_registro'];
        $obj->municipio                 = $result->fields['municipio'];
        $obj->departamento              = $result->fields['departamento'];
        $obj->abreviatura               = $result->fields['abreviatura'];
        $obj->ejecuta_labor_tecnica     = $result->fields['ejecuta_labor_tecnica'];
        $obj->estado_tercero            = $result->fields['estado'];
        $obj->mensaje                   = "Tercero encontrado";
        $obj->estado                    = true;
    }
}
echo json_encode($obj);
