<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MetadataR;
use App\Models\Notificaciones;
use App\Models\Cheques;
use DateTime;
use DateTimeZone;

class Detalles extends Component
{
    //Variables globales
    public $factu;
    public $empresa;
    public $selectempresa;
    public $moviselect;
    public $movivinc = [];
    public $btnvinactiv = 0;
    public $btnvinanewctiv = 0;
    public $searchcfdi;


    //Variables que se utilizaran para agregar un nuevo cheque
    public $Nuevo_numcheque,
    $Nuevo_tipomov,
    $Nuevo_fecha,
    $Nuevo_importecheque,
    $Nuevo_beneficiario,
    $Nuevo_tipoopera,
    $Nuevo_nombrec,
    $pushArchivos=[],
    $step3,
    $idNuevoCheque,
    $hola;

    //Variable para la suma de totales de las facturas seleccionadas
    public $sumtotalfactu;

    protected $listeners = [
        'mostmovi' => 'mostmovi',
     ];

    public function mostmovi($data)
    {
        $this->rfcEmpresa = $data['empresa'];
        $this->moviselect = $data['idmovi'];
    }

    public function mount()
    {
        //Le damos un valor a las variables declaradas
        $this->pushArchivos='';
        $this->Nuevo_nombrec='0';
        $this->idNuevoCheque=null;
        $this->step3=true;
        $this->selectempresa = $this->empresa;
        $this->hola='hola';
    }

    //Metodo para vaciar la variable
    public function CleanRFC()
    {
        $this->movivinc = [];
        $this->RFC = "";
        $this->sumtotalfactu = "";
        $this->moviselect = "";
        $this->searchcfdi = "";
    }

    //Metodo para declarar los campos obligatorios
    protected function rules(){
        return [
            'Nuevo_numcheque'=>'required',
            'Nuevo_tipomov'=>'',
            'Nuevo_fecha'=>'',
            'Nuevo_nombrec.*' =>  'mimes:pdf|max:1024', // 1MB Max
            'pushArchivos'=>'',
        ];
    }

    ///Metodo enviarAcuentas
    public function enviar(){

     $this->hola='quetal';


    }





