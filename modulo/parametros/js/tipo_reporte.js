var tableTipoReporte = "";
var dataDetalleTipoReporte = "";
var accion = "";

$(function(){
    $("#btn_nuevo_tipo_reporte").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoTipoReporte(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_tipo_reporte").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarTipoReporte(dataDetalleTipoReporte);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_tipo_reporte").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarTipoReporte(dataDetalleTipoReporte);
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

    initTableTipoReporte();
});

function initTableTipoReporte(){
    if (!$.fn.DataTable.isDataTable("#tbl_tipo_reporte")) {
        tableTipoReporte = "";
        tableTipoReporte = $("#tbl_tipo_reporte").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_tipo_reporte.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "id_tipo_reporte", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_tipo_reporte;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_tipo_reporte tbody").on("click", "tr", function() {
        $("#tbl_tipo_reporte tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleTipoReporte = tableTipoReporte.row(this).data();
        $("#id_tipo_reporte").val(dataDetalleTipoReporte.id_tipo_reporte);
    });
}

function nuevoTipoReporte(){
    $("#frm-titulo-tipo-reporte").html("Nuevo tipo de reporte");
    $("#frm-tipo-reporte").modal("show"); 
    $("#tbl_tipo_reporte tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleTipoReporte = "";
}

function editarTipoReporte(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de reporte a editar", null, opts);
    } 
    else {
        $("#frm-titulo-tipo-reporte").html("Editar Tipo Actividad");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#frm-tipo-reporte").modal("show"); 
    }
}

function eliminarTipoReporte(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de reporte a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el tipo de reporte "+dataDet.descripcion+"");
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
            var variable = $("#form-tipo-reporte").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-tipo-reporte").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_tipo_reporte="+$("#id_tipo_reporte").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_tipo_reporte.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-tipo-reporte").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableTipoReporte.ajax.reload();
                dataDetalleTipoReporte = "";
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