<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use Livewire\Component;

class NotifitareaSection extends Component
{
    public function render()
    {
        if (!empty(auth()->user()->admin)) {
            //Obtenemos las tareas asignadas
            $listatareas = Tareas::orderBy('updated_at', 'desc')->get();
        } else {
            //Obtenemos las tareas asignadas
            $listatareas = Tareas::where('estado', '0')->orderBy('created_at', 'desc')->get();
        }

        if (!empty(auth()->user()->admin)) {
            //Contador de tareas
            $totaltareas = 0;

            foreach ($listatareas as $tareas) {
                if ($tareas['estado'] != '0') {
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
        }

        return view('livewire.notifitarea-section', ['totaltareas' => $totaltareas, 'tareas' => $listatareas]);
    }
}
