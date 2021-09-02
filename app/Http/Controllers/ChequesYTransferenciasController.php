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

    // Método de la vista principal de cheques y transferencias
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

        // Genera la consulta dependiendo el mes y año seleccionado
        if ($r->has('mes')) {
            $mes = $r->mes;
            $anio = $r->anio;
            if ($r->mes == '00') {
                $fechaF = '';
            } else {
                $fechaF = "$anio-$mes-";
            }
            $colCheques = Cheques::where('rfc', $rfc)
                ->where('fecha', 'like', $fechaF . '%')
                ->orderBy('pendi', 'desc')
                ->orderBy('verificado')
                ->orderBy('conta')
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(100);
            // ->get();
        } else {
            $colCheques = Cheques::where(['rfc' => $rfc])
                ->orderBy('pendi', 'desc')
                ->orderBy('verificado')
                ->orderBy('conta')
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(100);
            // ->get();
        }

        // Actualiza los valores si el cheque es verificado
        if ($r->has('revisado')) {
            $id = $r->id;
            Cheques::where('_id', $id)->update([
                'verificado' => 1,
                'pendi' => 0,
                'revisado_fecha' => $dt->format('Y-m-d\TH:i:s'),
            ]);
            return back();
        }

        // Actualiza los valores si el cheque es contabilizado
        if ($r->has('conta')) {
            $id = $r->id;
            Cheques::where('_id', $id)->update([
                'conta' => 1,
                'contabilizado_fecha' => $dt->format('Y-m-d\TH:i:s'),
                'poliza' => $r->poliza,
            ]);
            return back();
        }

        // Actualiza los valores si el cheque es ajustado
        if ($r->has('ajuste')) {
            $id = $r->id;
            $ajuste = (float)str_replace(',', '', $r->ajuste);
            Cheques::where('_id', $id)->update([
                'ajuste' => $ajuste
            ]);
            return back();
        }

        // Actualiza los valores si el cheque es comentado
        if ($r->has('comentario')) {
            $id = $r->id;
            Cheques::where('_id', $id)->update([
                'comentario' => $r->comentario
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

    // Método para generar la vista de vinculación de cheques
    public function vincularCheque(Request $r)
    {
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $date = $dt->format('Y-m-d');

        // Verifica si el cheque será editado o creado
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
                ->with('date', $date)
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
            // Verifica si el cheque será creado con una vinculación de proveedores de cuentas por pagar o desde cero
            if ($r->has('totalXml')) {
                $vincular = true;
                $rfc = Auth::user()->RFC;
                $colCheques = Cheques::where(['rfc' => $rfc])
                    ->where('verificado', '=', 0)
                    ->orderBy('fecha', 'desc')
                    ->orderBy('updated_at', 'desc')
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
                ->with('date', $date)
                ->with('allcheck', $allcheck)
                ->with('totalXml', $totalXml)
                ->with('colCheques', $colCheques)
                ->with('vincular', $vincular)
                ->with('editar', $editar);
        }
    }

    // Método para desvincular los cheques
    public function desvincularCheque(Request $r)
    {
        $allcheck = $r->allcheck;
        $cheques_id = $r->cheques_id;
        $nXml = 0;
        // Revisa todos los UUID de los CFDI seleccionados y elimina la vinculación con cheques
        foreach ($allcheck as $i) {
            $nXml++;
            MetadataR::where('folioFiscal', $i)
                ->update([
                    'cheques_id' => null,
                ]);
        }

        // Actualiza el monto y cantidad de CFDIs desvinculados para actualizar la colección cheques
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

    // Método para la creación o actualización de cheques
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

        // Verifica si existen documentos relacionados
        if (!$files['0'] == '') {
            $n = 0;
            // Revisa todos los documentos enviados y los almacena eliminado caracteres no alfanuméricos
            // y concatenando la fecha de creación en el nombre
            foreach ($files as $f) {
                $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $f);
                $nombreArchivo = "$Id-$nombreArchivo";
                $r->doc_relacionados[$n]->move($rutaDescargaDR, $nombreArchivo);
                $n++;
                $pushArchivos[] = $nombreArchivo;
            }
        } else {
            $pushArchivos = '';
        }

        // Verifica si será actualización o creación del cheque a través del id
        if ($r->has('id')) {

            // Verifica si existe el archivo principal
            if ($subir_archivo == '') {
                $nombrec = $r->nombrec;
            } else {
                // Almacena eliminado caracteres no alfanuméricos y concatenando la fecha de creación en el nombre
                $subir_archivo = preg_replace('/[^A-z0-9.-]+/', '', $subir_archivo);
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

            // Si existen documentos adicionales se añaden como arreglo a la colección -
            // reiniciando el arreglo y eliminando los archivos que estuviesen vinculados anteriormente
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

            // Verifica si existe el archivo principal
            if ($subir_archivo == '') {
                $nombrec = '0';
            } else {
                // Almacena eliminado caracteres no alfanuméricos y concatenando la fecha de creación en el nombre
                $subir_archivo = preg_replace('/[^A-z0-9.-]+/', '', $subir_archivo);
                $nombrec = "$Id-$subir_archivo";
                $r->subir_archivo->move($rutaDescarga, $nombrec);
            }

            $chequeC = Cheques::create([
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

            // Añade los documentos relacionados como arreglo a la colección
            $chequeC->push('doc_relacionados', $pushArchivos);

            // Si el cheque es creado con vinculación de proveedores desde cuentas por pagar -
            // vincula inmediatamente los CFDIs seleccionados
            if ($r->has('allcheck')) {
                $cheque_id = $chequeC->_id;
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
                $faltaxml = $cheque_tXml->faltaxml + $nXml;
                $cheque_tXml->update([
                    'faltaxml' => $faltaxml,
                ]);

                $alerta = 'Cheque creado y vinculado exitosamente.';
                $this->alerta($alerta, $ruta);
            } else {
                $alerta = 'Cheque creado exitosamente.';
                $this->alerta($alerta, $ruta);
            }
        }
    }

    // Método para vincular los CFDIs a cheque
    public function agregarXmlCheque(Request $r)
    {
        $cheque_id = $r->selectCheque;
        $allcheck = $r->allcheck;
        $arrcheck = json_decode($allcheck, true);
        $nXml = 0;
        // Obtiene los UUID de los CFDIs seleccionados y vincula cada uno con el cheque elegido
        foreach ($arrcheck as $i) {
            $nXml++;
            $metar = MetadataR::where('folioFiscal', $i)->first();
            $cheque = Cheques::find($cheque_id);
            $cheque->metadata_r()->save($metar);
        }

        // Actualiza el monto y cantidad de CFDIs vinculados para actualizar la colección cheques
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

    // Método para mostrar la vista detallesCT de CDFIs vinculados
    public function detallesCT(Request $r)
    {
        // Obtiene los CFDIs de MetadataR vinculados al cheque
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

    // Método para eliminar los cheques con archivos y CFDIs vinculados
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

    // Genera una alerta y cambia la ruta
    public function alerta($mensaje, $ruta)
    {
        echo "<script>alert('$mensaje'); window.location = '$ruta';</script>";
    }
}
