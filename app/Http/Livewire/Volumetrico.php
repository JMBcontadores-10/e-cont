<?php

namespace App\Http\Livewire;

use App\Models\Volumetrico as VolumetricoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Volumetrico extends Component
{
    //Variables globales
    public $rfcEmpresa;

    //Variables para la seccion del calendario
    public $mescal;
    public $aniocal;

    //Escuchar los emitidos de otros componentes
    public $listeners = ['volumrefresh' => '$refresh'];

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

        //Vamos a hacer una consulta a los volumetricos para obtener
        $voludata = VolumetricoModel::where('rfc', $this->rfcEmpresa)
            ->get()
            ->first();

        //Ciclo for para llenar los campos con los dias que le pertenece
        for ($day = 01; $day <= $day_count; $day++, $str++) {
            //Formamos la fecha completa
            if ($day < 10) {
                $date = $ym . '-0' . $day;
            } else {
                $date = $ym . '-' . $day;
            }

            //Switch para marcar el dia de hoy
            switch ($date) {
                case $today:
                    $week .= '<td class="hoy" style="color:white">' . $day;

                    if ($this->rfcEmpresa) {
                        //Condicional para limitar la captura
                        if (auth()->user()->tipo) {
                            //Informacion PDF
                            if (!empty($voludata['volumetrico.' . $date . '.PDFVolu'])) {
                                $week .= '<br>' . 'PDF cargado';
                            } else {
                                $week .= '<br>' . 'Falta PDF';
                            }

                            //Informacion de captura
                            if (!empty($voludata['volumetrico.' . $date . '.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '.InvDeterD'])) {
                                $week .= '<br>' . 'Captura completa' . '<br>';
                            } else {
                                $week .= '<br>' . 'Falta capturar' . '<br>';
                            }

                            //Condicional para marcar que se agrego un volumetrico
                            if (!empty($voludata['volumetrico.' . $date . '.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '.InvDeterD'])) {
                                //Boton para insertar o editar datos
                                $week .= '<a class="icons fas fa-edit content_true" data-toggle="modal" 
                            data-target="#volucaptumodal' . $date . '" data-backdrop="static"
                            data-keyboard="false"></a> &nbsp;&nbsp;';
                            } else {
                                //Boton para insertar o editar datos
                                $week .= '<a class="icons fas fa-edit" data-toggle="modal" 
                            data-target="#volucaptumodal' . $date . '" data-backdrop="static"
                            data-keyboard="false"></a> &nbsp;&nbsp;';
                            }
                        } else {
                            //Informacion PDF
                            if (!empty($voludata['volumetrico.' . $date . '.PDFVolu'])) {
                                $week .= '<br>' . 'PDF cargado' . '<br>';
                            } else {
                                $week .= '<br>' . 'Falta PDF' . '<br>';
                            }
                        }

                        //Boton para mostrar el PDF
                        $week .= '<a class="icons fas fa-file-pdf" data-toggle="modal" 
                        data-target="#volupdfmodal' . $date . '" data-backdrop="static"
                        data-keyboard="false"></a>';
                    }
                    break;
                default:
                    $week .= '<td>' . $day;

                    if ($this->rfcEmpresa) {
                        //Condicional para limitar la captura
                        if (auth()->user()->tipo) {
                            //Informacion PDF
                            if (!empty($voludata['volumetrico.' . $date . '.PDFVolu'])) {
                                $week .= '<br>' . 'PDF cargado';
                            } else {
                                $week .= '<br>' . 'Falta PDF';
                            }

                            //Informacion de captura
                            if (!empty($voludata['volumetrico.' . $date . '.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '.InvDeterD'])) {
                                $week .= '<br>' . 'Captura completa' . '<br>';
                            } else {
                                $week .= '<br>' . 'Falta capturar' . '<br>';
                            }

                            //Condicional para marcar que se agrego un volumetrico
                            if (!empty($voludata['volumetrico.' . $date . '.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '.InvDeterD'])) {
                                //Boton para insertar o editar datos
                                $week .= '<a class="icons fas fa-edit content_true" data-toggle="modal" 
                            data-target="#volucaptumodal' . $date . '" data-backdrop="static"
                            data-keyboard="false"></a> &nbsp;&nbsp;';
                            } else {
                                //Boton para insertar o editar datos
                                $week .= '<a class="icons fas fa-edit" data-toggle="modal" 
                            data-target="#volucaptumodal$volupdf =" data-backdrop="static"
                            data-keyboard="false"></a> &nbsp;&nbsp;';
                            }
                        } else {
                            //Informacion PDF
                            if (!empty($voludata['volumetrico.' . $date . '.PDFVolu'])) {
                                $week .= '<br>' . 'PDF cargado' . '<br>';
                            } else {
                                $week .= '<br>' . 'Falta PDF' . '<br>';
                            }
                        }

                        //Boton para mostrar el PDF
                        $week .= '<a class="icons fas fa-file-pdf" data-toggle="modal" 
                        data-target="#volupdfmodal' . $date . '" data-backdrop="static"
                        data-keyboard="false"></a>';
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

    //Metodo para preparar procesos antes de iniciar
    public function mount()
    {
        //Condicional para saber si es una cuenta de contador o empresa
        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {
            $this->rfcEmpresa = auth()->user()->RFC;
        }

        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("m");
    }

    public function render()
    {
        //Obtenemos el valor del metodo del calendario
        $weeks = $this->Calendario();

        //Emitimos el metodo de refrescar la pagina
        $this->emit('Volumdata');

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
                    ->where('gas', "1")
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
                    ->where('gas', "1")
                    ->get();


                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else {
            $emp = '';
        }

        return view('livewire.volumetrico', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'weeks' => $weeks, 'meses' => $meses, 'anios' => $anios])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
