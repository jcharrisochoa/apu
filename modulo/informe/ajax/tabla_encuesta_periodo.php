<?php
session_start();
$url = file_get_contents("../../../conexion/credencial.json");
$credencial= json_decode($url, true);

require_once "../../pqr/clase/Encuesta.php";
$encuesta  = new Encuesta($credencial['driver'],$credencial['host'], $credencial['user'], $credencial['pwd'],$credencial['database']);
$result = $encuesta->tablaResumen($_POST,"C");
?>
<h3 class="">Calidad</h3>
<table class="table table-bordered responsive table-hover table-striped  table-condensed">
    <thead>
        <tr>
            <th class="text-center" rowspan="2">Municipio</th>
            <th class="text-center" colspan="4">Ene</th>
            <th class="text-center" colspan="4">Feb</th>
            <th class="text-center" colspan="4">Mar</th>
            <th class="text-center" colspan="4">Abr</th>
            <th class="text-center" colspan="4">May</th>
            <th class="text-center" colspan="4">Jun</th>
            <th class="text-center" colspan="4">Jul</th>
            <th class="text-center" colspan="4">Ago</th>
            <th class="text-center" colspan="4">Sep</th>
            <th class="text-center" colspan="4">Oct</th>
            <th class="text-center" colspan="4">Nov</th>
            <th class="text-center" colspan="4">Dic</th>
            <th class="text-center" rowspan="2">Total</th>
        </tr>
        <tr>
            <?php
            $i=0;
            while($i<12){
            ?>
            <th class="text-center">M</th>
            <th class="text-center">R</th>
            <th class="text-center">B</th>
            <th class="text-center">E</th>
            <?php
                $i++;
            }
            ?>
        </tr>
    </thead> 
    <tbody id="tbody_calidad">
        <?php
        $i=1;
        $sw = false;
        $tmp_id_municipio = "";  
        $total = 0;      
        while(!$result->EOF){
            if($tmp_id_municipio != $result->fields['id_municipio']){
                if($sw){ 
                    while($i<=12){
                        ?>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <?php
                        $i++;
                    }
                    echo "<td class=\"text-center\">".$total."</td></tr>";
                    $total = 0;
                }
                ?>
                <tr>
                <td class="text-left"><?=$result->fields['municipio']?></td>
                <?php
                $tmp_id_municipio = $result->fields['id_municipio'];
                $i=1;
                $sw=true;
            }
            
                
            while($i<=12){
                if($result->fields['mes']==$i){
                    $total += $result->fields['csm'] + $result->fields['csr'] + $result->fields['csb'] + $result->fields['cse']
                    ?>
                    <td class="text-center"><?=($result->fields['csm']==0)?"&nbsp;":"<span class=\"badge badge-danger\">".$result->fields['csm']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['csr']==0)?"&nbsp;":"<span class=\"badge badge-warning\">".$result->fields['csr']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['csb']==0)?"&nbsp;":"<span class=\"badge badge-info\">".$result->fields['csb']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['cse']==0)?"&nbsp;":"<span class=\"badge badge-success\">".$result->fields['cse']."</span>"?></td>
                    <?php
                    $i++;
                    break;
                }
                else{
                    ?>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <?php
                }
                $i++;
            }
            
            $result->MoveNext();
        }
        while($i<=12){
            ?>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <?php
            $i++;
        }
        echo "<td class=\"text-center\"><strong>".$total."</strong></td></tr>";
        ?>
    </tbody>
