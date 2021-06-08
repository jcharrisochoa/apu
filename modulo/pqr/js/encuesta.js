var tableEncuesta = "";
var dataDetalleEncuesta= "";
var accion = "";
$(function(){

    $("#identificacion").numeric();

    $("#txt_identificacion").numeric();

    $("#municipio").change(function() {
        listarBarrio("municipio","barrio");
    }); 

    $("#slt_municipio").change(function() {
        listarBarrio("slt_municipio","slt_barrio");
    });     

    $("#btn_buscar_encuesta").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            tableEncuesta.ajax.reload(function(){
                $("#modal-text-global").html("Se encontraron "+tableEncuesta.page.info().recordsTotal+" registros");
                $("#modal-mensaje-global").modal("show");
                dataDetalleEncuesta = "";
            });   
            event.handld = true;
        }
        return false;
    });

    $("#txt_identificacion").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               buscarUsuarioServicio(); 
               event.handld = true;
           };
       }
       //return false;
    });

    $("#btn_buscar_usuario_servicio").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if($.trim($("#txt_identificacion").val())==""){
                $("#frm-usuario-servicio").modal("show"); 
                $("#frm-usuario-servicio").css("z-index", "15001");
                tableUsuarioServicio.ajax.reload();
                detalleUsuarioServicio = "";

            }
            else{
                buscarUsuarioServicio();    
            }
            event.handld = true;
        }
        return false;
    });

    $("#btn_cancelar_usuario_servicio").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            event.handld = true;
            $("#id_usuario_servicio").val("");
            $("#txt_identificacion").val("");
            $("#txt_nombre").val("");
            $("#txt_direccion").val("");
            $("#txt_telefono").val("");
            $("#slt_tipo_identificacion").val("");
        }
        return false;
    });

    $("#txt_identificacion").blur(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            buscarUsuarioServicio();    
            event.handld = true;
        }
        return false;
    });

    $("#btn_nueva_encuesta").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            nuevaEncuesta(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_editar_encuesta").click(function(){       
        if (event.handled !== true) {
            event.preventDefault();
            editarEncuesta(dataDetalleEncuesta);
            accion = "editar";  
            event.handld = true;
        }
        return false;        
    });

    $("#btn_eliminar_encuesta").click(function(){        
        if (event.handled !== true) {
            event.preventDefault();
            eliminarEncuesta(dataDetalleEncuesta);
            accion = "eliminar";  
            event.handld = true;
        }
        return false;
    });

    $("#btn_detalle_encuesta").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            verDetalleEncuesta(); 
            accion = "";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_guardar_encuesta").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                guardarAccion(accion); 
            }
            event.handld = true;
        }
        return false;
    });

    $("#frm-usuario-servicio").off().on('hidden.bs.modal',function(){
        $("#frm-pqr").css("z-index", "15000"); //foco del modal inicial
        $('body').addClass('modal-open'); //render nuevamente el modal inicial
        $("#id_usuario_servicio").val(detalleUsuarioServicio.id_usuario_servicio);
        $("#txt_identificacion").val(detalleUsuarioServicio.identificacion);
        $("#txt_nombre").val(detalleUsuarioServicio.nombre);
        $("#txt_direccion").val(detalleUsuarioServicio.direccion);
        $("#txt_telefono").val(detalleUsuarioServicio.telefono);
        $("#slt_tipo_identificacion").val(detalleUsuarioServicio.id_tipo_identificacion).change();
    });

    $("#btn_exportar_encuesta").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            exportarEncuesta(); 
            event.handld = true;
        }
        return false;
    });

    InitTableEncuesta();
});

function listarBarrio(controlMunicipio,controlBarrio) {
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "pqr/ajax/listar_barrio.php",
        data: {
            id_municipio: $("#"+controlMunicipio).val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            $("#"+controlBarrio).empty();
            for (x in data.lista) {
                var selected = (dataDetalleEncuesta.id_barrio == data.lista[x].id_barrio)?"selected":"";

                $("#"+controlBarrio).append('<option value="' + data.lista[x].id_barrio + '"'+selected+'>' + data.lista[x].descripcion + '</option>');
            }
            $.unblockUI("");
        }
    });
}

