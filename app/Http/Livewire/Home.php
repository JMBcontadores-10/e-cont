<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cheques;
use App\Models\Tareas;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    //Variables globales
    use WithPagination;
    public $rfcEmpresa;
    public $search;
    public float $importe = 0;
    public $anio;
    public int $perPage = 3;
    protected $paginationTheme = 'bootstrap'; // Para dar e estilo numerico al paginador
    public $rfccolab;

    //Metodo para identificar el tipo de usuario
    public function mount()
    {
        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {
            $this->rfcEmpresa = auth()->user()->RFC;
        }

        //Obtenemos la lista de tareas de cada colaborador
        if (!empty(auth()->user()->admin)) {
            //Obtenemos el RFC del colaborador que inicio sesion
            $this->rfccolab = "";
        } else {
            $this->rfccolab = auth()->user()->RFC;
        }

        $this->condicion = '>=';
    }

    public function render()
    {
        //Condicional para mostrar las empresas
        if (!empty(auth()->user()->tipo) || !empty(auth()->user()->TipoSE)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->get();

                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else {
            $emp = '';
        }

        //Guardamos los datos de los años en una variable
        $anios = range(2014, date('Y'));

        //Consulta para obtener los datos pendientes
        //Condicional para mostrar que no se ejecute la consulta cuando no hay año
        $cheqpendient = Cheques::search($this->search)
            ->where('rfc', $this->rfcEmpresa)
            ->where('fecha', 'like', '%' . $this->anio . "-" . '%')
            ->get();

        //Condicional para saber si inicio sesion un colaborador o administrador 
        if (!empty(auth()->user()->admin)) {
            //Administrador
            //Consultamos los colaboradores
            $colaboradores = User::where('tipo', '2')
                ->orwhere('tipo', 'VOLU')
                ->where('nombre', '!=', null)
                ->get(['RFC', 'nombre']);
        } else {
            //Mandamos las varible vacia
            $colaboradores = "";
        }

        //Obtenemos las tareas asignadas
        $listatareas = Tareas::whereNull('completado')->get();

        //Arreglo para alamacenar la lista de proyectos
        $listaproyectos = [];

        //Obtenemos los proyectos
        foreach ($listatareas as $tareas) {
            if ($tareas['rfccolaborador'] == $this->rfccolab) {
                //Alamcenamos el RFC de los proectos
                $listaproyectos[] = ['RFC' => $tareas['rfcproyecto'], 'Nombre' => $tareas['nomproyecto']];

                //Eliminamos los repetidos
                $listaproyectos = array_map("unserialize", array_unique(array_map("serialize", $listaproyectos)));;
            }
        }

        return view('livewire.home', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'anios' => $anios, 'pendientes' => $cheqpendient, 'colaboradores' => $colaboradores, 'tareas' => $listatareas, 'proyectos' => $listaproyectos])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
