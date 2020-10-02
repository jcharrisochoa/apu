var tablePQR = "";
var dataDetallePQR = "";
var dataSet = [];
var accion = "";
var tableActividad = "";

$(function(){
    $("#txt_identificacion").numeric();

    $("#identificacion").numeric();

    $("#slt_municipio").change(function(){
        listarBarrioPuntoLuminico();
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
   
    $("#btn_buscar_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            tablePQR.ajax.reload(function(){
                $("#modal-text-global").html("Se encontraron "+tablePQR.page.info().recordsTotal+" registros");
                $("#modal-mensaje-global").modal("show");
                dataDetallePQR = "";
            });   
            event.handld = true;
        }
        return false;
    });

    $("#txt_luminaria").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               buscarLuminaria(); 
               event.handld = true;
           };
       }
       //return false;
    });

    //$(".make-switch").bootstrapSwitch('toggleRadioState');
    $('input.icheck-11').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-yellow'
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

    $("#txt_identificacion").blur(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            buscarUsuarioServicio();    
            event.handld = true;
        }
        return false;
    });

    $("#btn_nueva_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            nuevaPQR(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_editar_pqr").click(function(){       
        if (event.handled !== true) {
            event.preventDefault();
            editarPQR(dataDetallePQR);
            accion = "editar";  
            event.handld = true;
        }
        return false;        
    });

    $("#btn_eliminar_pqr").click(function(){        
        if (event.handled !== true) {
            event.preventDefault();
            eliminarPQR(dataDetallePQR);
            accion = "eliminar";  
            event.handld = true;
        }
        return false;
    });

    $("#btn_detalle_pqr").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            verDetallePQR(); 
            accion = "";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_agregar_comentario").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            agregarComentario(); 
            accion = "";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_buscar_punto_luminico").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if($("#slt_municipio").val()!=""){
                $("#frm-punto-luminico").modal("show"); 
                $("#frm-punto-luminico").css("z-index", "15001");
                $("#flt_municipio").val($("#slt_municipio").val()).change();
                tablePuntoLuminico.ajax.reload();
                dataDetallePuntoLuminico = "";
            }
            else
                toastr.warning("Seleccione el municipio", null, opts);

            event.handld = true;
        }
        return false;
    });
    
    $("#btn_cancelar_punto_luminico").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            event.handld = true;
            $("#id_luminaria").val("");
            $("#txt_poste").val("");
            $("#txt_luminaria").val("");
            $("#txt_direccion_reporte").val("");
            $("#slt_barrio_reporte").val("");
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
    
    $("#btn_guardar_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                guardarAccion(accion); 
            }
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_agregar_archivo").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            agregarArchivo();    
            event.handld = true;
        }
        return false;
    });

    $("#frm-punto-luminico").off().on('hidden.bs.modal',function(){
        //$(".modal-backdrop").not(':first').remove();
        $("#frm-pqr").css("z-index", "15000"); //foco del modal inicial
        $('body').addClass('modal-open'); //render nuevamente el modal inicial
        //console.log(dataDetallePuntoLuminico);
        $("#id_luminaria").val(dataDetallePuntoLuminico.id_luminaria);
        $("#txt_poste").val(dataDetallePuntoLuminico.poste_no);
        $("#txt_luminaria").val(dataDetallePuntoLuminico.luminaria_no);
        $("#txt_direccion_reporte").val(dataDetallePuntoLuminico.direccion);
        $("#slt_barrio_reporte").val(dataDetallePuntoLuminico.id_barrio).change();
    });

    $("#frm-usuario-servicio").off().on('hidden.bs.modal',function(){
        $("#frm-pqr").css("z-index", "15000"); //foco del modal inicial
        $('body').addClass('modal-open'); //render nuevamente el modal inicial
        //console.log(detalleUsuarioServicio);
        $("#id_usuario_servicio").val(detalleUsuarioServicio.id_usuario_servicio);
        $("#txt_identificacion").val(detalleUsuarioServicio.identificacion);
        $("#txt_nombre").val(detalleUsuarioServicio.nombre);
        $("#txt_direccion").val(detalleUsuarioServicio.direccion);
        $("#txt_telefono").val(detalleUsuarioServicio.telefono);
        $("#slt_tipo_identificacion").val(detalleUsuarioServicio.id_tipo_identificacion).change();
    });

    $("#frm-pqr").off().on('hidden.bs.modal',function(){
        $("#modal-mensaje-global").css("z-index", "15001"); //foco del modal inicial
        $('body').addClass('modal-open'); //render nuevamente el modal inicial
    });
   
    InitTablePQR();
});

