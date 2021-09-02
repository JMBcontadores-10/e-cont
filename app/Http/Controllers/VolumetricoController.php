<?php
namespace App\Http\Controllers;

use App\Models\Volumetrico;
use Illuminate\Http\Request;


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

  public function volumetrico1(Request $request)
  {
      $fech1 = $request->fech1;
      $acc = $request->accion;

      return view('volumetrico1', compact('fech1'))
            ->with('accion', $acc)
            ->with('fech1', $fech1);
  }

  public function insertaDatos(Request $request)
  {
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

      $idV =$rfcV."/".$fech1."/1";

      $volu = Volumetrico::create([

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
      ]);

      return view('volumetrico')
            ->with('volu', $volu);

  }

  public function updateDatos(Request $request)
  {
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

      $volum = Volumetrico::where('idV', $idV);

      $volum->update([
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
      ]);

      return view('volumetrico')
            // ->with('iiM', $iiM)
            // ->with('iiP', $iiP)
            // ->with('iiD', $iiD)
            // ->with('cM', $cM)
            // ->with('cP', $cP)
            // ->with('cD', $cD)
            // ->with('vM', $vM)
            // ->with('vP', $vP)
            // ->with('vD', $vD)
            // ->with('aM', $aM)
            // ->with('aP', $aP)
            // ->with('aD', $aD)
            // ->with('pM', $pM)
            // ->with('pP', $pP)
            // ->with('pD', $pD)
            ->with('volum', $volum);

  }
}