    //Metodo para guardar un cheque nuevo con el CFDI vinculado
    public function AgregarChequeCFDI(){
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $Id = $dt->format('H\Mi\SsA');
        $Id = $dt->format('Y\Hh\Mi\SsA');// obtener a??o y hora con segundos para evitar repetidos
        $Id2= $dt->format('d');
        $anio_actual = $dt->format('Y');/// a??o actual para registro de cuando se sube el pdf
        $mesActual=date('m');// mes actual para registro de cuando se sube el pdf
        //==== variables que obtienen ela??o y mes de pago que pone el ususario
        $dateValue = strtotime($this->Nuevo_fecha);//obtener la fecha
        $mesfPago = date('m',$dateValue);// obtener el mes
        $anioPago= date('Y',$dateValue);// obtener el a??o
        $espa=new Cheques();// se crea objeto para obtener la funcion meses espa??ol en modelo cheques
        $mesActualEs=$espa->fecha_es($mesActual);// se obtiene mes y se convierte en espa??ol

        $importeCheque = (float)str_replace(',', '', $this->Nuevo_importecheque);
        $nombrec=$this->Nuevo_nombrec;
        if($this->Nuevo_nombrec){
        $subir_archivo=$this->Nuevo_nombrec->getClientOriginalName();
        // Almacena eliminado caracteres no alfanum??ricos y concatenando la fecha de creaci??n en el nombre
        $subir_archivo = preg_replace('/[^A-z0-9.-]+/', '', $subir_archivo);
        $nombrec = "$Id2$mesActualEs$Id&$subir_archivo";
        }

        $ruta="contarappv1_descargas/".$this->empresa."/".$anioPago."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";
        $rutaRelacionados="contarappv1_descargas/".$this->empresa."/".$anioPago."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mesfPago)."/";

        $this->validate();

        $chequeC = Cheques::create([
            'Id' => $Id,
            'tipomov' => $this->Nuevo_tipomov,
            'numcheque' => $this->Nuevo_numcheque,
            'fecha' => $this->Nuevo_fecha,
            'importecheque' => $importeCheque,
            'Beneficiario' => $this->Nuevo_beneficiario,
            'tipoopera' => $this->Nuevo_tipoopera,
            'rfc' => $this->empresa,
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
       }
       else{
           $chequeC->push('doc_relacionados', $this->pushArchivos);
        }

        //Vinculamos los CFDI al movimiento creado recientemente
        foreach($this->movivinc as $mov){
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

    /// crea la notificacion
$tipo[]='CFA';

/// si mesfpago ?? anioPago son diferentes al mes y a??o actual se crea la notificacion
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


        $this->Nuevo_numcheque="";
        $this->Nuevo_fecha="";
        $this->Nuevo_beneficiario="";
        $importeCheque="";
        $this->Nuevo_tipomov="";
        $this->Nuevo_tipoopera="";

        $this->dispatchBrowserEvent('agregarpdf', []);
    }

    //Metodo para pasar de la seccion de subir PDF a subir relacionados
    public function Subirrela(){
        $this->dispatchBrowserEvent('agregarrela', []);
        $this->step3=false;
    }

    //Vincular un CFDI a un movimiento
    public function VincuCFDIMovi(){
        foreach($this->movivinc as $mov){
            $xml_r = MetadataR::where('folioFiscal', $mov)->first(); //Consulta a metadata_r
            $cheque = Cheques::find($this->moviselect);
            $cheque->metadata_r()->save($xml_r);

            // Obtiene el total de facturas vinculadas y suma el total
            $Ingresos = MetadataR::where(['cheques_id' => $this->moviselect])
            ->where('efecto','!=','Egreso')
            ->get()->sum('total');

            $TotalIngresos = round($Ingresos, 2);

            $Egresos = MetadataR::where(['cheques_id' => $this->moviselect])
            ->where('efecto','Egreso')
            ->get()->sum('total');

            $TotalEgresos= round($Egresos, 2);

            //Actualiza el contador faltaxml descontando cada factura
            $cheque->update(['faltaxml'=> $cheque->faltaxml + 1]);
        }

        $ImporteTotal = $TotalIngresos - $TotalEgresos;

        //Inserta el total de la suma de los cfdis  en importexml para corregir
        $cheque->update(['importexml' => $ImporteTotal]);

        //Redireccionamps a la vista de ChyT junto con las variables como parametro
        return redirect()->to('/chequesytransferencias');

    }

    //Retornamos a la vista cuando agregamos un nuevo movimiento con CFDI vinculados
    public function GotoChyT(){
        //Redireccionamps a la viste de ChyT junto con las variables como parametro
        return redirect()->to('/chequesytransferencias');
    }

    //Metodo para la suma de las facturas realcionadas
    public function SumFactu(){
        //Declaramos las variables que nos servira de acumuladores
        $TotalIngresos = 0;
        $TotalEgresos = 0;

        //Ciclo para obtener los valores de las facturas seleccionadas
        foreach($this->movivinc as $mov){

            // Obtiene el total de facturas vinculadas y suma el total
            $Ingresos = MetadataR::where(['folioFiscal' => $mov])
            ->where('efecto','!=','Egreso')
            ->get()->sum('total');

            $TotalIngresos += round($Ingresos, 2);

            $Egresos = MetadataR::where(['folioFiscal' => $mov])
            ->where('efecto','Egreso')
            ->get()->sum('total');

            $TotalEgresos += round($Egresos, 2);
        }

        //Obtenemos el valor total de la operacion y lo mostramos
        $this->sumtotalfactu = $TotalIngresos - $TotalEgresos;
    }

    public function refresh(){
        $this->Nuevo_numcheque="";
        $this->Nuevo_fecha="";
        $this->Nuevo_beneficiario="";
        $this->Nuevo_importecheque="";
        $this->Nuevo_tipomov="";
        $this->Nuevo_tipoopera="";
        $this->idNuevoCheque=null;
        $this->step3=true;
    }

    public function render()
    {
        //Condicional para activar el boton de vincular CFDI
        if($this->movivinc && $this->moviselect){
            $this->btnvinactiv = 1;
        }else{
            $this->btnvinactiv = 0;
        }

        //Condicional para activar el boton de vincular a nuevo cheque
        if($this->movivinc){
            $this->btnvinanewctiv = 1;
        }else{
            $this->btnvinanewctiv = 0;
        }

        //Consulta para obtener los datos de las facturas a vincular
        $CFDI = MetadataR::
        searchxml($this->searchcfdi)
        ->where('estado', '<>', 'Cancelado')
        ->where('receptorRfc', $this->empresa)
        ->where('emisorRfc', $this->factu)
        ->whereNull('cheques_id')
        ->orderBy('fechaEmision', 'desc')
        ->get();

        //Consulta de los cheques vinculados
        $Cheques = Cheques::
            where('rfc', $this->empresa)
            ->where('verificado','=', 0)
            ->where('conta','=', 0)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.detalles', ['empresa'=>$this->empresa, 'CFDI'=>$CFDI, 'Cheques'=>$Cheques, 'totalfactu'=>$this->sumtotalfactu, 'facturas'=>$this->factu]);
    }
}
