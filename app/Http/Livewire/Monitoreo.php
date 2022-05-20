<?php

namespace App\Http\Livewire;

use App\Models\MetadataE;
use App\Models\XmlE;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos
set_time_limit(3600); //Tiempo limite dado 1 hora

class Monitoreo extends Component
{
    //Variables globales
    public $rfcEmpresa;
    public $active = "hidden";
    public $fechaayer;

    //Variables del rango de fecha
    public $fechainic;
    public $fechafin;


    //Metodo para realizar la consulta del monitoreo
    public function ConsulMeta()
    {
        if ($this->rfcEmpresa) {
            //Consultamos los metadatos
            $infometaemit = MetadataE::where('emisorRfc', $this->rfcEmpresa)
                ->whereBetween('fechaEmision',  [$this->fechainic . 'T00:00:00', $this->fechafin . 'T23:59:59'])
                ->get();

            //Activamos los botones de exportacion
            $this->active = null;

            $this->dispatchBrowserEvent('cargagrafic', []);

            return $infometaemit;
        }
    }

    //Metodo para comsultar los XML
    public function ConsulXML()
    {
        if ($this->rfcEmpresa) {
            //Consultamos los metadatos
            $infoxmlemit = XmlE::where('Emisor.Rfc', $this->rfcEmpresa)
                ->whereBetween('Fecha',  [$this->fechainic . 'T00:00:00', $this->fechafin . 'T23:59:59'])
                ->get();

            return $infoxmlemit;
        }
    }

    //Metodo para realizar la consulta del monitoreo
    public function ConsulMetaClient()
    {
        if ($this->rfcEmpresa) {
            //Consultamos los metadatos
            $infometaemitclient = MetadataE::select('receptorRfc', 'receptorNombre')
                ->where('emisorRfc', $this->rfcEmpresa)
                ->whereBetween('fechaEmision',  [$this->fechainic . 'T00:00:00', $this->fechafin . 'T23:59:59'])
                ->groupBy('receptorRfc')
                ->get();

            return $infometaemitclient;
        }
    }

    public function mount()
    {
        //Establecemos la fecha de ayer
        $this->fechaayer = date('Y-m-d', strtotime('-1 day'));

        //Establecemos la fechas de inicio y fin
        $this->fechainic = date('Y-m-d', strtotime('-1 day'));
        $this->fechafin = date('Y-m-d', strtotime('-1 day'));

        //Establecemos el mes y año en las facturas por mes
        $this->factumesselect = date('m');
        $this->factuanioselect = date('Y');

        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {
            $this->rfcEmpresa = auth()->user()->RFC;
        }
    }

    public function render()
    {
        //Descomponemos la fecha al mes
        $mesayer = date('m', strtotime($this->fechaayer));

        //switch para cambiar el mes de numero a letras
        switch ($mesayer) {
            case 1:
                $mesayerstr = ' de Enero de ';
                break;
            case 2:
                $mesayerstr = ' de Febrero de ';
                break;
            case 3:
                $mesayerstr = ' de Marzo de ';
                break;
            case 4:
                $mesayerstr = ' de Abril de ';
                break;
            case 5:
                $mesayerstr = ' de Mayo de ';
                break;
            case 6:
                $mesayerstr = ' de Junio de ';
                break;
            case 7:
                $mesayerstr = ' de Julio de ';
                break;
            case 8:
                $mesayerstr = ' de Agosto de ';
                break;
            case 9:
                $mesayerstr = ' de Septiembre de ';
                break;
            case 10:
                $mesayerstr = ' de Octubre de ';
                break;
            case 11:
                $mesayerstr = ' de Noviembre de ';
                break;
            case 12:
                $mesayerstr = ' de Diciembre de ';
                break;
        }

        //Componemos todo el texto de la fecha de anterior
        $diaayer = date('d', strtotime($this->fechaayer)); //Dia anterior
        $anioayer = date('Y', strtotime($this->fechaayer)); //Año al que pertenece

        $fechaayerstr = $diaayer . $mesayerstr . $anioayer; //Componemos toda la cadena del dia

        //Condicional para obtener el tipo de usuario y almacenar las empresas viculadas de estas
        if (!empty(auth()->user()->tipo)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->get();

                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else if (!empty(auth()->user()->TipoSE)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->get();


                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else {
            $emp = '';
        }

        //Arreglo de los meses
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

        //Arreglo (rango) del año actual al 2014
        $anios = range(2014, date('Y'));

        return view('livewire.monitoreo', ['meses' => $meses, 'anios' => $anios, 'fechaayer' => $fechaayerstr, 'empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'consulmetaporhora' => $this->ConsulMeta(), 'consulxmlporhora' => $this->ConsulXML(), 'consulmetaclient' => $this->ConsulMetaClient()])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
