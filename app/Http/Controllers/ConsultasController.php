<?php

namespace App\Http\Controllers;

use CFDItoJson;
use Cleaner;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\XmlE;
use App\Models\XmlR;

class ConsultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('consultas');
    }

    public function consultas1()
    {
        return view('consultas1');
    }

    public function historial()
    {
        $rfc = Auth::user()->RFC;
        $n = 0;
        $tXml = 0;
        $tTabla = 0;

        $col = DB::table('calendario_e')
            ->select('fechaDescarga', 'rfc', 'descargasEmitidos', 'erroresEmitidos')
            ->where('rfc', $rfc)
            ->orderBy('fechaDescarga', 'asc')
            ->get();

        return view('historial')
            ->with('n', $n)
            ->with('tXml', $tXml)
            ->with('tTabla', $tTabla)
            ->with('rfc', $rfc)
            ->with('col', $col);
    }

    public function store(Request $request)
    {
        $rfc = Auth::user()->RFC;
        $tipodes = $request->tipodes;
        $tipoFac = $request->tipoFac;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        if ($tipoFac == 'I') {
            if ($tipodes == "Emitidas") {
                $colI = XmlE::where(['Emisor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            } else {
                $colI = XmlR::where(['Receptor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            }
        } elseif ($tipoFac == 'E') {
            if ($tipodes == "Emitidas") {
                $colI = XmlE::where(['Emisor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            } else {
                $colI = XmlR::where(['Receptor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            }
        } elseif ($tipoFac == 'N') {
            if ($tipodes == "Emitidas") {
                $colI = XmlE::where(['Emisor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            } else {
                $colI = XmlR::where(['Receptor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            }
        } elseif ($tipoFac == 'P') {
            if ($tipodes == "Emitidas") {
                $colI = XmlE::where(['Emisor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            } else {
                $colI = XmlR::where(['Receptor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFac])
                    ->whereBetween('Fecha', array($fecha1 . "T00:00:00", $fecha2 . "T23:59:59"))
                    ->orderBy('Fecha', 'asc')
                    ->get();
            }
        }

        // $ing= $this->ingreso($data);
        // // $this->egreso($data);
        // // $this->pago($data);
        // // $this->nomina($data);

        return view('consultas1')
            ->with('tipodes', $tipodes)
            ->with('tipoFac', $tipoFac)
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('colI', $colI);
    }

    // public function ingreso($data){

    //     $rfc = Auth::user()->RFC;

    //     $fecha1i = $data['fecha1'];
    //     $fecha2i = $data['fecha2'];
    //     $tipoFaci = $data['tipoFac'];
    //     $tipodesi = $data['tipodes'];

    // if($tipodesi == "Emitidas"){

    //    $colI = XmlE::where(['Emisor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFaci])
    //                   ->get();
    // }else{
    //     $colI = XmlR::where(['Receptor.Rfc' => $rfc, 'TipoDeComprobante' => $tipoFaci])
    //                   ->get();

    // }

    //     return view('consultas1')
    //     ->with('fecha1', $fecha1i)
    //     ->with('fecha2', $fecha2i)
    //     ->with('tipoFac', $tipoFaci)
    //     ->with('tipodes', $tipodesi)
    //     ->with('colI', $colI);


    // }

    // public function egreso($data){

    // }

    // public function pago($data){

    // }

    // public function nomina($data){

    // }
}
