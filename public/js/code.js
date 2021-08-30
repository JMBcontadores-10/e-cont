$("#form-editar").hide();
$("#form-crear").hide();
$("#vinp").hide();
$('input[id="vinpsub"]').hide();
$('th[id="vinp"]').hide();
$('td[id="vinp"]').hide();
$('#loading').hide();
$('#loadingE').hide();
$("#doc-relacionados").hide();
$("#login1").hide();

function showLogin1(){
    $("#login1").show();
    $("#entrar").hide();
}

function variableP(){
    // var selA = document.getElementById("empresas");
    // var anio = selA.options[selA.selectedIndex].value;
    // document.getElementById("password").value = document.getElementById("empresas").innerText;
}

function disableInputs() {
    $(':input[type="submit"]').prop('disabled', true);
    $('a[class="btn btn-primary excelR-export"]').addClass('disabled');
    $('a[class="btn btn-primary excelE-export"]').addClass('disabled');
    // $('a[class="btn btn-primary ml-2"]').addClass('disabled');
}

function enableInputs() {
    $(':input[type="submit"]').prop('disabled', false);
    $('a[class="btn btn-primary excelR-export disabled"]').removeClass('disabled');
    $('a[class="btn btn-primary excelE-export disabled"]').removeClass('disabled');
    // $('a[class="btn btn-primary ml-2 disabled"]').removeClass('disabled');
}

var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
        '<head><meta http-equiv="Content-type" content="text/html;charset=UTF-8" /><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/>' +
        '</x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        },
        format = function(s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
            })
        };

    return function(table, name) {
        var ctx = {
            worksheet: name || 'Worksheet',
            table: table.innerHTML
        }
        return uri + base64(format(template, ctx));
    }
})();

$('.excelR-export').on('click', function() {
    var $this = $(this);
    var table = $this.closest('.descargaR-form').find('.table').get(0);
    var fn = $this.attr('download');
    $this.attr('href', tableToExcel(table, fn));
    // window.location.href = tableToExcel(table, fn);
});

$('.excelE-export').on('click', function() {
    var $this = $(this);
    var table = $this.closest('.descargaE-form').find('.table').get(0);
    var fn = $this.attr('download');
    $this.attr('href', tableToExcel(table, fn));
    // window.location.href = tableToExcel(table, fn);
});

$('.login-form').on('submit', function() {
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
        success: function(response) {
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
    }).always(function() {
        enableInputs();
    });

    return false;
});

$('#recibidos-form').on('submit', function() {
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
        success: function(response) {
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
                    if (item.descargadoXml) {
                        descargadoXml = "Si";
                        checkedXml = '';
                    } else {
                        descargadoXml = "No";
                        checkedXml = 'checked';
                    }
                    if (item.descargadoPdf) {
                        descargadoPdf = "Si";
                        checkedPdf = '';
                    } else {
                        descargadoPdf = "No";
                        checkedPdf = 'checked';
                    }
                    html += '<tr>' +
                        '<td class="text-center align-middle">' + i + '</td>' +
                        '<td class="text-center align-middle txml">' + (item.urlDescargaXml ? '<input type="checkbox" ' + checkedXml + ' name="xml[' + item.folioFiscal + ']" value="' + item.urlDescargaXml + '"/>' : '-') + '</td>' +
                        '<td class="text-center align-middle tpdf">' + (item.urlDescargaRI ? '<input type="checkbox" ' + checkedPdf + ' name="ri[' + item.folioFiscal + ']" value="' + item.urlDescargaRI + '"/>' : '-') + '</td>' +
                        '<td class="text-center align-middle">' + item.folioFiscal + '</td>' +
                        '<td class="text-center align-middle">' + item.emisorRfc + '</td>' +
                        '<td class="text-center align-middle">' + item.emisorNombre + '</td>' +
                        '<td class="text-center align-middle">' + item.fechaEmision + '</td>' +
                        '<td class="text-center align-middle">' + item.fechaCertificacion + '</td>' +
                        '<td class="text-center align-middle">' + item.total + '</td>' +
                        '<td class="text-center align-middle">' + item.efecto + '</td>' +
                        '<td class="text-center align-middle">' + item.estado + '</td>' +
                        '<td class="text-center align-middle">' + (item.fechaCancelacion || '-') + '</td>' +
                        '<td class="text-center align-middle">' + aprobacion + '</td>' +
                        '<td class="text-center align-middle">' + descargadoXml + '</td>' +
                        '<td class="text-center align-middle">' + descargadoPdf + '</td>'
                        // + '<td class="text-center">' + item.pacCertifico + '</td>'
                        +
                        '</tr>';
                }

                tablaBody.html(html);
            }
            if (response.data && response.data.mensaje) {
                alert(response.data.mensaje);
            }
        }
    }).always(function() {
        enableInputs();
    });

    return false;
});

