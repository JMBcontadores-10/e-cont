<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Ajuste extends ModalComponent
{

    public Cheques $ajusteCheque; // coneccion al model cheques
    public $ajuste;
    




    protected function rules(){

      
      
      return[  'ajuste' => 'required|numeric'
    ];
    }

    


    

    public function guardar(){

        $this->validate();

       $valor = floatval($this->ajuste);

    
        $data=[


            'ajuste' => $valor

        ];

        $this->ajusteCheque->update($data);

        $this->dispatchBrowserEvent('ajuste', []);

    }

    public function render()
    {
        return view('livewire.ajuste',['datos' =>  $this->ajusteCheque ]);
    }
}
