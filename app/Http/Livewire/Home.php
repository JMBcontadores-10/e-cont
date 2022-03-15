<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cheques;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    //Variables globales
    use WithPagination;
    public $rfcEmpresa;
    public $search;
    public float $importe =0;
    public $anio;
    public int $perPage=3;
    protected $paginationTheme = 'bootstrap';// Para dar e estilo numerico al paginador

    //Metodo para identificar el tipo de usuario
    public function mount()
    {
        if(auth()->user()->tipo){
            $this->rfcEmpresa='';
        }
        else{
            $this->rfcEmpresa=auth()->user()->RFC;
        }

        $this->condicion='>=';
    }

    public function render()
    {
        //Condicional para mostrar las empresas
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

        //Guardamos los datos de los años en una variable
        $anios = range(2014, date('Y'));

        //Consulta para obtener los datos pendientes
        //Condicional para mostrar que no se ejecute la consulta cuando no hay año
        $cheqpendient = Cheques::
            search($this->search)
            ->where('rfc',$this->rfcEmpresa)
            ->where('fecha', 'like','%'.$this->anio."-".'%')
            ->get();

        return view('livewire.home', ['empresa'=>$this->rfcEmpresa, 'empresas'=>$emp, 'anios'=>$anios, 'pendientes'=>$cheqpendient])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
