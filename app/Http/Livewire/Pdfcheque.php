<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Pdfcheque extends Component
{

   
    public Cheques $pdfcheque; // coneccion al model cheques
    protected $listeners = ['refreshpdf' => '$refresh' ]; // listeners para refrescar el modal
   


protected function rules(){

    return [
       
        'pdfcheque._id'=> '',
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

     $valorOrigen=strtotime($this->pdfcheque->fecha);
        $anioOrigen= date('Y',$valorOrigen);
        $mesOrigen= date('m',$valorOrigen);

        $espa=new Cheques();
     
     
        return view('livewire.pdfcheque',['datos' =>  $this->pdfcheque, 'mes'=>$espa->fecha_es($mesOrigen) ]);
    }


    public function eliminar(){


        $this->validate();
          

        $cheque = Cheques::where('_id',$this->pdfcheque->id);
       
 
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

 

 $this->dispatchBrowserEvent('cerrarPdfmodal', ["IdCheque" => $this->pdfcheque->id]);


 
 

 $this->emitUp('chequesRefresh');//actualiza la tabla cheques y transferencias

 $this->emit('refreshpdf');


 
 //$this->dispatchBrowserEvent('pdf', []);
 



    }// fin funcion eliminar

public function refrecar(){

    $this->emitUp('chequesRefresh');//actualiza la tabla cheques y transferencias

    $this->emit('refreshpdf');

}





}
