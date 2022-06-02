<?php

namespace App\Http\Livewire;

use App\Models\MetadataR;
use App\Models\XmlE;
use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(6000); //Tiempo limite dado 1 hora

class Monithora extends Component
{
    //Variables globales
    public $emitidos;
    public $empresa;

    public function render()
    {
        return view('livewire.monithora');
    }
}
