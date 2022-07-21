<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use App\Models\User;
use Livewire\Component;

class Tareaadmincolab extends Component
{
    //Variables
    public $mestareaadmin;
    public $aniotareaadmin;

    protected $listeners = ['tareacolabrefresh' => 'tareacolabrefresh', 'colabrefresh' => '$refresh'];

    //Metodo para refrescar 
    public function tareacolabrefresh()
    {
        //Cerramos el modal de agregar tarea
        $this->dispatchBrowserEvent('estadonuevo', []);
    }

    public function render()
    {
        //Consultamos a la colleccion de clientes para obtener los colaboradores
        $colaboradores = User::where('tipo', '2')
            ->orwhere('tipo', 'VOLU')
            ->orwhere('tipo', 'Nomina')
            ->where('nombre', '!=', null)
            ->get();

        //Consultamos las tareas para mostrar por colaborador
        $tareas = Tareas::where('asigntarea', 'like', '%' . $this->aniotareaadmin . '-' . $this->mestareaadmin . '%')->get();

        //Cerramos el modal de agregar tarea
        $this->dispatchBrowserEvent('estadonuevo', []);

        return view('livewire.tareaadmincolab', ['colaboradores' => $colaboradores, 'tareas' => $tareas]);
    }
}
