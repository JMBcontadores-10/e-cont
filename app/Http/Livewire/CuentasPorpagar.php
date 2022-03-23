<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MetadataR;
use App\Models\Cheques;

class Cuentasporpagar extends Component
{
    //Variables globales
    public $rfcEmpresa;
    public int $perPage=20;
    public $search;
    public $variosP=[];


    //Variables para mostrar los CFDI
    public $RFC = "";

    public function EmitRFC($EmitRFC)
    {
        $this->RFC = $EmitRFC;
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


        $this->variosP='';
    }

    //Metodo para ejecutar la vista
    public function render()
    {




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
        $CFDI = MetadataR::
        where('estado', '<>', 'Cancelado')
        ->where('receptorRfc', $this->rfcEmpresa)
        ->where('emisorRfc', $this->RFC)
        ->whereNull('cheques_id')
        ->orderBy('fechaEmision', 'desc')
        ->get();





        return view('livewire.cuentasporpagar', ['empresa'=>$this->rfcEmpresa, 'empresas'=>$emp, 'meses'=>$meses, 'col'=>$col, 'CFDI'=>$CFDI, 'variosP'=>$this->variosP])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
