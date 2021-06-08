<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class PeriodoMantenimiento{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarPeriodoMantenimiento(){
        $this->sql = "select * from periodo_mantenimiento order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los Periodo Mantenimiento". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }
    
    function contarPeriodoMantenimiento($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from periodo_mantenimiento where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los Periodo Mantenimiento". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaPeriodoMantenimiento($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from periodo_mantenimiento where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los Periodo Mantenimiento". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoPeriodoMantenimiento($post){
        $this->sql = "INSERT INTO periodo_mantenimiento(descripcion,dias) VALUES('".$post['txt_descripcion']."',".$post['txt_dias'].");";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarPeriodoMantenimiento($post){
        $this->sql= "UPDATE periodo_mantenimiento SET descripcion='".$post['txt_descripcion']."' ,dias=".$post['txt_dias']." WHERE id_periodo_mantenimiento=".$post['id_periodo_mantenimiento'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Periodo Mantenimiento Actualizado");

    }

    function eliminarPeriodoMantenimiento($id_periodo_mantenimiento){
        $this->sql = "DELETE FROM periodo_mantenimiento WHERE id_periodo_mantenimiento=".$id_periodo_mantenimiento;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Periodo Mantenimiento Eliminado");
    }
}