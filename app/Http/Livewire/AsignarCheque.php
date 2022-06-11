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

    public Cheques $cheque;// enlasar al modelo cheques
    public $asignarCheque;
    public $RFC, $fecha;
    public $chequesAsignados = [];
    public $condicion, $idNuevoCheque, $content, $granTotal, $folioFiscal, $TotalPagado;



    public  $value=0, $anio;



    public $cont,
    $miId="";




    protected $listeners = ['refresAsignar' => '$refresh']; // listeners para refrescar el modal



    public function mount()
    {


        $this->condicion = '>';
        $this->idNuevoCheque = Null;
        $this->anio = date("Y");



    }


    protected function rules(){

        return [

            'cheque.importecheque'=>'',

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



    public function asignar($id, $suma)
    {
        $total=0;
        $resta=$this->TotalPagado- $suma ;
        $resta1=0;


        $asignacion = XmlE::where('Emisor.Rfc', $this->RFC)
            ->where('Complemento.0.Nomina.FechaFinalPago', $this->fecha)
            ->where('Folio', $this->asignarCheque)
            ->where('Serie', $this->anio)
            ->get();

        foreach ($asignacion as $a) {

            $insert = MetadataE::where('folioFiscal', $a['UUID'])->first();
           // $insert->push('cheques_id', $this->chequesAsignados);
            $insert->unset('cheques_id');
        }


        //////////////////////-->[ SECCION DE CHEQUES ]<--/////////////////////
foreach ($this->chequesAsignados as $chequeId){
    $q=Cheques::where('_id', $chequeId)->first();
if(isset($q->saldo) && $q->saldo <= $this->TotalPagado)
{
    Cheques::where('_id', $chequeId)
    ->update([
    'saldo' => 0,
    'nominaAsignada' => 1,
], ['upsert' => true]);

}elseif(isset($q->saldo) && $q->saldo > $this->TotalPagado){
$resta1 = $q->saldo - $resta;

Cheques::where('_id', $chequeId)
->update([
        'saldo' => $resta1,
    ], ['upsert' => true]);


}elseif($q->importecheque > $this->TotalPagado &&  !isset($q->saldo)){

    $resta1 = $q->importecheque - $resta;
    Cheques::where('_id', $chequeId)
    ->update([
        'saldo' => $resta1,
    ], ['upsert' => true]);



}else{

    Cheques::where('_id', $chequeId)
    ->update([
        'nominaAsignada' => 1,
    ], ['upsert' => true]);

}



}





    ////////////////// [ ] ///////////////////////

    }






    public function change($value,$id){
$this->validate();
$this->value=$value;
$this->miId=$id;


    }


    public function cheque($id)
    {

        session()->put('idnominas', $id);
        session()->put('rfcnomina', $this->RFC);

        return redirect()->to('/chequesytransferencias');
    }


    public function enviar($folio, $rfc, $fecha)
    {


        $this->emitTo('agregarcheque', 'arreg', $folio, $rfc, $fecha);
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

        $Cheques = Cheques::where('rfc', $this->RFC)
            ->where('tipoopera', 'Nómina')
            ->whereNull('nominaAsignada')
            ->orderBy('fecha', 'Desc')
            ->get();

        return view(
            'livewire.asignar-cheque',
            [
                'datos' => $this->asignarCheque,
                'RFC' => $this->RFC,
                'Cheques' => $Cheques,
                'chequesAsig' => $this->chequesAsignados,
                'fechaFinal' => $this->fecha,
                'content' => $this->content,
                'granTotal' => $this->granTotal,
                'folioFiscal' => $this->folioFiscal,
                'totalPagado' => $this->TotalPagado,
                'cont'=>$this->value,
                'miId'=>$this->miId,


            ]
        );
    }
}
