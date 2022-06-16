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
      });

      //Recibimos el llamado y ejecutamos la funcion
      window.addEventListener('addcomplement', event => {
            if (event.detail.idfecha != "0") {
                  $("#" + event.detail.idfecha).attr('hidden', false);
            } else {
                  //Desmarcamos los checkboxs
                  $(".Complemen").prop('checked', false);

                  //Escondemos la fila de complementarios
                  $(".Complement").attr('hidden', true);
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

      //Escondemos los complementarios al seleccionar una empresa
      $(".select").change(function () {
            $(".Complement").attr('hidden', true); //Escondemos la fila de complementarios
            $(".Complemen").prop('checked', false); //Desmarcamos los checkboxs
      });
});