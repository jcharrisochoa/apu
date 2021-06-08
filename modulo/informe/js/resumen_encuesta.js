$(function(){
   
    $("#btn_generar_informe").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            cargarDatosGrafica();
            event.handld = true;
        }
        return false;
    });

    cargarDatosGrafica();
});

function cargarDatosGrafica(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"informe/ajax/grafica_encuesta.php",
        data:{
            municipio:$("#municipio").val(),
            periodo:$("#periodo").val(),
            mes:$("#mes").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
            //console.log(data.response.calidad.data);

            var pie = new Array();
            var i=0;
            var tooltipCalidad = new Object();
            $("#tbl_calidad tbody").empty();
            data.response.calidad.data.forEach(function(idx){
                pie.push(idx.porcentaje);
                tooltipCalidad[i] = idx.descripcion;
                i++;
                $("#tbl_calidad tbody").append("<tr><td>"+i+"</td><td>"+idx.descripcion+"</td><td>"+idx.cantidad+"</td><td>"+idx.porcentaje+"%</td></tr>");
            });
            $(".pie-large-calidad").sparkline(pie, {
                type: 'pie',
                width: '150px ',
                height: '150px',
                sliceColors: ['#81c784', '#4caf50','#388e3c','#1b5e20'],
                borderWidth: 7,
                borderColor: '#f5f5f5',
                tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.2}}%)',
                tooltipValueLookups: {
                    names: tooltipCalidad
                }
            });

            //console.log(data.response.tiempo.data);
            pie = new Array();
            var i=0;
            var tooltipTiempo = new Object();
            $("#tbl_tiempo tbody").empty();
            data.response.tiempo.data.forEach(function(idx){
                pie.push(idx.porcentaje);
                tooltipTiempo[i] = idx.descripcion;
                i++;
                $("#tbl_tiempo tbody").append("<tr><td>"+i+"</td><td>"+idx.descripcion+"</td><td>"+idx.cantidad+"</td><td>"+idx.porcentaje+"%</td></tr>");
            });
            $(".pie-large-tiempo").sparkline(pie, {
                type: 'pie',
                width: '150px ',
                height: '150px',
                sliceColors: ['#b388ff', '#7c4dff','#651fff','#6200ea'],
                borderWidth: 7,
                borderColor: '#f5f5f5',
                tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.2}}%)',
                tooltipValueLookups: {
                    names: tooltipTiempo
                }
            });

            //console.log(data.response.atencion.data);
            pie = new Array();
            var i=0;
            var tooltipAtencion = new Object();
            $("#tbl_atencion tbody").empty();
            data.response.atencion.data.forEach(function(idx){
                pie.push(idx.porcentaje);  
                tooltipAtencion[i] = idx.descripcion;
                i++;
                $("#tbl_atencion tbody").append("<tr><td>"+i+"</td><td>"+idx.descripcion+"</td><td>"+idx.cantidad+"</td><td>"+idx.porcentaje+"%</td></tr>");
            });
            $(".pie-large-atencion").sparkline(pie, {
                type: 'pie',
                width: '150px ',
                height: '150px',
                sliceColors: ['#ffd180', '#ffab40','#ff9100','#ff6d00'],
                borderWidth: 7,
                borderColor: '#f5f5f5',
                tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.2}}%)',
                tooltipValueLookups: {
                    names: tooltipAtencion
                }
            });

            $.unblockUI(""); 
            tablaResumenEncuesta();
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function tablaResumenEncuesta(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url:"informe/ajax/tabla_encuesta_periodo.php",
        data:{
            periodo:$("#periodo").val(),
            mes:$("#mes").val(),
            municipio:$("#municipio").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
              $("#div_encuesta_periodo").html(data);
              $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}