<?php

namespace App\Http\Livewire;

use App\Models\XmlE;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Nominas extends Component
{
    public $rfcEmpresa;
    public $anio;

    public function mount()
    {

        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {

            $this->rfcEmpresa = auth()->user()->RFC;
        }

        /////////////////
        $this->anio=date("Y");

    }

    protected $listeners =[

      'nominarefresh' =>'$refresh',

    ];









    public function render()
    {

        set_time_limit(9200); //Tiempo limite dado 1 hora

        if(!empty(auth()->user()->tipo)){

            $e=array();
                  $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas


                  for($i=0; $i <$largo; $i++) {

                  $rfc=auth()->user()->empresas[$i];
                   $e=DB::Table('clientes')
                   ->select('RFC','nombre')

                   ->where('RFC', $rfc)

                   ->get();

                   foreach($e as $em){


                   $emp[]= array( $em['RFC'],$em['nombre']);
                   }
                  }

                }elseif(!empty(auth()->user()->TipoSE)){

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





                $nominas=XmlE::
                where('Emisor.Rfc',$this->rfcEmpresa)
                ->where('TipoDeComprobante','N')
                ->where('Serie', $this->anio)
                ->select('Fecha','Complemento','Total')
                ->groupBy('Folio')
                ->orderBy('Folio','Asc')
                ->get();






                $anios = range(2014, date('Y'));




        return view('livewire.nominas',[
        'empresas'=>$emp,
        'empresa'=>$this->rfcEmpresa,
        'nominas'=>$nominas,
        'anio'=>$this->anio,
        'anios'=>$anios,

        ])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
