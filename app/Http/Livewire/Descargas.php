<?php

namespace App\Http\Livewire;

//Clases para acceder al SAT
use PhpCfdi\CfdiSatScraper\QueryByFilters;
use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\SatScraper;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionData;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\CfdiToJson\JsonConverter;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use PhpCfdi\CfdiSatScraper\SatHttpGateway;

use App\Models\MetadataE;
use App\Models\MetadataR;
use App\Models\Calendario;
use App\Models\User;
use App\Models\XmlE;
use App\Models\XmlR;
use DateTimeImmutable;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use PhpCfdi\CfdiCleaner\Cleaner;
use PhpCfdi\CfdiSatScraper\Contracts\ResourceFileNamerInterface;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(3600); //Tiempo limite dado 1 hora

//Clases para cambiar el nombre a lo archivos
//XML
class FileNameXML implements \PhpCfdi\CfdiSatScraper\Contracts\ResourceFileNamerInterface
{
    public function nameFor(string $uuid): string
    {
        return strtoupper($uuid) . '.xml';
    }
}

//PDF
class FileNamePDF implements \PhpCfdi\CfdiSatScraper\Contracts\ResourceFileNamerInterface
{
    public function nameFor(string $uuid): string
    {
        return strtoupper($uuid) . '.pdf';
    }
}

//Acuse
class FileNamePDFAcuse implements \PhpCfdi\CfdiSatScraper\Contracts\ResourceFileNamerInterface
{
    public function nameFor(string $uuid): string
    {
        return strtoupper($uuid) . '-acuse' . '.pdf';
    }
}

class Descargas extends Component
{
    //Variables globales
    public $rfcEmpresa;

    //Variables para la autenticacion y descargas
    public $dircer;
    public $dirkey;
    public $pwd;
    public $rfcemp;

    //Variables de mensaje para inicio de sesion
    public $mnsinic;
    public $statemns; //El valor sera el color que se mostrara en el mensaje

    //Varibles para la tabla de CFDI
    public $tipo;
    public $totallist;

    //Obtener el valor de los checkbox
    public $cfdiselectxml = []; //Variable que contendra los folios para la descarga de los XML
    public $cfdiselectpdf = []; //Variable que contendra los folios para la descarga de los PDF
    public $cfdiselectpdfacuse = []; //Variable que contendra los folios para la descarga de los PDF Acuse

    //Variables para el filtro de recibidos
    public $diareci;
    public $mesreci;
    public $anioreci;

    //Variables para el filtro de emitidos
    //Fechas de inicio
    public $diaemitinic;
    public $mesemitinic;
    public $anioemitinic;

    //Fechas de fin
    public $diaemitfin;
    public $mesemitfin;
    public $anioemitfin;

    //Variables para la seccion del calendario
    public $reciboemit = 0; //Variable bandera para saber si es un fecha de recibido/emitido o no
    public $recibido = 0; //Variable bandera para saber si existe o no datos recibidos
    public $mescal;
    public $aniocal;


    //Recibimos los emitidos de livewire
    protected $listeners = ['addallcfdi' => 'addallcfdi'];

    public function addallcfdi($datacfdi)
    {
        //Obtenemos la cadena con los UUIDs convertidos
        $dataxmlcheck = $datacfdi['xmlval'];
        $datapdfcheck = $datacfdi['pdfval'];
        $dataacusecheck = $datacfdi['acuseval'];

        //Descomponemos la cadena y lo creamos en un arreglo
        $dataxmlcheck = explode(",", $dataxmlcheck);
        $datapdfcheck = explode(",", $datapdfcheck);
        $dataacusecheck = explode(",", $dataacusecheck);

        //Limpiamos los arreglos
        $this->cfdiselectxml = [];
        $this->cfdiselectpdf = [];
        $this->cfdiselectpdfacuse = [];

        //Metemos lo arreglos creados
        $this->cfdiselectxml = array_filter($dataxmlcheck);
        $this->cfdiselectpdf = array_filter($datapdfcheck);
        $this->cfdiselectpdfacuse = array_filter($dataacusecheck);

        //Ejecutamos el metodo de descarga de documentos
        $this->Descvincucfdi();

        //Emitimos una accion para recibirlo en la vista
        $this->dispatchBrowserEvent('deschecked', []);
    }

    //Metodo para convertir los meses en el formato carpetado
    public function Meses($mes)
    {
        //Mes
        switch ($mes) {
            case '01':
                return '1.Enero';
                break;

            case '02':
                return '2.Febrero';
                break;

            case '03':
                return '3.Marzo';
                break;

            case '04':
                return '4.Abril';
                break;

            case '05':
                return '5.Mayo';
                break;

            case '06':
                return '6.Junio';
                break;

            case '07':
                return '7.Julio';
                break;

            case '08':
                return '8.Agosto';
                break;

            case '09':
                return '9.Septiembre';
                break;

            case '10':
                return '10.Octubre';
                break;

            case '11':
                return '11.Noviembre';
                break;

            case '12':
                return '12.Diciembre';
                break;
        }
    }

