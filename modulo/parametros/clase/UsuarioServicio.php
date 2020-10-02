<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class UsuarioServicio{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function contarUsuarioServicio($post){
        $q = "";
       
        if(!empty($post['identificacion']))
            $q .= " and identificacion like '%".$post['identificacion']."%'";
        if(!empty($post['nombre']))
            $q .= " and nombre like '%".$post['nombre']."%'";
        if(!empty($post['direccion']))
            $q .= " and direccion like '%".$post['direccion']."%'";
            
        $this->sql = "select count(1) as total FROM usuario_servicio us 
                    join tipo_identificacion ti using(id_tipo_identificacion)
                    where
                    1=1".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los usuario". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaUsuarioServicio($post){
        $q = "";
       
        if(!empty($post['identificacion']))
            $q .= " and identificacion like '%".$post['identificacion']."%'";
        if(!empty($post['nombre']))
            $q .= " and nombre like '%".$post['nombre']."%'";
        if(!empty($post['direccion']))
            $q .= " and direccion like '%".$post['direccion']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"3":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "SELECT us.id_usuario_servicio,us.id_tipo_identificacion,us.identificacion,us.nombre,us.direccion,us.telefono,ti.abreviatura 
                    FROM usuario_servicio us 
                    join tipo_identificacion ti using(id_tipo_identificacion)
                    where
                    1=1".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los usuarios ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }
}