function InitTablePQR() {
    if (!$.fn.DataTable.isDataTable("#tbl_lista_pqr")) {
        tablePQR = "";
        tablePQR = $("#tbl_lista_pqr").on("preXhr.dt", function(e, settings, data) {
            data.id_pqr = $("#radicado").val(),
            data.municipio = $("#municipio").val(),
            data.tipo_pqr = $("#tipo_pqr").val(),    
            data.tipo_reporte = $("#tipo_reporte").val(),
            data.fechaini = $("#fch_pqr_ini").val(),
            data.fechafin = $("#fch_pqr_fin").val(),
            data.nombre = $("#nombre").val(),
            data.direccion = $("#direccion").val(),
            data.identificacion = $("#identificacion").val(),
            data.estado = $("#estado_pqr").val()
        }).DataTable({
            "aLengthMenu": [
                [15, 30, 50, 70, 100, 500],
                [15, 30, 50, 70, 100, 500]
            ],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": false,

            "ajax": {
                "url": "pqr/ajax/listar_pqr.php",
                "type": "POST"
            },
            "columns": [

                { "data": "item", "searchable": false },
                { "data": "id_pqr",className: "alignCenter","searchable": false,"orderable": true,"name":"p.id_pqr"},
                { "data": "municipio",className: "alignCenter","searchable": false,"orderable": true,"name":"m.descripcion"},
                { "data": "tipo_pqr",className: "alignCenter","searchable": false,"orderable": true,"name":"tp.descripcion"},
                { "data": "tipo_reporte",className: "alignCenter","searchable": false,"orderable": true,"name":"tr.descripcion"},
                { "data": "nombre",className: "alignCenter","searchable": false,"orderable": true,"name":"p.nombre_usuario_servicio"},
                { "data": "fch_pqr", className: "alignCenter", "searchable": false, "orderable": true,"name":"p.fch_pqr" },
                { "data": "direccion_reporte", className: "alignCenter", "searchable": false, "orderable": true,"name":"p.direccion_reporte" },
                { "data": "barrio_reporte", className: "alignCenter", "searchable": false, "orderable": false,},
                { "data": "usuario", className: "alignCenter", "searchable": false, "orderable": true,"name":"tc.usuario" },
                { "data": "estado", className: "alignCenter", "searchable": false, "orderable": true,"name":"ep.descripcion" },
                { "data": "id_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "poste_no", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "luminaria_no", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_municipio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_pqr", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_reporte", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_medio_recepcion_pqr", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_estado_pqr", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_usuario_servicio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "tipo_identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "direccion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "telefono", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "email", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "permitir_edicion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "permitir_eliminar", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_barrio_reporte", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "medio_recepcion","bVisible": false,className: "alignCenter","searchable": false,"orderable": false},
                { "data": "fch_cierre","bVisible": false,className: "alignCenter","searchable": false,"orderable": false},
                { "data": "tercero_cierra","bVisible": false,className: "alignCenter","searchable": false,"orderable": false}
                
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            }
            /*,
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_luminaria;
                $(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }*/
        });
    }
    $("#tbl_lista_pqr tbody").on("click", "tr", function() {
        $("#tbl_lista_pqr tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetallePQR = tablePQR.row(this).data();
        $("#id_pqr").val(dataDetallePQR.id_pqr);
    });
}

function listarBarrioPuntoLuminico() {
    var selected="";
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "pqr/ajax/listar_barrio.php",
        data: {
            id_municipio: $("#slt_municipio").val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            console.log(data);
            $("#slt_barrio_reporte").empty();
            for (x in data.lista) {
                selected = (dataDetallePQR.id_barrio_reporte ==  data.lista[x].id_barrio)?"selected":"";

                $("#slt_barrio_reporte").append('<option value="' + data.lista[x].id_barrio + '"'+selected+'>' + data.lista[x].descripcion + '</option>');
            }
            $.unblockUI("");
        }
    });
}

