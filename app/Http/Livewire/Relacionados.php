<?php

namespace App\Http\Livewire;


namespace App\Http\Livewire;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;


class Relacionados extends ModalComponent
{
    use WithFileUploads; // habilita la carga de archivos con live wire
 public Cheques $filesrelacionados; // coneccion al model cheques
 public $relacionados;
 public $relacionadosUp=[];// arreglo para guardar lo archivos pdf (select multiple)
public $user ='hola';

 protected $listeners = ['refreshComponent' => '$refresh']; // listeners para refrescar el modal




/////////////////////// funcion rules necesaria para validar datos en tiempo real
//////////////////////comparandolos con la base datos (siempre con livewire)
protected function rules(){

    return[

        'relacionadosUp' => ''


    ];
}

/////////////////////////////////////////////////////////////////
public function render()
{
    return view('livewire.relacionados', ['datos1' =>  $this->filesrelacionados ]);
}

///////////////////////////////////////////////////////////////////
// funcion para guardar los archivos relacionados y subirlos a la carpeta

public function guardar(){

    $dtz = new DateTimeZone("America/Mexico_City");
    $dt = new DateTime("now", $dtz);
    $rfc = Auth::user()->RFC;
    $anio = $dt->format('Y');
    $dateValue = strtotime($this->filesrelacionados->fecha);
    $mesfPago = date('m',$dateValue);
    $mesActual=date('m');
    $espa=new Cheques();
    //$espa->fecha_es($mes);
    $renameFile=$espa->fecha_es($mesActual).".pdf";


    $ruta="contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";
    $ruta2="/contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/";
    $ruta3="/contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";
    $rutaRelacionados="contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mesfPago)."/";



    $this->validate();// funcion predefinida por livwire para validacion de datos





    foreach ($this->relacionadosUp as $file) {// for each para leer el array /descomponerlo y guardar los archivos en la bd

         $cheque = Cheques::where('Id', $this->filesrelacionados->Id);
         $cheque->push('doc_relacionados',$espa->fecha_es($mesfPago)."/". $file->hashName());

        $file->store($rutaRelacionados, 'public2');// use se Storage:: para guardar los archivos en la carpeta


    }


    session()->flash('message');// crear session flash (se emite solo una ves por ejecucion) para activar el cerrado del Modal y el push alert


  //  $this->emitTo('relacionados', 'refreshComponent'); // emit para refrescar el modal una ves  se cierre



}

/////////////////////////////////////////////////////

/// funcion para eliminar los archivos relaciondados i
    public function eliminar($docs){


        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $dateValue = strtotime($this->filesrelacionados->fecha);
        $mesfPago = date('m',$dateValue);
        $mesActual=date('m');
        $espa=new Cheques();
        //$espa->fecha_es($mes);
        $renameFile=$espa->fecha_es($mesActual).".pdf";

        $rutaRelacionados="contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/Documentos_Relacionados/";

  $doc=$docs;


//$pushArchivos =array("foo", "bar", "hello", "world");
       $res =array("");

       if(count($this->filesrelacionados->doc_relacionados) == 1){


        $cheque = Cheques::where('Id', $this->filesrelacionados->Id);

        //$cheque->unset('doc_relacionados');
        $cheque->pull('doc_relacionados', $doc);



        $cheque->push('doc_relacionados', $res);


       }else{




        $cheque = Cheques::where('Id', $this->filesrelacionados->Id);

        //$cheque->unset('doc_relacionados');
        $cheque->pull('doc_relacionados', $doc);


        $path =$rutaRelacionados.$doc;

        Storage::disk('public2')->delete($path);

       }
 
      $this->emitTo('relacionados', 'refreshComponent'); // emit para refrescar el modal una ves  se cierre
 
      //$this->emit('reviewSectionRefresh'); 
      
      //$this->emitTo('chequesytransferencias', 'refreshCheques'); // emit para refrescar el modal una ves  se cierre
    }



 

/////////////////////////////////////////////////////////////


}
