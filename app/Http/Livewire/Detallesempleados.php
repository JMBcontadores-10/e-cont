<?php

namespace App\Http\Livewire;

use App\Models\XmlE;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Detallesempleados extends Component
{
    public
    $folio,
    $RFC,
    $fechaFinal,
    $anio;


    public function mount(){


        $this->anio=date("Y");

    }



    public function render()
    {



        $empleados=XmlE::
     where('Emisor.Rfc',$this->RFC)
        ->where('TipoDeComprobante','N')
        ->where('Serie', $this->anio)
       ->where('Folio',$this->folio)
       // ->select('Fecha','Complemento','Total')
       ->get();


        return view('livewire.detallesempleados',['colM'=>$empleados]);
    }
}
