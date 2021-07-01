<?php
function Meses(){
    $i = 1;
    $fin = 12;
    while($i<=$fin){
        $cad = (strlen($i)==1)?"0".$i:$i;
        $mes = new stdClass();
        switch($cad){
            case "01":
                $mes->id=$cad;
                $mes->descripcion="Enero";
                break;
            case "02":
                $mes->id=$cad;
                $mes->descripcion="Febrero";
                break;
            case "03":
                $mes->id=$cad;
                $mes->descripcion="Marzo";
                break;
            case "04":
                $mes->id=$cad;
                $mes->descripcion="Abrir";
                break;
            case "05":
                $mes->id=$cad;
                $mes->descripcion="Mayo";
                break;
            case "06":
                $mes->id=$cad;
                $mes->descripcion="Junio";
                break;
            case "07":
                $mes->id=$cad;
                $mes->descripcion="Julio";
                break;
            case "08":
                $mes->id=$cad;
                $mes->descripcion="Agosto";
                break;
            case "09":
                $mes->id=$cad;
                $mes->descripcion="Septiembre";
                break;
            case "10":
                $mes->id=$cad;
                $mes->descripcion="Octubre";
                break;
            case "11":
                $mes->id=$cad;
                $mes->descripcion="Noviembre";
                break;
            case "12":
                $mes->id=$cad;
                $mes->descripcion="Diciembre";
                break;
        }
        $meses[] = $mes;
        $i++;
    }
    return $meses;
}

function nombreMeses($mes){
        switch($mes){
            case 1:
                $descripcion="Enero";
                break;
            case 2:
                $descripcion="Febrero";
                break;
            case 3:
                $descripcion="Marzo";
                break;
            case 4:
                $descripcion="Abrir";
                break;
            case 5:
                $descripcion="Mayo";
                break;
            case 6:
                $descripcion="Junio";
                break;
            case 7:
                $descripcion="Julio";
                break;
            case 8:
                $descripcion="Agosto";
                break;
            case 9:
                $descripcion="Septiembre";
                break;
            case 10:
                $descripcion="Octubre";
                break;
            case 11:     
                $descripcion="Noviembre";
                break;
            case 12:                
                $descripcion="Diciembre";
                break;
        }

    return $descripcion;
}
?>