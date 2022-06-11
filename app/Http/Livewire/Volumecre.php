<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\Volumetrico;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Volumecre extends Component
{
    //Variables globales
    public $dia;
    public $empresa;
    public $sucursales;
    public $nomsucur;


    //Metdodo para eliminar el PDF
    public function ElimPDFVolu()
    {
        $mes = date('m', strtotime($this->dia));
        $anio = date('Y', strtotime($this->dia));
        $espa = new Cheques();

        if (!empty($this->sucursales)) {
            $empresadele = $this->sucursales;

            //Consultamos lo datos de los volumetricos
            $datavolum = Volumetrico::where(['rfc' => $empresadele])
                ->get()
                ->first();

            $path = "contarappv1_descargas/" . $this->empresa . "/" . $anio . "/CRE/" . $espa->fecha_es($mes) . "/" . $this->nomsucur . "/" . $datavolum['volumetrico.' . $this->dia . '.PDFCRE'];
        } else {
            $empresadele = $this->empresa;

            //Consultamos lo datos de los volumetricos
            $datavolum = Volumetrico::where(['rfc' => $empresadele])
                ->get()
                ->first();

            $path = "contarappv1_descargas/" . $this->empresa . "/" . $anio . "/CRE/" . $espa->fecha_es($mes) . "/" . $datavolum['volumetrico.' . $this->dia . '.PDFCRE'];
        }

        Volumetrico::where('rfc', $empresadele)
            ->update([
                'volumetrico.' . $this->dia . '.PDFCRE' => null,
            ]);

        //Elimina el pdf de la carpeta correspondiente
        Storage::disk('public2')->delete($path);

        $this->dispatchBrowserEvent('CerrarVoluCRE', ["dia" => $this->dia]);

        //Emitimos el metodo de refrescar la pagina
        $this->emitUp('volumrefresh');
    }

    //Metodo para vaciar las variables del modal
    public function Refresh()
    {
        //Emitimos el metodo de refrescar la pagina
        $this->emitUp('volumrefresh');
    }

    public function render()
    {
        return view('livewire.volumecre');
    }
}
