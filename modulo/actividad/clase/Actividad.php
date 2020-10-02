<?php
require_once dirname(__FILE__) . "/../../../libreria/adodb/adodb.inc.php";
require_once dirname(__FILE__)."/../../parametros/clase/General.php";

class Actividad extends General{

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

    function contarActividad($post){
        $q = "";
        if(!empty($post['municipio']))
            $q .= " and ac.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and ac.id_barrio=".$post['barrio'];
        if(!empty($post['tipo_actividad']))
            $q .= " and ac.id_tipo_actividad=".$post['tipo_actividad'];
        if(!empty($post['id_luminaria']))
            $q .= " and ac.id_luminaria=".$post['id_luminaria'];
        if(!empty($post['poste_no']))
            $q .= " and l.poste_no = '".$post['poste_no']."'";
        if(!empty($post['luminaria_no']))
            $q .= " and l.luminaria_no = '".$post['luminaria_no']."'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(ac.fch_actividad) >= '".$post['fechaini']."' and date(ac.fch_actividad) <= '".$post['fechafin']."'";
        if(!empty($post['id_pqr']))
            $q .= " and ac.id_pqr=".$post['id_pqr'];
        
            $this->sql = "select count(ac.id_actividad) as total
                from actividad ac
                join municipio m using(id_municipio)
                left join tercero t using(id_tercero)
                left join barrio b using(id_barrio)
                left join luminaria l using(id_luminaria)
                left join tipo_reporte tr using(id_tipo_reporte)
                left join tipo_actividad ta using(id_tipo_actividad)
                join estado_actividad ea using(id_estado_actividad)
                left join pqr p using(id_pqr)
                left join tipo_pqr tp using(id_tipo_pqr)
                left join vehiculo v using(id_vehiculo)
                where 
                1=1
                ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando las actividades". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }
    
    function listarActividad($post){
        $q = "";
        if(!empty($post['municipio']))
            $q .= " and ac.id_municipio=".$post['municipio'];
        if(!empty($post['barrio']))
            $q .= " and ac.id_barrio=".$post['barrio'];
        if(!empty($post['tipo_actividad']))
            $q .= " and ac.id_tipo_actividad=".$post['tipo_actividad'];
        if(!empty($post['id_luminaria']))
            $q .= " and ac.id_luminaria=".$post['id_luminaria'];
        if(!empty($post['poste_no']))
            $q .= " and l.poste_no = '".$post['poste_no']."'";
        if(!empty($post['luminaria_no']))
            $q .= " and l.luminaria_no = '".$post['luminaria_no']."'";
        if(!empty($post['fechaini']) and !empty($post['fechafin']))
            $q .= " and date(ac.fch_actividad) >= '".$post['fechaini']."' and date(ac.fch_actividad) <= '".$post['fechafin']."'";
        if(!empty($post['id_pqr']))
            $q .= " and ac.id_pqr=".$post['id_pqr'];

        if(!empty($post['order']['0']['column'])){
            $pos = $_POST['order']['0']['column'];	
            $campo = $_POST['columns'][$pos]['name'];
            $campo = ($campo=="")?"6":$campo;
        
            $q .= " order by ".$campo." ". $_POST['order']['0']['dir'];
        }

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];
            

        $this->sql = "select ac.id_actividad,m.descripcion as municipio,ta.descripcion as tipo,
                case when ac.id_barrio is null then ac.barrio else b.descripcion end as barrio,
                ac.direccion,ac.fch_actividad,l.poste_no,l.luminaria_no,t.nombre as tecnico,
                ac.fch_reporte,ac.observacion,tr.descripcion as tipo_reporte,ea.descripcion as estado_actividad,
                tl.descripcion as tipo_luminaria,ac.id_pqr,tp.descripcion as tipo_pqr,v.descripcion as vehiculo,ac.id_vehiculo,
                ac.id_estado_actividad,ac.id_tercero,ac.id_tipo_actividad,ac.id_tipo_luminaria,ac.id_barrio,ac.id_luminaria,ac.id_municipio
                from actividad ac
                join municipio m using(id_municipio)
                left join tipo_luminaria tl using(id_tipo_luminaria)
                left join tercero t using(id_tercero)
                left join barrio b using(id_barrio)
                left join luminaria l using(id_luminaria)                
                left join tipo_reporte tr using(id_tipo_reporte)
                left join tipo_actividad ta using(id_tipo_actividad)
                join estado_actividad ea using(id_estado_actividad)
                left join pqr p using(id_pqr)
                left join tipo_pqr tp using(id_tipo_pqr)
                left join vehiculo v using(id_vehiculo)
                where 
                1=1                
                ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando las actividades". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function nuevaActividad($post,$session){ 

        $errorDetalle = false;
        $id_luminaria = (!empty($post['id_luminaria']))?$post['id_luminaria']:"null";
       
        
        $id_vehiculo = (!empty($post['slt_vehiculo']))?$post['slt_vehiculo']:"null";

        
        $this->iniciarTransaccion();
        
        if(!empty($post['id_pqr'])){
            $id_pqr = $post['id_pqr'];
            $this->sql = "select id_tipo_reporte from pqr where id_pqr=".$post['id_pqr'];
            $result = $this->db->Execute($this->sql);
            if(!$result){
                return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
                $this->devolverTransaccion();                
            }
            else{
                $id_tipo_reporte = $result->fields['id_tipo_reporte'];
            }
        }
        else{
            $id_pqr = "null";
            $id_tipo_reporte = "null";
        }
       

        $this->sql = "INSERT INTO actividad(
                        id_luminaria,id_municipio,id_barrio,barrio,id_tipo_actividad,id_tercero,id_tipo_reporte,
                        id_estado_actividad,direccion,fch_actividad,fch_reporte,observacion,latitud,longitud,seq,id_pqr,
                        id_tercero_registra,fch_registro,id_vehiculo,id_tipo_luminaria
                        )
                        VALUES(
                        ".$id_luminaria.",".$post['slt_municipio'].",".$post['slt_barrio'].",null,".$post['slt_tipo_actividad'].",".$post['slt_tercero'].",
                        ".$id_tipo_reporte.",".$post['slt_estado_actividad'].",'".$post['txt_direccion']."','".$post['txt_fch_ejecucion']."',
                        '".$post['txt_fch_pqr']."','".$post['txt_observacion']."',0,0,1,
                        ".$id_pqr.",".$session['id_tercero'].",now(),".$id_vehiculo.",".$post['slt_tipo_luminaria']."
                        );";
        $result = $this->db->Execute($this->sql);
        if(!$result){
            return  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            $this->devolverTransaccion();
            
        }
        else{
            $id_actividad = $this->db->insert_id();
            $jsonServicio = json_decode($this->fix($post['detalle']));
            foreach($jsonServicio as $servicio){
                $this->sql = "INSERT INTO articulo_actividad(
                            id_actividad,id_articulo,cantidad
                            )
                            VALUES(
                            ".$id_actividad.",".$servicio->id_articulo.",".$servicio->cantidad."
                            );";
                $result = $this->db->Execute($this->sql);
                if(!$result){
                    $array = array("estado"=>false,"data"=>"Error asociando el servicio con codigo (".$servicio->id_articulo.") a la actividad".$this->db->ErrorMsg());
                    $this->devolverTransaccion();
                    $errorDetalle = true;
                    break;                    
                }
            } 
            if($errorDetalle)
                return $array;
            else{
                $this->finalizarTransaccion();
                return  array("estado"=>true,"data"=>$this->db->insert_id());
            }
        }
    }

    function eliminarActividad($id_actividad){
        $this->iniciarTransaccion();
        $this->sql = "delete from articulo_actividad where id_actividad=".$id_actividad;
        $result = $this->db->Execute($this->sql);
        if(!$result){
            $array =  array("estado"=>false,"data"=>$this->db->ErrorMsg());
            $this->devolverTransaccion();
        }
        else{
            $this->sql = "delete from actividad where id_actividad=".$id_actividad;
            $result = $this->db->Execute($this->sql);
            if(!$result){
                $array =  array("estado"=>false,"data"=>$this->db->ErrorMsg());
                $this->devolverTransaccion();
            }
            else{
                $this->finalizarTransaccion();
                $array =  array("estado"=>true,"data"=>"Actividad eliminada");
            }
        }
        return $array;
    }

    function editarActividad($post){
        $array = array();
        $errorDetalle = false;
        
        $id_luminaria = (!empty($post['id_luminaria']))?$post['id_luminaria']:"null";       
        $id_vehiculo = (!empty($post['slt_vehiculo']))?$post['slt_vehiculo']:"null";

        $this->iniciarTransaccion();
        
        if(!empty($post['id_pqr'])){
            $id_pqr = $post['id_pqr'];
            $this->sql = "select id_tipo_reporte from pqr where id_pqr=".$post['id_pqr'];
            $result = $this->db->Execute($this->sql);
            if(!$result){
                $array =  array("estado"=>false,"data"=>$this->db->ErrorMsg());
                $this->devolverTransaccion();                
            }
            else{
                $id_tipo_reporte = $result->fields['id_tipo_reporte'];
                $array =  array("estado"=>true,"data"=>"");
            }
        }
        else{
            $id_pqr = "null";
            $id_tipo_reporte = "null";
            $array =  array("estado"=>true,"data"=>"");
        }

        if($array['estado']){
            $this->sql = "UPDATE actividad SET
                        id_luminaria=".$id_luminaria.",
                        id_municipio=".$post['slt_municipio'].",
                        id_barrio=".$post['slt_barrio'].",
                        id_tipo_actividad=".$post['slt_tipo_actividad'].",
                        id_tercero=".$post['slt_tercero'].",
                        id_tipo_reporte=".$id_tipo_reporte.",
                        id_estado_actividad=".$post['slt_estado_actividad'].",
                        direccion='".$post['txt_direccion']."',
                        fch_actividad='".$post['txt_fch_ejecucion']."',
                        fch_reporte='".$post['txt_fch_pqr']."',
                        observacion='".$post['txt_observacion']."',
                        id_pqr=".$id_pqr.",
                        id_vehiculo=".$id_vehiculo.",
                        id_tipo_luminaria=".$post['slt_tipo_luminaria']."
                        where
                        id_actividad=".$post['id_actividad'];

            $result = $this->db->Execute($this->sql);
            if(!$result){
                $array = array("estado"=>false,"data"=>"Error actualizando la actividad ".$this->db->ErrorMsg());
                $this->devolverTransaccion();
            }
            else{
                $this->sql = "delete from articulo_actividad where id_actividad=".$post['id_actividad'];
                $result = $this->db->Execute($this->sql);
                if(!$result){
                    $array =  array("estado"=>false,"data"=>"Error Eliminando los servicios ".$this->db->ErrorMsg());
                    $this->devolverTransaccion();
                }
                else{
                    $jsonServicio = json_decode($this->fix($post['detalle']));
                    foreach($jsonServicio as $servicio){
                        $this->sql = "INSERT INTO articulo_actividad(
                                    id_actividad,id_articulo,cantidad
                                    )
                                    VALUES(
                                    ".$post['id_actividad'].",".$servicio->id_articulo.",".$servicio->cantidad."
                                    );";
                        $result = $this->db->Execute($this->sql);
                        if(!$result){
                            $array = array("estado"=>false,"data"=>"Error asociando el servicio con codigo (".$servicio->id_articulo.") a la actividad".$this->db->ErrorMsg());
                            $this->devolverTransaccion();
                            $errorDetalle = true;
                            break;                    
                        }
                    } 
                    if(!$errorDetalle){
                        $this->finalizarTransaccion();
                        $array =  array("estado"=>true,"data"=>"Actividad ".$post['id_actividad']." actualizada");
                    }
                }
            }
        }
        return $array;
    }

    function listarServicioActividad($post){
        $q = "";
        if(!empty($post['id_actividad']))
            $q .= " and aa.id_actividad=".$post['id_actividad'];

        if (!empty($post['start']) or !empty($post['length']))
            $q .= " limit ".$post['start'].",".$post['length'];

        $this->sql = "select a.id_articulo,a.descripcion,aa.cantidad
                    from articulo_actividad aa join articulo a using(id_articulo)
                    where 
                    1=1                
                    ".$q;
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando los servicios ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function contarServicioActividad($post){
        $q = "";

        if(!empty($post['id_actividad']))
            $q .= " and aa.id_actividad=".$post['id_actividad'];

        $this->sql = "select count(aa.id_actividad) as total
                      from articulo_actividad aa join articulo a using(id_articulo)
                      where 
                      1=1                
                      ".$q;

        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Contando los servicios ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result->fields['total'];
        }
    }

    //--Informes y graficos--
    function resumenActividadPeriodo($post){
       
        $q = "";

        if(!empty($post['tipo_actividad'])){
            $q = " and id_tipo_actividad=".$post['tipo_actividad'];
        }

        if(!empty($post['periodo'])){
            $q .= " and year(fch_actividad)=".$post['periodo'];
        }

        $this->sql = "select descripcion, 
                    (select count(1) from actividad where month(fch_actividad)=1 and id_municipio=m.id_municipio ".$q.") as ene,
                    (select count(1) from actividad where month(fch_actividad)=2 and id_municipio=m.id_municipio ".$q.") as feb,
                    (select count(1) from actividad where month(fch_actividad)=3 and id_municipio=m.id_municipio ".$q.") as mar,
                    (select count(1) from actividad where month(fch_actividad)=4 and id_municipio=m.id_municipio ".$q.") as abr,
                    (select count(1) from actividad where month(fch_actividad)=5 and id_municipio=m.id_municipio ".$q.") as may,
                    (select count(1) from actividad where month(fch_actividad)=6 and id_municipio=m.id_municipio ".$q.") as jun,
                    (select count(1) from actividad where month(fch_actividad)=7 and id_municipio=m.id_municipio ".$q.") as jul,
                    (select count(1) from actividad where month(fch_actividad)=8 and id_municipio=m.id_municipio ".$q.") as ago,
                    (select count(1) from actividad where month(fch_actividad)=9 and id_municipio=m.id_municipio ".$q.") as sep,
                    (select count(1) from actividad where month(fch_actividad)=10 and id_municipio=m.id_municipio ".$q.") as oct,
                    (select count(1) from actividad where month(fch_actividad)=11 and id_municipio=m.id_municipio ".$q.") as nov,
                    (select count(1) from actividad where month(fch_actividad)=12 and id_municipio=m.id_municipio ".$q.") as dic 
                    from municipio m
                    where 
                    m.tiene_contrato='S'
                    order by m.descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando resumen de actividades ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function totalActividadTipoPeriodo(){
        $this->sql = "select count(*) as cantidad,year(fch_actividad) as periodo,ta.descripcion as tipo
                        from actividad a
                        right join tipo_actividad ta using(id_tipo_actividad)
                        group by year(fch_actividad),ta.descripcion
                        order by year(fch_actividad)";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando cantidad de actividades ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function cantidadActividadMunicipio(){
        $this->sql = "select count(*) as cantidad ,m.descripcion as municipio
                    from actividad a join municipio m using(id_municipio)
                    group by m.descripcion
                    order by m.descripcion";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando cantidad de actividades ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }

    function cantidadActividadPeriodoActualMes(){
        $this->sql = "select count(*) as cantidad,month(fch_actividad) as mes
                        from actividad a 
                        where
                        year(fch_actividad)=year(now())
                        group by month(fch_actividad)
                        order by month(fch_actividad)";
        $this->result = $this->db->Execute($this->sql);
        if(!$this->result){
            echo "Error Consultando cantidad de actividades ". $this->db->ErrorMsg();
            return false;
        }
        else{
            return $this->result;
        }
    }
}
