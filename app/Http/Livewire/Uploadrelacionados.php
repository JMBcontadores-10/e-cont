<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Uploadrelacionados extends Component
{

    protected $listeners = ['refreshUpload' => 'render']; // listeners para refrescar el modal

       
   



    public function render()
    {
        return view('livewire.uploadrelacionados');
    }


    function refresh(){

        $this->emitTo('relacionados', 'refreshComponent');
        $this->emitTo( 'uploadrelacionados','refreshUpload');
    }
}
