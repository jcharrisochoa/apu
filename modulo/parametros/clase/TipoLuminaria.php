<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class TipoLuminaria{
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }
    function listarTipoLuminaria(){
        $this->sql = "select * from tipo_luminaria order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de luminaria". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }
}