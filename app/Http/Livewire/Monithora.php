<?php

namespace App\Http\Livewire;

use App\Models\MetadataR;
use App\Models\XmlE;
use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos
set_time_limit(3600); //Tiempo limite dado 1 hora

class Monithora extends Component
{
    //Variables globales
    public $empresa;

    //Variable de consulta de los metadatos emitidos
    public $infofactumetaemit;

    //Variable de consulta de los XML emitidos
    public $infofactuxmlemit;

    public function render()
    {
        return view('livewire.monithora');
    }
}
