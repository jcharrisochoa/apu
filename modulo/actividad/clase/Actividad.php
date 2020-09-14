<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Actividad{

    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function iniciarTransaccion(){
        $this->db->Execute("BEGIN;");
    }

    function finalizarTransaccion(){
        $this->db->Execute("COMMIT;");
    }

    function devolverTransaccion(){
        $this->db->Execute("ROLLBACK;");
    }
    
    function contarActividad($post){
        $q = "";
        if(!empty($post['municipio']))
            $q .= " and ac.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and ac.id_barrio=".$post['barrio'];
        if(!empty($post['tipo_actividad']))
            $q .= " and ac.id_tipo_actividad=".$post['tipo_actividad'];
        if(!empty($post['id_luminaria']))
            $q .= " and ac.id_luminaria=".$post['id_luminaria'];
        if(!empty($post['poste_no']))
            $q .= " and l.poste_no = '".$post['poste_no']."'";
        if(!empty($post['luminaria_no']))
            $q .= " and l.luminaria_no = '".$post['luminaria_no']."'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(ac.fch_actividad) >= '".$post['fechaini']."' and date(ac.fch_actividad) <= '".$post['fechafin']."'";
        
            $this->sql = "select count(ac.id_actividad) as total
                from actividad ac
                join municipio m using(id_municipio)
                left join tercero t using(id_tercero)
                left join barrio b using(id_barrio)
                left join luminaria l using(id_luminaria)
                left join tipo_reporte tr using(id_tipo_reporte)
                left join tipo_actividad ta using(id_tipo_actividad)
                join estado_actividad ea using(id_estado_actividad)
                where 
                1=1
                ".$q;
                $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando las actividades". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function listarActividad($post){
        $q = "";
        if(!empty($post['municipio']))
            $q .= " and ac.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and ac.id_barrio=".$post['barrio'];
        if(!empty($post['tipo_actividad']))
            $q .= " and ac.id_tipo_actividad=".$post['tipo_actividad'];
        if(!empty($post['id_luminaria']))
            $q .= " and ac.id_luminaria=".$post['id_luminaria'];
        if(!empty($post['poste_no']))
            $q .= " and l.poste_no = '".$post['poste_no']."'";
        if(!empty($post['luminaria_no']))
            $q .= " and l.luminaria_no = '".$post['luminaria_no']."'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(ac.fch_actividad) >= '".$post['fechaini']."' and date(ac.fch_actividad) <= '".$post['fechafin']."'";

        $pos = $_POST['order']['0']['column'];	
        $campo = $_POST['columns'][$pos]['name'];
        $campo = ($campo=="")?"6":$campo;
    
        $q .= " order by ".$campo." ". $_POST['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];
            

        $this->sql = "select ac.id_actividad,m.descripcion as municipio,ta.descripcion as tipo,
                case when ac.id_barrio is null then ac.barrio else b.descripcion end as barrio,
                ac.direccion,ac.fch_actividad,l.poste_no,l.luminaria_no,t.nombre as tecnico,
                ac.fch_reporte,ac.observacion,tr.descripcion as tipo_reporte,ea.descripcion as estado_actividad,
                tl.descripcion as tipo_luminaria
                from actividad ac
                join municipio m using(id_municipio)
                left join tercero t using(id_tercero)
                left join barrio b using(id_barrio)
                left join luminaria l using(id_luminaria)
                left join tipo_luminaria tl using(id_tipo_luminaria)
                left join tipo_reporte tr using(id_tipo_reporte)
                left join tipo_actividad ta using(id_tipo_actividad)
                join estado_actividad ea using(id_estado_actividad)
                where 
                1=1                
                ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando las actividades". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }
}