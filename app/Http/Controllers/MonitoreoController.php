<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MetadataE;
use DateTimeZone;
use DateTime;
use DateInterval;

class MonitoreoController extends Controller
{

    public function index()
    {
        set_time_limit(90);
        $rfcC = Auth::user()->RFC;
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);

        $dt->sub(new DateInterval('P1D'));

        $anio = $dt->format('Y');
        $mes = $dt->format('m');
        $dia = $dt->format('d');
        $fechaF = "$anio-$mes-$dia";
        $fecha1 = $fechaF . "T00:00:00";
        $fecha2 = $fechaF . "T23:59:59";

        $rfc = Auth::user()->RFC;


        $col = DB::table('metadata_e')
            ->select('receptorNombre', 'receptorRfc')
            ->where('emisorRfc', $rfc)
            ->whereBetween('fechaEmision', array($fecha1, $fecha2))
            ->groupBy('receptorRfc')
            ->orderBy('receptorRfc', 'asc')
            ->get();



        return view('monitoreo')

            ->with('rfc', $rfc)
            ->with('col', $col)
            ->with('fechaF', $fechaF)
            ->with('fecha2', $fecha2)
            ->with('fecha1', $fecha1);
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

        $rfc = Auth::user()->RFC;
        $receptorRfc = $req->receptorRfc;
        $receptorNombre = $req->receptorNombre;
        $n = 0;

        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);

        if (isset($argv[1])) {
            $dt->sub(new DateInterval($argv[1]));
        } else {
            $dt->sub(new DateInterval('P1D'));
        }

        $anio = $dt->format('Y');
        $mes = $dt->format('m');
        $dia = $dt->format('d');
        $fechaF = "$anio-$mes-$dia";
        $fecha1 = $fechaF . "T00:00:00";
        $fecha2 = $fechaF . "T23:59:59";

        $colF = DB::table('metadata_e')
            ->select('estado', 'fechaEmision', 'folioFiscal', 'receptorRfc', 'folioFiscal', 'fechaCertificacion', 'receptorNombre', 'total')
            ->where('receptorRfc', $receptorRfc)
            ->whereBetween('fechaEmision', array($fecha1, $fecha2))
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
