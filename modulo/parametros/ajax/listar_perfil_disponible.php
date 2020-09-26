<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);
require_once "../clase/Menu.php";
$menu = new Menu($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $menu->menuDisponible($_POST['id_tercero'],$_POST['buscar']);
$i=1;
while(!$result->EOF){
    ?>
    <tr id="tr_<?=$result->fields['id_menu']?>">
        <td class="text-center"><?=$i?></td>
        <td class="text-left"><?=$result->fields['nombre']?></td>
        <td class="text-center alert alert-success"><input type="checkbox" /></td>
        <td class="text-center alert alert-warning"><input type="checkbox" /></td>
        <td class="text-center alert alert-danger"><input type="checkbox" /></td>
        <td class="text-center alert alert-info"><input type="checkbox" /></td>
        <td class="text-center chk"><input type="checkbox" value="<?=$result->fields['id_menu']?>" /></td>
    </tr>
    <?php
    $i++;
    $result->MoveNext();
}