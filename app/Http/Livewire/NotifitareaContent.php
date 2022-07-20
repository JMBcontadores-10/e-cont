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
            ->update([
                'estado' => 'fin',
            ], ['upsert' => true]);
    }

    public function TareaAdmin()
    {
        //Redireccionamos a la vista de tareas
        return redirect()->route('tareas');
    }

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
            //Obtenemos las tareas asignadas
            $listatareas = Tareas::where('estado', '0')->orderBy('created_at', 'desc')->get();
        }

        return view('livewire.notifitarea-content', ['totaltareas' => $totaltareas, 'tareas' => $listatareas]);
    }
}
