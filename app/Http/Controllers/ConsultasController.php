<?php

namespace App\Http\Controllers;
use CFDItoJson;
use Cleaner;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

  public function consultas()
  {
    return view('consultas1');
  }


  public function historial(){
    $rfc = Auth::user()->RFC;
    $n=0;
    $tXml=0;
    $tTabla=0;

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

}
