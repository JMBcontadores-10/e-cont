<?php

namespace App\Http\Livewire;

use App\Models\MetadataE;
use App\Models\XmlE;
use Livewire\Component;

class Monitdetaclient extends Component
{
    //Variables globales
    public $empresa;

    //Variable de consulta de los metadatos emitidos
    public $consulmetaporhora;

    //Variable de consulta de los metadatos emitidos
    public $consulmetaclient;

    //Variable de consulta de los XML emitidos
    public $consulxmlporhora;

    public function render()
    {
        return view('livewire.monitdetaclient');
    }
}
