<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class TipoLuminaria{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarTipoLuminaria(){
        $this->sql = "select * from tipo_luminaria order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de luminaria". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }
    
    function contarTipoLuminaria($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from tipo_luminaria where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los tipos de luminaria". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaTipoLuminaria($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from tipo_luminaria where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de luminaria". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoTipoLuminaria($post){
        $this->sql = "INSERT INTO tipo_luminaria(descripcion) VALUES('".$post['txt_descripcion']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarTipoLuminaria($post){
        $this->sql= "UPDATE tipo_luminaria SET descripcion='".$post['txt_descripcion']."' WHERE id_tipo_luminaria=".$post['id_tipo_luminaria'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo Luminaria Actualizado");

    }

    function eliminarTipoLuminaria($id_tipo_luminaria){
        $this->sql = "DELETE FROM tipo_luminaria WHERE id_tipo_luminaria=".$id_tipo_luminaria;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo Luminaria Eliminado");
    }
}