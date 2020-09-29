<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../../actividad/clase/Actividad.php";
$actividad = new Actividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $actividad->resumenActividadPeriodo($_POST);
?>
<table class="table table-condensed table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th class="text-center">Municipio</th>
            <th class="text-center">Ene</th>
            <th class="text-center">Feb</th>
            <th class="text-center">Mar</th>
            <th class="text-center">Abr</th>
            <th class="text-center">May</th>
            <th class="text-center">Jun</th>
            <th class="text-center">Jul</th>
            <th class="text-center">Ago</th>
            <th class="text-center">Sep</th>
            <th class="text-center">Oct</th>
            <th class="text-center">Nov</th>
            <th class="text-center">Dic</th>
            <th class="text-center">Total</th>

        </tr>
    </thead>    
    <tbody>
    <?php
    $ene = 0;
    $feb = 0;
    $mar = 0;
    $abr = 0;
    $may = 0;
    $jun = 0;
    $jul = 0;
    $ago = 0;
    $sep = 0;
    $oct = 0;
    $nov = 0;
    $dic = 0;
    $total= 0;
    while(!$result->EOF){
        $total_cols = $result->fields['ene']+$result->fields['feb']+$result->fields['mar']+
                      $result->fields['abr']+$result->fields['may']+$result->fields['jun']+
                      $result->fields['jul']+$result->fields['ago']+$result->fields['sep']+
                      $result->fields['oct']+$result->fields['nov']+$result->fields['dic'];
    ?>
        <tr>
            <td class="text-left"><?=$result->fields['descripcion']?></td>
            <td class="text-right"><?=$result->fields['ene']?></td>
            <td class="text-right"><?=$result->fields['feb']?></td>
            <td class="text-right"><?=$result->fields['mar']?></td>
            <td class="text-right"><?=$result->fields['abr']?></td>
            <td class="text-right"><?=$result->fields['may']?></td>
            <td class="text-right"><?=$result->fields['jun']?></td>
            <td class="text-right"><?=$result->fields['jul']?></td>
            <td class="text-right"><?=$result->fields['ago']?></td>
            <td class="text-right"><?=$result->fields['sep']?></td>
            <td class="text-right"><?=$result->fields['oct']?></td>
            <td class="text-right"><?=$result->fields['nov']?></td>
            <td class="text-right"><?=$result->fields['dic']?></td>
            <td class="text-info text-right"><strong><?=$total_cols?></strong></td>
        </tr>
    <?php
        $ene += $result->fields['ene'];
        $feb += $result->fields['feb'];
        $mar += $result->fields['mar'];
        $abr += $result->fields['abr'];
        $may += $result->fields['may'];
        $jun += $result->fields['jun'];
        $jul += $result->fields['jul'];
        $ago += $result->fields['ago'];
        $sep += $result->fields['sep'];
        $oct += $result->fields['oct'];
        $nov += $result->fields['nov'];
        $dic += $result->fields['dic'];
        $total += $total_cols;
        $result->MoveNext();
    }
    ?>
    <tr class="text-info">
            <td>TOTAL</td>
            <td class="text-right"><strong><?=$ene?></strong></td>
            <td class="text-right"><strong><?=$feb?></strong></td>
            <td class="text-right"><strong><?=$mar?></strong></td>
            <td class="text-right"><strong><?=$abr?></strong></td>
            <td class="text-right"><strong><?=$may?></strong></td>
            <td class="text-right"><strong><?=$jun?></strong></td>
            <td class="text-right"><strong><?=$jul?></strong></td>
            <td class="text-right"><strong><?=$ago?></strong></td>
            <td class="text-right"><strong><?=$sep?></strong></td>
            <td class="text-right"><strong><?=$oct?></strong></td>
            <td class="text-right"><strong><?=$nov?></strong></td>
            <td class="text-right"><strong><?=$dic?></strong></td>
            <td class="text-info text-right"><strong><?=$total?></strong></td>
        </tr>
    </tbody>
</table>