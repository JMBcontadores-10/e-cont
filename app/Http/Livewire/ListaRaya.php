<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\XmlE;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ListaRaya extends Component
{


    public  $folio; // coneccion al model cheques4
    public $fecha;
    public $RFC;
    public $ruta;
    protected $listeners = ['refreshRaya' => '$refresh' ]; // listeners para refrescar el modal



    // public function mount(XmlE $raya)
    // {
    //   // in case you are reteriving a singal record (show or edit method for example)
    //   $this->raya= $raya;

    // }


    public function eliminar($ruta,$Folio,$dirname,$fecha){




 /// elimina el pdf de la carpeta correspondiente


  unlink($ruta);

  rmdir($dirname);


  $this->dispatchBrowserEvent('cerrarRayamodal', ["IdCheque" => $Folio,"fecha"=>$fecha]);





 $this->emit('refreshRaya');

 $this->emitTo('nominas','nominarefresh');



 //$this->dispatchBrowserEvent('pdf', []);




    }// fin funcion eliminar



    public function refreshRaya(){

        $this->emit('refreshRaya');

    }



    public function render()
    {
        return view('livewire.lista-raya',['datos'=>$this->folio,'RFC'=>$this->RFC,'fecha'=>$this->fecha, 'ruta'=>$this->ruta]);
    }
}
