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

// Muestra el login de contadores
function showLogin1() {
    $("#login1").show();
    $("#entrar").hide();
}

function inventario(){
     var invM = document.getElementById("invIniM").value;
     var coM = document.getElementById("comprasM").value;
     var veM = document.getElementById("ventasM").value;

     var result =  parseFloat(invM) + parseFloat(coM) - parseFloat(veM);
     document.getElementById("invDeterM").value = result;

     var invP = document.getElementById("invIniP").value;
     var coP = document.getElementById("comprasP").value;
     var veP = document.getElementById("ventasP").value;

     var result =  parseFloat(invP) + parseFloat(coP) - parseFloat(veP);
     document.getElementById("invDeterP").value = result;

     var invD = document.getElementById("invIniD").value;
     var coD = document.getElementById("comprasD").value;
     var veD = document.getElementById("ventasD").value;

     var result =  parseFloat(invD) + parseFloat(coD) - parseFloat(veD);
     document.getElementById("invDeterD").value = result;

    }

function merma(){
    var autoM = document.getElementById("autoM").value;
    var invDeterM = document.getElementById("invDeterM").value;

    var result =  parseFloat(invDeterM) - parseFloat(autoM);
    document.getElementById("mermaM").value = result;

    var autoP = document.getElementById("autoP").value;
    var invDeterP = document.getElementById("invDeterP").value;

    var result =  parseFloat(invDeterP) - parseFloat(autoP);
    document.getElementById("mermaP").value = result;

    var autoD = document.getElementById("autoD").value;
    var invDeterP = document.getElementById("invDeterD").value;

    var result =  parseFloat(invDeterD) - parseFloat(autoD);
    document.getElementById("mermaD").value = result;

}

function fun1(){
    if($('#user').val() == '3'){
        $('#function1').prop('disabled',true);
        $('#noDis1').html('No disponible');
        $('#function2').prop('disabled',true);
        $('#noDis2').html('No disponible');
        $('#function3').prop('disabled',true);
        $('#noDis3').html('No disponible');
        $('#function4').prop('disabled',true);
        $('#noDis4').html('No disponible');

    }
}

$(document).ready(function() {
    $('#chequesTabla tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Buscar palabra clave...'+title+'" />' );
    } );


    var table = $('#chequesTabla').DataTable({
"language": {
        search: 'Buscar:',
        "lengthMenu": "Mostrando _MENU_ registros por pagina",
        "zeroRecords": "Sin datos",
        "info": "Mostrando _PAGE_ de _PAGES_",
        "infoEmpty": "Sin registros",
        "infoFiltered": "(filtrados de _MAX_)",
paginate: {
    first: 'Primero',
    previous: 'Anterior',
    next: 'Siguiente',
    last: 'Último',
  }
    }
});
    table.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
} );


// Desahibilita los botones en descargas
function disableInputs() {
    $(':input[type="submit"]').prop('disabled', true);
    $('a[class="btn btn-primary excelR-export"]').addClass('disabled');
    $('a[class="btn btn-primary excelE-export"]').addClass('disabled');
    // $('a[class="btn btn-primary ml-2"]').addClass('disabled');
}

// Habilita los botones en descargas
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

// Exporta la tabla de descargas emitidas a excel
$('.excelR-export').on('click', function() {
    var $this = $(this);
    var table = $this.closest('.descargaR-form').find('.table').get(0);
    var fn = $this.attr('download');
    $this.attr('href', tableToExcel(table, fn));
    // window.location.href = tableToExcel(table, fn);
});

// Exporta la tabla de descargas recibidas a excel
$('.excelE-export').on('click', function() {
    var $this = $(this);
    var table = $this.closest('.descargaE-form').find('.table').get(0);
    var fn = $this.attr('download');
    $this.attr('href', tableToExcel(table, fn));
    // window.location.href = tableToExcel(table, fn);
});

// Genera el inicio de sesión al SAT desde Async en descargas
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

