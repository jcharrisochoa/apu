
<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";

class TipoActividad{

    private $sql;
    public $db;
    private $result;
    
    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function contarTipoActividad($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";
            
        $this->sql = "select count(1) as total from tipo_actividad where 1=1 ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los tipos de actividad". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function tablaTipoActividad($post){
        $q = "";
       
        if(!empty($post['search']['value']))
            $q .= " and descripcion like '%".$post['search']['value']."%'";

        $pos = $post['order']['0']['column'];	
        $campo = $post['columns'][$pos]['name'];
        $campo = ($campo=="")?"2":$campo;
    
        $q .= " order by ".$campo." ". $post['order']['0']['dir'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select * from tipo_actividad where 1=1 ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de actividad". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function listarTipoActividad($instalacion){
        $q = "";

        if(!empty($instalacion)){
            $q .= " and instalacion='".$instalacion."'";
        }

        $this->sql = "select * from tipo_actividad where 1=1 ".$q." order by descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los tipos de actividad". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }        
    }

    function nuevoTipoActividad($post){
        $this->sql = "INSERT INTO tipo_actividad(descripcion,instalacion,preventivo,correctivo) VALUES('".$post['txt_descripcion']."','".$post['slt_instalacion']."',
        ,'".$post['slt_preventivo']."','".$post['slt_correctivo']."');";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function editarTipoActividad($post){
        $this->sql= "UPDATE tipo_actividad SET descripcion='".$post['txt_descripcion']."',instalacion='".$post['slt_instalacion']."',
        preventivo='".$post['slt_preventivo']."',correctivo='".$post['slt_correctivo']."' WHERE id_tipo_actividad=".$post['id_tipo_actividad'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo Actividad Actualizado");

    }

    function eliminarTipoActividad($id_tipo_actividad){
        $this->sql = "DELETE FROM tipo_actividad WHERE id_tipo_actividad=".$id_tipo_actividad;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Tipo Actividad Eliminado");
    }
}