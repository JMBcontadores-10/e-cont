<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;

class Sustraer extends Component
{

public Cheques $sustraerImporte;
public $totalPagado;


    public function render()
    {
        return view('livewire.sustraer',['datos'=>$this->sustraerImporte, 'totalPagado'=>$this->totalPagado]);
    }
}
