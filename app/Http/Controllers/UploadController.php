<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateTimeZone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;

// controlador para el plugin filepond
class UploadController extends Controller
{




    public function store(Request $r, $id){// recibe por request llos parametros del filepond
 //establecer hora y año para documento
 $dtz = new DateTimeZone("America/Mexico_City");
 $dt = new DateTime("now", $dtz);
 $hora=date('h:i:s A');
 $rfc = Auth::user()->RFC;
 $anio = $dt->format('Y');
 // id del cheuqe

        $ID=$id;


        if($r->hasFile('avatar')){/// se consulta hasFile 'avatar'
       $file=$r->file('avatar');
       $filename=$file->getClientOriginalName();// se obtine el nombre original de archivo
       $fileExtencion=$file->getClientOriginalExtension();// se obtine lña extension
       $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
       $rfc = Auth::user()->RFC;// obtencion del rfc del usuario logeado
       $dateValue = strtotime($cheque->fecha);// metodo strtotime() para fecha
       $mesfPago = date('m',$dateValue);// obtencion mes del cheque
    $aniocheque= date('Y',$dateValue);// obtencion mes del cheque
       $espa=new Cheques();// creacion del obejeto cheques para llamar a funcion fecha_es()
        //$espa->fecha_es($mes);
       $Mes=$espa->fecha_es($mesfPago);// funcion fecha_es para convetir mes int en mes español
//===============================================================================================//
     /// mes en el que el usuario sube los archivos relacionados
        $mesActual=date('m');
        $dia=date('d');
        $actual=$espa->fecha_es($mesActual);
//===============================================================================================//
$Id = $dt->format('Y\Hh\Mi\SsA');// obtener año y hora con segundos para evitar repetidos
$Id2= $dt->format('d');
$fn=preg_replace('/[^A-z0-9.-]+/', '', $filename);

        $renameFile=$Id2.$Mes.$Id."&".$fn;// renombra los archivos
        //ruta donde se almacenan los archivos
       $ruta ='contarappv1_descargas/'.$cheque->rfc."/".$aniocheque."/Cheques_Transferencias/Documentos_Relacionados/".$Mes."/";


if ($fileExtencion == "pdf"){

/// se guarda el documento en la ruta especifica con store / storeAs
// se guradan los documentos relacionados en la carpeta correspondiente al mes
       $file->storeAs($ruta, $renameFile,'public2');

    /// se guardan los enlases en la bd
    $cheque->pull('doc_relacionados', "");
 $cheque->push('doc_relacionados', $renameFile);
// $cheque->push('doc_relacionados', $renameFile);


      return "entro en zona de carga".$ID . "Extencion". $fileExtencion;
}else{

    return "no es un Pdf es un ".  dd($fileExtencion);

}

        }// fin if

return "no entro";


    }// fin funcion store