$('#emitidos-form').on('submit', function() {
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
        success: function(response) {
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
                    if (item.descargadoXml) {
                        descargadoXml = "Si";
                        checkedXml = '';
                    } else {
                        descargadoXml = "No";
                        checkedXml = 'checked';
                    }
                    if (item.descargadoPdf) {
                        descargadoPdf = "Si";
                        checkedPdf = '';
                    } else {
                        descargadoPdf = "No";
                        checkedPdf = 'checked';
                    }
                    if (item.urlDescargaAcuse) {
                        if (item.descargadoAcuse) {
                            descargadoAcuse = "Si";
                            checkedAcuse = '';
                        } else {
                            descargadoAcuse = "No";
                            checkedAcuse = 'checked';
                        }
                    } else {
                        descargadoAcuse = "-";
                    }

                    html += '<tr>' +
                        '<td class="text-center align-middle">' + i + '</td>' +
                        '<td class="text-center align-middle etxml">' + (item.urlDescargaXml ? '<input type="checkbox" ' + checkedXml + ' name="xml[' + item.folioFiscal + ']" value="' + item.urlDescargaXml + '"/>' : '-') + '</td>' +
                        '<td class="text-center align-middle etpdf">' + (item.urlDescargaRI ? '<input type="checkbox" ' + checkedPdf + ' name="ri[' + item.folioFiscal + ']" value="' + item.urlDescargaRI + '"/>' : '-') + '</td>' +
                        '<td class="text-center align-middle etpdf">' + (item.urlDescargaAcuse ? '<input type="checkbox" ' + checkedAcuse + ' name="acuse[' + item.folioFiscal + ']" value="' + item.urlDescargaAcuse + '"/>' : '-') + '</td>' +
                        '<td class="text-center align-middle">' + item.folioFiscal + '</td>' +
                        '<td class="text-center align-middle">' + item.receptorRfc + '</td>' +
                        '<td class="text-center align-middle">' + item.receptorNombre + '</td>' +
                        '<td class="text-center align-middle">' + item.fechaEmision + '</td>' +
                        '<td class="text-center align-middle">' + item.fechaCertificacion + '</td>' +
                        '<td class="text-center align-middle">' + item.total + '</td>' +
                        '<td class="text-center align-middle">' + item.efecto + '</td>' +
                        '<td class="text-center align-middle">' + item.estado + '</td>' +
                        '<td class="text-center align-middle">' + aprobacion + '</td>' +
                        '<td class="text-center align-middle">' + descargadoXml + '</td>' +
                        '<td class="text-center align-middle">' + descargadoPdf + '</td>' +
                        '<td class="text-center align-middle">' + descargadoAcuse + '</td>'
                        // + '<td class="text-center">' + item.pacCertifico + '</td>'
                        +
                        '</tr>';
                }

                tablaBody.html(html);
            }
            if (response.data && response.data.mensaje) {
                alert(response.data.mensaje);
            }
        }
    }).always(function() {
        enableInputs();
    });

    return false;
});

