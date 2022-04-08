<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MetadataR;
use App\Models\Cheques;
use App\Models\Notificaciones;
use DateTime;
use DateTimeZone;

class Cuentasporpagar extends Component
{
    //Variables globales
    public $rfcEmpresa;
    public int $perPage = 5; //La paginacion en esta caso se utilizara con la funcion de take()
    public $search;
    public $moreprov = [];
    public $RFC;
    public $moviselect;
    public $movivinc = [];
    public $btnvinactiv = 0;
    public $btnvinanewctiv = 0;
    public $searchcfdi;
    public $showselect;
    public $proveselect;

    //Variables de paginacion
    public $paginici = 0; //Vamos a iniciar en le index 0
    public $paglapso = 10; //Se mostrra en un paso de 5 registros
    public $totalprov; //Total de registros de los proovedores
    public $totalpagi = 1; //Referencias al total de paginas de la paginacion
    public $pagiselect = 1; //Pagina seleccionada
    

    //Variables que se utilizaran para agregar un nuevo cheque
    public $Nuevo_numcheque,
    $Nuevo_tipomov,
    $Nuevo_fecha,
    $Nuevo_importecheque,
    $Nuevo_beneficiario,
    $Nuevo_tipoopera,
    $Nuevo_nombrec,
    $pushArchivos = [],
    $step3,
    $idNuevoCheque;

    //Variable para la suma de totales de las facturas seleccionadas
    public $sumtotalfactu;

    protected $listeners = [
        'mostmovi' => 'mostmovi',
        'morerow' => 'morerow',
     ];

    //Metodo para mostrar el modal con el movimiento seleccionado de cheques y transferencias
    public function mostmovi($data)
    {
        $this->rfcEmpresa = $data['empresa'];
        $this->moviselect = $data['idmovi'];

        //Activamo la bandera para mostrar el select de proveedores
        $this->showselect = 1;
    }

    //Metodo para recibir el RFC del select de proveedores
    public function sendrfc(){
        $this->RFC = $this->proveselect;
    }

    //Metodo para realizar la paginacion (aumento de registro)
    public function morereg(){
        //Daremos saltos de n registros
        $this->paginici = $this->paginici + $this->paglapso;

        //Guardamos el valor de la pagina que se selecciono
        $this->pagiselect = $this->pagiselect + 1;
    }

    //Metodo para realizar la paginacion (diminucion de registro)
    public function minusreg(){
        //Daremos saltos de n registros
        $this->paginici = $this->paginici - $this->paglapso;

        //Guardamos el valor de la pagina que se selecciono
        $this->pagiselect = $this->pagiselect - 1;
    }

    //Metodo para navegar entre los botones de la paginacion
    public function navpagi($pagiact, $indexpagi){
        //Vamos a pasarnos de un punto a otro
        $this->paginici = $pagiact;

        //Guardamos el valor de la pagina que se selecciono
        $this->pagiselect = $indexpagi;
    }

    //Metodo para identificar el tipo de usuario
    public function mount()
    {
        if(auth()->user()->tipo){
            $this->rfcEmpresa='';
        }
        else{
            $this->rfcEmpresa=auth()->user()->RFC;
        }

        //Le damos un valor a las variables declaradas
        $this->pushArchivos='';
        $this->Nuevo_nombrec='0';
        $this->idNuevoCheque=null;
        $this->step3=true;
    }

