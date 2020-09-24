<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Tercero.php";
$tercero = new Tercero($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $tercero->consultarFoto($_REQUEST['id_tercero']);
if($result->fields["nombre_foto"]==""){   
    $filename       = "../../../libreria/neon/assets/images/thumb-1@2x.png";
    $filesize       = filesize($filename);
    $fileContent    =  readfile($filename);
    $fileType       = "image/png";
}
else{
    $fileType       = $result->fields["tipo_foto"];
    $fileContent    = $result->fields["foto"];
    $filesize       = $result->fields["tamano_foto"];
    $filename       = str_replace(' ', '', $result->fields["nombre_foto"]);
    $fileext        = $result->fields["extension_foto"];

    $fileHandle = fopen("temp", "w");
    if (fwrite($fileHandle, $fileContent) == FALSE) {
        echo "Error al escribir el archivo temporal";
        exit;
    }
}
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Expires: 0"); 
header('Content-Transfer-Encoding: utf8'); 
header("Content-Length: $filesize"); 
header("Content-type: $fileType");
header("Content-Disposition: attachment; filename=$filename");
echo $fileContent;