var tableLiquidacion = "";
var dataDetalleLiquidacion = "";
var dataSet = [];
var accion = "";


$(function(){
    $("#txt_consumo").numeric({
        decimal: false, 
        negative: false
    });
    $(".numeric-positive").numeric({
        negative:false
    });

    $("#txt_valor_tarifa").keyup(function(){
        calcular();
    });

    $("#txt_consumo").keyup(function(){
        calcular();
    });

    $("#txt_valor_factura_energia_ap").keyup(function(){
        calcular();
    });


    $("#txt_valor_facturado_tsycc").keyup(function(){
        calcular();
    });

    $("#txt_valor_recaudo_tsycc").keyup(function(){
        calcular();
    });

    $("#txt_valor_recaudo_ap").keyup(function(){
        calcular();
    });

    $("#txt_valor_facturado_ap").keyup(function(){
        calcular();
    });
    

    $("#btn_buscar_liquidacion").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            tableLiquidacion.ajax.reload(function(){
                $("#modal-text-global").html("Se encontraron "+tableLiquidacion.page.info().recordsTotal+" registros");
                $("#modal-mensaje-global").modal("show");
                dataDetalleLiquidacion = "";
            });
            event.handld = true;
        }
        return false;
    });

    $("#frm-liquidacion").on('hidden.bs.modal',function(){ //accion cuando se cierra la ventana
        clearInput(".clear");
        $("#slt_municipio").prop("disabled",false);
        $("input[type=text]").prop("readonly",false);
        $("select").prop("disabled",false);
        $("#btn_guardar_liquidacion").prop("disabled",false);
        $("#txt_fecha_ini").addClass("datepicker");
        $("#txt_fecha_fin").addClass("datepicker");
        $("#btn_guardar_liquidacion").show();

        $("#tbl_liquidacion tbody tr").removeClass("highlight");
        dataDetalleLuminaria = "";
        accion="";
    });

    $("#btn_nueva_liquidacion").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            nuevaLiquidacion(); 
            accion = "nuevo";     
            event.handld = true;
        }
        return false;
    });

    $("#btn_editar_liquidacion").click(function(){       
        if (event.handled !== true) {
            event.preventDefault();
            editarLiquidacion(dataDetalleLiquidacion);
            accion = "editar";   
            event.handld = true;
        }
        return false;
    });

    $("#btn_eliminar_liquidacion").click(function(){        
        if (event.handled !== true) {
            event.preventDefault();
            eliminarLiquidacion(dataDetalleLiquidacion);
            accion = "eliminar";   
            event.handld = true;
        }
        return false;
    });

    $("#btn_detalle_liquidacion").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            verDetalle(dataDetalleLiquidacion);      
            event.handld = true;
        }
        return false;
    });

    $("#btn_exportar_liquidacion").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            exportarLiquidacion(); 
            event.handld = true;
        }
        return false;
    });

    $("#btn_guardar_liquidacion").click(function(event) {
        if (event.handled !== true) {
            event.preventDefault();
            if(ValidarDatos(".requerido")){
                guardarLiquidacion(accion);
            }  
            event.handld = true;
        }
        return false;
    });

    InitTableLiquidacion();
});

