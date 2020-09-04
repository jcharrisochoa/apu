<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class MedioRecepcionPQR{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarMedioRecepcionPQR(){
        $this->sql = "select * from medio_recepcion_pqr order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los medios de recepcion PQR". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function contarMedioRecepcionPQR($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from medio_recepcion_pqr where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando medios de recepcion". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function tablaMedioRecepcionPQR($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from medio_recepcion_pqr where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de actividad". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoMedioRecepcionPQR($post){
        $this->sql = "INSERT INTO medio_recepcion_pqr(descripcion) VALUES('".$post['txt_descripcion']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarMedioRecepcionPQR($post){
        $this->sql= "UPDATE medio_recepcion_pqr SET descripcion='".$post['txt_descripcion']."' WHERE id_medio_recepcion_pqr=".$post['id_medio_recepcion_pqr'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Medio Recepci&oacute;n PQR Actualizado");

    }

    function eliminarMedioRecepcionPQR($id_medio_recepcion_pqr){
        $this->sql = "DELETE FROM medio_recepcion_pqr WHERE id_medio_recepcion_pqr=".$id_medio_recepcion_pqr;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Medio Recepci&oacute;n Eliminado");
    }
}