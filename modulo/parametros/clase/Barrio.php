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
}