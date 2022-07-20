<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Tareanueva extends Component
{
    //Variables formulario
    public $idtarea;
    public $idtareaperiod;
    public $nombretarea;
    public $descripciontarea;
    public $proyectotarea;
    public $fechaentregatarea;
    public $prioridadtarea;
    public $colaboradortarea;
    public $frecuentetarea;
    public $periodotarea;
    public $periodotareaperson;
    public $imputarea;
    public $colaboradorestarea = [];
    public $diasfrecu = [];
    public $fechafrecufin;
    public $nuncafecha;
    public $diasfrecumes;

    //Variables de estado
    public $hidden = 'hidden';
    public $hiddensema = 'hidden';
    public $hiddenmes = 'hidden';
    public $activecolabo = 'hidden';
    public $requiretarea = null;
    public $edofechafin = null;


    protected $listeners = ['recibidedit' => 'recibidedit'];

    //Metodo para recibir el identificador emitido
    public function recibidedit($id)
    {
        //Lo alamcenamos en una variable global
        $this->idtarea = $id;
    }

    //Metodo para agregar una nueva tarea
    public function NuevaTarea()
    {
        //Condicional para saber si hay colaboradores seleccionados
        if ($this->colaboradorestarea != null) {
            //Condicional para saber si exite un identificador y asi editar
            if (!empty($this->idtarea)) { //Obtenemos los datos de proyecto
                $infoproyect = json_decode($this->proyectotarea);

                //Transformamos a los colaboradores en un array
                foreach ($this->colaboradorestarea as $colaborador) {

                    //Condicional para saber si la opcion de nunca esta activo
                    if ($this->nuncafecha == 1) {
                        $finfrecu = null;
                    } else {
                        $finfrecu = $this->fechafrecufin;
                    }

                    //Condicional para las descripciones
                    if (!empty($this->descripciontarea)) {
                        $descripcion = trim($this->descripciontarea);
                    } else {
                        $descripcion = null;
                    }

                    //Condicional para saber si hay frecuencia
                    if ($this->frecuentetarea != 'Si') {
                        $this->periodotarea = null;
                        //Limpiamos los dias seleccionados
                        $this->diasfrecu = [];
                        //Quitamos el fecha de final de creacion
                        $finfrecu = null;
                        //Limpiamos el impuesto
                        $this->imputarea = null;
                    }

                    //Condicional para saber el caso del periodo que se seleccione
                    switch ($this->periodotarea) {
                        case 'Mensual':
                            $this->diasfrecu = [];
                            break;

                        case 'Semanal':
                            $this->diasfrecumes = null;
                            break;

                        default:
                            $this->diasfrecu = [];
                            $this->diasfrecumes = null;
                            break;
                    }


                    $colaborador = json_decode($colaborador);
                    //Actualizamos los datos
                    Tareas::where('_id', $this->idtarea)
                        ->update([
                            'id' => $this->idtareaperiod,
                            'nombreadmin' => auth()->user()->nombre,
                            'rfcadmin' => auth()->user()->RFC,
                            'nombre' => $this->nombretarea,
                            'descripcion' => $descripcion,
                            'nomproyecto' => $infoproyect->Nombre ?? 'Sin proyecto',
                            'rfcproyecto' => $infoproyect->RFC ?? 'Sin proyecto',
                            'fechaentrega' => $this->fechaentregatarea,
                            'prioridad' => $this->prioridadtarea,
                            'frecuencia' => $this->frecuentetarea,
                            'periodo' =>  $this->periodotareaperson ?? $this->periodotarea,
                            'asigntarea' => $this->asigntarea,
                            'rfccolaborador' => $colaborador->RFC,
                            'nomcolaborador' => $colaborador->Nombre,
                            'tipoimpuesto' => $this->imputarea,
                            'estado' => '0',
                            'diasfrecu' => $this->diasfrecu,
                            'finfrecu' => $finfrecu,
                            'diamesfrecu' => $this->diasfrecumes,
                            'completado' => null,
                            'estado' => '0',
                            'finalizo' => null,
                        ], ['upsert' => true]);
                }
            } else {
                //Se crea una nueva tarea
                //Validaciones
                $this->validate([
                    'nombretarea' => 'required',
                    'prioridadtarea' => 'required',
                    'colaboradorestarea' => 'required',
                ]);

                //Obtenemos los datos de proyecto
                $infoproyect = json_decode($this->proyectotarea);

                //Transformamos a los colaboradores en un array
                foreach ($this->colaboradorestarea as $colaborador) {
                    $colaborador = json_decode($colaborador);
                    $dtz = new DateTimeZone("America/Mexico_City");
                    $dt = new DateTime("now", $dtz);

                    //Condicional para las descripciones
                    if (!empty($this->descripciontarea)) {
                        $descripcion = trim($this->descripciontarea);
                    } else {
                        $descripcion = null;
                    }

                    //Condicional para saber el caso del periodo que se seleccione
                    switch ($this->periodotarea) {
                        case 'Mensual':
                            $this->diasfrecu = [];
                            break;

                        case 'Semanal':
                            $this->diasfrecumes = null;
                            break;

                        default:
                            $this->diasfrecu = [];
                            $this->diasfrecumes = null;
                            break;
                    }

                    //Condicional para saber si la opcion de nuca esta activo
                    if ($this->nuncafecha == 1) {
                        $finfrecu = null;
                    } else {
                        $finfrecu = $this->fechafrecufin;
                    }


                    //Condicional para saber si hay frecuencia
                    if ($this->frecuentetarea != 'Si') {
                        $this->periodotarea = null;
                        //Limpiamos los dias seleccionados
                        $this->diasfrecu = [];
                        //Quitamos el fecha de final de creacion
                        $finfrecu = null;
                        //Limpiamos el impuesto
                        $this->imputarea = null;
                    }

                    //Crear nueva tarea
                    Tareas::create([
                        'id' => $this->nombretarea . '&' . $dt->format('Y-m-d H:i:s'),
                        'nombreadmin' => auth()->user()->nombre,
                        'rfcadmin' => auth()->user()->RFC,
                        'nombre' => $this->nombretarea,
                        'descripcion' => $descripcion,
                        'nomproyecto' => $infoproyect->Nombre ?? 'Sin proyecto',
                        'rfcproyecto' => $infoproyect->RFC ?? 'Sin proyecto',
                        'fechaentrega' => $this->fechaentregatarea,
                        'prioridad' => $this->prioridadtarea,
                        'frecuencia' => $this->frecuentetarea,
                        'periodo' =>  $this->periodotareaperson ?? $this->periodotarea,
                        'asigntarea' => date('Y-m-d'),
                        'rfccolaborador' => $colaborador->RFC,
                        'nomcolaborador' => $colaborador->Nombre,
                        'tipoimpuesto' => $this->imputarea,
                        'estado' => '0',
                        'diasfrecu' => $this->diasfrecu,
                        'finfrecu' => $finfrecu,
                        'diamesfrecu' => $this->diasfrecumes,
                    ]);
                }
            }

            //Limpiamos las variables
            $this->nombretarea = null;
            $this->descripciontarea = null;
            $this->proyectotarea = null;
            $this->fechaentregatarea = null;
            $this->prioridadtarea = null;
            $this->colaboradortarea = null;
            $this->frecuentetarea = null;
            $this->periodotarea = null;
            $this->imputarea = null;
            $this->colaboradorestarea = [];
            $this->diasfrecu = [];
            $this->fechafrecufin = null;
            $this->nuncafecha = null;
            $this->diasfrecumes = null;

            //Emitimos el refresco de la vista colaboradores
            $this->emit('tareacolabrefresh');

            //Cerramos el modal de agregar tarea
            $this->dispatchBrowserEvent('cerrartarea', []);
        } else {
            //Se enviara un mensaje de error
            $this->dispatchBrowserEvent('errortareas', ['error' => 'Seleccione al menos un colaborador']);
        }
    }

    //Metodo para agregar un colaborador a la tarea
    public function AddColabo()
    {
        if (!empty($this->colaboradortarea)) {
            //Agregar un colaborador a la tarea
            $this->colaboradorestarea[] = $this->colaboradortarea;

            //Limpiamos los repetidos
            $this->colaboradorestarea = array_map("unserialize", array_unique(array_map("serialize", $this->colaboradorestarea)));

            //Limpiar el campo de colaborador
            $this->colaboradortarea = null;
        } else {
            //Se enviara un mensaje de error
            $this->dispatchBrowserEvent('errortareas', ['error' => 'Seleccione un colaborador']);
        }
    }

    //Metodo para eliminar un colaborador de la tarea
    public function RemoveColabo()
    {
        //Eliminamos el dato seleccionado
        if (($Index = array_search($this->colaboradortarea, $this->colaboradorestarea)) !== false) {
            //Eliminamos el colaborador
            unset($this->colaboradorestarea[$Index]);
        }

        //Limpiar el campo de colaborador
        $this->colaboradortarea = null;
    }

    //Metodo para limpiar el periodo seleccionado
    public function NuevoPeriodo()
    {
        //Limpiamos las opciones del select
        $this->periodotarea = "";
        $this->periodotareaperson = "";
    }

    //Metodo de refresco
    public function Refresh()
    {
        //Limpiamos las variables
        $this->idtarea = null;
        $this->idtareaperiod = null;
        $this->nombretarea = null;
        $this->descripciontarea = null;
        $this->proyectotarea = null;
        $this->fechaentregatarea = null;
        $this->prioridadtarea = null;
        $this->frecuentetarea = null;
        $this->periodotarea = null;
        $this->asigntarea = null;
        $this->imputarea = null;
        $this->colaboradorestarea = [];
        $this->diasfrecu = [];
        $this->fechafrecufin = null;
        $this->diasfrecumes = null;
        $this->hidden = 'hidden';
        $this->hiddensema = 'hidden';
        $this->hiddenmes = 'hidden';
    }

    public function render()
    {
        //Condicional para saber si exite un identificador y asi editar
        if (!empty($this->idtarea)) {
            //Realizamos una consulta para obtener los datos
            $consultarea = Tareas::where('_id', $this->idtarea)->first();

            //Llenamos las variables
            $this->idtareaperiod = $consultarea['id'];
            $this->nombretarea = $consultarea['nombre'];
            $this->descripciontarea = $consultarea['descripcion'];
            $this->proyectotarea = json_encode(['RFC' => $consultarea['rfcproyecto'], 'Nombre' => $consultarea['nomproyecto']]);
            $this->fechaentregatarea = $consultarea['fechaentrega'];
            $this->prioridadtarea = $consultarea['prioridad'];
            $this->frecuentetarea = $consultarea['frecuencia'];
            $this->periodotarea = $consultarea['periodo'];
            $this->asigntarea = $consultarea['asigntarea'];
            $this->colaboradorestarea[] = json_encode(['RFC' => $consultarea['rfccolaborador'], 'Nombre' => $consultarea['nomcolaborador']]);
            $this->imputarea = $consultarea['tipoimpuesto'];
            $this->diasfrecu = $consultarea['diasfrecu'];
            $this->fechafrecufin = $consultarea['finfrecu'];
            $this->diasfrecumes = $consultarea['diamesfrecu'];

            //Condicional para saber si hay frecuencia
            if ($this->frecuentetarea == 'Si') {
                $this->hidden = null;
                $this->requiretarea = 'required';
            } else {
                $this->hidden = 'hidden';
                $this->requiretarea = null;
            }

            //Quitamos los campos para agregar colaboradores
            $this->activecolabo = 'hidden';

            //Condicional para no ocultar la seccion de frecuencia
            if ($this->frecuentetarea == 'Si') {
                $this->hidden = null;
                $this->requiretarea = 'required';
            } else {
                $this->hidden = 'hidden';
                $this->requiretarea = null;
            }

            //Condicional para ocultar los input de frecuencia
            switch ($this->periodotarea) {
                case 'Semanal':
                    $this->hiddensema = null;
                    $this->hiddenmes = 'hidden';
                    break;

                case 'Mensual':
                    $this->hiddensema = 'hidden';
                    $this->hiddenmes = null;
                    break;

                default:
                    $this->hiddensema = 'hidden';
                    $this->hiddenmes = 'hidden';
                    break;
            }

            //Condicional para marcar y deshabilitar el inpt de fecha
            if (empty($this->fechafrecufin)) {
                $this->dispatchBrowserEvent('nuncafecha', []);
            }
        } else {
            //Agregamos los campos para agregar colaboradores
            $this->activecolabo = null;
        }

        //Arreglo con las empresas que estan en ceros
        $empreceros = [
            ['RFC' => 'NOALTA-006', 'Nombre' => 'ADMON TOTAL PARA PEQUEÑAS Y MEDIANAS EMPRESAS ASUNCION, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-007',  'Nombre' => 'ESPECIALISTAS EN COMERCIO Y DISTRIBUCIÓN LCM, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-008', 'Nombre' => 'DESARROLLOS ARQUITECTONICOS ESCOBAR Y LOYA, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-009', 'Nombre' => 'MANTENIMIENTO INTEGRALES MULTINACIONAL MRH, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-010', 'Nombre' => 'COMERCIALIZACIONES GLOBAL C2, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-020', 'Nombre' => 'SERVICIOS PROFESIONALES A TU ALCANCE PEÑEIRO, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-012', 'Nombre' => 'SOLUCIONES INTEGRALES, DIES, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-013', 'Nombre' => 'SOLUCIONES Y PROYECCIONES A TU ALCANCE, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-014', 'Nombre' => 'SERVICIO LA RUAVE, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'DIOT' => '1'],
        ];

        //Almacenamos los datos del arreglo en el arreglo $emp
        foreach ($empreceros as $em) {
            $emp[] = $em;
        }

        //Arreglo con las empresas que no estan dados de alta en la base de datos
        $emprenoecont = [
            ['RFC' => 'NOALTA-001', 'Nombre' => 'GERARDO CEDON CORTIZO', 'Impuestos_Federales' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-002', 'Nombre' => 'CONTARAPP', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-003', 'Nombre' => 'SERVICIO HOTELERO THE ALEST, SA DE CV', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-004', 'Nombre' => 'GRUPO HOTELERO PICASSO, SA. DE CV.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-005', 'Nombre' => 'PERMERGRUP, S.C.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],

        ];

        //Almacenamos los datos del arreglo en el arreglo $emp
        foreach ($emprenoecont as $em) {
            $emp[] = $em;
        }

        $e = array();

        $e = DB::Table('clientes')
            ->where('RFC', '!=', 'ADMINISTRADOR')
            ->whereNull('tipo')
            ->whereNull('TipoSE')
            ->get();

        foreach ($e as $em) {
            //Condicional para saber si existe sucursales a las empresas
            if (!empty($em['Sucursales'])) {
                foreach ($em['Sucursales'] as $sucursal) {
                    $emp[] = array(
                        'RFC' => $sucursal['RFC'],
                        'Nombre' => $em['nombre'] . ' ' . $sucursal['Nombre'],
                        'Impuestos_Federales' => $sucursal['ImptoFederal'] ?? null,
                        'Impuestos_Remuneraciones' => $sucursal['ImptoRemuneracion'] ?? null,
                        'Impuestos_Hospedaje' => $sucursal['ImptoHospedaje'] ?? null,
                        'IMSS' => $sucursal['IMSS'] ?? null,
                        'DIOT' => $sucursal['DIOT'] ?? null,
                        'Balanza_Mensual' => $sucursal['BalanMensual'] ?? null,
                    );
                }
            } else {
                $emp[] = array('RFC' => $em['RFC'], 'Nombre' => $em['nombre'] ?? null);
            }
        }

        array_multisort(array_column($emp, 'Nombre'), SORT_ASC, $emp);

        //Obtenenmos los datos de los usuarios (Contadores)
        $consulconta = User::where('tipo', '2')
            ->orwhere('tipo', 'VOLU')
            ->where('nombre', '!=', null)
            ->get(['RFC', 'nombre']);

        return view('livewire.tareanueva', ['empresas' => $emp, 'contadores' => $consulconta]);
    }
}
