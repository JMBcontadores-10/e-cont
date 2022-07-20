<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\ExpedFiscal;
use App\Models\Tareas;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Livewire\Component;

class Tareacolab extends Component
{
    //Variables
    public $rfccolab;
    public $aniotareaadmin;
    public $mestareaadmin;
    public $colaboselect;

    //Metodo para enviar el identificador para editar una tarea
    public function SendInfoEdit($id)
    {
        $this->emit('recibidedit', $id);
    }

    //Metodo para marcar como completado una tarea
    public function Completado($id)
    {
        //Establecemos la zona horaria
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $espa = new Cheques();

        //Consulamos las tareas
        $consultarea = Tareas::where('_id', $id)->first();

        //Marcamos como completado la tarea
        Tareas::where('_id', $id)
            ->update([
                'completado' => '1',
                'estado' => '2',
                'finalizo' => $dt->format('Y-m-d H:i:s'),
            ], ['upsert' => true]);

        //Marcamos el impuesto si contiene
        if (!empty($consultarea['tipoimpuesto'])) {
            //Obtenemos el mes anterior
            $mesanteriorimpu = date('m', strtotime('-1 month'));

            ExpedFiscal::where('rfc', $consultarea['rfcproyecto'])
                ->update([
                    'rfc' => $consultarea['rfcproyecto'],
                    'ExpedFisc.' . $dt->format('Y') . '.' . $consultarea['tipoimpuesto'] . '.' . $espa->fecha_es($dt->format($mesanteriorimpu)) . '.Declaracion' => $dt->format('Y-m-d'),
                ],  ['upsert' => true]);
        }
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
            //Obtenemos el RFC del colaboradoFr que inicio sesion
            $this->rfccolab = "";
        } else {
            //Alamcenamos la fecha actual en variables
            $this->mestareaadmin = date('m');
            $this->aniotareaadmin = date('Y');
            $this->rfccolab = auth()->user()->RFC;
        }
    }

    public function render()
    {
        //Mostramos el filtro cuando es una cuenta de volumetrico
        if (auth()->user()->tipo == 'VOLU') {
            //Obtenemos las tareas asignadas
            if (!empty($this->colaboselect)) {
                $listatareas = Tareas::where('asigntarea', 'like', '%' . $this->aniotareaadmin . '-' . $this->mestareaadmin . '%')
                    ->where('rfccolaborador', $this->colaboselect)
                    ->where('rfcadmin', auth()->user()->RFC)
                    ->get();
            } else {
                $listatareas = Tareas::where('asigntarea', 'like', '%' . $this->aniotareaadmin . '-' . $this->mestareaadmin . '%')
                    ->where(function ($query) {
                        $query->where('rfccolaborador', '=', auth()->user()->RFC)
                            ->orWhere('rfcadmin', '=', auth()->user()->RFC);
                    })
                    ->get();
            }
        } else {
            //Obtenemos las tareas asignadas
            if (!empty($this->colaboselect)) {
                $listatareas = Tareas::where('asigntarea', 'like', '%' . $this->aniotareaadmin . '-' . $this->mestareaadmin . '%')
                    ->where('rfccolaborador', $this->colaboselect)
                    ->get();
            } else {
                $listatareas = Tareas::where('asigntarea', 'like', '%' . $this->aniotareaadmin . '-' . $this->mestareaadmin . '%')
                    ->get();
            }
        }

        //Arreglo para alamacenar la lista de proyectos
        $listaproyectos = [];

        //Obtenemos los proyectos
        foreach ($listatareas as $tareas) {
            if (!empty(auth()->user()->admin) || auth()->user()->tipo == 'VOLU') {
                //Alamcenamos el RFC de los proectos
                $listaproyectos[] = ['RFC' => $tareas['rfcproyecto'], 'Nombre' => $tareas['nomproyecto']];

                //Eliminamos los repetidos
                $listaproyectos = array_map("unserialize", array_unique(array_map("serialize", $listaproyectos)));
            } elseif ($tareas['rfccolaborador'] == $this->rfccolab) {
                //Alamcenamos el RFC de los proectos
                $listaproyectos[] = ['RFC' => $tareas['rfcproyecto'], 'Nombre' => $tareas['nomproyecto']];

                //Eliminamos los repetidos
                $listaproyectos = array_map("unserialize", array_unique(array_map("serialize", $listaproyectos)));
            }
        }

        //Condicional para saber si inicio sesion un colaborador o administrador 
        if (!empty(auth()->user()->admin) || auth()->user()->tipo == 'VOLU') {
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

        return view('livewire.tareacolab', ['tareas' => $listatareas, 'proyectos' => $listaproyectos, 'rfccolab' => $this->rfccolab, 'consulconta' => $consulconta, 'meses' => $meses, 'anios' => $anios,]);
    }
}
