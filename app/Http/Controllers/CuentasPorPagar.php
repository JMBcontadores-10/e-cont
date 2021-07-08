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

        $col = DB::table('metadata_r')
            ->select('emisorNombre', 'emisorRfc')
            ->where('receptorRfc', $rfc)
            ->groupBy('emisorRfc')
            ->orderBy('emisorNombre', 'asc')
            ->get();

        // $col = DB::collection('xml_r')
        //     ->select('0.Emisor.Rfc','0.Receptor.Nombre')
        //     // ->where(['0.Receptor.Rfc' => $rfc])
        //     ->where('0.Receptor.Rfc', $rfc)
        //     ->distinct('0.Emisor.Rfc')
        //     ->orderBy('0.Emisor.Rfc', 'asc')
        //     ->get();

        // dd($col);
        // $col = XmlR::where(['0.Receptor.Rfc' => $rfc])
        //     ->groupBy(['0.Emisor.Rfc'])
        //     // ->orderBy([''],'asc')
        //     ->get();

        // $arr = array();
        // foreach ($col as $v) {
        //     $var = $v['0']['Emisor']['Rfc'];
        //     $arr[] = (array)$var;
        // }
        // dd($arr);

        return view('cuentasporpagar')
            ->with('n', $n)
            ->with('tXml', $tXml)
            ->with('tTabla', $tTabla)
            ->with('rfc', $rfc)
            ->with('col', $col);
    }

    public function detalles(Request $req)
    {

        $rfc = Auth::user()->RFC;
        $emisorRfc = $req->emisorRfc;
        $emisorNombre = $req->emisorNombre;
        $n = 0;

        $colM = MetadataR::where(['receptorRfc' => $rfc, 'emisorRfc' => $emisorRfc])->orderBy('folioFiscal')->get();

        return view('detalles')
            ->with('colM', $colM)
            ->with('n', $n)
            ->with('emisorRfc', $emisorRfc)
            ->with('emisorNombre', $emisorNombre);
    }
}
