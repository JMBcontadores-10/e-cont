<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use DateTime;
use DateTimeZone;
use Livewire\Component;

class Poliza extends Component
{

    public Cheques $polizaCheque; // coneccion al model cheques



    /////////////////////// funcion rules necesaria para validar datos en tiempo real
    //////////////////////comparandolos con la base datos (siempre con livewire)
    protected function rules()
    {

        return [


            'polizaCheque.poliza' => 'required|',


        ];
    }




    public function guardar()
    {

        $this->validate();

        //Agregamos los campos contabilizados
        $dtz = new DateTimeZone("America/Mexico_City"); //Establecemos la zona horaria
        $dt = new DateTime("now", $dtz); //Obtenemos los datos de la fecha de hoy

        //Actualizamos los datos de la base de cheques
        Cheques::where('_id', $this->polizaCheque->_id)->update([
            'conta' => 1, //Ponemos en 1 el campo de conta
            'contabilizado_fecha' => $dt->format('Y-m-d\TH:i:s'), //Establecemos el formato
        ]);

        $this->polizaCheque->save(); // guarda todos los campos
        $this->emitTo('chequesytransferencias', 'chequesRefresh');
        $this->dispatchBrowserEvent('cerrarPolizamodal', []);
    }

    public function render()
    {
        return view('livewire.poliza', ['datos' => $this->polizaCheque]);
    }
}
