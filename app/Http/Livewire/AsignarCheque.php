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
use Psy\Command\WhereamiCommand;

class AsignarCheque extends Component

{


    public $asignarCheque;
    public $RFC, $fecha, $fechaPago,$serie,$cheques_asociados;
    public $chequesAsignados,
           $chequesVinculados;
    public $condicion,
     $idNuevoCheque,
     $content,
     $granTotal,
     $folioFiscal,
     $TotalPagado,
     $nominas,
     $mes,
     $xs,
     $nomina,
     $search,
     $tipoNomina,
     $temporales=[];

    public $importe;


    public  $value=0, $anio;







    public $cont,
    $miId="";




    protected $listeners = ['refresAsignar' => '$refresh',
    'asignacionCheck','almacenar'

]; // listeners para refrescar el modal

//// recibe los datos desde el modal sustraer
public function almacenar($uuid,$valor){

  //// variables
$cont=0;


if (sizeof( $this->temporales) == 0){ $this->temporales[]=array("uuid"=>$uuid, "importe"=>$valor);

}

////// se recorre el array para determinar si se debe agregar o actualizar un valor
        foreach ($this->temporales as $item){

      /////Para poder modificar directamente los elementos del array dentro de bucle,
      //se ha de anteponer & para crear un puntero  a $valor. En este caso el valor será asignado por referencia.
        foreach ( $this->temporales as $items => &$items_value) {
            if ($items_value['uuid'] == $uuid ){

                $items_value['importe'] = $valor;

                 $cont ++;//// contador para determinar si hay que insertar un nuevo valor
        }

    }//// fin del foreach &items_value
//// si no se encontraron valores para actualizar entonces hay que agregar uno nuevo
    if($cont == 0){ $this->temporales[]=array("uuid"=>$uuid, "importe"=>$valor);}

    }//// fin del foreach padre



}//// fin del metodo almacenar







/////////////////////////////////////////////

public function asignacionCheck($id){

$this->chequesAsignados[]=$id;

}

    public function mount()
    {

$this->nomina="nomina".$this->serie.$this->asignarCheque;
        $this->condicion = '>';
        $this->idNuevoCheque = Null;


        $Cheques = Cheques::where('rfc', $this->RFC)
        ->where('tipoopera', 'Nómina')
        ->whereNull('nominaAsignada')
        ->orderBy('fecha', 'Desc')
        ->get();

        foreach($Cheques as $c){

         $this->importe.$c->id;

        }
        $this->chequesAsignados = [];
        $this->chequesVinculados= [];


    }


    protected function rules(){

        return [

            'cheque.importecheque'=>'',
            'importe.*'=>'required',


        ];
    }










    public function asignarNuevo($cheques)
    {

        $this->condicion = $cheques;
    }



    public function asignar2($id, $resta)

    {
        $resta = floatval($resta);

       $asignacion = XmlE::where('Emisor.Rfc', $this->RFC)

            ->where('Complemento.0.Nomina.FechaFinalPago', $this->fecha)
            ->where('Folio', $this->asignarCheque)
            ->where('Serie', $this->anio)
            ->get();

        foreach ($asignacion as $a) {

           $insert = MetadataE::where('folioFiscal', $a['UUID'])->first();
            $insert->push('cheques_id', $this->chequesAsignados);
            // $insert->unset('cheques_id');
        }

        ////////////////// [ ] ///////////////////////
        Cheques::whereIn('_id', $this->chequesAsignados)
            ->where('_id', '!=', $id)
            ->update([
                //'saldo' => $resta,
                'nominaAsignada' => 1,
            ], ['upsert' => true]);

        ////////////////// [ ] ///////////////////////

        Cheques::where('_id', $id)
            ->update([
                'saldo' => $resta,
            ], ['upsert' => true]);

        ////////////////// [ ] ///////////////////////


    }



    public function asignar($totalP,$id, $suma)
    {

        //// variables
        $total=0;
        $resta=$this->TotalPagado - $suma ;
        $resta1=0;
        $saldo=0;


      $asignacion = XmlE::where('Emisor.Rfc', $this->RFC)
            ->where('Complemento.Nomina.FechaFinalPago', $this->fecha)
            ->where('Folio', $this->asignarCheque)
            ->where('Serie', $this->anio)
            ->get();



        foreach ($asignacion as $a) { /// asignar los chequesId alos meta de nomina

           $insert = MetadataE::where('folioFiscal', $a['UUID'])
           ->push('cheques_id', $this->chequesAsignados);

          // $insert->unset('cheques_id');


        }

////// realizar cambios alos cheques asignados
//// campo: nomin.serie.folio / saldo / nominaAsignada

foreach($this->chequesAsignados as $ch){

    $cheques = Cheques::where('_id', $ch)->first();

// si el importe del cheque es menor al totalPago se asigna el campo nominaAsignada

if ($this->temporales){

    foreach ($this->temporales as $item ){
       if($item['uuid'] === $ch){
       $cheques = Cheques::where('_id',$item['uuid'])->first();



      if(isset($cheques->saldo)){
        $saldo= $cheques->saldo - $item['importe'];


         }else{   $saldo= $cheques->importecheque - $item['importe'];}
    $cheques2=Cheques::where('_id', $item['uuid'])
    ->update([

        $this->nomina => $item['importe'],
        'saldo' => $saldo

    ]);
       }

    }

// fin del if temporales
}elseif(isset($cheques->saldo) && $cheques->saldo <= $totalP) {

    $cheques2=Cheques::where('_id', $ch)
    ->update([

        $this->nomina => $cheques->saldo ,
        'saldo' => 0,

         'nominaAsignada' => 1,
        // 'saldo'=>0,
    ]);

}elseif($cheques->importecheque <= $totalP)
{

    $cheques2=Cheques::where('_id', $ch)
    ->update([
    'nominaAsignada' => 1,
    ]);

}



}/// fin del foreach

//// vaciar el array $temporales
$this->temporales=[];
$this->chequesAsignados = [];
$this->chequesVinculados= [];

$this->emit('refresAsignar');
$this->emitTo('nominas','nominarefresh');

    }/// fin del metodo asignar





