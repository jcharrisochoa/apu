<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";
require_once dirname(__FILE__)."/General.php";
class Menu extends General{
    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
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

    function menuTercero($id_tercero,$buscar=""){
        $q = "";
        if(!empty($id_tercero)){
            $q = " and mt.id_tercero=".$id_tercero;
        }
        
        if(!empty($buscar))
            $q .= " and m.nombre like '%".$buscar."%' ";

        $this->sql = "select m.id_menu_padre,m.id_menu,m.nombre,m.ruta_pagina,m.icono,
                    (select nombre from menu where id_menu=m.id_menu_padre) as menu_padre,
                    (select icono from menu where id_menu=m.id_menu_padre) as icono_padre,
                    mt.crear,mt.actualizar,mt.eliminar,mt.imprimir
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
    
    function menuDisponible($id_tercero,$buscar){
        $q = "";
        if(!empty($buscar))
            $q = "m.nombre like '%".$buscar."%' and ";

        $this->sql = "select m.id_menu_padre,m.id_menu,m.nombre,m.ruta_pagina,m.icono,
                    (select nombre from menu where id_menu=m.id_menu_padre) as menu_padre,
                    (select icono from menu where id_menu=m.id_menu_padre) as icono_padre
                    from menu m
                    where
                    m.ejecutable='S'  and
                    ".$q."
                    not exists (select id_menu from menu_tercero where id_tercero=".$id_tercero." and id_menu=m.id_menu)
                    order by m.id_menu_padre,m.nombre;";
        $this->result = $this->db->Execute($this->sql);

        if(!$this->result){
            echo "Error Consultando el menu disponible del usuario". $this->db->ErrorMsg();
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

    function retirarPerfil($post){
        $sw = false;
        $this->iniciarTransaccion();
        $jsonPerfil = json_decode($this->fix($post['menu_array']));
        foreach($jsonPerfil as $perfil){
            $this->sql = "delete from menu_tercero where id_tercero=".$post['id_tercero']." and id_menu=".$perfil->id_menu;
            $this->result = $this->db->Execute($this->sql);
            if(!$this->result){                
                $array = array("estado"=>false,"data"=>$this->db->ErrorMsg());
                $this->devolverTransaccion();
                $sw = true;
                break;
            }
        }
        if(!$sw){
            $array = array("estado"=>true,"data"=>"");
            $this->finalizarTransaccion();
        }
        return $array;
    }

    function agregarPerfil($post){
        $sw = false;
        $this->iniciarTransaccion();
        $jsonPerfil = json_decode($this->fix($post['menu_array']));
        foreach($jsonPerfil as $perfil){
            $this->sql = "INSERT INTO menu_tercero(
                        id_menu,id_tercero,crear,actualizar,eliminar,imprimir
                        )
                        VALUES(
                        ".$perfil->id_menu.",".$post['id_tercero'].",'".$perfil->crear."','".$perfil->editar."',
                        '".$perfil->eliminar."','".$perfil->imprimir."'  
                        );";
            $this->result = $this->db->Execute($this->sql);
            if(!$this->result){                
                $array = array("estado"=>false,"data"=>$this->db->ErrorMsg());
                $this->devolverTransaccion();
                $sw = true;
                break;
            }
        }
        if(!$sw){
            $array = array("estado"=>true,"data"=>"");
            $this->finalizarTransaccion();
        }
        return $array;
    }
}