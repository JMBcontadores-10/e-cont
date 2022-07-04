<?php

namespace App\Http\Livewire;

use App\Models\ExpedFiscal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Cheques;

class Tareasadmin extends Component
{
    //Variables
    public $mestareaadmin;
    public $aniotareaadmin;
    public $avancetareaadmin;
    public $departament;
    public $active = "hidden";

    //Variable para la captura de impuestos
    public $fechaimpu;

    protected $listeners = [
        'tareaselect' => 'tareaselect',
    ];

    //Metodo para mostrar el modal con el movimiento seleccionado de cheques y transferencias
    public function tareaselect($data)
    {
        //Accedemos a la seccion de tareas
        $this->avancetareaadmin = $data['seccion'];
    }

    //Metodo para marcar un impuesto finalizado
    public function ImpuFin($rfc, $tipo)
    {
        //Convertimos los meses de numero a palabra
        $espa = new Cheques();

        //Almacenamos los datos en la base de datos
        ExpedFiscal::where('rfc', $rfc)
            ->update([
                'rfc' => $rfc,
                'ExpedFisc.' . $this->aniotareaadmin . '.' . $tipo . '.' . $espa->fecha_es($this->mestareaadmin) . '.Declaracion' => $this->fechaimpu,
            ],  ['upsert' => true]);

        //Limpiamos el datos de fecha
        $this->fechaimpu = null;
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

            $e = DB::Table('clientes')
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
                    $emp[] = array('RFC' => $em['RFC'], 'Nombre' => $em['nombre']);
                }
            }
        } else {
            $emp = '';
        }

        //Mostramos los departamentos
        if ($this->avancetareaadmin == 'Departamento') {
            $this->active = null;
        } else {
            $this->active = 'hidden';
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

        return view('livewire.tareasadmin', ['meses' => $meses, 'anios' => $anios, 'empresas' => $emp]);
    }
}
