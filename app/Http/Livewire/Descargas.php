<?php

namespace App\Http\Livewire;

//Clases para acceder a la sesion del SAT
use PhpCfdi\CfdiSatScraper\SatScraper;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionManager;
use PhpCfdi\CfdiSatScraper\Sessions\Fiel\FielSessionData;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\CfdiSatScraper\QueryByFilters;
use PhpCfdi\CfdiSatScraper\ResourceType;
use PhpCfdi\CfdiSatScraper\Sessions\Ciec\CiecSessionManager;

use App\Models\CalendarioR;
use App\Models\CalendarioE;
use App\Models\User;
use DateTimeImmutable;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

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


    //Metrodo para reiniciar el modal
    public function RefreshCal()
    {
        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("n");
    }

    //Metodo para obtener los datos de las empresas para la auntenticacion
    public function ObtAuth()
    {
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

    //Metodo para la autenticacion
    public function AuthEmpre()
    {
        //Consultar emitidos y reciobidos (INICIAL)
            //Variables para el certificado
            $certificate = 'storage/' . $this->dircer;
            $privateKey = 'storage/' . $this->dirkey;
            $passPhrase = $this->pwd;

            //Creamos al credenciales de acceso
            // crear la credencial
            $credential = Credential::create(file_get_contents($certificate), file_get_contents($privateKey), $passPhrase);
            if (!$credential->isFiel()) {
                throw new Exception('The certificate and private key is not a FIEL');
            }
            if (!$credential->certificate()->validOn()) {
                throw new Exception('The certificate and private key is not valid at this moment');
            }

            $satScraper = new SatScraper(FielSessionManager::create($credential));

            //Si es valido
            $this->statemns = 1;

        //Try catch para saber si se realizo el servicio correctamente
        try {
            
        } catch (Exception $e) {
            //Si no es valido
            $this->mnsinic = "Verifique que los archivos corresponden con la contraseña e intente nuevamente";
            $this->statemns = 0;
        }

        //Vamos a emitir un mensaje de session (dependiendo del mensaje)
        $this->dispatchBrowserEvent('mnssesion', ['mns' => $this->mnsinic, 'state' => $this->statemns]);
    }

    //Metodo para preparar procesos antes de iniciar
    public function mount()
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

        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("n");

        //Condicional para saber si es una cuenta de contador o empresa
        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {
            $this->rfcEmpresa = auth()->user()->RFC;
        }

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

            //Condicional para saber si el dia creado pertenece al dia de hoy
            if ($today == $date) {
                //Datos recibidos
                foreach ($LogRecical as $fechareci) {
                    if ($fechareci->fechaDescarga == $date) {
                        $week .= '<td class="hoy recibi">' . $day .
                            "<br><br>" . '<b>' . "Recibidos" . '</b>' . '<br>' .
                            "Cancelados: " . $fechareci->canceladosRecibidos . '<br>' .
                            "Descargados: " . $fechareci->descargasRecibidos . '<br>' .
                            "Errores: " . $fechareci->erroresRecibidos . '<br>' .
                            "Total: " . $fechareci->totalRecibidos;

                        $this->reciboemit = 1;
                        $this->recibido = 1;
                    }
                }

                //Datos emitidos
                foreach ($LogEmitcal as $fechaemit) {
                    if ($fechaemit->fechaDescarga == $date) {
                        if ($this->recibido == 1) {
                            $week .= "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                                "Descargados: " . $fechaemit->descargasEmitidos . '<br>' .
                                "Errores: " . $fechaemit->erroresEmitidos . '<br>' .
                                "Total: " . $fechaemit->totalEmitidos;

                            $this->recibido = 0;
                        } else {
                            $week .= '<td class="hoy recibi">' . $day .
                                "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                                "Descargados: " . $fechaemit->descargasEmitidos . '<br>' .
                                "Errores: " . $fechaemit->erroresEmitidos . '<br>' .
                                "Total: " . $fechaemit->totalEmitidos;
                        }

                        $this->reciboemit = 1;
                    }
                }

                //Condicional para saber si hay emitidos o recibidos
                if ($this->reciboemit == 0) {
                    $week .= '<td class="hoy">' . $day;
                }
            }

            //Otro dia
            else {
                foreach ($LogRecical as $fecha) {
                    if ($fecha->fechaDescarga == $date) {
                        $week .= '<td class="recibi">' . $day .
                            "<br><br>" . '<b>' . "Recibidos" . '</b>' . '<br>' .
                            "Cancelados: " . $fecha->canceladosRecibidos . '<br>' .
                            "Descargados: " . $fecha->descargasRecibidos . '<br>' .
                            "Errores: " . $fecha->erroresRecibidos . '<br>' .
                            "Total: " . $fecha->totalRecibidos;

                        $this->recibido = 1;
                        $this->reciboemit = 1;
                    }
                }

                //Datos emitidos
                foreach ($LogEmitcal as $fechaemit) {
                    if ($fechaemit->fechaDescarga == $date) {
                        if ($this->recibido == 1) {
                            $week .= "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                                "Descargados: " . $fechaemit->descargasEmitidos . '<br>' .
                                "Errores: " . $fechaemit->erroresEmitidos . '<br>' .
                                "Total: " . $fechaemit->totalEmitidos;

                            $this->recibido = 0;
                        } else {
                            $week .= '<td class="recibi">' . $day .
                                "<br><br>" . '<b>' . "Emitidos" . '</b>' . '<br>' .
                                "Descargados: " . $fechaemit->descargasEmitidos . '<br>' .
                                "Errores: " . $fechaemit->erroresEmitidos . '<br>' .
                                "Total: " . $fechaemit->totalEmitidos;
                        }

                        $this->reciboemit = 1;
                    }
                }

                //Condicional para saber si hay emitidos o recibidos
                if ($this->reciboemit == 0) {
                    $week .= '<td>' . $day;
                }
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

        return view('livewire.descargas', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'meses' => $meses, 'anios' => $anios, 'week' => $week, 'weeks' => $weeks])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
