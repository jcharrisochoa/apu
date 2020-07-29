<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Municipio{
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
}