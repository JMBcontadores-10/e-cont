
function disableInputs() {
    $('#main select, #main input, #main .btn').attr('disabled', 'disabled');
}
function enableInputs() {
    $('#main select, #main input, #main .btn').removeAttr('disabled');
}

var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'
            + '<head><meta http-equiv="Content-type" content="text/html;charset=UTF-8" /><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/>'
            + '</x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        },
        format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        };

    return function (table, name) {
        var ctx = {
            worksheet: name || 'Worksheet',
            table: table.innerHTML
        }
        return uri + base64(format(template, ctx));
    }
})();

$('.excel-export').on('click', function () {
    var $this = $(this);
    var table = $this.closest('.descarga-form').find('.table').get(0);
    var fn = $this.attr('download');
    $this.attr('href', tableToExcel(table, fn));
    // window.location.href = tableToExcel(table, fn);
});

$('.login-form').on('submit', function () {
    var form = $(this);
    var formData = new FormData(form.get(0));

    window.sesionDM = null;

    disableInputs();
    $('.tablas-resultados').removeClass('listo');
    $('.tablas-resultados tbody').empty();

    $.post({
        url: "async",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.debug(response);
            if (response.success && response.data) {
                if (response.data.sesion) {
                    window.sesionDM = response.data.sesion;
                }
                $('.tablas-resultados').addClass('listo');
            }
            if (response.data && response.data.mensaje) {
                alert(response.data.mensaje);
            }
        }
    }).always(function () {
        enableInputs();
    });

    return false;
});

$('#recibidos-form').on('submit', function () {
    var form = $(this);
    var formData = new FormData(form.get(0));
    formData.append('sesion', window.sesionDM);

    var tablaBody = $('#tabla-recibidos tbody');

    tablaBody.empty();
    disableInputs();

    $.post({
        url: "async",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.debug(response);

            if (response.success && response.data) {
                if (response.data.sesion) {
                    window.sesionDM = response.data.sesion;
                }

                var items = response.data.items;
                var html = '';

                for (var i in items) {
                    var item = items[i];
                    i++;
                    if (item.estado == 'Vigente') {
                        aprobacion = '<img src="img/ima.png">';
                    } else {
                        aprobacion = '<img src="img/ima2.png">';
                    }
                    html += '<tr>'
                        + '<td>' + i + '</td>'
                        + '<td class="text-center txml">' + (item.urlDescargaXml ? '<input type="checkbox" checked="checked" name="xml[' + item.folioFiscal + ']" value="' + item.urlDescargaXml + '"/>' : '-') + '</td>'
                        + '<td class="text-center tpdf">' + (item.urlDescargaRI ? '<input type="checkbox" checked="checked" name="ri[' + item.folioFiscal + ']" value="' + item.urlDescargaRI + '"/>' : '-') + '</td>'
                        + '<td class="blur">' + item.folioFiscal + '</td>'
                        + '<td class="blur">' + item.emisorRfc + '</td>'
                        + '<td class="blur">' + item.emisorNombre + '</td>'
                        + '<td>' + item.fechaEmision + '</td>'
                        + '<td>' + item.fechaCertificacion + '</td>'
                        + '<td>' + item.total + '</td>'
                        + '<td>' + item.efecto + '</td>'
                        + '<td>' + item.estado + '</td>'
                        + '<td>' + (item.fechaCancelacion || '-') + '</td>'
                        + '<td>' + aprobacion + '</td>'
                        + '<td class="blur">' + item.pacCertifico + '</td>'
                        + '</tr>'
                        ;
                }

                tablaBody.html(html);
            }
            if (response.data && response.data.mensaje) {
                alert(response.data.mensaje);
            }
        }
    }).always(function () {
        enableInputs();
    });

    return false;
});

$('#emitidos-form').on('submit', function () {
    var form = $(this);
    var formData = new FormData(form.get(0));
    formData.append('sesion', window.sesionDM);
    var tablaBody = $('#tabla-emitidos tbody');

    tablaBody.empty();
    disableInputs();

    $.post({
        url: "async",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.debug(response);

            if (response.success && response.data) {
                if (response.data.sesion) {
                    window.sesionDM = response.data.sesion;
                }

                var items = response.data.items;
                var html = '';
                var c = 1;

                for (var i in items) {
                    var item = items[i];
                    i++;
                    if (item.estado == 'Vigente') {
                        aprobacion = '<img src="img/ima.png">';
                    } else {
                        aprobacion = '<img src="img/ima2.png">';
                    }
                    html += '<tr>'
                        + '<td>' + i + '</td>'
                        + '<td class="text-center etxml">' + (item.urlDescargaXml ? '<input type="checkbox" checked="checked" name="xml[' + item.folioFiscal + ']" value="' + item.urlDescargaXml + '"/>' : '-') + '</td>'
                        + '<td class="text-center etpdf">' + (item.urlDescargaRI ? '<input type="checkbox" checked="checked" name="ri[' + item.folioFiscal + ']" value="' + item.urlDescargaRI + '"/>' : '-') + '</td>'
                        + '<td class="text-center etpdf">' + (item.urlDescargaAcuse ? '<input type="checkbox" checked="checked" name="acuse[' + item.folioFiscal + ']" value="' + item.urlDescargaAcuse + '"/>' : '-') + '</td>'
                        + '<td class="blur">' + item.folioFiscal + '</td>'
						+ '<td class="blur">' + item.receptorRfc + '</td>'
						+ '<td class="blur">' + item.receptorNombre + '</td>'
						+ '<td>' + item.fechaEmision + '</td>'
						+ '<td>' + item.fechaCertificacion + '</td>'
						+ '<td>' + item.total + '</td>'
						+ '<td>' + item.efecto + '</td>'
						+ '<td>' + item.estado + '</td>'
                        + '<td>' + aprobacion + '</td>'
						+ '<td class="blur">' + item.pacCertifico + '</td>'
						+ '</tr>'
                        ;
                }

                tablaBody.html(html);
            }
            if (response.data && response.data.mensaje) {
                alert(response.data.mensaje);
            }
        }
    }).always(function () {
        enableInputs();
    });

    return false;
});

$('.descarga-form').on('submit', function () {
    var form = $(this);
    var formData = new FormData(form.get(0));
    formData.append('sesion', window.sesionDM);

    disableInputs();

    $.post({
        url: "async",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.debug(response);

            if (response.success && response.data) {
                if (response.data.sesion) {
                    window.sesionDM = response.data.sesion;
                }
            }
            if (response.data && response.data.mensaje) {
                alert(response.data.mensaje);
            }
        }
    }).always(function () {
        enableInputs();
    });

    return false;
});

$('#allxml').change(function () {
    $('tbody tr td[class="text-center txml"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

$('#allpdf').change(function () {
    $('tbody tr td[class="text-center tpdf"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

$('#eallxml').change(function () {
    $('tbody tr td[class="text-center etxml"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

$('#eallpdf').change(function () {
    $('tbody tr td[class="text-center etpdf"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

