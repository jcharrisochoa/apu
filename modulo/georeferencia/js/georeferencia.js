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
    $("#mapa").height($(window).height()-200).width($(window).width()-20); 
    $("#municipio").select2().change(function() {
        listarBarrio();
    });
    $("#barrio").select2();
    $("#tipo").select2();

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
        url: "ajax/listar_barrio.php",
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
    removeMarker();
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "ajax/buscar_luminaria.php",
        data: {
            municipio: $("#municipio").val(),
            barrio: $("#barrio").val(),
            tipo :$("#tipo").val(),
            direccion: $("#direccion").val(),
            poste_luminaria : $("#poste_luminaria").val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {             
            for (x in data.puntos) {                
                var marker = new L.marker([data.puntos[x].longitud,data.puntos[x].latitud]).on('click', function(e) {
                    console.log(e);
                }); //.addTo(apmap);
                arrayMarker.push(marker);
                apmap.addLayer(arrayMarker[x]);
                marker.bindPopup(
                    "<b>Poste:</b>"+data.puntos[x].poste_no+
                    "<br />"+
                    "<b>Luminaria:</b>"+data.puntos[x].luminaria_no+
                    "<br />"+
                    "<b>Tipo:</b>"+data.puntos[x].tipo+
                    "<br />"+
                    "<b>Municipio:</b>"+data.puntos[x].municipio+
                    "<br />"+
                    "<b>Barrio:</b>"+data.puntos[x].barrio+
                    "<br />"+
                    "<b>Direccion:</b>"+data.puntos[x].direccion+
                    "<br />"+
                    "<b>Instalacion:</b>"+data.puntos[x].fch_instalacion+
                    "<br />"+
                    "<b>Estado:</b>"+data.puntos[x].estado

                ).openPopup();
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