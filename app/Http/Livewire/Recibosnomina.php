<?php

namespace App\Http\Livewire;

use App\Models\XmlE;
use Livewire\Component;

class Recibosnomina extends Component
{

    public XmlE $recibosNomina; // coneccion al model cheques
    public $RFC;
    protected $listeners = ['refreshRaya' => '$refresh' ]; // listeners para refrescar el modal

    public function render()
    {



        return view('livewire.recibosnomina',['datos'=>$this->recibosNomina,'RFC'=>$this->RFC]);
    }
}
