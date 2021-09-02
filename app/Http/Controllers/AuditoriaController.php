<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\MetadataE;
use App\Models\MetadataR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTimePeriod;
use PhpCfdi\SatWsDescargaMasiva\WebClient\GuzzleWebClient;
use PhpCfdi\SatWsDescargaMasiva\Services\Query\QueryParameters;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\MetadataPackageReader;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\Fiel;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\Exceptions\OpenZipFileException;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\FielRequestBuilder;

class AuditoriaController extends Controller
{
    public function index()
    {
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $hoy = $dt->format('Y-m-d');
        return view('auditoria')
            ->with('hoy', $hoy);
    }

    public function store(Request $request)
    {
        // Verifica si es reporte completo
        if ($request->has('rc')) {
            $rc = true;
        } else {
            $rc = false;
        }
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $anio = $dt->format('Y');
        $rfc = Auth::user()->RFC;
        $pass = Auth::user()->pass;
        $dircer = "storage/" . Auth::user()->dircer;
        $dirkey = "storage/" . Auth::user()->dirkey;
        $tipoer = $request->tipoer;
        $fecha1er = $request->fecha1er;
        $fecha2er = $request->fecha2er;
        $periodoI = "$fecha1er 00:00:00";
        $periodoF = "$fecha2er 23:59:59";
        $n = 0;
        $m = 0;

        // CREACIÓN DEL SERVICIO

        // Creación de la FIEL, puede leer archivos DER (como los envía el SAT) o PEM (convertidos con openssl)
        $fiel = Fiel::create(
            file_get_contents($dircer),
            file_get_contents($dirkey),
            $pass,
        );

        // verificar que la FIEL sea válida (no sea CSD y sea vigente acorde a la fecha del sistema)
        if (!$fiel->isValid()) {
            return;
        }

        // creación del web client basado en Guzzle que implementa WebClientInterface
        // para usarlo necesitas instalar guzzlehttp/guzzle pues no es una dependencia directa
        $webClient = new GuzzleWebClient();

        // creación del objeto encargado de crear las solicitudes firmadas usando una FIEL
        $requestBuilder = new FielRequestBuilder($fiel);

        // Creación del servicio
        $service = new Service($requestBuilder, $webClient);

        // REALIZAR LA CONSULTA

        //división de emitidas y recibidas en la descarga

        if ($tipoer == 'Emitidas') {
            $request = QueryParameters::create(
                DateTimePeriod::createFromValues($periodoI, $periodoF),
                DownloadType::issued(),
                RequestType::metadata(),
            );
            $colAu = MetadataE::where('emisorRfc', $rfc)
                ->whereBetween('fechaEmision', array($fecha1er . "T00:00:00", $fecha2er . "T23:59:59"))
                ->orderBy('fechaEmision', 'asc')
                ->get();
        } else {
            $request = QueryParameters::create(
                DateTimePeriod::createFromValues($periodoI, $periodoF),
                DownloadType::received(),
                RequestType::metadata(),
            );
            $colAu = MetadataR::where('receptorRfc', $rfc)
                ->whereBetween('fechaEmision', array($fecha1er . "T00:00:00", $fecha2er . "T23:59:59"))
                ->orderBy('fechaEmision', 'asc')
                ->get();
        }

        // Verifica si la consulta está vacía
        // if ($colAu->toArray() == null) {
        //     $colAuArr[] = "";
        // } else {
        //     // Crea el arreglo con los uuid de la consulta
        //     foreach ($colAu->toArray() as $c) {
        //         $colAuArr[] = $c['folioFiscal'];
        //     }
        // }

        // presentar la consulta
        $query = $service->query($request);

        // verificar que el proceso de consulta fue correcto
        if (!$query->getStatus()->isAccepted()) {
            echo "Fallo al presentar la consulta: {$query->getStatus()->getMessage()}";
            return;
        }

        // VERIFICAR UNA CONSULTA

        $requestId = $query->getRequestId();
        $terminado = true;

        while ($terminado) {
            // consultar el servicio de verificación
            $verify = $service->verify($requestId);

            // revisar que el proceso de verificación fue correcto
            if (!$verify->getStatus()->isAccepted()) {
                echo "Fallo al verificar la consulta {$requestId}: {$verify->getStatus()->getMessage()}";
                return;
            }

            // revisar que la consulta no haya sido rechazada
            if (!$verify->getCodeRequest()->isAccepted()) {
                echo "La solicitud {$requestId} fue rechazada: {$verify->getCodeRequest()->getMessage()}", PHP_EOL;
                return;
            }

            // revisar el progreso de la generación de los paquetes
            $statusRequest = $verify->getStatusRequest();
            if ($statusRequest->isExpired() || $statusRequest->isFailure() || $statusRequest->isRejected()) {
                echo "La solicitud {$requestId} no se puede completar", PHP_EOL;
                return;
            }

            if ($statusRequest->isInProgress() || $statusRequest->isAccepted()) {
                // echo "La solicitud {$requestId} se está procesando</br>", PHP_EOL;
                // return;
            }

            if ($statusRequest->isFinished()) {
                // echo "La solicitud {$requestId} está lista</br>", PHP_EOL;
                $terminado = false;
                // $alerta = "La solicitud {$requestId} está lista";
                // $this->alerta($alerta);
            }
        }

        // echo "Se encontraron {$verify->countPackages()} paquetes", PHP_EOL;
        // foreach ($verify->getPackagesIds() as $packageId) {
        //     echo " > {$packageId}</br>", PHP_EOL;
        // }

        $packageId = $verify->getPackagesIds()['0'];

        // DESCARGAR LOS PAQUETES DE LA CONSULTA

        // consultar el servicio de verificación
        // foreach($packagesIds as $packageId) {
        $download = $service->download($packageId);
        if (!$download->getStatus()->isAccepted()) {
            echo "El paquete {$packageId} no se ha podido descargar: {$download->getStatus()->getMessage()}", PHP_EOL;
            return;
            // continue;
        }
        $zipfile = "$packageId.zip";
        $nombrezip = "$zipfile";
        $ruta = "storage/contarappv1_descargas/$rfc/$anio/Auditoria/$nombrezip";
        file_put_contents($ruta, $download->getPackageContent());
        // echo "El paquete {$nombrezip} se ha almacenado</br>", PHP_EOL;

        // abrir el archivo de Metadata
        try {
            $metadataReader = MetadataPackageReader::createFromFile($ruta);
            // leer todos los registros de metadata dentro de todos los archivos del archivo ZIP
            // foreach ($metadataReader->metadata() as $uuid => $metadata) {
                // if ($metadata->estatus == '1') {
                //     $metadata->estatus = "Vigente";
                // } else {
                //     $metadata->estatus = "Cancelado";
                // }

                //Crea el arreglo de las uuid obtenidas de la metadata
                // $arr[] = $uuid;
            // }

            // $uuidDiff = array_diff($arr, $colAuArr);
            // $uuidInter = array_intersect($arr, $colAuArr);
        } catch (OpenZipFileException $exception) {
            echo $exception->getMessage() . "</br>", PHP_EOL;
            return;
        }

        return view('auditoria1')
            ->with('rc', $rc)
            ->with('n', $n)
            ->with('m', $m)
            ->with('tipoer', $tipoer)
            ->with('fecha1er', $fecha1er)
            ->with('fecha2er', $fecha2er)
            ->with('colAu', $colAu)
            ->with('metadata', $metadataReader->metadata())
            ->with('metadata2', $metadataReader->metadata());
    }
}
