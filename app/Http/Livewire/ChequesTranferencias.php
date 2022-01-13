<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;


class ChequesTranferencias extends Component
{
    public function render()
    {

        
         $rfc = Auth::user()->RFC;

         $cheque = Cheques::
        search($this->search)
        ->where('rfc',$rfc)
        ->paginate($this->perPage);
        return view('livewire.cheques-tranferencias',['datos' =>  "hola"]);
     
    }
}
