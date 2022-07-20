<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\ExpedFiscal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Tareadepto extends Component
{
    //Variables
    public $mestareaadmin;
    public $aniotareaadmin;
    public $departament;
    public $fechafactu;

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

    public function render()
    {
        switch ($this->departament) {
            case 'Contabilidad':
                //Obtenemos los RFC de las empresas de cada colaborador
                $rfccolab = User::where('depto', $this->departament)->get();

                //Obtenemos las empresas del usuario (Administrador)
                if (!empty(auth()->user()->admin)) {
                    $e = array();

                    foreach ($rfccolab as $colabora) {
                        foreach ($colabora->empresas as $empresas) {
                            $rfc = $empresas;

                            $e = DB::Table('clientes')
                                ->where('RFC', $rfc)
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
                        }
                    }
                } else {
                    $emp = '';
                }

                //Ponemos vacio la lista de tareas
                $listafactu = "";
                break;

            case 'Facturación':
                //Lista de los trabajadores perteneciente a la area de Facturacion
                $listafactu = User::where('depto', 'Facturacion')->get();

                //Ponemos vacio la lista de los proyectos (Empreesas)
                $emp = "";
                break;

            default:
                //Ponemos vacio 
                $listafactu = "";
                $emp = "";
                break;
        }

        return view('livewire.tareadepto', ['empresas' => $emp, 'listafactu' => $listafactu]);
    }
}
