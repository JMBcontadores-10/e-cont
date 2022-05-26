<?
namespace App\Http\Controllers;



class Email extends Controller
{



    public function email(){

        $destinatario = "pepito@desarrolloweb.com";
        $asunto = "Este mensaje es de prueba";
        $cuerpo = 'hola ';
        mail($destinatario,$asunto,$cuerpo);
        echo "mail";


    }



}