function nuevaPQR(){
    $("#archivo-agregado").hide();
    $("#frm-titulo-pqr").html("Nueva PQR");
    $("#frm-pqr").modal("show"); 
    $("#tbl_lista_pqr tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetallePQR = "";
}

function editarPQR(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la PQR a editar", null, opts);
    } 
    else {
        if (dataDet.permitir_edicion == 'N') {
            toastr.info("El estado en el que se encuentra la PQR, no le permite su edici&oacute;n", null, opts);
        }
        else{
            $("#archivo-agregado").show();
            $("#frm-titulo-pqr").html("Editar PQR");
            $("#id_pqr").val(dataDet.id_pqr);
            $("#id_luminaria").val(dataDet.id_luminaria);
            $("#id_usuario_servicio").val(dataDet.id_usuario_servicio);
            $("#slt_municipio").val(dataDet.id_municipio).change();
            $("#slt_tipo_pqr").val(dataDet.id_tipo_pqr).change();
            $("#slt_tipo_reporte").val(dataDet.id_tipo_reporte).change();
            $("#slt_medio_recepcion").val(dataDet.id_medio_recepcion_pqr).change();
            $("#slt_estado_pqr").val(dataDet.id_estado_pqr).change();
            $("#fch_pqr").val(dataDet.fch_pqr);
            $("#txt_identificacion").val(dataDet.identificacion);
            $("#slt_tipo_identificacion").val(dataDet.id_tipo_identificacion).change();
            $("#txt_nombre").val(dataDet.nombre);
            $("#txt_direccion").val(dataDet.direccion);
            $("#txt_telefono").val(dataDet.telefono);
            $("#txt_email").val(dataDet.email);
            $("#txt_poste").val(dataDet.poste_no);
            $("#txt_luminaria").val(dataDet.luminaria_no);
            $("#txt_comentario").val(dataDet.comentario);
            $("#txt_direccion_reporte").val(dataDet.direccion_reporte);
            $("#slt_barrio_reporte").val(dataDet.id_barrio_reporte).change();
            listarArchivo("panel-archivo",true);
            $("#frm-pqr").modal("show"); 
        }
    }
}

function eliminarPQR(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la PQR a eliminar", null, opts);
    } 
    else {
        if (dataDet.permitir_eliminar == 'N') {
            toastr.info("El estado en el que se encuentra la PQR, no le permite eliminarla", null, opts);
        } 
        else{
            $("#modal-body-conf").html("¿ Está seguro(a) de eleminar la PQR <strong>"+dataDet.id_pqr+"</strong>?");
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
}

function verDetallePQR(){
    if (dataDetallePQR.length == 0) {
        toastr.warning("Seleccione la PQR a visualizar", null, opts);
    } 
    else {
        $("#td_municipio").html(dataDetallePQR.municipio);
        $("#td_tipo_pqr").html(dataDetallePQR.tipo_pqr);
        $("#td_tipo_reporte").html(dataDetallePQR.tipo_reporte);  
        $("#td_fecha_pqr").html(dataDetallePQR.fch_pqr);
        $("#td_estado_pqr").html(dataDetallePQR.estado);
        $("#td_medio_recepcion").html(dataDetallePQR.medio_recepcion);
        $("#td_identificacion").html(dataDetallePQR.identificacion);
        $("#td_tipo_identificacion").html(dataDetallePQR.tipo_identificacion)
        $("#td_nombre").html(dataDetallePQR.nombre);
        $("#td_direccion").html(dataDetallePQR.direccion);
        $("#td_telefono").html(dataDetallePQR.telefono);
        $("#td_email").html(dataDetallePQR.email);
        $("#td_poste").html(dataDetallePQR.poste_no);
        $("#td_luminaria").html(dataDetallePQR.luminaria_no);
        $("#td_direccion_reporte").html(dataDetallePQR.direccion_reporte);
        $("#td_barrio_reporte").html(dataDetallePQR.barrio_reporte);
        $("#td_comentario").html(dataDetallePQR.comentario);
        $("#td_fecha_cierre_pqr").html(dataDetallePQR.fch_cierre);
        $("#td_usuario_cierre").html(dataDetallePQR.tercero_cierra);
        if(dataDetallePQR.fch_cierre == null)
            $("#a_cerrar_pqr").html("<i class=\"entypo-lock-open\"></i>");
        else
            $("#a_cerrar_pqr").html("<i class=\"entypo-lock\"></i>"); 

        listarArchivo("detalle-panel-archivo",false);
        listarComentario();
        if ($.fn.DataTable.isDataTable("#tbl_actividad_pqr")) {
            tableActividad.destroy();
        }
        InitTableActividad(dataDetallePQR);
        $("#modal-titulo-detalle-pqr").html("Detalle PQR No "+dataDetallePQR.id_pqr);
        $("#modal-detalle-pqr").modal("show");        
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
                        $("#chk_actualizar_datos").iCheck('disable');
                    }
                    else{
                        toastr.success(data.mensaje, null, opts);
                        $("#chk_actualizar_datos").iCheck('enable');
                    }

                    $("#id_usuario_servicio").val(data.id_usuario_servicio);
                    $("#slt_tipo_identificacion").val(data.id_tipo_identificacion).change();
                    $("#txt_nombre").val(data.nombre);
                    $("#txt_direccion").val(data.direccion);
                    $("#txt_telefono").val(data.telefono);
                    $("#txt_email").val(data.email);
                    $("#chk_actualizar_datos").iCheck('uncheck');
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
        $("#chk_actualizar_datos").iCheck('uncheck');
        $("#chk_actualizar_datos").iCheck('disable');
        //abrir ventana de todos los usuario
    }
}

