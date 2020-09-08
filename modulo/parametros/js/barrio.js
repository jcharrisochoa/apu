var tableBarrio = "";
var dataDetalleBarrio = "";
var accion = "";

$(function(){
    $("#btn_nuevo_barrio").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoBarrio(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_barrio").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarBarrio(dataDetalleBarrio);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_barrio").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarBarrio(dataDetalleBarrio);
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

    initTableBarrio();
});

function initTableBarrio(){
    if (!$.fn.DataTable.isDataTable("#tbl_barrio")) {
        tableBarrio = "";
        tableBarrio = $("#tbl_barrio").on("preXhr.dt", function(e, settings, data) {
                data.barrio = ""
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
                "url": "parametros/ajax/listar_barrio.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "municipio",className: "alignCenter","searchable": true,"orderable": true,"name":"m.descripcion"},
                { "data": "barrio",className: "alignCenter","searchable": true,"orderable": true,"name":"b.descripcion"},
                { "data": "id_municipio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_barrio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_barrio;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_barrio tbody").on("click", "tr", function() {
        $("#tbl_barrio tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleBarrio = tableBarrio.row(this).data();
        $("#id_barrio").val(dataDetalleBarrio.id_barrio);
    });
}

function nuevoBarrio(){
    $("#frm-titulo-barrio").html("Nuevo Barrio");
    $("#frm-barrio").modal("show"); 
    $("#tbl_barrio tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleBarrio = "";
}

function editarBarrio(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el barrio a editar", null, opts);
    } 
    else {
        $("#frm-titulo-barrio").html("Editar Barrio");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.barrio);
        $("#slt_municipio").val(dataDet.id_municipio).change();
        $("#frm-barrio").modal("show"); 
    }
}

function eliminarBarrio(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el barrio a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el barrio "+dataDet.barrio+"");
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
            var variable = $("#form-barrio").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-barrio").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_barrio="+$("#id_barrio").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_barrio.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-barrio").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableBarrio.ajax.reload();
                dataDetalleBarrio = "";
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