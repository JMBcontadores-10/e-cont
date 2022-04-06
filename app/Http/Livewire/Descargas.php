<?php

namespace App\Http\Livewire;

use App\Http\Classes\DescargaMasivaCfdi;
use App\Http\Classes\UtilCertificado;
use App\Models\User;
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

    //Variables para la seccion del calendario
    public $mes;
    public $anio;
    public $dias;

    //Metodo para redirigirnos a la fecha actual
    public function fechaactual(){
        //El mes y año iniciamos con los de hoy
        $this->anio = date("Y");
        $this->mes = date("m");
    }

    //Metodo para obtener los datos de las empresas para la auntenticacion
    public function ObtAuth(){
        if(empty($this->rfcEmpresa)){
            $this->dircer = "";
            $this->dirkey = "";
            $this->pwd = "";
            $this->rfcemp = "";
        }else{
            //Obtenemos los valores para la auntenticacion
            $AuntDesca = User::
            where('RFC', $this->rfcEmpresa)
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
    public function AuthEmpre(){
        //Instanciar clase principal
        $descargaCfdi = new DescargaMasivaCfdi();

        //Condicional para saber si los datos que se proporcionara son correctos
        if (!empty($this->dircer) && !empty($this->dirkey) && !empty($this->pwd)) {
            //Preparamos el certificado del inicio de sesion
            $certificado = new UtilCertificado();
            $acceso = $certificado->loadFiles(
                $this->dircer,
                $this->dirkey,
                $this->pwd
            );

            //Switch para el inicio de sesion
            switch ($acceso) {
                //Si los certificados son correctos
                case true:
                     //Iniciar sesion en el SAT
                    $acceso = $descargaCfdi->iniciarSesionFiel($certificado);
                    if ($acceso) {
                        $this->mnsinic = "Se ha iniciado la sesión";
                        $this->statemns = 1;
                    } else {
                        $this->mnsinic = "Ha ocurrido un error al iniciar sesión. Intente nuevamente";
                        $this->statemns = 0;
                    }
                    break;
                //Si hay algun error en los certificados
                case false:
                    $this->mnsinic = "Verifique que los archivos corresponden con la contraseña e intente nuevamente";
                    $this->statemns = 0;
                    break;
                }
        } 
        //Si los datos proporcionado son incorrectos
        else {
            $this->mnsinic = "Proporcione todos los datos";
            $this->statemns = 0;
        }

        //Vamos a emitir un mensaje de session (dependiendo del mensaje)
        $this->dispatchBrowserEvent('mnssesion', ['mns' => $this->mnsinic, 'state' => $this->statemns]);
    }

    //Metodo para preparar procesos antes de iniciar
    public function mount()
    {
        //El mes y año iniciamos con los de hoy
        $this->anio = date("Y");
        $this->mes = date("m");

        //Condicional para saber si es una cuenta de contador o empresa
        if(auth()->user()->tipo){
            $this->rfcEmpresa='';
        }else{
            $this->rfcEmpresa=auth()->user()->RFC;
        }

        //Condicional para saber si el Rfc tiene algo y realiza el almacenado a las variables necesarias
        if(empty($this->rfcEmpresa)){
            $this->dircer = "";
            $this->dirkey = "";
            $this->pwd = "";
            $this->rfcemp = "";
        }else{
            //Obtenemos los valores para la auntenticacion
            $AuntDesca = User::
            where('RFC', $this->rfcEmpresa)
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
        if(!empty(auth()->user()->tipo)){
            $e=array();
            $largo=sizeof(auth()->user()->empresas);
            for($i=0; $i <$largo; $i++) {
                $rfc=auth()->user()->empresas[$i];

                $e=DB::Table('clientes')
                ->select('RFC','nombre')
                ->where('RFC', $rfc)
                ->get();

                foreach($e as $em)
                $emp[]= array($em['RFC'],$em['nombre']);
            }
        }else if(!empty(auth()->user()->TipoSE)){
            $e=array();
            $largo=sizeof(auth()->user()->empresas);
            for($i=0; $i <$largo; $i++) {
                $rfc=auth()->user()->empresas[$i];

                $e=DB::Table('clientes')
                ->select('RFC','nombre')
                ->where('RFC', $rfc)
                ->get();

                foreach($e as $em)
                $emp[]= array($em['RFC'],$em['nombre']);
            }
        }
        else{
            $emp='';
        }

        return view('livewire.descargas', ['empresa'=>$this->rfcEmpresa, 'empresas'=>$emp, 'meses'=>$meses,'anios'=>$anios])
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
