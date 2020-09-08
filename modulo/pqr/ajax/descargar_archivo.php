<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/PQR.php";
$pqr = new PQR($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $pqr->consultarArchivoPQR($_REQUEST['id_archivo_pqr']);
if($result->NumRows()==0){
    echo "No existe el archivo";
}
else{
    $fileType       = $result->fields["tipo"];
    $fileContent    = $result->fields["archivo"];
    $filesize       = $result->fields["tamano"];
    $filename       = str_replace(' ', '', $result->fields["nombre_archivo"]);
    $fileext        = $result->fields["extension"];

    $fileHandle = fopen("temp", "w");
    if (fwrite($fileHandle, $fileContent) == FALSE) {
        echo "Error al escribir el archivo temporal";
        exit;
    }

    header('Content-Transfer-Encoding: utf8'); 
    header("Content-Length: $filesize"); 
    header("Content-type: $fileType");
    header("Content-Disposition: attachment; filename=$filename");
    echo $fileContent;
}