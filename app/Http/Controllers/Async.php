<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Classes\UtilCertificado;
use Illuminate\Support\Facades\Storage;
use App\Http\Classes\DescargaMasivaCfdi;
use App\Http\Classes\BusquedaRecibidos;
use App\Http\Classes\BusquedaEmitidos;
use App\Http\Classes\DescargaAsincrona;

class Async extends Controller
{
    public function index()
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

                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    $items = array();
                    foreach ($xmlInfoArr as $xmlInfo) {
                        $items[] = (array)$xmlInfo;
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
            } elseif ($accion == 'buscar-emitidos') {
                $filtros = new BusquedaEmitidos();
                $filtros->establecerFechaInicial($_POST['anio_i'], $_POST['mes_i'], $_POST['dia_i']);
                $filtros->establecerFechaFinal($_POST['anio_f'], $_POST['mes_f'], $_POST['dia_f']);

                $xmlInfoArr = $descargaCfdi->buscar($filtros);
                if ($xmlInfoArr) {
                    $items = array();
                    foreach ($xmlInfoArr as $xmlInfo) {
                        $items[] = (array)$xmlInfo;
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

                $rutaDescarga = $rutaDescarga . 'Recibidos/';
                $rutaDescargaXml = $rutaDescarga.'XML/';
                $rutaDescargaPdf = $rutaDescarga.'PDF/';
                $descarga = new DescargaAsincrona($maxDescargasSimultaneas);

                if (!empty($_POST['xml'])) {
                    foreach ($_POST['xml'] as $folioFiscal => $url) {
                        // xml
                        $descarga->agregarXml($url, $rutaDescargaXml, $folioFiscal, $folioFiscal);
                    }
                }
                if (!empty($_POST['ri'])) {
                    foreach ($_POST['ri'] as $folioFiscal => $url) {
                        // representacion impresa
                        $descarga->agregarRepImpr($url, $rutaDescargaPdf, $folioFiscal, $folioFiscal);
                    }
                }
                if (!empty($_POST['acuse'])) {
                    foreach ($_POST['acuse'] as $folioFiscal => $url) {
                        // acuse de resultado de cancelacion
                        $descarga->agregarAcuse($url, $rutaDescargaPdf, $folioFiscal, $folioFiscal . '-acuse');
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
            } elseif ($accion == 'descargar-emitidos') {

                $rutaDescarga = $rutaDescarga . 'Emitidos/';
                $rutaDescargaXml = $rutaDescarga.'XML/';
                $rutaDescargaPdf = $rutaDescarga.'PDF/';
                $descarga = new DescargaAsincrona($maxDescargasSimultaneas);

                if (!empty($_POST['xml'])) {
                    foreach ($_POST['xml'] as $folioFiscal => $url) {
                        // xml
                        $descarga->agregarXml($url, $rutaDescargaXml, $folioFiscal, $folioFiscal);
                    }
                }
                if (!empty($_POST['ri'])) {
                    foreach ($_POST['ri'] as $folioFiscal => $url) {
                        // representacion impresa
                        $descarga->agregarRepImpr($url, $rutaDescargaPdf, $folioFiscal, $folioFiscal);
                    }
                }
                if (!empty($_POST['acuse'])) {
                    foreach ($_POST['acuse'] as $folioFiscal => $url) {
                        // acuse de resultado de cancelacion
                        $descarga->agregarAcuse($url, $rutaDescargaPdf, $folioFiscal, $folioFiscal . '-acuse');
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
            }
        }
    }
}
