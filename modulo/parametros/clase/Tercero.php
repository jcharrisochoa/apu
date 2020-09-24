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

    function iniciarTransaccion(){
        $this->db->Execute("BEGIN;");
    }

    function finalizarTransaccion(){
        $this->db->Execute("COMMIT;");
    }

    function devolverTransaccion(){
        $this->db->Execute("ROLLBACK;");
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

    function contarEmpleado($post){
        $q = "";
        if(!empty($post['buscar']))
            $q = " and ( t.nombre like '%".$post['buscar']."%' or t.apellido like '%".$post['buscar']."%') ";

        $this->sql = "select count(1) as total
                        from tercero t
                        join tipo_identificacion ti using(id_tipo_identificacion)
                        join municipio m using(id_municipio)
                        join departamento d using(id_departamento)
                        where 
                        es_cliente='N' and es_proveedor='N' ".$q."";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){            
            return array("estado"=>false,"mensaje"=>"Error Consultando los empleados". $this->db->ErrorMsg(),"data"=>"");
        }
        else{
            return array("estado"=>true,"mensaje"=>"","data"=>$this->result->fields['total']);
        }
    }

    function listarEmpleado($post){
        $q = "";
        if(!empty($post['buscar']))
            $q = " and ( t.nombre like '%".$post['buscar']."%' or t.apellido like '%".$post['buscar']."%') ";
        $this->sql = "select t.id_tercero,t.id_tipo_identificacion,t.identificacion,t.nombre,t.apellido,
                        t.direccion,t.email,t.telefono,t.es_empleado,t.es_usuario,t.usuario,t.id_tercero_registra,
                        t.fch_registro,m.descripcion as municipio,d.descripcion as departamento,ti.abreviatura,t.ejecuta_labor_tecnica,
                        t.super_usuario
                        from tercero t
                        join tipo_identificacion ti using(id_tipo_identificacion)
                        join municipio m using(id_municipio)
                        join departamento d using(id_departamento)
                        where 
                        es_cliente='N' and es_proveedor='N' ".$q."
                        order by t.nombre,t.apellido limit ".$post['start'].",".$post['length'];
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            return array("estado"=>false,"mensaje"=>"Error Consultando los empleados". $this->db->ErrorMsg(),"data"=>"");
        }
        else{
            return array("estado"=>true,"mensaje"=>"","data"=>$this->result);
        }
    }

    function consultaExisteUsuario($usuario){
        $this->sql = "select id_tercero from tercero where usuario='".$usuario."' and es_usuario='S'";
        $result = $this->db->Execute($this->sql);
        if(!$result){
            return false;
        }
        else{
            return ($result->NumRows()==0)?false:true;
        }
    }

    function nuevaTercero($post,$file){
       
        $sw = false;
        if(isset($post["chk_usuario"])){
            if($this->consultaExisteUsuario($post['txt_usuario'])){
                return  array("estado"=>false,"data"=>"El usuario ".$post['txt_usuario']." ya se encuentra registrado, intente con otro");
            }
            else{
                $sw = true;
            }
        }
        else{
            $sw = true;
        }

        if($sw){  
            $q  = "";
            $campo = "";
            if(!empty($file['foto']['name'])){
                $fileHandle  = fopen($file['foto']['tmp_name'],"r");
                $fileContent = fread($fileHandle,$file['foto']['size']);
                $fileContent = addslashes($fileContent); 

                $ext    = substr($file['foto']['name'],strripos($file['foto']['name'],"."),strlen($file['foto']['name']));
                $nombre = str_replace(' ','',$file['foto']['name']);

                $q = ",'".$file['foto']['type']."',".$file['foto']['size'].",'".$ext."','".$nombre."','". $fileContent."'";
                $campo = ",tipo_foto,tamano_foto,extension_foto,nombre_foto,foto";
            }

            $post['txt_nombre']         = (!empty($post['txt_nombre']))?"'". $post['txt_nombre']."'":"null";
            $post['txt_apellido']       = (!empty($post['txt_apellido']))?"'". $post['txt_apellido']."'":"null";
            $post['txt_email']          = (!empty($post['txt_email']))?"'". $post['txt_email']."'":"null";
            $post['txt_telefono']       = (!empty($post['txt_telefono']))?"'". $post['txt_telefono']."'":"null";
            $post['txt_razon_social']   = (!empty($post['txt_razon_social']))?"'". $post['txt_razon_social']."'":"null";
            $post['txt_usuario']        = (!empty($post['txt_usuario']))?"'". $post['txt_usuario']."'":"null";
            $post['txt_clave']          = (!empty($post['txt_clave']))?"'". $post['txt_clave']."'":"null";

            $post["chk_usuario"]    = (isset($post["chk_usuario"]))?"S":"N";
            $post["chk_empleado"]   = (isset($post["chk_empleado"]))?"S":"N";
            $post["chk_cliente"]    = (isset($post["chk_cliente"]))?"S":"N";
            $post["chk_proveedor"]  = (isset($post["chk_proveedor"]))?"S":"N";
            $post["chk_tecnico"]    = (isset($post["chk_tecnico"]))?"S":"N";

            $this->sql = "INSERT INTO tercero(
                        id_tipo_identificacion,identificacion,nombre,apellido,direccion,email,telefono,id_municipio,razon_social,
                        es_cliente,es_proveedor,es_empleado,es_usuario,clave,usuario,id_tercero_registra,fch_registro,ejecuta_labor_tecnica,
                        super_usuario,estado".$campo."
                        )
                        VALUES(
                        ".$post['slt_tipo_identificacion'].",".$post['txt_identificacion'].",".$post['txt_nombre'].",".$post['txt_apellido'].",
                        '".$post['txt_direccion']."',".$post['txt_email'].",".$post['txt_telefono'].",".$post['slt_municipio'].",". $post['txt_razon_social'].",
                        '".$post["chk_cliente"]."','".$post["chk_proveedor"]."','".$post["chk_empleado"]."','".$post["chk_usuario"]."',md5(". $post['txt_clave']."),
                        ".$post['txt_usuario'].",".$_SESSION['id_tercero'].",now(),'".$post['chk_tecnico']."','N','".$post["slt_estado"]."'".$q."
                        );";

            $result = $this->db->Execute($this->sql);
            if(!$result){
                return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            }
            else{
                return  array("estado"=>true,"data"=>$this->db->insert_id());
            }
        }     
    }

    function editarTercero($post,$file){
        $sw = false;
        //--el tercero tiene usuario?
        $this->sql = "select usuario from tercero where id_tercero=".$post['id_tercero'];
        $result = $this->db->Execute($this->sql);
        if($result->fields['usuario']=="" and $post['txt_usuario'] !=""){ //convertir un empleado en usuario
            if($this->consultaExisteUsuario($post['txt_usuario'])){
                return  array("estado"=>false,"data"=>"El usuario ".$post['txt_usuario']." ya se encuentra registrado, intente con otro");
            }
            else{
                $sw = true;
            }
        }
        else{
            $sw = true;
        }

        if($sw){  
            $q  = "";
            if(!empty($file['foto']['name'])){
                $fileHandle  = fopen($file['foto']['tmp_name'],"r");
                $fileContent = fread($fileHandle,$file['foto']['size']);
                $fileContent = addslashes($fileContent); 

                $ext    = substr($file['foto']['name'],strripos($file['foto']['name'],"."),strlen($file['foto']['name']));
                $nombre = str_replace(' ','',$file['foto']['name']);

                $q = ",tipo_foto='".$file['foto']['type']."',tamano_foto=".$file['foto']['size'].",extension_foto='".$ext."',nombre_foto='".$nombre."',foto='". $fileContent."'";
            }

            $post['txt_nombre']         = (!empty($post['txt_nombre']))?"'". $post['txt_nombre']."'":"null";
            $post['txt_apellido']       = (!empty($post['txt_apellido']))?"'". $post['txt_apellido']."'":"null";
            $post['txt_email']          = (!empty($post['txt_email']))?"'". $post['txt_email']."'":"null";
            $post['txt_telefono']       = (!empty($post['txt_telefono']))?"'". $post['txt_telefono']."'":"null";
            $post['txt_razon_social']   = (!empty($post['txt_razon_social']))?"'". $post['txt_razon_social']."'":"null";
            $post['txt_usuario']        = (!empty($post['txt_usuario']))?"'". $post['txt_usuario']."'":"usuario";
            $post['txt_clave']          = (!empty($post['txt_clave']))?"md5('". $post['txt_clave']."')":"clave";

            $post["chk_usuario"]    = (isset($post["chk_usuario"]) or !empty($post['txt_usuario']))?"S":"N";
            $post["chk_empleado"]   = (isset($post["chk_empleado"]))?"S":"N";
            $post["chk_cliente"]    = (isset($post["chk_cliente"]))?"S":"N";
            $post["chk_proveedor"]  = (isset($post["chk_proveedor"]))?"S":"N";
            $post["chk_tecnico"]    = (isset($post["chk_tecnico"]))?"S":"N";

            $this->sql = "update tercero set
                        id_tipo_identificacion=".$post['slt_tipo_identificacion'].",
                        identificacion=".$post['txt_identificacion'].",
                        nombre=".$post['txt_nombre'].",
                        apellido=".$post['txt_apellido'].",
                        direccion='".$post['txt_direccion']."',
                        email=".$post['txt_email'].",
                        telefono=".$post['txt_telefono'].",
                        id_municipio=".$post['slt_municipio'].",
                        razon_social=". $post['txt_razon_social'].",
                        es_cliente='".$post["chk_cliente"]."',
                        es_proveedor='".$post["chk_proveedor"]."',
                        es_empleado='".$post["chk_empleado"]."',
                        es_usuario='".$post["chk_usuario"]."',
                        usuario=".$post['txt_usuario'].",
                        clave=".$post['txt_clave'].",
                        ejecuta_labor_tecnica='".$post['chk_tecnico']."',
                        estado='".$post['slt_estado']."'
                        ".$q."
                        where
                        id_tercero=".$post['id_tercero'];

            $result = $this->db->Execute($this->sql);
            if(!$result){
                return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            }
            else{
                return array("estado"=>true,"data"=>"Tercero actualizado");
            }
        }     
    }

    function eliminarTercero($id_tercero){
        $this->iniciarTransaccion();

        $this->sql = "delete from menu_tercero where id_tercero=".$id_tercero;
        $result = $this->db->Execute($this->sql);
        if(!$result){
            $this->devolverTransaccion();
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        }
        else{
            $this->sql = "delete from tercero where id_tercero=".$id_tercero;
            $result = $this->db->Execute($this->sql);
            if(!$result){
                $this->devolverTransaccion();
                return  array("estado"=>false,"data"=>"No es posible eliminar el registro, Tercero asociado a registro de otros m&oacute;dulos");
            }
            else{
                $this->finalizarTransaccion();
                return  array("estado"=>true,"data"=>"Tercero eliminado");
            }
        }
    }

    function consultarFoto($id_tercero){
        $this->sql = "select tipo_foto,tamano_foto,extension_foto,nombre_foto,foto from tercero where id_tercero=".$id_tercero;
        $result = $this->db->Execute($this->sql);
        if(!$result){
            return false;
        }
        else{
            return $result;
        }
    }

    function buscarTercero($id_tercero){
        $this->sql = "select t.id_tercero,t.id_tipo_identificacion,t.identificacion,t.nombre,t.apellido,t.razon_social,t.id_municipio,
        t.direccion,t.email,t.telefono,t.es_cliente,t.es_proveedor,t.es_empleado,t.es_usuario,t.usuario,t.id_tercero_registra,
        t.fch_registro,m.descripcion as municipio,d.descripcion as departamento,ti.abreviatura,t.ejecuta_labor_tecnica,t.estado,
        (select concat(nombre,' ',coalesce(apellido,'')) from tercero where id_tercero = t.id_tercero_registra) as usuario_registra
        from tercero t
        join tipo_identificacion ti using(id_tipo_identificacion)
        join municipio m using(id_municipio)
        join departamento d using(id_departamento) where id_tercero=".$id_tercero;
        $result = $this->db->Execute($this->sql);
        if(!$result){
            return false;
        }
        else{
            return $result;
        }
    }
    
}