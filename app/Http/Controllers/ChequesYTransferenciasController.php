<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\Cheques;
use App\Models\MetadataR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class ChequesYTransferenciasController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $r)
    {
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
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
        $n = 0;
        $rutaDescarga = "storage/contarappv1_descargas/$rfc/$anio/Cheques_Transferencias/";


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

        if ($r->has('revisado')) {
            $id = $r->id;
            Cheques::where('_id', $id)->update([
                'verificado' => 1
            ]);
            return back();
        }

        if ($r->has('conta')) {
            $id = $r->id;
            Cheques::where('_id', $id)->update([
                'conta' => 1
            ]);
            return back();
        }

        if ($r->has('ajuste')) {
            $id = $r->id;
            $ajuste = (float)str_replace(',', '', $r->ajuste);
            Cheques::where('_id', $id)->update([
                'ajuste' => $ajuste
            ]);
            return back();
        }

        return view('chequesytransferencias')
            ->with('rutaDescarga', $rutaDescarga)
            ->with('n', $n)
            ->with('colCheques', $colCheques)
            ->with('anios', $anios)
            ->with('meses', $meses);
    }

    public function vincularCheque(Request $r)
    {

        if ($r->has('editar')) {
            $editar = $r->editar;
            $id = $r->id;
            $tipo = $r->tipo;
            $numCheque = $r->numCheque;
            $fechaCheque = $r->fechaCheque;
            $importeCheque = $r->importeCheque;
            $importeT = $r->importeT;
            $beneficiario = $r->beneficiario;
            $tipoOperacion = $r->tipoOperacion;
            $subirArchivo = $r->subirArchivo;
            $nombrec = $r->nombrec;
            return view('vincular-cheque')
                ->with('nombrec', $nombrec)
                ->with('id', $id)
                ->with('tipo', $tipo)
                ->with('numCheque', $numCheque)
                ->with('fechaCheque', $fechaCheque)
                ->with('importeCheque', $importeCheque)
                ->with('importeT', $importeT)
                ->with('beneficiario', $beneficiario)
                ->with('tipoOperacion', $tipoOperacion)
                ->with('subirArchivo', $subirArchivo)
                ->with('editar', $editar);
        } else {
            if ($r->has('totalXml')) {
                $vincular = true;
                $rfc = Auth::user()->RFC;
                $colCheques = Cheques::where(['rfc' => $rfc])->orderBy('fecha', 'desc')->get();
                $totalXml = $r->totalXml;
                $totalXml = substr($totalXml, 1);
                $allcheck = $r->allcheck;
            } else {
                $vincular = false;
                $colCheques = false;
                $totalXml = false;
                $allcheck = false;
            }

            $editar = false;
            return view('vincular-cheque')
                ->with('allcheck', $allcheck)
                ->with('totalXml', $totalXml)
                ->with('colCheques', $colCheques)
                ->with('vincular', $vincular)
                ->with('editar', $editar);
        }
    }

    public function desvincularCheque(Request $r)
    {
        $allcheck = $r->allcheck;
        $cheques_id = $r->cheques_id;
        $nXml = 0;
        foreach ($allcheck as $i) {
            $nXml++;
            MetadataR::where('folioFiscal', $i)
                ->update([
                    'cheques_id' => null,
                ]);
        }

        $totalXml = $r->totalXml;
        $totalXml = substr($totalXml, 1);
        $totalXml = (float)str_replace(',', '', $totalXml);
        $cheque_tXml = Cheques::find($cheques_id);
        $importeXml = $cheque_tXml->importexml - $totalXml;
        $faltaxml = $cheque_tXml->faltaxml - $nXml;
        $cheque_tXml->update([
            'importexml' => $importeXml,
            'faltaxml' => $faltaxml,
        ]);

        $alerta = 'CFDI(s) desvinculado(s) exitosamente.';
        $ruta = 'cheques-transferencias';
        $this->alerta($alerta, $ruta);
    }

    public function createUpdateCheque(Request $r)
    {
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $rutaDescarga = "storage/contarappv1_descargas/$rfc/$anio/Cheques_Transferencias/";
        $subir_archivo = basename($_FILES['subir_archivo']['name']);
        $Id = $dt->format('YFd\Hh\Mi\SsA');
        $tipo = $r->tipo;
        $numCheque = $r->numCheque;
        $fechaCheque = $r->fechaCheque;
        $importeCheque = (float)str_replace(',', '', $r->importeCheque);
        $beneficiario = $r->beneficiario;
        $tipoOperacion = $r->tipoOperacion;
        $rnfcrep = '0';
        $importeT = (float)str_replace(',', '', $r->importeT);
        $verificado = 0;
        $faltaxml = 0;
        $conta = 0;
        $pendi = 0;
        $lista = 0;
        $ajuste = 0;
        $ruta = 'cheques-transferencias';

        if ($r->has('id')) {

            if ($subir_archivo == '') {
                $nombrec = $r->nombrec;
            } else {
                $nombrec = "$Id-$subir_archivo";
                $r->subir_archivo->move($rutaDescarga, $nombrec);
            }

            $_id = $r->id;
            Cheques::where('_id', $_id)
                ->update([
                    'Id' => $Id,
                    'tipomov' => $tipo,
                    'numcheque' => $numCheque,
                    'fecha' => $fechaCheque,
                    'importecheque' => $importeCheque,
                    'importexml' => $importeT,
                    'Beneficiario' => $beneficiario,
                    'tipoopera' => $tipoOperacion,
                    'nombrec' => $nombrec,
                ]);

            $alerta = 'Cheque actualizado exitosamente.';
            $this->alerta($alerta, $ruta);
        } else {

            if ($subir_archivo == '') {
                $nombrec = '0';
            } else {
                $nombrec = "$Id-$subir_archivo";
                $r->subir_archivo->move($rutaDescarga, $nombrec);
            }

            Cheques::create([
                'Id' => $Id,
                'tipomov' => $tipo,
                'numcheque' => $numCheque,
                'fecha' => $fechaCheque,
                'importecheque' => $importeCheque,
                'Beneficiario' => $beneficiario,
                'tipoopera' => $tipoOperacion,
                'rfc' => $rfc,
                'nombrec' => $nombrec,
                'rnfcrep' => $rnfcrep,
                'importexml' => $importeT,
                'verificado' => $verificado,
                'faltaxml' => $faltaxml,
                'conta' => $conta,
                'pendi' => $pendi,
                'lista' => $lista,
                'ajuste' => $ajuste,
            ]);

            $alerta = 'Cheque creado exitosamente.';
            $this->alerta($alerta, $ruta);
        }
    }

    public function agregarXmlCheque(Request $r)
    {
        $cheque_id = $r->selectCheque;
        $allcheck = $r->allcheck;
        $arrcheck = json_decode($allcheck, true);
        $nXml = 0;
        foreach ($arrcheck as $i) {
            $nXml++;
            $metar = MetadataR::where('folioFiscal', $i)->first();
            $cheque = Cheques::find($cheque_id);
            $cheque->metadata_r()->save($metar);
        }

        $cheque_tXml = Cheques::find($cheque_id);
        $totalXml = (float)str_replace(',', '', $r->totalXml);
        $importeXml = $cheque_tXml->importexml + $totalXml;
        $faltaxml = $cheque_tXml->faltaxml + $nXml;
        $cheque_tXml->update([
            'importexml' => $importeXml,
            'faltaxml' => $faltaxml,
        ]);

        $alerta = 'Cheque vinculado exitosamente.';
        $ruta = 'cheques-transferencias';
        $this->alerta($alerta, $ruta);
    }

    public function detallesCT(Request $r)
    {
        $rfc = Auth::user()->RFC;
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
        $verificado = $r->verificado;
        $cheque_id = $r->id;
        $c = Cheques::find($cheque_id);
        $colM = $c->metadata_r;
        $n = 0;
        return view('detallesCT')
            ->with('verificado', $verificado)
            ->with('id', $cheque_id)
            ->with('meses', $meses)
            ->with('rfc', $rfc)
            ->with('colM', $colM)
            ->with('n', $n);
    }

    public function deleteCheque(Request $r)
    {
        $id = $r->id;
        $rutaArchivo = $r->rutaArchivo;
        File::delete($rutaArchivo);
        $cheque = Cheques::where(['_id' => $id])->get()->first();
        $colM = $cheque->metadata_r;
        foreach ($colM as $i) {
            MetadataR::where('cheques_id', $i->cheques_id)
                ->update([
                    'cheques_id' => null,
                ]);
        }
        $cheque->delete();

        $alerta = 'Cheque eliminado exitosamente.';
        $ruta = 'cheques-transferencias';
        $this->alerta($alerta, $ruta);
    }

    public function alerta($mensaje, $ruta)
    {
        echo "<script>alert('$mensaje'); window.location = '$ruta';</script>";
    }
}
