<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Comentarios extends ModalComponent
{


    public Cheques $comentarioCheque; // coneccion al model cheques



/////////////////////// funcion rules necesaria para validar datos en tiempo real
//////////////////////comparandolos con la base datos (siempre con livewire)
protected function rules(){

    return[

        'comentarioCheque.comentario' => ''


    ];
}


 public function guardar(){

    $this->validate();

    $this->comentarioCheque->save();// guarda todos los campos

 }


    public function render()
    {
        return view('livewire.comentarios', ['datos' =>  $this->comentarioCheque ]);
    }
}
