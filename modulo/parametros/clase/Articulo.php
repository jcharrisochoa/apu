<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Articulo{

    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function buscarArticulo($id_articulo){
        $this->sql = "select * from articulo where id_articulo=".$id_articulo;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            return false;
        }
        else{
            return $this->result;
        }
    }

    function contarArticulo($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from articulo where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando articulo". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function tablaArticulo($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from articulo where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los articulo". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoArticulo($post){
        $this->sql = "INSERT INTO articulo(descripcion,clase) VALUES('".$post['txt_descripcion']."','".$post['slt_clase']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarArticulo($post){
        $this->sql= "UPDATE articulo SET 
                    descripcion='".$post['txt_descripcion']."',
                    clase='".$post['slt_clase']."' 
                    WHERE id_articulo=".$post['id_articulo'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Servicio Actualizado");

    }

    function eliminarArticulo($id_articulo){
        $this->sql = "DELETE FROM articulo WHERE id_articulo=".$id_articulo;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Servicio Eliminado");
    }
}
