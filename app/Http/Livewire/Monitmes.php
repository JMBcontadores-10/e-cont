<?php

namespace App\Http\Livewire;

use App\Models\MetadataE;
use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(6000); //Tiempo limite dado 1 hora

class Monitmes extends Component
{
    //Variables globales
    public $empresa;

    //Variables del rango de facturas por mes
    public $factumesselect;
    public $factuanioselect;
    public $diames = [];

    //Variables para los totales
    public $totalmontomes = 0;
    public $totalcantimes = 0;

    public function mount()
    {
        //Establecemos el mes y año en las facturas por mes
        $this->factumesselect = date('m');
        $this->factuanioselect = date('Y');
    }

    //Metodo para obtener las facturas por mes
    public function ConsulMetaMes()
    {
        if ($this->empresa) {

            //Ponemos a cero las variables
            $this->totalcantimes = 0;
            $this->totalmontomes = 0;

            //Variables de contenedor
            $datametames = "";
            $rowmetames = array();

            //Obtenemos el total de dias
            //Armamos la fecha de referencia
            $fechaselect = $this->factuanioselect . "-" . $this->factumesselect . "-" . "01";

            //Obtenemos el total
            $totaldiasmes = date('t', strtotime($fechaselect));

            //Creamos la fecha final del mes
            $fechaselectfin = $this->factuanioselect . "-" . $this->factumesselect . "-" . $totaldiasmes;

            //Realizaremos una consulta de los metadatos emitidos en el mes
            $infometaemitmes = MetadataE::where('emisorRfc', $this->empresa)
                ->whereBetween('fechaEmision',  [$fechaselect . 'T00:00:00', $fechaselectfin . 'T23:59:59'])
                ->get(['fechaEmision', 'total']);

            //En un ciclo obtendremos los datos necesarios
            for ($i = 1; $i <= $totaldiasmes; $i++) {
                //Variable contadora de facturas emitidas
                $cantiemitmes = 0;

                //Variable que obendra el monto
                $montoemitmes = 0;

                foreach ($infometaemitmes as $datametemes) {
                    //Obtenemos el dia de la fecha de la factura
                    $diafactuemit = date('j', strtotime($datametemes['fechaEmision']));

                    //Condicional para saber si coincide con el dia
                    if ($diafactuemit == $i) {
                        $cantiemitmes++; //Si coincide aumenta el conteo
                        $montoemitmes += floatval($datametemes['total']);
                    }
                }

                //Sacamos los totales de la cantidad y los montos
                $this->totalcantimes += $cantiemitmes;
                $this->totalmontomes += $montoemitmes;

                //Ingresamos los datos requeridos
                //Dia
                $datametames .= '<td>' . $i . '</td>';

                //Cantidad
                $datametames .= '<td>' . $cantiemitmes . '</td>';

                //Monto
                $datametames .= '<td> $ ' . number_format($montoemitmes, 2) . '</td>';

                //Monto (Oculto)
                $datametames .= '<td hidden>' . $montoemitmes . '</td>';

                //Alamcenamos los datos en el arreglo
                $rowmetames[] =  '<tr>' . $datametames . '</tr>';

                //Vaciamos la variable para almacenar las otras
                $datametames = "";
            }

            $this->dispatchBrowserEvent('cargagrafic', []);

            return $rowmetames;
        }
    }

    public function render()
    {
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

        //Almacenamos el contenido de facturacion por mes
        $factumes = $this->ConsulMetaMes();

        return view('livewire.monitmes', ['meses' => $meses, 'anios' => $anios, 'consulmetames' => $factumes]);
    }
}
