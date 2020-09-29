$(function(){
    tablaResumenActividadPeriodo();

    $("#btn_generar_cuadro_resumen").click(function(){        
        if (event.handled !== true) {
            event.preventDefault();
            tablaResumenActividadPeriodo();
            event.handld = true;
        }
        return false;
    });

});
function tablaResumenActividadPeriodo(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url:"informe/ajax/tabla_actividad_periodo.php",
        data:{
            periodo:$("#periodo").val(),
            tipo_actividad:$("#tipo_actividad").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
              $("#div_actividad_periodo").html(data);
              $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}