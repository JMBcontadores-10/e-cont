<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;

class Tareacal extends Component
{
    //Variables
    public $contaselect;

    //Variables para la seccion del calendario
    public $mescal;
    public $aniocal;

    //Metodo para crear el calendario
    public function Calendario()
    {
        //Condicional para saber si existe un colaborador seleccionado
        if (!empty($this->contaselect)) {
            //Consultamos las tareas de los colaboradores
            $tareascolabo = Tareas::get();
        }

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

        //Ciclo for para llenar los campos con los dias que le pertenece
        for ($day = 01; $day <= $day_count; $day++, $str++) {
            $date = date('Y-m-d', strtotime($ym . '-' . $day));

            //Switch para marcar el dia de hoy
            switch ($date) {
                case $today:
                    $week .= '<td class="hoy">' . $day . '<br>';

                    //Consulta para saber si existe una tarea
                    if (!empty($tareascolabo)) {
                        foreach ($tareascolabo as $tarea) {
                            //Condicional para saber si tiene un periodo de termino
                            if ($tarea['rfccolaborador'] == $this->contaselect && $tarea['asigntarea'] == $date) {
                                //Condicional para saber si una tarea ya esta completada
                                if (!empty($tarea['completado'])) {
                                    $week .= '<br> <div style="background:#b1ff79; color:#727e8c; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                } else {
                                    $week .= '<br> <div style="background:#f8f8f8; color:#727e8c; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                }
                            } elseif ($tarea['rfccolaborador'] == $this->contaselect && $tarea['fechaentrega'] >= $date && $tarea['asigntarea'] <= $date) {
                                //Condicional para saber si una tarea ya esta completada
                                if (!empty($tarea['completado'])) {
                                    $week .= '<br> <div style="background:#b1ff79; color:#727e8c; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                } else {
                                    $week .= '<br> <div style="background:#f8f8f8; color:#727e8c; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                }
                            }
                        }
                    }
                    break;
                default:
                    $week .= '<td>' . $day . '<br>';

                    //Consulta para saber si existe una tarea
                    if (!empty($tareascolabo)) {
                        foreach ($tareascolabo as $tarea) {
                            //Condicional para saber si tiene un periodo de termino
                            if ($tarea['rfccolaborador'] == $this->contaselect && $tarea['asigntarea'] == $date) {
                                //Condicional para saber si una tarea ya esta completada
                                if (!empty($tarea['completado'])) {
                                    $week .= '<br> <div style="background:#b1ff79; color:#727e8c; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                } else {
                                    $week .= '<br> <div style="background:#397ac4; color:#ffffff; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                }
                            } elseif ($tarea['rfccolaborador'] == $this->contaselect && $tarea['fechaentrega'] >= $date && $tarea['asigntarea'] <= $date) {
                                //Condicional para saber si una tarea ya esta completada
                                if (!empty($tarea['completado'])) {
                                    $week .= '<br> <div style="background:#b1ff79; color:#727e8c; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                } else {
                                    $week .= '<br> <div style="background:#397ac4; color:#ffffff; border-radius:5px; padding:5px;">' . $tarea['nombre'] . ' - ' . Str::limit($tarea['nomproyecto'], 15) . '</div>';
                                }
                            }
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

    //Metodo para refrescar el componente
    public function Refresh()
    {
        //Quitamos el colaborador para refrescar el modulo
        $this->contaselect = '';

        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("m");
    }

    public function mount()
    {
        //Condicional para saber si es una cuenta de contador o empresa
        if (auth()->user()->admin) {
            $this->contaselect = '';
        } else {
            $this->contaselect = auth()->user()->RFC;
        }

        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("m");
    }

    public function render()
    {
        //Condicional para saber si es una cuenta de contador o empresa
        if (empty(auth()->user()->admin)) {
            $this->contaselect = auth()->user()->RFC;
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


        //Condicional para saber si inicio sesion un colaborador o administrador 
        if (!empty(auth()->user()->admin)) {
            //Administrador
            //Consultamos los colaboradores
            $consulconta = User::where('tipo', '2')
                ->orwhere('tipo', 'VOLU')
                ->where('nombre', '!=', null)
                ->get(['RFC', 'nombre']);
        } else {
            //Mandamos las varible vacia
            $consulconta = "";
        }

        return view('livewire.tareacal', ['weeks' => $this->Calendario(), 'consulconta' => $consulconta, 'meses' => $meses, 'anios' => $anios]);
    }
}
