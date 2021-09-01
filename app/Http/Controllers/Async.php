<?php

namespace App\Http\Controllers;

use App\Models\XmlR;
use App\Models\XmlE;
use DirectoryIterator;
use App\Models\MetadataE;
use App\Models\MetadataR;
use App\Models\CalendarioE;
use App\Models\CalendarioR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Classes\UtilCertificado;
use PhpCfdi\CfdiToJson\JsonConverter;
use App\Http\Classes\BusquedaEmitidos;
use App\Http\Classes\BusquedaRecibidos;
use App\Http\Classes\DescargaAsincrona;
use Illuminate\Support\Facades\Storage;
use App\Http\Classes\DescargaMasivaCfdi;
use Illuminate\Support\Facades\Log;
use PhpCfdi\CfdiCleaner\Cleaner;

class Async extends Controller
{
    public function index(Request $r)
    {

        // Obtener configuracion
        $config = require dirname(dirname(__FILE__)) . '/Classes' . '/config.php';

        // Preparar variables
        $rutaDescarga = $config['rutaDescarga'];
        $maxDescargasSimultaneas = $config['maxDescargasSimultaneas'];

        $dircer = "storage/" . Auth::user()->dircer;
        $dirkey = "storage/" . Auth::user()->dirkey;
        $pwd = Auth::user()->pass;
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

        // Instanciar clase principal
        $descargaCfdi = new DescargaMasivaCfdi();

        // Función que regresa la sesión y los mensajes o items descargados
        function json_response($data, $success = true)
        {

            header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
            header('Content-Type: application/json');

            return json_encode(array(
                'success' => $success,
                'data' => $data
            ));
        }

        if (!empty($_POST)) {

            if (!empty($_POST['sesion'])) {
                $descargaCfdi->restaurarSesion($_POST['sesion']);
            }

            $accion = empty($_POST['accion']) ? 'login_fiel' : $_POST['accion'];

            if ($accion == 'login_fiel') {

                if (!empty($_POST['sesion'])) {
                    $sesion = $descargaCfdi->obtenerSesion();
                    unset($sesion);
                }

                if (!empty($dircer) && !empty($dirkey) && !empty($pwd)) {


                    // preparar certificado para inicio de sesion
                    $certificado = new UtilCertificado();
                    $ok = $certificado->loadFiles(
                        $dircer,
                        $dirkey,
                        $pwd
                    );

                    if ($ok) {
                        // iniciar sesion en el SAT
                        $ok = $descargaCfdi->iniciarSesionFiel($certificado);
                        if ($ok) {
                            echo json_response(array(
                                'mensaje' => 'Se ha iniciado la sesión',
                                'sesion' => $descargaCfdi->obtenerSesion()
                            ));
                        } else {
                            echo json_response(array(
                                'mensaje' => 'Ha ocurrido un error al iniciar sesión. Intente nuevamente',
                            ));
                        }
                    } else {
                        echo json_response(array(
                            'mensaje' => 'Verifique que los archivos corresponden con la contraseña e intente nuevamente',
                        ));
                    }
                } else {
                    echo json_response(array(
                        'mensaje' => 'Proporcione todos los datos',
                    ));
                }
            } elseif ($accion == 'buscar-recibidos') {
                // Se instancia la clase que recibirá las fechas enviadas por el usuario
                $filtros = new BusquedaRecibidos();
                $filtros->establecerFecha($_POST['anio'], $_POST['mes'], $_POST['dia']);
                $anio = $_POST['anio'];
                $mes = $_POST['mes'];

                // Genera el arreglo con los CFDI de la fecha establecida
                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    $items = array();
                    $rutaPdf = $rutaDescarga . "$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/PDF";
                    $rutaXml = $rutaDescarga . "$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/XML";
                    foreach ($xmlInfoArr as $index => $xmlInfo) {
                        $arr[] = (array)$xmlInfo;
                        $uuid = $xmlInfo->folioFiscal;
                        $pdf = $this->dirIteratorPdf($rutaPdf, $uuid);
                        $xml = $this->dirIteratorXml($rutaXml, $uuid);
                        $acuse = $this->dirIteratorPdfAcuse($rutaPdf, $uuid);
                        $arr2 = array(
                            'descargadoPdf' => $pdf,
                            'descargadoXml' => $xml,
                            'descargadoAcuse' => $acuse,
                        );
                        $items[] = array_merge($arr[$index], $arr2);
                    }
                    // Se envia el arreglo $items y la sesión para que se genere la tabla con js
                    echo json_response(array(
                        'items' => $items,
                        'sesion' => $descargaCfdi->obtenerSesion(),
                    ));
                } else {
                    echo json_response(array(
                        'mensaje' => 'No se han encontrado CFDIs',
                        'sesion' => $descargaCfdi->obtenerSesion()
                    ));
                }
            } elseif ($accion == 'buscar-emitidos') {
                // Se instancia la clase que recibirá las fechas enviadas por el usuario
                $filtros = new BusquedaEmitidos();
                $filtros->establecerFechaInicial($_POST['anio_i'], $_POST['mes_i'], $_POST['dia_i']);
                $filtros->establecerFechaFinal($_POST['anio_f'], $_POST['mes_f'], $_POST['dia_f']);
                $anio = $_POST['anio_i'];
                $mes = $_POST['mes_i'];

                // Genera el arreglo con los CFDI de la fecha establecida
                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    $items = array();
                    $rutaXml = $rutaDescarga . "$rfc/$anio/Descargas/$mes.$meses[$mes]/Emitidos/XML";
                    $rutaPdf = $rutaDescarga . "$rfc/$anio/Descargas/$mes.$meses[$mes]/Emitidos/PDF";
                    foreach ($xmlInfoArr as $index => $xmlInfo) {
                        $arr[] = (array)$xmlInfo;
                        $uuid = $xmlInfo->folioFiscal;
                        $xml = $this->dirIteratorXml($rutaXml, $uuid);
                        $pdf = $this->dirIteratorPdf($rutaPdf, $uuid);
                        $acuse = $this->dirIteratorPdfAcuse($rutaPdf, $uuid);
                        $arr2 = array(
                            'descargadoXml' => $xml,
                            'descargadoPdf' => $pdf,
                            'descargadoAcuse' => $acuse,
                        );
                        $items[] = array_merge($arr[$index], $arr2);
                    }
                    // Se envia el arreglo $items y la sesión para que se genere la tabla con js
                    echo json_response(array(
                        'items' => $items,
                        'sesion' => $descargaCfdi->obtenerSesion()
                    ));
                } else {
                    echo json_response(array(
                        'mensaje' => 'No se han encontrado CFDIs',
                        'sesion' => $descargaCfdi->obtenerSesion()
                    ));
                }
            } elseif ($accion == 'descargar-recibidos') {

                $tipoF = "recibidos";

                $anio = $_POST['anio'];
                $mes = $_POST['mes'];
                $dia = $_POST['dia'];
                $rutaEmpresa = "$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/DescargasManuales/";
                $rutaDescarga = $rutaDescarga . $rutaEmpresa;
                $descarga = new DescargaAsincrona($maxDescargasSimultaneas);

                $filtros = new BusquedaRecibidos();
                $filtros->establecerFecha($anio, $mes, $dia);
                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    foreach ($xmlInfoArr as $xmlInfo) {
                        $udx = $xmlInfo->urlDescargaXml;
                        $uda = $xmlInfo->urlDescargaAcuse;
                        $udr = $xmlInfo->urlDescargaRI;
                        $ff = $xmlInfo->folioFiscal;
                        $er = $xmlInfo->emisorRfc;
                        $en = $xmlInfo->emisorNombre;
                        $rr = $xmlInfo->receptorRfc;
                        $rn = $xmlInfo->receptorNombre;
                        $fe =  $xmlInfo->fechaEmision;
                        $fcer = $xmlInfo->fechaCertificacion;
                        $pc = $xmlInfo->pacCertifico;
                        $total = $xmlInfo->total;
                        $total = substr($total, 1);
                        $total = $this->floatvalue($total);
                        $efecto = $xmlInfo->efecto;
                        $estado = $xmlInfo->estado;
                        $ec = $xmlInfo->estadoCancelacion;
                        $epc = $xmlInfo->estadoProcesoCancelacion;
                        $fcan = $xmlInfo->fechaCancelacion;
                        $ua = $xmlInfo->urlAcuseXml;

                        // Almacena la metadata si el archivo fue seleccionado
                        if (!empty($_POST['xml'])) {
                            foreach ($_POST['xml'] as $folioFiscal => $url) {
                                if ($folioFiscal == $ff) {
                                    $meta = MetadataR::where(['folioFiscal' => $ff]);
                                    $meta->update(
                                        [
                                            'urlDescargaXml'            => $udx,
                                            'urlDescargaAcuse'          => $uda,
                                            'urlDescargaRI'             => $udr,
                                            'folioFiscal'               => $ff,
                                            'emisorRfc'                 => $er,
                                            'emisorNombre'              => $en,
                                            'receptorRfc'               => $rr,
                                            'receptorNombre'            => $rn,
                                            'fechaEmision'              => $fe,
                                            'fechaCertificacion'        => $fcer,
                                            'pacCertificado'            => $pc,
                                            'total'                     => $total,
                                            'efecto'                    => $efecto,
                                            'estado'                    => $estado,
                                            'estadoCancelacion'         => $ec,
                                            'estadoProcesoCancelacion'  => $epc,
                                            'fechaCancelacion'          => $fcan,
                                            'urlAcuseXml'               => $ua,
                                        ],
                                        ['upsert' => true]
                                    );
                                }
                            }
                        }

                        // Almacena la metadata si el archivo fue seleccionado
                        if (!empty($_POST['ri'])) {
                            foreach ($_POST['ri'] as $folioFiscal => $url) {
                                if ($folioFiscal == $ff) {
                                    $meta = MetadataR::where(['folioFiscal' => $ff]);
                                    $meta->update(
                                        [
                                            'urlDescargaXml'            => $udx,
                                            'urlDescargaAcuse'          => $uda,
                                            'urlDescargaRI'             => $udr,
                                            'folioFiscal'               => $ff,
                                            'emisorRfc'                 => $er,
                                            'emisorNombre'              => $en,
                                            'receptorRfc'               => $rr,
                                            'receptorNombre'            => $rn,
                                            'fechaEmision'              => $fe,
                                            'fechaCertificacion'        => $fcer,
                                            'pacCertificado'            => $pc,
                                            'total'                     => $total,
                                            'efecto'                    => $efecto,
                                            'estado'                    => $estado,
                                            'estadoCancelacion'         => $ec,
                                            'estadoProcesoCancelacion'  => $epc,
                                            'fechaCancelacion'          => $fcan,
                                            'urlAcuseXml'               => $ua,
                                        ],
                                        ['upsert' => true]
                                    );
                                }
                            }
                        }

                         // Almacena la metadata si el archivo fue seleccionado
                         if (!empty($_POST['acuse'])) {
                            foreach ($_POST['acuse'] as $folioFiscal => $url) {
                                if ($folioFiscal == $ff) {
                                    $meta = MetadataR::where(['folioFiscal' => $ff]);
                                    $meta->update(
                                        [
                                            'urlDescargaXml'            => $udx,
                                            'urlDescargaAcuse'          => $uda,
                                            'urlDescargaRI'             => $udr,
                                            'folioFiscal'               => $ff,
                                            'emisorRfc'                 => $er,
                                            'emisorNombre'              => $en,
                                            'receptorRfc'               => $rr,
                                            'receptorNombre'            => $rn,
                                            'fechaEmision'              => $fe,
                                            'fechaCertificacion'        => $fcer,
                                            'pacCertificado'            => $pc,
                                            'total'                     => $total,
                                            'efecto'                    => $efecto,
                                            'estado'                    => $estado,
                                            'estadoCancelacion'         => $ec,
                                            'estadoProcesoCancelacion'  => $epc,
                                            'fechaCancelacion'          => $fcan,
                                            'urlAcuseXml'               => $ua,
                                        ],
                                        ['upsert' => true]
                                    );
                                }
                            }
                        }
                    }
                }

