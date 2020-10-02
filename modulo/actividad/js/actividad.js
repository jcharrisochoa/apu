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
var tableActividad = "";
var dataSet = [];
var accion = "";
var tableServicioActividad = "";
var item    = 0;
var detalleActividad = "";
var tablePQR = "";
var detallePQR = "";

$(function() {

    $("#txt_cantidad").numeric({
        negative:false
    });

    $("#txt_codigo").numeric({
        negative:false
    });

    $("#municipio").change(function() {
        listarBarrioActividad("municipio","barrio");
    });

    $("#slt_municipio").change(function() {
        listarBarrioActividad("slt_municipio","slt_barrio");
    });
     
    $("#btn_guardar_frm").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                guardarAccion(accion);
            }   
            event.handld = true;
        }
        return false;
    });
    
    $("#btn_buscar_actividad").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            var json = tableActividad.ajax.reload(function(){
                //console.log("f:"+tableActividad.page.info().recordsTotal)
                detalleActividad = "";
                $("#modal-text-global").html("Se encontraron "+tableActividad.page.info().recordsTotal+" registros");
                $("#modal-mensaje-global").modal("show");
            }
            );
            event.handld = true;
        }
        return false;
    });

    $("#btn_nueva_actividad").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            nuevaActividad(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_editar_actividad").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            editarActividad(detalleActividad); 
            accion = "editar";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_actividad").click(function(){       
        if (event.handled !== true) {
            event.preventDefault();
            eliminarActividad(detalleActividad);
            accion = "eliminar";  
            event.handld = true;
        }
        return false;
    });

    $("#btn_cerrar_detalle_actividad").click(function(){
        $("actividad").html("");
        $("#modal-detalle-actividad").modal("hide");
    });

    $("#btn_agregar").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            agregarArticulo(); 
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            eliminarArticulo();    
            event.handld = true;
        }
        return false;
    }); 

    $("#btn_cancelar").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            clearInput(".clear-articulo"); 
            $("#tbl_articulo_actividad tbody tr").removeClass('highlight');    
            event.handld = true;
        }
        return false;
    }); 
    
    $("#btn_detalle_actividad").click(function(){
        if (event.handled !== true) {
            event.preventDefault();
            verDetalle(detalleActividad);    
            event.handld = true;
        }
        return false;
        
    });

    $("#btn_exportar_actividad").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            exportarActividad(); 
            event.handld = true;
        }
        return false;
    });

    $("#txt_luminaria").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               if($("#slt_municipio").val()!=""){
                    buscarLuminaria();
               }
               else{
                    toastr.warning("Seleccione el municipio", null, opts);
               } 
               event.handld = true;
           };
       }
       //return false;
    });

    $("#txt_pqr").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               if($("#slt_municipio").val()!=""){
                    buscarPQR(); 
               }
               else{
                    toastr.warning("Seleccione el municipio", null, opts);  
               }
               event.handld = true;
           };
       }
       //return false;
    });
    
    $("#txt_codigo").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               buscarArticulo(); 
               event.handld = true;
           };
       }
       //return false;
    });

    $("#txt_cantidad").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               agregarArticulo(); 
               event.handld = true;
           };
       }
       //return false;
    });

    $("#btn_buscar_luminaria").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if($("#slt_municipio").val()!=""){
                if($("#txt_luminaria").val()!=""){
                    buscarLuminaria(); 
                }
                else{
                    $("#frm-punto-luminico").modal("show"); 
                    $("#frm-punto-luminico").css("z-index", "15001");
                    $("#flt_municipio").val($("#slt_municipio").val()).change();
                    tablePuntoLuminico.ajax.reload();
                    dataDetallePuntoLuminico = "";
                }
            }
            else
                toastr.warning("Seleccione el municipio", null, opts);

            event.handld = true;
        }
        return false;
    });

    $("#btn_cancelar_punto_luminico").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            event.handld = true;
            $("#id_luminaria").val("");
            $("#txt_poste").val("");
            $("#txt_luminaria").val("");
            $("#txt_direccion").val("");
            $("#slt_barrio").val("");
            $("#slt_tipo_luminaria").val("");
        }
        return false;
    });

    $("#frm-punto-luminico").off().on('hidden.bs.modal',function(){
        $("#frm-actividad").css("z-index", "15000"); //foco del modal inicial
        $('body').addClass('modal-open'); 
        //console.log(dataDetallePuntoLuminico);
        $("#id_luminaria").val(dataDetallePuntoLuminico.id_luminaria);
        $("#txt_poste").val(dataDetallePuntoLuminico.poste_no);
        $("#txt_luminaria").val(dataDetallePuntoLuminico.luminaria_no);
        $("#txt_direccion").val(dataDetallePuntoLuminico.direccion);
        $("#slt_barrio").val(dataDetallePuntoLuminico.id_barrio).change();
        $("#slt_tipo_luminaria").val(dataDetallePuntoLuminico.id_tipo_luminaria).change();
    });

    $("#btn_buscar_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if($("#slt_municipio").val()!=""){
                if($("#txt_pqr").val()!=""){
                    buscarPQR(); 
                }
                else{
                    $("#frm-pqr").modal("show"); 
                    $("#frm-pqr").css("z-index", "15001");
                    tablePQR.ajax.reload();
                    detallePQR = "";
                }
            }
            else
                toastr.warning("Seleccione el municipio", null, opts);

            event.handld = true;
        }
        return false;
    });

    $("#btn_cancelar_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            event.handld = true;
            $("#id_pqr").val("");
            $("#txt_pqr").val("");
            $("#txt_fch_pqr").val("");
            $("#txt_tipo_reporte").val("");
            $("#txt_tipo_pqr").val("");
            $("#txt_luminaria").val("");
            $("#slt_tipo_luminaria").val("").change();
            $("#id_luminaria").val("");
            $("#txt_poste").val("");
            $("#txt_direccion").val("");
            $("#slt_barrio").val("").change();
    
        }
        return false;
    });
    
    $("#frm-pqr").off().on('hidden.bs.modal',function(){
        $("#frm-actividad").css("z-index", "15000"); //foco del modal inicial
        $('body').addClass('modal-open'); 
        $("#id_pqr").val(detallePQR.id_pqr);
        $("#txt_pqr").val(detallePQR.id_pqr);
        $("#txt_fch_pqr").val(detallePQR.fecha_reporte);
        $("#txt_tipo_reporte").val(detallePQR.tipo_reporte);
        $("#txt_tipo_pqr").val(detallePQR.tipo_pqr);

        $("#txt_luminaria").val(detallePQR.luminaria_no);
        $("#slt_tipo_luminaria").val(detallePQR.id_tipo_luminaria).change();
        $("#id_luminaria").val(detallePQR.id_luminaria);
        $("#txt_poste").val(detallePQR.poste_no);
        $("#txt_direccion").val(detallePQR.direccion_luminaria);
        $("#slt_barrio").val(detallePQR.id_barrio_luminaria).change();

        detallePQR = "";
    });

    $("#btn_filtrar_pqr").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();            
            tablePQR.ajax.reload();
            detallePQR = "";
            event.handld = true;
        }
        return false;
    });
    
    $("#flt_radicado").numeric({
        negative:false,
        decimal:false
    });

    $("#flt_identificacion_pqr").numeric({
        negative:false,
        decimal:false
    });

    $("#flt_radicado").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               tablePQR.ajax.reload();
                detallePQR = "";
               event.handld = true;
           };
       }
       //return false;
    });

    $("#flt_direccion_usuario_pqr").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               tablePQR.ajax.reload();
                detallePQR = "";
               event.handld = true;
           };
       }
       //return false;
    });

    $("#flt_nombre").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               tablePQR.ajax.reload();
                detallePQR = ""; 
               event.handld = true;
           };
       }
       //return false;
    });

    $("#flt_identificacion_pqr").keydown(function(event){
        if(event.keyCode == 13){
           if(event.handled !== true){
               event.preventDefault();
               tablePQR.ajax.reload();
                detallePQR = ""; 
               event.handld = true;
           };
       }
       //return false;
    });

    InitTableActividad();
    initTableArticulo();
    InitTablePQR();
});

