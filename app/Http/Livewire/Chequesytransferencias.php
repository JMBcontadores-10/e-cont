<?php

namespace App\Http\Livewire;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use Livewire\Component;

class Chequesytransferencias extends Component
{


    public $cheque;
    protected $listeners = [
        'chequesRefresh' => '$refresh',
     ];
    
   
    public function render()
    {
         
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $cheque = Cheques::where('rfc',$rfc)->paginate(20);
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        );
        $anios = range(2014, date('Y'));
     
     
     
    
        return view('livewire.chequesytransferencias',['colCheques' => $cheque, 'meses'=>$meses,'anios'=>$anios])
        ->extends('layouts.livewire-layout')
        ->section('content')
        ;
    }


    public function actualizar(){

        $this->emitTo('chequesytransferencia','reviewSectionRefresh');
    }
}
