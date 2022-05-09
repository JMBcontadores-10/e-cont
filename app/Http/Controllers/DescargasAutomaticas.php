<?php

namespace App\Http\Controllers;

//Clases para acceder al SAT

use App\Models\Calendario;
use PhpCfdi\CfdiSatScraper\QueryByFilters;
use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\SatScraper;

use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionData;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\CfdiToJson\JsonConverter;
use PhpCfdi\CfdiSatScraper\Contracts\MaximumRecordsHandler;

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
use DateTime;
use DateTimeImmutable;
use DirectoryIterator;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use MongoDB\Operation\Update;
use PhpCfdi\CfdiCleaner\Cleaner;
use PhpCfdi\CfdiSatScraper\Contracts\ResourceFileNamerInterface;
use PhpCfdi\CfdiSatScraper\Filters\DownloadType;
use PhpCfdi\CfdiSatScraper\Sessions\SessionManager;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(9200); //Tiempo limite dado 1 hora

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



class DescargasAutomaticas extends Controller
{




  public $tipo = "Emitidos";
  public $info;
  public $empresas;

  public $rfcs = [/// array que contiene las empresas
    // '1',
   // 'AHF060131G59',
// 'AHF060131G59',
//     'AFU1809135Y4',
//   'AIJ161001UD1',
//    'AAE160217C36',
//    'CDI1801116Y9',
//    'COB191129AZ2',
//    'DOT1911294F3',
//     'DRO191104EZ0',
//    'DRO191129DK5',
//    'ERO1911044L4',
 // 'PERE9308105X4',
   'FGA980316918',
//    'GPA161202UG8',
//    'GEM190507UW8',
//    'GPR020411182',
//    'HRU121221SC2',
//    'IAB0210236I7',
//    'JQU191009699',
//    'JCO171102SI9',
//    'MEN171108IG6',
//    'MAR191104R53',

//   'MCA130429FM8',
//    'MCA130827V4A',
//    'MOP18022474A',
//    'MOBJ8502058A4',
//    'PEM180224742',
//    'PEMJ7110258J3',
//    'PML170329AZ9',
//    'PERA0009086X3',
//    'PER180309RB3',
//    'RUCE750317I21',
//    'SBE190522I97',
//    'SGA1905229H3',
// 'SGA1410217U4',
//    'SGT190523QX8',
// 'SGX190523KA4',
//    'SGX160127MC4',
//    'STR9303188X3',
//    'SVI831123632',
//   'SCT150918RC9',
//   'SAJ161001KC6',
//    'SPE171102P94',
//    'SCO1905221P2',
//    'GMH1602172L8',
//    'MGE1602172LA',
//    'SAE191009dd8',
//    'SMA180913NK6',
//    'SST030407D77',
//    'TEL1911043PA',
//    'TOVF901004DN5',
//    'VER191104SP3',
//    'VPT050906GI8',
//    'VCO990603D84',
//    'IAR010220GK5',
//    'GRU210504TH9',
//   'GMG21010706W2',
//   'JCO2105043Y1',
];



public function __construct()
    {


        $ignore=['06' ,'08', '09', '26', '50'];
        /////IGNORE=(06 08 09 26 50)

$rfcIgnore=['SST030407D77J','SST030407D77M','PERE9308105X4C','PERE9308105X4T','ADMINISTRADOR','NOMINAS','AIJ161001UD1',
'SST030407D77M',
'SGP210107CE8',
'SGX190523KA4',

// 'AAE160217C36',
// 'AFU1809135Y4',
// 'AHF060131G59',
// 'GEM190507UW8',
// 'FGA980316918',
// 'ERO1911044L4',
// 'DRO191129DK5',
// 'DRO191104EZ0',
// 'DOT1911294F3',
// 'CDI1801116Y9',
// 'GEM190507UW8',
// 'GPA161202UG8',
// 'GMH1602172L8',
// 'GMG2101076W2',
// 'SCT150918RC9',
// 'JCO171102SI9',
// 'IAR010220GK5',
// 'IAB0210236I7',
// 'HRU121221SC2',
// 'GRU210504TH9',
// 'GPR020411182',


 'GME210504KW1',/// sin certificado clave ->64 GPR020411182
// 'CDI1801116Y9',
'COB191129AZ2',/////MARCO ERROR FIEL CHECAR
];
     $this->empresas=DB::table('clientes')
                ->select('RFC')
                ->where('RFC','PERE9308105X4')
                //   ->whereNull('tipo','TipoSE')
                //  ->whereNotIn('Id_Cliente', $ignore)
                //  ->whereNotIn('RFC', $rfcIgnore)
                 ->orderBy('RFC','asc')
                ->get();


    }