    public function store2(Request $r, $id){// recibe por request llos parametros del filepond
        //establecer hora y año para documento
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $hora=date('h:i:s A');
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        // id del cheuqe

               $ID=$id;


               if($r->hasFile('adicionalesNuevoCheque')){/// se consulta hasFile 'avatar'
              $file=$r->file('adicionalesNuevoCheque');
              $filename=$file->getClientOriginalName();// se obtine el nombre original de archivo
              $fileExtencion=$file->getClientOriginalExtension();// se obtine lña extension
              $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
              $rfc = Auth::user()->RFC;// obtencion del rfc del usuario logeado
              $dateValue = strtotime($cheque->fecha);// metodo strtotime() para fecha
              $mesfPago = date('m',$dateValue);// obtencion mes del cheque
           $aniocheque= date('Y',$dateValue);// obtencion mes del cheque
              $espa=new Cheques();// creacion del obejeto cheques para llamar a funcion fecha_es()
               //$espa->fecha_es($mes);
              $Mes=$espa->fecha_es($mesfPago);// funcion fecha_es para convetir mes int en mes español
       //===============================================================================================//
            /// mes en el que el usuario sube los archivos relacionados
               $mesActual=date('m');
               $dia=date('d');
               $actual=$espa->fecha_es($mesActual);
       //===============================================================================================//
       $Id = $dt->format('Y\Hh\Mi\SsA');// obtener año y hora con segundos para evitar repetidos
       $Id2= $dt->format('d');
       $fn=preg_replace('/[^A-z0-9.-]+/', '', $filename);

               $renameFile=$Id2.$Mes.$Id."&".$fn;// renombra los archivos
               //ruta donde se almacenan los archivos
              $ruta ='contarappv1_descargas/'.$cheque->rfc."/".$aniocheque."/Cheques_Transferencias/Documentos_Relacionados/".$Mes."/";


              if ($fileExtencion == "pdf"){

       /// se guarda el documento en la ruta especifica con store / storeAs
       // se guradan los documentos relacionados en la carpeta correspondiente al mes
              $file->storeAs($ruta, $renameFile,'public2');

           /// se guardan los enlases en la bd
           $cheque->pull('doc_relacionados', "");
        $cheque->push('doc_relacionados', $renameFile);
       // $cheque->push('doc_relacionados', $renameFile);


             return "entro en zona de carga".$ID;

            }else{

                return "no es un Pdf es un ".  dd($fileExtencion);

            }


               }// fin if

       return "no entro";


           }// fin funcion store










/*====================================================================== */

public function  storeEditPdf(Request $r, $id){

    $ID=$id;


    if($r->hasFile('editCheque')){/// se consulta hasFile 'editcheque'
        $file=$r->file('editCheque');
        $filename=$file->getClientOriginalName();// se obtine el nombre original de archivo
        $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);

        $rfc = Auth::user()->RFC;
        $Id = $dt->format('Y\Hh\Mi\SsA');// obtener año y hora con segundos para evitar repetidos
        $Id2= $dt->format('d');
        $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
        $anio = $dt->format('Y');
        $dateValue = strtotime($cheque->fecha);
        $anio = date('Y',$dateValue);
        $mesfPago=date('m',$dateValue);
        $mesActual=date('m');
        $espa=new Cheques();
        //$espa->fecha_es($mes);
        $renameFile=$Id2.$espa->fecha_es($mesActual).$Id."&".$nombreArchivo;


    $ruta="contarappv1_descargas/".$cheque->rfc."/".$anio."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";

    if($cheque->nombrec=="0"){


        // se guradan los documentos relacionados en la carpeta correspondiente al mes
       $file->storeAs($ruta, $renameFile,'public2');

       $cheque->update([  // actualiza el campo nombrec a 0
        'nombrec' => $renameFile,
       ]);



       return "entro en zona de carga".$ID."<br>".$ruta;
    }else{
        return "ya existe un archivo en la base";

    }

    }

}// fin funcion edit pdf





public function  storeEditPdf2(Request $r, $id){

    $ID=$id;


    if($r->hasFile('nuevoCheque')){/// se consulta hasFile 'editcheque'
        $file=$r->file('nuevoCheque');
        $filename=$file->getClientOriginalName();// se obtine el nombre original de archivo
        $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);

        $rfc = Auth::user()->RFC;
        $Id = $dt->format('Y\Hh\Mi\SsA');// obtener año y hora con segundos para evitar repetidos
        $Id2= $dt->format('d');
        $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
        $anio = $dt->format('Y');
        $dateValue = strtotime($cheque->fecha);
        $anio = date('Y',$dateValue);
        $mesfPago=date('m',$dateValue);
        $mesActual=date('m');
        $espa=new Cheques();
        //$espa->fecha_es($mes);
        $renameFile=$Id2.$espa->fecha_es($mesActual).$Id."&".$nombreArchivo;


    $ruta="contarappv1_descargas/".$cheque->rfc."/".$anio."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";


    if($cheque->nombrec=="0"){

        // se guradan los documentos relacionados en la carpeta correspondiente al mes
       $file->storeAs($ruta, $renameFile,'public2');

       $cheque->update([  // actualiza el campo nombrec a 0
        'nombrec' => $renameFile,
       ]);



       return "entro en zona de carga".$ID."<br>".$ruta;

    }else{

        return "ya existe un archivo en la base";
    }


    }

}// fin funcion edit pdf



}// fin clase
