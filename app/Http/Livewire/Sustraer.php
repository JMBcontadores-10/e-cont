<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;

class Sustraer extends Component
{


public $totalPagado,
       $importe,
       $fechaPago,
       $serie,
       $periodo,
       $totalrestante,
       $miId,
       $hola,
       $temporales=[];


/// metodo rules


protected function rules(){

    return [

        'importe'=>'required',


    ];
}

protected $listeners=[

'refreshSustraer' => '$refresh',
'recibeAsignar',
];



///functionload

public function load() {


    $this->hola="hola";
}

// metodo sustraer
public function sustraer($id)
{


    ///validar campos de formulario livewire
    $this->validate();


   $cheque= Cheques::where('_id', $id)->first();



  /// si existe el campo saldo, se deduce del saldo de
  ///lo contrario se toma el importe
  if(isset($cheque->saldo)){

    $total=$cheque->saldo - $this->importe;

  }else{  $total=$cheque->importecheque - $this->importe;}


//// actualizar o crear campo saldo si $total es mayor a 0
    if($total>0){
        Cheques::where('_id', $id)
        ->update([
            'saldo' => $total,

        ], ['upsert' => true]);

        //// saldos asignados registro
        // $saldos_asignados= Cheques::where('_id', $id)
        // ->update([
        //     'importes_asignados'=>[
        //        'nomina'.$this->fechaPago => [$this->importe],
        //     ]
        //     ]);

        $saldos_asignados= Cheques::where('_id', $id)
        ->update(['nomina'.$this->serie.$this->periodo => floatval( $this->importe)],['upsert'=>true]);

        $this->emitUp('asignacionCheck',$id);


    }else{
        Cheques::where('_id', $id)
        ->update([
            'saldo' => 0,
        ], ['upsert' => true]);


    //// saldos asignados registro
    $saldos_asignados= Cheques::where('_id', $id);
    $saldos_asignados->push('saldos_asignados'[ $id[$this->importe]]

);




    }

/////

///// limpiar campo importe
    $this->reset('importe');

}

///// recibeAsignar

public function recibeAsignar($id) {

$this->miId=$id;
}


 public function almacenar(){

$this->emitTo('asignar-cheque','almacenar',$this->miId ,$this->importe);

 }




    public function render()
    {



        return view('livewire.sustraer',[
        'totalPagado'=>$this->totalPagado,
        'fechaPago'=>$this->fechaPago,
          'serie'=>$this->serie
        ]);
    }
}
