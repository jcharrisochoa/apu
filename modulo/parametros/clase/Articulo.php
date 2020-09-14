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
}
