<?php


namespace App\Http\Livewire;


use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use App\Models\MetadataE;
use App\Models\MetadataR;
use App\Models\Notificaciones;
use App\Models\XmlE;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class Agregarcheque extends Component
{

 use WithFileUploads;

    // public Cheques $ajusteCheque; // coneccion al model cheques
    public Cheques $Crear;// enlaza al modelo cheques
    public $Nuevo_numcheque,
    $Nuevo_tipomov,
    $Nuevo_fecha,
    $Nuevo_importecheque,
    $Nuevo_beneficiario,
    $Nuevo_tipoopera,
    $Nuevo_nombrec,
    $rfcEmpresa,
    $pushArchivos=[],
    $step3,
    $folio,
    $rfc,
    $fecha,
    $movivinc,
    $TotalIngresos = 0,
    $TotalEgresos = 0;
 //Variable para la suma de totales de las facturas seleccionadas
 public $sumtotalfactu;


    public $idNuevoCheque;

    protected $listeners = ['actualizar' => '$refresh',
    'agregar',

]; // listeners para refrescar el modal




//// funcion agregarDesdeCuentas
public function agregar($id,$sum){
    $this->movivinc = $id;
    $this->sumtotalfactu = $sum;
}



public function arreg($folio,$rfc,$fecha){

$this->folio=$folio;
$this->rfc=$rfc;
$this->fecha=$fecha;
$this->rfcEmpresa=$rfc;

}

    public function mount()
    {

        $this->Crear=new Cheques();

       $this->pushArchivos='';
       $this->Nuevo_nombrec='0';
       $this->rfcEmpresa=Auth::user()->RFC;

       $this->idNuevoCheque=null;

       $this->step3=true;

       $this->arr="vacio";

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
        $rfc = $this->rfcEmpresa;
        $Id = $dt->format('H\Mi\SsA');
        $diaAnioActual=date('d-Y');//obtiene dia y año actual para el pdf
        $Id = $dt->format('Y\Hh\Mi\SsA');// obtener año y hora con segundos para evitar repetidos
        $Id2= $dt->format('d');

        $anio_actual = $dt->format('Y');/// año actual para registro de cuando se sube el pdf
        $mesActual=date('m');// mes actual para registro de cuando se sube el pdf
        //==== variables que obtienen elaño y mes de pago que pone el ususario
        $dateValue = strtotime($this->Nuevo_fecha);//obtener la fecha
        $mesfPago = date('m',$dateValue);// obtener el mes
        $anioPago= date('Y',$dateValue);// obtener el año
        $espa=new Cheques();// se crea objeto para obtener la funcion meses español en modelo cheques
        $mesActualEs=$espa->fecha_es($mesActual);// se obtiene mes y se convierte en español
        $mess=$espa->fecha_es($mesfPago);// se obtiene mes y se convierte en español
        $importeCheque = (float)str_replace(',', '', $this->Nuevo_importecheque);
        $nombrec=$this->Nuevo_nombrec;
        if($this->Nuevo_nombrec){
        $subir_archivo=$this->Nuevo_nombrec->getClientOriginalName();
        // Almacena eliminado caracteres no alfanuméricos y concatenando la fecha de creación en el nombre
        $subir_archivo = preg_replace('/[^A-z0-9.-]+/', '', $subir_archivo);
        $nombrec = "$Id2$mesActualEs$Id&$subir_archivo";
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
        'rfc' => $this->rfcEmpresa,
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

      $this->idNuevoCheque=$chequeC;
     if($this->Nuevo_nombrec){
     $this->Nuevo_nombrec->storeAs($ruta, $nombrec, 'public2');// use se Storage:: para guardar los archivos en la carpeta se debe agregar
      }

      if($this->pushArchivos){
     foreach ($this->pushArchivos as $file) {// for each para leer el array /descomponerlo y guardar los archivos en la bd
      $docuRel=$file->getClientOriginalName();
      $docuRel = preg_replace('/[^A-z0-9.-]+/', '', $docuRel);
      $relacionados = "$Id2$mesActualEs$Id&$docuRel";

      $chequeC->push('doc_relacionados',$relacionados);

        $file->storeAs($rutaRelacionados, $relacionados, 'public2');// use se Storage:: para guardar los archivos en la carpeta se debe agregar


      }

}else{
 $chequeC->push('doc_relacionados', $this->pushArchivos);

      //  $this->dispatchBrowserEvent('hola', []);
}

////////////////////////////////////////////////////////////////////
######-->[ SI EL CHEQUE SE CREA DESDE cuentasporpagar SE VINCULAN LOS UUIDS]<--##########

/// si movivinc no es vacio se vincula el cheque con el movimiento
if($this->movivinc){
  //Vinculamos los CFDI al movimiento creado recientemente
  foreach(json_decode($this->movivinc) as $mov){
    $xml_r = MetadataR::where('folioFiscal', $mov)->first(); //Consulta a metadata_r
    $cheque = Cheques::find($this->idNuevoCheque->_id);
    $cheque->metadata_r()->save($xml_r);

    // Obtiene el total de facturas vinculadas y suma el total
    $Ingresos = MetadataR::where(['cheques_id' => $this->idNuevoCheque->_id])
    ->where('efecto','!=','Egreso')
    ->get()->sum('total');

    $TotalIngresos = round($Ingresos, 2);

    $Egresos = MetadataR::where(['cheques_id' => $this->idNuevoCheque->_id])
    ->where('efecto','Egreso')
    ->get()->sum('total');

    $TotalEgresos= round($Egresos, 2);

    //Actualiza el contador faltaxml descontando cada factura
    $cheque->update(['faltaxml'=> $cheque->faltaxml + 1]);
}
$ImporteTotal = $TotalIngresos - $TotalEgresos;

//Inserta el total de la suma de los cfdis  en importexml para corregir
$cheque->update(['importexml' => $ImporteTotal]);

}// fin if movivinc

#################[ FIN DE LA SECCION VICNULACION DESE CUENTAS]################################
///////////////////////////////////////////////////////////////////////////


//######## [se identifica si viene de nominas la creacion del nuevo cheque para vincular el cheque alos empleados]#########///


//##### [FIN DE LA SECCION NOMINAS ]###################///

/// crea la notificacion
$tipo[]='CFA';

/// si mesfpago ó anioPago son diferentes al mes y año actual se crea la notificacion
if(empty(auth()->user()->tipo)){

    if($mesfPago!=$mesActual || $anioPago!=$anio_actual){

$chequeC1 = Notificaciones::create([

        'numcheque' => $this->Nuevo_numcheque,
        'fecha' => $this->Nuevo_fecha,
        'importecheque' => $importeCheque,
        'Beneficiario' => $this->Nuevo_beneficiario,
        'tipoopera' => $this->Nuevo_tipoopera,
        'cheques_id' => $chequeC->_id,
        'rfc' => $this->rfcEmpresa,
        'read_at' => 0,
        'tipo'=> 'CFA',


]);

    }///fin del if de mesfpago y anioPago
} // fin del if de tipo



session()->put('idns', $chequeC->_id);
session()->put('rfcn', $rfc);

$this->Nuevo_numcheque="";
$this->Nuevo_fecha="";
$this->Nuevo_beneficiario="";
$importeCheque="";
$this->Nuevo_tipomov="";
$this->Nuevo_tipoopera="";


######################### [ SI EL CHEQUE SE CREA DESDE NOMINAS SE ASIGNA EL ID ALOS EMPLEADOS ]################
if($this->folio){
    // $this->folio="definido";

    $dat = strtotime($this->fecha);//obtener la fecha
    $anio= date('Y',$dat);// obtener el año

    $asignacion =XmlE::where('Emisor.Rfc',$this->rfc)
    ->where('Complemento.0.Nomina.FechaFinalPago',$this->fecha)
    ->where('Folio',$this->folio)
    ->where('Serie', $anio)
    ->get();

    foreach($asignacion as $a){


      $insert=MetadataE::where('folioFiscal',$a['UUID'])->first();
      $insert->push('cheques_id', $chequeC->_id);
     //$insert->unset('cheques_id');
    }


    }
######################### [ FIN ]################

//$this->dispatchBrowserEvent('cier', []);// recarga la pagina mediante js checar chequesytranscontrol.js
$this->emitTo( 'chequesytransferencias','chequesRefresh');//actualiza la tabla cheques y transferencias

// $this->emit( 'actualizar');//actualiza la tabla cheques y transferencias

$this->dispatchBrowserEvent('step2', []);


$this->emitTo( 'notification-secction','avisoPush');




    }





    public function render()
    {

        if(!empty(auth()->user()->tipo) ||!empty(auth()->user()->TipoSE) ){

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
        return view('livewire.agregarcheque',[
            'empresas'=>$emp,
            'idNuevoCheque'=>$this->idNuevoCheque,
            'step3'=>$this->step3,
            'folio'=>$this->folio,
            'rfc'=>$this->rfc,
            'fecha'=>$this->fecha,
            'totalfactu'=>$this->sumtotalfactu,
            // 'arreglo_cuentas'=>$this->arreglo_cuentas,

        ]);
    }



    public function step3(){

        $this->dispatchBrowserEvent('step3', []);
        $this->step3=false;
    }


    public function refresh(){

        //
        $this->emitUp('chequesRefresh');//actualiza la tabla cheques y transferencias
    //  $this->emitSelf('actualizar');


$this->Nuevo_numcheque="";
$this->Nuevo_fecha="";
$this->Nuevo_beneficiario="";
$this->Nuevo_importecheque="";
$this->Nuevo_tipomov="";
$this->Nuevo_tipoopera="";
$this->idNuevoCheque=null;
$this->step3=true;
$this->sumtotalfactu = "";
if($this->movivinc){
        // $this->emit('refreshUpload');
        return redirect()->to('/chequesytransferencias');
}
    }


    public function refresh2(){

        //
        $this->emitUp('chequesRefresh');//actualiza la tabla cheques y transferencias
    //  $this->emitSelf('actualizar');


$this->Nuevo_numcheque="";
$this->Nuevo_fecha="";
$this->Nuevo_beneficiario="";
$this->Nuevo_importecheque="";
$this->Nuevo_tipomov="";
$this->Nuevo_tipoopera="";
$this->idNuevoCheque=null;
$this->step3=true;
$this->sumtotalfactu = "";

    }



}
