<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use App\Models\User;
use Livewire\Component;

class Tareacolab extends Component
{
    //Variables
    public $mestareaadmin;
    public $aniotareaadmin;

    public function render()
    {
        //Consultamos a la colleccion de clientes para obtener los colaboradores
        $colaboradores = User::where('tipo', '2')
            ->orwhere('tipo', 'VOLU')
            ->where('nombre', '!=', null)
            ->get();

        //Consultamos las tareas para mostrar por colaborador
        $tareas = Tareas::get();

        return view('livewire.tareacolab', ['colaboradores' => $colaboradores, 'tareas' => $tareas]);
    }
}
