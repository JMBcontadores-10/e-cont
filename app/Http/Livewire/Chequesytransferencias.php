<?php

namespace App\Http\Livewire;


use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use Livewire\Component;
use Livewire\WithPagination;

class Chequesytransferencias extends Component
{



    public $users, $name, $email, $user_id;
    public $cheque;

    public int $perPage=100;
    public $search;

    protected $listeners = [
        'chequesRefresh' => '$refresh',
     ];


     public function mount()
     {
         $this->search;
     }
 

protected function rules(){

    return [
        'user_id' => '',
        'name' => '',
       
    ];
}









    public function render()
    {

        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$rfc)

        ->paginate($this->perPage)
        ;
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
        ->section('content');

    }



    public function buscar(){

        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$rfc)

        ->paginate($this->perPage)
        ;

       $class="table nowrap dataTable no-footer";// clase para la tabla de cheques y tranferencias
       // $this->dispatchBrowserEvent('hola', []);
    }


    public function editar($id){

  
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $cheque = Cheques::
      
        where('_id',$id)->first();

        ;

        $this->user_id = $id;
        $this->name = $cheque->numcheque;
        $this->fecha = $cheque->fecha;

 

    }



    public function actualizar(){

        $this->emitTo('chequesytransferencia','chequesRefresh');
    }




}