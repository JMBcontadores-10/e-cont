<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\MetadataR;
use Livewire\Component;

use App\Exports\FacturasExport;
use Maatwebsite\Excel\Facades\Excel;

class FacturasVinculadas extends Component
{



    public $checkedDesvincular=[];
    public float  $total=0;

    public Cheques $facturaVinculada;/// modelo

public function mount(){



}



    public function render()
    {



if($this->checkedDesvincular){

  $this->total=1;

}else{

    $this->total=0;
}

        $colM =MetadataR::where(['cheques_id' => $this->facturaVinculada->_id])->get();



        return view('livewire.facturas-vinculadas',['colM'=>$colM,'datos'=>$this->facturaVinculada,'total'=>$this->total]);
    }

public function desvincular(){

    $nXml=0;
// Revisa todos los UUID de los CFDI seleccionados y elimina la vinculación con cheques
foreach ($this->checkedDesvincular as $i) {





$xml_r=MetadataR::where('folioFiscal', $i)->first(); ///consulta a metadata_r
$cheques=Cheques::where('_id',$xml_r->cheques_id)->first();///consulta cheques


//// actualiza el importe descontando el importe del cheque del metadata_r
$cheques->update(['importexml'=> $cheques->importexml-$xml_r->total]);
/// actualiza el contador faltaxml descontando cada factura
$cheques->update(['faltaxml'=> $cheques->faltaxml-1]);
///  desvincula las facturas
MetadataR::where('folioFiscal', $i)
->update([
    'cheques_id' => null,
]);

$this->checkedDesvincular=[];/// reset array para evitar conflicto



}


if($cheques->faltaxml==0){

    $this->dispatchBrowserEvent('cerrarFacturas', []);// cierra el modal si ya no hay facturas
}


$this->emitTo( 'chequesytransferencias','chequesRefresh');//actualiza la tabla cheques y transferencias



// // Actualiza el monto y cantidad de CFDIs desvinculados para actualizar la colección cheques
// $totalXml = $this->facturaVinculada->totalxml;
// $totalXml = substr($totalXml, 1);
// $totalXml = (float)str_replace(',', '', $totalXml);
// $cheque_tXml = Cheques::find($cheques_id);
// $importeXml = $cheque_tXml->importexml - $totalXml;
// $faltaxml = $cheque_tXml->faltaxml - $nXml;
// $cheque_tXml->update([
//     'importexml' => $importeXml,
//     'faltaxml' => $faltaxml,
// ]);



}



public function export($facturas){
    $cheque =Cheques::where(['_id' => $facturas])->first();
    return Excel::download(new FacturasExport($facturas), $cheque->numcheque.'FacturasVinculadas.xlsx');
}





}
