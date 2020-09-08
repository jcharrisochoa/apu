var tableTipoIdentificacion = "";
var dataDetalleTipoIdentificacion = "";
var accion = "";

$(function(){
    $("#btn_nuevo_tipo_identificacion").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoTipoIdentificacion(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_tipo_identificacion").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarTipoIdentificacion(dataDetalleTipoIdentificacion);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_tipo_identificacion").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarTipoIdentificacion(dataDetalleTipoIdentificacion);
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

    initTableTipoIdentificacion();
});

function initTableTipoIdentificacion(){
    if (!$.fn.DataTable.isDataTable("#tbl_tipo_identificacion")) {
        tableTipoIdentificacion = "";
        tableTipoIdentificacion = $("#tbl_tipo_identificacion").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_tipo_identificacion.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "abreviatura",className: "alignCenter","searchable": true,"orderable": true,"name":"abreviatura"},
                { "data": "id_tipo_identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_tipo_identificacion;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_tipo_identificacion tbody").on("click", "tr", function() {
        $("#tbl_tipo_identificacion tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleTipoIdentificacion = tableTipoIdentificacion.row(this).data();
        $("#id_tipo_identificacion").val(dataDetalleTipoIdentificacion.id_tipo_identificacion);
    });
}

function nuevoTipoIdentificacion(){
    $("#frm-titulo-tipo-identificacion").html("Nuevo Tipo de Identificacion");
    $("#frm-tipo-identificacion").modal("show"); 
    $("#tbl_tipo_identificacion tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleTipoIdentificacion = "";
}

function editarTipoIdentificacion(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de identificacion a editar", null, opts);
    } 
    else {
        $("#frm-titulo-tipo-identificacion").html("Editar Tipo Identificacion");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#txt_abreviatura").val(dataDet.abreviatura);
        $("#frm-tipo-identificacion").modal("show"); 
    }
}

function eliminarTipoIdentificacion(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de identificacion a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el tipo de identificacion "+dataDet.descripcion+"");
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
            var variable = $("#form-tipo-identificacion").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-tipo-identificacion").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_tipo_identificacion="+$("#id_tipo_identificacion").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_tipo_identificacion.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-tipo-identificacion").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableTipoIdentificacion.ajax.reload();
                dataDetalleTipoIdentificacion = "";
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