    //Metodo de inicio de sesion
    public function InicioSesion()
    {
        //Variables de cookies de sesion para no volver a realizar el inicio de sesion
        $cookieJarPath = sprintf('%s\build\cookies\%s.json', getcwd(), $this->rfcEmpresa);
        //Se almacena ña cookie en un gateway para mandarlo al cliente y este realizar las consultas
        $gateway = new SatHttpGateway(new Client(), new FileCookieJar($cookieJarPath, true));

        //Obtiene las variables para crear el crtificado
        $certificate = 'storage/' . $this->dircer;
        $privateKey = 'storage/' . $this->dirkey;
        $passPhrase = $this->pwd;

        //Creamos al credenciales de acceso
        $credential = Credential::create(
            /*En la libreria no utiliza 'file_get_contents', pero si vamos a acceder al certificado
                por medio de una direccion utiliza la funcion*/
            file_get_contents($certificate),
            file_get_contents($privateKey),
            $passPhrase
        );

        if (!$credential->isFiel()) {
            throw new Exception('The certificate and private key is not a FIEL');
        }
        if (!$credential->certificate()->validOn()) {
            throw new Exception('The certificate and private key is not valid at this moment');
        }

        //Creamos la session utilizando la FIEL
        $satScraper = new SatScraper(FielSessionManager::create($credential), $gateway);

        return $satScraper;
    }

    //Consultas del SAT (Emitidos o recibidos)
    public function ConsultSAT()
    {
        //Condicional para comprobar si hay una empresa (Si es contador)
        if ($this->rfcEmpresa) {
            /*Como se va a realizar una peticion a la pagina del SAT vamos a realizar un try catch para verificar que la conexion
        se realizo correctamente*/
            try {
                //Creamos la session utilizando la FIEL
                $satScraper = $this->InicioSesion();

                //Vamos a realizar una consulta
                if ($this->tipo == 'Emitidos') {
                    $query = new QueryByFilters(
                        new DateTimeImmutable($this->anioemitinic . '-' . $this->mesemitinic . '-' . $this->diaemitinic),
                        new DateTimeImmutable($this->anioemitfin . '-' . $this->mesemitfin . '-' . $this->diaemitfin)
                    );
                } else {
                    switch ($this->diareci) {
                        case "all":
                            //Obtenemos el valor del ultimo dia
                            $timestamp = strtotime($this->anioreci . "-" . $this->mesreci . '-01');
                            $day_count = date('t', $timestamp);

                            $query = new QueryByFilters(
                                new DateTimeImmutable($this->anioreci . '-' . $this->mesreci . '-01'),
                                new DateTimeImmutable($this->anioreci . '-' . $this->mesreci . '-' . $day_count)
                            );
                            $query->setDownloadType(DownloadType::recibidos());
                            break;
                        default:
                            $query = new QueryByFilters(
                                new DateTimeImmutable($this->anioreci . '-' . $this->mesreci . '-' . $this->diareci),
                                new DateTimeImmutable($this->anioreci . '-' . $this->mesreci . '-' . $this->diareci)
                            );
                            $query->setDownloadType(DownloadType::recibidos());
                            break;
                    }
                }

                //Emitimos una accion para recibirlo en la vista
                $this->dispatchBrowserEvent('deschecked', []);

                //Retornamos el valor de la consulta
                return $satScraper->listByPeriod($query);
            } catch (Exception $e) {
                //Retornamos un mensaje de error
                return "Parece que hubo un error: " . $e;
            }
        } else {
            return "Sin empresa, de favor seleccione una empresa";
        }
    }