function InitTableEncuesta() {
    if (!$.fn.DataTable.isDataTable("#tbl_encuesta")) {
        tableEncuesta = "";
        tableEncuesta = $("#tbl_encuesta").on("preXhr.dt", function(e, settings, data) {
            data.municipio = $("#municipio").val(),
            data.barrio = $("#barrio").val(),
            data.identificacion = $("#identificacion").val(),            
            data.fechaini = $("#fch_encuesta_ini").val(),
            data.fechafin = $("#fch_encuesta_fin").val(),
            data.direccion = $("#direccion").val(),
            data.nombre = $("#nombre").val()
        }).DataTable({ ///todo por una D jeje
            "aLengthMenu": [
                [15, 30, 50, 70, 100],
                [15, 30, 50, 70, 100]
            ],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": false,

            "ajax": {
                "url": "pqr/ajax/listar_encuesta.php",
                "type": "POST"
            },
            "columns": [

                { "data": "item", "searchable": false },
                { "data": "municipio",className: "alignCenter","searchable": false,"orderable": true,"name":"m.descripcion"},
                { "data": "nombre",className: "alignCenter","searchable": false,"orderable": true,"name":"e.nombre_usuario_servicio"},
                { "data": "barrio",className: "alignCenter","searchable": false,"orderable": true,"name":"b.barrio"},
                { "data": "direccion",className: "alignCenter","searchable": false,"orderable": true,"name":"e.direccion"},
                { "data": "calidad",className: "alignCenter","searchable": false,"orderable": true,"name":"e.calidad_servicio"},
                { "data": "tiempo",className: "alignCenter","searchable": false,"orderable": true,"name":"e.tiempo_atencion"},
                { "data": "atencion",className: "alignCenter","searchable": false,"orderable": true,"name":"e.atencion_grupo_trabajo"},
                { "data": "fch_encuesta",className: "alignCenter","searchable": false,"orderable": true,"name":"e.fch_encuesta"},
                { "data": "usuario",className: "alignCenter","searchable": false,"orderable": true,"name":"us.usuario"},
                { "data": "fch_registro",className: "alignCenter","searchable": false,"orderable": true,"name":"e.fch_registro"},
                { "data": "id_municipio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_barrio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tercero_registra", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_usuario_servicio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_encuesta", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "comentario", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "telefono", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "email", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "abreviatura", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "fch_actualiza", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "usuario_actualiza", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }

            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            }
        });
    }
    $("#tbl_encuesta tbody").on("click", "tr", function() {
        $("#tbl_encuesta tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleEncuesta = tableEncuesta.row(this).data();
        //console.log(dataDetalleEncuesta);
        $("#id_encuesta").val(dataDetalleEncuesta.id_encuesta);
    });
}

function nuevaEncuesta(){
    $("#frm-titulo-encuesta").html("Nueva Encuesta");
    $("#frm-encuesta").modal("show"); 
    $("#tbl_lista_encuesta tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleEncuesta = "";
}

function editarEncuesta(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la Encuesta a editar", null, opts);
    } 
    else {
        $("#frm-titulo-encuesta").html("Editar Encuesta #"+dataDet.id_encuesta);
        $("#id_encuesta").val(dataDet.id_encuesta);
        $("#id_usuario_servicio").val(dataDet.id_usuario_servicio);
        $("#slt_municipio").val(dataDet.id_municipio).change();
        $("#fch_encuesta").val(dataDet.fch_encuesta);
        $("#txt_identificacion").val(dataDet.identificacion);
        $("#slt_tipo_identificacion").val(dataDet.id_tipo_identificacion).change();
        $("#txt_nombre").val(dataDet.nombre);
        $("#txt_direccion").val(dataDet.direccion);
        $("#txt_telefono").val(dataDet.telefono);
        $("#txt_email").val(dataDet.email);
        $("#txt_comentario").val(dataDet.comentario);
        $("#slt_barrio").val(dataDet.id_barrio).change();
        $("#frm-encuesta").modal("show"); 
        
        switch(dataDet.calidad){
            case "M":
                $("#c1").prop("checked",true);
                break;
            case "R":
                $("#c2").prop("checked",true);
                break;
            case "B":
                $("#c3").prop("checked",true);
                break;
            case "E":
                $("#c4").prop("checked",true);
                break;
        }

        switch(dataDet.tiempo){
            case "M":
                $("#t1").prop("checked",true);
                break;
            case "R":
                $("#t2").prop("checked",true);
                break;
            case "B":
                $("#t3").prop("checked",true);
                break;
            case "E":
                $("#t4").prop("checked",true);
                break;
        }

        switch(dataDet.atencion){
            case "M":
                $("#a1").prop("checked",true);
                break;
            case "R":
                $("#a2").prop("checked",true);
                break;
            case "B":
                $("#a3").prop("checked",true);
                break;
            case "E":
                $("#a4").prop("checked",true);
                break;
        }
    }
}

function eliminarEncuesta(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la Encuesta a eliminar", null, opts);
    } 
    else {       
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar la Encuesta <strong>"+dataDet.id_encuesta+"</strong>?");
        $("#modal-conf").modal("show");
        $("#btn_si").off("click").on("click",function(event){      
            if (event.handled !== true) {
                event.preventDefault();
                $("#modal-conf").modal("hide");
                $("#modal-body-conf").html("")
                guardarAccion("eliminar");                     
                event.handld = true;
            }
            return false;                
        });          
    }
}

function buscarUsuarioServicio(){
    if($.trim($("#txt_identificacion").val())!=""){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"pqr/ajax/buscar_usuario_servicio.php",
            data:{
                identificacion:$("#txt_identificacion").val()
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.estado){
                    if(data.id_usuario_servicio == ""){
                        toastr.warning(data.mensaje, null, opts);
                        //$("#chk_actualizar_datos").iCheck('disable');
                    }
                    else{
                        toastr.success(data.mensaje, null, opts);
                        //$("#chk_actualizar_datos").iCheck('enable');
                    }

                    $("#id_usuario_servicio").val(data.id_usuario_servicio);
                    $("#slt_tipo_identificacion").val(data.id_tipo_identificacion).change();
                    $("#txt_nombre").val(data.nombre);
                    $("#txt_direccion").val(data.direccion);
                    $("#txt_telefono").val(data.telefono);
                    $("#txt_email").val(data.email);
                    //$("#chk_actualizar_datos").iCheck('uncheck');
                }
                else{
                    toastr.error(data.mensaje, null, opts);
                }            
                $.unblockUI("");            
            },
            error:function(){
                toastr.error("Error General", null, opts);
                $.unblockUI(""); 
            }
        });
    }
    else{
        $("#id_usuario_servicio").val("");
        $("#slt_tipo_identificacion").val("").change();
        $("#txt_nombre").val("");
        $("#txt_direccion").val("");
        $("#txt_telefono").val("");
        $("#txt_email").val("");
        //$("#chk_actualizar_datos").iCheck('uncheck');
        //$("#chk_actualizar_datos").iCheck('disable');
        //abrir ventana de todos los usuario
    }
}

