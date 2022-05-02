<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Volumetrico;


class Volumepdf extends Component
{
    //Variables globales
    public $dia;
    public $empresa;

    public function render()
    {
        //Consultamos lo datos de los volumetricos
        $datavolum = Volumetrico::where(['rfc' => $this->empresa])
            ->get()
            ->first();

        if ($datavolum) {
            //Consultamos si existe un PDF cargado
            $volupdf = $datavolum['volumetrico.' . $this->dia . '.VoluPDF'];
        }else{
            $volupdf = 0;
        }

        return view('livewire.volumepdf', ['volupdf' => $volupdf]);
    }
}