$('.descargaR-form').on('submit', function() {
    var form = $(this);
    var formData = new FormData(form.get(0));
    formData.append('sesion', window.sesionDM);
    var selA = document.getElementById("anio");
    var anio = selA.options[selA.selectedIndex].value;
    var selM = document.getElementById("mes");
    var mes = selM.options[selM.selectedIndex].value;
    var selD = document.getElementById("dia");
    var dia = selD.options[selD.selectedIndex].value;
    formData.append('anio', anio);
    formData.append('mes', mes);
    formData.append('dia', dia);

    disableInputs();
    $('#loading').show();

    $.post({
        url: "async",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
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
    }).always(function() {
        enableInputs();
        $('#loading').hide();
        var tablaBody = $('#tabla-recibidos tbody');
        tablaBody.empty();
    });

    return false;
});

$('.descargaE-form').on('submit', function() {
    var form = $(this);
    var formData = new FormData(form.get(0));
    formData.append('sesion', window.sesionDM);
    var sel_Ai = document.getElementById("anio-e1");
    var anio_i = sel_Ai.options[sel_Ai.selectedIndex].value;
    var sel_Mi = document.getElementById("mes-e1");
    var mes_i = sel_Mi.options[sel_Mi.selectedIndex].value;
    var sel_Di = document.getElementById("dia-e1");
    var dia_i = sel_Di.options[sel_Di.selectedIndex].value;
    var sel_Af = document.getElementById("anio-e2");
    var anio_f = sel_Af.options[sel_Af.selectedIndex].value;
    var sel_Mf = document.getElementById("mes-e2");
    var mes_f = sel_Mf.options[sel_Mf.selectedIndex].value;
    var sel_Df = document.getElementById("dia-e2");
    var dia_f = sel_Df.options[sel_Df.selectedIndex].value;
    formData.append('anio_i', anio_i);
    formData.append('mes_i', mes_i);
    formData.append('dia_i', dia_i);
    formData.append('anio_f', anio_f);
    formData.append('mes_f', mes_f);
    formData.append('dia_f', dia_f);

    disableInputs();
    $('#loadingE').show();

    $.post({
        url: "async",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
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
    }).always(function() {
        enableInputs();
        $('#loadingE').hide();
        var tablaBody = $('#tabla-emitidos tbody');
        tablaBody.empty();
    });

    return false;
});

