<?php

namespace App\Http\Controllers;

use App\Models\MetadataR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CuentasPorPagar extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Método para generar la vista principal de cuentas por pagar
    public function index()
    {
        $rfc = Auth::user()->RFC;
        $n = 0;
        $tXml = 0;
        $tTabla = 0;

        $col = DB::collection('metadata_r')
            ->select('emisorNombre', 'emisorRfc')
            ->where('receptorRfc', $rfc)
            ->groupBy('emisorRfc')
            ->orderBy('emisorRfc', 'asc')
            ->get();

        return view('cuentasporpagar')
            ->with('n', $n)
            ->with('tXml', $tXml)
            ->with('tTabla', $tTabla)
            ->with('rfc', $rfc)
            ->with('col', $col);
    }

    // Método para mostrar los CFDIs pertenecientes a cada proveedor
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
