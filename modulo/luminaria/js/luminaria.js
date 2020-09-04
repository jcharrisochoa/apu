var tableLuminaria = "";
var tableActividad = "";
var dataDetalleLuminaria = "";
var dataSet = [];
var accion = "";

$(function() {

    $("#municipio").change(function() {
        listarBarrio("municipio","barrio");
    });    
    
    $("#slt_municipio").change(function() {
        listarBarrio("slt_municipio","slt_barrio");
    });

    $("#txt_latitud").numeric();

    $("#txt_longitud").numeric();

    $("#btn_buscar_luminaria").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            tableLuminaria.ajax.reload(function(){
                $("#modal-text-global").html("Se encontraron "+tableLuminaria.page.info().recordsTotal+" registros");
                $("#modal-mensaje-global").modal("show");
                dataDetalleLuminaria = "";
            });
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_detalle_luminaria").click(function(){
        //console.log(dataDetalleLuminaria);
        verDetalle(dataDetalleLuminaria);
    });

    $("#btn_nueva_luminaria").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            nuevaLuminaria(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_editar_luminaria").click(function(){
        editarLuminaria(dataDetalleLuminaria);
        accion = "editar";
    });

    $("#btn_eliminar_luminaria").click(function(){
        eliminarLuminaria(dataDetalleLuminaria);
        accion = "eliminar";
    });

    $("#btn_guardar_luminaria").click(function(){
        if(ValidarDatos(".requerido")){
            guardarAccion(accion);
        }
    });

    $("#frm-luminaria").on('hidden.bs.modal',function(){ //accion cuando se cierra la ventana
        clearInput(".clear");
        listarBarrio("slt_municipio","slt_barrio");
        $("#tbl_luminaria tbody tr").removeClass("highlight");
        dataDetalleLuminaria = "";
        accion="";
    });

    InitTableLuminaria();
});

function listarBarrio(controlMunicipio,controlBarrio) {
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "luminaria/ajax/listar_barrio.php",
        data: {
            id_municipio: $("#"+controlMunicipio).val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            $("#"+controlBarrio).empty();
            for (x in data.lista) {
                var selected = (dataDetalleLuminaria.id_barrio == data.lista[x].id_barrio)?"selected":"";

                $("#"+controlBarrio).append('<option value="' + data.lista[x].id_barrio + '"'+selected+'>' + data.lista[x].descripcion + '</option>');
            }
            $.unblockUI("");
        }
    });
}

function InitTableLuminaria() {
    if (!$.fn.DataTable.isDataTable("#tbl_luminaria")) {
        tableLuminaria = "";
        tableLuminaria = $("#tbl_luminaria").on("preXhr.dt", function(e, settings, data) {
            data.luminaria_no = $("#luminaria_no").val(),
            data.poste_no = $("#poste_no").val(),
                data.municipio = $("#municipio").val(),
                data.barrio = $("#barrio").val(),
                data.fechaini = $("#fch_instalacion_ini").val(),
                data.fechafin = $("#fch_instalacion_fin").val(),
                data.direccion = $("#direccion").val(),
                data.tipo = $("#tipo").val()
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
                "url": "luminaria/ajax/listar_luminaria.php",
                "type": "POST"
            },
            "columns": [

                { "data": "item", "searchable": false },
                { "data": "municipio",className: "alignCenter","searchable": false,"orderable": true,"name":"m.descripcion"},
                { "data": "poste_no",className: "alignCenter","searchable": false,"orderable": true,"name":"l.poste_no"},
                { "data": "luminaria_no",className: "alignCenter","searchable": false,"orderable": true,"name":"l.luminaria_no"},
                { "data": "tipo",className: "alignCenter","searchable": false,"orderable": true,"name":"tl.descripcion"},
                { "data": "direccion",className: "alignCenter","searchable": false,"orderable": true,"name":"l.direccion"},
                { "data": "latitud", className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "longitud", className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "fch_instalacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "fch_registro", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_municipio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_barrio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tercero_proveedor", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_estado_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }

            ],
            "order": [
                [2, "DESC"],
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_luminaria;
                $(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_luminaria tbody").on("click", "tr", function() {
        $("#tbl_luminaria tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleLuminaria = tableLuminaria.row(this).data();
        $("#id_luminaria").val(dataDetalleLuminaria.id_luminaria);
    });
}

function verDetalle(dataDet) {
    if (dataDet.length == 0) {
        //$("poste_no").html("");
        toastr.warning("Seleccione la luminaria a visualizar", null, opts);
    } else {
        $("#poste_no").html("Poste " + dataDet.poste_no);
        $("#td_poste_no").html(dataDet.poste_no);
        $("#td_luminaria_no").html(dataDet.luminaria_no);
        $("#td_tipo").html(dataDet.tipo);
        $("#td_municipio").html(dataDet.municipio);
        $("#td_barrio").html(dataDet.barrio);
        $("#td_direccion").html(dataDet.direccion);
        $("#td_latitud").html(dataDet.latitud);
        $("#td_longitud").html(dataDet.longitud);
        $("#td_fch_instalacion").html(dataDet.fch_instalacion);
        $("#td_fch_registro").html(dataDet.fch_registro);
        $("#td_usuario").html(dataDet.usuario);
        $("#td_estado").html(dataDet.estado);
        $("#td_proveedor").html(dataDet.proveedor);
        if ($.fn.DataTable.isDataTable("#tbl_actividad_luminaria")) {
            tableActividad.destroy();
        }
        InitTableActividad(dataDet);
        $("#modal-detalle-luminaria").modal("show");
    }
}

function InitTableActividad(dataDet) {
    //tableActividad = "";
    tableActividad = $("#tbl_actividad_luminaria").on("preXhr.dt", function(e, settings, data) {
        data.id_luminaria = dataDet.id_luminaria
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
            "url": "luminaria/ajax/listar_actividad.php",
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

function nuevaLuminaria(){
    $("#frm-titulo-luminaria").html("Nuevo Punto Lum&iacute;nico");
    $("#frm-luminaria").modal("show"); 
    $("#tbl_luminaria tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleLuminaria = "";
}

function editarLuminaria(dataDet){
    //console.log(dataDet);
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la luminaria a editar", null, opts);
    } 
    else {
        $("#frm-titulo-luminaria").html("Editar Punto Lum&iacute;nico");
        $("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_luminaria_no").val(dataDet.luminaria_no);
        $("#slt_tipo_luminaria").val(dataDet.id_tipo_luminaria).change();
        $("#slt_municipio").val(dataDet.id_municipio).change();
        $("#slt_barrio").val(dataDet.barrio);
        $("#txt_direccion").val(dataDet.direccion);
        $("#txt_latitud").val(dataDet.latitud);
        $("#txt_longitud").val(dataDet.longitud);
        $("#txt_fch_instalacion").val(dataDet.fch_instalacion);
        $("#slt_estado").val(dataDet.id_estado_luminaria).change();
        $("#slt_proveedor").val(dataDet.id_tercero_proveedor).change();
        $("#frm-luminaria").modal("show"); 
    }
}

function eliminarLuminaria(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la luminaria a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el punto lum&iacute;nico ?<br>Poste No:<strong>"+dataDet.poste_no+"</strong><br>Luminaria No:<strong>"+dataDet.luminaria_no+"</strong>");
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

function guardarAccion(accion){
    switch(accion){
        case "nuevo":
            var variable = $("#form-luminaria").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-luminaria").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_luminaria="+$("#id_luminaria").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"luminaria/ajax/guardar_accion.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-luminaria").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableLuminaria.ajax.reload();
                dataDetalleLuminaria = "";
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

