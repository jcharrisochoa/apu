<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class MedicionLuminaria{
    private $sql;
    public $db;
    private $result;

    function iniciarTransaccion(){
        $this->db->Execute("BEGIN;");
    }

    function finalizarTransaccion(){
        $this->db->Execute("COMMIT;");
    }

    function devolverTransaccion(){
        $this->db->Execute("ROLLBACK;");
    }

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function contarMedicionLuminaria($post){
            $q = "";
        if(!empty($post['tipo']))
            $q .= " and ml.tipo ='".$post['tipo']."'";        

        $this->sql = "select count(*) as total
                    from medicion_luminaria ml 
                    join clase_iluminacion ci using(id_clase_iluminacion)
                    join tercero t using(id_tercero)
                    where
                    ml.id_luminaria = ".$post['id_luminaria']."
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando las mediciones de luminarias". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function listarMedicionLuminaria($post){
            $q = "";
        if(!empty($post['tipo']))
        $q .= " and ml.tipo ='".$post['tipo']."'";  
 

        if(!empty($post['order']['0']['column'])){
            $pos = $post['order']['0']['column'];	
            $campo = $post['columns'][$pos]['name'];
            $campo = ($campo=="")?"1":$campo;
        
            $q .= " order by ".$campo." ". $post['order']['0']['dir'];
        }

            
        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select ml.*,ci.descripcion as clase_iluminacion,ci.abreviatura ,t.usuario
                    from medicion_luminaria ml 
                    join clase_iluminacion ci using(id_clase_iluminacion)
                    join tercero t using(id_tercero)
                    where
                    ml.id_luminaria = ".$post['id_luminaria']."
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando las mediciones de luminarias". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function nuevaMedicion($post){
        $this->sql = "INSERT INTO medicion_luminaria(
            id_luminaria,id_clase_iluminacion, fch_visita, hm, sm, wm, ilum_lux, uniformidad, cumple_retilap, id_tercero, fch_registro, tipo
            )
            VALUES(
            ".$post['id_luminaria'].",".$post['slt_clase_iluminacion'].",'".$post['txt_fecha']."',".$post['txt_hm'].", ".$post['txt_sm'].",".$post['txt_wm'].", 
            ".$post['txt_ilum_lux'].",".$post['txt_uniformidad'].", '".$post['slt_cumple_retilap']."',".$_SESSION['id_tercero'].", now(), '".$post['tipo_medicion']."'
            );";

        $result = $this->db->Execute($this->sql);
        if(!$result){
            $array = array("estado"=>false,"data"=>$this->db->ErrorMsg());
        }
        else{
            $id_luminaria = $this->db->insert_id();
            $array =  array("estado"=>true,"data"=> $id_luminaria);
        }
        return $array;
    }

    function eliminarMedicion($id_medicion){
        $this->sql = "delete from medicion_luminaria where id_medicion=".$id_medicion;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Dato eliminado");
    }
}
?>