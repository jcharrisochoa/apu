<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Liquidacion{

    private $sql;
    public $db;
    private $result;

    function iniciarTransaccion(){
        $this->db->Execute("BEGIN;");
    }

    function finalizarTransaccion(){
        $this->db->Execute("COMMIT;");
    }

    function devolverTransaccion(){
        $this->db->Execute("ROLLBACK;");
    }

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function contarLiquidacion($post){
        $q = "";
        if(!empty($post['municipio']))
            $q .= " and l.id_municipio=".$post['municipio'];
        if(!empty($post['periodo_liquidacion']))
            $q .= " and l.periodo_liquidacion=".$post['periodo_liquidacion'];
        if(!empty($post['mes_liquidacion']))
            $q .= " and l.mes_liquidacion=".$post['mes_liquidacion'];

        $this->sql = "select count(1) as total        
                        from liquidacion l
                        join municipio m using(id_municipio)
                        join tercero t on(l.id_tercero_registra = t.id_tercero)
                        where
                        1=1
                        ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando las liquidacions". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function listarLiquidacion($post){
        
        $q = "";
        if(!empty($post['municipio']))
            $q .= " and l.id_municipio=".$post['municipio'];
        if(!empty($post['periodo_liquidacion']))
            $q .= " and l.periodo_liquidacion=".$post['periodo_liquidacion'];
        if(!empty($post['mes_liquidacion']))
            $q .= " and l.mes_liquidacion=".$post['mes_liquidacion'];


        if(!empty($post['order']['0']['column'])){
            $pos = $post['order']['0']['column'];	
            $campo = $post['columns'][$pos]['name'];
            $campo = ($campo=="")?"1":$campo;
        
            $q .= " order by ".$campo." ". $post['order']['0']['dir'];
        }

            
        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select l.*,m.descripcion as municipio ,t.nombre,
                    (select nombre from tercero t2 where t2.id_tercero = l.id_tercero_actualiza) as actualiza
                    from liquidacion l
                    join municipio m using(id_municipio)
                    join tercero t on(l.id_tercero_registra = t.id_tercero)
                    where
                    1=1
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando las liquidacions". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function nuevaLiquidacion($post){

        $this->sql = "INSERT INTO liquidacion(
            id_municipio, periodo_liquidacion, mes_liquidacion, fch_ini_facturacion, fch_fin_facturacion, valor_tarifa, 
            consumo, valor_consumo, facturacion_impuesto_ap, recaudo_impuesto_ap, facturacion_energia, facturacion_tsycc, recaudo_tsycc, 
            id_tercero_registra, fch_registro, id_tercero_actualiza, fch_actualiza
            )
            VALUES(
            ".$post['slt_municipio'].",".$post['slt_periodo_liquidacion'].",".$post['slt_mes_liquidacion'].", 
            '".$post['txt_fecha_ini']."', '".$post['txt_fecha_fin']."',
            ".$post['txt_valor_tarifa'].",".$post['txt_consumo'].", ".$post['txt_total_consumo'].", ".$post['txt_valor_facturado_ap'].", 
            ".$post['txt_valor_recaudo_ap'].", ".$post['txt_valor_factura_energia_ap'].", ".$post['txt_valor_facturado_tsycc'].",
            ".$post['txt_valor_recaudo_tsycc'].", ".$_SESSION['id_tercero'].", now(), ".$_SESSION['id_tercero'].", now()
           );";

        $result = $this->db->Execute($this->sql);
        if(!$result){
            $array = array("estado"=>false,"data"=>$this->db->ErrorMsg());
        }
        else{
            $id_liquidacion = $this->db->insert_id();
            $array =  array("estado"=>true,"data"=> $id_liquidacion);
        }
        return $array;
    }

    function editarLiquidacion($post){

        $this->sql = "UPDATE  liquidacion SET
            periodo_liquidacion=".$post['slt_periodo_liquidacion'].", 
            mes_liquidacion=".$post['slt_mes_liquidacion'].", 
            fch_ini_facturacion='".$post['txt_fecha_ini']."',
            fch_fin_facturacion='".$post['txt_fecha_fin']."', 
            valor_tarifa=".$post['txt_valor_tarifa'].", 
            consumo=".$post['txt_consumo'].", 
            valor_consumo=".$post['txt_total_consumo'].", 
            facturacion_impuesto_ap=".$post['txt_valor_facturado_ap'].", 
            recaudo_impuesto_ap=".$post['txt_valor_recaudo_ap'].", 
            facturacion_energia=".$post['txt_valor_factura_energia_ap'].", 
            facturacion_tsycc=".$post['txt_valor_facturado_tsycc'].", 
            recaudo_tsycc=".$post['txt_valor_recaudo_tsycc'].", 
            id_tercero_actualiza=".$_SESSION['id_tercero'].", 
            fch_actualiza= now()
            WHERE
            id_liquidacion = ".$post['id_liquidacion'].";";

        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Liquidación actualizada");
    }

    function eliminarLiquidacion($id_liquidacion){
        $this->sql = "delete from liquidacion where id_liquidacion=".$id_liquidacion;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Liquidacion eliminada");
    }

    function getPeriodo($ini){
        while($ini<=date("Y")){
            $periodo[] = $ini;
            $ini++;
        }
        return $periodo;
    }

    function getMes(){
        $i = 1;
        $fin = 12;
        while($i<=$fin){
            $mes = new stdClass();
            switch($i){
                case 1:
                    $mes->id=$i;
                    $mes->descripcion="Enero";
                    break;
                case 2:
                    $mes->id=$i;
                    $mes->descripcion="Febrero";
                    break;
                case 3:
                    $mes->id=$i;
                    $mes->descripcion="Marzo";
                    break;
                case 4:
                    $mes->id=$i;
                    $mes->descripcion="Abrir";
                    break;
                case 5:
                    $mes->id=$i;
                    $mes->descripcion="Mayo";
                    break;
                case 6:
                    $mes->id=$i;
                    $mes->descripcion="Junio";
                    break;
                case 7:
                    $mes->id=$i;
                    $mes->descripcion="Julio";
                    break;
                case 8:
                    $mes->id=$i;
                    $mes->descripcion="Agosto";
                    break;
                case 9:
                    $mes->id=$i;
                    $mes->descripcion="Septiembre";
                    break;
                case 10:
                    $mes->id=$i;
                    $mes->descripcion="Octubre";
                    break;
                case 11:
                    $mes->id=$i;
                    $mes->descripcion="Noviembre";
                    break;
                case 12:
                    $mes->id=$i;
                    $mes->descripcion="Diciembre";
                    break;
            }
            $meses[] = $mes;
            $i++;
        }
        return $meses;
    }
}
?>