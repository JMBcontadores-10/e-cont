<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NuevoCheque extends Component
{


    protected $listeners = ['refreshUpload' => 'render' ]; // listeners para refrescar el modal
   

   



    public function render()
    {
        return view('livewire.nuevocheque');
    }


    function refreshh(){

        // 
        $this->emitUp('chequesRefresh');//actualiza la tabla cheques y transferencias
         $this->emitSelf('refreshUpload');

       
        // $this->emit('refreshUpload');
    }
}