function guardarAccion(accion){    
    switch(accion){
        case "nuevo":
            var formPQR = new FormData(document.getElementById("form-pqr"));           
            formPQR.append("accion",accion);          
            break;
        case "editar":
            var formPQR = new FormData(document.getElementById("form-pqr"));
            formPQR.append("accion",accion);
            break;
        case "eliminar":
            var formPQR = new FormData();
            formPQR.append("id_pqr",$("#id_pqr").val());
            formPQR.append("accion",accion);
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        processData: false,
        contentType: false,
        url:"pqr/ajax/guardar_accion.php",
        data:formPQR,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-pqr").modal("hide"); 
                clearInput(".clear"); 
                $(".fileinput").fileinput('clear');  //limpia el input type file             
                tablePQR.ajax.reload();
                
                if(accion=="nuevo"){
                    $("#modal-text-global").html("<center><h3>Radicado No "+data.response.id_pqr+"</h3></center>");
                    $("#modal-mensaje-global").modal("show");
                }
                accion="";
                dataDetallePQR = "";
                $("#chk_actualizar_datos").iCheck('uncheck');
                //$("#modal-text-global").css("z-index", "15101");                 
                //$('body').addClass('modal-open'); 
               
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

function agregarArchivo(){
    var formArchivoPQR = new FormData(document.getElementById("form-pqr-archivo"));
    formArchivoPQR.append("id_pqr",dataDetallePQR.id_pqr); 
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        processData: false,
        contentType: false,
        url:"pqr/ajax/guardar_archivo.php",
        data:formArchivoPQR,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.estado){
                $(".fileinput").fileinput('clear');  //limpia el input type file             
                listarArchivo("detalle-panel-archivo",false);             
            }
            else{
                toastr.error(data.data, null, opts);
            }            
            $.unblockUI("");            
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function listarArchivo(panel,eliminar){    
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "pqr/ajax/listar_archivo.php",
        data: {
            id_pqr: dataDetallePQR.id_pqr
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            var html = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">";
            var del = "";
            for (x in data) {

                if(eliminar)
                    del = "<a href=\"#\" title=\"Eliminar Archivo\" onclick=\"eliminarArchivo("+data[x].id_archivo_pqr+",'"+data[x].nombre_archivo+"')\"><i class=\"entypo-trash\"></i></a>";
                
                html = html + "<tr height='25'><td><i class=\"entypo-calendar\"></i>"+data[x].fch_registro+"</td><td>"+data[x].usuario+"</td><td><a href=\"pqr/ajax/descargar_archivo.php?id_archivo_pqr="+data[x].id_archivo_pqr+"\" targer=\"_blank\">"+data[x].nombre_archivo+"</a></td><td><a href=\"pqr/ajax/descargar_archivo.php?id_archivo_pqr="+data[x].id_archivo_pqr+"\" targer=\"_blank\"><i class=\"entypo-download\"></i></a></td><td>"+del+"</td></tr>";
            }
            html = html + "</table>";
            $("#"+panel).html(html);
            $.unblockUI("");
        }
    });
}

