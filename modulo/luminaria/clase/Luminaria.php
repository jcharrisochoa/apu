<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class Luminaria{

    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function contarLuminaria($post){
        if(!empty($post['municipio']))
            $q .= " and l.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and l.id_barrio=".$post['barrio'];
        if(!empty($post['tipo']))
            $q .= " and l.id_tipo_luminaria=".$post['tipo'];
        if(!empty($post['direccion']))
            $q .= " and l.direccion like '%".$post['direccion']."%'";
        if(!empty($post['poste_luminaria']))
            $q .= " and (l.luminaria_no like '%".$post['poste_luminaria']."%' or l.poste_no like '%".$post['poste_luminaria']."%')";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(l.fch_instalacion) >= '".$post['fechaini']."' and date(l.fch_instalacion) <= '".$post['fechafin']."'";

        $this->sql = "select count(1) as total        
                        from luminaria l
                        join municipio m using(id_municipio)
                        join barrio b using(id_barrio)
                        join estado_luminaria el using(id_estado_luminaria)
                        join tipo_luminaria tl using(id_tipo_luminaria) 
                        join tercero tc on (tc.id_tercero = l.id_tercero_registra)
                        where
                        1=1
                        ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando las luminarias". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function listarLuminaria($post){
        if(!empty($post['municipio']))
            $q .= " and l.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and l.id_barrio=".$post['barrio'];
        if(!empty($post['tipo']))
            $q .= " and l.id_tipo_luminaria=".$post['tipo'];
        if(!empty($post['direccion']))
            $q .= " and l.direccion like '%".$post['direccion']."%'";
        if(!empty($post['poste_luminaria']))
            $q .= " and (l.luminaria_no like '%".$post['poste_luminaria']."%' or l.poste_no like '%".$post['poste_luminaria']."%')";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(l.fch_instalacion) >= '".$post['fechaini']."' and date(l.fch_instalacion) <= '".$post['fechafin']."'";

        $this->sql = "select l.id_luminaria,l.poste_no,l.luminaria_no,m.descripcion as municipio,l.direccion,b.descripcion as barrio,l.latitud,l.longitud,l.fch_instalacion,
                    el.descripcion as estado,tl.descripcion as tipo,l.potencia,l.referencia,l.fch_registro,tc. usuario,
                    (select razon_social from tercero where id_tercero = l.id_tercero) as proveedor
                    from luminaria l
                    join municipio m using(id_municipio)
                    join barrio b using(id_barrio)
                    join estado_luminaria el using(id_estado_luminaria)
                    join tipo_luminaria tl using(id_tipo_luminaria) 
                    join tercero tc on (tc.id_tercero = l.id_tercero_registra) 
                    where
                    1=1
                    ".$q."
                    limit ".$post['start'].",".$post['length'];
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando las luminarias". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }
}