function InitTableLiquidacion() {
    if (!$.fn.DataTable.isDataTable("#tbl_liquidacion")) {
        tableLiquidacion = "";
        tableLiquidacion = $("#tbl_liquidacion").on("preXhr.dt", function(e, settings, data) {
                data.municipio = $("#municipio").val(),
                data.periodo_liquidacion = $("#periodo_liquidacion").val(),
                data.mes_liquidacion = $("#mes_liquidacion").val()
        }).DataTable({ ///todo por una D jeje
            "aLengthMenu": [
                [15, 30, 50, 70, 100],
                [15, 30, 50, 70, 100]
            ],
            dom: 'Bfrtip',
				buttons: [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					'pdfHtml5'
				],
            "bStateSave": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "bAutoWidth": true,
            "searching": false,

            "ajax": {
                "url": "liquidacion/ajax/listar_liquidacion.php",
                "type": "POST"
            },
            "columns": [
                { "data": "item",className: "text-center", "searchable": false },
                { "data": "municipio",className: "text-left","searchable": false,"orderable": true,"name":"m.descripcion"},
                { "data": "periodo",className: "text-center","searchable": false,"orderable": true,"name":"l.periodo_liquidacion"},
                { "data": "nombre_mes",className: "text-center","searchable": false,"orderable": true,"name":"l.mes_liquidacion"},
                { "data": "consumo",className: "text-right","searchable": false,"orderable": true,"name":"l.consumo"},
                { "data": "valor_tarifa", className: "text-right", "searchable": false, "orderable": false ,render: $.fn.dataTable.render.number('.', ',', 2, '$ ') },
                { "data": "valor_consumo", className: "text-right", "searchable": false, "orderable": false ,render: $.fn.dataTable.render.number('.', ',', 2, '$ ') },
                { "data": "factura_energia", className: "text-right", "searchable": false, "orderable": false ,render: $.fn.dataTable.render.number('.', ',', 2, '$ ')},
                { "data": "facturado_ap", className: "text-right", "searchable": false, "orderable": false ,render: $.fn.dataTable.render.number('.', ',', 2, '$ ')},                
                { "data": "facturado_tsycc", className: "text-right", "searchable": false, "orderable": false ,render: $.fn.dataTable.render.number('.', ',', 2, '$ ')},
                { "data": "diferencia_facturacion", className: "text-right", "searchable": false, "orderable": false ,
                    render: function (data, type, row) {
                        if (parseFloat(row.diferencia_facturacion) != 0) {
                            return '<span class="badge  badge-danger">$ ' +$.number(row.diferencia_facturacion,2, ',', '.')+ ' </span>';
                        }
                        else {
                            return '<span class="">$ ' + $.number(row.diferencia_facturacion,2, ',', '.') + '</span>';
                        }
                        
                    }
                },
                { "data": "recaudo_ap", className: "text-right", "searchable": false, "orderable": false ,render: $.fn.dataTable.render.number('.', ',', 2, '$ ')},                
                { "data": "recaudo_tsycc", className: "text-right" , "searchable": false, "orderable": false ,render: $.fn.dataTable.render.number('.', ',', 2, '$ ')},
                { "data": "diferencia_recaudo", className: "text-right", "searchable": false, "orderable": false ,
                   // render: $.fn.dataTable.render.number('.', ',', 2, '$ '),
                    render: function (data, type, row) {
                        if (parseFloat(row.diferencia_recaudo) != 0) {
                            return '<span class="badge badge-danger">$ ' +$.number(row.diferencia_recaudo,2, ',', '.')+ ' </span>';
                        }
                        else {
                            return '<span class="">$ ' +$.number(row.diferencia_recaudo,2, ',', '.')+ '</span>';
                        }
                    }
                },            

            ],
            "order": [
                [2, "DESC"],
                [1, "DESC"]
            ],
            language: {
                url: "../../../../libreria/DataTableSp.json"
            },
            "rowCallback": function(row, data, dataIndex) {
                var rowId = data.id;
                $(row).find("td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)").css("color", data.colorestado);
            }
        });
    }
    $("#tbl_liquidacion tbody").on("click", "tr", function() {
        $("#tbl_liquidacion tbody tr").removeClass("highlight");
        $(this).addClass("highlight");
        dataDetalleLiquidacion = tableLiquidacion.row(this).data();
        $("#id_liquidacion").val(dataDetalleLiquidacion.id);
    });
}

