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
        //PENDIENTE PARA LA CREACION DE TAREAS PERIODICAS
        $tareasperiodo = Tareas::whereNotNull('periodo')
            ->groupBy('id')
            ->get([
                'id',
                'nombreadmin',
                'rfcadmin',
                'nombre',
                'descripcion',
                'nomproyecto',
                'rfcproyecto',
                'fechaentrega',
                'prioridad',
                'frecuencia',
                'periodo',
                'rfccolaborador',
                'nomcolaborador',
                'tipoimpuesto',
                'diasfrecu',
                'finfrecu',
                'diamesfrecu',
            ]);

        //Realizamos un bucle para obtener los periodos
        foreach ($tareasperiodo as $tarea) {
            //Condicional para saber el periodo
            switch ($tarea['periodo']) {
                case 'Diario':
                    //Obtenemos los datos del dia de hoy y la fecha de finalizacion
                    $fecha_actual = strtotime(date('Y-m-d H:i:00', time()));
                    $fecha_entrada = strtotime($tarea['finfrecu'] . ' 23:59:59');

                    //Condicional para saber si a fecha de repeticion finalizo
                    if ($fecha_actual < $fecha_entrada || $tarea['finfrecu'] == null) {
                        //Condicional para saber si existe una fecha de entrega
                        if (!empty($tarea['fechaentrega'])) {
                            $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 day"));
                        } else {
                            $fechaentrega = null;
                        }

                        //Creamos una nueva tarea utilizando los datos anteriores
                        Tareas::create([
                            'id' => $tarea['id'],
                            'nombreadmin' => $tarea['nombreadmin'],
                            'rfcadmin' => $tarea['rfcadmin'],
                            'nombre' => $tarea['nombre'],
                            'descripcion' => $tarea['descripcion'],
                            'nomproyecto' => $tarea['nomproyecto'],
                            'rfcproyecto' => $tarea['rfcproyecto'],
                            'fechaentrega' => $fechaentrega,
                            'prioridad' => $tarea['prioridad'],
                            'frecuencia' => $tarea['frecuencia'],
                            'periodo' => $tarea['periodo'],
                            'asigntarea' => date('Y-m-d'),
                            'rfccolaborador' => $tarea['rfccolaborador'],
                            'nomcolaborador' => $tarea['nomcolaborador'],
                            'tipoimpuesto' => $tarea['tipoimpuesto'],
                            'estado' => '0',
                            'diasfrecu' => $tarea['diasfrecu'],
                            'finfrecu' => $tarea['finfrecu'],
                        ]);

                        echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                    }

                    break;

                case 'Semanal':
                    //Obtenemos los datos del dia de hoy y la fecha de finalizacion
                    $fecha_actual = strtotime(date('Y-m-d H:i:00', time()));
                    $fecha_entrada = strtotime($tarea['finfrecu'] . ' 23:59:59');

                    //Condicional para saber si a fecha de repeticion finalizo
                    if ($fecha_actual < $fecha_entrada || $tarea['finfrecu'] == null) {
                        //Condicional para saber si tiene o no dias seleccionados
                        if (!empty($tarea['diasfrecu'])) {
                            //Foreach para saber los dias de la semana que se repetira la tarea
                            foreach ($tarea['diasfrecu'] as $dias) {
                                //Switch para saber el dia
                                switch ($dias) {
                                    case "L":
                                        $dia = "Monday";
                                        break;

                                    case "M":
                                        $dia = "Tuesday";
                                        break;

                                    case "Mi":
                                        $dia = "Wednesday";
                                        break;

                                    case "J":
                                        $dia = "Thursday";
                                        break;

                                    case "V":
                                        $dia = "Friday";
                                        break;

                                    case "S":
                                        $dia = "Saturday";
                                        break;

                                    case "D":
                                        $dia = "Sunday";
                                        break;
                                }

                                //Condicional para saber si el dia de hoy es el indicado
                                $diahoy = date('l'); //Obtenemos el nombre del dia de hoy
                                if ($diahoy ==  $dia) {
                                    //Condicional para saber si existe una fecha de entrega
                                    if (!empty($tarea['fechaentrega'])) {
                                        $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 week"));
                                    } else {
                                        $fechaentrega = null;
                                    }

                                    //Creamos una nueva tarea utilizando los datos anteriores
                                    Tareas::create([
                                        'id' => $tarea['id'],
                                        'nombreadmin' => $tarea['nombreadmin'],
                                        'rfcadmin' => $tarea['rfcadmin'],
                                        'nombre' => $tarea['nombre'],
                                        'descripcion' => $tarea['descripcion'],
                                        'nomproyecto' => $tarea['nomproyecto'],
                                        'rfcproyecto' => $tarea['rfcproyecto'],
                                        'fechaentrega' => $fechaentrega,
                                        'prioridad' => $tarea['prioridad'],
                                        'frecuencia' => $tarea['frecuencia'],
                                        'periodo' => $tarea['periodo'],
                                        'asigntarea' => date('Y-m-d'),
                                        'rfccolaborador' => $tarea['rfccolaborador'],
                                        'nomcolaborador' => $tarea['nomcolaborador'],
                                        'tipoimpuesto' => $tarea['tipoimpuesto'],
                                        'estado' => '0',
                                        'diasfrecu' => $tarea['diasfrecu'],
                                        'finfrecu' => $tarea['finfrecu'],
                                    ]);

                                    echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                                } else {
                                    //Obtenemos la fecha de hoy
                                    $fechahoy = date('Y-m-d');

                                    //Obtenemos la fecha de la tarea
                                    $fechatarea =  date("Y-m-d", strtotime($tarea['asigntarea'] . "+ 1 week"));

                                    //Condicional para saber si las fechas coinciden
                                    if ($fechahoy == $fechatarea) {
                                        //Condicional para saber si existe una fecha de entrega
                                        if (!empty($tarea['fechaentrega'])) {
                                            $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 week"));
                                        } else {
                                            $fechaentrega = null;
                                        }

                                        //Creamos una nueva tarea utilizando los datos anteriores
                                        Tareas::create([
                                            'id' => $tarea['id'],
                                            'nombreadmin' => $tarea['nombreadmin'],
                                            'rfcadmin' => $tarea['rfcadmin'],
                                            'nombre' => $tarea['nombre'],
                                            'descripcion' => $tarea['descripcion'],
                                            'nomproyecto' => $tarea['nomproyecto'],
                                            'rfcproyecto' => $tarea['rfcproyecto'],
                                            'fechaentrega' => $fechaentrega,
                                            'prioridad' => $tarea['prioridad'],
                                            'frecuencia' => $tarea['frecuencia'],
                                            'periodo' => $tarea['periodo'],
                                            'asigntarea' => date('Y-m-d'),
                                            'rfccolaborador' => $tarea['rfccolaborador'],
                                            'nomcolaborador' => $tarea['nomcolaborador'],
                                            'tipoimpuesto' => $tarea['tipoimpuesto'],
                                            'estado' => '0',
                                            'diasfrecu' => $tarea['diasfrecu'],
                                            'finfrecu' => $tarea['finfrecu'],
                                        ]);

                                        echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                                    }
                                }
                            }
                        } else {
                            //Si no selecciona la fecha
                            //Obtenemos la fecha de hoy
                            $fechahoy = date('Y-m-d');

                            //Obtenemos la fecha de la tarea
                            $fechatarea =  date("Y-m-d", strtotime($tarea['asigntarea'] . "+ 1 week"));

                            //Condicional para saber si las fechas coinciden
                            if ($fechahoy == $fechatarea) {
                                //Condicional para saber si existe una fecha de entrega
                                if (!empty($tarea['fechaentrega'])) {
                                    $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 week"));
                                } else {
                                    $fechaentrega = null;
                                }

                                //Creamos una nueva tarea utilizando los datos anteriores
                                Tareas::create([
                                    'id' => $tarea['id'],
                                    'nombreadmin' => $tarea['nombreadmin'],
                                    'rfcadmin' => $tarea['rfcadmin'],
                                    'nombre' => $tarea['nombre'],
                                    'descripcion' => $tarea['descripcion'],
                                    'nomproyecto' => $tarea['nomproyecto'],
                                    'rfcproyecto' => $tarea['rfcproyecto'],
                                    'fechaentrega' => $fechaentrega,
                                    'prioridad' => $tarea['prioridad'],
                                    'frecuencia' => $tarea['frecuencia'],
                                    'periodo' => $tarea['periodo'],
                                    'asigntarea' => date('Y-m-d'),
                                    'rfccolaborador' => $tarea['rfccolaborador'],
                                    'nomcolaborador' => $tarea['nomcolaborador'],
                                    'tipoimpuesto' => $tarea['tipoimpuesto'],
                                    'estado' => '0',
                                    'diasfrecu' => $tarea['diasfrecu'],
                                    'finfrecu' => $tarea['finfrecu'],
                                ]);

                                echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                            }
                        }
                    }
                    break;

                case 'Mensual':
                    //Obtenemos los datos del dia de hoy y la fecha de finalizacion
                    $fecha_actual = strtotime(date('Y-m-d H:i:00', time()));
                    $fecha_entrada = strtotime($tarea['finfrecu'] . ' 23:59:59');

                    //Condicional para saber si a fecha de repeticion finalizo
                    if ($fecha_actual < $fecha_entrada || $tarea['finfrecu'] == null) {
                        //Obtenemos una condicional para saber si tiene una fecha de periodo
                        if (!empty($tarea['diamesfrecu'])) {
                            //Condicional para saber si se selecciono en fin de mes
                            if ($tarea['diamesfrecu'] == 'finmes') {
                                //Obtenemos el numero del dia de hoy
                                $diahoyfinmes = date('j');

                                //Obtenemos el ultimo dia del mes
                                $diafinmes = date('t');

                                //Condicional para saber si el llego al ultimo dia del mes
                                if ($diahoyfinmes == $diafinmes) {
                                    //Condicional para saber si existe una fecha de entrega
                                    if (!empty($tarea['fechaentrega'])) {
                                        $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 month"));
                                    } else {
                                        $fechaentrega = null;
                                    }

                                    //Creamos una nueva tarea utilizando los datos anteriores
                                    Tareas::create([
                                        'id' => $tarea['id'],
                                        'nombreadmin' => $tarea['nombreadmin'],
                                        'rfcadmin' => $tarea['rfcadmin'],
                                        'nombre' => $tarea['nombre'],
                                        'descripcion' => $tarea['descripcion'],
                                        'nomproyecto' => $tarea['nomproyecto'],
                                        'rfcproyecto' => $tarea['rfcproyecto'],
                                        'fechaentrega' => $fechaentrega,
                                        'prioridad' => $tarea['prioridad'],
                                        'frecuencia' => $tarea['frecuencia'],
                                        'periodo' => $tarea['periodo'],
                                        'asigntarea' => date('Y-m-d'),
                                        'rfccolaborador' => $tarea['rfccolaborador'],
                                        'nomcolaborador' => $tarea['nomcolaborador'],
                                        'tipoimpuesto' => $tarea['tipoimpuesto'],
                                        'estado' => '0',
                                        'diasfrecu' => $tarea['diasfrecu'],
                                        'finfrecu' => $tarea['finfrecu'],
                                        'diamesfrecu' => $tarea['diamesfrecu'],
                                    ]);

                                    echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                                }
                            } else {
                                //Si es diferente a fin de mes
                                //Obtenemos el numero del dia de hoy
                                $diahoyfinmes = date('j');

                                //Condicional para saber si la fecha del dia coincide con el numero seleccionado
                                if ($diahoyfinmes == $tarea['diamesfrecu']) {
                                    //Condicional para saber si existe una fecha de entrega
                                    if (!empty($tarea['fechaentrega'])) {
                                        $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 month"));
                                    } else {
                                        $fechaentrega = null;
                                    }

                                    //Creamos una nueva tarea utilizando los datos anteriores
                                    Tareas::create([
                                        'id' => $tarea['id'],
                                        'nombreadmin' => $tarea['nombreadmin'],
                                        'rfcadmin' => $tarea['rfcadmin'],
                                        'nombre' => $tarea['nombre'],
                                        'descripcion' => $tarea['descripcion'],
                                        'nomproyecto' => $tarea['nomproyecto'],
                                        'rfcproyecto' => $tarea['rfcproyecto'],
                                        'fechaentrega' => $fechaentrega,
                                        'prioridad' => $tarea['prioridad'],
                                        'frecuencia' => $tarea['frecuencia'],
                                        'periodo' => $tarea['periodo'],
                                        'asigntarea' => date('Y-m-d'),
                                        'rfccolaborador' => $tarea['rfccolaborador'],
                                        'nomcolaborador' => $tarea['nomcolaborador'],
                                        'tipoimpuesto' => $tarea['tipoimpuesto'],
                                        'estado' => '0',
                                        'diasfrecu' => $tarea['diasfrecu'],
                                        'finfrecu' => $tarea['finfrecu'],
                                        'diamesfrecu' => $tarea['diamesfrecu'],
                                    ]);

                                    echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                                }
                            }
                        } else {
                            //Obtenemos la fecha de hoy
                            $fechahoy = date('Y-m-d');

                            //Obtenemos la fecha de la tarea
                            $fechatarea =  date("Y-m-d", strtotime($tarea['asigntarea'] . "+ 1 month"));

                            //Condicional para saber si las fechas coinciden
                            if ($fechahoy == $fechatarea) {
                                //Condicional para saber si existe una fecha de entrega
                                if (!empty($tarea['fechaentrega'])) {
                                    $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 month"));
                                } else {
                                    $fechaentrega = null;
                                }

                                //Creamos una nueva tarea utilizando los datos anteriores
                                Tareas::create([
                                    'id' => $tarea['id'],
                                    'nombreadmin' => $tarea['nombreadmin'],
                                    'rfcadmin' => $tarea['rfcadmin'],
                                    'nombre' => $tarea['nombre'],
                                    'descripcion' => $tarea['descripcion'],
                                    'nomproyecto' => $tarea['nomproyecto'],
                                    'rfcproyecto' => $tarea['rfcproyecto'],
                                    'fechaentrega' => $fechaentrega,
                                    'prioridad' => $tarea['prioridad'],
                                    'frecuencia' => $tarea['frecuencia'],
                                    'periodo' => $tarea['periodo'],
                                    'asigntarea' => date('Y-m-d'),
                                    'rfccolaborador' => $tarea['rfccolaborador'],
                                    'nomcolaborador' => $tarea['nomcolaborador'],
                                    'tipoimpuesto' => $tarea['tipoimpuesto'],
                                    'estado' => '0',
                                    'diasfrecu' => $tarea['diasfrecu'],
                                    'finfrecu' => $tarea['finfrecu'],
                                    'diamesfrecu' => $tarea['diamesfrecu'],
                                ]);

                                echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                            }
                        }
                    }
                    break;

                case 'Bimestral':
                    //Obtenemos los datos del dia de hoy y la fecha de finalizacion
                    $fecha_actual = strtotime(date('Y-m-d H:i:00', time()));
                    $fecha_entrada = strtotime($tarea['finfrecu'] . ' 23:59:59');

                    //Condicional para saber si a fecha de repeticion finalizo
                    if ($fecha_actual < $fecha_entrada || $tarea['finfrecu'] == null) {
                        //Obtenemos la fecha de hoy
                        $fechahoy = date('Y-m-d');

                        //Obtenemos la fecha de la tarea
                        $fechatarea =  date("Y-m-d", strtotime($tarea['asigntarea'] . "+ 2 month"));

                        //Condicional para saber si las fechas coinciden
                        if ($fechahoy == $fechatarea) {
                            //Condicional para saber si existe una fecha de entrega
                            if (!empty($tarea['fechaentrega'])) {
                                $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 2 month"));
                            } else {
                                $fechaentrega = null;
                            }

                            //Creamos una nueva tarea utilizando los datos anteriores
                            Tareas::create([
                                'id' => $tarea['id'],
                                'nombreadmin' => $tarea['nombreadmin'],
                                'rfcadmin' => $tarea['rfcadmin'],
                                'nombre' => $tarea['nombre'],
                                'descripcion' => $tarea['descripcion'],
                                'nomproyecto' => $tarea['nomproyecto'],
                                'rfcproyecto' => $tarea['rfcproyecto'],
                                'fechaentrega' => $fechaentrega,
                                'prioridad' => $tarea['prioridad'],
                                'frecuencia' => $tarea['frecuencia'],
                                'periodo' => $tarea['periodo'],
                                'asigntarea' => date('Y-m-d'),
                                'rfccolaborador' => $tarea['rfccolaborador'],
                                'nomcolaborador' => $tarea['nomcolaborador'],
                                'tipoimpuesto' => $tarea['tipoimpuesto'],
                                'estado' => '0',
                                'diasfrecu' => $tarea['diasfrecu'],
                                'finfrecu' => $tarea['finfrecu'],
                            ]);

                            echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                        }
                    }
                    break;

                case 'Anual':
                    //Obtenemos los datos del dia de hoy y la fecha de finalizacion
                    $fecha_actual = strtotime(date('Y-m-d H:i:00', time()));
                    $fecha_entrada = strtotime($tarea['finfrecu'] . ' 23:59:59');

                    //Condicional para saber si a fecha de repeticion finalizo
                    if ($fecha_actual < $fecha_entrada || $tarea['finfrecu'] == null) {
                        //Obtenemos la fecha de hoy
                        $fechahoy = date('Y-m-d');

                        //Obtenemos la fecha de la tarea
                        $fechatarea =  date("Y-m-d", strtotime($tarea['asigntarea'] . "+ 1 year"));

                        //Condicional para saber si las fechas coinciden
                        if ($fechahoy == $fechatarea) {
                            //Condicional para saber si existe una fecha de entrega
                            if (!empty($tarea['fechaentrega'])) {
                                $fechaentrega = date("Y-m-d", strtotime($tarea['fechaentrega'] . "+ 1 year"));
                            } else {
                                $fechaentrega = null;
                            }

                            //Creamos una nueva tarea utilizando los datos anteriores
                            Tareas::create([
                                'id' => $tarea['id'],
                                'nombreadmin' => $tarea['nombreadmin'],
                                'rfcadmin' => $tarea['rfcadmin'],
                                'nombre' => $tarea['nombre'],
                                'descripcion' => $tarea['descripcion'],
                                'nomproyecto' => $tarea['nomproyecto'],
                                'rfcproyecto' => $tarea['rfcproyecto'],
                                'fechaentrega' => $fechaentrega,
                                'prioridad' => $tarea['prioridad'],
                                'frecuencia' => $tarea['frecuencia'],
                                'periodo' => $tarea['periodo'],
                                'asigntarea' => date('Y-m-d'),
                                'rfccolaborador' => $tarea['rfccolaborador'],
                                'nomcolaborador' => $tarea['nomcolaborador'],
                                'tipoimpuesto' => $tarea['tipoimpuesto'],
                                'estado' => '0',
                                'diasfrecu' => $tarea['diasfrecu'],
                                'finfrecu' => $tarea['finfrecu'],
                            ]);

                            echo 'Tarea' . $tarea['periodo'] . ' ' . $tarea['nombre'] . ' creada satisfactoriamente';
                        }
                    }
                    break;
            }
        }
    }
}
