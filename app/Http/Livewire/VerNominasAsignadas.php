<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\MetadataE;
use App\Models\XmlE;
use Livewire\Component;

class VerNominasAsignadas extends Component
{

    public Cheques $asignadas;// enlasar al modelo cheques

    public $metadatos,
           $xmle;

    // array's

    public $arreglo=[],
           $foliosFiscales=[];

protected  $listeners=[

'refreshVerNominas' => '$refresh',

];

    public function render()
    {


       $this->metadatos= MetadataE::where('cheques_id',$this->asignadas->_id)
       ->where('estado','!=','Cancelado')
       ->get();

       foreach($this->metadatos as $metad){

        $this->foliosFiscales[]= $metad->folioFiscal;


       }



       $this->xmle=XmlE::
       whereIn('UUID',$this->foliosFiscales)


       ->get();





         return view('livewire.ver-nominas-asignadas');
    }
}
