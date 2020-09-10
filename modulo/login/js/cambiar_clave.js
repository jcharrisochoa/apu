$(function(){
    $("#btn_guardar").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            cambiarClave();    
            event.handld = true;
        }
        return false;
    });

});

function cambiarClave(){
    if(ValidarDatos(".requerido")){
        if($("#clave1").val() != $("#clave2").val()){
            toastr.error("Las claves no son iguales", null, opts);
        }
        else{
            $.ajax({
                async:true,
                type: "POST",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded",
                url:"login/ajax/cambiar_clave.php",
                data:{
                    clave:$("#clave1").val()
                },
                beforeSend:inicioEnvio,
                success:function(data){
                    if(data.estado){
                        toastr.success(data.mensaje, null, opts);
                        clearInput(".clear"); 
                    }
                    else{
                        toastr.error(data.mensaje, null, opts);
                    }            
                    $.unblockUI("");            
                },
                error:function(){
                    toastr.error("Error General", null, opts);
                    $.unblockUI(""); 
                }
            });
        }
    }
}