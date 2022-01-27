<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Pdfcheque extends ModalComponent
{

   
    public Cheques $pdfcheque; // coneccion al model cheques
    protected $listeners = ['refreshpdf' => '$refresh']; // listeners para refrescar el modal
   



protected function rules(){

    return [
        'name' => ''
    ];
}


    public function ver(){

       // $users = Cheques::where('id',$id)->first();
      //  $this->user_id = $id;
       // $this->name = $user->nombrec;
       // $this->email = $user->email;
       $this->dispatchBrowserEvent('pdf', []);

       echo"hola";
     
    }



    public function render()
    {

     
     
        return view('livewire.pdfcheque',['datos' =>  $this->pdfcheque ]);
    }


    public function eliminar($id){

        $cheque = Cheques::where('_id', $id);
       
 
        $rfc = Auth::user()->RFC;
       
        $anioo = strtotime($this->pdfcheque->fecha); // se obtiene fecha del cheque
        $mesPago1 = strtotime($this->pdfcheque->fecha); // se obtiene fecha del cheque
        $mes = date('m',$mesPago1);
        $anio = date('Y',$anioo);
        $espa=new Cheques();

        $path="contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/".$espa->fecha_es($mes)."/" .$this->pdfcheque->nombrec;

        $cheque->update([  // actualiza el campo nombrec a 0 
         'nombrec' => "0",
        ]);

 /// elimina el pdf de la carpeta correspondiente
 
 Storage::disk('public2')->delete($path);


 $this->emitTo( 'pdfcheque','refreshpdf');

 //session()->flash('flash');
 //$this->dispatchBrowserEvent('pdf', []);
 $this->emitTo( 'chequesytransferencias','chequesRefresh');
 $this->emitTo( 'pdfcheque','refreshpdf');

    }// fin funcion eliminar


    function refresh(){

        $this->emitTo('relacionados', 'refreshComponent');
        $this->emitTo( 'pdfcheque','refreshpdf');
    }



}
