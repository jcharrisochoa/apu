var barChart;
var lineChart;
var arrayEnergia;
$(function(){
   
    $("#btn_generar_informe").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            cargarDatosConsumo();
            tablaResumen();
            event.handld = true;
        }
        return false;
    });

    cargarDatosConsumo();
    tablaResumen();
});

function cargarDatosConsumo(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"informe/ajax/historico_facturacion.php",
        data:{
            municipio:$("#municipio").val(),
            periodo:$("#periodo").val(),
            mes:$("#mes").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
            graficaHistorialConsumo(data);
            graficaRecaudoVsFacturacion(data);
            $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function graficaHistorialConsumo(data){
    /*
    (1) // para habilitar la tabla dinamica de consumo y valor energia
    $("#tbl_consumo tbody").empty();

    var i = 1;
    var rowConsumo = "<tr><td><strong>KW</strong></td>";
    var rowValorConsumo = "<tr><td><strong>Valor Energ&iacute;a</strong></td>";
    var sw = false;
    while(i<=12){
        sw = false;
        data.data.forEach(function(idx){
            if(idx.mes == i){
                rowConsumo = rowConsumo + "<td class='text-right'>"+idx.consumo+"</td>";
                rowValorConsumo = rowValorConsumo + "<td class='text-right'>$ "+idx.factura_energia+"</td>";
                sw = true;
            }       

        });
        if(!sw){
            rowConsumo = rowConsumo + "<td class='text-center'>-</td>";
            rowValorConsumo = rowValorConsumo + "<td class='text-right'>$ 0</td>";
        }
        i++;
    }
    rowConsumo = rowConsumo + "</tr>";
    rowValorConsumo = rowValorConsumo + "</tr>";

    $("#tbl_consumo tbody").append(rowConsumo+rowValorConsumo);*/
    

    //--
    var consumoCanvas = document.getElementById("grafica_historico_consumo");

    //--Recuperacion de los datos
    
    arrayEnergia     = new Array();
    var arrayMes         = new Array();
    var arrayConsumo = new Array();

    data.data.forEach(function(idx){
        var energia = {mes:"",valor:""};
        energia.mes = idx.nombre_mes
        energia.valor = idx.factura_energia
        arrayEnergia.push(energia);

        arrayConsumo.push(idx.consumo);
        arrayMes.push(idx.nombre_mes);
    });

    var dataCons = {
        labels: arrayMes,
        datasets: [{
          label: 'Kw',
          data: arrayConsumo,
          fill: false,
          borderColor: 'rgb( 255, 87, 51, 0.7)',
          tension: 0.1
        }]
      };


    var chartOptions = {
        legend: {display: true},
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Histórico de Consumo'
            },
            legend: {
                display: true,
                labels: {
                    color: 'rgb(73, 73, 73)'
                }
            },
            tooltip: {
                callbacks: {
                    footer:footer
                }
            }
        }
    };

    if(lineChart!=null){
        lineChart.destroy();
    }

    lineChart = new Chart(consumoCanvas,{
        type: 'line',
        data: dataCons,
        options: chartOptions
    });

}

function footer(value){
    arrayEnergia.forEach(function(idx){
        if(value[0].label == idx.mes){
           valor = idx.valor; 
        }
    });
    return '$' +valor;
    //return '$' + $.number(valor,2, ',', '.');
}

