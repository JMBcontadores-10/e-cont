<?php

namespace App\Http\Livewire;

//Clases para acceder al SAT
use PhpCfdi\CfdiSatScraper\QueryByFilters;
use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\SatScraper;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionData;
use PhpCfdi\Credentials\Credential;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use PhpCfdi\CfdiSatScraper\SatHttpGateway;

use App\Models\CalendarioR;
use App\Models\CalendarioE;
use App\Models\User;
use DateTimeImmutable;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas
set_time_limit(3600);

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
    public $chkxml; //Banderas para saber si extan activos los checks
    public $chkpdf; //Banderas para saber si extan activos los checks

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

    //Consultas del SAT (Emitidos o recibidos)
    public function ConsultSAT()
    {
        //Condicional para comprobar si hay una empresa (Si es contador)
        if ($this->rfcEmpresa) {
            /*Como se va a realizar una peticion a la pagina del SAT vamos a realizar un try catch para verificar que la conexion
        se realizo correctamente*/
            try {
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

    //Metodo para descargar los archivos relacionados
    public function Descvincucfdi()
    {
        //Convertir los meses en el formato carpetado
        function Meses($mes)
        {
            //Mes
            switch ($mes) {
                case '1':
                    return '1.Enero';
                    break;

                case '2':
                    return '2.Febrero';
                    break;

                case '3':
                    return '3.Marzo';
                    break;

                case '4':
                    return '4.Abril';
                    break;

                case '5':
                    return '5.Mayo';
                    break;

                case '6':
                    return '6.Junio';
                    break;

                case '7':
                    return '7.Julio';
                    break;

                case '8':
                    return '8.Agosto';
                    break;

                case '9':
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

        //Acceso a la sesion
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
                $mesruta = Meses($this->mesreci);

                //XML
                $rutaxml = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/XML/";
                //PDF/Acuse
                $rutapdf = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesruta/Recibidos/PDF/";

                //Realizamos la descarga
                //XML
                $satScraper->resourceDownloader(ResourceType::xml(), $listxmlreci)
                    ->saveTo($rutaxml, true, 0777);

                //PDF
                $satScraper->resourceDownloader(ResourceType::pdf(), $listpdfreci)
                    ->saveTo($rutapdf, true, 0777);

                //PDF Acuse
                $satScraper->resourceDownloader(ResourceType::cancelVoucher(), $listpdfacusereci)
                    ->saveTo($rutapdf, true, 0777);

                //Limpiamos los arreglos
                $this->cfdiselectxml = [];
                $this->cfdiselectpdf = [];
                $this->cfdiselectpdfacuse = [];

                //Ponemos en cero los checks
                $this->chkxml = 0;
                $this->chkpdf = 0;
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
                //XML
                foreach ($listxmlemit as $listxmlemitdato) {
                    $mesreciemitxml = $listxmlemitdato->fechaEmision;
                    $mesreciemitxml = explode("-", $mesreciemitxml);
                    $mesreciemitxml = intval($mesreciemitxml[1]);

                    //Realizamos una consulta del CFDI que vamos a guardar
                    $foliofiscal = [$listxmlemitdato->uuid];
                    $cfdiemitxml = $satScraper->listByUuids($foliofiscal, DownloadType::emitidos());

                    //Aqui llamamos a la funcion de meses
                    //XML
                    $mesrutaxml = Meses($mesreciemitxml);

                    //XML
                    $rutaxml = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesrutaxml/Emitidos/XML/";

                    //Realizamos la descarga
                    //XML
                    $satScraper->resourceDownloader(ResourceType::xml(), $cfdiemitxml)
                        ->saveTo($rutaxml, true, 0777);
                }

                //PDF
                foreach ($listpdfemit as $listpdfemitdato) {
                    $mesreciemitpdf = $listpdfemitdato->fechaEmision;
                    $mesreciemitpdf = explode("-", $mesreciemitpdf);
                    $mesreciemitpdf = intval($mesreciemitpdf[1]);

                    //Realizamos una consulta del CFDI que vamos a guardar
                    $foliofiscal = [$listpdfemitdato->uuid];
                    $cfdiemitxml = $satScraper->listByUuids($foliofiscal, DownloadType::emitidos());

                    //Aqui llamamos a la funcion de meses
                    //PDF
                    $mesrutapdf = Meses($mesreciemitpdf);

                    //PDF
                    $rutapdf = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesrutapdf/Emitidos/PDF/";

                    //Realizamos la descarga
                    //PDF
                    $satScraper->resourceDownloader(ResourceType::pdf(), $cfdiemitxml)
                        ->saveTo($rutapdf, true, 0777);
                }

                //PDF Acuse
                foreach ($listpdfacuseemit as $listpdfacuseemitdato) {
                    $mesreciemitpdfacu = $listpdfacuseemitdato->fechaEmision;
                    $mesreciemitpdfacu = explode("-", $mesreciemitpdfacu);
                    $mesreciemitpdfacu = intval($mesreciemitpdfacu[1]);

                    //Realizamos una consulta del CFDI que vamos a guardar
                    $foliofiscal = [$listpdfacuseemitdato->uuid];
                    $cfdiemitxml = $satScraper->listByUuids($foliofiscal, DownloadType::emitidos());

                    //Aqui llamamos a la funcion de meses
                    //PDF Acuse
                    $mesrutapdfacuse = Meses($mesreciemitpdfacu);

                    //Acuse
                    $rutapdfacu = "storage/contarappv1_descargas/$this->rfcEmpresa/$this->anioreci/Descargas/$mesrutapdfacuse/Emitidos/PDF/";

                    //Realizamos la descarga
                    //PDF Acuse
                    $satScraper->resourceDownloader(ResourceType::cancelVoucher(), $cfdiemitxml)
                        ->saveTo($rutapdfacu, true, 0777);
                }

                //Limpiamos los arreglos
                $this->cfdiselectxml = [];
                $this->cfdiselectpdf = [];
                $this->cfdiselectpdfacuse = [];

                //Ponemos en cero los checks
                $this->chkxml = 0;
                $this->chkpdf = 0;
                break;
            default:
                $this->successdescarga = "No hay tipo";
                break;
        }
    }

    //Metodo para limpiar los campos de busqueda (Esto sucedera al cambiar de empresa (si es contador) y al cambiar de recibido a emitidos)
    public function ResetParamColsul()
    {
        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Recibidos)
        $this->anioreci = date("Y");
        $this->mesreci = date("n");
        $this->diareci = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango inicio)
        $this->anioemitinic = date("Y");
        $this->mesemitinic = date("n");
        $this->diaemitinic = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango fin)
        $this->anioemitfin = date("Y");
        $this->mesemitfin = date("n");
        $this->diaemitfin = date("j");

        //Reinciamos los arreglos
        $this->cfdiselectxml = [];
        $this->cfdiselectpdf = [];
        $this->cfdiselectpdfacuse = [];

        //Ponemos en cero los checks
        $this->chkxml = 0;
        $this->chkpdf = 0;
    }

    //Metodo para marcar todos los checkbox (XML Recibidos)
    public function Allchk($tipo)
    {
        if ($this->chkxml || $this->chkpdf) {
            //Condicional para saber que tipo de datos se van a seleccionar
            switch ($tipo) {
                case "xmlall":
                    //Obtenemos la consulta
                    $list = $this->ConsultSAT();
                    //Introducimos los valores de la lista (consulta en el arreglo para hacer un check all)
                    foreach ($list as $UUID) {
                        array_push($this->cfdiselectxml, $UUID->uuid);
                    }
                    break;

                case "pdfall":
                    //Obtenemos la consulta
                    $list = $this->ConsultSAT();
                    //Introducimos los valores de la lista (consulta en el arreglo para hacer un check all)
                    foreach ($list as $UUID) {
                        array_push($this->cfdiselectpdf, $UUID->uuid);
                    }
                    break;
            }
        }

        //Condicional para corroborar que si estan desmarcados
        if (empty($this->chkxml)) {
            //Si el checkbox de xml esta desactivado
            $this->cfdiselectxml = [];
        }

        //Condicional para corroborar que si estan desmarcados
        if (empty($this->chkpdf)) {
            //Si el checkbox de pdf esta desactivado
            $this->cfdiselectpdf = [];
        }
    }

    //Metrodo para reiniciar el modal
    public function RefreshCal()
    {
        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("n");
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
            $ym = date('Y-n');
        }

        //Establecemos el inicio del calendario
        $timestamp = strtotime($ym . '-01');
        if ($timestamp === false) {
            $ym = date('Y-n');
            $timestamp = strtotime($ym . '-01');
        }

        //Obtenemos el dia de hoy
        $today = date('Y-n-d', time());

        //Obtenemos lo dias que tiene el mes
        $day_count = date('t', $timestamp);

        // 0:Sun 1:Mon 2:Tue ...
        $str = date('w', mktime(0, 0, 0, date('n', $timestamp), 1, date('Y', $timestamp)));

        //Variables para la creacion del calendario
        $weeks = array();
        $week = '';

        //Campos vacios
        $week .= str_repeat('<td></td>', $str);

        //Haremos una consulta al calendarios de recibidos
        $LogRecical = CalendarioR::select('fechaDescarga', 'rfc', 'canceladosRecibidos', 'descargasRecibidos', 'erroresRecibidos', 'totalRecibidos')
            ->where('rfc', $this->rfcEmpresa)
            ->groupBy('fechaDescarga')
            ->orderBy('fechaDescarga', 'asc')
            ->get();

        //Haremos una consulta al calendarios de emitidos
        $LogEmitcal = CalendarioE::select('fechaDescarga', 'rfc', 'descargasEmitidos', 'erroresEmitidos', 'totalEmitidos')
            ->where('rfc', $this->rfcEmpresa)
            ->groupBy('fechaDescarga')
            ->orderBy('fechaDescarga', 'asc')
            ->get();

        //Ciclo for para llenar los campos con los dias que le pertenece
        for ($day = 1; $day <= $day_count; $day++, $str++) {

            //Condicional para formatear el dia de unidades y decenas (ya que en la base de datos tiene un formato diferente)
            if ($day < 10 && $this->aniocal > 2021) {
                $date = $ym . '-0' . $day;
            } else {
                $date = $ym . '-' . $day;
            }

            //Iniciamos en cero la variable por cada iteracion que se haga
            $this->reciboemit = 0;

            //Switch para marcar el dia de hoy
            switch ($date) {
                case $today:
                    $week .= '<td class="hoy">' . $day;

                    //Agregamos los recibidos
                    foreach ($LogRecical as $DataReci) {
                        if ($DataReci->fechaDescarga == $date) {
                            $week .= "<br><br>" . '<b>' . "Recibidos" . '</b>' . '<br>' .
                                "Cancelados: " . $DataReci->canceladosRecibidos . '<br>' .
                                "Descargados: " . $DataReci->descargasRecibidos . '<br>' .
                                "Errores: " . $DataReci->erroresRecibidos . '<br>' .
                                "Total: " . $DataReci->totalRecibidos;
                        }
                    }

                    //Agregamos los emitidos
                    foreach ($LogEmitcal as $DataEmit) {
                        if ($DataEmit->fechaDescarga == $date) {
                            $week .= "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                                "Descargados: " . $DataEmit->descargasEmitidos . '<br>' .
                                "Errores: " . $DataEmit->erroresEmitidos . '<br>' .
                                "Total: " . $DataEmit->totalEmitidos;
                        }
                    }

                    break;
                default:
                    $week .= '<td>' . $day;

                    //Agregamos los recibidos
                    foreach ($LogRecical as $DataReci) {
                        if ($DataReci->fechaDescarga == $date) {
                            $week .= "<br><br>" . '<b>' . "Recibidos" . '</b>' . '<br>' .
                                "Cancelados: " . $DataReci->canceladosRecibidos . '<br>' .
                                "Descargados: " . $DataReci->descargasRecibidos . '<br>' .
                                "Errores: " . $DataReci->erroresRecibidos . '<br>' .
                                "Total: " . $DataReci->totalRecibidos;
                        }
                    }

                    //Agregamos los emitidos
                    foreach ($LogEmitcal as $DataEmit) {
                        if ($DataEmit->fechaDescarga == $date) {
                            $week .= "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                                "Descargados: " . $DataEmit->descargasEmitidos . '<br>' .
                                "Errores: " . $DataEmit->erroresEmitidos . '<br>' .
                                "Total: " . $DataEmit->totalEmitidos;
                        }
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
        $this->mesreci = date("n");
        $this->diareci = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango inicio)
        $this->anioemitinic = date("Y");
        $this->mesemitinic = date("n");
        $this->diaemitinic = date("j");

        //Configuramos la fecha del filtro para que muestre la fecha de hoy (Emitidos rango fin)
        $this->anioemitfin = date("Y");
        $this->mesemitfin = date("n");
        $this->diaemitfin = date("j");

        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("n");

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

        //Arreglo de los meses
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

        return view('livewire.descargas', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'meses' => $meses, 'anios' => $anios, 'weeks' => $weeks, 'list' => $list])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
