<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use Livewire\Component;
use App\Models\User;

class Tareasadmin extends Component
{
    //Variables
    public $mestareaadmin;
    public $aniotareaadmin;
    public $avancetareaadmin;
    public $departament;
    public $colaboselect;

    //Variable para la captura de impuestos
    public $fechaimpu;

    protected $listeners = [
        'tareaselect' => 'tareaselect',
    ];

    //Metodo para limpiar reiniciar los filtros
    public function ReloadFilt()
    {
        //Seleccionamos la primera opcion
        $this->departament = 'Contabilidad';

        //Limpiamos el filtro de los empleados
        $this->colaboselect = "";
    }

    //Metodo para mostrar el modal con el movimiento seleccionado de cheques y transferencias
    public function tareaselect($data)
    {
        //Accedemos a la seccion de tareas
        $this->avancetareaadmin = $data['seccion'];
    }

    public function mount()
    {
        //Alamcenamos la fecha actual en variables
        $this->mestareaadmin = date('m');
        $this->aniotareaadmin = date('Y');

        //Almacenamos el avance de la tarea en variables
        $this->avancetareaadmin = 'Departamento';

        //Almacenamos el avance de la tarea en variables
        $this->departament = 'Contabilidad';
    }

    public function render()
    {
        //Vamos a obtener los colaboradores
        $consulconta = User::where('tipo', '2')
            ->orwhere('tipo', 'VOLU')
            ->where('nombre', '!=', null)
            ->get(['RFC', 'nombre']);

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

        return view('livewire.tareasadmin', ['meses' => $meses, 'anios' => $anios, 'consulconta' => $consulconta]);
    }
}