    //Metodo para almacenar los metadatos
    public function SaveMetadatos($listcfdi, $tipo)
    {
        //En varaibles guardamos los valores necesarios para la inserción y en el mismo ciclo agrgamos los metadatos
        foreach ($listcfdi as $datapdfreci) {
            $urldescxml = $datapdfreci->urlXml;
            $urldescpdf = $datapdfreci->urlPdf;
            $urldesacuse = $datapdfreci->urlCancelVoucher;
            $folifiscal = strtoupper($datapdfreci->uuid); //Convertimos en mayusculas para respetar el formato
            $emisorrfc = $datapdfreci->rfcEmisor;
            $emisornom = $datapdfreci->nombreEmisor;
            $receptorrfc = $datapdfreci->rfcReceptor;
            $receptornom = $datapdfreci->nombreReceptor;
            $fechaemi = $datapdfreci->fechaEmision;
            $fechacerti = $datapdfreci->fechaCertificacion;
            $paccerti = $datapdfreci->pacCertifico;

            $total = $datapdfreci->total;
            $total = substr($total, 1);
            $total = str_replace(",", ".", $total);
            $total = preg_replace('/\.(?=.*\.)/', '', $total);

            $efecto = $datapdfreci->efectoComprobante;
            $estado = $datapdfreci->estadoComprobante;
            $edocancel = $datapdfreci->estatusCancelacion;
            $edoproccancel = $datapdfreci->estatusProcesoCancelacion;
            $fechacancel = $datapdfreci->fechaProcesoCancelacion;
            $urlacuse = null; //Este datos es null ya que no se encuentra en los metadata de cancelacion

            //Almacenamos el metadata
            switch ($tipo) {
                case "Emitidos":
                    $metadatarecipdf = MetadataE::where(['folioFiscal' => $folifiscal]);
                    break;
                case "Recibidos":
                    $metadatarecipdf = MetadataR::where(['folioFiscal' => $folifiscal]);
                    break;
            }

            $metadatarecipdf->update([
                'urlDescargaXml'            => $urldescxml,
                'urlDescargaAcuse'          => $urldesacuse,
                'urlDescargaRI'             => $urldescpdf,
                'folioFiscal'               => $folifiscal,
                'emisorRfc'                 => $emisorrfc,
                'emisorNombre'              => $emisornom,
                'receptorRfc'               => $receptorrfc,
                'receptorNombre'            => $receptornom,
                'fechaEmision'              => $fechaemi,
                'fechaCertificacion'        => $fechacerti,
                'pacCertificado'            => $paccerti,
                'total'                     => $total,
                'efecto'                    => $efecto,
                'estado'                    => $estado,
                'estadoCancelacion'         => $edocancel,
                'estadoProcesoCancelacion'  => $edoproccancel,
                'fechaCancelacion'          => $fechacancel,
                'urlAcuseXml'               => $urlacuse,
            ], ['upsert' => true]);
        }
    }

    //Metodo para almacenar los XML
    public function SaveXML($rutaxml, $uuid, $tipo)
    {
        //Obtenemos el contenido de la ruta
        $contentxmlreci = file_get_contents($rutaxml);

        //Try/catch para revisar si existe algun problema con el CFDI
        try {
            //Primeramente vamos a leer el archivo sin pasar por limpieza

            //Ahora el cfdi descargado lo convertimos en json
            $xmlcfdi = JsonConverter::convertToJson($contentxmlreci);

            //Decodificamos el json creado en un arreglo
            $arraycfdireci = json_decode($xmlcfdi, true);
        } catch (Exception $e) {
            //Si existe un problema con el archivo, se pasa por el mismo proceso pero ahora lo limpiamos

            //Limpiamos el XML descargado
            $cleanxmlreci = Cleaner::staticClean($contentxmlreci);

            //Ahora el cfdi descargado lo convertimos en json
            $xmlcfdi = JsonConverter::convertToJson($cleanxmlreci);

            //Decodificamos el json creado en un arreglo
            $arraycfdireci = json_decode($xmlcfdi, true);
        }

        //Agregamos los datos del arreglo a la coleccion de XML recibidos
        switch ($tipo) {
            case "Emitidos":
                XmlE::where(['UUID' => $uuid])
                    ->update(
                        $arraycfdireci,
                        ['upsert' => true]
                    );

                XmlE::where(['UUID' => $uuid])
                    ->update([
                        'UUID' => strtoupper($uuid)
                    ]);
                break;
            case "Recibidos":
                XmlR::where(['UUID' => $uuid])
                    ->update(
                        $arraycfdireci,
                        ['upsert' => true]
                    );

                XmlR::where(['UUID' => $uuid])
                    ->update([
                        'UUID' => strtoupper($uuid)
                    ]);
                break;
        }
    }

