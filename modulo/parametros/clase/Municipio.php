<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Municipio{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarMunicipioContrato(){
        $this->sql = "select * from municipio where tiene_contrato='S' order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los municipios". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function contarMunicipio($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and (m.descripcion like '%".$post['search']['value']."%' or d.descripcion like '%".$post['search']['value']."%')";

        $this->sql = "select count(1) as total 
                        from municipio m
                        join departamento d using(id_departamento)
                        where
                        1=1 ".$q;
        
                        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los municipios". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaMunicipio($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and (m.descripcion like '%".$post['search']['value']."%' or d.descripcion like '%".$post['search']['value']."%')";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"3":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select m.id_municipio,m.id_departamento,m.descripcion as municipio,m.tiene_contrato,m.latitud,m.longitud,
                    d.descripcion as departamento 
                    from municipio m
                    join departamento d using(id_departamento)
                    where
                    1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los municipio". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoMunicipio($post){
        $latitud    = (!empty($post['txt_latitud']))?$post['txt_latitud']:"0";
        $longitud   = (!empty($post['txt_longitud']))?$post['txt_longitud']:"0";

        $this->sql = "INSERT INTO municipio(
            id_departamento,descripcion,tiene_contrato,latitud,longitud
            ) 
            VALUES(
            ".$post['slt_departamento'].",'".$post['txt_descripcion']."',
            '".$post['slt_tiene_contrato']."',".$latitud.",".$longitud."
            );";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarMunicipio($post){
        $latitud    = (!empty($post['txt_latitud']))?$post['txt_latitud']:"0";
        $longitud   = (!empty($post['txt_longitud']))?$post['txt_longitud']:"0";

        $this->sql= "UPDATE municipio SET 
        id_departamento=".$post['slt_departamento'].",
        descripcion='".$post['txt_descripcion']."',
        tiene_contrato='".$post['slt_tiene_contrato']."',
        latitud=".$latitud.",
        longitud=".$longitud."
        WHERE 
        id_municipio=".$post['id_municipio'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Municipio Actualizado");

    }

    function eliminarMunicipio($id_municipio){
        $this->sql = "DELETE FROM municipio WHERE id_municipio=".$id_municipio;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Municipio Eliminado");
    }
}