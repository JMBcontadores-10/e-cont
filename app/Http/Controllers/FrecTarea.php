<?php

namespace App\Http\Controllers;

use App\Models\Tareas;
use Illuminate\Http\Request;

class FrecTarea extends Controller
{
    //Metodo para crear la tarea dependiento del periodo dado
    public function TareaPeriodo()
    {
        //Consultamos los datos de las tareas
        $tareasperiodo = Tareas::whereNotNull('periodo')
            ->get();

        //Realizamos un bucle para obtener los periodos
        foreach ($tareasperiodo as $tarea) {
            //Condicional para saber el periodo
            switch ($tarea['periodo']) {
                case 'Diario':
                    //Creamos una nueva tarea utilizando los datos anteriores
                    Tareas::create([
                        'id' => $tarea['id'],
                        'nombreadmin' => $tarea['nombreadmin'],
                        'rfcadmin' => $tarea['rfcadmin'],
                        'nombre' => $tarea['nombre'],
                        'descripcion' => $tarea['descripcion'],
                        'nomproyecto' => $tarea['nomproyecto'],
                        'rfcproyecto' => $tarea['rfcproyecto'],
                        'fechaentrega' => date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 day")),
                        'prioridad' => $tarea['prioridad'],
                        'frecuencia' => $tarea['frecuencia'],
                        'periodo' => $tarea['periodo'],
                        'asigntarea' => date('Y-m-d'),
                        'rfccolaborador' => $tarea['rfccolaborador'],
                        'nomcolaborador' => $tarea['nomcolaborador'],
                        'estado' => '0',
                    ]);

                    echo "Diario" . ' - ' . date('Y-m-d') . '<br>';
                    break;

                case 'Semanal':
                    //Obtenemos la fecha de hoy
                    $fechahoy = date('Y-m-d');

                    //Obtenemos la fecha de la tarea
                    $fechatarea =  date("Y-m-d", strtotime($tarea['iniciotarea'] . "+ 1 week"));

                    //Condicional para saber si las fechas coinciden
                    if ($fechahoy == $fechatarea) {
                        //Creamos una nueva tarea utilizando los datos anteriores
                        Tareas::create([
                            'id' => $tarea['id'],
                            'nombreadmin' => $tarea['nombreadmin'],
                            'rfcadmin' => $tarea['rfcadmin'],
                            'nombre' => $tarea['nombre'],
                            'descripcion' => $tarea['descripcion'],
                            'nomproyecto' => $tarea['nomproyecto'],
                            'rfcproyecto' => $tarea['rfcproyecto'],
                            'fechaentrega' => date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 week")),
                            'prioridad' => $tarea['prioridad'],
                            'frecuencia' => $tarea['frecuencia'],
                            'periodo' => $tarea['periodo'],
                            'asigntarea' => date('Y-m-d'),
                            'rfccolaborador' => $tarea['rfccolaborador'],
                            'nomcolaborador' => $tarea['nomcolaborador'],
                            'estado' => '0',
                        ]);
                    }

                    echo "Semanal" . ' - ' . $fechahoy . ' - ' . $fechatarea . '<br>';
                    break;

                case 'Mensual':
                    //Obtenemos la fecha de hoy
                    $fechahoy = date('Y-m-d');

                    //Obtenemos la fecha de la tarea
                    $fechatarea =  date("Y-m-d", strtotime($tarea['iniciotarea'] . "+ 1 month"));

                    //Condicional para saber si las fechas coinciden
                    if ($fechahoy == $fechatarea) {
                        //Creamos una nueva tarea utilizando los datos anteriores
                        Tareas::create([
                            'id' => $tarea['id'],
                            'nombreadmin' => $tarea['nombreadmin'],
                            'rfcadmin' => $tarea['rfcadmin'],
                            'nombre' => $tarea['nombre'],
                            'descripcion' => $tarea['descripcion'],
                            'nomproyecto' => $tarea['nomproyecto'],
                            'rfcproyecto' => $tarea['rfcproyecto'],
                            'fechaentrega' => date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 month")),
                            'prioridad' => $tarea['prioridad'],
                            'frecuencia' => $tarea['frecuencia'],
                            'periodo' => $tarea['periodo'],
                            'asigntarea' => date('Y-m-d'),
                            'rfccolaborador' => $tarea['rfccolaborador'],
                            'nomcolaborador' => $tarea['nomcolaborador'],
                            'estado' => '0',
                        ]);
                    }

                    echo "Mensual" . ' - ' . $fechahoy . ' - ' . $fechatarea . '<br>';
                    break;

                case 'Bimestral':
                    //Obtenemos la fecha de hoy
                    $fechahoy = date('Y-m-d');

                    //Obtenemos la fecha de la tarea
                    $fechatarea =  date("Y-m-d", strtotime($tarea['iniciotarea'] . "+ 2 month"));

                    //Condicional para saber si las fechas coinciden
                    if ($fechahoy == $fechatarea) {
                        //Creamos una nueva tarea utilizando los datos anteriores
                        Tareas::create([
                            'id' => $tarea['id'],
                            'nombreadmin' => $tarea['nombreadmin'],
                            'rfcadmin' => $tarea['rfcadmin'],
                            'nombre' => $tarea['nombre'],
                            'descripcion' => $tarea['descripcion'],
                            'nomproyecto' => $tarea['nomproyecto'],
                            'rfcproyecto' => $tarea['rfcproyecto'],
                            'fechaentrega' => date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 2 month")),
                            'prioridad' => $tarea['prioridad'],
                            'frecuencia' => $tarea['frecuencia'],
                            'periodo' => $tarea['periodo'],
                            'asigntarea' => date('Y-m-d'),
                            'rfccolaborador' => $tarea['rfccolaborador'],
                            'nomcolaborador' => $tarea['nomcolaborador'],
                            'estado' => '0',
                        ]);
                    }

                    echo "Bimestral" . ' - ' . $fechahoy . ' - ' . $fechatarea . '<br>';
                    break;

                case 'Anual':
                    //Obtenemos la fecha de hoy
                    $fechahoy = date('Y-m-d');

                    //Obtenemos la fecha de la tarea
                    $fechatarea =  date("Y-m-d", strtotime($tarea['iniciotarea'] . "+ 1 year"));

                    //Condicional para saber si las fechas coinciden
                    if ($fechahoy == $fechatarea) {
                        //Creamos una nueva tarea utilizando los datos anteriores
                        Tareas::create([
                            'id' => $tarea['id'],
                            'nombreadmin' => $tarea['nombreadmin'],
                            'rfcadmin' => $tarea['rfcadmin'],
                            'nombre' => $tarea['nombre'],
                            'descripcion' => $tarea['descripcion'],
                            'nomproyecto' => $tarea['nomproyecto'],
                            'rfcproyecto' => $tarea['rfcproyecto'],
                            'fechaentrega' => date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 year")),
                            'prioridad' => $tarea['prioridad'],
                            'frecuencia' => $tarea['frecuencia'],
                            'periodo' => $tarea['periodo'],
                            'asigntarea' => date('Y-m-d'),
                            'rfccolaborador' => $tarea['rfccolaborador'],
                            'nomcolaborador' => $tarea['nomcolaborador'],
                            'estado' => '0',
                        ]);
                    }

                    echo "Anual" . ' - ' . $fechahoy . ' - ' . $fechatarea . '<br>';
                    break;
            }
        }
    }
}
