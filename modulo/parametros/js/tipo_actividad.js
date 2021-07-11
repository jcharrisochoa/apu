var tableTipoActividad = "";
var dataDetalleTipoActividad = "";
var accion = "";

$(function(){
    $("#btn_nuevo_tipo_actividad").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoTipoActividad(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_tipo_actividad").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarTipoActividad(dataDetalleTipoActividad);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_tipo_actividad").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarTipoActividad(dataDetalleTipoActividad);
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

    initTableTipoActividad();
});

function initTableTipoActividad(){
    if (!$.fn.DataTable.isDataTable("#tbl_tipo_actividad")) {
        tableTipoActividad = "";
        tableTipoActividad = $("#tbl_tipo_actividad").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_tipo_actividad.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "text-left","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "id_tipo_actividad", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "instalacion",className: "text-center","searchable": false,"orderable": true,"name":"instalacion"},
                { "data": "preventivo",className: "text-center","searchable": false,"orderable": true,"name":"preventivo"},
                { "data": "correctivo",className: "text-center","searchable": false,"orderable": true,"name":"correctivo"},
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_tipo_actividad;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_tipo_actividad tbody").on("click", "tr", function() {
        $("#tbl_tipo_actividad tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleTipoActividad = tableTipoActividad.row(this).data();
        $("#id_tipo_actividad").val(dataDetalleTipoActividad.id_tipo_actividad);
    });
}

function nuevoTipoActividad(){
    $("#frm-titulo-tipo-actividad").html("Nuevo Tipo de Actividad");
    $("#frm-tipo-actividad").modal("show"); 
    $("#tbl_tipo_actividad tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleTipoActividad = "";
}

function editarTipoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de actividad a editar", null, opts);
    } 
    else {
        $("#frm-titulo-tipo-actividad").html("Editar Tipo Actividad");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#slt_instalacion").val(dataDet.instalacion).change();
        $("#slt_preventivo").val(dataDet.preventivo).change();
        $("#slt_correctivo").val(dataDet.correctivo).change();
        $("#frm-tipo-actividad").modal("show"); 
    }
}

function eliminarTipoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el tipo de actividad a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el tipo de actividad "+dataDet.descripcion+"");
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
            var variable = $("#form-tipo-actividad").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-tipo-actividad").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_tipo_actividad="+$("#id_tipo_actividad").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_tipo_actividad.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-tipo-actividad").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableTipoActividad.ajax.reload();
                dataDetalleTipoActividad = "";
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