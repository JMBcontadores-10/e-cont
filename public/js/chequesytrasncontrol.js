

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

/// enviar formulario por ajax para evitar la recarga de la pagina///


    $('#create-account-button').on('click', function(e) {
        $.ajaxSetup({
            headers: {'X-CSRF-Token':$("input[name=_token]").val() }
        });

        var dataString = $('#create-account-form').serialize();

        $.ajax({
            type: "POST",
            url:"/archivo-pagar",
            data: dataString,
            success: function(res){
                $('#respuesta').html(res);
            }
        });






        alert('Datos serializados: '+dataString);
    });




// Get a reference to the file input element
//const inputElement = document.getElementById("avatar");

// ================Filepond=========================== //

function filepond(id) {



// registrar plugin validacion filepond  se deben agregar los cdn despues del body 
FilePond.registerPlugin(FilePondPluginFileValidateType);
// registrar plugin validacion size filepond  se deben agregar los cdn despues del body
FilePond.registerPlugin(FilePondPluginFileValidateSize);

const token = document.querySelector('input[name="_token"]');
 var ID =id;

const ids= document.getElementById("user").value;

const iden = document.getElementById("avatar");
const ruta= iden;



// Create a FilePond instance
 //const pond = FilePond.create(ruta);// creacion simple de filepond
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
    name:'avatar',
 server: {

       url:'upload/'+ID,
       headers:{
           'X-CSRF-TOKEN': token.value

 }
 
}



});


}// fin funcion


function filepondEditCheque(id) {



    // registrar plugin validacion filepond  se deben agregar los cdn despues del body 
    FilePond.registerPlugin(FilePondPluginFileValidateType);
    // registrar plugin validacion size filepond  se deben agregar los cdn despues del body
    FilePond.registerPlugin(FilePondPluginFileValidateSize);
    
    const token = document.querySelector('input[name="_token"]');
     var ID =id;
    
    const ids= document.getElementById("user").value;
    
    const iden = document.getElementById("editCheque"+ID);
    const ruta= iden;
    
    
    
    // Create a FilePond instance
     //const pond = FilePond.create(ruta);// creacion simple de filepond
    var filePondObj=FilePond.create(ruta, {        /// creacion con validacion de archivos
        maxFileSize: '1000KB',
        labelMaxFileSizeExceeded: 'El archivo debe pesar menos de 1MB / 1000KB',
        labelIdle:'Carga automatica de pdf <span class="filepond--label-action"> Explorar </span>',
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
        name:'editCheque',
     server: {
    
           url:'uploadEdit/'+ID,
           headers:{
               'X-CSRF-TOKEN': token.value
    
     }
     
    }
    
    
    
    });
    
    
    
    //alert(token.value); ID
    
    
    }// fin funcion

//=====================FUNCION FADE DIV RELACIONADOS============================================//

function fade(){



   // alert(ids);


}

function updateDiv(id)
{


    ids="#"+id;

   // $(ids).fadeOut();
    //$( ids ).load(window.location.href + ids );

   //$("#relacionadosView").load(" #relacionadosView > *");
   //document.getElementById(ids).innerHTML = document.getElementById(ids).innerHTML ;

   //document.getElementById(ids).innerHTML.reload
 alert(ids);


}

function limpiar(){
   
   // alert('limpiar');

  $("#table_refresh").load(" #table_refresh > *");

  //$("#table_refresh").load(location.href + " #table_refresh");
    
  
}

window.addEventListener('say-goodbye', event => {
    location.reload();
});


window.addEventListener('pdf', event => {
   alert('click pdf');
});


window.addEventListener('ajuste', event => {
    location.reload();
});
