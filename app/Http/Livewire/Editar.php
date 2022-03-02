<?php

namespace App\Http\Livewire;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;


class Editar extends ModalComponent
{


    public Cheques $editCheque;// enlasar al modelo cheques


    use WithFileUploads;
    public $editChequenombrec;
    public $relacionados;
    public $relacionadosUp =[];

    public$importe;


    public function mount(){


        $this->importe =$this->editCheque->importecheque;

    }


/////////////////////// funcion rules necesaria para validar datos en tiempo real
//////////////////////comparandolos con la base datos (siempre con livewire)
    protected function rules(){

        return[
            
            
            'editCheque.numcheque'=>'required',
            'editCheque.tipomov' => 'required',
            'editCheque.fecha' => 'required',
            'editCheque.importecheque' => 'required',
            'editCheque.Beneficiario'=> 'required',
            'editCheque.tipoopera'=> 'required',
            'editChequenombrec' => '',
            'relacionadosUp' => ' '// 6MB Max


        ];
    }



    public function render()
    {
        return view('livewire.editar', ['datos' =>  $this->editCheque]);
    }


    public function actualizar(){







        $dtz =new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $hora=$dt->format('YFd\Hh\Mi\SsA');
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $dateValue = strtotime($this->editCheque->fecha);
        $mesfPago = date('m',$dateValue);
        $anioValue = strtotime($this->editCheque->fecha);
        $anioo = date('Y',$dateValue);

      

        // ================================//
        // definir ruta origen //
        $cheque = Cheques::where('_id', $this->editCheque->id)->first(); // se instancia al cheque
        $valorOrigen=strtotime($cheque->fecha);
        $anioOrigen= date('Y',$valorOrigen);
        $mesOrigen= date('m',$valorOrigen);

        $espa=new Cheques();
        //$espa->fecha_es($mes);


        $rutaOrigen="/contarappv1_descargas/".$cheque->rfc."/".$anioOrigen."/Cheques_Transferencias/".$espa->fecha_es($mesOrigen)."/";
        $rutaDestino="/contarappv1_descargas/".$cheque->rfc."/".$anioo."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";

$rutaRelacionadosOrigen="contarappv1_descargas/".$cheque->rfc."/".$anioOrigen."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mesOrigen)."/";
$rutaRelacionadosDestino="contarappv1_descargas/".$cheque->rfc."/".$anioo."/Cheques_Transferencias/Documentos_Relacionados/". $espa->fecha_es($mesfPago)."/";



        $this->validate();

    


/* verifica si existe el pdf en el dir. y lo elimina si se va a remplazar */
// $this->editCheque->save();// guarda todos los campos



$numcheque=$this->editCheque->numcheque;
$tipomov=$this->editCheque->tipomov;
$fecha=$this->editCheque->fecha;
$Beneficiario=$this->editCheque->Beneficiario;
$tipoopera=$this->editCheque->tipoopera;
$valor = floatval($this->editCheque->importecheque);
    
$data=[

     'numcheque'=> $numcheque,
    'tipomov' =>$tipomov,
    'fecha'=>$fecha,
    'Beneficiario'=>$Beneficiario,
    'tipoopera'=>$tipoopera,
    'importecheque' => $valor

];

$this->editCheque->update($data);// actuliza la base de datos con el campo recibido 'ajuste'





// && $this->editCheque->doc_relacionados !=""
if ($this->editCheque->nombrec!="0" ){

    // $porcionesnombrec = explode("/", $this->editCheque->nombrec);


    // if (Storage::exists($rutaDestino.$this->editCheque->nombrec)) {
    //     Storage::delete($rutaDestino.$this->editCheque->nombrec);
    // }
if(strcmp($rutaOrigen.$this->editCheque->nombrec , $rutaDestino.$this->editCheque->nombrec) ==0){/// compara las rutas para ver si son iguales y no mover archivo


   /// no se ejecuta nada

}else{
    Storage::disk('public2')->move($rutaOrigen.$this->editCheque->nombrec,  $rutaDestino.$this->editCheque->nombrec);

    
}

}






foreach ($this->editCheque->doc_relacionados as  $rel) {
    // if (Storage::exists($rutaRelacionadosDestino.$rel)) {
    //     Storage::delete($rutaRelacionadosDestino.$rel);
    // }
    // $porcionesrel = explode("/", $rel);

if($rel!=""){

    if(strcmp($rutaRelacionadosOrigen.$rel, $rutaRelacionadosDestino.$rel) == 0){/// compara las rutas para ver si son iguales y no mover archivo

/// no se ejecuta nada
       


    }else{

        Storage::disk('public2')->move($rutaRelacionadosOrigen.$rel,  $rutaRelacionadosDestino.$rel);

    }

}//fin del if $rel vacio







// Storage::disk('public2')->move($rutaOrigen.$this->editCheque->nombrec,  $rutaDestino.$this->editCheque->nombrec);





}






// $this->editCheque->save();// guarda todos los campos



$this->dispatchBrowserEvent('cerrarEditar', []);
$this->emitTo( 'chequesytransferencias','chequesRefresh');//actualiza la tabla cheques y transferencias


}



}