                // Descarga el archivo si fue seleccionado
                if (!empty($_POST['xml'])) {
                    foreach ($_POST['xml'] as $folioFiscal => $url) {
                        // xml
                        $descarga->agregarXml($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }

                // Descarga el archivo si fue seleccionado
                if (!empty($_POST['ri'])) {
                    foreach ($_POST['ri'] as $folioFiscal => $url) {
                        // representacion impresa
                        $descarga->agregarRepImpr($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }

                // Descarga el archivo si fue seleccionado
                if (!empty($_POST['acuse'])) {
                    foreach ($_POST['acuse'] as $folioFiscal => $url) {
                        // acuse de resultado de cancelacion
                        $descarga->agregarAcuse($url, $rutaDescarga, $folioFiscal, $folioFiscal . '-acuse');
                    }
                }

                $descarga->procesar();

                $str = 'Descargados: ' . $descarga->totalDescargados() . '.'
                    . ' Errores: ' . $descarga->totalErrores() . '.'
                    . ' Duración: ' . $descarga->segundosTranscurridos() . ' segundos.';
                echo json_response(array(
                    'mensaje' => $str,
                    'sesion' => $descargaCfdi->obtenerSesion()
                ));

                $this->filtroArchivos($rutaDescarga, $tipoF);
                $fecha = "$anio-$mes-$dia";
                $totalDR = $descarga->totalDescargados();
                $totalER = $descarga->totalErrores();
                $this->updateRecibidos($rfc, $fecha, $totalDR, $totalER);
            } elseif ($accion == 'descargar-emitidos') {

                // variable
                $tipoF = "emitidos";
                $anio = $_POST['anio_i'];
                $mes = $_POST['mes_i'];
                $dia = $_POST['dia_i'];
                $aniof = $_POST['anio_f'];
                $mesf = $_POST['mes_f'];
                $diaf = $_POST['dia_f'];
                $rutaEmpresa = "$rfc/$anio/Descargas/$mes.$meses[$mes]/Emitidos/DescargasManuales/";
                $rutaDescarga = $rutaDescarga . $rutaEmpresa;
                $descarga = new DescargaAsincrona($maxDescargasSimultaneas);

                $filtros = new BusquedaEmitidos();
                $filtros->establecerFechaInicial($anio, $mes, $dia);
                $filtros->establecerFechaFinal($aniof, $mesf, $diaf);
                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    foreach ($xmlInfoArr as $xmlInfo) {
                        $udx = $xmlInfo->urlDescargaXml;
                        $uda = $xmlInfo->urlDescargaAcuse;
                        $udr = $xmlInfo->urlDescargaRI;
                        $ff = $xmlInfo->folioFiscal;
                        $er = $xmlInfo->emisorRfc;
                        $en = $xmlInfo->emisorNombre;
                        $rr = $xmlInfo->receptorRfc;
                        $rn = $xmlInfo->receptorNombre;
                        $fe =  $xmlInfo->fechaEmision;
                        $fcer = $xmlInfo->fechaCertificacion;
                        $pc = $xmlInfo->pacCertifico;
                        $total = $xmlInfo->total;
                        $total = substr($total, 1);
                        $total = $this->floatvalue($total);
                        $efecto = $xmlInfo->efecto;
                        $estado = $xmlInfo->estado;
                        $ec = $xmlInfo->estadoCancelacion;
                        $epc = $xmlInfo->estadoProcesoCancelacion;
                        $fcan = $xmlInfo->fechaCancelacion;
                        $ua = $xmlInfo->urlAcuseXml;

                        // Almacena la metadata si el archivo fue seleccionado
                        if (!empty($_POST['xml'])) {
                            foreach ($_POST['xml'] as $folioFiscal => $url) {
                                if ($folioFiscal == $ff) {
                                    $meta = MetadataE::where(['folioFiscal' => $ff]);
                                    $meta->update(
                                        [
                                            'urlDescargaXml'            => $udx,
                                            'urlDescargaAcuse'          => $uda,
                                            'urlDescargaRI'             => $udr,
                                            'folioFiscal'               => $ff,
                                            'emisorRfc'                 => $er,
                                            'emisorNombre'              => $en,
                                            'receptorRfc'               => $rr,
                                            'receptorNombre'            => $rn,
                                            'fechaEmision'              => $fe,
                                            'fechaCertificacion'        => $fcer,
                                            'pacCertificado'            => $pc,
                                            'total'                     => $total,
                                            'efecto'                    => $efecto,
                                            'estado'                    => $estado,
                                            'estadoCancelacion'         => $ec,
                                            'estadoProcesoCancelacion'  => $epc,
                                            'fechaCancelacion'          => $fcan,
                                            'urlAcuseXml'               => $ua,
                                        ],
                                        ['upsert' => true]
                                    );
                                }
                            }
                        }

                        // Almacena la metadata si el archivo fue seleccionado
                        if (!empty($_POST['ri'])) {
                            foreach ($_POST['ri'] as $folioFiscal => $url) {
                                if ($folioFiscal == $ff) {
                                    $meta = MetadataE::where(['folioFiscal' => $ff]);
                                    $meta->update(
                                        [
                                            'urlDescargaXml'            => $udx,
                                            'urlDescargaAcuse'          => $uda,
                                            'urlDescargaRI'             => $udr,
                                            'folioFiscal'               => $ff,
                                            'emisorRfc'                 => $er,
                                            'emisorNombre'              => $en,
                                            'receptorRfc'               => $rr,
                                            'receptorNombre'            => $rn,
                                            'fechaEmision'              => $fe,
                                            'fechaCertificacion'        => $fcer,
                                            'pacCertificado'            => $pc,
                                            'total'                     => $total,
                                            'efecto'                    => $efecto,
                                            'estado'                    => $estado,
                                            'estadoCancelacion'         => $ec,
                                            'estadoProcesoCancelacion'  => $epc,
                                            'fechaCancelacion'          => $fcan,
                                            'urlAcuseXml'               => $ua,
                                        ],
                                        ['upsert' => true]
                                    );
                                }
                            }
                        }

                        // Almacena la metadata si el archivo fue seleccionado
                        if (!empty($_POST['acuse'])) {
                            foreach ($_POST['acuse'] as $folioFiscal => $url) {
                                if ($folioFiscal == $ff) {
                                    $meta = MetadataE::where(['folioFiscal' => $ff]);
                                    $meta->update(
                                        [
                                            'urlDescargaXml'            => $udx,
                                            'urlDescargaAcuse'          => $uda,
                                            'urlDescargaRI'             => $udr,
                                            'folioFiscal'               => $ff,
                                            'emisorRfc'                 => $er,
                                            'emisorNombre'              => $en,
                                            'receptorRfc'               => $rr,
                                            'receptorNombre'            => $rn,
                                            'fechaEmision'              => $fe,
                                            'fechaCertificacion'        => $fcer,
                                            'pacCertificado'            => $pc,
                                            'total'                     => $total,
                                            'efecto'                    => $efecto,
                                            'estado'                    => $estado,
                                            'estadoCancelacion'         => $ec,
                                            'estadoProcesoCancelacion'  => $epc,
                                            'fechaCancelacion'          => $fcan,
                                            'urlAcuseXml'               => $ua,
                                        ],
                                        ['upsert' => true]
                                    );
                                }
                            }
                        }
                    }
                }

                // Descarga el archivo si fue seleccionado
                if (!empty($_POST['xml'])) {
                    foreach ($_POST['xml'] as $folioFiscal => $url) {
                        // xml
                        $descarga->agregarXml($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }

                // Descarga el archivo si fue seleccionado
                if (!empty($_POST['ri'])) {
                    foreach ($_POST['ri'] as $folioFiscal => $url) {
                        // representacion impresa
                        $descarga->agregarRepImpr($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }

                // Descarga el archivo si fue seleccionado
                if (!empty($_POST['acuse'])) {
                    foreach ($_POST['acuse'] as $folioFiscal => $url) {
                        // acuse de resultado de cancelacion
                        $descarga->agregarAcuse($url, $rutaDescarga, $folioFiscal, $folioFiscal . '-acuse');
                    }
                }

                $descarga->procesar();

                $str = 'Descargados: ' . $descarga->totalDescargados() . '.'
                    . ' Errores: ' . $descarga->totalErrores() . '.'
                    . ' Duración: ' . $descarga->segundosTranscurridos() . ' segundos.';
                echo json_response(array(
                    'mensaje' => $str,
                    'sesion' => $descargaCfdi->obtenerSesion()
                ));

                $this->filtroArchivos($rutaDescarga, $tipoF);
                $fecha = "$anio-$mes-$dia";
                $fechaF = "$aniof-$mesf-$diaf";
                $totalDE = $descarga->totalDescargados();
                $totalEE = $descarga->totalErrores();
                $this->updateEmitidos($rfc, $fecha, $fechaF, $totalDE, $totalEE);
            }
        }
    }

    // Verifica los archivos descargados y los renombra en la carpeta correspondiente si es pdf o xml
    // Si pesa menos de 2000 bytes no es renombrado para evitar archivos corruptos
    // Si es xml lo inserta en MongoDB por tipo recibido o emitido
    public function filtroArchivos($rutaDescarga, $tipoF)
    {
        $dir = new DirectoryIterator($rutaDescarga);
        foreach ($dir as $fileinfo) {
            $fileName = $fileinfo->getFilename();
            $filePathname = $fileinfo->getPathname();
            $fileSize = filesize($filePathname);
            $fileExt = $fileinfo->getExtension();
            $fileBaseName = $fileinfo->getBasename(".$fileExt");
            $rutaGuardar = dirname(dirname($filePathname)) . "/";

            if (!$fileinfo->isDot()) {
                if ($fileSize > 2000) {
                    if ($fileExt == 'pdf') {
                        rename($filePathname, $rutaGuardar . 'PDF/' . $fileName);
                    } else {
                        $var = $rutaGuardar . "XML/$fileName";
                        rename($filePathname, $var);
                        // prueba de cfditojson
                        $contents = file_get_contents($var);
                        $cleaner = Cleaner::staticClean($contents);
                        $json = JsonConverter::convertToJson($cleaner);
                        $array = json_decode($json, true);

                        if ($tipoF == 'recibidos') {

                            XmlR::where(['UUID' => $fileBaseName])
                                ->update(
                                    $array,
                                    ['upsert' => true]
                                );
                        } else {
                            XmlE::where(['UUID' => $fileBaseName])
                                ->update(
                                    $array,
                                    ['upsert' => true]
                                );
                        }
                    }
                }
            }
        }
    }

    // Recibe la ruta y uuid del archivo xml para verificar si existe almacenado en la carpeta correspondiente
    public function dirIteratorXml($ruta, $uuid)
    {
        $dir = new DirectoryIterator($ruta);
        foreach ($dir as $fileinfo) {
            $fileBaseName = $fileinfo->getBasename('.xml');
            if (!$fileinfo->isDot()) {
                if ($uuid == $fileBaseName) {
                    return true;
                }
            }
        }
        return false;
    }

    // Recibe la ruta y uuid del archivo pdf para verificar si existe almacenado en la carpeta correspondiente
    public function dirIteratorPdf($ruta, $uuid)
    {
        $dir = new DirectoryIterator($ruta);
        foreach ($dir as $fileinfo) {
            $fileBaseName = $fileinfo->getBasename('.pdf');
            if (!$fileinfo->isDot()) {
                if ($uuid == $fileBaseName) {
                    return true;
                }
            }
        }
        return false;
    }

    // Recibe la ruta y uuid del archivo pdf (acuse) para verificar si existe almacenado en la carpeta correspondiente
    public function dirIteratorPdfAcuse($ruta, $uuid)
    {
        $dir = new DirectoryIterator($ruta);
        foreach ($dir as $fileinfo) {
            $fileBaseName = $fileinfo->getBasename('.pdf');
            if (!$fileinfo->isDot()) {
                if ($uuid . '-acuse' == $fileBaseName) {
                    return true;
                }
            }
        }
        return false;
    }

    // Crea o modifica la colección calendario_r con la fecha y el total de descargas y errores
    public function updateRecibidos($rfc, $fecha, $totalD, $totalE)
    {
        $cal = CalendarioR::where(['rfc' => $rfc, 'fechaDescarga' => $fecha, 'fechaDescargaF' => $fecha]);
        $cget = $cal->get()->first();
        if (!$cget == null) {
            $descargas = $cget->descargasRecibidos;
            $errores = $cget->erroresRecibidos;
            $nDescargas = $descargas + $totalD;
            $nErrores = $errores - $totalD;
            $nTotal = $nDescargas + $nErrores;
        } else {
            $nDescargas = $totalD;
            $nErrores = $totalE;
            $nTotal = $nDescargas + $nErrores;
        }

        $cal->update(
            [
                'rfc' => $rfc,
                'fechaDescarga' => $fecha,
                'fechaDescargaF' => $fecha,
                'descargasRecibidos' => $nDescargas,
                'erroresRecibidos' => $nErrores,
                'totalRecibidos' => $nTotal,
            ],
            ['upsert' => true]
        );
    }

    // Crea o modifica la colección calendario_e con la fecha y el total de descargas y errores
    public function updateEmitidos($rfc, $fecha, $fechaF, $totalD, $totalE)
    {
        $cal = CalendarioE::where(['rfc' => $rfc, 'fechaDescarga' => $fecha, 'fechaDescargaF' => $fechaF]);
        $cget = $cal->get()->first();
        if (!$cget == null) {
            $descargas = $cget->descargasEmitidos;
            $errores = $cget->erroresEmitidos;
            $nDescargas = $descargas + $totalD;
            $nErrores = $errores - $totalD;
            $nTotal = $nDescargas + $nErrores;
        } else {
            $nDescargas = $totalD;
            $nErrores = $totalE;
            $nTotal = $nDescargas + $nErrores;
        }

        $cal->update(
            [
                'rfc' => $rfc,
                'fechaDescarga' => $fecha,
                'fechaDescargaF' => $fechaF,
                'descargasEmitidos' => $nDescargas,
                'erroresEmitidos' => $nErrores,
                'totalEmitidos' => $nTotal,
            ],
            ['upsert' => true]
        );
    }

    // Elimina las comas de los valores totales
    public function floatvalue($val)
    {
        $val = str_replace(",", ".", $val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return $val;
        // return floatval($val);
    }
}
