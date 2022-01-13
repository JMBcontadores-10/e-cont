<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;

class Cheque extends Component
{


    public int $perPage=10;
    public $search = '';


    public function render()
    {
       
        
        return view('livewire.cheque')
        ;
    }
}
