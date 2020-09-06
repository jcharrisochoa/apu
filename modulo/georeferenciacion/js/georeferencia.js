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
var apmap;
var arrayMarker = new Array();

$(function(){
    $("#mapa").height($(window).height()-100).width($(window).width()-300); 

    $("#municipio").change(function() {
        listarBarrio();
    });

    $("#btn_buscar_luminaria").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos("requerido")){
                buscar();
            }
            event.handld = true;
        }
        return false;
    });
    apmap = L.map('mapa').setView([10.939345, -74.846531], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(apmap);
});


function listarBarrio() {
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "georeferenciacion/ajax/listar_barrio.php",
        data: {
            id_municipio: $("#municipio").val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            $("#barrio").empty();
            for (x in data.lista) {
                $("#barrio").append('<option value="' + data.lista[x].id_barrio + '">' + data.lista[x].descripcion + '</option>');
            }
            $.unblockUI("");
        }
    });
}

function buscar(){

    var iconConfig = L.icon({
        iconUrl: "../../libreria/leaflet/images/marker-icon.png",       
        iconSize: [25, 41],
        iconAnchor: [22, 94],
        popupAnchor: [-3, -76]        
    });

    removeMarker();
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "georeferenciacion/ajax/buscar_luminaria.php",
        data: {
            municipio: $("#municipio").val(),
            barrio: $("#barrio").val(),
            tipo :$("#tipo").val(),
            direccion: $("#direccion").val(),
            luminaria_no:$("#luminaria_no").val(),
            poste_no: $("#poste_no").val(),
            fechaini: $("#fch_instalacion_ini").val(),
            fechafin: $("#fch_instalacion_fin").val()

        },
        beforeSend: inicioEnvio,
        success: function(data) {             
            for (x in data.puntos) {                
                var marker = new L.marker([data.puntos[x].longitud,data.puntos[x].latitud]).on('click', function(e) {
                    //console.log(this.luminaria);
                    verDetalle(this.luminaria);
                });
                marker.luminaria = {
                    id_luminaria :data.puntos[x].id_luminaria,
                    poste_no:data.puntos[x].poste_no,
                    luminaria_no:data.puntos[x].luminaria_no,
                    tipo:data.puntos[x].tipo,
                    municipio:data.puntos[x].municipio,
                    barrio:data.puntos[x].barrio,
                    direccion:data.puntos[x].direccion,
                    latitud:data.puntos[x].latitud,
                    longitud:data.puntos[x].longitud,
                    fch_instalacion:data.puntos[x].fch_instalacion,
                    fch_registro:data.puntos[x].fch_registro,
                    usuario:data.puntos[x].usuario,
                    estado:data.puntos[x].estado
                };
                arrayMarker.push(marker);
                apmap.addLayer(arrayMarker[x]);
            }
            $.unblockUI("");
        }
    });
}
function removeMarker(){
    for(i=0;i<arrayMarker.length;i++) {
        apmap.removeLayer(arrayMarker[i]);
    } 
    arrayMarker=[];
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
        if ($.fn.DataTable.isDataTable("#tbl_actividad_luminaria")) {
            tableActividad.destroy();
        }
        InitTableActividad(dataDet);
        $("#modal-detalle-luminaria").modal("show");
    }
}
function InitTableActividad(dataDet) {
    //tableActividad = "";
    tableActividad = $("#tbl_actividad_luminaria").on("preXhr.dt", function(e, settings, data) {
        data.id_luminaria = dataDet.id_luminaria
    }).DataTable({
        "aLengthMenu": [
            [3, 5, 10],
            [3, 5, 10]
        ],
        "bStateSave": false,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "bAutoWidth": true,
        "searching": false,
        "ajax": {
            "url": "luminaria/ajax/listar_actividad.php",
            "type": "POST"
        },
        "columns": [
            { "data": "item", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "codigo","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "tipo", className: "alignRight", "searchable": false, "orderable": false },
            { "data": "descripcion", "searchable": false, "orderable": false },
            { "data": "direccion", "searchable": false, "orderable": false },
            { "data": "fch_reclamo", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "fch_ejecucion", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "tecnico", className: "alignCenter", "searchable": false, "orderable": false },
            { "data": "estado_actividad", className: "alignCenter", "searchable": false, "orderable": false },

        ],
        language: {
            url: "../../../../libreria/DataTableSp.json"
        }
    });
}
