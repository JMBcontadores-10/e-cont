$(document).ready(function() {
    //Marcar el modulo que es encuentra actualemtene
    //Vamos a obtener la localizacion actual
    let URLactual = window.location;
    //Lo convertimos es un arreglo para obtener el valor de
    let ArregloURL = URLactual.toString().split("/");
    //Le damos la clase activa
    $("#"+ArregloURL.pop()).addClass("active");
});
