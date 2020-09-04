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
            $q = "";
        if(!empty($post['municipio']))
            $q .= " and l.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and l.id_barrio=".$post['barrio'];
        if(!empty($post['tipo']))
            $q .= " and l.id_tipo_luminaria=".$post['tipo'];
        if(!empty($post['direccion']))
            $q .= " and l.direccion like '%".$post['direccion']."%'";
        if(!empty($post['poste_no']))
            $q .= " and l.poste_no = '".$post['poste_no']."'";
        if(!empty($post['luminaria_no']))
            $q .= " and l.luminaria_no = '".$post['luminaria_no']."'";
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
            $q = "";
        if(!empty($post['municipio']))
            $q .= " and l.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and l.id_barrio=".$post['barrio'];
        if(!empty($post['tipo']))
            $q .= " and l.id_tipo_luminaria=".$post['tipo'];
        if(!empty($post['direccion']))
            $q .= " and l.direccion like '%".$post['direccion']."%'";
        if(!empty($post['poste_no']))
            $q .= " and l.poste_no = '".$post['poste_no']."'";
        if(!empty($post['luminaria_no']))
            $q .= " and l.luminaria_no = '".$post['luminaria_no']."'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(l.fch_instalacion) >= '".$post['fechaini']."' and date(l.fch_instalacion) <= '".$post['fechafin']."'";


        if(!empty($post['order']['0']['column'])){
            $pos = $post['order']['0']['column'];	
            $campo = $post['columns'][$pos]['name'];
            $campo = ($campo=="")?"1":$campo;
        
            $q .= " order by ".$campo." ". $post['order']['0']['dir'];
        }

            
        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select l.id_luminaria,l.poste_no,l.luminaria_no,m.descripcion as municipio,l.direccion,b.descripcion as barrio,l.latitud,l.longitud,l.fch_instalacion,
                    el.descripcion as estado,tl.descripcion as tipo,l.potencia,l.referencia,l.fch_registro,tc. usuario,
                    (select razon_social from tercero where id_tercero = l.id_tercero_proveedor) as proveedor,
                    (select concat(nombre,' ',apellido) from tercero where id_tercero = l.id_tercero) as instalador,
                    l.id_municipio,l.id_barrio,l.id_tercero,id_tercero_proveedor,l.id_estado_luminaria,
                    l.id_tipo_luminaria
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
            echo "Error Consultando las luminarias". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function nuevaLuminaria($post){

        $latitud    = (!empty($post['txt_latitud']))?$post['txt_latitud']:"0";
        $longitud   = (!empty($post['txt_longitud']))?$post['txt_longitud']:"0";
        $proveedor  = (!empty($post['slt_proveedor']))?$post['slt_proveedor']:"null";

        if($this->validaExistePosteLuminaria($post['txt_poste_no'],$post['txt_luminaria_no'],$post['slt_municipio'])){
            return  array("estado"=>false,"data"=>"Estos datos ya estan registrados,Poste No: ".$post['txt_poste_no'].",Luminaria No: ".$post['txt_luminaria_no']);
        }
        else{
            $this->sql = "INSERT INTO luminaria(
                        poste_no,luminaria_no,id_tipo_luminaria,id_municipio,
                        direccion,id_barrio,latitud,longitud,id_tercero,referencia,potencia,
                        fch_instalacion,fch_registro,id_tercero_registra,id_estado_luminaria,id_tercero_proveedor
                        )
                        VALUES(
                        '".$post['txt_poste_no']."','".$post['txt_luminaria_no']."',".$post['slt_tipo_luminaria'].",
                        ".$post['slt_municipio'].",'".$post['txt_direccion']."',".$post['slt_barrio'].",".$latitud.",
                        ".$longitud.",null,null,null,'".$post['txt_fch_instalacion']."',
                        now(),".$_SESSION['id_tercero'].",".$post['slt_estado'].",".$proveedor."
                        );";

            $result = $this->db->Execute($this->sql);
            if(!$result)
                return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            else
                return  array("estado"=>true,"data"=>$this->db->insert_id());
        }
        
    }

    function editarLuminaria($post){
        $latitud    = (!empty($post['txt_latitud']))?$post['txt_latitud']:"0";
        $longitud   = (!empty($post['txt_longitud']))?$post['txt_longitud']:"0";
        $proveedor  = (!empty($post['slt_proveedor']))?$post['slt_proveedor']:"null";

        $this->sql = "UPDATE luminaria SET 
                    poste_no='".$post['txt_poste_no']."',
                    luminaria_no='".$post['txt_luminaria_no']."',
                    id_tipo_luminaria=".$post['slt_tipo_luminaria'].",
                    id_municipio=".$post['slt_municipio'].",
                    direccion='".$post['txt_direccion']."',
                    id_barrio=".$post['slt_barrio'].",
                    latitud=".$latitud.",
                    longitud=".$longitud.",
                    fch_instalacion='".$post['txt_fch_instalacion']."',
                    id_estado_luminaria=".$post['slt_estado'].",
                    id_tercero_proveedor=".$proveedor."
                    WHERE
                    id_luminaria=".$post['id_luminaria'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Punto luminico actualizado");
    }

    function eliminarLuminaria($id_luminaria){
        $this->sql = "delete from luminaria where id_luminaria=".$id_luminaria;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Punto luminico eliminado");
    }

    function validaExistePosteLuminaria($poste,$luminaria,$municipio){
        $this->sql = "select id_luminaria 
                    from luminaria 
                    where 
                    poste_no='".$poste."' and 
                    luminaria_no='".$luminaria."' and 
                    id_municipio=".$municipio;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error validando luminaria". $this->db->ErrorMsg();
            return false;
        }
        else{
            return ($this->result->NumRows()==0)?false:true;
        }
    }
}