function guardarAccion(accion){    
    switch(accion){
        case "nuevo":
            var formEncuesta = new FormData(document.getElementById("form-encuesta"));           
            formEncuesta.append("accion",accion);          
            break;
        case "editar":
            var formEncuesta = new FormData(document.getElementById("form-encuesta"));
            formEncuesta.append("accion",accion);
            break;
        case "eliminar":
            var formEncuesta = new FormData();
            formEncuesta.append("id_encuesta",$("#id_encuesta").val());
            formEncuesta.append("accion",accion);
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        processData: false,
        contentType: false,
        url:"pqr/ajax/guardar_accion_encuesta.php",
        data:formEncuesta,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-encuesta").modal("hide"); 
                clearInput(".clear"); 
                tableEncuesta.ajax.reload();
                accion="";
                dataDetalleEncuesta = "";
                //$("#chk_actualizar_datos").iCheck('uncheck');               
            }
            else{
                toastr.error(data.response.mensaje, null, opts);
            }            
            $.unblockUI("");            
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function verDetalleEncuesta(){
    if (dataDetalleEncuesta.length == 0) {
        toastr.warning("Seleccione la Encuesta a visualizar", null, opts);
    } 
    else {
        //console.log(dataDetalleEncuesta);
        $("#td_municipio").html(dataDetalleEncuesta.municipio);
        $("#td_barrio").html(dataDetalleEncuesta.barrio);
        $("#td_tipo_identificacion").html(dataDetalleEncuesta.abreviatura);
        $("#td_identificacion").html(dataDetalleEncuesta.identificacion);
        $("#td_nombre").html(dataDetalleEncuesta.nombre);
        $("#td_direccion").html(dataDetalleEncuesta.direccion);
        $("#td_telefono").html(dataDetalleEncuesta.telefono);
        $("#td_email").html(dataDetalleEncuesta.email);
        $("#td_fecha_encuesta").html(dataDetalleEncuesta.fch_encuesta);
        $("#td_comentario").html(dataDetalleEncuesta.comentario);
        $("#td_fecha_registro").html(dataDetalleEncuesta.fch_registro);
        $("#td_usuario_registra").html(dataDetalleEncuesta.usuario);
        $("#td_fch_actualiza").html(dataDetalleEncuesta.fch_actualiza);
        $("#td_usuario_actualiza").html(dataDetalleEncuesta.usuario_actualiza);

        switch(dataDetalleEncuesta.calidad){
            case "M":
                $("#td_calidad_servicio").html("Malo");
                $("#td_calidad_servicio").addClass("badge-danger");
                break;
            case "R":
                $("#td_calidad_servicio").html("Regular");
                $("#td_calidad_servicio").addClass("badge-warning");
                break;
            case "B":
                $("#td_calidad_servicio").html("Bueno");
                $("#td_calidad_servicio").addClass("badge-info");
                break;
            case "E":
                $("#td_calidad_servicio").html("Excelente");
                $("#td_calidad_servicio").addClass("badge-success");
                break;
        }

        switch(dataDetalleEncuesta.tiempo){
            case "M":
                $("#td_tiempo_respuesta").html("Malo");
                $("#td_tiempo_respuesta").addClass("badge-danger");
                break;
            case "R":
                $("#td_tiempo_respuesta").html("Regular");
                $("#td_tiempo_respuesta").addClass("badge-warning");
                break;
            case "B":
                $("#td_tiempo_respuesta").html("Bueno");
                $("#td_tiempo_respuesta").addClass("badge-info");
                break;
            case "E":
                $("#td_tiempo_respuesta").html("Excelente");
                $("#td_tiempo_respuesta").addClass("badge-success");
                break;
        }

        switch(dataDetalleEncuesta.atencion){
            case "M":
                $("#atencion_del_personal").html("Malo");
                $("#atencion_del_personal").addClass("badge-danger");
                break;
            case "R":
                $("#atencion_del_personal").html("Regular");
                $("#atencion_del_personal").addClass("badge-warning");
                break;
            case "B":
                $("#atencion_del_personal").html("Bueno");
                $("#atencion_del_personal").addClass("badge-info");
                break;
            case "E":
                $("#atencion_del_personal").html("Excelente");
                $("#atencion_del_personal").addClass("badge-success");
                break;
        }

        $("#modal-titulo-detalle-encuesta").html("Detalle Encuesta No "+dataDetalleEncuesta.id_encuesta);
        $("#modal-detalle-encuesta").modal("show");        
    }
}

function exportarEncuesta(){

    var url = "identificacion="+$("#identificacion").val()+"&"+
                "municipio="+$("#municipio").val()+"&"+
                "barrio="+$("#barrio").val()+"&"+
                "fechaini="+$("#fch_encuesta_ini").val()+"&"+
                "fechafin="+$("#fch_encuesta_fin").val()+"&"+
                "direccion="+$("#direccion").val()+"&"+
                "nombre="+$("#nombre").val();

    window.open("pqr/ajax/exportar_encuesta.php?"+url);
}