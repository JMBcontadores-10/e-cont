<?php

namespace App\Http\Livewire;

use Livewire\Component;
use DateTime;
use DateTimeZone;
use App\Models\Tareas;

class NotifitareaContent extends Component
{
    public function IniciarTarea($id)
    {
        //Establecemos la zona horaria
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);

        //Cambiamos de no iniciado a en proceso
        Tareas::where('_id', $id)
            ->update([
                'estado' => '1',
                'iniciotareas' => $dt->format('Y-m-d H:i:s'),
            ], ['upsert' => true]);

        //Redireccionamos a la vista de tareas
        return redirect()->route('tareas');
    }

    public function FinTareas($id)
    {
        //Cambiamos de no iniciado a en proceso
        Tareas::where('_id', $id)
            ->delete();
    }

    public function TareaAdmin()
    {
        //Redireccionamos a la vista de tareas
        return redirect()->route('tareas');
    }

    public function render()
    {
        if (!empty(auth()->user()->admin)) {
            //Obtenemos las tareas asignadas y finalizada
            $listatareas = Tareas::orderBy('updated_at', 'desc')->get();
        } else {
            //Obtenemos las tareas asignadas
            $listatareas = Tareas::where('estado', '0')->orderBy('created_at', 'desc')->get();
        }

        if (!empty(auth()->user()->admin)) {
            //Contador de tareas
            $totaltareas = 0;

            foreach ($listatareas as $tareas) {
                if ($tareas['estado'] != '0' && $tareas['estado'] != null) {
                    $totaltareas++;
                }
            }
        } elseif (!empty(auth()->user()->tipo)) {
            //Contador de tareas
            $totaltareas = 0;

            foreach ($listatareas as $tareas) {
                if ($tareas['rfccolaborador'] == auth()->user()->RFC) {
                    $totaltareas++;
                }
            }
        } else {
            $totaltareas = 0;
        }

        return view('livewire.notifitarea-content', ['totaltareas' => $totaltareas, 'tareas' => $listatareas]);
    }
}
