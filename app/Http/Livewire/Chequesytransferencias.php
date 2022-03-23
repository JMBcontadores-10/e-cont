<?php

namespace App\Http\Livewire;


use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;



class Chequesytransferencias extends Component
{


    use WithFileUploads;
    use WithPagination;
    public Cheques $ajusteCheque; // coneccion al model cheques
    public Cheques $Crear;// enlaza al modelo cheques
    public $datos;
    public float $ajuste;

    public  $users, $name, $email, $user_id,$fecha,$ajuste2,$datos1,$user;
    public $cheque;
    public  float $importe =0;
     public $condicion;
    public $revisado;
    public $estatus;
    public $impresion;




   ///=======================variables nuevo-cheque=========================///
    public $Nuevo_numcheque,$Nuevo_tipomov,$Nuevo_fecha,$Nuevo_importecheque,$Nuevo_beneficiario,
    $Nuevo_tipoopera,$Nuevo_pdf,$relacionadosUp =[];


   ///======================= fin variables nuevo-cheque====================///


    public $mes;
    public $anio;
    public $todos;
    public $rfcEmpresa;

    protected $paginationTheme = 'bootstrap';// para dar e estilo numerico al paginador


    public function mount(Request $request)
    {

        $this->Crear=new Cheques();

        $this->anio=date("Y");
        $this->mes=date("m");

if(auth()->user()->tipo){
        $this->rfcEmpresa='';
}else{

    $this->rfcEmpresa=auth()->user()->RFC;
}


//$this->importe=0;
$this->condicion='>=';

    //Condicional para agregar los datos del nuevo cheque
    $this->rfcEmpresa = $request->input('empresa');
    $this->todos = $request->input('todos');
    $this->search = $request->input('id');
    }


    public int $perPage=20;
    public $search;


    public function updatingSearch(){

        $this->resetPage();
    }

    public function updatingImporte(){

        $this->resetPage();
    }


    protected $listeners = [
        'chequesRefresh' => '$refresh',
     ];

protected function rules(){

    return [
        'user_id' => '',
        'name' => '',
        'Nuevo_numcheque'=>'required',
        'Nuevo_tipomov'=>'',
        'Nuevo_fecha'=>'',
        'Nuevo_importecheque'=>'',
        'Nuevo_beneficiario'=>'',

        //======== modal ajuste =====//



    ];
}







    public function render()
    {


        if(Auth::check()){/// autentica si se incio session

            auth()->user();

           }



        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);

        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');

      if($this->todos){

        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$this->rfcEmpresa)
        ->orderBy('fecha', 'desc')
        ->orderBy('updated_at', 'desc')


        ->paginate($this->perPage);

      }elseif($this->estatus=='pendi'){

        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$this->rfcEmpresa)
        ->where('importecheque',$this->condicion,$this->importe)
        ->where('pendi',1)
        ->where('fecha', 'like','%'.$this->anio."-".'%')
        ->where('fecha', 'like','%' ."-".$this->mes."-".'%')
        ->orderBy('fecha', 'desc')

        ->paginate($this->perPage);



      }elseif($this->estatus=='sin_revisar'){

        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$this->rfcEmpresa)
        ->where('importecheque',$this->condicion,$this->importe)
        ->where('verificado',0)
        ->where('pendi',0)
        ->where('fecha', 'like','%'.$this->anio."-".'%')
        ->where('fecha', 'like','%' ."-".$this->mes."-".'%')
        ->orderBy('fecha', 'desc')

        ->paginate($this->perPage);



      }elseif($this->estatus=='sin_conta'){

        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$this->rfcEmpresa)
        ->where('importecheque',$this->condicion,$this->importe)
        ->where('conta',0)
        ->where('verificado',1)
        ->where('fecha', 'like','%'.$this->anio."-".'%')
        ->where('fecha', 'like','%' ."-".$this->mes."-".'%')
        ->orderBy('fecha', 'desc')

        ->paginate($this->perPage);




      }
      else{

        // $ch = Cheques::
        // search($this->search)
        // ->where('rfc',$this->rfcEmpresa)
        // ->get();



        // Cheques::where('rfc',$this->rfcEmpresa)->update([
        //     'verificado' => 1,
        //     'pendi' => 0,
        //     'revisado_fecha' => $dt->format('Y-m-d\TH:i:s'),
        // ]);


        $cheque = Cheques::
        search($this->search)
        ->where('rfc',$this->rfcEmpresa)
        ->where('importecheque',$this->condicion,$this->importe)

        ->where('fecha', 'like','%'.$this->anio."-".'%')
        ->where('fecha', 'like','%' ."-".$this->mes."-".'%')
        ->orderBy('fecha', 'desc')
        ->orderBy('created_at', 'desc')

        ->paginate($this->perPage);



      }



if(!empty(auth()->user()->tipo)){

$e=array();
      $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas


      for($i=0; $i <$largo; $i++) {

      $rfc=auth()->user()->empresas[$i];
       $e=DB::Table('clientes')
       ->select('RFC','nombre')

       ->where('RFC', $rfc)

       ->get();

       foreach($e as $em)


       $emp[]= array( $em['RFC'],$em['nombre']);
      }

    }else{

$emp='';


    }//end if





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



if($this->revisado){

    $dtz = new DateTimeZone("America/Mexico_City");
    $dt = new DateTime("now", $dtz);

    Cheques::where('_id', $this->revisado)->update([
        'verificado' => 1,
        'pendi' => 0,
        'revisado_fecha' => $dt->format('Y-m-d\TH:i:s'),
    ]);

    $this->revisado='';
    $this->emitTo( 'chequesytransferencias','chequesRefresh');

}

if($this->impresion){

    $dtz = new DateTimeZone("America/Mexico_City");
    $dt = new DateTime("now", $dtz);

    Cheques::where('_id', $this->impresion)->update([
        'impresion' => 'on',

    ]);

    $this->impresion='';
    $this->emitTo('chequesytransferencias','chequesRefresh');

}




        return view('livewire.chequesytransferencias',['colCheques' => $cheque, 'meses'=>$meses,'anios'=>$anios,'empresa'=>$this->rfcEmpresa,'empresas'=>$emp])
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


    public function edit($id){








    }







    public function actualizar(){

        $this->emitTo('chequesytransferencia','chequesRefresh');
    }


    public function refeshModal(){

        $this->emitTo('pdfcheque','refreshpdf');//actualiza la tabla cheques y transferencias


    }


    public function revisado($id){

        $this->emit('uploadrelacionados',$id);
    }








}/// fin de la clase principal
