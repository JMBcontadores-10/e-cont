<?php declare(strict_types=1);

namespace App\Http\Livewire;

use Livewire\Component;
use PhpCfdi\CfdiSatScraper\QueryByFilters;

use PhpCfdi\CfdiSatScraper\SatScraper;

use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;

use PhpCfdi\Credentials\Credential;

use PhpCfdi\CfdiSatScraper\Contracts\MaximumRecordsHandler;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use PhpCfdi\CfdiSatScraper\SatHttpGateway;

use PhpCfdi\CfdiSatScraper\Filters\DownloadType;
use DateTimeImmutable;

use Exception;

use Illuminate\Support\Facades\DB;

class Auditoria extends Component
{

    public $fecha_ini, $fecha_fin;
    public $metadata2;
    public $tipoer, $rc, $rfcEmpresa;
    public $active;
    public $contador=0;


    public function mount()
    {
              $this->active="hidden";

        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {

            $this->rfcEmpresa = auth()->user()->RFC;
        }

        /////////////////metadatos

        $this->metadata = null;
        $this->metadata2 = [];
    }


    public function consultar()
    {

          if($this->fecha_ini !=Null){


        try {

            $cliente = DB::table('clientes')
                //  ->select('tipo', 'password', 'Id_Conta','nombre')
                ->where('RFC', $this->rfcEmpresa)
                ->first();


            //Condicional para comprobar si hay una empresa (Si es contador)




            // define handler
            $handler = new class() implements MaximumRecordsHandler
            {
                public function handle(DateTimeImmutable $date): void
                {
                    //     echo 'Se encontraron más de 500 CFDI en el segundo: ', $date->format('c'), PHP_EOL;
                    //
                }
            };


            //Variables de cookies de sesion para no volver a realizar el inicio de sesion
            $cookieJarPath = sprintf('%s\build\cookies\%s.json', getcwd(), $cliente['RFC']);
            //Se almacena ña cookie en un gateway para mandarlo al cliente y este realizar las consultas
            $gateway = new SatHttpGateway(new Client(), new FileCookieJar($cookieJarPath, true));

            //Obtiene las variables para crear el crtificado
            $certificate = 'storage/' . $cliente['dircer'];
            $privateKey = 'storage/' . $cliente['dirkey'];
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


            ###############################-->[ SECCION DE CFDI´S EMITIDOS]<--####################################
            //Vamos a realizar una consulta

            if($this->tipoer =="Emitidas"){
            /// -->[EMITIDOS]<---/////
            $query = new QueryByFilters(
                new DateTimeImmutable($this->fecha_ini),
                new DateTimeImmutable($this->fecha_fin),

            );
        }else{

            $query = new QueryByFilters(
                new DateTimeImmutable($this->fecha_ini),
                new DateTimeImmutable($this->fecha_fin),
            );

            $query->setDownloadType(DownloadType::recibidos());
        }


            $this->active=NUll;
            //Retornamos el valor de la consulta
            return $satScraper->listByPeriod($query);
        } catch (Exception $e) {
            //Retornamos un mensaje de error
            return "Parece que hubo un error: " . $e;
        }


    }else{

        return Null;
    }


    }








    public function render()
    {


        if(!empty(auth()->user()->tipo)){

            $e=array();
                  $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas


                  for($i=0; $i <$largo; $i++) {

                  $rfc=auth()->user()->empresas[$i];
                   $e=DB::Table('clientes')
                   ->select('RFC','nombre')

                   ->where('RFC', $rfc)

                   ->get();

                   foreach($e as $em){


                   $emp[]= array( $em['RFC'],$em['nombre']);
                   }
                  }

                }elseif(!empty(auth()->user()->TipoSE)){

                    $e=array();
                          $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas


                          for($i=0; $i <$largo; $i++) {

                          $rfc=auth()->user()->empresas[$i];
                           $e=DB::Table('clientes')
                           ->select('RFC','nombre')

                           ->where('RFC', $rfc)

                           ->get();

                           foreach($e as $em)


                           $emp[]= array( $em['RFC'],$em['nombre']);
                          }
                          }else{

            $emp='';


                }//end if




        return view('livewire.auditoria',['list'=>$this->consultar(),
        'contador'=>$this->contador,
        'active'=>$this->active,
        'empresas'=>$emp,
        'empresa'=>$this->rfcEmpresa

        ])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
