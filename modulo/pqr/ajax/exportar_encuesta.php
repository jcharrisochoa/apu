<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../../../libreria/PHPExcel/Classes/PHPExcel.php";
require_once "../../../libreria/PHPExcel/Classes/PHPExcel/Reader/Excel2007.php";
require_once "../../../libreria/PHPExcel/Classes/PHPExcel/IOFactory.php";
require_once "../clase/Encuesta.php";
$luminaria  = new Encuesta($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $luminaria->listarEncuesta($_REQUEST);

$objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("AsoAtlantico")
            ->setLastModifiedBy("SID")
            ->setTitle("Office 2007 XLSX Luminaria")
            ->setSubject("Office 2007 XLSX Luminaria")
            ->setDescription("Test doc for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Luminaria");

    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('PHPExcel logo');
    $objDrawing->setDescription('PHPExcel logo');
    $objDrawing->setPath('../../../libreria/img/logo.png');
    $objDrawing->setHeight(80);
    $objDrawing->setCoordinates('A3');
    $objDrawing->setOffSetX(20);
    $objDrawing->setRotation(0);
    $objDrawing->getShadow()->setVisible(true);
    $objDrawing->getShadow()->setDirection(0);
    $objDrawing->SetWorksheet($objPHPExcel->getActiveSheet());

    $objPHPExcel->getActiveSheet()->setTitle('Luminaria');
    $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setName('Arial');
    $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setSize(12);
    $objPHPExcel->getActiveSheet()->mergeCells('A3:J3');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'REPORTE ENCUESTA SATISFACCIÓN');
    $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()->getStyle('K3:L3')->getFont()->setName('Arial');
    $objPHPExcel->getActiveSheet()->getStyle('K3:L3')->getFont()->setSize(8);
    $objPHPExcel->getActiveSheet()->mergeCells('K3:L3');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, "GENERADO POR: ".$_SESSION['nombre']." ".$_SESSION['apellido']."\n FECHA GENERACION:".date("Y-m-d"));
    $objPHPExcel->getActiveSheet()->getStyle('K3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('K3:L3')->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(80);


    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

    
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 9, '#');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 9, 'MUNICIPIO');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 9, 'BARRIO');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 9, 'DIRECCION');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 9, 'IDENTIFICACION');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 9, 'NOMBRE/RAZON SOCIAL');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 9, 'CALIDAD');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 9, 'TIEMPO');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 9, 'SATISFACCION');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 9, 'FCH ENCUESTA');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 9, 'REGISTRADO POR');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 9, 'FCH REGISTRO');

    $row = 10;
    $item = 1;
    while (!$result->EOF) {
        

        //$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(60);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, strval($item));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result->fields["municipio"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result->fields["barrio"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result->fields["direccion"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result->fields["identificacion"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result->fields["nombre_usuario_servicio"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, respuesta($result->fields["calidad_servicio"]));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, respuesta($result->fields["tiempo_atencion"]));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, respuesta($result->fields['atencion_grupo_trabajo']));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $result->fields["fch_encuesta"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $result->fields["usuario"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $result->fields["fch_registro"]);      
        
        $result->MoveNext();
        $row++;
        $item++;
    }
    $nombre = 'encuesta_satisfaccion_asoatlantico' . rand() . '.xlsx';

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($nombre);

    header("Content-Disposition: attachment; filename=" . $nombre);
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Length: " . filesize($nombre));
    readfile($nombre);
    unlink($nombre);

    function respuesta($r){
        switch($r){
            case "E":
                $descripcion = "EXCELENTE";
                break;
            case "B":
                $descripcion = "BUENO";
                break;
            case "R":
                $descripcion = "REGULAR";
                break;
            case "M":
                $descripcion = "MALO";
                break; 
        }
        return $descripcion;
    }
?>