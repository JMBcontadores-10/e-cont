<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\XmlE;
use Livewire\Component;

class ListaRaya extends Component
{


    public XmlE $raya; // coneccion al model cheques
    public $RFC;
    protected $listeners = ['refreshRaya' => '$refresh' ]; // listeners para refrescar el modal



    public function render()
    {
        return view('livewire.lista-raya',['datos'=>$this->raya,'RFC'=>$this->RFC]);
    }
}