    //Metodo para descargar los archivos relacionados
    public function Descvincucfdi()
    {
        //Creamos la session utilizando la FIEL
        $satScraper = $this->InicioSesion();

        //Condicional para saber si pertenece a un emitido o a un recibido
        switch ($this->tipo) {
            case "Recibidos":
                //Para realizar las descargas tenemos que tener una lista de tipo metadata por lo que realizaremos la consulta
                //Recibidos

                //Consultas
                //XML
                $listxmlreci = $satScraper->listByUuids($this->cfdiselectxml, DownloadType::recibidos());
                //PDF
                $listpdfreci = $satScraper->listByUuids($this->cfdiselectpdf, DownloadType::recibidos());
                //PDF Acuse
                $listpdfacusereci = $satScraper->listByUuids($this->cfdiselectpdfacuse, DownloadType::recibidos());

                //Rutas
                //Aqui llamamos a la funcion de mese
                $mesruta = $this->Meses($this->mesreci);

                //XML
                $rutaxml = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/XML/";
                //PDF
                $rutapdf = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/PDF/";
                //Acuse
                $rutaacuse = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/ACUSE/";

                //Realizamos la descarga
                //XML
                $satScraper->resourceDownloader(ResourceType::xml(), $listxmlreci)
                    ->setResourceFileNamer(new FileNameXML())
                    ->saveTo($rutaxml, true, 0777);

                //PDF
                $satScraper->resourceDownloader(ResourceType::pdf(), $listpdfreci)
                    ->setResourceFileNamer(new FileNamePDF())
                    ->saveTo($rutapdf, true, 0777);

                //PDF Acuse
                $satScraper->resourceDownloader(ResourceType::cancelVoucher(), $listpdfacusereci)
                    ->setResourceFileNamer(new FileNamePDFAcuse())
                    ->saveTo($rutaacuse, true, 0777);


                //Llamamos la funcion para almacenar los metadatos en la base de datos
                //PDF
                $this->SaveMetadatos($listpdfreci, $this->tipo);

                //XML
                $this->SaveMetadatos($listxmlreci, $this->tipo);

                //Acuse
                $this->SaveMetadatos($listpdfacusereci, $this->tipo);


                //Vamos a comporbar si la carpeta tiene XML descargados
                foreach ($this->cfdiselectxml as $listxmlcfdi) {
                    $rutaxmlfile = $rutaxml . strtoupper($listxmlcfdi) . ".xml";
                    if (file_exists($rutaxmlfile)) {
                        $this->SaveXML($rutaxmlfile, strtoupper($listxmlcfdi), $this->tipo);
                    }
                }

                //Metodo para mostrar el resultado de las descargas
                $this->AddCalReci();

                //Limpiamos los arreglos
                $this->cfdiselectxml = [];
                $this->cfdiselectpdf = [];
                $this->cfdiselectpdfacuse = [];
                break;
            case "Emitidos":
                //Para realizar las descargas tenemos que tener una lista de tipo metadata por lo que realizaremos la consulta
                //Emitidos

                //Consultas
                //XML
                $listxmlemit = $satScraper->listByUuids($this->cfdiselectxml, DownloadType::emitidos());
                //PDF
                $listpdfemit = $satScraper->listByUuids($this->cfdiselectpdf, DownloadType::emitidos());
                //PDF Acuse
                $listpdfacuseemit = $satScraper->listByUuids($this->cfdiselectpdfacuse, DownloadType::emitidos());

                //Rutas
                //En emitidos se basa en rangos de fecha (A diferencia de recibidos), por lo que haremos es obtener el mes
                //Para optimizar la descarga de emitidos y ahorrar tiempo se hara una condicional donde se compara si el mes inicial es igual que la final
                if ($this->mesemitinic . "-" . $this->anioemitinic == $this->mesemitfin . "-" . $this->anioemitfin) {
                    //Rutas
                    //Aqui llamamos a la funcion de meses
                    $mesruta = $this->Meses($this->mesemitinic);

                    //XML
                    $rutaxml = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioemitinic/Descargas/$mesruta/Emitidos/XML/";
                    //PDF
                    $rutapdf = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioemitinic/Descargas/$mesruta/Emitidos/PDF/";
                    //Acuse
                    $rutaacuse = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioemitinic/Descargas/$mesruta/Emitidos/ACUSE/";

                    //Realizamos la descarga
                    //XML
                    $satScraper->resourceDownloader(ResourceType::xml(), $listxmlemit)
                        ->setResourceFileNamer(new FileNameXML())
                        ->saveTo($rutaxml, true, 0777);

                    //PDF
                    $satScraper->resourceDownloader(ResourceType::pdf(), $listpdfemit)
                        ->setResourceFileNamer(new FileNamePDF())
                        ->saveTo($rutapdf, true, 0777);

                    //PDF Acuse
                    $satScraper->resourceDownloader(ResourceType::cancelVoucher(), $listpdfacuseemit)
                        ->setResourceFileNamer(new FileNamePDFAcuse())
                        ->saveTo($rutaacuse, true, 0777);

                    //Vamos a comporbar si la carpeta tiene XML descargados
                    foreach ($this->cfdiselectxml as $listxmlcfdi) {
                        $rutaxmlfile = $rutaxml . strtoupper($listxmlcfdi) . ".xml";
                        if (file_exists($rutaxmlfile)) {
                            $this->SaveXML($rutaxmlfile, strtoupper($listxmlcfdi), $this->tipo);
                        }
                    }

                    //Metodo para mostrar el resultado de las descargas
                    $this->AddCalEmit();
                } else {
                    //XML
                    foreach ($listxmlemit as $listxmlemitdato) {
                        $mesreciemitxml = date("m", strtotime($listxmlemitdato->fechaEmision)); //Descomponemos la fecha al mes
                        $anioreciemitxml = date("Y", strtotime($listxmlemitdato->fechaEmision)); //Descomponemos la fecha el año

                        //Realizamos una consulta del CFDI que vamos a guardar
                        $foliofiscal = [$listxmlemitdato->uuid];
                        $cfdiemitxml = $satScraper->listByUuids($foliofiscal, DownloadType::emitidos());

                        //Aqui llamamos a la funcion de meses
                        //XML
                        $mesrutaxml = $this->Meses($mesreciemitxml);

                        //XML
                        $rutaxml = "storage/contarappv1_descargas/$this->rfcEmpresa/$anioreciemitxml/Descargas/$mesrutaxml/Emitidos/XML/";

                        //Realizamos la descarga
                        //XML
                        $satScraper->resourceDownloader(ResourceType::xml(), $cfdiemitxml)
                            ->setResourceFileNamer(new FileNameXML())
                            ->saveTo($rutaxml, true, 0777);

                        //Vamos a comporbar si la carpeta tiene XML descargados
                        foreach ($this->cfdiselectxml as $listxmlcfdi) {
                            $rutaxmlfile = $rutaxml . strtoupper($listxmlcfdi) . ".xml";
                            if (file_exists($rutaxmlfile)) {
                                $this->SaveXML($rutaxmlfile, strtoupper($listxmlcfdi), $this->tipo);
                            }
                        }
                    }

                    //PDF
                    foreach ($listpdfemit as $listpdfemitdato) {
                        $mesreciemitpdf = date("m", strtotime($listpdfemitdato->fechaEmision)); //Descomponemos la fecha al mes
                        $anioreciemitpdf = date("Y", strtotime($listpdfemitdato->fechaEmision)); //Descomponemos la fecha el año

                        //Realizamos una consulta del CFDI que vamos a guardar
                        $foliofiscal = [$listpdfemitdato->uuid];
                        $cfdiemitxml = $satScraper->listByUuids($foliofiscal, DownloadType::emitidos());

                        //Aqui llamamos a la funcion de meses
                        //PDF
                        $mesrutapdf = $this->Meses($mesreciemitpdf);

                        //PDF
                        $rutapdf = "storage/contarappv1_descargas/$this->rfcEmpresa/$anioreciemitpdf/Descargas/$mesrutapdf/Emitidos/PDF/";

                        //Realizamos la descarga
                        //PDF
                        $satScraper->resourceDownloader(ResourceType::pdf(), $cfdiemitxml)
                            ->setResourceFileNamer(new FileNamePDF())
                            ->saveTo($rutapdf, true, 0777);
                    }

                    //PDF Acuse
                    foreach ($listpdfacuseemit as $listpdfacuseemitdato) {
                        $mesreciemitpdfacu = date("m", strtotime($listpdfacuseemitdato->fechaEmision)); //Descomponemos la fecha al mes
                        $anioreciemitpdfacu = date("Y", strtotime($listpdfacuseemitdato->fechaEmision)); //Descomponemos la fecha el año

                        //Realizamos una consulta del CFDI que vamos a guardar
                        $foliofiscal = [$listpdfacuseemitdato->uuid];
                        $cfdiemitxml = $satScraper->listByUuids($foliofiscal, DownloadType::emitidos());

                        //Aqui llamamos a la funcion de meses
                        //PDF Acuse
                        $mesrutapdfacuse = $this->Meses($mesreciemitpdfacu);

                        //Acuse
                        //Acuse
                        $rutaacuse = "storage/contarappv1_descargas/$this->rfcEmpresa/$anioreciemitpdfacu/Descargas/$mesrutapdfacuse/Emitidos/ACUSE/";

                        //Realizamos la descarga
                        //PDF Acuse
                        $satScraper->resourceDownloader(ResourceType::cancelVoucher(), $cfdiemitxml)
                            ->setResourceFileNamer(new FileNamePDFAcuse())
                            ->saveTo($rutaacuse, true, 0777);
                    }
                }

                //Llamamos la funcion para almacenar los metadatos en la base de datos
                //PDF
                $this->SaveMetadatos($listpdfemit, $this->tipo);

                //XML
                $this->SaveMetadatos($listxmlemit, $this->tipo);

                //Acuse
                $this->SaveMetadatos($listpdfacuseemit, $this->tipo);

                //Metodo para mostrar el resultado de las descargas
                $this->AddCalEmit();

                //Limpiamos los arreglos
                $this->cfdiselectxml = [];
                $this->cfdiselectpdf = [];
                $this->cfdiselectpdfacuse = [];
                break;
            default:
                $this->successdescarga = "No hay tipo";
                break;
        }
    }

