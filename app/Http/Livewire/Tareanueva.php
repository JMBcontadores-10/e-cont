<?php

namespace App\Http\Livewire;

use App\Models\Tareas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Tareanueva extends Component
{
    //Variables formulario
    public $nombretarea;
    public $descripciontarea;
    public $proyectotarea;
    public $fechaentregatarea;
    public $prioridadtarea;
    public $colaboradortarea;
    public $frecuentetarea;
    public $periodotarea;
    public $colaboradorestarea = [];

    //Metodo para agregar una nueva tarea
    public function NuevaTarea()
    {
        //Condicional para saber si hay colaboradores seleccionados
        if ($this->colaboradorestarea != null) {
            //Se crea una nueva tarea
            //Validaciones
            $this->validate([
                'nombretarea' => 'required',
                'prioridadtarea' => 'required',
                'colaboradorestarea' => 'required',
            ]);

            //Transformamos a los colaboradores en un array
            foreach ($this->colaboradorestarea as $colaborador) {
                $colaborador = json_decode($colaborador);
                $colaboradores[] = $colaborador;
            }

            //Obtenemos los datos de proyecto
            $infoproyect = json_decode($this->proyectotarea);

            //Crear nueva tarea
            Tareas::create([
                'nombre' => $this->nombretarea,
                'descripcion' => $this->descripciontarea,
                'nomproyecto' => $infoproyect->Nombre ?? null,
                'rfcproyecto' => $infoproyect->RFC ?? null,
                'fechaentrega' => $this->fechaentregatarea,
                'prioridad' => $this->prioridadtarea,
                'frecuencia' => $this->frecuentetarea,
                'periodo' => $this->periodotarea,
                'colaboradores' => $colaboradores,
            ]);

            //Limpiamos las variables
            $this->nombretarea = null;
            $this->descripciontarea = null;
            $this->proyectotarea = null;
            $this->fechaentregatarea = null;
            $this->prioridadtarea = null;
            $this->colaboradortarea = null;
            $this->frecuentetarea = null;
            $this->periodotarea = null;
            $this->colaboradorestarea = [];

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

    public function render()
    {
        //Arreglo con las empresas que no estan dados de alta en la base de datos
        $emprenoecont = [
            ['RFC' => 'NOALTA-001', 'Nombre' => 'GERARDO CEDON CORTIZO', 'Impuestos_Federales' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-002', 'Nombre' => 'CONTARAPP', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-003', 'Nombre' => 'SERVICIO HOTELERO THE ALEST, SA DE CV', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-004', 'Nombre' => 'GRUPO HOTELERO PICASSO, SA. DE CV.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-005', 'Nombre' => 'PERMERGRUP, S.C.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-006', 'Nombre' => 'ADMON TOTAL PARA PEQUEÑAS Y MEDIANAS EMPRESAS ASUNCION, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-007',  'Nombre' => 'ESPECIALISTAS EN COMERCIO Y DISTRIBUCIÓN LCM, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-008', 'Nombre' => 'DESARROLLOS ARQUITECTONICOS ESCOBAR Y LOYA, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-009', 'Nombre' => 'MANTENIMIENTO INTEGRALES MULTINACIONAL MRH, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-010', 'Nombre' => 'COMERCIALIZACIONES GLOBAL C2, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-011', 'Nombre' => 'SERVICIOS PROFECIOANLES A TU ALCANCE PEÑEIRO, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-012', 'Nombre' => 'SOLUCIONES INTEGRALES, DIES, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-013', 'Nombre' => 'SOLUCIONES Y PROYECCIONES A TU ALCANCE, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'Balanza_Mensual' => '1', 'DIOT' => '1'],
            ['RFC' => 'NOALTA-014', 'Nombre' => 'SERVICIO LA RUAVE, S.A. DE C.V.', 'Impuestos_Federales' => '1', 'DIOT' => '1'],
        ];

        //Almacenamos los datos del arreglo en el arreglo $emp
        foreach ($emprenoecont as $em) {
            $emp[] = $em;
        }

        //Obtenemos las empresas del usuario (Administrador)
        if (!empty(auth()->user()->admin)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->get();

                foreach ($e as $em)
                    $emp[] = array('RFC' => $em['RFC'], 'Nombre' => $em['nombre']);
            }
        } else {
            $emp = '';
        }

        //Obtenenmos los datos de los usuarios (Contadores)
        $consulconta = User::where('tipo', '2')
            ->where('nombre', '!=', null)
            ->get(['RFC', 'nombre']);

        return view('livewire.tareanueva', ['empresas' => $emp, 'contadores' => $consulconta]);
    }
}
