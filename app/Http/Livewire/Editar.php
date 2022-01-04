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

        if(!empty($this->editChequenombrec)){
        $nombre =$this->editChequenombrec->getClientOriginalName();  
        $nombreFile=preg_replace('/[^A-z0-9.-]+/', '', $nombre);
        $renameFile=$hora.$nombreFile;
        }

        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $dateValue = strtotime($this->editCheque->fecha);
        $mesfPago = date('m',$dateValue);
        $anioValue = strtotime($this->editCheque->fecha);
        $anioo = date('Y',$dateValue);
        $mesActual=date('m');
        $espa=new Cheques();
        //$espa->fecha_es($mes);

        $ruta="contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";
        $ruta2="/contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/";
        $ruta3="/contarappv1_descargas/".$rfc."/".$anioo."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";
        $rutaRelacionados="contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mesfPago)."/";

        

        $this->validate();


/* verifica si existe el pdf en el dir. y lo elimina si se va a remplazar */

        if(!empty($this->editChequenombrec)){

            if ($this->editCheque->nombrec!="0"){

            $nomfile = explode("/", $this->editCheque->nombrec);

            $file =$nomfile[1];

         $path = storage_path('.././public/storage').$ruta3.$nomfile[1];
         if(file_exists($path)){
         unlink($path);
         }

        }

        $this->editChequenombrec->storeAs($ruta,  $renameFile ,'public2');

        $data=[


            'nombrec' => $espa->fecha_es($mesfPago)."/" . $renameFile

        ];

        $this->editCheque->update($data); // guarda el documento el pdf

         }/* fin- verifica si existe el pdfen el dir. y lo elimina si se sube uno nuevo */
      /*====se toma el nombre del pdf de la db para moverlo de carpeta======*/ 
         else{


if ($this->editCheque->nombrec!="0"){
            $nomfile = explode("/", $this->editCheque->nombrec);

            $file =$ruta3.$nomfile[1];
            if(Storage::disk('public2')->exists($file)) {



            }else{
       //Storage::disk('public2')->copy($ruta2.$this->editCheque->nombrec, $ruta3.$nomfile[1] );
       // Storage::disk('public2')->writeStream($ruta3.$nomfile[0].".pdf", Storage::readStream($ruta2.$this->editCheque->nombrec));
       Storage::disk('public2')->move($ruta2.$this->editCheque->nombrec,  $ruta3.$nomfile[1]);


      // unlink($file);
    }
           
     
    
       $data=[

        'nombrec' => $espa->fecha_es($mesfPago)."/" .$nomfile[1]
       ];

       

       $this->editCheque->update($data); // guarda el documento el pdf
        

         }

        }
   /*==== fin- se toma el nombre del pdf de la db para moverlo de carpeta======*/ 


  
         $this->editCheque->save();// guarda todos los campos



      

        session()->flash('c');
      // $this->emitTo('chequesytransferencias', 'chequesRefresh');
      $this->dispatchBrowserEvent('say-goodbye', []);


    }







}




/* 


  foreach ($this->relacionadosUp as $file) {

             $cheque = Cheques::where('Id', $this->editCheque->Id);
             $cheque->push('doc_relacionados',$espa->fecha_es($mesfPago)."/". $file->hashName());

            $file->store($rutaRelacionados, 'public2');


        }
*/