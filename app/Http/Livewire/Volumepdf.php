<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;
use App\Models\Volumetrico;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Volumepdf extends Component
{
    //Variables globales
    public $dia;
    public $empresa;
    public $sucursales;
    public $nomsucur;

    protected $listeners = ['refrashpdfvolu' => '$refresh'];

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

            $path = "contarappv1_descargas/" . $this->empresa . "/" . $anio . "/Volumetricos/" . $espa->fecha_es($mes) . "/" . $this->nomsucur . "/" . $datavolum['volumetrico.' . $this->dia . '.PDFVolu'];
        } else {
            $empresadele = $this->empresa;

            //Consultamos lo datos de los volumetricos
            $datavolum = Volumetrico::where(['rfc' => $empresadele])
                ->get()
                ->first();

            $path = "contarappv1_descargas/" . $this->empresa . "/" . $anio . "/Volumetricos/" . $espa->fecha_es($mes) . "/" . $datavolum['volumetrico.' . $this->dia . '.PDFVolu'];
        }

        Volumetrico::where('rfc', $empresadele)
            ->update([
                'volumetrico.' . $this->dia . '.PDFVolu' => null,
            ]);

        //Elimina el pdf de la carpeta correspondiente
        Storage::disk('public2')->delete($path);

        $this->dispatchBrowserEvent('CerrarVoluPDF', ["dia" => $this->dia]);

        //Emitimos el metodo de refrescar la pagina
        $this->emit('volumrefresh');
        //Emitimos el metodo de refrescar la pagina
        $this->emit('refrashpdfvolu');
    }

    //Metodo para vaciar las variables del modal
    public function Refresh()
    {
        //Emitimos el metodo de refrescar la pagina
        $this->emit('volumrefresh');
    }

    public function render()
    {
        return view('livewire.volumepdf');
    }
}
