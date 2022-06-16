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

    //Variable para la captura de impuestos
    public $fechaimpu;

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

        //aLmacenamos el avance de la tarea en variables
        $this->avancetareaadmin = 'Departamento';
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

        return view('livewire.tareasadmin', ['meses' => $meses, 'anios' => $anios, 'empresas' => $emp,]);
    }
}
