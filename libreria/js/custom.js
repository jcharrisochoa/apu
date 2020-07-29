/**
 * Prueba Nuemro 2 
 */
;
(function($) {
    $.fn.datepicker.dates['es'] = {
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        monthsTitle: "Meses",
        clear: "Borrar",
        weekStart: 1,
        format: "yyyy-mm-dd"
    };
}(jQuery));
$(function() {
    $(".datepicker").each(function(i, el) {
        var $this = $(el),
            opts = {
                language: 'es',
                autoclose: 'true',
                startDate: attrDefault($this, 'startDate', ''),
                endDate: attrDefault($this, 'endDate', ''),
                daysOfWeekDisabled: attrDefault($this, 'disabledDays', ''),
                startView: attrDefault($this, 'startView', 0),
                rtl: rtl()
            },
            $n = $this.next(),
            $p = $this.prev();
        $this.datepicker(opts);
        if ($n.is('.input-group-addon') && $n.has('a')) {
            $n.on('click', function(ev) {
                ev.preventDefault();
                $this.datepicker('show');
            });
        }
        if ($p.is('.input-group-addon') && $p.has('a')) {
            $p.on('click', function(ev) {
                ev.preventDefault();
                $this.datepicker('show');
            });
        }
    });
    $("[data-mask]").each(function(i, el) {
        var $this = $(el),
            mask = $this.data('mask').toString();
        opts = "";
        switch (mask.toLowerCase()) {
            case "phone":
                mask = "(999) 999-9999";
                break;
            case "currency":
                opts = {
                    'alias': 'currency',
                    rightAlign: true
                };
                break;
            case "currencyd":
                opts = {
                    'alias': 'currency',
                    rightAlign: true,
                    digits: 0
                };
                break;
            case "email":
                mask = 'Regex';
                opts.regex = "[a-zA-Z0-9._%-]+@[a-zA-Z0-9-]+\\.[a-zA-Z]{2,4}";
                break;

            case "decimal":
                opts = {
                    'alias': 'decimal',
                    rightAlign: true,
                    'groupSeparator': '.',
                    'autoGroup': true
                };
                break;
        }
        $this.inputmask(opts);
    });
    $("input[type='text']").click(function() {
        $(this).select();
    });
    $(".switch").not("[data-switch-no-init]").bootstrapSwitch();
});

function inicioEnvio() {
    $.blockUI({ message: '<img src="../../libreria/img/loading.gif" height="150px" width="150px" />' });
    return;
}

function disableInput(clas) {
    var collection = $(clas);
    collection.each(function() {
        $(this).attr("disabled", true);
    });
    return;
}

function disableInputBootstrapSwitch(clas) {
    var collection = $(clas);
    collection.each(function() {
        $(this).bootstrapSwitch("disabled", true);
    });
    return;
}

function enableInputBootstrapSwitch(clas) {
    var collection = $(clas);
    collection.each(function() {
        $(this).bootstrapSwitch("disabled", false);
    });
    return;
}

function enableInput(clas) {
    var collection = $(clas);
    collection.each(function() {
        $(this).attr("disabled", false);
    });
    return;
}

function clearInput(clas) {
    var collection = $(clas);
    collection.each(function() {
        switch ($(this).attr("type")) {
            case "text":
                $(this).val("");
                break;
            case "password":
                $(this).val("");
                break;
            case "hidden":
                $(this).val("");
                break;
            case "radio":
                $(this).prop("checked", false);

                break;
            case "checkbox":
                $(this).prop("checked", false);
                //$(this).bootstrapSwitch("state",false);
                break;
            default:
                var str = $(this).get(0);
                str = str.tagName;
                switch (str.toLowerCase()) {
                    case "select":
                        $(this).val("").trigger("change");
                        break;
                    case "textarea":
                        $(this).val("");
                        break;
                }
                break;
        }
    });
    return;
}

function ValidarDatos(clas) {
    var collection = $(clas);
    var sw = true;
    collection.each(function() {
        switch ($(this).attr("type")) {
            case "text":
                if ($.trim($(this).val()) === "") {
                    var texto = $(this).attr("title");
                    $("#modal-body").html(texto + ' no puede ser en blanco !');
                    $("#modal-msgbox").modal('show');
                    $(this).focus();
                    sw = false;
                }
                break;
            case "password":
                if ($.trim($(this).val()) === "") {
                    var texto = $(this).attr("title");
                    $("#modal-body").html(texto + ' no puede ser en blanco !');
                    $("#modal-msgbox").modal('show');
                    $(this).focus();
                    sw = false;
                }
                break;
            case "radio":
                var name = $(this).attr("name");
                if (!$('input[name=' + name + ']').is(':checked')) {
                    var texto = $(this).attr("title");
                    $("#modal-body").html(texto + ' no puede ser en blanco !');
                    $("#modal-msgbox").modal('show');
                    $(this).focus();
                    sw = false;
                }
                break;
            default:
                var str = $(this).get(0);
                str = str.tagName;
                switch (str) {
                    case "SELECT":
                        if ($.trim($(this).val()) === "") {
                            var texto = $(this).attr("title");
                            $("#modal-body").html(texto + ' no puede ser en blanco !');
                            $("#modal-msgbox").modal('show');
                            $(this).focus();
                            sw = false;
                        }
                        break;

                }
                break;

        }
        if (!sw) {
            return false;
        }
    });
    return sw;
}

function updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[type="checkbox"]', $table);
    var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);
    // If none of the checkboxes are checked
    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }

        // If all of the checkboxes are checked
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }

        // If some of the checkboxes are checked
    } else {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
    }
}

function getValor(valor) {
    var vl = valor.replace("$", "");
    var vlI = vl.replace(/,/g, "");
    var vlII = vlI.replace(/_/g, "");
    return vlII;
}

function getFormatFecha(valor) {
    ano = valor.substring(0, 4);
    mes = valor.substring(5, 7);
    dia = valor.substring(8, 10);
    return dia + "/" + mes + "/" + ano;
}

function sumarDias(fecha, dias) {
    milisegundos = parseInt(35 * 24 * 60 * 60 * 1000);
    day = fecha.getDate();
    // el mes es devuelto entre 0 y 11
    month = fecha.getMonth() + 1;
    year = fecha.getFullYear();
    //Obtenemos los milisegundos desde media noche del 1/1/1970
    tiempo = fecha.getTime();
    //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
    milisegundos = parseInt(dias * 24 * 60 * 60 * 1000);
    //Modificamos la fecha actual
    total = fecha.setTime(tiempo + milisegundos);
    day = fecha.getDate();
    month = fecha.getMonth() + 1;
    year = fecha.getFullYear();
    if (day < 10) {
        day = '0' + day
    }
    if (month < 10) {
        month = '0' + month
    }
    var today = day + '/' + month + '/' + year;
    return today;
}