<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Menu{
    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function menuTercero($id_tercero){

        if(!empty($id_tercero)){
            $q = " and mt.id_tercero=".$id_tercero;
        }

        $this->sql = "select m.id_menu_padre,m.id_menu,m.nombre,m.ruta_pagina,m.icono,
                    (select nombre from menu where id_menu=m.id_menu_padre) as menu_padre,
                    (select icono from menu where id_menu=m.id_menu_padre) as icono_padre
                    from menu_tercero mt,menu m
                    where
                    mt.id_menu = m.id_menu and
                    m.ejecutable='S' 
                    ".$q."
                    order by m.id_menu_padre,m.nombre;";
        $this->result = $this->db->Execute($this->sql);

        if(!$this->result){
            echo "Error Consultando el menu del usuario". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }
    
    function propiedadEjecutable($id_menu,$id_tercero){

        $this->sql = "select crear,actualizar,eliminar,imprimir 
                      from menu_tercero 
                      where 
                      id_menu=".$id_menu." and 
                      id_tercero=".$id_tercero;
        $this->result = $this->db->Execute($this->sql);

        if(!$this->result){
            echo "Error Consultando las propiedades del usuario". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }
}