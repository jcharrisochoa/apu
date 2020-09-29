var accion = "";

$(function(){

    $("#txt_identificacion").numeric({
        negative:false,
        decimal:false
    })

    $("#txt_buscar_empleado").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               listarEmpleado(0); 
               event.handld = true;
           };
       }
    });

    $("#txt_menu_disponible").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               listarPerfilDisponible(); 
               event.handld = true;
           };
       }
    });

    $("#txt_menu_asignado").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               listarPerfilAsignado(); 
               event.handld = true;
           };
       }
    });  

    $('input.icheck-11').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-yellow'
    });

    $("#chk_usuario").iCheck({
        checkboxClass: 'icheckbox_square-blue'
    }).on('ifChecked', function(){
        $("#txt_usuario").prop("disabled",false);
        $("#txt_clave").prop("disabled",false);
        $("#txt_clave_2").prop("disabled",false);
        $("#txt_usuario").addClass("requerido");
        $("#txt_clave").addClass("requerido");
        $("#txt_clave_2").addClass("requerido");
      }).on('ifUnchecked',function(){
        $("#txt_usuario").prop("disabled",true);
        $("#txt_clave").prop("disabled",true);
        $("#txt_clave_2").prop("disabled",true);
        $("#txt_usuario").removeClass("requerido");
        $("#txt_clave").removeClass("requerido");
        $("#txt_clave_2").removeClass("requerido");
        $("#txt_usuario").val("");
        $("#txt_clave").val("");
        $("#txt_clave_2").val("");
      });

    $("#btn_retirar_perfil").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            accionPerfil("retirar"); 
            event.handld = true;
        }
        return false;
    });

    $("#btn_agregar_perfil").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            accionPerfil("agregar"); 
            event.handld = true;
        }
        return false;
    });

    $("#btn_nuevo_empleado").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            nuevoEmpleado(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_guardar_frm").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                if(!$("#chk_usuario").is(":checked") && !$("#chk_empleado").is(":checked")){
                    toastr.warning("Marque si es Empleado y/o Usuario", null, opts);
                }
                else{
                    if($("#txt_clave").val() != $("#txt_clave_2").val()){
                        toastr.warning("Las claves digitadas no son iguales", null, opts);
                    }
                    else{
                        guardarAccion(accion);
                    }
                }
            }
            event.handld = true;
        }
        return false;
    });

    $("#frm-empleando").on('hidden.bs.modal',function(){ //accion cuando se cierra la ventana
        clearInput(".clear");
        $(".fileinput").fileinput('clear'); 
        $("#chk_usuario").iCheck('enable');
        $("#chk_usuario").iCheck('uncheck');
        $("#txt_clave").prop("disabled",true);
        $("#txt_clave_2").prop("disabled",true);
        listarEmpleado(0);
        accion="";
    });

    listarEmpleado(0);

});

