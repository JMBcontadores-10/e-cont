<?php

namespace App\Http\Controllers;

use App\Models\Cheques;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChequesYTransferenciasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $r)
    {
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
        $rfc = Auth::user()->RFC;
        $n = 0;

        if ($r->has('mes')) {
            $mes = $r->mes;
            $anio = $r->anio;
            $fechaF = "$anio-$mes-";
            $colCheques = Cheques::where('rfc', $rfc)
                ->where('fecha', 'like', $fechaF . '%')
                ->orderBy('fecha', 'desc')->get();
        } else {
            $colCheques = Cheques::where(['rfc' => $rfc])->orderBy('fecha', 'desc')->get();
        }

        return view('chequesytransferencias')
            ->with('n', $n)
            ->with('colCheques', $colCheques)
            ->with('anios', $anios)
            ->with('meses', $meses);
    }

    public function vincularCheque()
    {
        return view('vincular-cheque');
    }

    public function archivoPagar(){
        $subir_archivo = basename($_FILES['subir_archivo']['name']);
        dd($subir_archivo);
        // $alerta = "Cheque registrado exitosamente.";
        // $this->alerta($alerta);
        // return view('vincular-cheque');
    }

    public function deleteCheque(Request $r)
    {
        $id = $r->id;
        $cheque = Cheques::where(['_id' => $id])->get()->first();
        $cheque->delete();

        return back();
    }

    public function alerta($mensaje)
    {
        echo "<script>alert('$mensaje');</script>";
    }
}
