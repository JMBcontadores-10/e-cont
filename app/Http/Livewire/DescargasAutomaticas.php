<?php

namespace App\Http\Livewire;

use Livewire\Component;


namespace App\Http\Controllers;

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

use App\Models\CalendarioR;
use App\Models\CalendarioE;
use App\Models\MetadataE;
use App\Models\MetadataR;
use App\Models\User;
use App\Models\XmlE;
use App\Models\XmlR;
use DateTimeImmutable;
use DirectoryIterator;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use MongoDB\Operation\Update;
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




class DescargasAutomaticas extends Controller
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
  public $tipo = "Emitidos";
  public $chkxml; //Banderas para saber si extan activos los checks
  public $chkpdf; //Banderas para saber si extan activos los checks
  public $solocfdi = []; //Almacena los UUID de los cfdi seleccionados

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

  public $info;

  public $rfcs = [/// array que contiene las empresas
    // '1',
   // 'AHF060131G59',
   'AHF060131G59',
   'AFU1809135Y4',
   'AIJ161001UD1',
   'AAE160217C36',
   'CDI1801116Y9',
   'COB191129AZ2',
   'DOT1911294F3',
   'DRO191104EZ0',
   'DRO191129DK5',
   'ERO1911044L4',
   'PERE9308105X4',
   'FGA980316918',
   'GPA161202UG8',
   'GEM190507UW8',
   'GPR020411182',
   'HRU121221SC2',
   'IAB0210236I7',
   'JQU191009699',
   'JCO171102SI9',
   'MEN171108IG6',
   'MAR191104R53',
   'MCA130429FM8',
   'MCA130827V4A',
   'MOP18022474A',
   'MOBJ8502058A4',
   'PEM180224742',
   'PEMJ7110258J3',
   'PML170329AZ9',
   'PERA0009086X3',
   'PER180309RB3',
   'RUCE750317I21',
   'SBE190522I97',
   'SGA1905229H3',
   'SGA1410217U4',
   'SGT190523QX8',
   //'SGX190523KA4',
   'SGX160127MC4',
   'STR9303188X3',
   'SVI831123632',
   'SCT150918RC9',
   'SAJ161001KC6',
   'SPE171102P94',
   'SCO1905221P2',
   'GMH1602172L8',
   'MGE1602172LA',
   'SAE191009dd8',
   'SMA180913NK6',
   'SST030407D77',
   'TEL1911043PA',
   'TOVF901004DN5',
   'VER191104SP3',
   'VPT050906GI8',
   'VCO990603D84',
   'IAR010220GK5',
   'GRU210504TH9',
  // 'GMG21010706W2',
  // 'JCO2105043Y1',
];



  //Consultas del SAT (Emitidos o recibidos)
  public function ConsultSAT()
  {


    foreach($this->rfcs as $rfc){///foreach empresas

        $cliente = DB::table('clientes')
        //  ->select('tipo', 'password', 'Id_Conta','nombre')
          ->where('RFC', $rfc)
          ->first();
echo $cliente['RFC']."<br>";



          /*Como se va a realizar una peticion a la pagina del SAT vamos a realizar un try catch para verificar que la conexion
      se realizo correctamente*/
          try {


              //Variables de cookies de sesion para no volver a realizar el inicio de sesion
              $cookieJarPath = sprintf('%s\build\cookies\%s.json', getcwd(), $cliente['RFC']);
              //Se almacena ña cookie en un gateway para mandarlo al cliente y este realizar las consultas
              $gateway = new SatHttpGateway(new Client(), new FileCookieJar($cookieJarPath, true));

              //Obtiene las variables para crear el crtificado
              $certificate = 'storage/'.$cliente['dircer'];
              $privateKey = 'storage/'.$cliente['dirkey'] ;
              $passPhrase = $cliente['pass'];

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
                      new DateTimeImmutable('2022-04-16'),
                      new DateTimeImmutable('2022-04-18')
                  );
              } else {
                  switch ($this->diareci) {
                      case "all":
                          //Obtenemos el valor del ultimo dia
                          $timestamp = strtotime('2022-04-18');
                          $day_count = date('t', $timestamp);

                          $query = new QueryByFilters(
                              new DateTimeImmutable('2022-04-16'),
                              new DateTimeImmutable('2022-04-18' . $day_count)
                          );
                          $query->setDownloadType(DownloadType::recibidos());
                          break;
                      default:
                          $query = new QueryByFilters(
                              new DateTimeImmutable('2022-04-16'),
                              new DateTimeImmutable('2022-04-18')
                          );
                          $query->setDownloadType(DownloadType::recibidos());
                          break;
                  }
              }

              //Retornamos el valor de la consulta
            echo  sizeof($satScraper->listByPeriod($query));
          } catch (Exception $e) {
              //Retornamos un mensaje de error
              return "Parece que hubo un error: " . $e;
          }




}/// fin del foeach clientes


  }

///=========================================================================///////
}