<?php

namespace App\Http\Livewire;
use App\Models\MetadataR;
use Illuminate\Http\Request;
use App\Models\ListaNegra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use DateTime;
use DateTimeZone;

use Livewire\Component;

class CuentasPorpagar extends Component
{


    public $datos;
    public float $ajuste;

    public  $users, $name, $email, $user_id, $fecha, $ajuste2, $datos1, $user;
    public $cheque;
    public  float $importe = 0;
    public $condicion;
    public $revisado;


    public $mes;
    public $anio;
    public $todos;

    public $rfcEmpresa;
    public $rfc;


    //-----------------------



    //---------





    public function render()
    {



        if (Auth::check()) { /// autentica si se incio session
            $n = 0;
            //  auth()->user();

            //$rfc = auth()->user()->RFC;
            //$rfc = ;


            $tXml = 0;
            $tTabla = 0;
            $col =  0;

            /*
            $col = DB::collection('metadata_r')
             ->select('emisorNombre', 'emisorRfc')
             ->where('receptorRfc', $rfc)
             ->groupBy('emisorRfc')
             ->orderBy('emisorRfc', 'asc')
             ->get();
*/
        }







        if (!empty(auth()->user()->tipo)) {

            $e = array();
            $largo = sizeof(auth()->user()->empresas); // obtener el largo del array empresas


            for ($i = 0; $i < $largo; $i++) {

                $rfc = auth()->user()->empresas[$i];
                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')

                    ->where('RFC', $rfc)

                    ->get();

                foreach ($e as $em)


                    $emp[] = array($em['RFC'], $em['nombre']);

                $col = DB::collection('metadata_r')
                    ->select('emisorNombre', 'emisorRfc')
                    ->where('receptorRfc', $rfc)
                    ->groupBy('emisorRfc')
                    ->orderBy('emisorRfc', 'asc')
                    ->get();
            }
        } else {

            $emp = '';
        } //end if




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
        $anios = range(2014, date('Y'));





        return view('livewire.cuentas-porpagar', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp])
            ->extends('layouts.livewire-layout')
            ->section('content')
            ->with('n', $n)
            ->with('tXml', $tXml)
            ->with('tTabla', $tTabla)

            ->with('col', $col);
    }


    public function detalles(Request $req)
    {
        $meses = array(
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        );
        $rutaDescarga = config("descargamasiva.path");
        $rfc = Auth::user()->RFC;
        $emisorRfc = $req->emisorRfc;
        $emisorNombre = $req->emisorNombre;
        $n = 0;

        // Verifica si se eligieron varios proveedores o uno solo
        if ($req->has('allcheck') or $req->has('arrRfc')) {
            $emisorRfc = '';
            $emisorNombre = "Varios Proveedores";
            // Verifica si se envian los proveedores desde detallesCT en cheques y transferencias -
            // o directo de cuentas por pagar
            if ($req->has('arrRfc')) {
                $allch = json_decode($req->arrRfc, true);
            } else {
                $allch = $req->allcheck;
            }
            $colM = MetadataR::where(['receptorRfc' => $rfc])
                ->whereIn('emisorRfc', $allch)
                ->where('estado', '<>', 'Cancelado')
                ->whereNull('cheques_id')
                ->orderBY('emisorRfc')
                ->orderBy('fechaEmision', 'desc')
                ->paginate(50);
            // ->get();
        } else {
            $colM = MetadataR::where(['receptorRfc' => $rfc, 'emisorRfc' => $emisorRfc])
                ->where('estado', '<>', 'Cancelado')
                ->whereNull('cheques_id')
                ->orderBy('fechaEmision', 'desc')
                ->paginate(50);
            // ->get();
        }


        if (!empty(auth()->user()->tipo)) {

            $e = array();
            $largo = sizeof(auth()->user()->empresas); // obtener el largo del array empresas


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
        } //end if


        return view('detalles')
            ->with('meses', $meses)
            ->with('rutaDescarga', $rutaDescarga)
            ->with('rfc', $rfc)
            ->with('colM', $colM)
            ->with('n', $n)
            ->with('emisorRfc', $emisorRfc)
            ->with('emisorNombre', $emisorNombre);
    }
}
