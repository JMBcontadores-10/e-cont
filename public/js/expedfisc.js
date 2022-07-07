$(document).ready(function () {
      //Ejecutamos la funcion de convercion del input
      FilePondAcuseExped('acuse', '');

      //Recibimos el llamado y ejecutamos la funcion
      window.addEventListener('inputfilepond', event => {
            FilePondAcuseExped('acuse', event.detail.idacuse);
            $(".b").show();
      });

      //Recibimos el llamado y ejecutamos la funcion
      window.addEventListener('showcomplemes', event => {
            $("input[mescomple='" + event.detail.mes + "']").prop("checked", true);
            $("." + event.detail.mes).show();
      });

      //Recibimos el llamado y ejecutamos la funcion
      window.addEventListener('noclosecomple', event => {
            //Mostramos la columna de complementarios seleccionada
            if (event.detail.TipoComp != "") {
                  //Condicional para saber que tipo de complementario selecciono
                  if (event.detail.TipoComp === "A" || event.detail.TipoComp === "C") {
                        //Mostramos la columna de complementarios seleccionada
                        $('.ComplementCA' + event.detail.Mes).show();

                        //Marcamos el checkbox de complementarios
                        $("input[mescomple='ComplementCA" + event.detail.Mes + "']").prop("checked", true);
                  } else if (event.detail.TipoComp === "B" || event.detail.TipoComp === "D") {
                        //Mostramos la columna de complementarios seleccionada
                        $('.ComplementCA' + event.detail.Mes).show(); //Abrimos el primero de los complementarios
                        $('.ComplementBD' + event.detail.Mes).show(); //Abrimos el segundo de los complementarios

                        //Marcamos el checkbox de complementarios
                        $("input[mescomple='ComplementCA" + event.detail.Mes + "']").prop("checked", true);
                        $("input[mescomple='ComplementBD" + event.detail.Mes + "']").prop("checked", true);
                  }
            }
      });

      //Boton para convertir el input en filepond
      $(".selectfecha").click(function () {
            //Ejecutamos la funcion de convercion del input
            FilePondAcuseExped('acuse', '');

            //Escondemos el contenido de los archivos
            $(".wrapper").html('<div class="TxtNoArchivos"><h4>No hay archivo</h4></div>');
            $(".b").hide();
      });
});