<?php

namespace App\Http\Livewire;


use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;


class Chequesytransferencias extends Component
{


    use WithFileUploads;
    use WithPagination;
    public Cheques $ajusteCheque; // coneccion al model cheques
    public Cheques $Crear;// enlaza al modelo cheques
    public $datos;
    public float $ajuste;

    public  $users, $name, $email, $user_id,$fecha,$importe,$ajuste2,$datos1,$user;
    public $cheque;

   ///=======================variables nuevo-cheque=========================///
    public $Nuevo_tipomov,$Nuevo_fecha,$Nuevo_importecheque,$Nuevo_beneficiario,
    $Nuevo_tipoopera,$Nuevo_pdf,$relacionadosUp =[];


   ///======================= fin variables nuevo-cheque====================///
   
    
    public $mes;
    public $anio;
    public $todos;

    protected $paginationTheme = 'bootstrap';// para dar e estilo numerico al paginador

  
    public function mount()
    {

        $this->anio=date("Y");
        $this->mes=date("m");

    }


    public int $perPage=20;
    public $search;


    public function updatingSearch(){

        $this->resetPage();
    }



  

    protected $listeners = [
        'chequesRefresh' => '$refresh',
     ];


    
 

protected function rules(){

    return [
        'user_id' => '',
        'name' => '',
        //======== modal ajuste =====//
      
       
       
    ];
}








    public function render()
    {

 
       
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');

      if($this->todos){

        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$rfc)
        ->orderBy('fecha', 'desc')
        ->orderBy('updated_at', 'desc')
     
      
        ->paginate($this->perPage);

      }else{

        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$rfc)
        ->where('fecha', 'like','%'.$this->anio."-".'%')
        ->where('fecha', 'like','%' ."-".$this->mes."-".'%')
      
        ->paginate($this->perPage);

      }
        
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

  
        
        $rfc = Auth::user()->RFC;
        
        $cheque = Cheques::
      
        where('_id',$id)->first();

        ;

        $this->user_id = $id;
        $this->name = $cheque->numcheque;
        $this->fecha = $cheque->importexml;
        
        $this->importe=$cheque->importexml;
        $this->ajuste2=$cheque->ajuste;
        $this->datos1=$cheque;

        
 

    }






    public function actualizar(){

        $this->emitTo('chequesytransferencia','chequesRefresh');
    }


///================== metodos modal ajuste ==================//

public function guardar(){

    $this->validate();

   $valor = floatval($this->ajuste);


    $data=[


        'ajuste' => $valor

    ];

    $this->ajusteCheque->update($data);

    $this->dispatchBrowserEvent('ajuste', []);

}



/// ===================== Seccion metodos nuevo cheque   ============================//

public function save_nuevo_cheque(){


}




/// ===================== fin seccion metodos nuevo cheque   ============================//



}/// fin de la clase principal