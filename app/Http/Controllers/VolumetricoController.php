<?php

namespace App\Http\Controllers;

use App\Models\Volumetrico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use DateTimeZone;
use DateTime;
use DateInterval;


class VolumetricoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('volumetrico');
    }

    public function convolu(Request $request)
    {

        $id1 = $request->id1;
        $id2 = $request->id2;

        $conVol = DB::table('volumetrico')
            ->where('RFC', Auth::user()->RFC)
            ->whereBetween('fech1', array($id1, $id2))
            ->orderBy('Fecha', 'asc')
            ->get();

        return view('convolumetrico')
               ->with('conVol', $conVol);
    }

    public function volumetrico1(Request $request)
    {
        $fech1 = $request->fech1;
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("$fech1", $dtz);

        $dt->sub(new DateInterval('P1D'));

        $anio = $dt->format('Y');
        $mes = $dt->format('m');
        $dia = $dt->format('d');
        $fechaFv = "$anio-$mes-$dia";
        $acc = $request->accion;

        return view('volumetrico1', compact('fech1'))
            ->with('accion', $acc)
            ->with('fech1', $fech1)
            ->with('fechaFv', $fechaFv);
    }

    public function insertaDatos(Request $request)
    {
        $meses = [
            '1.Enero',
            '2.Febrero',
            '3.Marzo',
            '4.Abril',
            '5.Mayo',
            '6.Junio',
            '7.Julio',
            '8.Agosto',
            '9.Septiembre',
            '10.Octubre',
            '11.Noviembre',
            '12.Diciembre',
        ];

        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfcV = $request->rfc;
        $anio = $dt->format('Y');

        $subir_archivo = basename($_FILES['archivoVol']['name']);
        $fech1 = $request->fech1;
        $f1 = new DateTime($fech1, $dtz);
        $mes = $f1->format('n');
        $mes1 = $mes-1;
        $arr = $meses[$mes1];
        $ruta = "storage/contarappv1_descargas/$rfcV/$anio/Volumetrico/$arr/";

        $subir_archivo = preg_replace('/[^A-z0-9.-]+/', '', $subir_archivo);
        $nombrec = "$fech1-$subir_archivo";
        $request->archivoVol->move($ruta, $nombrec);
        $num = "1";
        $fech1 = $request->fech1;
        $iiM = $request->invIniM;
        $iiP = $request->invIniP;
        $iiD = $request->invIniD;
        $cM = $request->comprasM;
        $cP = $request->comprasP;
        $cD = $request->comprasD;
        $vM = $request->ventasM;
        $vP = $request->ventasP;
        $vD = $request->ventasD;
        $aM = $request->autoM;
        $aP = $request->autoP;
        $aD = $request->autoD;
        $pM = $request->pventaM;
        $pP = $request->pventaP;
        $pD = $request->pventaD;
        $idM = $request->invDeterM;
        $idP = $request->invDeterP;
        $idD = $request->invDeterD;
        $mermaM = $request->mermaM;
        $mermaP = $request->mermaP;
        $mermaD = $request->mermaD;


        $idV = $rfcV . "/" . $fech1 . "/1";

        $volu = Volumetrico::create([

            'num' => $num,
            'idV' => $idV,
            'RFC' => $rfcV,
            'fech1'  => $fech1,
            'iiM'  => $iiM,
            'iiP' => $iiP,
            'iiD' => $iiD,
            'cM' => $cM,
            'cP' => $cP,
            'cD' => $cD,
            'vM' => $vM,
            'vP' => $vP,
            'vD' => $vD,
            'aM' => $aM,
            'aP' => $aP,
            'aD' => $aD,
            'pM' => $pM,
            'pP' => $pP,
            'pD' => $pD,
            'idM' => $idM,
            'idP' => $idP,
            'idD' => $idD,
            'mermaM' => $mermaM,
            'mermaP' => $mermaP,
            'mermaD' => $mermaD,
        ]);

        return view('volumetrico')
            ->with('volu', $volu);

    }

    public function updateDatos(Request $request)
    {
        $num = $request->num;
        $idV = $request->idV;
        $rfcV = $request->rfc;
        $fech1 = $request->fech1;
        $iiM = $request->invIniM;
        $iiP = $request->invIniP;
        $iiD = $request->invIniD;
        $cM = $request->comprasM;
        $cP = $request->comprasP;
        $cD = $request->comprasD;
        $vM = $request->ventasM;
        $vP = $request->ventasP;
        $vD = $request->ventasD;
        $aM = $request->autoM;
        $aP = $request->autoP;
        $aD = $request->autoD;
        $pM = $request->pventaM;
        $pP = $request->pventaP;
        $pD = $request->pventaD;
        $idM = $request->invDeterM;
        $idP = $request->invDeterP;
        $idD = $request->invDeterD;

        $volum = Volumetrico::where('idV', $idV);

        $volum->update([
            'num' => $num,
            'RFC' => $rfcV,
            'fech1'  => $fech1,
            'iiM'  => $iiM,
            'iiP' => $iiP,
            'iiD' => $iiD,
            'cM' => $cM,
            'cP' => $cP,
            'cD' => $cD,
            'vM' => $vM,
            'vP' => $vP,
            'vD' => $vD,
            'aM' => $aM,
            'aP' => $aP,
            'aD' => $aD,
            'pM' => $pM,
            'pP' => $pP,
            'pD' => $pD,
            'idM' => $idM,
            'idP' => $idP,
            'idD' => $idD,
        ]);

        return view('volumetrico')
            ->with('volum', $volum);
    }

    public function updatePrecio(Request $request){

        $num = "2";
        $fech1 = $request->fech1;
        $rfcV = $request->rfc;
        $iiM = $request->invIniM;
        $iiP = $request->invIniP;
        $iiD = $request->invIniD;
        $cM = $request->comprasM;
        $cP = $request->comprasP;
        $cD = $request->comprasD;
        $vM = $request->ventasM;
        $vP = $request->ventasP;
        $vD = $request->ventasD;
        $aM = $request->autoM;
        $aP = $request->autoP;
        $aD = $request->autoD;
        $pM = $request->pventaM;
        $pP = $request->pventaP;
        $pD = $request->pventaD;
        $idM = $request->invDeterM;
        $idP = $request->invDeterP;
        $idD = $request->invDeterD;

        $idV = $rfcV . "/" . $fech1 . "/2";

        $volu = Volumetrico::create([

            'num' => $num,
            'idV' => $idV,
            'RFC' => $rfcV,
            'fech1'  => $fech1,
            'iiM'  => $iiM,
            'iiP' => $iiP,
            'iiD' => $iiD,
            'cM' => $cM,
            'cP' => $cP,
            'cD' => $cD,
            'vM' => $vM,
            'vP' => $vP,
            'vD' => $vD,
            'aM' => $aM,
            'aP' => $aP,
            'aD' => $aD,
            'pM' => $pM,
            'pP' => $pP,
            'pD' => $pD,
            'idM' => $idM,
            'idP' => $idP,
            'idD' => $idD,
        ]);

        return view('volumetrico')
            ->with('volu', $volu);

    }
}
