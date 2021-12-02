$( document ).ready(function() {
    $('#exampleModalCenter').modal('toggle')
});



/*
//Funcion para obtrener la fecha de pago por js
function diaSemana() {
  var x = document.getElementById("fecha");
  let date = new Date(x.value.replace(/-+/g, '/'));
// Obtener semana , año, mes y dia selecionados para convertirlos a formato mx
  let options = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };

// Obtener el mes de la fecha que selecciona el usuario
  let optionM={

    month:'long'

  };

  var monthmx=(date.toLocaleDateString('es-MX', optionM));


alert(monthmx);



}// end diaSemana

*/

/*
//funcion simple para  desactivar seccion de pdf pago y relacionados
var tipo = document.getElementById('tipo');// se obtiene el select mediante getId
//se obtiene el valor del option con un listener
tipo.addEventListener('change',
  function(){
    var selectedOption = this.options[tipo.selectedIndex];
   var t=selectedOption.value; // se almacena en una variable


  // alert(t);

// se obtienen el o los div´s para hacer fadeOut
  var p= document.querySelector('#ComPago'); //


if(t =="Efectivo"){

    jQuery("#ComPago").removeAttr("required")
$("#pdfPago").fadeOut("slow");
$("#drop-zone").fadeOut("slow");

}
if (t != "Efectivo"){

//p.setAttribute("required", "");
p.setAttribute("required", "");
$("#pdfPago").fadeIn("slow");
$("#drop-zone").fadeIn("slow");

}

  });
*/

//#####  funcion para eliminar archivos pdf del array file-multiple ######

const dt = new DataTransfer(); // permite manejar los archivos del archivo de entrada

$("#attachment").on('change', function(e){
	for(var i = 0; i < this.files.length; i++){
		let fileBloc = $('<span/>', {class: 'file-block'}),
			 fileName = $('<span/>', {class: 'name', text: this.files.item(i).name});
		fileBloc.append('<span class="file-delete"><span>x</span></span>')
			.append(fileName);
		$("#filesList > #files-names").append(fileBloc);
	};
	// Agregar archivos al objeto DataTransfer
	for (let file of this.files) {
		dt.items.add(file);
	}
	// Actualización de los archivos del archivo de entrada después de la adición
	this.files = dt.files;

	// EventListener para el botón de eliminación creado
	$('span.file-delete').click(function(){
		let name = $(this).next('span.name').text();
		// Suprimir la visualización del nombre del archivo
		$(this).parent().remove();
		for(let i = 0; i < dt.items.length; i++){
			// Coincidencia de archivo y nombre
			if(name === dt.items[i].getAsFile().name){
				// Eliminar el archivo en el objeto DataTransfer
				dt.items.remove(i);
				continue;
			}
		}
		// Actualización de los archivos del archivo de entrada después de la eliminación
		document.getElementById('attachment').files = dt.files;
	});
});




/////////////////////









