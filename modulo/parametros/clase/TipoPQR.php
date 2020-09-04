<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class TipoPQR{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }
    
    function listarTipoPQR(){
        $this->sql = "select * from tipo_pqr order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de PQR". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function contarTipoPQR($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from tipo_pqr where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando tipos de PQR". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function tablaTipoPQR($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from tipo_pqr where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de PQR". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoTipoPQR($post){
        $this->sql = "INSERT INTO tipo_pqr(descripcion,dias_vencimiento,estado) VALUES('".$post['txt_descripcion']."',".$post['txt_dia'].",'".$post['slt_estado']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarTipoPQR($post){
        $this->sql= "UPDATE tipo_pqr SET 
                    descripcion='".$post['txt_descripcion']."',
                    dias_vencimiento=".$post['txt_dia'].",
                    estado='".$post['slt_estado']."' 
                    WHERE id_tipo_pqr=".$post['id_tipo_pqr'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo PQR Actualizado");

    }

    function eliminarTipoPQR($id_tipo_pqr){
        $this->sql = "DELETE FROM tipo_pqr WHERE id_tipo_pqr=".$id_tipo_pqr;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo PQR Eliminado");
    }
}