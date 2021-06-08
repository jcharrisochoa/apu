<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class ClaseIluminacion{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarClaseIluminacion(){
        $this->sql = "select * from clase_iluminacion order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los clase de iluminacion". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }
    
    function contarClaseIluminacion($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from clase_iluminacion where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los clase de iluminacion". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaClaseIluminacion($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from clase_iluminacion where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los clase de iluminacion". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoClaseIluminacion($post){
        $this->sql = "INSERT INTO clase_iluminacion(descripcion,abreviatura) VALUES('".$post['txt_descripcion']."','".$post['txt_abreviatura']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarClaseIluminacion($post){
        $this->sql= "UPDATE clase_iluminacion SET descripcion='".$post['txt_descripcion']."',abreviatura='".$post['txt_abreviatura']."' WHERE id_clase_iluminacion=".$post['id_clase_iluminacion'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Clase Iluminacion Actualizado");

    }

    function eliminarClaseIluminacion($id_clase_iluminacion){
        $this->sql = "DELETE FROM clase_iluminacion WHERE id_clase_iluminacion=".$id_clase_iluminacion;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Clase Iluminacion Eliminado");
    }
}