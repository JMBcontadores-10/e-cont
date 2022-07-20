<?php

namespace App\Http\Livewire;

use App\Models\ExpedFiscal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Cheques;
use App\Models\User;

class Tareaproyect extends Component
{
    //Variable para la captura de impuestos
    public $mestareaadmin;
    public $aniotareaadmin;
    public $fechaimpu;

    public function render()
    {
        //Arreglo con las empresas que estan en ceros
        $empreceros = [
            [
                'RFC' => 'NOALTA-006',
                'Nombre' => 'ADMON TOTAL PARA PEQUEÑAS Y MEDIANAS EMPRESAS ASUNCION, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-007',
                'Nombre' => 'ESPECIALISTAS EN COMERCIO Y DISTRIBUCIÓN LCM, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-008',
                'Nombre' => 'DESARROLLOS ARQUITECTONICOS ESCOBAR Y LOYA, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-009',
                'Nombre' => 'MANTENIMIENTO INTEGRALES MULTINACIONAL MRH, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-010',
                'Nombre' => 'COMERCIALIZACIONES GLOBAL C2, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-020',
                'Nombre' => 'SERVICIOS PROFESIONALES A TU ALCANCE PEÑEIRO, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-012',
                'Nombre' => 'SOLUCIONES INTEGRALES, DIES, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-013',
                'Nombre' => 'SOLUCIONES Y PROYECCIONES A TU ALCANCE, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-014',
                'Nombre' => 'SERVICIO LA RUAVE, S.A. DE C.V.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 0,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
        ];

        //Almacenamos los datos del arreglo en el arreglo $emp
        foreach ($empreceros as $em) {
            $empceros[] = $em;
        }

        //Arreglo con las empresas que no estan dados de alta en la base de datos
        $emprenoecont = [
            [
                'RFC' => 'NOALTA-001',
                'Nombre' => 'GERARDO CEDON CORTIZO',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 0,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 1,
            ],
            [
                'RFC' => 'NOALTA-002',
                'Nombre' => 'CONTARAPP',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-003',
                'Nombre' => 'SERVICIO HOTELERO THE ALEST, SA DE CV',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-004',
                'Nombre' => 'GRUPO HOTELERO PICASSO, SA. DE CV.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
            [
                'RFC' => 'NOALTA-005',
                'Nombre' => 'PERMERGRUP, S.C.',
                'Cierre_Facturacion' => 0,
                'Impuestos_Remuneraciones' => 0,
                'Impuestos_Estatal' => 0,
                'Impuestos_Hospedaje' => 0,
                'Declaracion_INEGI' => 0,
                'Impuestos_Federales' => 1,
                'Balanza_Mensual' => 1,
                'DIOT' => 1,
                'Cierre_Econt' => 0,
                'Notas_Credito' => 0,
                'Costo_Ventas' => 0,
                'Archivo_Digital' => 0,
                'Conciliacion_Impuesto' => 0,
            ],
        ];

        //Almacenamos los datos del arreglo en el arreglo $emp
        foreach ($emprenoecont as $em) {
            $empnoalta[] = $em;
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
                            'Cierre_Facturacion' => $sucursal['CierreFactu'],
                            'IMSS' => $sucursal['IMSS'],
                            'Impuestos_Remuneraciones' => $sucursal['ImptoRemuneracion'],
                            'Impuestos_Estatal' => $sucursal['ImpuestoEstatal'],
                            'Impuestos_Hospedaje' => $sucursal['ImptoHospedaje'],
                            'Declaracion_INEGI' => $sucursal['DeclaINEGI'],
                            'Impuestos_Federales' => $sucursal['ImptoFederal'],
                            'Balanza_Mensual' => $sucursal['BalanMensual'],
                            'DIOT' => $sucursal['DIOT'],
                            'Cierre_Econt' => $sucursal['CierreEcont'],
                            'Notas_Credito' => $sucursal['EmitCredit'],
                            'Costo_Ventas' => $sucursal['CostoVentas'],
                            'Archivo_Digital' => $sucursal['ArchivoDigit'],
                            'Conciliacion_Impuesto' => $sucursal['ConcImpu'],
                        );
                    }
                } else {
                    $emp[] = array('RFC' => $em['RFC'], 'Nombre' => $em['nombre'] ?? null);
                }
            }
        } else {
            $emp = '';
        }

        //Consultamos los colaboradores
        $consulconta = User::where('tipo', '2')
            ->where('nombre', '!=', null)
            ->whereNull('admin')
            ->get();

        return view('livewire.tareaproyect', ['empresas' => $emp, 'consulconta' => $consulconta, 'empnoalta' => $empnoalta, 'empreceros' => $empreceros]);
    }
}
