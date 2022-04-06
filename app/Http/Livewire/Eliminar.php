<?php

namespace App\Http\Livewire;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use App\Models\MetadataR;
use App\Models\Notificaciones;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use File;
use Zip;
use Illuminate\Http\Request;
use ZipArchive;

class Eliminar extends ModalComponent
{


    public Cheques $eliminarCheque;// enlasar al modelo cheques




    protected function rules(){

        return [

            'eliminarCheque._id'=>'required',



            //======== modal ajuste =====//



        ];
    }

    public function render()
    {
///////// enviar el numero de facturas vinculadas ala vista
       $vinculados= MetadataR::where(['cheques_id' => $this->eliminarCheque->_id])->get();
       $numVinculados=count($vinculados);

        return view('livewire.eliminar', ['datos' =>  $this->eliminarCheque, 'numVinculados'=>$numVinculados]);
    }


    public function descargarZip($id)
    {
        $Archivos = [];
        $cheque = Cheques::where(['_id' => $id])->get()->first();

        $dateValue = strtotime($cheque->fecha);
        $anio = date('Y',$dateValue);
        $mes=date('m',$dateValue);
       $fecha=date('Y-m-d');

        $espa=new Cheques();
        $ruta="/storage/contarappv1_descargas/".$cheque->rfc."/".$anio."/Cheques_Transferencias/".$espa->fecha_es($mes)."/";
        $rutaRelacionados ='/storage/contarappv1_descargas/'.$cheque->rfc."/".$anio."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mes)."/";

        if(!$cheque->nombrec == "0"){

            $Archivos[] = public_path().$ruta.$cheque->nombrec;

        }



        if(!$cheque->doc_relacionados[0] == null){

            foreach($cheque->doc_relacionados as $doc){
                /** Store the names of the invoices with full path inside the paymentFiles variable */
               $Archivos[] = public_path().$rutaRelacionados. $doc;
            }

}
    	return Zip::create("econt$fecha.zip",$Archivos);
    }


    public function eliminar(){

        $dateValue = strtotime($this->eliminarCheque->fecha);// metodo strtotime() para fecha
        $mesCheque = date('m',$dateValue);// obtencion mes del cheque
        $anioCheque = date('Y',$dateValue);// obtencion mes del cheque
        $espa=new Cheques();
        //$espa->fecha_es($mes);

////// establecer ruta para la eliminacion del pdf principal
$ruta="contarappv1_descargas/".$this->eliminarCheque->rfc."/".$anioCheque."/Cheques_Transferencias/".$espa->fecha_es($mesCheque)."/";

////// establecer ruta para la eliminacion de los documetos relacionados
$rutaRelacionados ='contarappv1_descargas/'.$this->eliminarCheque->rfc."/".$anioCheque."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mesCheque)."/";

############ eliminacion de archivos ########################

/////// se elimina el pdf principal //////////////

Storage::disk('public2')->delete($ruta.$this->eliminarCheque->nombrec);

//////// se eliminan los documentos relacionados
        $cheque = Cheques::where(['_id' => $this->eliminarCheque->_id])->get()->first();
        if (!$this->eliminarCheque->doc_relacionados[0]==null) {
            foreach ($this->eliminarCheque->doc_relacionados as $c) {
                $rutaArchivo =  $rutaRelacionados.$c;
        Storage::disk('public2')->delete($rutaArchivo);
            }
        }

############ fin seccion eliminacion de archivos ########################

///// se desvinculan las facturas de cuentas por pagar (cheques_id)////
$colM =  MetadataR::where(['cheques_id' => $this->eliminarCheque->_id])->get();

        foreach ($colM as $i) {
            MetadataR::where('cheques_id', $i->cheques_id)
                ->update([
                    'cheques_id' => null,
                ]);
        }


####################### se crea notificacion de eliminacion ################

/// crea la notificacion

$notiE=Notificaciones::where('cheques_id', $this->eliminarCheque->_id)
->where('tipo', 'CA');
$notiE->update(
    [

        'tipo'=> 'CE',
    ]

);






////////// se elimina el movimiento completamente
$this->eliminarCheque->delete();



$this->dispatchBrowserEvent('cerrarEliminar', []);

 $this->emitTo( 'chequesytransferencias','chequesRefresh');//actualiza la tabla cheques y transferencias


}




}
