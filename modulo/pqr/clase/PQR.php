<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";
require_once dirname(__FILE__)."/../../parametros/clase/General.php";
class PQR extends General{

    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        //$global = new Global();
    }

    function contarPQR($post){
        $q = "";
        if(!empty($post['id_pqr']))
            $q .= " and p.id_pqr =".$post['id_pqr'];
        if(!empty($post['municipio']))
            $q .= " and p.id_municipio=".$post['municipio'];
        if(!empty($post['tipo_pqr']))
            $q .= " and p.id_tipo_pqr=".$post['tipo_pqr'];
        if(!empty($post['tipo_reporte']))
            $q .= " and p.id_tipo_reporte=".$post['tipo_reporte'];
        if(!empty($post['estado']))
            $q .= " and p.id_estado_pqr=".$post['estado'];
        if(!empty($post['nombre']))
            $q .= " and p.nombre_usuario_servicio like '%".$post['nombre']."%'";
        if(!empty($post['direccion']))
            $q .= " and p.direccion_reporte like '%".$post['direccion']."%'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(p.fch_pqr) >= '".$post['fechaini']."' and date(p.fch_pqr) <= '".$post['fechafin']."'";

        $this->sql = "select count(1) as total        
                    from pqr p
                    join municipio m using(id_municipio) 
                    left join luminaria l using(id_luminaria)
                    join tipo_pqr tp using(id_tipo_pqr)
                    join tipo_reporte tr using(id_tipo_reporte)
                    join medio_recepcion_pqr mr using(id_medio_recepcion_pqr)
                    left join usuario_servicio us using(id_usuario_servicio)
                    left join tipo_identificacion ti using(id_tipo_identificacion)
                    join estado_pqr ep using(id_estado_pqr)
                    join tercero tc on(p.id_tercero_registra = tc.id_tercero)
                    where
                    1=1
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando las PQR ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function listarPQR($post){
        $q = "";
        if(!empty($post['id_pqr']))
            $q .= " and p.id_pqr =".$post['id_pqr'];
        if(!empty($post['municipio']))
            $q .= " and p.id_municipio=".$post['municipio'];
        if(!empty($post['tipo_pqr']))
            $q .= " and p.id_tipo_pqr=".$post['tipo_pqr'];
        if(!empty($post['tipo_reporte']))
            $q .= " and p.id_tipo_reporte=".$post['tipo_reporte'];
        if(!empty($post['estado']))
            $q .= " and p.id_estado_pqr=".$post['estado'];
        if(!empty($post['nombre']))
            $q .= " and p.nombre_usuario_servicio like '%".$post['nombre']."%'";
        if(!empty($post['direccion']))
            $q .= " and p.direccion_reporte like '%".$post['direccion']."%'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(p.fch_pqr) >= '".$post['fechaini']."' and date(p.fch_pqr) <= '".$post['fechafin']."'";

        
        if(!empty($post['order']['0']['column'])){
            $pos = $post['order']['0']['column'];	
            $campo = $post['columns'][$pos]['name'];
            $campo = ($campo=="")?"1":$campo;
        
            $q .= " order by ".$campo." ". $post['order']['0']['dir'];
        }

            
        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select p.*,m.descripcion as municipio,tp.descripcion as tipo_pqr,tr.descripcion as tipo_reporte,
                    mr.descripcion as medio_recepcion,us.id_tipo_identificacion,us.identificacion,us.nombre,us.direccion,
                    us.telefono,us.email,ti.abreviatura,ep.descripcion as estado,tc.usuario,l.poste_no,l.luminaria_no,
                    ep.permitir_edicion,ep.permitir_eliminar,ti.abreviatura,p.id_barrio_reporte,
                    p.direccion_reporte,p.nombre_usuario_servicio,p.direccion_usuario_servicio,p.telefono_usuario_servicio,p.fch_cierre,
                    (select descripcion from barrio where id_municipio=p.id_municipio and id_barrio=p.id_barrio_reporte) as barrio_reporte,
                    (select nombre from tercero where id_tercero=p.id_tercero_cierra) as tercero_cierra
                    from pqr p
                    join municipio m using(id_municipio) 
                    left join luminaria l using(id_luminaria)
                    join tipo_pqr tp using(id_tipo_pqr)
                    join tipo_reporte tr using(id_tipo_reporte)
                    join medio_recepcion_pqr mr using(id_medio_recepcion_pqr)
                    left join usuario_servicio us using(id_usuario_servicio)
                    left join tipo_identificacion ti using(id_tipo_identificacion)
                    join estado_pqr ep using(id_estado_pqr)
                    join tercero tc on(p.id_tercero_registra = tc.id_tercero)
                    where
                    1=1
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando las PQR ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function buscarUsuarioServicio($identificacion){
        $this->sql = "SELECT id_usuario_servicio,id_tipo_identificacion,nombre,direccion,telefono,email 
                      FROM usuario_servicio 
                      where 
                      identificacion=".$identificacion;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result)
            return false;
        else
            return $this->result;
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

    function nuevaPQR($post,$file){

        $id_luminaria    = (!empty($post['id_luminaria']))?$post['id_luminaria']:"null";
        $sw = true;
        $this->iniciarTransaccion();

        if(empty($post['id_usuario_servicio']) and !empty($post['txt_identificacion'])){
            $array = $this->crearUsuarioServicio($post);
            if(!$array['estado']){
                $this->devolverTransaccion();
                $sw = false;
                return $array;
            }
            else{
                $id_usuario_servicio = $array['data'];
            }
        }
        else{
            if(!empty($post['chk_actualizar_datos']) and !empty($post['id_usuario_servicio']) and !empty($post['txt_identificacion'])){
                $array = $this->actualizarUsuarioServicio($post);
                if(!$array['estado']){
                    $this->devolverTransaccion();
                    $sw = false;
                    return $array;
                }
                else{
                    $id_usuario_servicio = $post['id_usuario_servicio'];
                }
            }
            else{
                $id_usuario_servicio = ($post['id_usuario_servicio']!="")?$post['id_usuario_servicio']:"null";
            }
        }

        if($sw){
            $this->sql = "INSERT INTO pqr(
                        id_municipio,id_tipo_pqr,id_tipo_reporte,id_medio_recepcion_pqr,
                        id_usuario_servicio,id_luminaria,comentario,id_tercero_registra,
                        fch_registro,fch_pqr,id_estado_pqr,id_barrio_reporte,direccion_reporte,
                        nombre_usuario_servicio,direccion_usuario_servicio,telefono_usuario_servicio
                        )
                        VALUES(
                        ".$post['slt_municipio'].",".$post['slt_tipo_pqr'].",".$post['slt_tipo_reporte'].",".$post['slt_medio_recepcion'].",
                        ".$id_usuario_servicio.",".$id_luminaria.",'".$post['txt_comentario']."',".$_SESSION['id_tercero'].",
                        now(),'".$post['fch_pqr']."',".$post['slt_estado_pqr'].",".$post['slt_barrio_reporte'].",'".$post['txt_direccion_reporte']."',
                        '".$post['txt_nombre']."','".$post['txt_direccion']."','".$post['txt_telefono']."'
                        );";

            $result = $this->db->Execute($this->sql);
            if(!$result){
                $this->devolverTransaccion();
                return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            }
            else{
                $id_pqr = $this->db->insert_id();
                if(!empty($file['archivo']['name'])){
                    $archivo = $this->guardarArchivoPQR($id_pqr,$_SESSION['id_tercero'],$file);
                    if(!$archivo['estado']){
                        $this->devolverTransaccion();
                        return $archivo;
                    }
                    else{
                        $this->finalizarTransaccion();
                        return  array("estado"=>true,"data"=>$id_pqr);
                    }
                }
                else{
                    $this->finalizarTransaccion();
                    return  array("estado"=>true,"data"=>$id_pqr);
                }                
            }                
        }        
    }

    function editarPQR($post,$file){
        $id_luminaria    = (!empty($post['id_luminaria']))?$post['id_luminaria']:"null";
        $sw = true;
        $this->iniciarTransaccion();

        //--Datos del usuario externo
        if(empty($post['id_usuario_servicio']) and !empty($post['txt_identificacion'])){
            $array = $this->crearUsuarioServicio($post);
            if(!$array['estado']){
                $this->devolverTransaccion();
                $sw = false;
                return $array;
            }
            else{
                $id_usuario_servicio = $array['data'];
            }
        }
        else{
            if(!empty($post['chk_actualizar_datos']) and !empty($post['id_usuario_servicio']) and !empty($post['txt_identificacion'])){
                $array = $this->actualizarUsuarioServicio($post);
                if(!$array['estado']){
                    $this->devolverTransaccion();
                    $sw = false;
                    return $array;
                }
                else{
                    $id_usuario_servicio = $post['id_usuario_servicio'];
                }
            }
            else{
                $id_usuario_servicio = ($post['id_usuario_servicio']!="")?$post['id_usuario_servicio']:"null";
            }
        }
        //--

        $this->sql = "UPDATE pqr SET 
                    id_municipio=".$post['slt_municipio'].",
                    id_tipo_pqr=".$post['slt_tipo_pqr'].",
                    id_tipo_reporte=".$post['slt_tipo_reporte'].",
                    id_medio_recepcion_pqr=".$post['slt_medio_recepcion'].",
                    id_usuario_servicio=".$id_usuario_servicio.",
                    id_luminaria=".$id_luminaria.",
                    comentario='".$post['txt_comentario']."',
                    fch_pqr='".$post['fch_pqr']."',
                    id_estado_pqr=".$post['slt_estado_pqr'].",
                    id_barrio_reporte=".$post['slt_barrio_reporte'].",
                    direccion_reporte='".$post['txt_direccion_reporte']."',
                    nombre_usuario_servicio='".$post['txt_nombre']."',
                    direccion_usuario_servicio='".$post['txt_direccion']."',
                    telefono_usuario_servicio='".$post['txt_telefono']."'
                    WHERE
                    id_pqr=".$post['id_pqr'].";";

        $result = $this->db->Execute($this->sql);
        if(!$result){
            $this->devolverTransaccion();
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        }
        else{
            //print_r($file);
            if(!empty($file['archivo']['name'])){
                $archivo = $this->guardarArchivoPQR($post['id_pqr'],$_SESSION['id_tercero'],$file);
                if(!$archivo['estado']){
                    $this->devolverTransaccion();
                    return $archivo;
                }
                else{
                    $this->finalizarTransaccion();
                    return  array("estado"=>true,"data"=>"PQR actualizada");
                }
            }
            else{
                $this->finalizarTransaccion();
                return  array("estado"=>true,"data"=>"PQR actualizada");
            } 
        }
    }

    function eliminarPQR($id_pqr){
        $this->iniciarTransaccion();

        $this->sql = "delete from archivo_pqr where id_pqr=".$id_pqr;
        $result = $this->db->Execute($this->sql);
        if(!$result){
            $this->devolverTransaccion();
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        }
        else{
            $this->sql = "delete from pqr where id_pqr=".$id_pqr;
            $result = $this->db->Execute($this->sql);
            if(!$result){
                $this->devolverTransaccion();
                return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            }
            else{
                $this->finalizarTransaccion();
                return  array("estado"=>true,"data"=>"PQR eliminada");
            }
        }
    }

    function crearUsuarioServicio($post){
        
        $dv = $this->calcularDV($post['txt_identificacion']);

        $email = (!empty($post['txt_email']))?"'".$post['txt_email']."'":"null";

        $this->sql = "INSERT INTO usuario_servicio(
                    id_tipo_identificacion,identificacion,digito_verificacion,
                    nombre,id_municipio,direccion,telefono,email,
                    id_tercero_registra,fch_registro
                    )
                    VALUES(
                    ".$post['slt_tipo_identificacion'].",".$post['txt_identificacion'].",".$dv.",
                    '".$post['txt_nombre']."',".$post['slt_municipio'].",'".$post['txt_direccion']."',
                    '".$post['txt_telefono']."',".$email.",".$_SESSION['id_tercero'].",now() 
                    );";
        $result = $this->db->Execute($this->sql);

        if(!$result)
            return  array("estado"=>false,"data"=>"Error creando el usuario ".$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());
    }

    function actualizarUsuarioServicio($post){

        $dv = $this->calcularDV($post['txt_identificacion']);

        $email = (!empty($post['txt_email']))?"'".$post['txt_email']."'":"null";

        $this->sql = "update usuario_servicio set 
        id_tipo_identificacion=".$post['slt_tipo_identificacion'].",
        identificacion=".$post['txt_identificacion'].",
        digito_verificacion=".$dv.",
        nombre='".$post['txt_nombre']."',
        direccion='".$post['txt_direccion']."',
        telefono='".$post['txt_telefono']."',
        email=".$email." 
        where 
        id_usuario_servicio=".$post['id_usuario_servicio'];
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>"Error actualizando el usuario ".$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>"Usuario actualizado");

    }

    function guardarArchivoPQR($id_pqr,$id_tercero_registra,$file){

        $fileHandle  = fopen($file['archivo']['tmp_name'],"r");
        $fileContent = fread($fileHandle,$file['archivo']['size']);
        $fileContent = addslashes($fileContent); 

        $ext = substr($file['archivo']['name'],strripos($file['archivo']['name'],"."),strlen($file['archivo']['name']));
        $nombre = str_replace(' ','',$file['archivo']['name']);

        $this->sql = "INSERT INTO archivo_pqr(
                    id_pqr,tipo,tamano,
                    extension,nombre_archivo,archivo,id_tercero_registra,fch_registro
                    )
                    VALUES(
                    ".$id_pqr.",'".$file['archivo']['type']."',".$file['archivo']['size'].",
                    '".$ext."','".$nombre."','". $fileContent."',".$id_tercero_registra.",now()
                    );";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>"Error guardando el archivo ".$nombre." ".$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id());                
    }

    function listarArchivoPQR($id_pqr){
        $this->sql = "select ap.id_archivo_pqr,ap.nombre_archivo,ap.tipo,ap.fch_registro,t.usuario 
                    from archivo_pqr ap
                    join tercero t on(ap.id_tercero_registra=t.id_tercero)
                    where
                    ap.id_pqr=".$id_pqr;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result)
            return false;
        else
            return $this->result;
    }

    function consultarArchivoPQR($id_archivo_pqr){
        $this->sql = "select ap.tipo,ap.tamano,ap.extension,ap.nombre_archivo,ap.archivo
                    from archivo_pqr ap
                    where
                    ap.id_archivo_pqr=".$id_archivo_pqr;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result)
            return false;
        else
            return $this->result;
    }
    
    function eliminarArchivo($id_archivo_pqr){
        $this->sql = "delete from archivo_pqr where id_archivo_pqr=".$id_archivo_pqr;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result)
            return false;
        else
            return $this->result;
    }

    function guardarComentarioPQR($id_pqr,$id_tercero,$comentario){
        $this->sql = "INSERT INTO comentario_pqr(
                    id_pqr,id_tercero,comentario,fch_registro
                    )
                    VALUES(
                    ".$id_pqr.",".$id_tercero.",'".$comentario."',now()
                    );";
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"data"=>"Error agregando el comentario ".$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"data"=>$this->db->insert_id()); 

    }

    function listarComentarioPQR($id_pqr){
        $this->sql = "select cp.comentario,cp.fch_registro,t.nombre
                    from comentario_pqr cp join tercero t using(id_tercero)
                    where
                    cp.id_pqr=".$id_pqr."
                    order by cp.fch_registro";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result)
            return false;
        else
            return $this->result;
    }

    function buscarPQR($id_municipio,$id_pqr){
        $this->sql = "select p.id_pqr,p.fch_pqr,p.comentario,tp.descripcion as tipo_pqr,tr.descripcion as tipo_reporte,
                    l.id_luminaria,l.poste_no,l.direccion,l.id_barrio,l.latitud,l.longitud,l.fch_instalacion,
                    tl.descripcion as tipo_luminaria,b.descripcion as barrio,l.luminaria_no,l.id_tipo_luminaria
                    from pqr p
                    join tipo_pqr tp using(id_tipo_pqr)
                    left join tipo_reporte tr using(id_tipo_reporte)
                    left join luminaria l using(id_luminaria)
                    left join tipo_luminaria tl using(id_tipo_luminaria)
                    left join barrio b using(id_barrio)
                    where
                    p.id_municipio=".$id_municipio." and
                    p.id_pqr=".$id_pqr;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            return false;
        }
        else{
            return $this->result;
        }
    }

    function cerrarPQR($id_pqr,$id_tercero){
        $this->sql = "update pqr set id_estado_pqr=2,fch_cierre=now(),id_tercero_cierra=".$id_tercero." where id_pqr=".$id_pqr;
        $result = $this->db->Execute($this->sql);
        if(!$result)
            return  array("estado"=>false,"mensaje"=>"Error cerrarndo la PQR ".$this->db->ErrorMsg());
        else
            return  array("estado"=>true,"mensaje"=>"PQR Cerrada"); 
    }
}