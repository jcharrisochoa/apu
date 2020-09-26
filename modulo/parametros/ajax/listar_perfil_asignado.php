<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Menu.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $menu->menuTercero($_POST['id_tercero'],$_POST['buscar']);
$i=1;
while(!$result->EOF){
    ?>
    <tr>
        <td class="text-center"><?=$i?></td>
        <td class="text-left"><?=$result->fields['nombre']?></td>
        <td class="text-center alert alert-success"><?=($result->fields['crear']=="S")?"<i class=\"entypo-check\">":""?></td>
        <td class="text-center alert alert-warning"><?=($result->fields['actualizar']=="S")?"<i class=\"entypo-check\">":""?></td>
        <td class="text-center alert alert-danger"><?=($result->fields['eliminar']=="S")?"<i class=\"entypo-check\">":""?></td>
        <td class="text-center alert alert-info"><?=($result->fields['imprimir']=="S")?"<i class=\"entypo-check\">":""?></td>
        <td class="text-center"><input type="checkbox" value="<?=$result->fields['id_menu']?>" /></td>
    </tr>
    <?php
    $i++;
    $result->MoveNext();
}