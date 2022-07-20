<?php

namespace App\Http\Livewire;

use App\Models\MetadataE;
use App\Models\XmlE;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Detallesempleados extends Component
{
    public
    $folio,
    $RFC,
    $fechaFinal,
    $anio,
    $tipoNomina;




public function deducciones($i,$uuid,$tipoNomina =null){

    switch ($i) {
        case "FD":


            $deduccion= XmlE::where(['UUID' => $uuid])
            ->where([
                "Complemento.0.Nomina.Deducciones.Deduccion" =>
                [
                    '$elemMatch' =>
                    [
                        "TipoDeduccion" =>"009"
                    ]
                    ]
            ])->first();

            if($deduccion!=NULL){

            foreach($deduccion['Complemento.0.Nomina.Deducciones.Deduccion'] as $d){
                if ($d['TipoDeduccion']=="009")

                return $d['Importe'];


        }

    }else{   return "-";}


    break;

        case "ISR":
            $deduccion= XmlE::where(['UUID' => $uuid])
            ->where([
                "Complemento.0.Nomina.Deducciones.Deduccion" =>
                [
                    '$elemMatch' =>
                    [
                        "TipoDeduccion" =>"002"
                    ]
                    ]
            ])->first();

            if($deduccion!=NULL){

            foreach($deduccion['Complemento.0.Nomina.Deducciones.Deduccion'] as $d){


                if($d['TipoDeduccion']=="002" && $tipoNomina == "E"){


                    return $d['Importe'];

                }elseif ($d['TipoDeduccion']=="002" && $d['Concepto'] == "ISR mes"){

                return $d['Importe'];
                }


        }

    }else{   return "-";}



            break;







    }

// return $i;
}


    public function render()
    {

       $metadata=MetadataE::
       where('emisorRfc',$this->RFC)
       ->where('estado','!=','Cancelado')
     ->where('efecto','Nómina')
       ->select('folioFiscal')
      ->project(['_id' => 0])
       ->get();

foreach($metadata as $m){

$cont[]=$m->folioFiscal;

}



        $empleados=XmlE::
          whereIn('UUID',$cont)
         ->where('Emisor.Rfc',$this->RFC)
        ->where('TipoDeComprobante','N')
        ->where('Complemento.0.Nomina.TipoNomina',$this->tipoNomina)
        ->where('Serie', $this->anio)
       ->where('Folio',$this->folio)
       // ->select('Fecha','Complemento','Total')
       ->get();


        return view('livewire.detallesempleados',['colM'=>$empleados,'anio'=>$this->anio, 'meta'=>$metadata ]);
    }
}