function verDetalle(dataDet) {
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la liquidaci&oacute;n a visualizar", null, opts);
    } else {
        $("#slt_municipio").val(dataDet.id_municipio).change();
        $("#slt_periodo_liquidacion").val(dataDet.periodo).change();
        $("#slt_mes_liquidacion").val(dataDet.mes).change();
        $("#txt_fecha_ini").val(dataDet.fch_ini_facturacion);
        $("#txt_fecha_fin").val(dataDet.fch_fin_facturacion);
        $("#txt_valor_tarifa").val(dataDet.valor_tarifa);
        $("#txt_consumo").val(dataDet.consumo);
        $("#txt_total_consumo").val(dataDet.valor_consumo);
        $("#txt_valor_facturado_ap").val(dataDet.facturado_ap);
        $("#txt_valor_recaudo_ap").val(dataDet.recaudo_ap);
        $("#txt_valor_factura_energia_ap").val(dataDet.factura_energia);
        $("#txt_valor_facturado_tsycc").val(dataDet.facturado_tsycc);
        $("#txt_valor_recaudo_tsycc").val(dataDet.recaudo_tsycc);

        calcular();
        $("#slt_municipio").prop("disabled",true);
        $("input[type=text]").prop("readonly",true);
        $("select").prop("disabled",true);
        $("#btn_guardar_liquidacion").prop("disabled",true);
        $("#txt_fecha_ini").removeClass("datepicker");
        $("#txt_fecha_fin").removeClass("datepicker");
        $("#btn_guardar_liquidacion").hide();

        $("#frm-titulo-liquidacion").html("Liquidacion "+dataDet.municipio+" / "+dataDet.periodo+"-"+dataDet.nombre_mes);
        $("#frm-liquidacion").modal("show"); 
        
    }
}

function nuevaLiquidacion(){
    $("#frm-titulo-liquidacion").html("Nueva Liquidaci&oacute;n");
    $("#frm-liquidacion").modal("show"); 
    $("#slt_municipio").prop("disabled",false);
    $("#tbl_liquidacion tbody tr").removeClass("highlight");
    clearInput(".clear");
    dataDetalleLiquidacion = "";
}

function calcular(){
    var tarifa =  ($("#txt_valor_tarifa").val()=="")?0:$("#txt_valor_tarifa").val();
    var consumo =  ($("#txt_consumo").val()=="")?0:$("#txt_consumo").val();
    var valorEnergia =  ($("#txt_valor_factura_energia_ap").val()=="")?0:$("#txt_valor_factura_energia_ap").val();
    var totalConsumo = parseFloat(tarifa) * consumo;
    var diferencia = parseFloat(valorEnergia) - totalConsumo;
    
    $("#txt_total_consumo").val(totalConsumo);
    $("#diferencia_consumo").html(diferencia.toFixed(2));
    if(diferencia==0){
        $("#diferencia_consumo").removeClass("badge-danger");
        $("#diferencia_consumo").addClass("badge-success");        
    }
    else{
        $("#diferencia_consumo").removeClass("badge-success");
        $("#diferencia_consumo").addClass("badge-danger");
    }

    var facturado_tsycc = ($("#txt_valor_facturado_tsycc").val()=="")?0:$("#txt_valor_facturado_tsycc").val();
    var recaudo_tsycc   = ($("#txt_valor_recaudo_tsycc").val()=="")?0:$("#txt_valor_recaudo_tsycc").val();

    var facturado_ap      = ($("#txt_valor_facturado_ap").val()=="")?0:$("#txt_valor_facturado_ap").val();
    var recaudo_ap    = ($("#txt_valor_recaudo_ap").val()=="")?0:$("#txt_valor_recaudo_ap").val();
    

    $("#diferencia_facturacion").html(facturado_ap - facturado_tsycc);
    $("#diferencia_recaudo").html(recaudo_ap - recaudo_tsycc );

    if(facturado_tsycc == facturado_ap){
        $("#diferencia_facturacion").removeClass("badge-danger");
        $("#diferencia_facturacion").addClass("badge-success"); 
    }
    else{        
        $("#diferencia_facturacion").removeClass("badge-success");
        $("#diferencia_facturacion").addClass("badge-danger");
    }

    if(recaudo_tsycc == recaudo_ap){
        $("#diferencia_recaudo").removeClass("badge-danger");
        $("#diferencia_recaudo").addClass("badge-success"); 
    }
    else{
        $("#diferencia_recaudo").removeClass("badge-success");
        $("#diferencia_recaudo").addClass("badge-danger");
    }
}

