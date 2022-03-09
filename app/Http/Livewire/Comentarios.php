<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\Notificaciones;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Comentarios extends ModalComponent
{


    public Cheques $comentarioCheque; // coneccion al model cheques
    public $notificar;
    public $receptor;


    public function mount(){
#### se establece el recpetor del mensejaje dependiendo de la sesion
        if(auth()->user()->tipo){/// si el contador esta definido

            $this->receptor=$this->comentarioCheque->rfc;/// se obtiene el rfc de la coneccion al modelo que tiene el rfc de la empresa

    }else{


        $this->receptor="";
    }




    }

/////////////////////// funcion rules necesaria para validar datos en tiempo real
//////////////////////comparandolos con la base datos (siempre con livewire)
protected function rules(){

    return[

        'comentarioCheque.comentario' => ''


    ];
}


 public function guardar(){

if($this->notificar){

    $chequeC = Notificaciones::create([

        'emisorMensaje' =>auth()->user()->RFC,
        'receptorMensaje' => $this->receptor,
        'numcheque'=>$this->comentarioCheque->numcheque,
        'fecha' => date('Y-m-d') ,
        'read_at' => 0,
        'tipo'=> 'M',
        'cheques_id'=>$this->comentarioCheque->_id,


]);

}else{



}

$this->notificar='';

    $this->validate();

    $this->comentarioCheque->save();// guarda todos los campos
    $this->emitTo( 'chequesytransferencias','chequesRefresh');
 }


    public function render()
    {
        return view('livewire.comentarios', ['datos' =>  $this->comentarioCheque ]);
    }
}