    //Agregar al calendario de recibidos y emitidos
    //Metodo para agregar los descargados recibidos en la base de datos de calendario
    public function AddCalReci()
    {
        //Variables
        $totaldesc = 0; //Total de descargados
        $totalcfdi = $this->totallist; //Total de cfdi
        $allcfdi = []; //arreglo donde se guardaran todos los uuids

        //Variables para el guardados de la base
        $fechadesc = $this->anioreci . '-' . $this->mesreci . '-' . $this->diareci; //Fecha de descarga
        $cfdidesc = 0;
        $cfdierror = 0;
        $cfdirecibi = 0;

        //Obtendremos los uuids seleccionados
        $allcfdi = array_merge($this->cfdiselectxml, $this->cfdiselectpdf, $this->cfdiselectpdfacuse);
        //Eliminamos los uuids repetidos
        $allcfdi = array_unique($allcfdi);

        //Ejecutamos el metodo de los meses
        $mesruta = $this->Meses($this->mesreci);

        //Rutas
        //XML
        $rutaxml = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/XML/";
        //PDF
        $rutapdf = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/PDF/";
        //Acuse
        $rutapdfacuse = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/ACUSE/";

        //Con un bucle pasamos por los uuids almacenados en el arreglo
        foreach ($allcfdi as $listuuids) {
            //Buscamos si exsiten los archivos (si estn descargados)
            //XML/PDF/Acuse
            $xmlfile = $rutaxml . strtoupper($listuuids) . '.xml';
            $pdffile = $rutapdf . strtoupper($listuuids) . '.pdf';
            $acusefile = $rutapdfacuse . strtoupper($listuuids) . '-acuse' . '.pdf';

            if (file_exists($xmlfile) || file_exists($pdffile) || file_exists($acusefile)) {
                $totaldesc++;
            } else {
                $cfdierror++;
            }
        }

        //En una condicional comparamos si el total de descargados es el total de la descarga
        if ($totaldesc == $totalcfdi) {
            $cfdidesc = $totalcfdi; //Agregamos el total descargado
            $cfdirecibi = $totalcfdi - $cfdierror; //Agregamos el total recibido

        } else {
            //De lo contrario sacamos los errores
            $cfdidesc = $totalcfdi; //Agregamos el total descargado
            $cfdirecibi = $totalcfdi - $cfdierror; //Agregamos el total recibido
        }

        //Agregar a la base de datos
        $busca = Calendario::where(['rfc' => $this->rfcEmpresa]);
        $busca->update(
            [
                'rfc' => $this->rfcEmpresa,
                'descargas.' . $fechadesc . '.fechaDescargas' => $fechadesc,
                'descargas.' . $fechadesc . '.descargasRecibidos' => $cfdidesc,
                'descargas.' . $fechadesc . '.erroresRecibidos' => $cfdierror,
                'descargas.' . $fechadesc . '.totalRecibidos' => $cfdirecibi,
            ],
            ['upsert' => true]
        );
    }

