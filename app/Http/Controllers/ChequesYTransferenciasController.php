<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\Cheques;
use App\Models\MetadataR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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
                ->orderBy('fecha', 'desc')
                // ->paginate(50);
                ->get();
        } else {
            $colCheques = Cheques::where(['rfc' => $rfc])
                ->orderBy('fecha', 'desc')
                // ->paginate(50);
                ->get();
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
                $colCheques = Cheques::where(['rfc' => $rfc])
                    ->where('verificado', '=', 0)
                    ->orderBy('fecha', 'desc')
                    ->get();
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
        $files = $_FILES['doc_relacionados']['name'];
        $rutaDescargaDR = "storage/contarappv1_descargas/$rfc/$anio/Cheques_Transferencias/Documentos_Relacionados/";

        if (!$files['0'] == '') {
            $n = 0;
            foreach ($files as $f) {
                $nombreArchivo = preg_replace('/[^A-z0-9.]+/', '', $f);
                $nombreArchivo = "$Id-$nombreArchivo";
                $r->doc_relacionados[$n]->move($rutaDescargaDR, $nombreArchivo);
                $n++;
                $pushArchivos[] = $nombreArchivo;
            }
        } else {
            $pushArchivos = '';
        }

        if ($r->has('id')) {

            if ($subir_archivo == '') {
                $nombrec = $r->nombrec;
            } else {
                $subir_archivo = preg_replace('/[^A-z0-9.]+/', '', $subir_archivo);
                $nombrec = "$Id-$subir_archivo";
                $r->subir_archivo->move($rutaDescarga, $nombrec);
            }

            $_id = $r->id;
            $cheque = Cheques::where('_id', $_id);
            $cheque->update([
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

            if (!$files['0'] == '') {
                $chequef = $cheque->first();
                if (!$chequef->doc_relacionados == null) {
                    foreach ($chequef->doc_relacionados as $c) {
                        $rutaArchivo =  $rutaDescargaDR . $c;
                        File::delete($rutaArchivo);
                    }
                }
                $cheque->unset('doc_relacionados');
                $cheque->push('doc_relacionados', $pushArchivos);
            }

            $alerta = 'Cheque actualizado exitosamente.';
            $this->alerta($alerta, $ruta);
        } else {

            if ($subir_archivo == '') {
                $nombrec = '0';
            } else {
                $subir_archivo = preg_replace('/[^A-z0-9.]+/', '', $subir_archivo);
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
            ])->push('doc_relacionados', $pushArchivos);

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
            // if ($metar->efecto == 'Pago') {
            //     $xmlr = XmlR::where('UUID', $i)->first();
            //     $docRel = $xmlr['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
            //     foreach ($docRel as $d) {
            //         $uuidRef = $d['IdDocumento'];
            //         echo "$uuidRef <br>";
            //     }
            // }
            // dd($docRel);
            // dd($metar);
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
        $colM = $c->metadata_r
            ->sortBy([
                ['emisorRfc', 'asc'],
                ['fechaEmision', 'desc'],
            ]);
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
        $rfc = Auth::user()->RFC;
        $rutaDescargaDR = "storage/contarappv1_descargas/$rfc/2021/Cheques_Transferencias/Documentos_Relacionados/";
        $id = $r->id;
        $rutaArchivo = $r->rutaArchivo;
        File::delete($rutaArchivo);
        $cheque = Cheques::where(['_id' => $id])->get()->first();
        if (!$cheque->doc_relacionados == null) {
            foreach ($cheque->doc_relacionados as $c) {
                $rutaArchivo =  $rutaDescargaDR . $c;
                File::delete($rutaArchivo);
            }
        }
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
