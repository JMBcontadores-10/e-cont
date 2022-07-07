<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\MetadataE;
use App\Models\XmlE;
use Exception;
use Livewire\Component;
use PhpParser\Node\Stmt\TryCatch;

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


////// redireccionar a nominas
 public function IrNominas($fechaPago,$rfc){

    $dateValue = strtotime($fechaPago);//obtener la fecha
    $mesPago = date('m',$dateValue);// obtener el mes
    $anioPago= date('Y',$dateValue);// obtener el año

    session()->put('mes', $mesPago);
    session()->put('rfcnomina', $rfc);
    session()->put('anio',$anioPago);

    return redirect()->to('/nominas');

 }




    public function render()
    {


       $this->metadatos= MetadataE::where('cheques_id',$this->asignadas->_id)
       ->where('estado','!=','Cancelado')
       ->get();

       foreach($this->metadatos as $metad){

        $this->foliosFiscales[]= $metad->folioFiscal;


       }







         return view('livewire.ver-nominas-asignadas')
        //  ->with('xmle',$this->xmle)
         ;
    }
}