    //Metodo para agregar los descargados emitidos en la base de datos de calendario
    public function AddCalEmit()
    {
        //Variables
        $allcfdi = []; //arreglo donde se guardaran todos los uuids
        $fechainic = $this->anioemitinic . '-' . $this->mesemitinic . '-' . $this->diaemitinic; //Fecha inicial
        $fechafin = $this->anioemitfin . '-' . $this->mesemitfin . '-' . $this->diaemitfin; //Fecha final

        //Obtendremos los uuids seleccionados
        $allcfdi = array_merge($this->cfdiselectxml, $this->cfdiselectpdf, $this->cfdiselectpdfacuse);
        //Eliminamos los uuids repetidos
        $allcfdi = array_unique($allcfdi);

        //Obtener el rengo de fechas
        for ($i = $fechainic; $i <= $fechafin; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {
            //Variables para el guardados de la base
            $fechadesc = $i; //Fecha de descarga
            $cfdidesc = 0;
            $cfdierror = 0;
            $cfdirecibi = 0;
            $totaldesc = 0; //Total de descargados

            //Sacamos el total de los cfdis descargados
            $totalcfdi = $this->totallist; //Total de cfdi

            //Obtenemos el mes
            $mesruta = date("m", strtotime($i));
            $anioruta = date("Y", strtotime($i));

            //Ejecutamos el metodo de los meses
            $mesruta = $this->Meses($mesruta);

            //Rutas
            //XML
            $rutaxml = "storage/contarappv1_descargas/$this->rfcEmpresa/$anioruta/Descargas/$mesruta/Emitidos/XML/";
            //PDF
            $rutapdf = "storage/contarappv1_descargas/$this->rfcEmpresa/$anioruta/Descargas/$mesruta/Emitidos/PDF/";
            //Acuse
            $rutapdfacuse = "storage/contarappv1_descargas/$this->rfcEmpresa/$anioruta/Descargas/$mesruta/Emitidos/ACUSE/";

            //Con un bucle pasamos por los uuids almacenados en el arreglo
            foreach ($allcfdi as $listuuids) {
                $fechacfdiselect = "";

                //Consultamos los datos del cfdi
                $listdatacfdi = XmlE::where(['UUID' => strtoupper($listuuids)])->get();

                //Obtenemos la fecha
                foreach ($listdatacfdi as $listdatacfdi) {
                    $fechacfdiselect = date("Y-m-d", strtotime($listdatacfdi->Fecha));
                }

                //Condicional para saber si la fecha es igual
                if ($fechacfdiselect == $i) {
                    //Buscamos si exsiten los archivos (si estn descargados)
                    //XML/PDF/Acuse
                    $xmlfile = $rutaxml . strtoupper($listuuids) . '.xml';
                    $pdffile = $rutapdf . strtoupper($listuuids) . '.pdf';
                    $acusefile = $rutapdfacuse . strtoupper($listuuids) . '-acuse' . '.pdf';

                    if (file_exists($xmlfile) || file_exists($pdffile) || file_exists($acusefile)) {
                        $totaldesc++;
                    } else {
                        $cfdierror++;
                    }
                }
            }


            //En una condicional comparamos si el total de descargados es el total de la descarga
            if ($totaldesc == $totalcfdi) {
                $cfdidesc = $totalcfdi; //Agregamos el total descargado
                $cfdirecibi = $totalcfdi - $cfdierror; //Agregamos el total recibido

            } else {
                //De lo contrario sacamos los errores
                $cfdidesc = $totalcfdi; //Agregamos el total descargado
                $cfdirecibi = $totalcfdi - $cfdierror; //Agregamos el total recibido
            }

            //Agregar a la base de datos
            $busca = Calendario::where(['rfc' => $this->rfcEmpresa]);
            $busca->update(
                [
                    'rfc' => $this->rfcEmpresa,
                    'descargas.' . $fechadesc . '.fechaDescargas' => $fechadesc,
                    'descargas.' . $fechadesc . '.descargasEmitidos' => $cfdidesc,
                    'descargas.' . $fechadesc . '.erroresEmitidos' => $cfdierror,
                    'descargas.' . $fechadesc . '.totalEmitidos' => $cfdirecibi,
                ],
                ['upsert' => true]
            );
        }
    }

    //Metodo para limpiar los campos de busqueda (Esto sucedera al cambiar de empresa (si es contador) y al cambiar de recibido a emitidos)
    public function ResetParamColsul()
    {
        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Recibidos)
        $this->anioreci = date("Y");
        $this->mesreci = date("m");
        $this->diareci = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango inicio)
        $this->anioemitinic = date("Y");
        $this->mesemitinic = date("m");
        $this->diaemitinic = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango fin)
        $this->anioemitfin = date("Y");
        $this->mesemitfin = date("m");
        $this->diaemitfin = date("j");

        //Reinciamos los arreglos
        $this->cfdiselectxml = [];
        $this->cfdiselectpdf = [];
        $this->cfdiselectpdfacuse = [];

        //Emitimos una accion para recibirlo en la vista
        $this->dispatchBrowserEvent('deschecked', []);
    }

    //Metrodo para reiniciar el modal
    public function RefreshCal()
    {
        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("m");
    }

    //Metodo para crear el calendario
    public function Calendario()
    {
        //Calendario
        //Obtenemos la zona horaria
        date_default_timezone_set('America/Mexico_City');

        //Condicional para saber si el mes y año tiene algun valor
        if (isset($this->aniocal) || isset($this->mescal)) {
            //Si tiene algo obtenemos el valor de las variables
            $ym = $this->aniocal . "-" . $this->mescal;
        } else {
            //De lo contario no vamos al mes y año actual
            $ym = date('Y-m');
        }

        //Establecemos el inicio del calendario
        $timestamp = strtotime($ym . '-01');
        if ($timestamp === false) {
            $ym = date('Y-m');
            $timestamp = strtotime($ym . '-01');
        }

        //Obtenemos el dia de hoy
        $today = date('Y-m-d', time());

        //Obtenemos lo dias que tiene el mes
        $day_count = date('t', $timestamp);

        // 0:Sun 1:Mon 2:Tue ...
        $str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));

        //Variables para la creacion del calendario
        $weeks = array();
        $week = '';

        //Campos vacios
        $week .= str_repeat('<td></td>', $str);

        //Haremos una consulta al calendarios de recibidos/emitidos
        $LogReciEmical = Calendario::where(['rfc' => $this->rfcEmpresa])->get()->first();

        //Ciclo for para llenar los campos con los dias que le pertenece
        for ($day = 1; $day <= $day_count; $day++, $str++) {
            //Formamos la fecha completa
            $date = $ym . '-' . $day;

            //Iniciamos en cero la variable por cada iteracion que se haga
            $this->reciboemit = 0;

            //Switch para marcar el dia de hoy
            switch ($date) {
                case $today:
                    $week .= '<td class="hoy">' . $day;

                    //Descomonemos la consulta y insertamos los datos requeridos
                    //Recibidos
                    if (isset($LogReciEmical['descargas.' . $date . '.descargasRecibidos'])) {
                        $week .= "<br><br>" . '<b>' . "Recibidos" . '</b>' . '<br>' .
                            "Descargados: " . $LogReciEmical['descargas.' . $date . '.descargasRecibidos'] . '<br>' .
                            "Errores: " . $LogReciEmical['descargas.' . $date . '.erroresRecibidos'] . '<br>' .
                            "Total: " . $LogReciEmical['descargas.' . $date . '.totalRecibidos'];
                    }

                    //Emitidos
                    if (isset($LogReciEmical['descargas.' . $date . '.descargasEmitidos'])) {
                        $week .= "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                            "Descargados: " . $LogReciEmical['descargas.' . $date . '.descargasEmitidos'] . '<br>' .
                            "Errores: " . $LogReciEmical['descargas.' . $date . '.erroresEmitidos'] . '<br>' .
                            "Total: " . $LogReciEmical['descargas.' . $date . '.totalEmitidos'];
                    }

                    break;
                default:
                    $week .= '<td>' . $day;

                    //Descomonemos la consulta y insertamos los datos requeridos
                    //Recibidos
                    if (isset($LogReciEmical['descargas.' . $date . '.descargasRecibidos'])) {
                        $week .= "<br><br>" . '<b>' . "Recibidos" . '</b>' . '<br>' .
                            "Descargados: " . $LogReciEmical['descargas.' . $date . '.descargasRecibidos'] . '<br>' .
                            "Errores: " . $LogReciEmical['descargas.' . $date . '.erroresRecibidos'] . '<br>' .
                            "Total: " . $LogReciEmical['descargas.' . $date . '.totalRecibidos'];
                    }

                    //Emitidos
                    if (isset($LogReciEmical['descargas.' . $date . '.descargasEmitidos'])) {
                        $week .= "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                            "Descargados: " . $LogReciEmical['descargas.' . $date . '.descargasEmitidos'] . '<br>' .
                            "Errores: " . $LogReciEmical['descargas.' . $date . '.erroresEmitidos'] . '<br>' .
                            "Total: " . $LogReciEmical['descargas.' . $date . '.totalEmitidos'];
                    }

                    break;
            }

            //Cerramos la celda que pertenece el dia
            $week .= '</td>';

            //Condicional para saber si llegamos el final de la semana o mes
            if ($str % 7 == 6 || $day == $day_count) {

                //Condicion para saber si el dia pertenece al final de los dias contados
                if ($day == $day_count) {
                    //Agregamos un campo vacio
                    $week .= str_repeat('<td></td>', 6 - ($str % 7));
                }

                //Todas las semanas las agregas en un arreglo
                $weeks[] = '<tr>' . $week . '</tr>';

                //Limpiamos la variable para agregar ora semana
                $week = '';
            }
        }

        //Retornamos el valor de las semanas
        return $weeks;
    }

    //Metodo para obtener los datos de las empresas para la auntenticacion
    public function ObtAuth()
    {
        //Reiniciamos los parametros de busqueda
        $this->ResetParamColsul();

        if (empty($this->rfcEmpresa)) {
            $this->dircer = "";
            $this->dirkey = "";
            $this->pwd = "";
            $this->rfcemp = "";
        } else {
            //Obtenemos los valores para la auntenticacion
            $AuntDesca = User::where('RFC', $this->rfcEmpresa)
                ->get();

            //Los metemos en las variables dedicadas
            foreach ($AuntDesca as $DatAuthEmpre) {
                $this->dircer = $DatAuthEmpre->dircer;
                $this->dirkey = $DatAuthEmpre->dirkey;
                $this->pwd = $DatAuthEmpre->pass;
                $this->rfcemp = $DatAuthEmpre->RFC;
            }
        }

        //Emitimos una accion para recibirlo en la vista
        $this->dispatchBrowserEvent('deschecked', []);
    }

    //Metodo para preparar procesos antes de iniciar
    public function mount()
    {
        //Condicional para saber si es una cuenta de contador o empresa
        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {
            $this->rfcEmpresa = auth()->user()->RFC;
        }

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Recibidos)
        $this->anioreci = date("Y");
        $this->mesreci = date("m");
        $this->diareci = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango inicio)
        $this->anioemitinic = date("Y");
        $this->mesemitinic = date("m");
        $this->diaemitinic = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango fin)
        $this->anioemitfin = date("Y");
        $this->mesemitfin = date("m");
        $this->diaemitfin = date("j");

        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("m");

        //Vamos a establecer el tipo como recibido al iniciar
        $this->tipo = "Recibidos";

        //Condicional para saber si el Rfc tiene algo y realiza el almacenado a las variables necesarias
        if (empty($this->rfcEmpresa)) {
            $this->dircer = "";
            $this->dirkey = "";
            $this->pwd = "";
            $this->rfcemp = "";
        } else {
            //Obtenemos los valores para la auntenticacion
            $AuntDesca = User::where('RFC', $this->rfcEmpresa)
                ->get();

            //Los metemos en las variables dedicadas
            foreach ($AuntDesca as $DatAuthEmpre) {
                $this->dircer = $DatAuthEmpre->dircer;
                $this->dirkey = $DatAuthEmpre->dirkey;
                $this->pwd = $DatAuthEmpre->pass;
                $this->rfcemp = $DatAuthEmpre->RFC;
            }
        }
    }

    public function render()
    {
        //Obtenemos el valor del metodo del calendario
        $weeks = $this->Calendario();

        //Obtenemos la consulta
        $list = $this->ConsultSAT();

        //Contamos el total de registros arrojados
        if (!is_string($list)) {
            $this->totallist = count($list);
        } else {
            $this->totallist = " - ";
        }

        //Arreglo de los meses
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

        //Arreglo (rango) del año actual al 2014
        $anios = range(2014, date('Y'));

        //Condicional para obtener el tipo de usuario y almacenar las empresas viculadas de estas
        if (!empty(auth()->user()->tipo)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->get();

                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else if (!empty(auth()->user()->TipoSE)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->get();

                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else {
            $emp = '';
        }

        return view('livewire.descargas', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'meses' => $meses, 'anios' => $anios, 'weeks' => $weeks, 'list' => $list, "totallist" => $this->totallist])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
