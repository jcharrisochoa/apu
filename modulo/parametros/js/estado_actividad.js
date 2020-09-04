var tableEstadoActividad = "";
var dataDetalleEstadoActividad = "";
var accion = "";

$(function(){
    $("#btn_nuevo_estado_actividad").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoEstadoActividad(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_estado_actividad").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarEstadoActividad(dataDetalleEstadoActividad);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_estado_actividad").click(function(){
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
    if (!$.fn.DataTable.isDataTable("#tbl_estado_actividad")) {
        tableEstadoActividad = "";
        tableEstadoActividad = $("#tbl_estado_actividad").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_estado_actividad.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "id_estado_actividad", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_estado_actividad;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_estado_actividad tbody").on("click", "tr", function() {
        $("#tbl_estado_actividad tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleEstadoActividad = tableEstadoActividad.row(this).data();
        $("#id_estado_actividad").val(dataDetalleEstadoActividad.id_estado_actividad);
    });
}

function nuevoEstadoActividad(){
    $("#frm-titulo-estado-actividad").html("Nuevo Estado Actividad");
    $("#frm-estado-actividad").modal("show"); 
    $("#tbl_estado_actividad tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleEstadoActividad = "";
}

function editarEstadoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el estado de la actividad a editar", null, opts);
    } 
    else {
        $("#frm-titulo-estado-actividad").html("Editar Estado Actividad");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#frm-estado-actividad").modal("show"); 
    }
}

function eliminarEstadoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el estado de la actividad a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el estado de la actividad "+dataDet.descripcion+"");
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
            var variable = $("#form-estado-actividad").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-estado-actividad").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_estado_actividad="+$("#id_estado_actividad").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_estado_actividad.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-estado-actividad").modal("hide"); 
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