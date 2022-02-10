<?php


namespace App\Http\Livewire;


use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use App\Models\Notificaciones;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Agregarcheque extends Component
{

 use WithFileUploads;
    public Cheques $ajusteCheque; // coneccion al model cheques
    public Cheques $Crear;// enlaza al modelo cheques
    public $Nuevo_numcheque,
    $Nuevo_tipomov,
    $Nuevo_fecha,
    $Nuevo_importecheque,
    $Nuevo_beneficiario,
    $Nuevo_tipoopera,
    $Nuevo_nombrec,
    $pushArchivos=[];

    public function mount()
    {

        $this->Crear=new Cheques();
        
       $this->pushArchivos='';
       $this->Nuevo_nombrec='0';

    }




    protected function rules(){

        return [
          
            'Nuevo_numcheque'=>'required',
            'Nuevo_tipomov'=>'',
            'Nuevo_fecha'=>'',
            'Nuevo_nombrec.*' =>  'mimes:pdf|max:1024', // 1MB Max
            'pushArchivos'=>'',
            
           
            //======== modal ajuste =====//
          
           
           
        ];
    }
    
    public function guardar_nuevo_cheque(){
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $Id = $dt->format('H\Mi\SsA');
        $diaAnioActual=date('d-Y');//obtiene dia y año actual para el pdf

        $anio_actual = $dt->format('Y');/// año actual para registro de cuando se sube el pdf
        $mesActual=date('m');// mes actual para registro de cuando se sube el pdf
        //==== variables que obtienen elaño y mes de pago que pone el ususario
        $dateValue = strtotime($this->Nuevo_fecha);//obtener la fecha
        $mesfPago = date('m',$dateValue);// obtener el mes 
        $anioPago= date('Y',$dateValue);// obtener el año
        $espa=new Cheques();// se crea objeto para obtener la funcion meses español en modelo cheques
        $mesActualEs=$espa->fecha_es($mesActual);// se obtiene mes y se convierte en español
        $importeCheque = (float)str_replace(',', '', $this->Nuevo_importecheque);
        $nombrec=$this->Nuevo_nombrec;
        if($this->Nuevo_nombrec){
        $subir_archivo=$this->Nuevo_nombrec->getClientOriginalName();
        // Almacena eliminado caracteres no alfanuméricos y concatenando la fecha de creación en el nombre
        $subir_archivo = preg_replace('/[^A-z0-9.-]+/', '', $subir_archivo);
        $nombrec = "$mesActualEs$diaAnioActual-$Id-#$subir_archivo";
        }

        //==========RUTAS PARA SUBIR LOS ARCHIVOS====///
       
        $ruta="contarappv1_descargas/".$rfc."/".$anioPago."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";
        $rutaRelacionados="contarappv1_descargas/".$rfc."/".$anioPago."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mesfPago)."/";
       



$this->validate();

    
    $chequeC = Cheques::create([
        'Id' => $Id,
        'tipomov' => $this->Nuevo_tipomov,
        'numcheque' => $this->Nuevo_numcheque,
        'fecha' => $this->Nuevo_fecha,
        'importecheque' => $importeCheque,
        'Beneficiario' => $this->Nuevo_beneficiario,
        'tipoopera' => $this->Nuevo_tipoopera,
        'rfc' => $rfc,
        'nombrec' => $nombrec,
        'rnfcrep' => '0',
        'importexml' => 0,
        'verificado' => 0,
        'faltaxml' => 0,
        'conta' => 0,
        'pendi' => 1,
        'lista' => 0,
        'ajuste' => 0,

    ]);
    
if($this->Nuevo_nombrec){
    $this->Nuevo_nombrec->storeAs($ruta, $nombrec, 'public2');// use se Storage:: para guardar los archivos en la carpeta se debe agregar 
}

if($this->pushArchivos){
    foreach ($this->pushArchivos as $file) {// for each para leer el array /descomponerlo y guardar los archivos en la bd
      $docuRel=$file->getClientOriginalName();
      $docuRel = preg_replace('/[^A-z0-9.-]+/', '', $docuRel);
      $relacionados = "$mesActualEs$diaAnioActual-$Id-#$docuRel";
      
      $chequeC->push('doc_relacionados',$relacionados);

        $file->storeAs($rutaRelacionados, $relacionados, 'public2');// use se Storage:: para guardar los archivos en la carpeta se debe agregar 


   }

}else{
 $chequeC->push('doc_relacionados', $this->pushArchivos); 
        
      //  $this->dispatchBrowserEvent('hola', []);
}

/// crea la notificacion

$chequeC = Notificaciones::create([
      
        'numcheque' => $this->Nuevo_numcheque,
        'fecha' => $this->Nuevo_fecha,
        'importecheque' => $importeCheque,
        'Beneficiario' => $this->Nuevo_beneficiario,
        'tipoopera' => $this->Nuevo_tipoopera,
        'rfc' => $rfc,
   

]);


    
    }



    public function render()
    {
        return view('livewire.agregarcheque');
    }
}