function listarEmpleado(start){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/listar_tercero.php",
        data:{
            start:start,
            editar:$("#editar").val(),
            eliminar:$("#eliminar").val(),
            buscar:$("#txt_buscar_empleado").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
              $("#div_listado_tercero").html(data);
              $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function nuevoEmpleado(){
    $("#frm-titulo-empleado").html("Nuevo Empleado / Usuario");
    $("#frm-empleado").modal("show"); 
    clearInput(".clear");
    $(".fileinput").fileinput('clear'); 
    $("#chk_usuario").iCheck('enable');
    $("#chk_usuario").iCheck('uncheck');
    $("#txt_clave").prop("disabled",true);
    $("#txt_clave_2").prop("disabled",true);
    
}

function editarTercero(id_tercero){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/buscar_tercero.php",
        data:{
            id_tercero:id_tercero
        },
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.estado){
                accion = "editar";
                $("#frm-titulo-empleado").html("Editar Usuario");
                $("#id_tercero").val(data.id_tercero);
                $("#slt_tipo_identificacion").val(data.id_tipo_identificacion).change();
                $("#slt_municipio").val(data.id_municipio).change();
                $("#txt_nombre").val(data.nombre);
                $("#txt_apellido").val(data.apellido);
                $("#txt_identificacion").val(data.identificacion);
                $("#txt_direccion").val(data.direccion);
                $("#txt_telefono").val(data.telefono);
                $("#txt_email").val(data.email);
                $("#txt_usuario").val(data.usuario);
                $("#slt_estado").val(data.estado_tercero).change();
                $("#frm-empleado").modal("show"); 
                if(data.es_usuario=="S"){
                    $("#chk_usuario").iCheck('check');
                    $("#txt_usuario").prop("disabled",true);
                    $("#chk_usuario").iCheck('disable');
                    $("#txt_clave").removeClass("requerido");
                    $("#txt_clave_2").removeClass("requerido");
                }
                else{
                    $("#chk_usuario").iCheck('uncheck');
                    $("#chk_usuario").iCheck('enable');
                }
                
                if(data.es_empleado=="S")
                    $("#chk_empleado").iCheck('check');
                else
                    $("#chk_empleado").iCheck('uncheck');
                
                if(data.ejecuta_labor_tecnica=="S")
                    $("#chk_tecnico").iCheck('check');
                else
                    $("#chk_tecnico").iCheck('uncheck');

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

function eliminarTercero(id_tercero,nombre){
    $("#modal-body-conf").html("¿ Está seguro(a) de eleminar Tercero <strong>"+nombre+"</strong>?");
    $("#modal-conf").modal("show");
    $("#btn_si").off("click").on("click",function(event){      
        if (event.handled !== true) {
            event.preventDefault();
            $("#modal-conf").modal("hide");
            $("#modal-body-conf").html("")
            $("#id_tercero").val(id_tercero);
            guardarAccion("eliminar");                     
            event.handld = true;
        }
        return false;                
    });   
}

function guardarAccion(accion){
    switch(accion){
        case "nuevo":
            var formPQR = new FormData(document.getElementById("form-empleado"));           
            formPQR.append("accion",accion);          
            break;
        case "editar":
            var formPQR = new FormData(document.getElementById("form-empleado"));
            formPQR.append("accion",accion);
            break;
        case "eliminar":
            var formPQR = new FormData();
            formPQR.append("id_tercero",$("#id_tercero").val());
            formPQR.append("accion",accion);
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        processData: false,
        contentType: false,
        url:"parametros/ajax/guardar_tercero.php",
        data:formPQR,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-empleado").modal("hide"); 
                clearInput(".clear"); 
                $(".fileinput").fileinput('clear');  //limpia el input type file             
                accion=""; 
                listarEmpleado(0);              
            }
            else{
                toastr.error(data.response.mensaje, null, opts);
            }            
            $.unblockUI("");            
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function verPerfil(id_tercero){  
    $("#id_tercero").val(id_tercero);
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/buscar_tercero.php",
        data:{
            id_tercero:id_tercero
        },
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.estado){
                $("#modal-titulo-detalle-tercero").html("Perfil Usuario "+data.nombre+" "+data.apellido);
                $("#modal-detalle-tercero").modal("show");
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


    listarPerfilAsignado();
    listarPerfilDisponible();
     
}

function listarPerfilAsignado(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/listar_perfil_asignado.php",
        data:{
            id_tercero:$("#id_tercero").val(),
            buscar:$("#txt_menu_asignado").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
              $("#lista-perfil-asignado").html(data);
              $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function listarPerfilDisponible(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url:"parametros/ajax/listar_perfil_disponible.php",
        data:{
            id_tercero:$("#id_tercero").val(),
            buscar:$("#txt_menu_disponible").val()
        },
        beforeSend:inicioEnvio,
        success:function(data){
              $("#lista-perfil-disponible").html(data);
              $.unblockUI(""); 
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function accionPerfil(accion){
    var objPerfil = {id_menu:"",crear:"",editar:"",eliminar:"",imprimir:""};
    var arrayPerfil = [];
    objPerfil.id_menu = "";
    objPerfil.crear ="";
    objPerfil.editar="";
    objPerfil.eliminar="";
    objPerfil.imprimir="";

    switch(accion){
        case "retirar":       
            $("#tbl_perfil_agregado tbody input[type=checkbox]").each(function(){
                if($(this).is(":checked")){
                    objPerfil.id_menu = $(this).val();
                    objPerfil.crear ="";
                    objPerfil.editar="";
                    objPerfil.eliminar="";
                    objPerfil.imprimir="";
                    arrayPerfil.push(objPerfil);
                    objPerfil = {id_menu:"",crear:"",editar:"",eliminar:"",imprimir:""};
                    
                }
            });
            break;
        case "agregar":
            $("#tbl_perfil_disponible tbody .chk input[type=checkbox]").each(function(){
                if($(this).is(":checked")){
                    objPerfil.id_menu = $(this).val();
                    objPerfil.crear     = ($("#tr_"+$(this).val()+" .alert-success input[type=checkbox]").is(":checked"))?"S":"N";
                    objPerfil.editar    = ($("#tr_"+$(this).val()+" .alert-warning input[type=checkbox]").is(":checked"))?"S":"N";
                    objPerfil.eliminar  = ($("#tr_"+$(this).val()+" .alert-danger input[type=checkbox]").is(":checked"))?"S":"N";
                    objPerfil.imprimir  = ($("#tr_"+$(this).val()+" .alert-info input[type=checkbox]").is(":checked"))?"S":"N"; 
                    arrayPerfil.push(objPerfil);
                    objPerfil = {id_menu:"",crear:"",editar:"",eliminar:"",imprimir:""};
                }
            });
            break;            
    }

    if(arrayPerfil.length>0){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"parametros/ajax/guardar_perfil.php",
            data:{
                id_tercero:$("#id_tercero").val(),
                menu_array:JSON.stringify(arrayPerfil),
                accion:accion
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.response.estado){
                    toastr.success(data.response.mensaje, null, opts);
                    listarPerfilAsignado();
                    listarPerfilDisponible();
                }
                else{
                    toastr.error(data.response.mensaje, null, opts);
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