</table>
<br>
<?php
$result = $encuesta->tablaResumen($_POST,"T");
?>
<h3 class="">Tiempo</h3>
<table class="table table-bordered responsive table-hover table-striped  table-condensed">
    <thead>
        <tr>
            <th class="text-center" rowspan="2">Municipio</th>
            <th class="text-center" colspan="4">Ene</th>
            <th class="text-center" colspan="4">Feb</th>
            <th class="text-center" colspan="4">Mar</th>
            <th class="text-center" colspan="4">Abr</th>
            <th class="text-center" colspan="4">May</th>
            <th class="text-center" colspan="4">Jun</th>
            <th class="text-center" colspan="4">Jul</th>
            <th class="text-center" colspan="4">Ago</th>
            <th class="text-center" colspan="4">Sep</th>
            <th class="text-center" colspan="4">Oct</th>
            <th class="text-center" colspan="4">Nov</th>
            <th class="text-center" colspan="4">Dic</th>
            <th class="text-center" rowspan="2">Total</th>
        </tr>
        <tr>
            <?php
            $i=0;
            while($i<12){
            ?>
            <th class="text-center">M</th>
            <th class="text-center">R</th>
            <th class="text-center">B</th>
            <th class="text-center">E</th>
            <?php
                $i++;
            }
            ?>
        </tr>
    </thead> 
    <tbody id="tbody_calidad">
        <?php
        $i=1;
        $sw = false;
        $tmp_id_municipio = "";  
        $total = 0;      
        while(!$result->EOF){
            if($tmp_id_municipio != $result->fields['id_municipio']){
                if($sw){ 
                    while($i<=12){
                        ?>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <?php
                        $i++;
                    }
                    echo "<td class=\"text-center\">".$total."</td></tr>";
                    $total = 0;
                }
                ?>
                <tr>
                <td class="text-left"><?=$result->fields['municipio']?></td>
                <?php
                $tmp_id_municipio = $result->fields['id_municipio'];
                $i=1;
                $sw=true;
            }
            
                
            while($i<=12){
                if($result->fields['mes']==$i){
                    $total += $result->fields['csm'] + $result->fields['csr'] + $result->fields['csb'] + $result->fields['cse']
                    ?>
                    <td class="text-center"><?=($result->fields['csm']==0)?"&nbsp;":"<span class=\"badge badge-danger\">".$result->fields['csm']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['csr']==0)?"&nbsp;":"<span class=\"badge badge-warning\">".$result->fields['csr']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['csb']==0)?"&nbsp;":"<span class=\"badge badge-info\">".$result->fields['csb']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['cse']==0)?"&nbsp;":"<span class=\"badge badge-success\">".$result->fields['cse']."</span>"?></td>
                    <?php
                    $i++;
                    break;
                }
                else{
                    ?>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <?php
                }
                $i++;
            }
            
            $result->MoveNext();
        }
        while($i<=12){
            ?>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <?php
            $i++;
        }
        echo "<td class=\"text-center\"><strong>".$total."</strong></td></tr>";
        ?>
    </tbody>
</table>

<br>
<?php
$result = $encuesta->tablaResumen($_POST,"A");
?>
<h3 class="">Atenci&oacute;n</h3>
<table class="table table-bordered responsive table-hover table-striped  table-condensed">
    <thead>
        <tr>
            <th class="text-center" rowspan="2">Municipio</th>
            <th class="text-center" colspan="4">Ene</th>
            <th class="text-center" colspan="4">Feb</th>
            <th class="text-center" colspan="4">Mar</th>
            <th class="text-center" colspan="4">Abr</th>
            <th class="text-center" colspan="4">May</th>
            <th class="text-center" colspan="4">Jun</th>
            <th class="text-center" colspan="4">Jul</th>
            <th class="text-center" colspan="4">Ago</th>
            <th class="text-center" colspan="4">Sep</th>
            <th class="text-center" colspan="4">Oct</th>
            <th class="text-center" colspan="4">Nov</th>
            <th class="text-center" colspan="4">Dic</th>
            <th class="text-center" rowspan="2">Total</th>
        </tr>
        <tr>
            <?php
            $i=0;
            while($i<12){
            ?>
            <th class="text-center" title="Mala">M</th>
            <th class="text-center" title="Regular">R</th>
            <th class="text-center" title="Buena">B</th>
            <th class="text-center" title="Excelente">E</th>
            <?php
                $i++;
            }
            ?>
        </tr>
    </thead> 
    <tbody id="tbody_calidad">
        <?php
        $i=1;
        $sw = false;
        $tmp_id_municipio = "";  
        $total = 0;      
        while(!$result->EOF){
            if($tmp_id_municipio != $result->fields['id_municipio']){
                if($sw){ 
                    while($i<=12){
                        ?>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <?php
                        $i++;
                    }
                    echo "<td class=\"text-center\">".$total."</td></tr>";
                    $total = 0;
                }
                ?>
                <tr>
                <td class="text-left"><?=$result->fields['municipio']?></td>
                <?php
                $tmp_id_municipio = $result->fields['id_municipio'];
                $i=1;
                $sw=true;
            }
            
                
            while($i<=12){
                if($result->fields['mes']==$i){
                    $total += $result->fields['csm'] + $result->fields['csr'] + $result->fields['csb'] + $result->fields['cse']
                    ?>
                    <td class="text-center"><?=($result->fields['csm']==0)?"&nbsp;":"<span class=\"badge badge-danger\">".$result->fields['csm']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['csr']==0)?"&nbsp;":"<span class=\"badge badge-warning\">".$result->fields['csr']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['csb']==0)?"&nbsp;":"<span class=\"badge badge-info\">".$result->fields['csb']."</span>"?></td>
                    <td class="text-center"><?=($result->fields['cse']==0)?"&nbsp;":"<span class=\"badge badge-success\">".$result->fields['cse']."</span>"?></td>
                    <?php
                    $i++;
                    break;
                }
                else{
                    ?>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                    <?php
                }
                $i++;
            }
            
            $result->MoveNext();
        }
        while($i<=12){
            ?>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <?php
            $i++;
        }
        echo "<td class=\"text-center\"><strong>".$total."</strong></td></tr>";
        ?>
    </tbody>
</table>