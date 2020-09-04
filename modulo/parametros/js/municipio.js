var tableMunicipio = "";
var dataDetalleMunicipio = "";
var accion = "";

$(function(){
    $("#txt_latitud").numeric()
    $("#txt_longitud").numeric();

    $("#btn_nuevo_municipio").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoMunicipio(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_municipio").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarMunicipio(dataDetalleMunicipio);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_municipio").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarMunicipio(dataDetalleMunicipio);
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

    initTableMunicipio();
});

function initTableMunicipio(){
    if (!$.fn.DataTable.isDataTable("#tbl_municipio")) {
        tableMunicipio = "";
        tableMunicipio = $("#tbl_municipio").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_municipio.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "departamento",className: "alignCenter","searchable": true,"orderable": true,"name":"d.descripcion"},
                { "data": "municipio",className: "alignCenter","searchable": true,"orderable": true,"name":"m.descripcion"},
                { "data": "tiene_contrato",className: "alignCenter","searchable": true,"orderable": true,"name":"m.tiene_contrato"},
                { "data": "latitud",className: "alignCenter","searchable": true,"orderable": true,"name":"m.latitud"},
                { "data": "longitud",className: "alignCenter","searchable": true,"orderable": true,"name":"m.longitud"},
                { "data": "id_municipio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_departamento", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_municipio;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_municipio tbody").on("click", "tr", function() {
        $("#tbl_municipio tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleMunicipio = tableMunicipio.row(this).data();
        $("#id_municipio").val(dataDetalleMunicipio.id_municipio);
    });
}

function nuevoMunicipio(){
    $("#frm-titulo-municipio").html("Nuevo Municipio");
    $("#frm-municipio").modal("show"); 
    $("#tbl_municipio tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleMunicipio = "";
}

function editarMunicipio(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el municipio a editar", null, opts);
    } 
    else {
        $("#frm-titulo-municipio").html("Editar Municipio");
        $("#slt_departamento").val(dataDet.id_departamento).change();
        $("#txt_descripcion").val(dataDet.municipio);
        $("#txt_latitud").val(dataDet.latitud);
        $("#txt_longitud").val(dataDet.longitud);
        $("#slt_tiene_contrato").val(dataDet.tiene_contrato).change();
        $("#frm-municipio").modal("show"); 
    }
}

function eliminarMunicipio(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el municipio a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el municipio "+dataDet.municipio+"");
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
            var variable = $("#form-municipio").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-municipio").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_municipio="+$("#id_municipio").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_municipio.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-municipio").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableMunicipio.ajax.reload();
                dataDetalleMunicipio = "";
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