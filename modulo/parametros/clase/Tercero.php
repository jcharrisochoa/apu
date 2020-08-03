<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Tercero{
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }
    
}