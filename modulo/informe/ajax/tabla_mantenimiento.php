<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../../actividad/clase/Actividad.php";
$actividad  = new Actividad($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $actividad->mantenimientoPendiente($_POST);
?>
<table class="table table-bordered responsive table-hover table-striped  table-condensed">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Municipio</th>
            <th class="text-center">Poste No</th>
            <th class="text-center">Luminaria No</th>
            <th class="text-center">Direcci&oacute;n</th>
            <th class="text-center">Barrio</th>
            <th class="text-center">Fch Instalaci&oacute;n</th>
            <th class="text-center">&Uacute;lt. Mantenimiento</th>
            <th class="text-center">Dias Vencimiento</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $row=1;
    while(!$result->EOF){
    ?>
        <tr>
            <td class="text-center"><?=$row?></td>
            <td class="text-center"><?=$result->fields['municipio']?></td>
            <td class="text-center"><?=$result->fields['poste_no']?></dh>
            <td class="text-center"><?=$result->fields['luminaria_no']?></td>
            <td class="text-center"><?=$result->fields['direccion']?></td>
            <td class="text-center"><?=$result->fields['barrio']?></td>
            <td class="text-center"><?=$result->fields['fch_instalacion']?></td>
            <td class="text-center"><?=$result->fields['ultimo_mto']?></td>
            <td class="text-center"><?=$result->fields['dias_vencimiento']?></td>
        </tr>
    <?php
        $row++;
        $result->MoveNext();
    }
    ?>
    </tbody>
</table>