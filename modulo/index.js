var tablePuntoLuminico = "";
var dataDetallePuntoLuminico = "";

$(function(){
    initTablePuntoLuminico();
});

function cargarModulo(url){
    $("#contenido").load(url);
    
    $("#flt_direccion").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               actualizarTabla();
               event.handld = true;
           };
       }
    });

    $("#flt_poste_no").keydown(function(event){
        if(event.keyCode == 13){
        if(event.handled !== true){
            event.preventDefault();
            actualizarTabla();
            event.handld = true;
        };
    }
    });

    $("#flt_luminaria_no").keydown(function(event){
        if(event.keyCode == 13){
        if(event.handled !== true){
            event.preventDefault();
            actualizarTabla();
            event.handld = true;
        };
    }
    });

    $("#flt_municipio").change(function() {
        listarBarrio("flt_municipio","flt_barrio");
    });

    $("#btn_buscar_luminaria").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            actualizarTabla();
            event.handld = true;
        }
        return false;
    });
}

function actualizarTabla(){
    tablePuntoLuminico.ajax.reload(function(){
        toastr.info("Se encontraron "+tablePuntoLuminico.page.info().recordsTotal+" registros", null, opts);
    });
}

function initTablePuntoLuminico(){
    if (!$.fn.DataTable.isDataTable("#tbl_punto_luminico")) {
        //tablePuntoLuminico = "";
        tablePuntoLuminico = $("#tbl_punto_luminico").on("preXhr.dt", function(e, settings, data) {
            data.luminaria_no = $("#flt_luminaria_no").val(),
            data.poste_no = $("#flt_poste_no").val(),
                data.municipio = $("#flt_municipio").val(),
                data.barrio = $("#flt_barrio").val(),
                data.direccion = $("#flt_direccion").val(),
                data.tipo = $("#flt_tipo").val()
        }).DataTable({ ///todo por una D jeje
            "aLengthMenu": [
                [5,10,15],
                [5,10,15]
            ],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": false,

            "ajax": {
                "url": "global/lista_global_luminaria.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "municipio",className: "alignCenter","searchable": false,"orderable": true,"name":"m.descripcion"},
                { "data": "tipo",className: "alignCenter","searchable": false,"orderable": true,"name":"tl.descripcion"},
                { "data": "poste_no",className: "alignCenter","searchable": false,"orderable": true,"name":"l.poste_no"},
                { "data": "luminaria_no",className: "alignCenter","searchable": false,"orderable": true,"name":"l.luminaria_no"},
                { "data": "barrio",className: "alignCenter","searchable": false,"orderable": true,"name":"b.descripcion"},
                { "data": "direccion",className: "alignCenter","searchable": false,"orderable": true,"name":"l.direccion"},
                { "data": "id_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
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
        $("#tbl_punto_luminico tbody").on("click", "tr", function() {
            $("#tbl_punto_luminico tbody tr").removeClass("highlight");
            $(this).addClass("highlight");
            dataDetallePuntoLuminico = tablePuntoLuminico.row(this).data();
            $("#frm-punto-luminico").modal("hide");
            //$("#id_departamento").val(dataDetalleDepartamento.id_departamento);
        });
    }
}

function listarBarrio(controlMunicipio,controlBarrio) {
    var selected = "";
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "luminaria/ajax/listar_barrio.php",
        data: {
            id_municipio: $("#"+controlMunicipio).val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            $("#"+controlBarrio).empty();
            for (x in data.lista) {

               //selected = (dataDetalleLuminaria.id_barrio == data.lista[x].id_barrio)?"selected":"";

                $("#"+controlBarrio).append('<option value="' + data.lista[x].id_barrio + '"'+selected+'>' + data.lista[x].descripcion + '</option>');
            }
            $.unblockUI("");
        }
    });
}