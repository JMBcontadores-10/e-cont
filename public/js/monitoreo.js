//Variables globales
var DiaMes = 0;
let ChartHora;
let ChartMesMonto;
let ChartMesCanti;

//Exportacion de la tabla de facturacion por clientes (Excel)
function exportReportToExcel(empresa) {
      $('#tableclient').tableExport({
            exportHiddenCells: true,
            type: 'excel',
            fileName: empresa,
            ignoreColumn: [4], //Quitamos la ultima columna de destalles
            mso: {
                  fileFormat:'xlsx',
                  styles: ['background-color'],
            }
      });
}

//Exportacion de la tabla de facturacion por clientes (PDF)
function exportReportToPdf(empresa) {
      $('#tableclient').tableExport({
            exportHiddenCells: true,
            fileName: empresa,
            ignoreColumn: [4], //Quitamos la ultima columna de destalles
            type: 'pdf',
            pdfmake: {
                  enabled: true,
                  docDefinition: { pageOrientation: 'landscape' }
            }
      });
}

//Exportacion por de la tabla de facturacion por hora o cliente (Excel)
function ExportHoraClientExcel(idempre, empresa) {
      $('#' + idempre).tableExport({
            fileName: empresa,
            ignoreColumn: [13], //Quitamos la ultima columna de destalles
            type: 'excel',
            mso: {
                  fileFormat:'xlsx',
                  styles: ['background-color'],
            }
      });
}

//Exportacion por de la tabla de facturacion por hora o cliente (PDF)
function ExportHoraClientPDF(idempre, empresa) {
      $('#' + idempre).tableExport({
            fileName: empresa,
            ignoreColumn: [13],  //Quitamos la ultima columna de destalles
            type: 'pdf',
            pdfmake: {
                  enabled: true,
                  docDefinition: {
                        pageSize: 'A1',
                        pageOrientation: 'landscape'
                  }
            }
      });
}

//Funcion para limpiar la tabla de detalle clientes
function CleanTableClient() {
      $('.detaclientbody').remove();
      $('#totaldetaclient').text("0.00");
}

