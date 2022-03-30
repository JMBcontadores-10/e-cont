<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MetadataR;
use App\Models\Notificaciones;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Cheques;
use DateTime;
use DateTimeZone;

class Detalles extends Component
{
    //Variables globales
    public MetadataR $factu;
    public $rfcEmpresa;
    public $RFC;
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
    $idNuevoCheque;

    //Variable para la suma de totales de las facturas seleccionadas
    public $sumtotalfactu;

    public function mount()
    {
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
        $this->RFC = "";
        $this->sumtotalfactu = "";
        $this->moviselect = "";
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
        session()->flash('ChequeId', $this->idNuevoCheque->_id);
        session()->flash('Empresa', $this->rfcEmpresa);
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

        //Consulta para obtener los datos de CFDI
        //Condicional para saber si el valor de RFC es un arreglo o un string
        if(is_array($this->RFC)){
            //Si es arreglo
            $CFDI = MetadataR::
            search($this->searchcfdi)
            ->where('estado', '<>', 'Cancelado')
            ->where('receptorRfc', $this->rfcEmpresa)
            ->wherein('emisorRfc', $this->RFC)
            ->whereNull('cheques_id')
            ->orderBy('fechaEmision', 'desc')
            ->get();

        }else{
            //No es arreglo
            $CFDI = MetadataR::
            search($this->searchcfdi)
            ->where('estado', '<>', 'Cancelado')
            ->where('receptorRfc', $this->rfcEmpresa)
            ->where('emisorRfc', $this->factu->emisorRfc)
            ->whereNull('cheques_id')
            ->orderBy('fechaEmision', 'desc')
            ->get();
        }

        //Consulta de los cheques vinculados
        $Cheques = Cheques::
            where('rfc', $this->rfcEmpresa)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.detalles', ['empresa'=>$this->rfcEmpresa, 'CFDI'=>$CFDI, 'Cheques'=>$Cheques, 'totalfactu'=>$this->sumtotalfactu, 'facturas'=>$this->factu]);
    }
}
