<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Ajuste extends ModalComponent
{



    public Cheques $ajusteCheque; // coneccion al model cheques
    public $ajuste;




  protected $listeners = ['refreshAjuste'=>'$refresh', 'emitajuste'];


     public function emitajuste(){

        $this->emitTo( 'chequesytransferencias','chequesRefresh');

     }

    protected function rules(){



      return[  'ajuste' => 'required|numeric',

    ];
    }






    public function guardar(){

        $this->validate();

       $valor = floatval($this->ajuste);


        $data=[


            'ajuste' => $valor

        ];

        $this->ajusteCheque->update($data);// actuliza la base de datos con el campo recibido 'ajuste'

      //  $this->dispatchBrowserEvent('ajuste', []);// recarga la pagina mediante js checar chequesytranscontrol.js
      $this->emitTo( 'chequesytransferencias','chequesRefresh');
    }

    public function render()
    {
        return view('livewire.ajuste',['datos'=>$this->ajusteCheque]);
    }
}
