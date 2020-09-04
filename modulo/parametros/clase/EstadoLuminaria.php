<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class EstadoLuminaria{
    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarEstadoLuminaria($post){
        $this->sql = "select * from estado_luminaria order by descripcion";
        $this->result = $this->db->Execute($this->sql);

        if(!$this->result){
            echo "Error Consultando los estados de la luminaria". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function contarEstadoLuminaria($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $this->sql = "select count(1) as total from estado_luminaria where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los tipos de actividad". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function tablaEstadoLuminaria($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from estado_luminaria where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de actividad". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoEstadoLuminaria($post){
        $this->sql = "INSERT INTO estado_luminaria(descripcion) VALUES('".$post['txt_descripcion']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarEstadoLuminaria($post){
        $this->sql= "UPDATE estado_luminaria SET descripcion='".$post['txt_descripcion']."' WHERE id_estado_luminaria=".$post['id_estado_luminaria'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Estado Luminaria Actualizado");

    }

    function eliminarEstadoLuminaria($id_estado_luminaria){
        $this->sql = "DELETE FROM estado_luminaria WHERE id_estado_luminaria=".$id_estado_luminaria;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Estado Luminaria Eliminado");
    }
}