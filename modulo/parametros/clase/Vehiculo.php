<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Vehiculo{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarVehiculo(){
        $this->sql = "select * from vehiculo order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los vehiculo". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }
    
    function contarVehiculo($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from vehiculo where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los vehiculo". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaVehiculo($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from vehiculo where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los vehiculo". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoVehiculo($post){
        $this->sql = "INSERT INTO vehiculo(descripcion,estado) VALUES('".$post['txt_descripcion']."','".$post['slt_estado']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarVehiculo($post){
        $this->sql= "UPDATE vehiculo SET 
        descripcion='".$post['txt_descripcion']."',
        estado='".$post['slt_estado']."'
        WHERE id_vehiculo=".$post['id_vehiculo'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Vehiculo Actualizado");

    }

    function eliminarVehiculo($id_vehiculo){
        $this->sql = "DELETE FROM vehiculo WHERE id_vehiculo=".$id_vehiculo;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Vehiculo Eliminado");
    }
}