<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class TipoIdentificacion{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarTipoIdentificacion(){
        $this->sql = "select * from tipo_identificacion order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de identificacion". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }
    
    function contarTipoIdentificacion($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from tipo_identificacion where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los tipos de identificacion". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaTipoIdentificacion($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from tipo_identificacion where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de identificacion". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoTipoIdentificacion($post){
        $this->sql = "INSERT INTO tipo_identificacion(descripcion,abreviatura) VALUES('".$post['txt_descripcion']."','".$post['txt_abreviatura']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarTipoIdentificacion($post){
        $this->sql= "UPDATE tipo_identificacion SET descripcion='".$post['txt_descripcion']."',abreviatura='".$post['txt_abreviatura']."' WHERE id_tipo_identificacion=".$post['id_tipo_identificacion'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo Identificacion Actualizado");

    }

    function eliminarTipoIdentificacion($id_tipo_identificacion){
        $this->sql = "DELETE FROM tipo_identificacion WHERE id_tipo_identificacion=".$id_tipo_identificacion;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo Identificacion Eliminado");
    }
}