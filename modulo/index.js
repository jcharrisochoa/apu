var tablePuntoLuminico = "";
var dataDetallePuntoLuminico = "";
var tableUsuarioServicio = "";
var detalleUsuarioServicio = "";

$(function(){
    initTablePuntoLuminico();
    initTableUsuarioServicio();

    $("#flt_municipio").change(function(){
        listarBarrio("flt_municipio","flt_barrio");
    });
    //totalPeriodoTipo();
});

function cargarModulo(url){
    $("#contenido").load(url);
    
    $("#flt_direccion").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               actualizarTablaPuntoLuminico();
               event.handld = true;
           };
       }
    });

    $("#flt_poste_no").keydown(function(event){
        if(event.keyCode == 13){
        if(event.handled !== true){
            event.preventDefault();
            actualizarTablaPuntoLuminico();
            event.handld = true;
        };
    }
    });

    $("#flt_luminaria_no").keydown(function(event){
        if(event.keyCode == 13){
        if(event.handled !== true){
            event.preventDefault();
            actualizarTablaPuntoLuminico();
            event.handld = true;
        };
    }
    });

    $("#flt_municipio").change(function() {
        listarBarrio("flt_municipio","flt_barrio");
    });

    $("#flt_identificacion").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               actualizarTablaUsuarioServicio();
               event.handld = true;
           };
       }
    });

    $("#flt_nombre").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               actualizarTablaUsuarioServicio();
               event.handld = true;
           };
       }
    });

    $("#flt_direccion_usuario").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               actualizarTablaUsuarioServicio();
               event.handld = true;
           };
       }
    });
   
    $("#btn_buscar_luminaria").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            actualizarTablaPuntoLuminico();
            event.handld = true;
        }
        return false;
    });

    $("#btn_filtrar_usuario_servicio").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            actualizarTablaUsuarioServicio();
            event.handld = true;
        }
        return false;
    });
}

function actualizarTablaPuntoLuminico(){
    tablePuntoLuminico.ajax.reload(function(){
        toastr.info("Se encontraron "+tablePuntoLuminico.page.info().recordsTotal+" registros", null, opts);
    });
}

function actualizarTablaUsuarioServicio(){
    tableUsuarioServicio.ajax.reload(function(){
        toastr.info("Se encontraron "+tableUsuarioServicio.page.info().recordsTotal+" registros", null, opts);
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
                { "data": "id_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_barrio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_luminaria", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
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

function initTableUsuarioServicio(){
    if (!$.fn.DataTable.isDataTable("#tbl_usuario_servicio")) {
        tableUsuarioServicio = $("#tbl_usuario_servicio").on("preXhr.dt", function(e, settings, data) {
            data.identificacion = $("#flt_identificacion").val(),
            data.nombre = $("#flt_nombre").val(),
            data.direccion = $("#flt_direccion_usuario").val()
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
                "url": "global/lista_global_usuario_servicio.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item", "searchable": false },
                { "data": "abreviatura",className: "alignCenter","searchable": false,"orderable": true,"name":"ti.abreviatura"},
                { "data": "identificacion",className: "alignCenter","searchable": false,"orderable": true,"name":"us.identificacion"},
                { "data": "nombre",className: "alignCenter","searchable": false,"orderable": true,"name":"us.nombre"},
                { "data": "direccion",className: "alignCenter","searchable": false,"orderable": true,"name":"us.direccion"},
                { "data": "telefono",className: "alignCenter","searchable": false,"orderable": true,"name":"us.telefono"},
                { "data": "id_usuario_servicio", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_identificacion", "bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            }
        });
        $("#tbl_usuario_servicio tbody").on("click", "tr", function() {
            $("#tbl_usuario_servicio tbody tr").removeClass("highlight");
            $(this).addClass("highlight");
            detalleUsuarioServicio = tableUsuarioServicio.row(this).data();
            $("#frm-usuario-servicio").modal("hide");
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

function totalPeriodoTipo(){
    $("#chart_actividad_tipo_periodo").html("");
    $("#chart_actividad_municipio").html("");
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "informe/ajax/actividad_tipo_periodo.php",
        beforeSend: inicioEnvio,
        success: function(data) {
            toastr.success("ok", null, opts);
            console.log(data);
            $.unblockUI("");
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });


    
    if(typeof Morris != 'undefined'){
        Morris.Bar({
            element: 'chart_actividad_tipo_periodo',
            axes: true,
            data: [
                {x: '2013 Q1', y: getRandomInt(1,10), z: getRandomInt(1,10)},
                {x: '2013 Q2', y: getRandomInt(1,10), z: getRandomInt(1,10), a: getRandomInt(1,10)},
                {x: '2013 Q3', y: getRandomInt(1,10), z: getRandomInt(1,10), a: getRandomInt(1,10)},
                {x: '2013 Q4', y: getRandomInt(1,10), z: getRandomInt(1,10), a: getRandomInt(1,10)}
            ],
            xkey: 'x',
            ykeys: ['y', 'z', 'a'],
            labels: ['Facebook', 'LinkedIn', 'Google+'],
            barColors: ['#b75cee', '#ee6e5c', '#6e5cee']
        });

        Morris.Bar({
            element: 'chart_actividad_municipio',
            axes: true,
            data: [
                {x: '2013 Q1', y: getRandomInt(1,10), z: getRandomInt(1,10)},
                {x: '2013 Q2', y: getRandomInt(1,10), z: getRandomInt(1,10), a: getRandomInt(1,10)},
                {x: '2013 Q3', y: getRandomInt(1,10), z: getRandomInt(1,10), a: getRandomInt(1,10)},
                {x: '2013 Q4', y: getRandomInt(1,10), z: getRandomInt(1,10), a: getRandomInt(1,10)}
            ],
            xkey: 'x',
            ykeys: ['y', 'z', 'a'],
            labels: ['Facebook', 'LinkedIn', 'Google+'],
            barColors: ['#bec825', '#6dc825', '#25c880']
        });

        // Line Chart
			var day_data = [
				{"elapsed": "I", "value": 34},
				{"elapsed": "II", "value": 24},
				{"elapsed": "III", "value": 3},
				{"elapsed": "IV", "value": 12},
				{"elapsed": "V", "value": 13},
				{"elapsed": "VI", "value": 22},
				{"elapsed": "VII", "value": 5},
				{"elapsed": "VIII", "value": 26},
				{"elapsed": "IX", "value": 12},
				{"elapsed": "X", "value": 19}
			];
			
			Morris.Line({
				element: 'chart_actividad_periodo_actual',
				data: day_data,
				xkey: 'elapsed',
				ykeys: ['value'],
				labels: ['value'],
				parseTime: false,
				lineColors: ['#242d3c']
			});
    }
}

function getRandomInt(min, max) 
{
	return Math.floor(Math.random() * (max - min + 1)) + min;
}