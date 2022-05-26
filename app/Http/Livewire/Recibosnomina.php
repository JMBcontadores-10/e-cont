<?php

namespace App\Http\Livewire;

use App\Models\XmlE;
use Livewire\Component;

class Recibosnomina extends Component
{

    public $folio; // coneccion al model cheques
    public $RFC;
    public $fecha;
    protected $listeners = ['refresNomina' => '$refresh' ]; // listeners para refrescar el modal


    public function eliminar($ruta,$Folio,$dirname,$fecha){




        /// elimina el pdf de la carpeta correspondiente


         unlink($ruta);

         rmdir($dirname);


         $this->dispatchBrowserEvent('cerrarNominamodal', ["IdCheque" => $Folio,"fecha"=>$fecha]);


        $this->emit('refresNomina');

        $this->emitTo('nominas','nominarefresh');



        //$this->dispatchBrowserEvent('pdf', []);




           }// fin funcion eliminar


public function refrescarNomina(){

    $this->emit('refresNomina');

}






    public function render()
    {



        return view('livewire.recibosnomina',['datos'=>$this->folio,'RFC'=>$this->RFC,'fecha'=>$this->fecha]);
    }
}
