//Acciones al cerrar el modal de nueva tarea
$(".closetarea").click(function () {
      //Reiniciamos el formulario
      $('#NuevaTarea').trigger("reset");

      //Escondemos la seccion de frecuencia
      $(".periodogroup").attr("hidden", true);
});

//Accion para seleccionar el periodo
$("#periodo").change(function () {
      //Obtenemos el valor seleccionado
      var PeriodoSelect = $(this).val();

      //Switch para mostrar el filtro dependiendo el periodo
      switch (PeriodoSelect) {
            case "Semanal":
                  $("#diasemselect").prop('hidden', false);
                  $("#diamesselect").prop('hidden', true);
                  break;

            case "Mensual":
                  $("#diasemselect").prop('hidden', true);
                  $("#diamesselect").prop('hidden', false);
                  break;

            default:
                  $("#diasemselect").prop('hidden', true);
                  $("#diamesselect").prop('hidden', true);
                  break;
      }
});

//Accion para bloquear el input de fecha fin
$("#checkfrecnunca").click(function () {
      if ($(this).prop('checked') == true) {
            //Bloqueamos el boton
            $("#fechafintarea").prop('disabled', true);
      } else {
            //Bloqueamos el boton
            $("#fechafintarea").prop('disabled', false);
      }
});

//Exportar tablas a PDF y Excel
function exporttareasavanceexcel(fecha) {
      $('.tareaavance').tableExport({
            type: 'excel',
            fileName: 'E-cont avence tareas ' + fecha,
            preventInjection: false,
            mso: {
                  styles: ['background-color'],
            }
      });
}

//Funcion para mostrar el inout de frecuencia
$(".frecuente").change(function () {
      //Obtenemos el valor del select
      var frecuencia = $(this).val();

      //Condicional para mostrar el input de periodo
      if (frecuencia == "Si") {
            $(".periodogroup").attr('hidden', false);
            $('#periodo').prop("required", true);
      } else {
            $(".periodogroup").attr('hidden', true);
            $('#periodo').prop("required", false);
      }
});

//Accion para marcar los botones del checkbox
$(".checkday").click(function () {
      //Obtenemos el Id del input
      var id = $(this).attr('for');

      //Condicional para saber si esta chequeado el input
      if ($('#' + id).prop('checked') == false) {
            $(this).css({
                  'background': '#0075ff',
                  'color': '#ffffff'
            });
      } else {
            $(this).css({
                  'background': '#ebecef',
                  'color': '#475f7b'
            });
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

      //Recibimos el llamado y ejecutamos la funcion
      window.addEventListener('nuncafecha', event => {
            //Deshabilitamos el boton y marcamos el input
            $("#checkfrecnunca").prop("checked", true);
            $("#fechafintarea").prop("disabled", true);
      });
});