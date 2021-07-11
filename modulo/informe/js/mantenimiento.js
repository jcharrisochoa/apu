$(function(){
    $("#limite").numeric({
        negative:false
    });

    $("#btn_generar_informe").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                tablaMantenimiento();
            }
            event.handld = true;
        }
        return false;
    });

    $("#btn_exportar_mantenimiento").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                exportarMantenimiento();
            }
            event.handld = true;
        }
        return false;
    });

});

function tablaMantenimiento(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url:"informe/ajax/tabla_mantenimiento.php",
        data:{
            periodo:$("#periodo").val(),
            mes:$("#mes").val(),
            municipio:$("#municipio").val(),
            limite:$("#limite").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
              $("#div_mantenimiento").html(data);
              $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}


function exportarMantenimiento(){
    
   var url = "periodo="+$("#periodo").val()+"&"+
                "mes="+$("#mes").val()+"&"+
                "municipio="+$("#municipio").val()+"&"+
                "limite="+$("#limite").val();

    window.open("informe/ajax/exportar_mantenimiento.php?"+url);
}