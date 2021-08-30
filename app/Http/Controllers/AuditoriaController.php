<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MetadataE;
use App\Models\MetadataR;

class AuditoriaController extends Controller
{
    public function index(){

        return view('auditoria');
    }

    public function store(Request $request){
        $rfc = Auth::user()->RFC;
        $tipoer = $request->tipoer;
        $fecha1er = $request->fecha1er;
        $fecha2er = $request->fecha2er;

        if ($tipoer == "Emitidas") {
            $colA= MetadataE::where('emisorRfc', $rfc)
                ->whereBetween('fechaEmision', array($fecha1er . "T00:00:00", $fecha2er . "T23:59:59"))
                ->orderBy('fechaEmision', 'asc')
                ->get();
        } else {
            $colA = MetadataR::where('receptorRfc', $rfc)
                ->whereBetween('fechaEmision', array($fecha1er . "T00:00:00", $fecha2er . "T23:59:59"))
                ->orderBy('fechaEmision', 'asc')
                ->get();
        }

        return view('auditoria1')
            ->with('tipoer', $tipoer)
            ->with('fecha1er', $fecha1er)
            ->with('fecha2er', $fecha2er)
            ->with('colA', $colA);;




    }
}
