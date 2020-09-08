var tableEstadoPQR = "";
var dataDetalleEstadoPQR = "";
var accion = "";

$(function(){
    $("#txt_dia").numeric();

    $("#btn_nuevo_estado_pqr").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoEstadoActividad(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_estado_pqr").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarEstadoActividad(dataDetalleEstadoPQR);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_estado_pqr").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarEstadoActividad(dataDetalleEstadoPQR);
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
    if (!$.fn.DataTable.isDataTable("#tbl_estado_pqr")) {
        tableEstadoPQR = "";
        tableEstadoPQR = $("#tbl_estado_pqr").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_estado_pqr.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "permitir_edicion",className: "alignCenter","searchable": false,"orderable": false,},
                { "data": "permitir_eliminar",className: "alignCenter","searchable": false,"orderable": false},
                { "data": "id_estado_pqr", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_estado_pqr;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_estado_pqr tbody").on("click", "tr", function() {
        $("#tbl_estado_pqr tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleEstadoPQR = tableEstadoPQR.row(this).data();
        $("#id_estado_pqr").val(dataDetalleEstadoPQR.id_estado_pqr);
    });
}

function nuevoEstadoActividad(){
    $("#frm-titulo-estado-pqr").html("Nuevo Estado PQR");
    $("#frm-estado-pqr").modal("show"); 
    $("#tbl_estado_pqr tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleEstadoPQR = "";
}

function editarEstadoActividad(dataDet){
   // console.log(dataDet);
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el estado de PQR a editar", null, opts);
    } 
    else {
        $("#frm-titulo-estado-pqr").html("Editar Estado PQR");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#slt_permitir_edicion").val(dataDet.permitir_edicion).change();
        $("#slt_permitir_eliminar").val(dataDet.permitir_eliminar).change();
        $("#frm-estado-pqr").modal("show"); 
    }
}

function eliminarEstadoActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el estado PQR a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el estado PQR "+dataDet.descripcion+"");
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
            var variable = $("#form-estado-pqr").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-estado-pqr").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_estado_pqr="+$("#id_estado_pqr").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_estado_pqr.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-estado-pqr").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableEstadoPQR.ajax.reload();
                dataDetalleEstadoPQR = "";
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