function graficaRecaudoVsFacturacion(data){
    //-identificacion del canvas
    var compareCanvas = document.getElementById("grafica_recaudo_vs_facturacion");

    //--Recuperacion de los datos
    arrayfacturacion = new Array();
    arrayrecaudo     = new Array();
    arraymes         = new Array();

    data.data.forEach(function(idx){
        arrayfacturacion.push(idx.facturacion_ap);
        arrayrecaudo.push(idx.recaudo_ap);
        arraymes.push(idx.nombre_mes);
    });


    var facturacionData = {
        label: 'Facturacion',
        data: arrayfacturacion,
        backgroundColor: 'rgba(29, 82, 171, 0.8)',
        borderWidth: 0,
       // yAxisID: "y-axis-facturacion"
    };
      
    var recaudoData = {
        label: 'Recaudo',
        data: arrayrecaudo,
        backgroundColor: 'rgba(104, 127, 165, 0.8)',
        borderWidth: 0,
        //yAxisID: "y-axis-recaudo"
    };
      
    var mesData = {
        labels: arraymes,
        datasets: [facturacionData, recaudoData]
    };
      
    var chartOptions = {
        legend: {display: true},
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Recaudo Vs Facturación IAP'
            },
            legend: {
                display: true,
                labels: {
                    color: 'rgb(73, 73, 73)'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(value, index, values) {
                        return '$' + $.number(value.raw,2, ',', '.');
                    }
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(value, index, values) {
                        return '$' + $.number(value,2, ',', '.');
                    }
                }
            }
        }
    };
      
    if(barChart!=null){
        barChart.destroy();
    }

    barChart = new Chart(compareCanvas, {
        type: 'bar',
        label: '# of Votes',
        data: mesData,
        options: chartOptions
    });

}

function tablaResumen(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"informe/ajax/resumen_liquidacion.php",
        data:{
            municipio:$("#municipio").val(),
            periodo:$("#periodo").val(),
            mes:$("#mes").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){  
            $("#tbl_resumen tbody").empty();
            var trow = "";
            var i = 1;
            var sumFap = 0;
            var sumRap = 0;
            var sumFts = 0;
            var sumRts = 0;

            data.data.forEach(function(idx){
                trow = trow + "<tr><td>"+i+"</td><td>"+
                idx.municipio+"</td><td class=\"text-right\">$ "+
                $.number(idx.facturacion_tsycc,2, ',', '.')+"</td><td class=\"text-right\">$ "+
                $.number(idx.recaudo_tsycc,2, ',', '.')+"</td><td class=\"text-right\">$ "+
                $.number(idx.facturacion_ap,2, ',', '.')+"</td><td class=\"text-right\">$ "+
                $.number(idx.recaudo_ap,2, ',', '.')+"</td></tr>";

                sumFap = sumFap + parseFloat(idx.facturacion_ap);
                sumRap = sumRap + parseFloat(idx.recaudo_ap);
                sumFts = sumFts + parseFloat(idx.facturacion_tsycc);
                sumRts = sumRts + parseFloat(idx.recaudo_tsycc);

                i++;
            });
            

            $("#tbl_resumen tbody").append(trow);
            var dFact = sumFts - sumFap;
            var dReca = sumRts - sumRap; 
            trow = "<tr><th colspan=\"2\" class=\"text-center\">TOTAL</th><th class=\"text-right\">$ "+
            $.number(sumFts,2, ',', '.')+"</th><th class=\"text-right\">$ "+
            $.number(sumRts,2, ',', '.')+"</th><th class=\"text-right\">$ "+
            $.number(sumFap,2, ',', '.')+"</th><th class=\"text-right\">$ "+
            $.number(sumRap,2, ',', '.')+"</th></tr>"+
            "<tr><td colspan=\"4\" class=\"text-right\"><strong>DIF FACTURACION</strong></td><td colspan=\"2\" class=\"text-right dfact \">$ "+dFact+"</td></tr>"+
            "<tr><td colspan=\"4\" class=\"text-right\"><strong>DIF RECAUDO</strong></td><td colspan=\"2\" class=\"text-right dreca\">$ "+dReca+"</td></tr>";
            
            $("#tbl_resumen tfoot").empty();
            $("#tbl_resumen tfoot").append(trow);

            if(dFact != 0)
                $(".dfact").addClass("danger");
            else
                $(".dfact").addClass("success");

            if(dReca != 0)
                $(".dreca").addClass("danger");
            else
                $(".dreca").addClass("success");

           
            
            
            $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}