function eliminarArchivo(id_archivo_pqr,nombre_archivo){
    var q = confirm("Está seguro(a) de eliminar el archivo "+nombre_archivo+"?");
    if(q){
        $.ajax({
            async: true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url: "pqr/ajax/eliminar_archivo.php",
            data: {
                id_archivo_pqr: id_archivo_pqr
            },
            beforeSend: inicioEnvio,
            success: function(data) {
                if(!data.estado)
                    toastr.error(data.mensaje, null, opts);
                else
                    toastr.success(data.mensaje, null, opts);
                listarArchivo();
                $.unblockUI("");
            },
            error:function(){
                toastr.error("Error General", null, opts);
                $.unblockUI(""); 
            }
        });
    }
}

function agregarComentario(){
    if($.trim($("#txt_agregar_comentario").val())!=""){
        $.ajax({
            async: true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url: "pqr/ajax/guardar_comentario.php",
            data: {
                id_pqr: dataDetallePQR.id_pqr,
                comentario:$("#txt_agregar_comentario").val()
            },
            beforeSend: inicioEnvio,
            success: function(data) {
                if(!data.estado)
                    toastr.error(data.data, null, opts);

                listarComentario();
                $("#txt_agregar_comentario").val("");
                $.unblockUI("");
            }
        });
    }
}

function listarComentario(){
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "pqr/ajax/listar_comentarios.php",
        data: {
            id_pqr: dataDetallePQR.id_pqr
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            var html = "<ul class=\"comments-list\">";
            for (x in data)
                html = html + "<li><div class=\"comment-details\"><div class=\"comment-head\"><i class=\"entypo-user\"></i> | <a href=\"#\">"+data[x].usuario+"</a> | "+data[x].fch_registro+"</div><p class=\"comment-text\">"+data[x].comentario+"</p></div></div></li>";
            html = html + "</ul>"

            $("#lista-comentario").html(html);
            $.unblockUI("");
        }
    });
}

function buscarLuminaria(){
    if($.trim($("#txt_luminaria").val())!="" && $("#slt_municipio").val()!=""){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"global/buscar_luminaria.php",
            data:{
                id_municipio:$("#slt_municipio").val(),
                luminaria_no:$("#txt_luminaria").val()
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.estado){
                    if(data.id_luminaria == ""){
                        toastr.warning(data.mensaje, null, opts);
                    }
                    else{
                        toastr.success(data.mensaje, null, opts);
                    }
                    //$("#txt_tipo_luminaria").val(data.tipo_luminaria);
                    $("#id_luminaria").val(data.id_luminaria);
                    $("#txt_poste").val(data.poste_no);
                    $("#txt_direccion_reporte").val(data.direccion);
                    $("#slt_barrio_reporte").val(data.id_barrio);
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
        
        $("#id_luminaria").val("");
        $("#txt_poste").val("");
        $("#txt_direccion_reporte").val("");
        $("#slt_barrio_reporte").val("");

    }
}

function cerrarPQR(){
    if(dataDetallePQR.fch_cierre == null ){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"pqr/ajax/cerrar_pqr.php",
            data:{
                id_pqr:$("#id_pqr").val()
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.estado){
                    toastr.success(data.mensaje, null, opts);  
                    dataDetallePQR = "";
                    tablePQR.ajax.reload(); 
                    $("#modal-detalle-pqr").modal("hide");         
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
        toastr.info("PQR ya fue cerrada", null, opts);  
    }
}

function InitTableActividad(dataDet) {

    tableActividad = $("#tbl_actividad_pqr").on("preXhr.dt", function(e, settings, data) {
        data.id_pqr = dataDet.id_pqr
    }).DataTable({
        "aLengthMenu": [
            [3, 5, 10],
            [3, 5, 10]
        ],
        "bStateSave": false,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "bAutoWidth": true,
        "searching": false,
        "ajax": {
            "url": "pqr/ajax/listar_actividad.php",
            "type": "POST"
        },
        "columns": [
            { "data": "item", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "codigo", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "tipo", className: "alignRight", "searchable": false, "orderable": false },
            { "data": "descripcion", "searchable": false, "orderable": false },
            { "data": "direccion", "searchable": false, "orderable": false },
            { "data": "fch_reclamo", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "fch_ejecucion", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "tecnico", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "estado_actividad", className: "alignCenter", "searchable": false, "orderable": false },

        ],
        language: {
            url: "../../../../libreria/DataTableSp.json"
        }
    });
}