function listarBarrioActividad(controlMunicipio,controlBarrio) {
    var selected = "";
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "actividad/ajax/listar_barrio.php",
        data: {
            id_municipio: $("#"+controlMunicipio).val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            //console.log(data);
            $("#"+controlBarrio).empty();
            for (x in data.lista) {
                selected = (detalleActividad.id_barrio == data.lista[x].id_barrio)?"selected":"";
                $("#"+controlBarrio).append('<option value="' + data.lista[x].id_barrio + '" '+selected+'>' + data.lista[x].descripcion + '</option>');
            }
            $.unblockUI("");
        }
    });
}

function InitTableActividad() {
    if (!$.fn.DataTable.isDataTable("#tbl_actividad")) {
        tableActividad = "";
        tableActividad = $("#tbl_actividad").on("preXhr.dt", function(e, settings, data) {
            data.luminaria_no = $("#luminaria_no").val(),
            data.poste_no = $("#poste_no").val(),
                data.municipio = $("#municipio").val(),
                data.barrio = $("#barrio").val(),
                data.tipo_actividad = $("#tipo_actividad").val(),
                data.fechaini = $("#fch_actividad_ini").val(),
                data.fechafin = $("#fch_actividad_fin").val()
                /*data.direccion = $("#direccion").val(),
                */
        }).DataTable({ ///todo por una D jeje
            "aLengthMenu": [
                [15, 30, 50, 70, 100],
                [15, 30, 50, 70, 100]
            ],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": false,
            "ajax": {
                "url": "actividad/ajax/listar_actividad.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item",className: "alignCenter","searchable": false,"orderable": false},
                { "data": "municipio",className: "alignCenter","searchable": false,"orderable": true,"name":"m.descripcion"},
                { "data": "id_actividad",className: "alignCenter","searchable": false,"orderable": true,"name":"ac.id_actividad"},
                { "data": "tipo",className: "alignCenter","searchable": false,"orderable": true,"name":"ta.descripcion"},
                { "data": "barrio",className: "alignLeft","searchable": false,"orderable": true,"name":"4"},
                { "data": "direccion",className: "alignLeft","searchable": false,"orderable": true,"name":"ac.direccion"},
                { "data": "fch_actividad",className: "alignCenter","searchable": false,"orderable": true,"name":"ac.fch_actividad"},
                { "data": "poste_no","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "luminaria_no","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "tecnico","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "fch_reporte","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "tipo_reporte","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "estado_actividad","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "tipo_luminaria","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "observacion","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_pqr","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "tipo_pqr","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "vehiculo","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_vehiculo","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_estado_actividad","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tercero","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_actividad","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_tipo_luminaria","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_luminaria","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_barrio","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false },
                { "data": "id_municipio","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
            ],
            "order": [
                [1,"DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            }
        });
    }
    $("#tbl_actividad tbody").on("click", "tr", function() {
        $("#tbl_actividad tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        detalleActividad = tableActividad.row(this).data();
        $("#id_actividad").val(detalleActividad.id_actividad);
    });
}

function verDetalle(dataDet) {
    if (dataDet.length == 0) {
        $("actividad").html("");
        $("#modal-body-orden").html("Seleccione la actividad a visualizar");
        $("#modal-detalle-actividad").modal("show");
    } else {
        $("#actividad").html("Actividad No " + dataDet.id_actividad);
        $("#td_poste_no").html(dataDet.poste_no);
        $("#td_luminaria_no").html(dataDet.luminaria_no);
        $("#td_tipo").html(dataDet.tipo);
        $("#td_municipio").html(dataDet.municipio);
        $("#td_barrio").html(dataDet.barrio);
        $("#td_direccion").html(dataDet.direccion);
        $("#td_tipo_reporte").html(dataDet.tipo_reporte);
        $("#td_longitud").html(dataDet.longitud);
        $("#td_fch_instalacion").html(dataDet.fch_actividad);
        $("#td_fch_reporte").html(dataDet.fch_reporte);
        $("#td_usuario").html(dataDet.tecnico);
        $("#td_estado").html(dataDet.estado_actividad);
        $("#td_observacion").html(dataDet.observacion);
        $("#td_tipo_luminaria").html(dataDet.tipo_luminaria);
        $("#td_pqr").html(dataDet.id_pqr);
        $("#td_tipo_pqr").html(dataDet.tipo_pqr);
        $("#td_vehiculo").html(dataDet.vehiculo);
        if ($.fn.DataTable.isDataTable("#tbl_servicio")) {
            tableServicioActividad.destroy();
        }
        InitTableServicio(dataDet);
        $("#modal-detalle-actividad").modal("show");
    }
}

function InitTableServicio(dataDet) {
    tableServicioActividad = $("#tbl_servicio").on("preXhr.dt", function(e, settings, data) {
        data.id_actividad = dataDet.id_actividad
    }).DataTable({
        "aLengthMenu": [
            [3, 5, 10],
            [3, 5, 10]
        ],
        "bStateSave": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "bAutoWidth": true,
        "searching": false,
        "ajax": {
            "url": "actividad/ajax/listar_servicio_actividad.php",
            "type": "POST"
        },
        "columns": [
            { "data": "item", className: "text-center", "searchable": false, "orderable": false,"width": "20%"  },
            { "data": "codigo", className: "text-center", "searchable": false, "orderable": false},
            { "data": "descripcion", className: "alignLeft", "searchable": false, "orderable": false },
            { "data": "cantidad", className: "text-right", "searchable": false, "orderable": false }

        ],
        language: {
            url: "../../../../libreria/DataTableSp.json"
        }
    });
}

function nuevaActividad(){
    $("#frm-titulo-actividad").html("Nueva Actividad");
    $("#frm-actividad").modal("show"); 
    $("#tbl_actividad tbody tr").removeClass("highlight");
    clearInput(".clear");
    //tableActividad = "";
}

function editarActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la actividad a editar", null, opts);
    } 
    else {
        $("#frm-titulo-actividad").html("Editar Actividad No "+dataDet.id_actividad);
        $("#slt_municipio").val(dataDet.id_municipio).change();
        //$("#slt_barrio").val(dataDet.id_barrio).change();
        $("#id_luminaria").val(dataDet.id_luminaria);
        $("#id_pqr").val(dataDet.id_pqr);
        $("#txt_pqr").val(dataDet.id_pqr);
        $("#txt_fch_pqr").val(dataDet.fch_reporte);        
        $("#txt_tipo_reporte").val(dataDet.tipo_reporte);
        $("#txt_tipo_pqr").val(dataDet.tipo_pqr);
        $("#slt_tipo_luminaria").val(dataDet.id_tipo_luminaria).change();
        $("#slt_tipo_actividad").val(dataDet.id_tipo_actividad).change();
        $("#txt_direccion").val(dataDet.direccion);
        $("#slt_tercero").val(dataDet.id_tercero).change();
        $("#txt_fch_ejecucion").val(dataDet.fch_actividad);
        $("#slt_vehiculo").val(dataDet.id_vehiculo);
        $("#txt_observacion").val(dataDet.observacion);
        $("#txt_poste").val(dataDet.poste_no);
        $("#txt_luminaria").val(dataDet.luminaria_no);
        $("#slt_estado_actividad").val(dataDet.id_estado_actividad);
        listarServicioActividad();
        $("#frm-actividad").modal("show"); 
    }
}

function buscarArticulo(){
    if($.trim($("#txt_codigo").val())!=""){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"global/buscar_articulo.php",
            data:{
                id_articulo:$("#txt_codigo").val()
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.estado){
                    if(data.id_articulo == ""){
                        toastr.warning(data.mensaje, null, opts);
                    }
                    else{
                        toastr.success(data.mensaje, null, opts);
                        $("#txt_cantidad").focus();
                    }
                    $("#txt_descripcion").val(data.descripcion);
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
        $("#txt_descripcion").val("");
        $("#id_articulo").val("");
    }
}

function agregarArticulo(){
    if(parseInt($("#txt_codigo").val())==0 ||  $("#txt_codigo").val()==""){
        toastr.warning("Digite el codigo del art&iacute;culo a ingresar", null, opts);
    }
    else{
        if($("#txt_descripcion").val()==""){
            toastr.warning("Digite la descripcion del art&iacute;culo a ingresar", null, opts);
        }
        else{
            if(parseFloat($("#txt_cantidad").val())==0 || $("#txt_cantidad").val()==""){
            /* $("#modal-body").html("Digite la cantidad del producto a agregar en la orden");
                $("#modal-msgbox").modal('show');*/
                toastr.warning("Digite la cantidad a ingresar", null, opts);
            }
            else{
                tableArticuloActividad.row.add({
                    "item":item+1,
                    "id_articulo":$("#txt_codigo").val(),
                    "descripcion":$("#txt_descripcion").val(),
                    "cantidad":$("#txt_cantidad").val()
                }).draw();
                item++;
                clearInput(".clear-articulo");
                $("#txt_codigo").focus();

               /* $("#tbl_articulo_actividad tbody").on("click","tr", function () {         
                    $("#tbl_articulo_actividad tbody tr").removeClass('highlight');
                    $(this).addClass('highlight');
                    if(tableArticuloActividad.row(this).length>0){
                        var data=tableArticuloActividad.row(this).data();
                        $("#item").val(data.item);
                        $("#txt_codigo").val(data.id_articulo);
                        $("#txt_descripcion").val(data.descripcion); 
                        $("#txt_cantidad").val(data.cantidad);      
                    }              
                });*/
            }
        }
    }
}

function eliminarArticulo(){
    if($("#tbl_articulo_actividad tbody tr").hasClass('highlight')){
        tableArticuloActividad.row('.highlight').remove().draw();
        clearInput(".clear-articulo");
        item--;
    }
    else{
         toastr.warning("Seleccione el art&iacute;culo a eliminar", null, opts);
    }
}

function initTableArticulo(){ 
    tableArticuloActividad="";
    tableArticuloActividad = $("#tbl_articulo_actividad").DataTable({  
                                "aLengthMenu": [
                                    [5, 10, 15, 20],
                                    [5, 10, 15, 20]
                                ],      
                                "bStateSave": true,
                                "bAutoWidth": true,
                                "processing": true,        
                                "responsive": true,
                                "searching": false,
                                data: dataSet,
                                "columns": [          
                                    {"data":"item",className: "text-center","searchable": false,"orderable": false,"width": "20%"},                                
                                    {"data":"id_articulo",className: "text-center","searchable": false,"orderable": false},
                                    {"data":"descripcion",className: "text-left","searchable": false,"orderable": false},
                                    {"data":"cantidad",className:"text-right"}
                                ],
                               
                                language: {
                                    url: "../../../../libreria/DataTableSp.json"
                                }
                            }); 
    $("#tbl_articulo_actividad tbody").on("click","tr", function () {         
        $("#tbl_articulo_actividad tbody tr").removeClass('highlight');
        $(this).addClass('highlight');
        if(tableArticuloActividad.row(this).length>0){
            var data=tableArticuloActividad.row(this).data();
            $("#item").val(data.item);
            $("#txt_codigo").val(data.id_articulo);
            $("#txt_descripcion").val(data.descripcion); 
            $("#txt_cantidad").val(data.cantidad);      
        }              
    });
}

function buscarLuminaria(){
    if($.trim($("#txt_luminaria").val())!="" && $("#slt_municipio").val()!=""){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"global/buscar_luminaria.php",
            data:{
                id_municipio:$("#slt_municipio").val(),
                luminaria_no:$("#txt_luminaria").val()
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.estado){
                    if(data.id_luminaria == ""){
                        toastr.warning(data.mensaje, null, opts);
                    }
                    else{
                        toastr.success(data.mensaje, null, opts);
                    }
                    $("#slt_tipo_luminaria").val(data.id_tipo_luminaria).change();
                    $("#id_luminaria").val(data.id_luminaria);
                    $("#txt_poste").val(data.poste_no);
                    $("#txt_direccion").val(data.direccion);
                    $("#slt_barrio").val(data.id_barrio).change();
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
        $("#slt_tipo_luminaria").val("").change();
        $("#id_luminaria").val("");
        $("#txt_poste_no").val("");
        $("#txt_direccion").val("");
        $("#slt_barrio").val("").change();
        $("#id_pqr").val("");
        $("#txt_fch_pqr").val("");
        $("#txt_tipo_pqr").val("");
        $("#txt_tipo_reporte").val("");
    }
}

function buscarPQR(){
    if($.trim($("#txt_pqr").val())!="" && $("#slt_municipio").val()!=""){
        $.ajax({
            async:true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url:"global/buscar_pqr.php",
            data:{
                id_municipio:$("#slt_municipio").val(),
                id_pqr:$("#txt_pqr").val()
            },
            beforeSend:inicioEnvio,
            success:function(data){
                if(data.estado){
                    if(data.id_pqr == ""){
                        toastr.warning(data.mensaje, null, opts);
                    }
                    else{
                        toastr.success(data.mensaje, null, opts);
                    }
                    $("#id_pqr").val(data.id_pqr);
                    $("#txt_fch_pqr").val(data.fch_pqr);
                    $("#txt_tipo_pqr").val(data.tipo_pqr);
                    $("#txt_tipo_reporte").val(data.tipo_reporte);
                    $("#txt_luminaria").val(data.luminaria_no);
                    $("#slt_tipo_luminaria").val(data.id_tipo_luminaria).change();
                    $("#id_luminaria").val(data.id_luminaria);
                    $("#txt_poste").val(data.poste_no);
                    $("#txt_direccion").val(data.direccion);
                    $("#slt_barrio").val(data.id_barrio).change();
                    
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
        $("#id_pqr").val("");
        $("#txt_fch_pqr").val("");
        $("#txt_tipo_pqr").val("");
        $("#txt_tipo_reporte").val("");
        $("#slt_tipo_luminaria").val("").change();
        $("#id_luminaria").val("");
        $("#txt_poste_no").val("");
        $("#txt_direccion").val("");
        $("#slt_barrio").val("").change();
    }
}

function guardarAccion(accion){
    switch(accion){
        case "nuevo":
            var variable = $("#form-actividad").serialize()+"&detalle="+JSON.stringify(tableArticuloActividad.rows().data().toArray())+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-actividad").serialize()+"&detalle="+JSON.stringify(tableArticuloActividad.rows().data().toArray())+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_actividad="+$("#id_actividad").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"actividad/ajax/guardar_accion.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                if(accion=="editar")
                    $("#frm-actividad").modal("hide"); 
                clearInput(".clear");
                clearInput(".clear-articulo");
                accion="";
                tableActividad.ajax.reload();
                tableArticuloActividad.clear().draw();
                detalleActividad = "";
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

function eliminarActividad(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la actividad a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar la actividad  No "+dataDet.id_actividad+" seleccionada ?");
        $("#modal-conf").modal("show");
        $("#btn_si").off("click").on("click",function(event){      
            if (event.handled !== true) {
                event.preventDefault();
                $("#modal-conf").modal("hide");
                $("#modal-body-conf").html("")
                guardarAccion("eliminar");                     
                event.handld = true;
            }
            return false;                
        });  
    }
}

function listarServicioActividad(){
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"actividad/ajax/listar_servicio_actividad.php",
        data:{
            id_actividad:detalleActividad.id_actividad,
            draw:""
        },
        beforeSend:inicioEnvio,
        success:function(data){
            tableArticuloActividad.clear().draw();
            $.each(data.data,function(i,det){
                item++;
                tableArticuloActividad.row.add({
                    "item":item,
                    "id_articulo":det['codigo'],
                    "descripcion":det['descripcion'],
                    "cantidad":det['cantidad']
                }).draw();
            });         
            $.unblockUI("");            
        },
        error:function(){
            toastr.error("Error General", null, opts);
            $.unblockUI(""); 
        }
    });
}

function exportarActividad(){
    
    var url = "luminaria_no="+$("#luminaria_no").val()+"&"+
                "poste_no="+$("#poste_no").val()+"&"+
                "municipio="+$("#municipio").val()+"&"+
                "barrio="+$("#barrio").val()+"&"+
                "tipo_actividad ="+$("#tipo_actividad").val()+"&"+
                "fechaini="+$("#fch_actividad_ini").val()+"&"+
                "fechafin="+$("#fch_actividad_fin").val();

    window.open("actividad/ajax/exportar_actividad.php?"+url);
}

function InitTablePQR() {
    if (!$.fn.DataTable.isDataTable("#tbl_pqr")) {
        tablePQR = $("#tbl_pqr").on("preXhr.dt", function(e, settings, data) {
            data.municipio = $("#slt_municipio").val(),
            data.id_pqr     = $("#flt_radicado").val(),
            data.direccion  = $("#flt_direccion_usuario_pqr").val(),
            data.nombre     = $("#flt_nombre").val(),
            data.identificacion = $("#flt_identificacion_pqr").val(),
            data.fechaini   = $("#flt_fch_pqr_ini").val(),
            data.fechafin   = $("#flt_fch_pqr_fin").val()
        }).DataTable({ ///todo por una D jeje
            "aLengthMenu": [
                [10, 15, 20],
                [10, 15, 20]
            ],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": false,
            "ajax": {
                "url": "actividad/ajax/listar_pqr.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item",className: "text-center","searchable": false,"orderable": false},
                { "data": "id_pqr",className: "text-center","searchable": false,"orderable": true,"name":"p.id_pqr"},
                { "data": "nombre",className: "text-left","searchable": false,"orderable": true,"name":"p.nombre_usuario_servicio"},
                { "data": "direccion",className: "text-left","searchable": false,"orderable": true,"name":"p.direccion_reporte"},
                { "data": "barrio",className: "text-left","searchable": false,"orderable": true,"name":"b.descripcion"},
                { "data": "tipo_reporte",className: "text-left","searchable": false,"orderable": true,"name":"tr.descripcion"},
                { "data": "tipo_pqr",className: "text-left","searchable": false,"orderable": true,"name":"tp.descripcion"},
                { "data": "fecha_reporte", className: "text-center", "searchable": false, "orderable": true,"name":"p.fch_pqr" },
                { "data": "estado", className: "text-center", "searchable": false, "orderable": false },
                { "data": "id_luminaria","bVisible": false, className: "text-center", "searchable": false, "orderable": false },
                { "data": "luminaria_no","bVisible": false, className: "text-center", "searchable": false, "orderable": false },
                { "data": "poste_no","bVisible": false, className: "text-center", "searchable": false, "orderable": false },
                { "data": "id_tipo_luminaria","bVisible": false, className: "text-center", "searchable": false, "orderable": false },
                { "data": "id_barrio_luminaria","bVisible": false, className: "text-center", "searchable": false, "orderable": false },
                { "data": "direccion_luminaria","bVisible": false, className: "text-center", "searchable": false, "orderable": false }

            ],
            "order": [
                [1,"DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            }
        });
    }
    $("#tbl_pqr tbody").on("click", "tr", function() {
        $("#tbl_pqr tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        detallePQR = tablePQR.row(this).data();
        $("#frm-pqr").modal("hide");
    });
}