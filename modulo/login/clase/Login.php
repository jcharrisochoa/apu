<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Login{
    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function login($usuario,$clave){
        if(!empty($usuario) and !empty($clave)){
            $this->sql = "select t.id_tercero,t.nombre,t.apellido,t.usuario,t.clave
                            from tercero t
                            where
                            usuario='".$usuario."' and
                            clave = md5('".$clave."') and
                            es_usuario='S';";
            $this->result = $this->db->Execute($this->sql);

            if(!$this->result){
                return array("estado"=>false,"data"=>"","mensaje"=>"Error Consultando el usuario");
            }
            else{
                return array("estado"=>true,"data"=>$this->result,"mensaje"=>"");
            }
        }
        else{
            return array("estado"=>false,"data"=>"","mensaje"=>"Error Datos Incompletos");
        }
    }
    function cambiarClaveUsuario($id_tercero,$clave){
        if(!empty($id_tercero) and !empty($clave)){
            $this->sql = "update tercero set clave = md5('".$clave."')  where id_tercero='".$id_tercero."' and es_usuario='S';";
            $this->result = $this->db->Execute($this->sql);

            if(!$this->result){
                return array("estado"=>false,"data"=>"","mensaje"=>"Error actualizando la clave");
            }
            else{
                return array("estado"=>true,"data"=>$this->result,"mensaje"=>"Clave actualizada");
            }
        }
        else{
            return array("estado"=>false,"data"=>"","mensaje"=>"Error Datos Incompletos");
        }
    }
}

