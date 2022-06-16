<?php

namespace App\Http\Livewire;

use App\Models\ExpedFiscal;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Expediacuse extends Component
{
    //Variables globales
    public $dataacuse;

    //Listener para escuchar los emit de otros componente
    protected $listeners = ['recidataacuse' => 'recidataacuse', 'refreshacuse' => '$refresh', 'uploadacuse' => 'uploadacuse'];

    //Metodo perteneciente al emit donde recibimos la cadena con los datos
    public function recidataacuse($identacuse)
    {
        //Almacenamos el argumento que pasa por nuestra funcion 
        $this->dataacuse = $identacuse;

        //Convertimos el input con el plugin de filepond
        $this->dispatchBrowserEvent('inputfilepond', ['idacuse' => $identacuse]);
    }

    //Metodo para mostrar los archivos cada vez que se suba uno
    public function uploadacuse($data)
    {

        //Convertimos el input con el plugin de filepond
        $this->dispatchBrowserEvent('inputfilepond', ['idacuse' => $data['id']]);
    }

    //Metodo para eliminar los acuses
    public function Eliminar($acuse)
    {
        //Descomponemos la cadena enviada (a un arreglo)
        $iddescompuestos = explode('&', $this->dataacuse);

        //Tipo
        $Tipo = $iddescompuestos[0];

        //Empresa
        $Empresa = $iddescompuestos[1];

        //Mes
        $Mes = $iddescompuestos[2];

        //Año
        $Anio = $iddescompuestos[3];

        //Consultamos lo datos de los volumetricos
        $dataacuse = ExpedFiscal::where(['rfc' => $Empresa])
            ->first();

        //Obtenemos la ruta donde estan los archivos
        $path = 'contarappv1_descargas/' . $Empresa . '/' . $Anio . '/Expediente_Fiscal/' . $Tipo . '/' . $Mes . '/' . $acuse;

        //Eliminamos el dato del arreglo
        $dataacuse->pull('ExpedFisc.' . $Anio . '.' . $Tipo . '.' . $Mes . '.Acuse', $acuse);

        //Elimina el pdf de la carpeta correspondiente
        Storage::disk('public2')->delete($path);

        //Emitimos la accion de cerrar el modal de eliminar
        $this->dispatchBrowserEvent('inputfilepond', ['idacuse' => $this->dataacuse]);
    }

    //Metodo para refrescar los datos de la vista principal
    public function Refresh()
    {
        //Descomponemos la cadena enviada (a un arreglo)
        $iddescompuestos = explode('&', $this->dataacuse);

        //Mes
        $Mes = $iddescompuestos[2];

        //Funcion para emitir el refresco
        $this->emit('uploadcomp', $Mes);
    }

    public function render()
    {
        return view('livewire.expediacuse');
    }
}