  //Consultas del SAT (Emitidos o recibidos)
  public function ConsultSAT($valor)
  {




////=================[ FECHA ]============================/////

$fecha=date('Y-m-d');//obtiene fecha actual

//resto los dias pasados por la ruta
$diaX= date("Y-m-d",strtotime($fecha."-". $valor ."days"));
$date= strtotime($diaX);//obtener la fecha para sacar el mes
$mes = date('m',$date);// obtener el mes como entero
$anio= date('Y',$date);// obtener el año como entero

///---------->[FECHAS ]<---------------------///


$firstDate  = new DateTime($diaX);
$secondDate = new DateTime($diaX);
$intvl = $firstDate->diff($secondDate);
$meses= intval($intvl->m)+1;
// echo "hay:&nbsp;".$meses."<br>";

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

// define handler
$handler = new class () implements MaximumRecordsHandler {
    public function handle(DateTimeImmutable $date): void
    {
        echo 'Se encontraron más de 500 CFDI en el segundo: ', $date->format('c'), PHP_EOL;
    }
};




// $Calendario =Calendario::where(['rfc' => 'AFU1809135Y4'])->where('descargas', '20-04-2022')->get();
// echo count($Calendario)."<br>";
// $data = Calendario::where([
//     'rfc' => "AFU1809135Y4",
// ],[
// 'descargas' => '20-04-2022'
//     ])
//     ->get()->first();

// echo "<br>fecha descarga".$data."<br>";

// echo "<br>fecha descarga".$data['descargas.20-04-2022.erroresEmitidos']."<br>";



try{






    foreach($this->rfcs as $rfc){///foreach empresas




        $cliente = DB::table('clientes')
        //  ->select('tipo', 'password', 'Id_Conta','nombre')
          ->where('RFC', $rfc)
          ->first();
    //    echo $cliente['RFC']."<br>".$diaX;
     $rfc=$cliente['RFC'];


          /*Como se va a realizar una peticion a la pagina del SAT vamos a realizar un try catch para verificar que la conexion
      se realizo correctamente*/



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
              $satScraper = new SatScraper(FielSessionManager::create($credential), $gateway, $handler);
              $rutas =
              [
                  'Emitidos',
                  'Recibidos'
              ];
              foreach ($rutas as $r) {
                $totaldesc=0;

                $cfdierror=0;


###############################-->[ SECCION DE CFDI´S EMITIDOS]<--####################################
              //Vamos a realizar una consulta

                  /// -->[EMITIDOS]<---/////
                  $query = new QueryByFilters(
                    new DateTimeImmutable('2022-04-16'),
                      new DateTimeImmutable('2022-05-09'),

                  );

              //Retornamos el valor de la consulta
              if ($r == 'Emitidos') {
              $list = $satScraper->listByPeriod($query);
              echo "Emitidos:". count($list);



              }else{

            $query->setDownloadType(DownloadType::recibidos());
                // ///  se asigna nuevo valor de recibidos al query
             $list = $satScraper->listByPeriod($query);
             echo "Recibidos:". count($list);



              }

        //    $satScraper->listByPeriod($query);

// //Aqui llamamos a la funcion de meses
$mesruta = Meses(05);

//======================[RUTAS DESCARGAS EMITIDOS]================================///
//XML
$rutaxml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/$r/XML/";
//PDF
$rutapdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/$r/PDF/";
//ACUSE
$rutaacuse = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/$r/ACUSES/";

// descarga de cada uno de los CFDI, reporta los descargados en $downloadedUuids


// impresión de cada uno de los metadata
echo "#####################[ ". $diaX ."&nbsp;&nbsp;CFDI´S $r POR LA EMPRESA ".$cliente['nombre'] ."&nbsp;{".$rfc."}]#######################<br>";
foreach ($list as $cfdi) {

    echo 'UUID: ',strtoupper($cfdi->uuid()), PHP_EOL.'<br>';
    echo 'Emisor: ', $cfdi->get('rfcEmisor'), ' - ', $cfdi->get('nombreEmisor'), PHP_EOL.'<br>';
    echo 'Receptor: ', $cfdi->get('rfcReceptor'), ' - ', $cfdi->get('nombreReceptor'), PHP_EOL.'<br>';
    echo 'Fecha: ', $cfdi->get('fechaEmision'), PHP_EOL.'<br>';
    echo 'Tipo: ', $cfdi->get('efectoComprobante'), PHP_EOL.'<br>';
    echo 'Estado: ', $cfdi->get('estadoComprobante'), PHP_EOL.'<br>';

    echo "===================================================================================================<br>";
$allcfdi[]=strtoupper($cfdi->uuid());
    //++++++++++++++++++++++++++++{{SECCION DE ENCARPETAMIENTO POR MES}}+++++++++++++++++++++++++++++++//

//     $date1= strtotime($cfdi->get('fechaEmision'));//obtener la fecha para sacar el mes
//     echo "aqui". $mes1 = date('m',$date1)."<br>";// obtener el mes como entero
//    echo $anio1= date('Y',$date1) ."<br>";// obtener el año como entero


//  //Realizamos una consulta del CFDI que vamos a guardar
//  $foliofiscal = [$cfdi->uuid()];
//  $cfdiemitxml = $satScraper->listByUuids($foliofiscal, DownloadType::emitidos());

// //Aqui llamamos a la funcion de meses
// $mesruta = Meses($mes1);

//   //======================[RUTAS DESCARGAS EMITIDOS]================================///
// //XML
// $rutaxml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/Emitidos/XML/";
// //PDF
// $rutapdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/Emitidos/PDF/";
// //ACUSE
// $rutaacuse = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/Emitidos/ACUSES/";

//   //Realizamos la descarga
//   //PDF
//   $satScraper->resourceDownloader(ResourceType::pdf(), $cfdiemitxml)
//       ->setResourceFileNamer(new FileNamePDF())
//       ->saveTo($rutapdf, true, 0777);

//       /////=========================[DESCARGA Y ALAMCENA LOS XML ]=======================================////
// $downloadedUuids = $satScraper->resourceDownloader(ResourceType::xml(), $cfdiemitxml)
// ->setConcurrency(50)                            // cambiar a 50 descargas simultáneas
// ->saveTo($rutaxml, true, 0777);                 // ejecutar la instrucción de descarga
// // echo json_encode($downloadedUuids);
// /////=========================[DESCARGA Y ALAMCENA LOS PDF ]=======================================////
// //PDF
// $satScraper->resourceDownloader(ResourceType::pdf(), $cfdiemitxml)
// ->setResourceFileNamer(new FileNamePDF())
// ->saveTo($rutapdf, true, 0777);

// /////=========================[DESCARGA Y ALAMCENA LOS ACUSE ]=======================================////
// //PDF Acuse
// $satScraper->resourceDownloader(ResourceType::cancelVoucher(), $cfdiemitxml)
// ->setResourceFileNamer(new FileNamePDF())
// ->saveTo($rutaacuse, true, 0777);

//++++++++++++++++++++++++++++{{ FIN SECCION DE ENCARPETAMIENTO POR MES}}+++++++++++++++++++++++++++++++//


}




/////=========================[DESCARGA Y ALAMCENA LOS XML ]=======================================////


$downloadedUuids = $satScraper->resourceDownloader(ResourceType::xml(), $list)
     ->setResourceFileNamer(new FileNameXML())                          // cambiar a 50 descargas simultáneas
    ->saveTo($rutaxml, true, 0777);

// ejecutar la instrucción de descarga
// echo json_encode($downloadedUuids);
// /////=========================[DESCARGA Y ALAMCENA LOS PDF ]=======================================////
// //PDF
$satScraper->resourceDownloader(ResourceType::pdf(), $list)
->setResourceFileNamer(new FileNamePDF())
->saveTo($rutapdf, true, 0777);

// /////=========================[DESCARGA Y ALAMCENA LOS ACUSE ]=======================================////
// //PDF Acuse
$satScraper->resourceDownloader(ResourceType::cancelVoucher(), $list)
->setResourceFileNamer(new FileNamePDFAcuse())
->saveTo($rutaacuse, true, 0777);


//// ================================ [GUARDA LOS METADATOS EN LA BASE] ============================////

//Almacenamos los metadatos


  //En varaibles guardamos los valores necesarios para la inserción y en el mismo ciclo agrgamos los metadatos
  foreach ($list as $datapdfreci) {
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
    if ($r == 'Emitidos') { $metadatarecipdf =MetadataE::where(['folioFiscal' => $folifiscal]);
    }else{$metadatarecipdf =MetadataR::where(['folioFiscal' => $folifiscal]); }

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


//Con un bucle pasamos por los uuids almacenados en el arreglo

if(isset($allcfdi)){
foreach ($allcfdi as $listuuids) {
    //Buscamos si exsiten los archivos (si estn descargados)
    //XML/PDF/Acuse
    $xmlfile = $rutaxml . strtoupper($listuuids) . '.xml';
    $pdffile = $rutapdf . strtoupper($listuuids) . '.pdf';
    $acusefile = $rutaacuse . strtoupper($listuuids) . '-acuse' . '.pdf';

    if (file_exists($xmlfile) || file_exists($pdffile) || file_exists($acusefile)) {
        $totaldesc++;
    } else {
        $cfdierror++;
    }
}

}//fin de isset

  //En una condicional comparamos si el total de descargados es el total de la descarga
  if ($totaldesc == count($list)) {
    $cfdidesc = count($list); //Agregamos el total descargado
    $cfdirecibi = count($list) - $cfdierror; //Agregamos el total recibido

} else {
    //De lo contrario sacamos los errores
    $cfdidesc = count($list); //Agregamos el total descargado
    $cfdirecibi = count($list) - $cfdierror; //Agregamos el total recibido
}


 //Almacenamos los XML recibidos y emitidos convirtiendo el xml en un  json
 $ruta = $rutaxml;
 $dir = new DirectoryIterator($ruta);
 echo " $ruta <br><br>";
 foreach ($dir as $fileinfo) {
     $fileName = $fileinfo->getFilename();
     $fileExt = $fileinfo->getExtension();
     $fileBaseName = $fileinfo->getBasename(".$fileExt");
     $filePathname = $fileinfo->getPathname();
     echo $filePathname;
     echo "<br>";
     if (!$fileinfo->isDot()) {


         $contents = file_get_contents($filePathname);
         $cleaner = Cleaner::staticClean($contents);

         $script = new Scriptp;
         $cfdi = $script->cfdi_to_json($cleaner);
         // $json = JsonConverter::convertToJson($cfdi);
         $array = json_decode($cfdi, true);

         //Agregamos los datos del arreglo a la coleccion de XML recibidos
         if ($r == 'Emitidos') {
         XmlE::where(['UUID' => $fileBaseName])
             ->update(
                 $array,
                 ['upsert' => true]
             );
        /// convertimos en mayusculas los UUID
        $actualizar= XmlE::where(['UUID' => $fileBaseName]);
            $actualizar->update([
                'UUID' => strtoupper($fileBaseName),
            ]);


    //Agregar a la base de datos
    $busca = Calendario::where(['rfc' => $rfc]);
    $busca->update(
        [
            'rfc' => $rfc,
            'descargas.' . $diaX . '.fechaDescargas' => $diaX,
            'descargas.' . $diaX . '.descargasEmitidos' => count($list),
            'descargas.' . $diaX . '.erroresEmitidos' =>  $cfdierror,
            'descargas.' . $diaX . '.totalEmitidos' =>  $cfdirecibi,
        ],
        ['upsert' => true]
    );




        }else{
            XmlR::where(['UUID' => $fileBaseName])
            ->update(
                $array,
                ['upsert' => true]
            );
       /// convertimos en mayusculas los UUID
       $actualizar= XmlR::where(['UUID' => $fileBaseName]);
           $actualizar->update([
               'UUID' => strtoupper($fileBaseName),
           ]);


               //Agregar a la base de datos
        $busca = Calendario::where(['rfc' => $rfc]);
        $busca->update(
            [
                'rfc' => $rfc,
                'descargas.' . $diaX . '.fechaDescargas' => $diaX,
                'descargas.' . $diaX . '.descargasRecibidos' => count($list),
                'descargas.' . $diaX. '.erroresRecibidos' => $cfdierror,
                'descargas.' .$diaX. '.totalRecibidos' =>$cfdirecibi,
            ],
            ['upsert' => true]
        );

        }










     }

    }



    $allcfdi=[];




//// ================================ [ FIN- GUARDA LOS METADATOS EN LA BASE] ============================////



###############################-->[ SECCION DE CFDI´S RECIBIDOS]<--####################################

//   /// -->[RECIBIDOS]<---/////
//   $query = new QueryByFilters(
//     new DateTimeImmutable('2022-02-01'),
//     new DateTimeImmutable('2022-02-14'),
// );
// $query->setDownloadType(DownloadType::recibidos());
// ///  se asigna nuevo valor de recibidos al query
// $list = $satScraper->listByPeriod($query);

// // impresión de cada uno de los metadata
// echo "===============[  CFDI´S RECIBIDOS  ".$cliente['nombre'] ."]================<br>";
// foreach ($list as $cfdi) {
//     echo 'UUID: ', $cfdi->uuid(), PHP_EOL.'<br>';
//     echo 'Emisor: ', $cfdi->get('rfcEmisor'), ' - ', $cfdi->get('nombreEmisor'), PHP_EOL.'<br>';
//     echo 'Receptor: ', $cfdi->get('rfcReceptor'), ' - ', $cfdi->get('nombreReceptor'), PHP_EOL.'<br>';
//     echo 'Fecha: ', $cfdi->get('fechaEmision'), PHP_EOL.'<br>';
//     echo 'Tipo: ', $cfdi->get('efectoComprobante'), PHP_EOL.'<br>';
//     echo 'Estado: ', $cfdi->get('estadoComprobante'), PHP_EOL.'<br>';


//     $date1= strtotime($cfdi->get('fechaEmision'));//obtener la fecha para sacar el mes
//  echo "aqui". $mes1 = date('m',$date1)."<br>";// obtener el mes como entero
// echo $anio1= date('Y',$date1) ."<br>";// obtener el año como entero
// echo "objeto:". var_dump($cfdi)."<br>";




// }


// //======================[RUTAS DESCARGAS EMITIDOS]================================///
// //XML
// $rutaxml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/Recibidos/XML/";
// //PDF
// $rutapdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/Recibidos/PDF/";
// //ACUSE
// $rutaacuse = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mesruta/Recibidos/ACUSES/";

// // descarga de cada uno de los CFDI, reporta los descargados en $downloadedUuids

// /////=========================[DESCARGA Y ALAMCENA LOS XML ]=======================================////
// $downloadedUuids = $satScraper->resourceDownloader(ResourceType::xml(), $list)
//     ->setConcurrency(50)                            // cambiar a 50 descargas simultáneas
//     ->saveTo($rutaxml, true, 0777);                 // ejecutar la instrucción de descarga
// // echo json_encode($downloadedUuids);
// /////=========================[DESCARGA Y ALAMCENA LOS PDF ]=======================================////
// //PDF
// $satScraper->resourceDownloader(ResourceType::pdf(), $list)
// ->setResourceFileNamer(new FileNamePDF())
// ->saveTo($rutapdf, true, 0777);

// /////=========================[DESCARGA Y ALAMCENA LOS ACUSE ]=======================================////
// //PDF Acuse
// $satScraper->resourceDownloader(ResourceType::cancelVoucher(), $list)
// ->setResourceFileNamer(new FileNamePDF())
// ->saveTo($rutaacuse, true, 0777);



              }// fin del foreach emitidos y recibidos

     }/// fin del foeach clientes


    } catch (Exception $e) {
        //Retornamos un mensaje de error
        return "error: " . $e;
    }


//      $files = glob('build/cookies/*'); //obtenemos todos los nombres de los ficheros
//      foreach($files as $file){
//     if(is_file($file)){



//             if(file_exists($file)){
//                   unlink($file);
//                }
//                   try{


//                       $bug = 0;
//                   }
//                   catch(\Exception $e){
//                       $bug = $e->errorInfo[1];
//                   }
//                   if($bug==0){
//                     //   echo "success";
//                   }else{
//                     //   echo 'error';
//                   }




//     }
// }





}








}// fin de la clase DescargasAutomaticas


