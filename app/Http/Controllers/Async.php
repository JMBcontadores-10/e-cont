<?php

namespace App\Http\Controllers;

use DirectoryIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Classes\UtilCertificado;
use App\Http\Classes\BusquedaEmitidos;
use App\Http\Classes\BusquedaRecibidos;
use App\Http\Classes\DescargaAsincrona;
use Illuminate\Support\Facades\Storage;
use App\Http\Classes\DescargaMasivaCfdi;

class Async extends Controller
{
    public function index(Request $r)
    {

        // Obtener configuracion
        $config = require dirname(dirname(__FILE__)) . '/Classes' . '/config.php';

        // Preparar variables
        $rutaDescarga = $config['rutaDescarga'];
        $maxDescargasSimultaneas = $config['maxDescargasSimultaneas'];

        $rutaApp = "C:/laragon/www/contarappv1/public";
        $dc = Storage::url(Auth::user()->dircer);
        $dircer = $rutaApp . $dc;
        $dk = Storage::url(Auth::user()->dirkey);
        $dirkey = $rutaApp . $dk;
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
                $filtros = new BusquedaRecibidos();
                $filtros->establecerFecha($_POST['anio'], $_POST['mes'], $_POST['dia']);
                $anio = $_POST['anio'];
                $mes = $_POST['mes'];

                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    $items = array();
                    $rutaPdf = $rutaDescarga."$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/PDF";
                    $rutaXml = $rutaDescarga."$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/XML";
                    foreach ($xmlInfoArr as $index => $xmlInfo) {
                        $arr[] = (array)$xmlInfo;
                        $uuid = $xmlInfo->folioFiscal;
                        $pdf = $this->dirIteratorPdf($rutaPdf, $uuid);
                        $xml = $this->dirIteratorXml($rutaXml, $uuid);
                        $arr2 = array(
                            'descargadoPdf' => $pdf,
                            'descargadoXml' => $xml
                        );
                        $items[] = array_merge($arr[$index], $arr2);
                    }
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
                $filtros = new BusquedaEmitidos();
                $filtros->establecerFechaInicial($_POST['anio_i'], $_POST['mes_i'], $_POST['dia_i']);
                $filtros->establecerFechaFinal($_POST['anio_f'], $_POST['mes_f'], $_POST['dia_f']);
                $anio = $_POST['anio_i'];
                $mes = $_POST['mes_i'];

                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    $items = array();
                    $rutaXml = $rutaDescarga."$rfc/$anio/Descargas/$mes.$meses[$mes]/Emitidos/XML";
                    $rutaPdf = $rutaDescarga."$rfc/$anio/Descargas/$mes.$meses[$mes]/Emitidos/PDF";
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

                $anio = $_POST['anio'];
                $mes = $_POST['mes'];
                $rutaEmpresa = "$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/DescargasManuales/";
                $rutaDescarga = $rutaDescarga . $rutaEmpresa;
                $descarga = new DescargaAsincrona($maxDescargasSimultaneas);

                if (!empty($_POST['xml'])) {
                    foreach ($_POST['xml'] as $folioFiscal => $url) {
                        // xml
                        $descarga->agregarXml($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }
                if (!empty($_POST['ri'])) {
                    foreach ($_POST['ri'] as $folioFiscal => $url) {
                        // representacion impresa
                        $descarga->agregarRepImpr($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }
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

                $this->filtroArchivos($rutaDescarga);

            } elseif ($accion == 'descargar-emitidos') {

                $anio = $_POST['anio_i'];
                $mes = $_POST['mes_i'];
                $rutaEmpresa = "$rfc/$anio/Descargas/$mes.$meses[$mes]/Emitidos/DescargasManuales/";
                $rutaDescarga = $rutaDescarga . $rutaEmpresa;
                $descarga = new DescargaAsincrona($maxDescargasSimultaneas);

                if (!empty($_POST['xml'])) {
                    foreach ($_POST['xml'] as $folioFiscal => $url) {
                        // xml
                        $descarga->agregarXml($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }
                if (!empty($_POST['ri'])) {
                    foreach ($_POST['ri'] as $folioFiscal => $url) {
                        // representacion impresa
                        $descarga->agregarRepImpr($url, $rutaDescarga, $folioFiscal, $folioFiscal);
                    }
                }
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

                $this->filtroArchivos($rutaDescarga);

            }
        }
    }

    public function filtroArchivos($rutaDescarga)
    {
        $dir = new DirectoryIterator($rutaDescarga);
        foreach ($dir as $fileinfo) {
            $fileName = $fileinfo->getFilename();
            $filePathname = $fileinfo->getPathname();
            $fileSize = filesize($filePathname);
            $fileExt = $fileinfo->getExtension();
            $rutaGuardar = dirname(dirname($filePathname)) . "/";
            if (!$fileinfo->isDot()) {
                if ($fileSize > 0) {
                    if ($fileExt == 'pdf') {
                        rename($filePathname, $rutaGuardar . 'PDF/' . $fileName);
                    } else {
                        rename($filePathname, $rutaGuardar . 'XML/' . $fileName);
                    }
                }
            }
        }
    }

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

    public function dirIteratorPdfAcuse($ruta, $uuid)
    {
        $dir = new DirectoryIterator($ruta);
        foreach ($dir as $fileinfo) {
            $fileBaseName = $fileinfo->getBasename('.pdf');
            if (!$fileinfo->isDot()) {
                if ($uuid.'-acuse' == $fileBaseName) {
                    return true;
                }
            }
        }
        return false;
    }

}
