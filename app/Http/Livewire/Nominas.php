<?php

namespace App\Http\Livewire;

use App\Models\MetadataE;
use App\Models\XmlE;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Nominas extends Component
{
    public $rfcEmpresa;
    public $anio,$mes;

    public function mount()
    {

        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {

            $this->rfcEmpresa = auth()->user()->RFC;
        }

        /////////////////
        $this->anio=date("Y");
        $this->mes=date("m");

    }

    protected $listeners =[

      'nominarefresh' =>'$refresh',

    ];


    // public function cheque($id)
    // {

    //   session()->put('idnominas',$id);
    //   session()->put('rfcnomina', $this->rfcEmpresa);

    // return redirect()->to('/chequesytransferencias');

    // }

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
                ->where('Complemento.0.Nomina.FechaPago','like','%' ."-".$this->mes."-".'%')
                ->select('Fecha','Complemento','Total','Emisor')
                ->groupBy('Folio')
                ->orderBy('Folio','Asc')
                ->get();




          ###################################################
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


          ###################################################




                $anios = range(2014, date('Y'));

        return view('livewire.nominas',[
        'empresas'=>$emp,
        'empresa'=>$this->rfcEmpresa,
        'nominas'=>$nominas,
        'anio'=>$this->anio,
        'anios'=>$anios,
        'meses'=>$meses,
        ])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
