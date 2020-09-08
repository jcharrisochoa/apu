var tablePQR = "";
var dataDetallePQR = "";
var dataSet = [];
var accion = "";

$(function(){
    $("#txt_identificacion").numeric();

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
   
    //$(".make-switch").bootstrapSwitch('toggleRadioState');
    $('input.icheck-11').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-yellow'
    });

    $("#btn_buscar_usuario_servicio").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            buscarUsuarioServicio();    
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

    $("#btn_buscar_punto_luminico").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if($("#slt_municipio").val()!=""){
                $("#frm-punto-luminico").modal("show"); 
                $("#frm-punto-luminico").css("z-index", "15001");
                $("#flt_municipio").val($("#slt_municipio").val()).change();
                tablePuntoLuminico.ajax.reload();
            }
            else
                toastr.warning("Seleccione el municipio", null, opts);

            event.handld = true;
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
    
    //$("#frm-punto-luminico").on('hidden.bs.modal',function(){
       // $(this).modal('dispose');
        //$(this).off('hidden.bs.modal'); 
      //  $('body').removeClass('modal-open');
        //$('.modal-backdrop').remove();
        //$("#frm-pqr").modal("show"); 
    //});

    $("#frm-punto-luminico").off().on('hidden.bs.modal',function(){
        //$(".modal-backdrop").not(':first').remove();
        $("#frm-pqr").css("z-index", "15000"); //foco del modal inicial
        $('body').addClass('modal-open'); //render nuevamente el modal inicial
        //console.log(dataDetallePuntoLuminico);
        $("#id_luminaria").val(dataDetallePuntoLuminico.id_luminaria);
        $("#txt_poste").val(dataDetallePuntoLuminico.poste_no);
        $("#txt_luminaria").val(dataDetallePuntoLuminico.luminaria_no);
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
            data.estado = $("#estado").val()
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
                { "data": "medio_recepcion",className: "alignCenter","searchable": false,"orderable": true,"name":"mr.descripcion"},
                { "data": "nombre",className: "alignCenter","searchable": false,"orderable": true,"name":"us.nombre"},
                { "data": "fch_pqr", className: "alignCenter", "searchable": false, "orderable": true,"name":"p.fch_pqr" },
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
                { "data": "identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "nombre", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "direccion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "telefono", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "email", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "permitir_edicion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "permitir_eliminar", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
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
            listarArchivo();
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

function listarArchivo(){    
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
            for (x in data) {
                html = html + "<tr height='25'><td><i class=\"entypo-calendar\"></i>"+data[x].fch_registro+"</td><td>"+data[x].usuario+"</td><td><a href=\"pqr/ajax/descargar_archivo.php?id_archivo_pqr="+data[x].id_archivo_pqr+"\" targer=\"_blank\">"+data[x].nombre_archivo+"</a></td><td><a href=\"pqr/ajax/descargar_archivo.php?id_archivo_pqr="+data[x].id_archivo_pqr+"\" targer=\"_blank\"><i class=\"entypo-download\"></i></a></td><td><a href=\"#\" title=\"Eliminar Archivo\" onclick=\"eliminarArchivo("+data[x].id_archivo_pqr+",'"+data[x].nombre_archivo+"')\"><i class=\"entypo-trash\"></i></a></td></tr>";
            }
            html = html + "</table>";
            $("#panel-archivo").html(html);
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