function guardarLiquidacion(){
    switch(accion){
        case "nuevo":
            var variable = $("#form-liquidacion").serialize()+"&accion="+accion;
            break;
        case "editar":
            var variable = $("#form-liquidacion").serialize()+"&accion="+accion;
            break;
        case "eliminar":
            var variable = "id_liquidacion="+$("#id_liquidacion").val()+"&accion="+accion;
            break;
    }
    $.ajax({
        async:true,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        url:"liquidacion/ajax/guardar_accion.php",
        data:variable,
        beforeSend:inicioEnvio,
        success:function(data){
            if(data.response.estado){
                toastr.success(data.response.mensaje, null, opts);
                $("#frm-liquidacion").modal("hide"); 
                clearInput(".clear");
                accion="";
                tableLiquidacion.ajax.reload();
                dataDetalleLiquidacion = "";
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

function editarLiquidacion(dataDet){
    //console.log(dataDet);
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la liquidacion a editar", null, opts);
    } 
    else {
        $("#frm-titulo-liquidacion").html("Editar Liquidacion "+dataDet.municipio+" / "+dataDet.periodo+"-"+dataDet.nombre_mes);
        $("#id_liquidacion").val(dataDet.id);       
        $("#slt_municipio").val(dataDet.id_municipio).change();
        $("#slt_periodo_liquidacion").val(dataDet.periodo).change();
        $("#slt_mes_liquidacion").val(dataDet.mes).change();
        $("#txt_fecha_ini").val(dataDet.fch_ini_facturacion);
        $("#txt_fecha_fin").val(dataDet.fch_fin_facturacion);
        $("#txt_valor_tarifa").val(dataDet.valor_tarifa);
        $("#txt_consumo").val(dataDet.consumo);
        $("#txt_total_consumo").val(dataDet.valor_consumo);
        $("#txt_valor_facturado_ap").val(dataDet.facturado_ap);
        $("#txt_valor_recaudo_ap").val(dataDet.recaudo_ap);
        $("#txt_valor_factura_energia_ap").val(dataDet.factura_energia);
        $("#txt_valor_facturado_tsycc").val(dataDet.facturado_tsycc);
        $("#txt_valor_recaudo_tsycc").val(dataDet.recaudo_tsycc);
        calcular();
        $("#slt_municipio").prop("disabled",true);
        $("#frm-liquidacion").modal("show"); 
    }
}

function eliminarLiquidacion(dataDet){
    if (dataDet.length == 0) {
        toastr.warning("Seleccione la liquidacion a eliminar", null, opts);
    } 
    else {
        $("#modal-body-conf").html("¿ Está seguro(a) de eleminar la liquidaci&oacute;n "+dataDet.municipio+" / "+dataDet.periodo+"-"+dataDet.nombre_mes+"?");
        $("#modal-conf").modal("show");
        $("#btn_si").off("click").on("click",function(event){      
            if (event.handled !== true) {
                event.preventDefault();
                $("#modal-conf").modal("hide");
                $("#modal-body-conf").html("")
                guardarLiquidacion("eliminar");                     
                event.handld = true;
            }
            return false;                
        });  
    }
}

function exportarLiquidacion(){
    var url = "municipio="+$("#municipio").val()+"&"+
                "periodo_liquidacion="+$("#periodo_liquidacion").val()+"&"+
                "mes_liquidacion="+$("#mes_liquidacion").val();

    window.open("liquidacion/ajax/exportar_liquidacion.php?"+url);
}