<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MetadataR;
use App\Models\Cheques;
use Exception;

class Cuentasporpagar extends Component
{
    //Variables globales
    public $rfcEmpresa;
    public int $perPage=20;
    public $search;
    public $moreprov = [];
    public $RFC;
    public $moviselect;
    public $movivinc = [];
    public $btnvinactiv = 0;

    //Metodo para vaciar la variable
    public function CleanRFC()
    {
        $this->moreprov = [];
        $this->RFC = "";
    }

    //Metodo para meter emitido en la variable publica
    public function EmitRFC($EmitRFC)
    {
        $this->RFC = $EmitRFC;
    }

    //Metodo para meter el arreglo generado en la variable publica
    public function EmitRFCArray(){
        $this->RFC = $this->moreprov;
    }

    //Vincular un CFDI a un movimiento
    public function VincuCFDIMovi(){
        try{
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

            return redirect()->route('cheques'); 

        }catch(Exception $e){
            return redirect()->route('cuentaspagar'); 
        }
        
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
    }

    //Metodo para ejecutar la vista
    public function render()
    {
        //Condicional para activar el boton
        if($this->movivinc && $this->moviselect){
            $this->btnvinactiv = 1;
        }else{
            $this->btnvinactiv = 0;
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
        }else{
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
        ->where('receptorRfc', $this->rfcEmpresa)
        ->groupBy('emisorRfc')
        ->groupBy('emisorNombre')
        ->orderBy('emisorRfc', 'asc')
        ->get();

        //Consulta para obtener los datos de CFDI
        //Condicional para saber si el valor de RFC es un arreglo o un string
        if(is_array($this->RFC)){
            //Si es arreglo
            $CFDI = MetadataR::
            where('estado', '<>', 'Cancelado')
            ->where('receptorRfc', $this->rfcEmpresa)
            ->wherein('emisorRfc', $this->RFC)
            ->whereNull('cheques_id')
            ->orderBy('fechaEmision', 'desc')
            ->get();

        }else{
            //No es arreglo
            $CFDI = MetadataR::
            where('estado', '<>', 'Cancelado')
            ->where('receptorRfc', $this->rfcEmpresa)
            ->where('emisorRfc', $this->RFC)
            ->whereNull('cheques_id')
            ->orderBy('fechaEmision', 'desc')
            ->get();
        }

        //Consulta de los cheques vinculados
        $Cheques = Cheques::
            where('rfc', $this->rfcEmpresa)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.cuentasporpagar', ['empresa'=>$this->rfcEmpresa, 'empresas'=>$emp, 'meses'=>$meses, 'col'=>$col, 'CFDI'=>$CFDI, 'Cheques'=>$Cheques])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
