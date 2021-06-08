var tableClaseIluminacion = "";
var dataDetalleClaseIluminacion = "";
var accion = "";

$(function(){
    $("#btn_nuevo_clase_iluminacion").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoClaseIluminacion(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_clase_iluminacion").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarClaseIluminacion(dataDetalleClaseIluminacion);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_clase_iluminacion").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarClaseIluminacion(dataDetalleClaseIluminacion);
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

    initTableClaseIluminacion();
});

function initTableClaseIluminacion(){
    if (!$.fn.DataTable.isDataTable("#tbl_clase_iluminacion")) {
        tableClaseIluminacion = "";
        tableClaseIluminacion = $("#tbl_clase_iluminacion").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_clase_iluminacion.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "abreviatura",className: "alignCenter","searchable": true,"orderable": true,"name":"abreviatura"},
                { "data": "id_clase_iluminacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_clase_iluminacion;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_clase_iluminacion tbody").on("click", "tr", function() {
        $("#tbl_clase_iluminacion tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleClaseIluminacion = tableClaseIluminacion.row(this).data();
        $("#id_clase_iluminacion").val(dataDetalleClaseIluminacion.id_clase_iluminacion);
    });
}

function nuevoClaseIluminacion(){
    $("#frm-titulo-clase-iluminacion").html("Nueva Clase de Iluminación");
    $("#frm-clase-iluminacion").modal("show"); 
    $("#tbl_clase_iluminacion tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleClaseIluminacion = "";
}

function editarClaseIluminacion(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la clase de iluminacion a editar", null, opts);
    } 
    else {
        $("#frm-titulo-clase-iluminacion").html("Editar Clase de Iluminación");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#txt_abreviatura").val(dataDet.abreviatura);
        $("#frm-clase-iluminacion").modal("show"); 
    }
}

function eliminarClaseIluminacion(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la clase de iluminación a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar la clase de iluminación "+dataDet.descripcion+"");
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
            var variable = $("#form-clase-iluminacion").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-clase-iluminacion").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_clase_iluminacion="+$("#id_clase_iluminacion").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_clase_iluminacion.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-clase-iluminacion").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableClaseIluminacion.ajax.reload();
                dataDetalleClaseIluminacion = "";
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