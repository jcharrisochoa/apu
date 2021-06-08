<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";
require_once dirname(__FILE__)."/../../parametros/clase/General.php";
class Encuesta extends General{

    private $sql;
    public $db;
    private $result;

    function __construct($driver, $host, $user, $password, $database) {
        $this->db = NewADOConnection($driver);
        $this->db->Connect( $host, $user, $password, $database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        //$global = new Global();
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

    function contarEncuesta($post){
        $q = "";
        if(!empty($post['id_encuesta']))
            $q .= " and e.id_encuesta =".$post['id_encuesta'];
        if(!empty($post['municipio']))
            $q .= " and m.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and e.id_barrio=".$post['barrio'];
        if(!empty($post['nombre']))
            $q .= " and e.nombre_usuario_servicio like '%".$post['nombre']."%'";
        if(!empty($post['direccion']))
            $q .= " and e.direccion like '%".$post['direccion']."%'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(e.fch_encuesta) >= '".$post['fechaini']."' and date(e.fch_encuesta) <= '".$post['fechafin']."'";
        
        $this->sql = "select count(1) as total        
                    from encuesta e
                    join barrio b using(id_barrio)
                    join municipio m using(id_municipio)
                    left join usuario_servicio us using(id_usuario_servicio)
                    left join tipo_identificacion ti using(id_tipo_identificacion)
                    join tercero t on(e.id_tercero_registra = t.id_tercero)
                    where
                    1=1
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando las Encuestas ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    function listarEncuesta($post){
        $q = "";
        if(!empty($post['id_encuesta']))
            $q .= " and e.id_encuesta =".$post['id_encuesta'];
        if(!empty($post['municipio']))
            $q .= " and m.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and e.id_barrio=".$post['barrio'];
        if(!empty($post['nombre']))
            $q .= " and e.nombre_usuario_servicio like '%".$post['nombre']."%'";
        if(!empty($post['direccion']))
            $q .= " and e.direccion like '%".$post['direccion']."%'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(e.fch_encuesta) >= '".$post['fechaini']."' and date(e.fch_encuesta) <= '".$post['fechafin']."'";

        
       /* if(!empty($post['order']['0']['column'])){
            $pos = $post['order']['0']['column'];	
            $campo = $post['columns'][$pos]['name'];
            $campo = ($campo=="")?"1":$campo;
        
            $q .= " order by ".$campo." ". $post['order']['0']['dir'];
        }*/

            
        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select e.* ,b.descripcion as barrio,m.descripcion as municipio,t.usuario,us.identificacion,us.nombre,ti.abreviatura,
                    m.id_municipio,us.id_tipo_identificacion,(select usuario from tercero tc where tc.id_tercero = e.id_tercero_actualiza) as usuario_actualiza
                    from encuesta e
                    join barrio b using(id_barrio)
                    join municipio m using(id_municipio)
                    left join usuario_servicio us using(id_usuario_servicio)
                    left join tipo_identificacion ti using(id_tipo_identificacion)
                    join tercero t on(e.id_tercero_registra = t.id_tercero)
                    where
                    1=1
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando las Encuestas ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function nuevaEncuesta($post){
        $this->iniciarTransaccion();
        $sw = true;
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
            $post['txt_email'] = (!empty($post['txt_email']))?"'".$post['txt_email']."'":"null";
            $post['txt_comentario'] = (!empty($post['txt_comentario']))?"'".$post['txt_comentario']."'":"null";

            $this->sql = "INSERT INTO encuesta(
                id_usuario_servicio,nombre_usuario_servicio,id_barrio,direccion,
                telefono,correo_electronico,id_tercero_registra,fch_encuesta,fch_registro,
                calidad_servicio,tiempo_atencion,atencion_grupo_trabajo,comentario
                )
                VALUES(
                ".$id_usuario_servicio.",'".$post['txt_nombre']."',".$post['slt_barrio'].",
                '".$post['txt_direccion']."','".$post['txt_telefono']."',".$post['txt_email'].",
                ".$_SESSION['id_tercero'].",'".$post['fch_encuesta']."',now(),'".$post['r1']."',
                '".$post['r2']."','".$post['r3']."',".$post['txt_comentario']."
                )";
            $result = $this->db->Execute($this->sql);
            if(!$result){
                $this->devolverTransaccion();
                return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            }
            else{
                $id_encuesta = $this->db->insert_id();
                $this->finalizarTransaccion();
                return  array("estado"=>true,"data"=>$id_encuesta);
            }
        }
    }

    function editarEncuesta($post){
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
        if($sw){
            $post['txt_email'] = (!empty($post['txt_email']))?"'".$post['txt_email']."'":"null";
            $post['txt_comentario'] = (!empty($post['txt_comentario']))?"'".$post['txt_comentario']."'":"null";

            $this->sql = "UPDATE encuesta SET 
                        id_barrio=".$post['slt_barrio'].",
                        direccion='".$post['txt_direccion']."',
                        id_usuario_servicio=".$id_usuario_servicio.",
                        nombre_usuario_servicio='".$post['txt_nombre']."',
                        telefono='".$post['txt_telefono']."',
                        correo_electronico=".$post['txt_email'].",
                        calidad_servicio='".$post['r1']."',
                        tiempo_atencion='".$post['r2']."',
                        atencion_grupo_trabajo='".$post['r3']."',
                        comentario=".$post['txt_comentario'].",
                        fch_encuesta='".$post['fch_encuesta']."',
                        id_tercero_actualiza=".$_SESSION['id_tercero'].",
                        fch_actualiza=now()                        
                        WHERE
                        id_encuesta=".$post['id_encuesta'].";";

            $result = $this->db->Execute($this->sql);
            if(!$result){
                $error = $this->db->ErrorMsg();
                $this->devolverTransaccion();
                return  array("estado"=>false,"data"=>"Error Transacción:".$error);
            }
            else{
                $this->finalizarTransaccion();
                return  array("estado"=>true,"data"=>"Encuesta actualizada");
            }
        }
    }

    function eliminarEncuesta($id_encuesta){
 
        $this->sql = "delete from encuesta where id_encuesta=".$id_encuesta;
        $result = $this->db->Execute($this->sql);
        if(!$result){
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
        }
        else{
            return  array("estado"=>true,"data"=>"PQR eliminada");
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

    /*
    $opt: 
        "C"=Calidad;
        "T"=Tiempo;
        "A"=Atencion;
    */
    function grafica($post,$opt){
        $q = "";
        switch($opt){
            case "A":
                $campo = "e.atencion_grupo_trabajo";
                break;
            case "C":
                $campo = "e.calidad_servicio";
                break;
            case "T":
                $campo = "e.tiempo_atencion";
                break;
            default:
                $campo = "";
                break;
        }
        if(!empty($campo)){
            if(!empty($post['municipio']))
                $q .= " and m.id_municipio=".$post['municipio'];

            if(!empty($post['periodo']))
                $q .= " and year(e.fch_encuesta)=".$post['periodo'];

            if(!empty($post['mes']))
                $q .= " and month(e.fch_encuesta)='".$post['mes']."'";

            $this->sql = "select count(*) as cantidad,".$campo." as calificacion ,
            round((count(*)/(
                            select count(1) 
                            from encuesta e
                            join barrio b  using(id_barrio)
                            join municipio m using(id_municipio) where 1=1  ".$q."
                            )
            ),2)*100 as porcentaje
            from encuesta e
            join barrio b  using(id_barrio)
            join municipio m using(id_municipio)
            where
            1=1
            ".$q."
            group by ".$campo." ";
            $result = $this->db->Execute($this->sql);
        }
        else
            return false;

        return $result;
    }

    function calificacion($opt){
        switch($opt){
            case "E":
                $descripcion = "EXCELENTE";
                break;
            case "B":
                $descripcion = "BUENO";
                break;
            case "R":
                $descripcion = "REGULAR";
                break;
            case "M":
                $descripcion = "MALO";
                break;
            default:
                $descripcion = "";
                break;
        }
        return $descripcion;
    }

    function tablaResumen($post,$opt){
        $q = "";
        switch($opt){
            case "A":
                $campo = "e2.atencion_grupo_trabajo";
                break;
            case "C":
                $campo = "e2.calidad_servicio";
                break;
            case "T":
                $campo = "e2.tiempo_atencion";
                break;
            default:
                $campo = "";
                break;
        }
        if(!empty($campo)){
            if(!empty($post['municipio']))
                $q .= " and m.id_municipio=".$post['municipio'];

            if(!empty($post['periodo']))
                $q .= " and year(e.fch_encuesta)=".$post['periodo'];

            if(!empty($post['mes']))
                $q .= " and month(e.fch_encuesta)='".$post['mes']."'";

            $this->sql = "select m.descripcion  as municipio ,month(e.fch_encuesta) as mes,
                        (select count(1) from encuesta e2 join barrio b2 using(id_barrio) join municipio m2 using(id_municipio) where m.id_municipio = m2.id_municipio  and ". $campo."='M' and year(e2.fch_encuesta)=year(e.fch_encuesta) and month(e2.fch_encuesta)=month(e.fch_encuesta)) as csm,
                        (select count(1) from encuesta e2 join barrio b2 using(id_barrio) join municipio m2 using(id_municipio) where m.id_municipio = m2.id_municipio  and ". $campo."='R' and year(e2.fch_encuesta)=year(e.fch_encuesta) and month(e2.fch_encuesta)=month(e.fch_encuesta)) as csr,
                        (select count(1) from encuesta e2 join barrio b2 using(id_barrio) join municipio m2 using(id_municipio) where m.id_municipio = m2.id_municipio  and ". $campo."='B' and year(e2.fch_encuesta)=year(e.fch_encuesta) and month(e2.fch_encuesta)=month(e.fch_encuesta)) as csb,
                        (select count(1) from encuesta e2 join barrio b2 using(id_barrio) join municipio m2 using(id_municipio) where m.id_municipio = m2.id_municipio  and ". $campo."='E' and year(e2.fch_encuesta)=year(e.fch_encuesta) and month(e2.fch_encuesta)=month(e.fch_encuesta)) as cse,
                        count(*) as cantidad,m.id_municipio 
                        from encuesta e 
                        join barrio b using(id_barrio) join municipio m using(id_municipio)
                        where 
                        1=1
                        ".$q."
                        group by m.descripcion,month(e.fch_encuesta),m.id_municipio 
                        order by m.descripcion,month(e.fch_encuesta) ";
            $result = $this->db->Execute($this->sql);
        }
        else
            return false;

        return $result;
    }
}
