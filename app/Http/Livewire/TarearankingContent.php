<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tareas;
use App\Models\User;

class TarearankingContent extends Component
{
    //Variables
    public $mestareaadmin;
    public $aniotareaadmin;

    public function render()
    {
        //Obtenemos las fechas de inicio y fin
        //Establecemos las dos fechas de inicio y fin
        $Inicio = "Monday";
        $Fin = "Sunday";

        //Obtenemos la fecha de hoy
        $Hoy = strtotime(date("Y-m-d"));

        $SemanaIncio = date('Y-m-d', strtotime('last ' . $Inicio, $Hoy)); //Obtenemos el dia inicial de la semana
        $SemanaFin = date('Y-m-d', strtotime('next ' . $Fin, $Hoy)); //Obtenemos el ultimo dia de la semana

        //Condicional para saber si la fecha de hoy pertenece a la fecha de inicio y la fecha final
        if (date("l", $Hoy) == $Inicio) {
            $SemanaIncio = date("Y-m-d", $Hoy);
        }
        if (date("l", $Hoy) == $Fin) {
            $SemanaFin = date("Y-m-d", $Hoy);
        }

        //Arreglo para obtener los colaboradores y porcentaje
        $ranking = [];

        //Consultamos a la colleccion de clientes para obtener los colaboradores
        $colaboradores = User::where('tipo', '2')
            ->orwhere('tipo', 'VOLU')
            ->whereNull('admin')
            ->where('nombre', '!=', null)
            ->get();

        //Consultamos las tareas para mostrar por colaborador
        $tareas = Tareas::whereBetween('asigntarea', [$SemanaIncio, $SemanaFin])->get();

        //Obtenemos los porcentaje de cada colaborador
        foreach ($colaboradores as $contador) {
            //Limpiamos el arreglo de proyecto al cambiar de contador
            $proyectos = [];

            //Total de tareas creadas
            $totaltareas = 0;

            //Total de tareas completadas
            $totalcompletados = 0;

            foreach ($tareas as $tarea) {
                if ($tarea['rfccolaborador'] == $contador['RFC']) {
                    //Almacenamos las empresas
                    $proyectos[] = $tarea['_id'];
                }
            }

            //Contamos los repetidos
            $infotareas = array_count_values($proyectos);

            foreach ($infotareas as $key => $value) {
                //Total de tareas completadas por colaborador
                $completado = 0;

                //Ciclo para pasar por todas la tareas para saber cuales tareas esta completadas
                foreach ($tareas as $tarea) {
                    //Vamos a utiizar el _id y la tareas completadas para saber si esta completadas
                    if ($tarea['completado'] == '1' && $tarea['_id'] == $key) {
                        $completado++;
                    }
                }

                //Realzamos la suma de los totales
                $totalcompletados += $completado;

                //Obtenemos el total de tareas
                $totaltareas += $value;

                //Total de tareas completadas
                $completado = 0;
            }

            //Obtenenmos el porcentaje de los totales de cada colaborador
            if (!empty($totalcompletados) || !empty($totaltareas)) {
                //Obtenemos el porcentaje
                $totalporcentaje = ($totalcompletados * 100) / $totaltareas;
            } else {
                $totalporcentaje = 0;
            }

            //Metemos el RFC del colaborador junto con su porcentaje 
            $ranking[] = ['RFC' => $contador['RFC'], 'Nombre' => $contador['nombre'], 'Porcentaje' => $totalporcentaje];
        }

        //Bucle para obtener el porcentaje del colaborador que inicio sesion
        foreach ($ranking as $ranker) {
            if (!empty(auth()->user()->admin)) { //Condicional para obtener el porcentaje con respecto al RFC
                $porcent = 0;
            }

            if ($ranker['RFC'] == auth()->user()->RFC) { //Condicional para obtener el porcentaje con respecto al RFC
                $porcent = $ranker['Porcentaje'];
            } else {
                $porcent = 0;
            }
        }

        //Ordenamos el areglo de manera descendente para saber si esta de mayor a menor
        array_multisort(array_column($ranking, 'Porcentaje'), SORT_DESC, $ranking);

        //Obtenemos la posicion en donde se encuentra
        $posrank = array_search(auth()->user()->RFC, array_column($ranking, 'RFC'));

        return view('livewire.tarearanking-content', ['porcent' => $porcent, 'posrank' => $posrank + 1, 'ranking' => $ranking]);
    }
}
