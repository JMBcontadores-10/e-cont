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
                  canti.push(cantifactu.trim());
            });

            return canti;
      }

      //Obtendremos los datos de la tabla
      const ctx = document.getElementById('myChart').getContext('2d');
      let myChart = new Chart(ctx, {
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

      //Creamos una grafica
      function creategrafic() {
            //Condicional para revisar si existe el canvas
            if (myChart) {
                  //Si existe detruirlo para crear otro
                  myChart.destroy();
            }

            //Creamos otro canvas
            myChart = new Chart(ctx, {
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
      }

      window.addEventListener('cargagrafic', event => {
            creategrafic();
      })
});
