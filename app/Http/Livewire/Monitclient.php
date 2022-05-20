<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Monitclient extends Component
{
    //Variables globales
    public $empresa;

    //Variable de consulta de los metadatos emitidos
    public $consulmetaporhora;

    //Variable de consulta de los metadatos emitidos
    public $consulmetaclient;

    public function render()
    {
        return view('livewire.monitclient');
    }
}
