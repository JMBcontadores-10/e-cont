<?php

namespace App\Http\Livewire;

use App\Models\MetadataE;
use App\Models\XmlE;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(3600); //Tiempo limite dado 1 hora
ini_set('memory_limit', '1024M'); //Incrementamos la memoria 

class Monitoreo extends Component
{
    //Variables globales
    public $rfcEmpresa;
    public $active = "hidden";
    public $fechaayer;

    //Variables del rango de fecha
    public $fechainic;
    public $fechafin;

    //Variable del RFC recibido
    public $rfcrecib;

    public function SendRFCReci($rfc)
    {
        $this->$rfcrecib = $rfc;
    }

    //Metodo para realizar la consulta del monitoreo
    public function ConsulEmit()
    {
        if ($this->rfcEmpresa) {
            //Arreglo donde tendra los datos de los emitidos (Metadatos y XML)
            $listemit = array();

            //Consultamos los metadatos
            $infometaemit = MetadataE::where('emisorRfc', $this->rfcEmpresa)
                ->whereBetween('fechaEmision',  [$this->fechainic . 'T00:00:00', $this->fechafin . 'T23:59:59'])
                ->where('efecto', '!=', 'Nómina')
                ->get(['estado', 'efecto', 'fechaEmision', 'fechaCertificacion', 'folioFiscal', 'receptorRfc', 'receptorNombre', 'total']);

            //Ciclo para descomponer los datos del Metadato
            foreach ($infometaemit as $datametaemit) {
                //Consulta para obtener los datos del XML
                $infofactuxmlemit = XmlE::where('UUID', $datametaemit['folioFiscal'])
                    ->first(['UUID', 'Serie', 'Folio', 'LugarExpedicion', 'FormaPago', 'Conceptos.Concepto']);

                //Metemos en el arreglo los datos emitidos
                $listemit[] = [
                    'Estado' => $datametaemit['estado'] ?? null,
                    'Efecto' => $datametaemit['efecto'] ?? null,
                    'FechaEmision' => $datametaemit['fechaEmision'] ?? null,
                    'FechaCertificacion' => $datametaemit['fechaCertificacion'] ?? null,
                    'Serie' => $infofactuxmlemit['Serie'] ?? null,
                    'Folio' => $infofactuxmlemit['Folio'] ?? null,
                    'UUID' => $infofactuxmlemit['UUID'] ?? null,
                    'LugarExpedicion' => $infofactuxmlemit['LugarExpedicion'] ?? null,
                    'ReceptorRfc' => $datametaemit['receptorRfc'] ?? null,
                    'ReceptorNombre' => $datametaemit['receptorNombre'] ?? null,
                    'Total' => $datametaemit['total'] ?? null,
                    'FormaPago' => $infofactuxmlemit['FormaPago'] ?? null,
                    'Concepto' => $infofactuxmlemit['Conceptos.Concepto'] ?? null,
                ];
            }

            //Activamos los botones de exportacion
            $this->active = null;

            $this->dispatchBrowserEvent('cargagrafic', []);

            return json_encode($listemit);
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
                $mesayerstr = ' de Enero ';
                break;
            case 2:
                $mesayerstr = ' de Febrero ';
                break;
            case 3:
                $mesayerstr = ' de Marzo ';
                break;
            case 4:
                $mesayerstr = ' de Abril ';
                break;
            case 5:
                $mesayerstr = ' de Mayo ';
                break;
            case 6:
                $mesayerstr = ' de Junio ';
                break;
            case 7:
                $mesayerstr = ' de Julio ';
                break;
            case 8:
                $mesayerstr = ' de Agosto ';
                break;
            case 9:
                $mesayerstr = ' de Septiembre ';
                break;
            case 10:
                $mesayerstr = ' de Octubre ';
                break;
            case 11:
                $mesayerstr = ' de Noviembre ';
                break;
            case 12:
                $mesayerstr = ' de Diciembre ';
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

        return view('livewire.monitoreo', ['meses' => $meses, 'anios' => $anios, 'fechaayer' => $fechaayerstr, 'empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'consulemit' => $this->ConsulEmit()])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
