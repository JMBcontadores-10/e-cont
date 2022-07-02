<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Livewire\Component;

class Tareacolab extends Component
{
    //Variables
    public $rfccolab;

    //Metodo para marcar como completado una tarea
    public function Completado($id)
    {
        //Establecemos la zona horaria
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);

        //Marcamos como completado la tarea
        Tareas::where('_id', $id)
            ->update([
                'completado' => '1',
                'estado' => '2',
                'finalizo' => $dt->format('Y-m-d H:i:s'),
            ], ['upsert' => true]);
    }

    //Metodo para cancelar la tarea
    public function Cancelar($id)
    {
        //Eliminamos la tarea
        Tareas::where('_id', $id)
            ->delete();
    }

    public function mount()
    {
        //Obtenemos la lista de tareas de cada colaborador
        if (!empty(auth()->user()->admin)) {
            //Obtenemos el RFC del colaborador que inicio sesion
            $this->rfccolab = "";
        } else {
            $this->rfccolab = auth()->user()->RFC;
        }
    }

    public function render()
    {
        //Obtenemos las tareas asignadas
        $listatareas = Tareas::get();

        //Arreglo para alamacenar la lista de proyectos
        $listaproyectos = [];

        //Obtenemos los proyectos
        foreach ($listatareas as $tareas) {
            if ($tareas['rfccolaborador'] == $this->rfccolab) {
                //Alamcenamos el RFC de los proectos
                $listaproyectos[] = ['RFC' => $tareas['rfcproyecto'], 'Nombre' => $tareas['nomproyecto']];

                //Eliminamos los repetidos
                $listaproyectos = array_map("unserialize", array_unique(array_map("serialize", $listaproyectos)));
            } elseif(!empty(auth()->user()->admin)) {
                //Alamcenamos el RFC de los proectos
                $listaproyectos[] = ['RFC' => $tareas['rfcproyecto'], 'Nombre' => $tareas['nomproyecto']];

                //Eliminamos los repetidos
                $listaproyectos = array_map("unserialize", array_unique(array_map("serialize", $listaproyectos)));
            }
        }

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

        return view('livewire.tareacolab', ['tareas' => $listatareas, 'proyectos' => $listaproyectos, 'rfccolab' => $this->rfccolab, 'consulconta' => $consulconta]);
    }
}
