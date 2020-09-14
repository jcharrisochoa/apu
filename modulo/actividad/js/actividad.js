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
var tableArticulo = [];
var objArticulo = {id_articulo:"",descripcion:"",cantidad:""};
var item    = 0;

$(function() {

    $("#txt_cantidad").numeric({
        negative:false
    });

    $("#txt_codigo").numeric({
        negative:false
    });

    $("#municipio").change(function() {
        listarBarrio();
    });
    /*$("#barrio").select2();
    $("#tipo_actividad").select2();*/
    $("#btn_buscar_actividad").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            var json = tableActividad.ajax.reload(function(){
                //console.log("f:"+tableActividad.page.info().recordsTotal)
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

    $("#btn_cerrar_detalle_actividad").click(function(){
        $("actividad").html("");
        $("#modal-detalle-actividad").modal("hide");
    });

    $("#btn_agregar").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            agregarArticulo(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            eliminarArticulo(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
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
   
    InitTableActividad();
    initTableArticulo();
});

function listarBarrio() {
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url: "actividad/ajax/listar_barrio.php",
        data: {
            id_municipio: $("#municipio").val()
        },
        beforeSend: inicioEnvio,
        success: function(data) {
            console.log(data);
            $("#barrio").empty();
            for (x in data.lista) {
                $("#barrio").append('<option value="' + data.lista[x].id_barrio + '">' + data.lista[x].descripcion + '</option>');
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
                { "data": "observacion","bVisible": false, className: "alignCenter", "searchable": false, "orderable": false }
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
        var data = tableActividad.row(this).data();
        verDetalle(data);
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
        
        $("#modal-detalle-actividad").modal("show");
    }
}

function nuevaActividad(){
    $("#frm-titulo-actividad").html("Nueva Actividad");
    $("#frm-actividad").modal("show"); 
    $("#tbl_actividad tbody tr").removeClass("highlight");
    clearInput(".clear");
    //tableActividad = "";
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
                clearInputAddArticulo();
                $("#txt_codigo").focus();

                $("#tbl_articulo_actividad tbody").on("click","tr", function () {         
                    $("#tbl_articulo_actividad tbody tr").removeClass('highlight');
                    $(this).addClass('highlight');
                    if(tableArticuloActividad.row(this).length>0){
                        var data=tableArticuloActividad.row(this).data();
                        $("#item").val(data.item);
                        $("#txt_codigo").val(data.id_articulo);
                        $("#txt_descripcionpro").val(data.descripcion); 
                        $("#txt_cantidad").val(data.cantidad);      
                    }              
                });
            }
        }
    }
}

function eliminarArticulo(){
    if($("#tbl_articulo_actividad tbody tr").hasClass('highlight')){
        tableArticuloActividad.row('.highlight').remove().draw();
        clearInputAddArticulo();
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
                                    {"data":"item",className: "alignCenter","searchable": false,"orderable": false},                                
                                    {"data":"id_articulo",className: "alignCenter","searchable": false,"orderable": false},
                                    {"data":"descripcion",className: "alignLeft","searchable": false,"orderable": false},
                                    {"data":"cantidad",className:"alignRight"}
                                ],
                               
                                language: {
                                    url: "../../../../libreria/DataTableSp.json"
                                }
                            }); 
}

function clearInputAddArticulo(){
    $("#txt_codigo").val("");
    $("#txt_descripcion").val("");
    $("#txt_cantidad").val("");
}