<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use Livewire\Component;

class NotifitareaSection extends Component
{
    public function render()
    {
        //Condicional para saber si se inicio sesion un contador o empresa
        if (!empty(auth()->user()->tipo)) {
            //switch para obtener el total de tareas
            switch (true) {
                case !empty(auth()->user()->admin):
                    //Obtenemos las tareas asignadas
                    $listatareas = Tareas::orderBy('updated_at', 'desc')->get();

                    //Contador de tareas
                    $totaltareas = 0;

                    foreach ($listatareas as $tareas) {
                        if ($tareas['estado'] != '0' && $tareas['estado'] != null &&  $tareas['estado'] != 'fin') {
                            $totaltareas++;
                        }
                    }
                    break;

                case auth()->user()->tipo == 'VOLU':
                    $listatareas = Tareas::orderBy('updated_at', 'desc')
                        ->get();

                    //Contador de tareas
                    $totaltareas = 0;

                    foreach ($listatareas as $tareas) {
                        //Obtenemos el total del tareas con respecto a los que emiten y reciben tareas
                        if (($tareas['rfcadmin'] == auth()->user()->RFC && $tareas['estado'] != '0' && $tareas['estado'] != null && $tareas['estado'] != 'fin') || ($tareas['rfccolaborador'] == auth()->user()->RFC && $tareas['estado'] != 'fin' && $tareas['estado'] == '0')) {
                            $totaltareas++;
                        }
                    }
                    break;

                default:
                    //Obtenemos las tareas asignadas
                    $listatareas = Tareas::where('estado', '0')->orderBy('created_at', 'desc')->get();

                    //Contador de tareas
                    $totaltareas = 0;

                    foreach ($listatareas as $tareas) {
                        if ($tareas['rfccolaborador'] == auth()->user()->RFC &&  $tareas['estado'] != 'fin') {
                            $totaltareas++;
                        }
                    }
                    break;
            }
        } else {
            $totaltareas = 0; //Si es una empera esta se iguala a 0
        }


        return view('livewire.notifitarea-section', ['totaltareas' => $totaltareas, 'tareas' => $listatareas]);
    }
}