    //Metodo para vaciar la variable
    public function CleanRFC()
    {
        $this->movivinc = [];
        $this->moreprov = [];
        $this->RFC = "";
        $this->sumtotalfactu = "";
        $this->moviselect = "";
        $this->searchcfdi = "";
        $this->showselect = 0;
        $this->proveselect = "";
        $this->pagiselect = 1;
        $this->paginici = 0;
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

    //Metodo para guardar un cheque nuevo con el CFDI vinculado
    public function AgregarChequeCFDI(){
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $Id = $dt->format('H\Mi\SsA');
        $Id = $dt->format('Y\Hh\Mi\SsA');// obtener año y hora con segundos para evitar repetidos
        $Id2= $dt->format('d');

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
        $nombrec = "$Id2$mesActualEs$Id&$subir_archivo";
        }

        $ruta="contarappv1_descargas/".$this->rfcEmpresa."/".$anioPago."/Cheques_Transferencias/".$espa->fecha_es($mesfPago)."/";
        $rutaRelacionados="contarappv1_descargas/".$this->rfcEmpresa."/".$anioPago."/Cheques_Transferencias/Documentos_Relacionados/".$espa->fecha_es($mesfPago)."/";

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
        $tipo[]='CA';
        $chequeC = Notificaciones::create([
        'numcheque' => $this->Nuevo_numcheque,
        'fecha' => $this->Nuevo_fecha,
        'importecheque' => $importeCheque,
        'Beneficiario' => $this->Nuevo_beneficiario,
        'tipoopera' => $this->Nuevo_tipoopera,
        'rfc' => $this->rfcEmpresa,
        'read_at' => 0,
        'tipo'=> 'CA',
        ]);

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

    //Metodo para meter el arreglo generado en la variable publica
    public function EmitRFCArray(){
        $this->RFC = $this->moreprov;
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

        //Redireccionamps a la viste de ChyT junto con las variables como parametro
        session()->flash('ChequeId', $this->moviselect);
        session()->flash('Empresa', $this->rfcEmpresa);
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

    //Metodo para ejecutar la vista
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

        //Condicional para obtener el tipo de usuario y almacenar las empresas viculadas de estas
        if(!empty(auth()->user()->tipo)){
            $e=array();
            $largo=sizeof(auth()->user()->empresas);
            for($i=0; $i <$largo; $i++) {
                $rfc=auth()->user()->empresas[$i];

                $e=DB::Table('clientes')
                ->select('RFC','nombre')
                ->where('RFC', $rfc)
                ->get();

                foreach($e as $em)
                $emp[]= array($em['RFC'],$em['nombre']);
            }
        }else if(!empty(auth()->user()->TipoSE)){
            $e=array();
            $largo=sizeof(auth()->user()->empresas);
            for($i=0; $i <$largo; $i++) {
                $rfc=auth()->user()->empresas[$i];

                $e=DB::Table('clientes')
                ->select('RFC','nombre')
                ->where('RFC', $rfc)
                ->get();

                foreach($e as $em)
                $emp[]= array($em['RFC'],$em['nombre']);
            }
        }
        else{

            $emp='';
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

        $col = MetadataR::
        search($this->search)
        ->select('emisorNombre', 'emisorRfc')
        ->where('receptorRfc', $this->rfcEmpresa)
        ->where('estado', '<>', 'Cancelado')
        ->whereNull('cheques_id')
        ->groupBy('emisorRfc')
        ->orderBy('emisorRfc', 'asc')
        ->get();

        $this->totalprov = count($col);
        $this->totalpagi = ceil($this->totalprov / $this->paglapso);
        $col = $col->toArray();
        $col = array_slice($col, $this->paginici, $this->paglapso, true);

        $provselect = MetadataR::
        select('emisorNombre', 'emisorRfc')
        ->where('receptorRfc', $this->rfcEmpresa)
        ->where('estado', '<>', 'Cancelado')
        ->whereNull('cheques_id')
        ->groupBy('emisorRfc')
        ->orderBy('emisorRfc', 'asc')
        ->get();

        //Consulta para obtener los datos de CFDI
        //Condicional para saber si el valor de RFC es un arreglo o un string
        if(is_array($this->RFC)){
            //Si es arreglo
            $CFDI = MetadataR::
            searchxml($this->searchcfdi)
            ->where('estado', '<>', 'Cancelado')
            ->where('receptorRfc', $this->rfcEmpresa)
            ->wherein('emisorRfc', $this->RFC)
            ->whereNull('cheques_id')
            ->orderBy('fechaEmision', 'desc')
            ->get();

        }else{
            //No es arreglo
            $CFDI = MetadataR::
            searchxml($this->searchcfdi)
            ->where('estado', '<>', 'Cancelado')
            ->where('receptorRfc', $this->rfcEmpresa)
            ->where('emisorRfc', $this->RFC)
            ->whereNull('cheques_id')
            ->orderBy('fechaEmision', 'desc')
            ->get();
        }

        //Consulta de los cheques vinculados
        $Cheques = Cheques::
            where('rfc', $this->rfcEmpresa)
            ->where('verificado', 0)
            ->where('conta', 0)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.cuentasporpagar', ['empresa'=>$this->rfcEmpresa, 'empresas'=>$emp, 'meses'=>$meses, 'col'=>$col, 'CFDI'=>$CFDI, 'Cheques'=>$Cheques, 'totalfactu'=>$this->sumtotalfactu, 'provselect'=>$provselect])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
