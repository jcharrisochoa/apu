var tableTipoPQR = "";
var dataDetalleTipoPQR = "";
var accion = "";

$(function(){
    $("#txt_dia").numeric();

    $("#btn_nuevo_tipo_pqr").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoEstadoActividad(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_tipo_pqr").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarEstadoActividad(dataDetalleTipoPQR);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_tipo_pqr").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarEstadoActividad(dataDetalleTipoPQR);
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
    if (!$.fn.DataTable.isDataTable("#tbl_tipo_pqr")) {
        tableTipoPQR = "";
        tableTipoPQR = $("#tbl_tipo_pqr").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_tipo_pqr.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "dias_vencimiento",className: "alignCenter","searchable": true,"orderable": true,"name":"dias_vencimiento"},
                { "data": "estado",className: "alignCenter","searchable": true,"orderable": true,"name":"estado"},
                { "data": "id_tipo_pqr", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_tipo_pqr;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_tipo_pqr tbody").on("click", "tr", function() {
        $("#tbl_tipo_pqr tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleTipoPQR = tableTipoPQR.row(this).data();
        $("#id_tipo_pqr").val(dataDetalleTipoPQR.id_tipo_pqr);
    });
}

function nuevoEstadoActividad(){
    $("#frm-titulo-tipo-pqr").html("Nuevo Tipo PQR");
    $("#frm-tipo-pqr").modal("show"); 
    $("#tbl_tipo_pqr tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleTipoPQR = "";
}

function editarEstadoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de PQR a editar", null, opts);
    } 
    else {
        $("#frm-titulo-tipo-pqr").html("Editar Tipo PQR");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#txt_dia").val(dataDet.dias_vencimiento);
        $("#slt_estado").val(dataDet.estado).change();
        $("#frm-tipo-pqr").modal("show"); 
    }
}

function eliminarEstadoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo PQR a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el tipo PQR "+dataDet.descripcion+"");
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
            var variable = $("#form-tipo-pqr").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-tipo-pqr").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_tipo_pqr="+$("#id_tipo_pqr").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_tipo_pqr.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-tipo-pqr").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableTipoPQR.ajax.reload();
                dataDetalleTipoPQR = "";
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