    ////////////////// [ ] ///////////////////////








public function change($value,$id){
$this->validate();
$this->value=$value;
$this->miId=$id;


    }


    public function cheque($id)
    {

        session()->put('idnominas', $id);
        session()->put('rfcnomina', $this->RFC);
        session()->put('nomina',$this->nomina);

        return redirect()->to('/chequesytransferencias');
    }


    public function enviar($folio, $rfc, $fecha)
    {


        $this->emitTo('agregarcheque', 'arreg', $folio, $rfc, $fecha);
    }


////////////////////////// [ DESVICULAR LOS CHEQUES ASIGNADOS A LA NOMINA ]//////////////////////

    public function desvicunlar(){
$n=$this->nomina="nomina".$this->serie.$this->asignarCheque;


        $metadata = MetadataE:: // consulta a MetadataE
            whereIn('cheques_id',$this->chequesVinculados)
            ->where('efecto', 'Nómina')

            ->where('estado','Vigente')
            ->get();


            foreach($metadata as $m){

            $xml=XmlE::where('UUID',$m->folioFiscal)->first();
            if($xml->Folio ==  $this->asignarCheque && $xml->Serie == $this->serie)
             $meta=
             MetadataE::where('folioFiscal',$xml->UUID) // consulta a MetadataE
            -> where('efecto', 'Nómina')

             ->where('estado','Vigente')

             ->pull('cheques_id',$this->chequesVinculados);
            }

      $cheque= Cheques::
       whereIn('_id',$this->chequesVinculados)
       ->get();

      foreach($cheque as $c):
        $saldo= $c->$n + $c->saldo;
        //// si la suma del saldo y el campo nomina son
        ///iguales al importe original se elimina campo  saldo
        if($saldo == $c->importecheque){
            $c->unset($this->nomina);
            $c->unset('saldo');
        if(isset($c->nominaAsignada)){  $c->unset('nominaAsignada');}
         }elseif(isset($c->nominaAsignada) && !isset($c->saldo) ){

            $c->unset('nominaAsignada');

        }else{

            $c->unset($n);

            $actualizar= Cheques::
            where('_id',$c->_id)
            ->update([
                'saldo'=> $saldo,
                ]);



            if(isset($c->nominaAsignada)){  $c->unset('nominaAsignada');}
         }

       endforeach;

       $this->temporales=[];
       $this->chequesAsignados = [];
       $this->chequesVinculados= [];

       $this->emitSelf('refresAsignar');
       $this->emitTo('nominas','nominarefresh');


    }

///////////// emitir datos al modal sustraer

public function emitirAsustraer($id){

    $this->emitTo('sustraer','recibeAsignar',$id);

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
if (isset($this->cheques_asociados->cheques_id)  ){
        $Cheques = Cheques::search($this->search)
         ->where('rfc', $this->RFC)
           ->WhereNotIn('_id', $this->cheques_asociados->cheques_id)

            ->where('tipoopera', 'Nómina')
            ->whereNull('nominaAsignada',$this->nomina)

            ->orderBy('fecha', 'Desc')
            ->get();
}else{
    $Cheques = Cheques::search($this->search)
    ->where('rfc', $this->RFC)
             ->whereNull($this->nomina)
            ->where('tipoopera', 'Nómina')
            ->whereNull('nominaAsignada')
            ->orderBy('fecha', 'Desc')
            ->get();

}


        return view(
            'livewire.asignar-cheque',
            [
                'datos' => $this->asignarCheque,
                'RFC' => $this->RFC,
                'Cheques' => $Cheques,
                'Cheques1' => $Cheques,
                'chequesAsig' => $this->chequesAsignados,
                'fechaFinal' => $this->fecha,
                'content' => $this->content,
                'granTotal' => $this->granTotal,
                'folioFiscal' => $this->folioFiscal,
                'totalPagado' => $this->TotalPagado,
                'cont'=>$this->value,
                'miId'=>$this->miId,
                'fechaPago'=>$this->fechaPago,
                'serie'=>$this->serie,
                'cheques_asociados'=> $this->cheques_asociados,


            ]
        );
    }
}
