var propiedades = {
    message: "",
    css: {
        padding: 0,
        margin: 0,
        top: "40%",
        left: "45%",
        textAlign: "center",
        color: "#000",
        cursor: "not-allowed",
        width: "150px",
        height: "150px",
        opacity: .5
    },
    baseZ: "1040"
};
var tableLuminaria = "";
var tableActividad = "";
var dataSet = [];

$(function() {
    $("#municipio").select2().change(function() {
        listarBarrio();
    });
    $("#barrio").select2();
    $("#tipo").select2();

    $("#btn_buscar_luminaria").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            tableLuminaria.ajax.reload();
            event.handld = true;
        }
        return false;
    });


    InitTableLuminaria();
    InitTableActividad();
});

function InitTableLuminaria() {
    if (!$.fn.DataTable.isDataTable("#tbl_luminaria")) {
        tableLuminaria = "";
        tableLuminaria = $("#tbl_luminaria").on("preXhr.dt", function(e, settings, data) {
            data.poste_luminaria = $("#poste_luminaria").val(),
                data.municipio = $("#municipio").val(),
                data.barrio = $("#barrio").val(),
                data.fechaini = $("#fch_instalacion_ini").val(),
                data.fechafin = $("#fch_instalacion_fin").val(),
                data.direccion = $("#direccion").val(),
                data.tipo = $("#tipo").val()
        }).DataTable({ ///todo por una D jeje
            "aLengthMenu": [
                [15, 30, 50, 70, 100],
                [15, 30, 50, 70, 100]
            ],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": false,
            "ajax": {
                "url": "ajax/listar_luminaria.php",
                "type": "POST"
            },
            "columns": [

                { "data": "item", "searchable": false },
                { "data": "municipio", "searchable": false, "orderable": false },
                { "data": "poste_no", "searchable": false, "orderable": false },
                { "data": "luminaria_no", className: "alignCenter" },
                { "data": "tipo", className: "alignLeft" },
                { "data": "direccion", "searchable": false },
                { "data": "latitud", className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "longitud", className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "fch_instalacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "fch_registro", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }

            ],
            "order": [
                [2, "DESC"],
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id_luminaria;
                $(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_luminaria tbody").on("click", "tr", function() {
        $("#tbl_luminaria tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        var data = tableLuminaria.row(this).data();
        verDetalle(data);
    });
}

function verDetalle(dataDet) {
    if (dataDet.length == 0) {
        $("poste_no").html("");
        $("#modal-body-orden").html("Seleccione la luminaria a visualizar");
        $("#modal-detalle-luminaria").modal("show");
    } else {
        $("#poste_no").html("Poste " + dataDet.poste_no);
        $("#td_poste_no").html(dataDet.poste_no);
        $("#td_luminaria_no").html(dataDet.luminaria_no);
        $("#td_tipo").html(dataDet.tipo);
        $("#td_municipio").html(dataDet.municipio);
        $("#td_barrio").html(dataDet.barrio);
        $("#td_direccion").html(dataDet.direccion);
        $("#td_latitud").html(dataDet.latitud);
        $("#td_longitud").html(dataDet.longitud);
        $("#td_fch_instalacion").html(dataDet.fch_instalacion);
        $("#td_fch_registro").html(dataDet.fch_registro);
        $("#td_usuario").html(dataDet.usuario);
        $("#td_estado").html(dataDet.estado);
        $("#td_proveedor").html(dataDet.proveedor);
        $.ajax({
            async: true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url: "ajax/listar_actividad.php",
            data: {
                id_luminaria: dataDet.id_luminaria
            },
            beforeSend: inicioEnvio,
            success: function(dataActividad) {
                console.log(dataActividad);
                $.unblockUI("");
                if (dataActividad.n == 1) {
                    //$("#modal-body-orden").html(data.mensaje);
                    $("#modal-detalle-luminaria").modal("show");
                } else {
                    $("#modal-detalle-luminaria").modal("show");
                }
            }
        });
    }
}

function listarBarrio() {
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "ajax/listar_barrio.php",
        data: {
            id_municipio: $("#municipio").val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            console.log(data);
            $("#barrio").empty();
            for (x in data.lista) {
                $("#barrio").append('<option value="' + data.lista[x].id_barrio + '">' + data.lista[x].descripcion + '</option>');
            }
            $.unblockUI("");
        }
    });
}

function InitTableActividad() {
    tableActividad = "";
    tableActividad = $("#tbl_actividad_luminaria").DataTable({
        "aLengthMenu": [
            [5, 10, 15, 20],
            [5, 10, 15, 20]
        ],
        "bStateSave": false,
        "processing": true,
        "responsive": true,
        "searching": false,
        data: dataSet,
        "columns": [
            { "data": "item", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "codigo", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "tipo", className: "alignRight", "searchable": false, "orderable": false },
            { "data": "descripcion", "searchable": false, "orderable": false },
            { "data": "direccion", "searchable": false, "orderable": false },
            { "data": "fch_reclamo", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "fch_ejecucion", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "latitud", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "longitud", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "tecnico", className: "alignCenter", "searchable": false, "orderable": false }

        ],
        language: {
            url: "../../../../libreria/DataTableSp.json"
        }
    });
}