$(document).ready(function () {
      //Variables globales
      var dias = [];
      for (var i = 0; i <= 23; i++) {
            dias.push(i);
      }

      //Obtenemos los datos de la tabla
      function FactuHistori() {
            //Variable que contendra los datos de la tabla
            var canti = [];

            $('#factuhisto tbody tr').each(function () {
                  var cantifactu = $(this).find('td:eq(1)').text();
                  var Alerta = $(this).find('td:eq(0)').text().trim(); //Para no usar lo valores totales al graficar
                  if (Alerta != 'Total:') {
                        canti.push(cantifactu.trim());
                  }
            });

            return canti;
      }

      //Creamos el canvas de la grafica
      const ctxhora = document.getElementById('cantifactuhora').getContext('2d');

      ChartHora = new Chart(ctxhora, {
            data: {
                  labels: dias,
                  datasets: [{
                        type: 'bar',
                        label: '# de facturas',
                        data: FactuHistori(),
                        backgroundColor: [
                              'rgba(54, 162, 235, 0.2)',
                        ],
                        borderColor: [
                              'rgba(54, 162, 235, 1)',
                        ],
                        borderWidth: 1
                  },
                  {
                        type: 'line',
                        label: '# de facturas',
                        data: FactuHistori(),
                        backgroundColor: [
                              '#8494a733',
                        ],
                        borderColor: [
                              '#8494a7',
                        ],
                        borderWidth: 1
                  }]
            },
            options: {
                  scales: {
                        y: {
                              beginAtZero: true
                        }
                  }
            }
      });

      //Creamos las graficas
      function creategrafic() {
            //Vamos a obtener dia y mes para conocer el total de dias
            var MesSelect = $("#MesSelectFactu").val();
            var AnioSelect = $("#AnioSelectFactu").val();

            //Obtenemos el total de dias
            TotalDia = new Date(AnioSelect, MesSelect, 0).getDate();

            //Metemos los dias dentro de un arreglo
            var diasmes = [];
            for (var i = 1; i <= TotalDia; i++) {
                  diasmes.push(i);
            }

            //Facturacion por hora

            //Condicional para revisar si existe el canvas
            if (ChartHora) {
                  //Si existe detruirlo para crear otro
                  ChartHora.destroy();
            }

            //Creamos otro canvas
            ChartHora = new Chart(ctxhora, {
                  type: 'bar',
                  data: {
                        labels: dias,
                        datasets: [{
                              type: 'bar',
                              label: '# de facturas',
                              data: FactuHistori(),
                              backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)',
                              ],
                              borderColor: [
                                    'rgba(54, 162, 235, 1)',
                              ],
                              borderWidth: 1
                        },
                        {
                              type: 'line',
                              label: '# de facturas',
                              data: FactuHistori(),
                              backgroundColor: [
                                    '#8494a733',
                              ],
                              borderColor: [
                                    '#8494a7',
                              ],
                              borderWidth: 1
                        }
                        ]
                  },
                  options: {
                        scales: {
                              y: {
                                    beginAtZero: true
                              }
                        }
                  }
            });

            //Facturacion por mes (Monto)

            //Variable que contendra los datos de la tabla (Monto)
            var mesmonto = [];

            $('#factumes tbody tr').each(function () {
                  var montofactumes = $(this).find('td:eq(3)').text();
                  var Alerta = $(this).find('td:eq(0)').text().trim(); //Para no usar lo valores totales al graficar
                  if (Alerta != 'Total:') {
                        mesmonto.push(montofactumes.trim());
                  }
            });

            //Condicional para revisar si existe el canvas
            if (ChartMesMonto) {
                  //Si existe detruirlo para crear otro
                  ChartMesMonto.destroy();
            }

            //Creamos el canvas de la grafica
            const ctxmesmonto = document.getElementById('cantifactumesmonto').getContext('2d');

            //Creamos otro canvas
            ChartMesMonto = new Chart(ctxmesmonto, {
                  type: 'bar',
                  data: {
                        labels: diasmes,
                        datasets: [
                              {
                                    type: 'line',
                                    label: '$ de facturas',
                                    data: mesmonto,
                                    backgroundColor: [
                                          '#8494a733',
                                    ],
                                    borderColor: [
                                          '#8494a7',
                                    ],
                                    borderWidth: 1
                              },
                              {
                                    type: 'bar',
                                    label: '$ de facturas',
                                    data: mesmonto,
                                    backgroundColor: [
                                          'rgba(54, 162, 235, 0.2)',
                                    ],
                                    borderColor: [
                                          'rgba(54, 162, 235, 1)',
                                    ],
                                    borderWidth: 1
                              }
                        ]
                  },
                  options: {
                        scales: {
                              y: {
                                    beginAtZero: true
                              }
                        }
                  }
            });


            //Facturacion por mes (Cantidad)

            //Variable que contendra los datos de la tabla (Cantidad)
            var cantimescanti = [];

            $('#factumes tbody tr').each(function () {
                  var montofactumes = $(this).find('td:eq(1)').text();
                  var Alerta = $(this).find('td:eq(0)').text().trim(); //Para no usar lo valores totales al graficar
                  if (Alerta != 'Total:') {
                        cantimescanti.push(montofactumes.trim());
                  }
            });

            //Condicional para revisar si existe el canvas
            if (ChartMesCanti) {
                  //Si existe detruirlo para crear otro
                  ChartMesCanti.destroy();
            }

            //Creamos el canvas de la grafica
            const ctxmescanti = document.getElementById('cantifactumescanti').getContext('2d');

            //Creamos otro canvas
            ChartMesCanti = new Chart(ctxmescanti, {
                  type: 'bar',
                  data: {
                        labels: diasmes,
                        datasets: [
                              {
                                    type: 'line',
                                    label: '# de facturas',
                                    data: cantimescanti,
                                    backgroundColor: [
                                          '#8494a733',
                                    ],
                                    borderColor: [
                                          '#8494a7',
                                    ],
                                    borderWidth: 1
                              },
                              {
                                    type: 'bar',
                                    label: '# de facturas',
                                    data: cantimescanti,
                                    backgroundColor: [
                                          'rgba(54, 162, 235, 0.2)',
                                    ],
                                    borderColor: [
                                          'rgba(54, 162, 235, 1)',
                                    ],
                                    borderWidth: 1
                              }
                        ]
                  },
                  options: {
                        scales: {
                              y: {
                                    beginAtZero: true
                              }
                        }
                  }
            });
      }

      //Funcion para ejecutar acciones al precionar el boton de buscar
      window.addEventListener('cargagrafic', event => {
            //Obtenemos el total de dias
            var totaldias = event.detail.dias;

            //Condicional para saber si esta en el rango de dias permitido
            if (totaldias > 31) {
                  $("#mnsexcep").text(
                        "Lo sentimos el rango m??ximo a seleccionar son 31 d??as");
                  $("#Mnstotaldias").prop("hidden", false);
                  setTimeout(function () {
                        $("#Mnstotaldias").prop("hidden", true);
                  }, 2500);
            }

            //Obtenemos el total de inconsistencias
            var totalinconsis = $("#TxtInconsis").val();

            //Enviamos el numero de iniconsistencias (Se mostrara el contador cuando exista una inconsitencias)
            if (totalinconsis > 0) {
                  $("#numinconsis").text(totalinconsis);
            } else {
                  $(".badge").hide();
            }

            //Funcion para crear los graficos
            creategrafic();
      });
});