// Obtiene y genera la tabla de recibidos en descargas
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
                        '<td class="text-center align-middle txml">' + (item.urlDescargaXml ? '<input type="checkbox" ' + checkedXml + ' name="xml[' + item.folioFiscal + ']" value="' + item.urlDescargaXml + '"/>' : '-') + '</td>' +
                        '<td class="text-center align-middle tpdf">' + (item.urlDescargaRI ? '<input type="checkbox" ' + checkedPdf + ' name="ri[' + item.folioFiscal + ']" value="' + item.urlDescargaRI + '"/>' : '-') + '</td>' +
                        '<td class="text-center align-middle tpdf">' + (item.urlDescargaAcuse ? '<input type="checkbox" ' + checkedAcuse + ' name="acuse[' + item.folioFiscal + ']" value="' + item.urlDescargaAcuse + '"/>' : '-') + '</td>' +
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

// Obtiene y genera la tabla de emitidos en descargas
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

// Genera la descarga de recibidos a través de Async
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

// Genera la descarga de emitidos a través de Async
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

// Modifican los checkboxes cada vez que se seleccionan los encabezados de columna
$('#allxml').change(function() {
    $('tbody tr td[class="text-center align-middle txml"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

// Modifican los checkboxes cada vez que se seleccionan los encabezados de columna
$('#allpdf').change(function() {
    $('tbody tr td[class="text-center align-middle tpdf"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

// Modifican los checkboxes cada vez que se seleccionan los encabezados de columna
$('#eallxml').change(function() {
    $('tbody tr td[class="text-center align-middle etxml"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

// Modifican los checkboxes cada vez que se seleccionan los encabezados de columna
$('#eallpdf').change(function() {
    $('tbody tr td[class="text-center align-middle etpdf"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
});

// Modifican los checkboxes cada vez que se seleccionan los encabezados de columna y suma el monto de su respectivo valor
$('#allcheck').change(function() {
    $('tbody tr td[class="text-center align-middle allcheck"] input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    calcular();
});

// Filtra las tablas dependiendo su contenido

$("#filtrar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".buscar tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});
//se deshabilita la funcion  filtrar tabla



// Suma los valores de los checkboxes cada vez que son seleccionados
$(document).on('click keyup', '.mis-checkboxes,.mis-adicionales', function() {
    calcular();
});

// Suma los valores de las clases mis-checkboxes y mis-adicionales
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

// Muestra los input file en vincular cheque
$('#alerta-archivo-si').on('click', function() {
    $("#alerta-archivo").hide();
    $('#form-editar').show();
    $("#doc-relacionados").show();
    return false;
});

// Esconde los input file en vincular cheque
$('#alerta-archivo-no').on('click', function() {
    $("#alerta-archivo").hide();
    $('#form-editar').show();
    $("#subir-archivo").hide();
    return false;
});

// Evita vincular CFDIs si ninguno ha sido seleccionado o si existe error en la suma y el valor es 0
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

// Muestra el form de crear cheque en vincluar cheque
$('#alerta-crear-si').on('click', function() {
    $("#alerta-crear").hide();
    $('#form-crear').show();
    return false;
});

// Mantiene escondido el form de crear cheque en vincular cheque
$('#alerta-crear-no').on('click', function() {
    $("#alerta-crear").hide();
    return false;
});

// Muestra la columna de vinculación a varios proveedores en cuentas por pagar
$('#vinpbtn').on('click', function() {
    $("#vinp").show();
    $('input[id="vinpsub"]').show();
    $('th[id="vinp"]').show();
    $('td[id="vinp"]').show();
    return false;
});

// Muestra la columna de vinculación a varios proveedores en cuentas por pagar (NUEVO MODULO)
$("#BtnMoreProv").click(function(){
    var ColumMasProv = $(".VincVarProv");
    if(ColumMasProv.is(":visible")){
        $(".DesatallesProv").attr("disabled", true);
        ColumMasProv.hide();
    }else{
        $(".DesatallesProv").attr("disabled", false);
        ColumMasProv.show();
    }
});

// Evita vincular proveedores si ninguno ha sido seleccionado
$('#vinpsub').on('click', function() {
    var lenght = $('div.checkbox-group :checkbox:checked').length
    if (!lenght > 0) {
        alert('Favor de seleccionar al menos un proveedor.')
        return false;
    }
});

// Muestra en una alerta los pendientes de los cheques
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

// Permite abrir los documentos adicionales de cada cheque
function verAdicional(btn_id) {
    var ra = document.getElementById("ruta-adicionales").value;
    var vda = document.getElementById("docs-adicionales" + btn_id);
    var da = vda.options[vda.selectedIndex].value;
    var rutaArchivo = ra + da;
    window.open(rutaArchivo, '_blank');
}

// Permite abrir los documentos adicionales de cada cheque sin el input Select
function verAdicionales(id_button) {

    var ru = document.getElementById("rutaAdicional").value;
    var iden = document.getElementById("iden" + id_button).value;
    var ruta= ru + iden;



    window.open(ruta, '_blank');
}


// Evita registrar múltiples veces cualquier cheque al momento de su creación o actualización
function submitBlock() {
    $('#reg-cheque').hide()
    setTimeout("$('#reg-cheque').show()", 250);
}

//FilePond para los input de cuentas por pagar
//Para agregar PDF
function AddPDFChequeCFDI(id, rutaid){
    // registrar plugin validacion filepond  se deben agregar los cdn despues del body
    FilePond.registerPlugin(FilePondPluginFileValidateType);
    // registrar plugin validacion size filepond  se deben agregar los cdn despues del body
    FilePond.registerPlugin(FilePondPluginFileValidateSize);

    const token = document.querySelector('input[name="_token"]');
    const ruta = document.getElementById(rutaid);

    var filePondObj=FilePond.create(ruta, {        /// creacion con validacion de archivos
        maxFileSize: '1000KB',
        labelMaxFileSizeExceeded: 'El archivo debe pesar menos de 1MB / 1000KB',
        labelIdle:'Sube un archivo <span class="filepond--label-action"> Explorar </span>',
        labelFileLoading:'Cargando',
        labelFileProcessing:'Subiendo a E-cont..',
        labelFileProcessingComplete: 'Carga completa',
        labelFileProcessingAborted: 'Carga cancelada',
        labelTapToCancel: 'Presiona para cancelar',
        allowMultiple: false,
        //instantUpload: false,
        acceptedFileTypes: ["application/pdf"],
        fileValidateTypeDetectType: (source, type) =>
            new Promise((resolve, reject) => {
                // Do custom type detection here and return with promise
                resolve(type);
            }),

    });

    FilePond.setOptions({
        name:'nuevoCheque',
     server: {
           url:'uploadEdit2/'+id,
           headers:{
               'X-CSRF-TOKEN': token.value
            }
        }
    });
}

//Para agregar los relacionados
function AddRelChequeCFDI(id, rutaid){
    // registrar plugin validacion filepond  se deben agregar los cdn despues del body
    FilePond.registerPlugin(FilePondPluginFileValidateType);
    // registrar plugin validacion size filepond  se deben agregar los cdn despues del body
    FilePond.registerPlugin(FilePondPluginFileValidateSize);

    const token = document.querySelector('input[name="_token"]');
    const ruta = document.getElementById(rutaid);

    var filePondObj=FilePond.create(ruta, {        /// creacion con validacion de archivos
        maxFileSize: '1000KB',
        labelMaxFileSizeExceeded: 'El archivo debe pesar menos de 1MB / 1000KB',
        labelIdle:'Carga de archivos adicionales <span class="filepond--label-action"> Explorar </span>',
        labelFileLoading:'Cargando',
        labelFileProcessing:'Subiendo a E-cont..',
        labelFileProcessingComplete: 'Carga completa',
        labelFileProcessingAborted: 'Carga cancelada',
        labelTapToCancel: 'Presiona para cancelar',
        allowMultiple: true,
        //instantUpload: false,
        acceptedFileTypes: ["application/pdf"],
        fileValidateTypeDetectType: (source, type) =>
            new Promise((resolve, reject) => {
                // Do custom type detection here and return with promise

                resolve(type);
            }),
    });

    FilePond.setOptions({
        name:'adicionalesNuevoCheque',
     server: {
           url:'upload2/'+id,
           headers:{
               'X-CSRF-TOKEN': token.value
            }
        }
    });
}

function GuardarMovi(id, empresa){
    sessionStorage.setItem('idmovicheq', id);
    sessionStorage.setItem('empresacheq', empresa);
}