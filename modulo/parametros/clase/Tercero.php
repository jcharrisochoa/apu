<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Tercero{
    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function listarTecnico(){
        $this->sql = "select nombre,apellido,id_tercero from tercero where es_empleado='S' and ejecuta_labor_tecnica='S' order by nombre,apellido";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tecnicos". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }
    
}