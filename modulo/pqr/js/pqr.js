var tablePQR = "";
var dataDetallePQR = "";
var dataSet = [];
var accion = "";

$(function(){
    $("#txt_identificacion").numeric();

    $("#txt_identificacion").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               buscarUsuarioServicio(); 
               event.handld = true;
           };
       }
       //return false;
   });
   
    //$(".make-switch").bootstrapSwitch('toggleRadioState');
    $('input.icheck-11').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-yellow'
    });

    $("#btn_buscar_usuario_servicio").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            buscarUsuarioServicio(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_nueva_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            nuevaPQR(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_buscar_punto_luminico").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if($("#slt_municipio").val()!=""){
                $("#frm-punto-luminico").modal("show"); 
                $("#frm-punto-luminico").css("z-index", "15001");
                $("#flt_municipio").val($("#slt_municipio").val()).change();
                tablePuntoLuminico.ajax.reload();
            }
            else
                toastr.warning("Seleccione el municipio", null, opts);

            event.handld = true;
        }
        return false;
    });

    $("#btn_guardar_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                guardarAccion(accion); 
            }
            event.handld = true;
        }
        return false;
    });
    
    //$("#frm-punto-luminico").on('hidden.bs.modal',function(){
       // $(this).modal('dispose');
        //$(this).off('hidden.bs.modal'); 
      //  $('body').removeClass('modal-open');
        //$('.modal-backdrop').remove();
        //$("#frm-pqr").modal("show"); 
    //});

    $("#frm-punto-luminico").off().on('hidden.bs.modal',function(){
        //$(".modal-backdrop").not(':first').remove();
        $("#frm-pqr").css("z-index", "15000"); //foco del modal inicial
        $('body').addClass('modal-open'); //render nuevamente el modal inicial
        //console.log(dataDetallePuntoLuminico);
        $("#id_luminaria").val(dataDetallePuntoLuminico.id_luminaria);
        $("#txt_poste").val(dataDetallePuntoLuminico.poste_no);
        $("#txt_luminaria").val(dataDetallePuntoLuminico.luminaria_no);
    });
    
});

function nuevaPQR(){
    $("#frm-titulo-pqr").html("Nueva PQR");
    $("#frm-pqr").modal("show"); 
    $("#tbl_lista_pqr tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetallePQR = "";
}

function buscarUsuarioServicio(){
    if($.trim($("#txt_identificacion").val())!=""){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"pqr/ajax/buscar_usuario_servicio.php",
            data:{
                identificacion:$("#txt_identificacion").val()
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.estado){
                    if(data.id_usuario_servicio == "")
                        toastr.warning(data.mensaje, null, opts);
                    else
                        toastr.success(data.mensaje, null, opts);

                    $("#id_usuario_servicio").val(data.id_usuario_servicio);
                    $("#txt_nombre").val(data.nombre);
                    $("#txt_direccion").val(data.direccion);
                    $("#txt_telefono").val(data.telefono);
                    $("#txt_email").val(data.email);
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
    else{
        //abrir ventana de todos los usuario
    }
}

function guardarAccion(accion){
    alert("guardar "+accion);
}