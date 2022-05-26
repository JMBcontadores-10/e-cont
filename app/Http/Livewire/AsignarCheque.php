<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\MetadataE;
use App\Models\MetadataR;
use App\Models\Notificaciones;
use App\Models\XmlE;
use DateTime;
use DateTimeZone;
use Livewire\Component;

class AsignarCheque extends Component

{


    public $asignarCheque; // coneccion al model cheques
    public $RFC,$fecha;
    public $chequesAsignados=[];
    public $condicion, $idNuevoCheque;

    public $cont=0;
    public $anio;





    protected $listeners = ['refreshRaya' => '$refresh' ]; // listeners para refrescar el modal


    public function mount(){



        $this->condicion='>';
        $this->idNuevoCheque=Null;
        $this->anio=date("Y");


    }


    public function asignarNuevo($cheques){


        $this->condicion=$cheques;

    }

    public function asignar(){

      $asignacion =XmlE::where('Emisor.Rfc',$this->RFC)
      ->where('Complemento.0.Nomina.FechaFinalPago',$this->fecha)
      ->where('Folio',$this->asignarCheque)
      ->where('Serie', $this->anio)
      ->get();

      foreach($asignacion as $a){


        $insert=MetadataE::where('folioFiscal',$a['UUID'])->first();
        $insert->push('cheques_id', $this->chequesAsignados);
       //$insert->unset('cheques_id');
      }






// $this->cont=count($this->chequesAsignados);



    }



    public function enviar($folio,$rfc,$fecha){




        $this->emitTo('agregarcheque','arreg',$folio,$rfc,$fecha);

    }







    public function render()
    {




                    // $TotalPagado=DB::Table('xmlemitidos')
                //  -> where('Emisor.Rfc',$this->rfcEmpresa)
                //  ->where('TipoDeComprobante','N')
                //  ->where('Serie', $this->anio)
                // ->where('Folio',$nom['Folio'])
                // // ->select('Fecha','Complemento','Total')
                // ->first();

         //Consulta de los cheques vinculados
         $Cheques = Cheques::
         where('rfc', $this->RFC)
         ->where('tipoopera','Nómina')
         ->orderBy('fecha','Desc')

         ->get();

        return view('livewire.asignar-cheque',['datos'=>$this->asignarCheque,'RFC'=>$this->RFC,'Cheques'=>$Cheques,'chequesAsig'=>$this->chequesAsignados,'fechaFinal'=>$this->fecha]);
    }
}
