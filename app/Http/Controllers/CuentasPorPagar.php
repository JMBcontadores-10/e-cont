<?php

namespace App\Http\Controllers;

use App\Models\MetadataR;
use App\Models\XmlR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CuentasPorPagar extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $config = require dirname(dirname(__FILE__)) . '/Classes' . '/config.php';
        $rutaDescarga = $config['rutaDescarga'];
        $rfc = Auth::user()->RFC;
        $emisorRfc = $req->emisorRfc;
        $emisorNombre = $req->emisorNombre;
        $n = 0;

        if ($req->has('allcheck')) {
            $emisorRfc = '';
            $emisorNombre = "Varios Proveedores";
            $allch = $req->allcheck;
            $colM = MetadataR::where(['receptorRfc' => $rfc])
                ->whereIn('emisorRfc', $allch)
                ->whereNull('cheques_id')
                ->orderBY('emisorRfc')
                ->orderBy('fechaEmision')
                ->get();
        } else {
            $colM = MetadataR::where(['receptorRfc' => $rfc, 'emisorRfc' => $emisorRfc])
                ->whereNull('cheques_id')
                ->orderBy('fechaEmision')
                ->get();
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
