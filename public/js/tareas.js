//Funcion para mostrar el inout de frecuencia
$("#frecuente").change(function () {
      //Obtenemos el valor del select
      var frecuencia = $(this).val();

      //Condicional para mostrar el input de periodo
      if (frecuencia == "Si") {
            $("#periodogroup").attr('hidden', false);
      } else {
            $("#periodogroup").attr('hidden', true);
      }
});

//Funciones al cargar la pagina
$(document).ready(function () {
      //Recibimos el llamado y ejecutamos la funcion
      window.addEventListener('errortareas', event => {
            //Mostramos el mensaje de error
            $("#Mnserrorcolab").prop('hidden', false);
            $("#mnscolab").text(event.detail.error);

            //Desaparecemos el mensaje de error
            setTimeout(function () {
                  $("#Mnserrorcolab").prop('hidden', true);
            }, 2500)
      });

      //Recibimos el llamado y ejecutamos la funcion
      window.addEventListener('cerrartarea', event => {
            //Cerramos el modal
            $(".closetarea").click();
      });
});