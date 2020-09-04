var tableDepartamento = "";
var dataDetalleDepartamento = "";
var accion = "";

$(function(){
    $("#btn_nuevo_departamento").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoDepartamento(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_editar_departamento").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarDepartamento(dataDetalleDepartamento);
            accion = "editar";
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_departamento").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            eliminarDepartamento(dataDetalleDepartamento);
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

    initTableDepartamento();
});

function initTableDepartamento(){
    if (!$.fn.DataTable.isDataTable("#tbl_departamento")) {
        tableDepartamento = "";
        tableDepartamento = $("#tbl_departamento").on("preXhr.dt", function(e, settings, data) {
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
                "url": "parametros/ajax/listar_departamento.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "descripcion",className: "alignCenter","searchable": true,"orderable": true,"name":"descripcion"},
                { "data": "id_departamento", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_departamento;
                //$(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_departamento tbody").on("click", "tr", function() {
        $("#tbl_departamento tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleDepartamento = tableDepartamento.row(this).data();
        $("#id_departamento").val(dataDetalleDepartamento.id_departamento);
    });
}

function nuevoDepartamento(){
    $("#frm-titulo-departamento").html("Nuevo Departamento");
    $("#frm-departamento").modal("show"); 
    $("#tbl_departamento tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleDepartamento = "";
}

function editarDepartamento(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el departamento a editar", null, opts);
    } 
    else {
        $("#frm-titulo-departamento").html("Editar Departamento");
        //$("#txt_poste_no").val(dataDet.poste_no);
        $("#txt_descripcion").val(dataDet.descripcion);
        $("#frm-departamento").modal("show"); 
    }
}

function eliminarDepartamento(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione el departamento a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar el departamento "+dataDet.descripcion+"");
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
            var variable = $("#form-departamento").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-departamento").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_departamento="+$("#id_departamento").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/guardar_departamento.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-departamento").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableDepartamento.ajax.reload();
                dataDetalleDepartamento = "";
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