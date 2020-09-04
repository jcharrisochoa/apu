var tableEstadoLuminaria = "";
var dataDetalleEstadoLuminaria = "";
var accion = "";

$(function(){
    $("#btn_nuevo_estado_luminaria").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoEstadoLuminaria(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_estado_luminaria").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarEstadoLuminaria(dataDetalleEstadoLuminaria);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_estado_luminaria").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarEstadoLuminaria(dataDetalleEstadoLuminaria);
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

    initTableEstadoLuminaria();
});

function initTableEstadoLuminaria(){
    if (!$.fn.DataTable.isDataTable("#tbl_estado_luminaria")) {
        tableEstadoLuminaria = "";
        tableEstadoLuminaria = $("#tbl_estado_luminaria").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_estado_luminaria.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "id_estado_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_estado_luminaria;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_estado_luminaria tbody").on("click", "tr", function() {
        $("#tbl_estado_luminaria tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleEstadoLuminaria = tableEstadoLuminaria.row(this).data();
        $("#id_estado_luminaria").val(dataDetalleEstadoLuminaria.id_estado_luminaria);
    });
}

function nuevoEstadoLuminaria(){
    $("#frm-titulo-estado-luminaria").html("Nuevo Estado Luminaria");
    $("#frm-estado-luminaria").modal("show"); 
    $("#tbl_estado_luminaria tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleEstadoLuminaria = "";
}

function editarEstadoLuminaria(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el estado de la luminaria a editar", null, opts);
    } 
    else {
        $("#frm-titulo-estado-luminaria").html("Editar Estado Luminaria");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#frm-estado-luminaria").modal("show"); 
    }
}

function eliminarEstadoLuminaria(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el estado de la luminaria a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el estado de la luminaria "+dataDet.descripcion+"");
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
            var variable = $("#form-estado-luminaria").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-estado-luminaria").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_estado_luminaria="+$("#id_estado_luminaria").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_estado_luminaria.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-estado-luminaria").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableEstadoLuminaria.ajax.reload();
                dataDetalleEstadoLuminaria = "";
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