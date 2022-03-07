$(document).ready(function() {
    //Con la variable de session que obtiene lo modulos vamos a seleccionarlo en el menu
    var ModuloSelect =  sessionStorage.getItem("Modulo");

    //Vamos a hacer una condicion donde si es el valor de inicio se quite la clase active
    switch(ModuloSelect){
        case "Inicio":
            //Removemos la clase active en todos las opciones del menu
            $(".menu-content li").removeClass("active");
            //Vaciamos la variable de session
            break;
        default:
            //Removemos la clase active en todos las opciones del menu
            $(".menu-content li").removeClass("active");
            //Vamos a agregarle la clase perteneciente al modulo activo
            $("#"+ModuloSelect).addClass("active");
            //Vaciamos la variable de session
            break;
    }
});

//Vamos a almacenar el valor de ID del boton al que vamos a acceder
$(".main-menu li").click(function() {
    var IdModulo = $(this).attr("id");
    //Condicional para identificar los modulos en construccion
    if(IdModulo != "Contruccion"){
        //Vamos a guardar el id de cada modulo en una variable de session
        sessionStorage.setItem("Modulo", IdModulo);
    }
})
