var tableEstadoActividad = "";
var dataDetalleEstadoActividad = "";
var accion = "";

$(function(){
    $("#btn_nuevo_tipo_luminaria").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoEstadoActividad(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_tipo_luminaria").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarEstadoActividad(dataDetalleEstadoActividad);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_tipo_luminaria").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarEstadoActividad(dataDetalleEstadoActividad);
            accion = "eliminar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_guardar_frm").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                guardarAccion(accion);
            }
            event.handld = true;
        }
        return false;
    });

    initTableEstadoActividad();
});

function initTableEstadoActividad(){
    if (!$.fn.DataTable.isDataTable("#tbl_tipo_luminaria")) {
        tableEstadoActividad = "";
        tableEstadoActividad = $("#tbl_tipo_luminaria").on("preXhr.dt", function(e, settings, data) {
                data.municipio = $("#descripcion").val()
        }).DataTable({ ///todo por una D jeje
            "aLengthMenu": [
                [15, 30, 50],
                [15, 30, 50]
            ],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": true,

            "ajax": {
                "url": "parametros/ajax/listar_tipo_luminaria.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "id_tipo_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_tipo_luminaria;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_tipo_luminaria tbody").on("click", "tr", function() {
        $("#tbl_tipo_luminaria tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleEstadoActividad = tableEstadoActividad.row(this).data();
        $("#id_tipo_luminaria").val(dataDetalleEstadoActividad.id_tipo_luminaria);
    });
}

function nuevoEstadoActividad(){
    $("#frm-titulo-tipo-luminaria").html("Nuevo Tipo Luminaria");
    $("#frm-tipo-luminaria").modal("show"); 
    $("#tbl_tipo_luminaria tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleEstadoActividad = "";
}

function editarEstadoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de luminaria a editar", null, opts);
    } 
    else {
        $("#frm-titulo-tipo-luminaria").html("Editar Tipo Luminaria");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#frm-tipo-luminaria").modal("show"); 
    }
}

function eliminarEstadoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de luminaria a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el tipo de luminaria "+dataDet.descripcion+"");
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
            var variable = $("#form-tipo-luminaria").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-tipo-luminaria").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_tipo_luminaria="+$("#id_tipo_luminaria").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_tipo_luminaria.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-tipo-luminaria").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableEstadoActividad.ajax.reload();
                dataDetalleEstadoActividad = "";
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