<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MetadataE;

class MonitoreoController extends Controller
{
  //   public function index()
  // {
  //   return view('monitoreo');
  // }

  public function index()
  {
    $hoy = date("d-M-Y");
    $ayer = date("d-M-Y", strtotime($hoy."- 1 days"));
    $rfc = Auth::user()->RFC;
    $n=0;
    $tXml=0;
    $tTabla=0;
    $sum=0;
    $nXml = 0;

    $col = DB::table('metadata_e')
        ->select('emisorRfc', 'emisorNombre', 'receptorNombre', 'receptorRfc','total', 'fechaEmision')
        ->where('emisorRfc',$rfc, 'fechaEmision', $ayer)
        ->groupBy('receptorRfc', 'receptorNombre')
        ->orderBy('receptorRfc', 'asc')
        ->get();
  

    return view('monitoreo')
        ->with('n', $n)
        ->with('tXml', $tXml)
        ->with('tTabla', $tTabla)
        ->with('rfc', $rfc)
        ->with('col', $col);
    
    
  }

  public function detallesfactura(Request $req)
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
        // $config = require dirname(dirname(__FILE__)) . '/Classes' . '/config.php';
        // $rutaDescarga = $config['rutaDescarga'];
        $rfc = Auth::user()->RFC;
        $receptorRfc = $req->receptorRfc;
        $receptorNombre = $req->receptorNombre;
        $n = 0;

        $colF = DB::table('metadata_e')
                ->select('estado', 'fechaEmision', 'folioFiscal', 'receptorRfc', 'receptorNombre', 'total')
                ->where(['receptorRfc' => $receptorRfc])
                ->orderBy('folioFiscal')->get();

        return view('detallesfactura')
            ->with('meses', $meses)
            ->with('rfc', $rfc)
            ->with('colF', $colF)
            ->with('n', $n)
            ->with('receptorRfc', $receptorRfc)
            ->with('receptorNombre', $receptorNombre);
    }
}
