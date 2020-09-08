<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Barrio{
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }
    function listarBarrioMunicipio($id_municipio){
        if(!empty($id_municipio)){
            $this->sql = "select * from barrio where id_municipio=".$id_municipio." order by descripcion";
            $this->result = $this->db->Execute($this->sql);
            if(!$this->result){
                echo array("mensaje"=>"Error Consultando los barrios". $this->db->ErrorMsg());
                return false;
            }
            else{
                return $this->result;
            }
        }
        else
            return false;            
    }

    function contarBarrio($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and (m.descripcion like '%".$post['search']['value']."%' or b.descripcion like '%".$post['search']['value']."%')";
            
        $this->sql = "select count(1) as total from barrio b join municipio m using(id_municipio) where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los barrios". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaBarrio($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and (m.descripcion like '%".$post['search']['value']."%' or b.descripcion like '%".$post['search']['value']."%')";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"1":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select b.id_barrio,b.id_municipio,b.descripcion as barrio,m.descripcion as municipio 
                    from barrio b join municipio m using(id_municipio) 
                    where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los barrios". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoBarrio($post){
        $this->sql = "INSERT INTO barrio(id_municipio,descripcion) VALUES(".$post['slt_municipio'].",'".$post['txt_descripcion']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarBarrio($post){
        $this->sql= "UPDATE barrio SET descripcion='".$post['txt_descripcion']."',id_municipio=".$post['slt_municipio']." WHERE id_barrio=".$post['id_barrio'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Barrio Actualizado");

    }

    function eliminarBarrio($id_barrio){
        $this->sql = "DELETE FROM barrio WHERE id_barrio=".$id_barrio;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Barrio Eliminado");
    }
}