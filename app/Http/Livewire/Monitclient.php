<?php

namespace App\Http\Livewire;

use Livewire\Component;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(6000); //Tiempo limite dado 1 hora

class Monitclient extends Component
{
    //Variables globales
    public $empresa;

    //Variable de consulta de los metadatos emitidos
    public $emitidos;

    public function render()
    {
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

        return view('livewire.monitclient', ['consulmetaclient' => $listrecirfc]);
    }
}
