<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Departamento{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarDepartamento(){
        $this->sql = "select * from departamento order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los departamentos". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function contarDepartamento($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $this->sql = "select count(1) as total from departamento where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando departamento". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function tablaDepartamento($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from departamento where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los departamentos ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoDepartamento($post){
        $this->sql = "INSERT INTO departamento(descripcion) VALUES('".$post['txt_descripcion']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarDepartamento($post){
        $this->sql= "UPDATE departamento SET descripcion='".$post['txt_descripcion']."' WHERE id_departamento=".$post['id_departamento'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Departamento Actualizado");

    }

    function eliminarDepartamento($id_departamento){
        $this->sql = "DELETE FROM departamento WHERE id_departamento=".$id_departamento;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Departamento Eliminado");
    }
}