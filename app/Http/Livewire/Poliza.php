<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;

class Poliza extends Component
{

    public Cheques $polizaCheque; // coneccion al model cheques



/////////////////////// funcion rules necesaria para validar datos en tiempo real
//////////////////////comparandolos con la base datos (siempre con livewire)
protected function rules(){

    return[

        'polizaCheque.poliza' => 'required'


    ];
}




public function message(){

    return[
'poliza' =>'La poliza es requerida',

    ];
}


    public function guardar(){

        $this->validate();



        $data=[


            'conta' => 1,

        ];

        $this->polizaCheque->update($data);// actuliza la base de datos con el campo recibido 'ajuste'

        $this->polizaCheque->save();// guarda todos los campos
        $this->emitTo( 'chequesytransferencias','chequesRefresh');
        $this->dispatchBrowserEvent('cerrarPolizamodal', []);
     }

    public function render()
    {
        return view('livewire.poliza',['datos'=>$this->polizaCheque]);
    }
}
