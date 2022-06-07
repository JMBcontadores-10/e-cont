<?php

namespace App\Http\Livewire;

use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(6000); //Tiempo limite dado 1 hora

class Monitclient extends Component
{
    //Variables globales
    public $empresa;
    public $fechainic;
    public $fechafin;

    //Variable de consulta de los metadatos emitidos
    public $emitidos;

    //Variable para obtener el RFC seleccionado
    public $rfcinfo;

    public function SendRFCReci($rfc)
    {
        $this->rfcinfo = $rfc;
    }

    public function render()
    {
        //Bandera a emitir para mostrar un alerta en el boton de Facturacion por cliente
        $inconsistencias = 0;

        //Vamos a obtener los RFC recibidos
        $listrecirfc = array(); //Arreglo donde obtendremos los rfc

        $emitidoslist = json_decode($this->emitidos); //Descomponemos Json

        //Ciclo para descomponer los emitidos
        foreach ($emitidoslist as $dataemitidos) {
            $listrecirfc[] = ['RFC' => $dataemitidos->ReceptorRfc, 'Nombre' => $dataemitidos->ReceptorNombre]; //Insertamos los datos en el arreglo
        }

        //Eliminamos los repetidos
        $listrecirfcclean = array_unique(array_column($listrecirfc, 'RFC'));
        $listrecirfc = array_intersect_key($listrecirfc, $listrecirfcclean);

        //Construimos la tabla
        //Variables de contenedor
        $datainfomonit = '';
        $rowinfomonit = [];

        //Variables para obtener el total
        $totalfactu = 0;
        $totalmonto = 0;

        foreach ($listrecirfc as $datametaclient) {
            //Variable de contenedor
            $totalfactuclient = 0; //Cantidad de facturas
            $montofactuclient = 0; //Cantidad de monto

            //Alamacenamos el RFC para enviarlo
            $rfcreci = "'" . $datametaclient['RFC'] . "'";

            //Ciclo para obtener la cantidad de facturas por cliente
            foreach ($emitidoslist as $datametaporhora) {
                if ($datametaclient['RFC'] == $datametaporhora->ReceptorRfc) {
                    $totalfactuclient++;
                    $montofactuclient += $datametaporhora->Total;
                }
            }

            //Obtenemos el total
            $totalfactu += $totalfactuclient; //Cantidad
            $totalmonto += $montofactuclient; //Monto

            //Ingresamos los datos requeridos

            //RFC receptor
            $datainfomonit .= '<td>' . $datametaclient['RFC'] . '</td>';

            //Nombre receptor
            $datainfomonit .= '<td>' . $datametaclient['Nombre'] . '</td>';

            //#Fact emitidas
            $datainfomonit .= '<td>' . $totalfactuclient . '</td>';

            //Monto
            $datainfomonit .= '<td> $ ' . number_format($montofactuclient, 2) . '</td>';

            //Detalle
            $datainfomonit .=
                '<td> <a data-backdrop="static" data-keyboard="false" data-toggle="modal"
                 data-target="#detalleporclient" wire:click="SendRFCReci(' .
                $rfcreci .
                ')" class="icons fas fa-eye"></a> </td>';

            //Condicional para detectar si a un RFC le emiten mas de 10 facturas con monto total menor de 2000
            if ($totalfactuclient >= 10 && $montofactuclient < 3000) {
                //Ponemos el 1 la bandera de incosistencias
                $inconsistencias++;

                //Alamcenamos los datos en el arreglo
                $rowinfomonit[$totalfactuclient . $datametaclient['RFC']] = '<tr style="background-color: #ffc8c8">' . $datainfomonit . '</tr>';
            } else {
                //Alamcenamos los datos en el arreglo
                $rowinfomonit[$totalfactuclient . $datametaclient['RFC']] = '<tr>' . $datainfomonit . '</tr>';
            }

            //Vaciamos la variable para almacenar las otras
            $datainfomonit = '';
        }

        //Ordenamos la tabla
        krsort($rowinfomonit, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

        return view('livewire.monitclient', ['rowinfomonit' => $rowinfomonit, 'totalfactu' => $totalfactu, 'totalmonto' => $totalmonto, 'inconsistencias' => $inconsistencias]);
    }
}
