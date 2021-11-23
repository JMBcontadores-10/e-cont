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


//funciion simple para  desactivar seccion de pdf pago y relacionados
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
$("#pdfRelacionados").fadeOut("slow");

}
if (t != "Efectivo"){

//p.setAttribute("required", "");
$("#pdfPago").fadeIn("slow");
$("#pdfRelacionados").fadeIn("slow");

}

  });


//#####  funcion para eliminar archivos pdf del array file-multiple ######

const dt = new DataTransfer(); // Permet de manipuler les fichiers de l'input file

$("#attachment").on('change', function(e){
	for(var i = 0; i < this.files.length; i++){
		let fileBloc = $('<span/>', {class: 'file-block'}),
			 fileName = $('<span/>', {class: 'name', text: this.files.item(i).name});
		fileBloc.append('<span class="file-delete"><span>+</span></span>')
			.append(fileName);
		$("#filesList > #files-names").append(fileBloc);
	};
	// Ajout des fichiers dans l'objet DataTransfer
	for (let file of this.files) {
		dt.items.add(file);
	}
	// Mise à jour des fichiers de l'input file après ajout
	this.files = dt.files;

	// EventListener pour le bouton de suppression créé
	$('span.file-delete').click(function(){
		let name = $(this).next('span.name').text();
		// Supprimer l'affichage du nom de fichier
		$(this).parent().remove();
		for(let i = 0; i < dt.items.length; i++){
			// Correspondance du fichier et du nom
			if(name === dt.items[i].getAsFile().name){
				// Suppression du fichier dans l'objet DataTransfer
				dt.items.remove(i);
				continue;
			}
		}
		// Mise à jour des fichiers de l'input file après suppression
		document.getElementById('attachment').files = dt.files;
	});
});
