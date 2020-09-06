<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class PQR{

    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function buscarUsuarioServicio($identificacion){
        $this->sql = "SELECT id_usuario_servicio,nombre,direccion,telefono,email 
                      FROM usuario_servicio 
                      where 
                      identificacion=".$identificacion;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result)
            return false;
        else
            return $this->result;
    }

    function iniciarTransaccion(){
        $this->db->Execute("BEGIN;");
    }
    function finalizarTransaccion(){
        $this->db->Execute("COMMIT;");
    }
    function devolverTransaccion(){
        $this->db->Execute("ROLLBACK;");
    }
}