$('#allxml').change(function() {
    $('tbody tr td[class="text-center align-middle txml"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

$('#allpdf').change(function() {
    $('tbody tr td[class="text-center align-middle tpdf"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

$('#eallxml').change(function() {
    $('tbody tr td[class="text-center align-middle etxml"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

$('#eallpdf').change(function() {
    $('tbody tr td[class="text-center align-middle etpdf"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

$('#allcheck').change(function() {
    $('tbody tr td[class="text-center align-middle allcheck"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    calcular();
});

$("#filtrar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".buscar tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

$(document).on('click keyup', '.mis-checkboxes,.mis-adicionales', function() {
    calcular();
});

function calcular() {
    var tot = $('#total');
    tot.val(0);
    $('.mis-checkboxes,.mis-adicionales').each(function() {
        if ($(this).hasClass('mis-checkboxes')) {
            tot.val(($(this).is(':checked') ? parseFloat($(this).attr('tu-attr-precio')) : 0) + parseFloat(tot.val()));
        } else {
            tot.val(parseFloat(tot.val()) + (isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val())));
        }
    });
    var totalParts = parseFloat(tot.val()).toFixed(2).split('.');
    tot.val('$' + totalParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.' + (totalParts.length > 1 ? totalParts[1] : '00'));
}

$('#alerta-archivo-si').on('click', function() {
    $("#alerta-archivo").hide();
    $('#form-editar').show();
    $("#doc-relacionados").show();
    return false;
});

$('#alerta-archivo-no').on('click', function() {
    $("#alerta-archivo").hide();
    $('#form-editar').show();
    $("#subir-archivo").hide();
    return false;
});

$('#vinct').on('click', function() {
    var total = document.getElementById("total").value
    var lenght = $('div.checkbox-group :checkbox:checked').length
    if (!lenght > 0) {
        alert('Favor de seleccionar al menos un CFDI.')
        return false;
    }
    if (total == 0) {
        alert('El valor total es 0, favor de seleccionar nuevamente los CFDI.')
        return false;
    }
});

$('#alerta-crear-si').on('click', function() {
    $("#alerta-crear").hide();
    $('#form-crear').show();
    return false;
});

$('#alerta-crear-no').on('click', function() {
    $("#alerta-crear").hide();
    return false;
});

$('#vinpbtn').on('click', function() {
    $("#vinp").show();
    $('input[id="vinpsub"]').show();
    $('th[id="vinp"]').show();
    $('td[id="vinp"]').show();
    return false;
});

$('#vinpsub').on('click', function() {
    var lenght = $('div.checkbox-group :checkbox:checked').length
    if (!lenght > 0) {
        alert('Favor de seleccionar al menos un proveedor.')
        return false;
    }
});

function alertaP(a, b, c) {
    var nl = "\r\n"
    var msg = ''
    if (b == 0) {
        msg += "- No tiene CFDI's vinculados.";
        msg += nl;
    }
    if (c == 0) {
        msg += "- No tiene pdf asociado.";
        msg += nl;
    }
    if (a == 0) {
        msg += "- Existe diferencia con el importe total.";
        msg += nl;
    }
    alert(msg);
}

function verAdicional(btn_id) {
    var ra = document.getElementById("ruta-adicionales").value;
    var vda = document.getElementById("docs-adicionales" + btn_id);
    var da = vda.options[vda.selectedIndex].value;
    var rutaArchivo = ra + da;
    window.open(rutaArchivo, '_blank');
}

// function submitBlock() {
//     $('#reg-cheque').on('submit', function() {
//         // $('#reg-cheque').attr('disabled', true);
//         alert('Hola');
//     });
// }

// function sortTable(n) {
//     var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
//     var n = 12;
//     table = document.getElementById("chequesTabla");
//     switching = true;
//     // Set the sorting direction to ascending:
//     dir = "asc";
//     /* Make a loop that will continue until
//     no switching has been done: */
//     while (switching) {
//         // Start by saying: no switching is done:
//         switching = false;
//         rows = table.rows;
//         /* Loop through all table rows (except the
//         first, which contains table headers): */
//         for (i = 1; i < (rows.length - 1); i++) {
//             // Start by saying there should be no switching:
//             shouldSwitch = false;
//             /* Get the two elements you want to compare,
//             one from current row and one from the next: */
//             x = rows[i].getElementsByTagName("TD")[n];
//             y = rows[i + 1].getElementsByTagName("TD")[n];
//             /* Check if the two rows should switch place,
//             based on the direction, asc or desc: */
//             if (dir == "asc") {
//                 if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
//                     // If so, mark as a switch and break the loop:
//                     shouldSwitch = true;
//                     break;
//                 }
//             } else if (dir == "desc") {
//                 if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
//                     // If so, mark as a switch and break the loop:
//                     shouldSwitch = true;
//                     break;
//                 }
//             }
//         }
//         if (shouldSwitch) {
//             /* If a switch has been marked, make the switch
//             and mark that a switch has been done: */
//             rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
//             switching = true;
//             // Each time a switch is done, increase this count by 1:
//             switchcount++;
//         } else {
//             /* If no switching has been done AND the direction is "asc",
//             set the direction to "desc" and run the while loop again. */
//             if (switchcount == 0 && dir == "asc") {
//                 dir = "desc";
//                 switching = true;
//             }
//         }
//     }
// }
