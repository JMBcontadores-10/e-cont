<?php

namespace App\Http\Livewire;

use App\Models\MetadataE;
use App\Models\XmlE;
use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(6000); //Tiempo limite dado 1 hora

class Monitdetaclient extends Component
{
    //Variables globales
    public $empresa;

    //Variable de consulta de los metadatos emitidos
    public $emitidos;

    //Variable que recibirla el RFC
    public $rfcreci;

    //Escuchamos el emit del componente padre
    protected $listeners = ['sendrfc'];

    //Metodo que recibimos el RFC del componente padre 
    public function sendrfc($rfc)
    {
        $this->rfcreci = $rfc;
    }

    public function render()
    {
        return view('livewire.monitdetaclient', []);
    }
}
