var tableArticulo = "";
var dataDetalleArticulo = "";
var accion = "";

$(function(){
    $("#btn_nuevo_articulo").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoArticulo(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_articulo").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarArticulo(dataDetalleArticulo);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_articulo").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarArticulo(dataDetalleArticulo);
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

    initTableArticulo();
});

function initTableArticulo(){
    if (!$.fn.DataTable.isDataTable("#tbl_articulo")) {
        tableArticulo = "";
        tableArticulo = $("#tbl_articulo").on("preXhr.dt", function(e, settings, data) {
                data.descripcion = $("#descripcion").val()
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
                "url": "parametros/ajax/listar_articulo.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "id_articulo", className: "text-center", "searchable": true, "orderable": true,"name":"id_articulo"},
                { "data": "descripcion",className: "text-left","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "clase","bVisible": false,className: "text-center","searchable": true,"orderable": true,"name":"clase"},
                { "data": "descripcion_clase",className: "text-center","searchable": false,"orderable": false}
               
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_articulo;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_articulo tbody").on("click", "tr", function() {
        $("#tbl_articulo tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleArticulo = tableArticulo.row(this).data();
        $("#id_articulo").val(dataDetalleArticulo.id_articulo);
    });
}

function nuevoArticulo(){
    $("#frm-titulo-articulo").html("Nuevo Servicio");
    $("#frm-articulo").modal("show"); 
    $("#tbl_articulo tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleArticulo = "";
}

function editarArticulo(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el servicio a editar", null, opts);
    } 
    else {
        $("#frm-titulo-articulo").html("Editar Servicio");
        $("#slt_clase").val(dataDet.clase).change();
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#frm-articulo").modal("show"); 
    }
}

function eliminarArticulo(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el servicio a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el servicio "+dataDet.descripcion+"");
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
            var variable = $("#form-articulo").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-articulo").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_articulo="+$("#id_articulo").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_articulo.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-articulo").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableArticulo.ajax.reload();
                dataDetalleArticulo = "";
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