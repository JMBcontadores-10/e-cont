<?php

namespace App\Http\Livewire;

use App\Models\Notificaciones;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationSecction extends Component
{

public $polling;


public function mount(){

   $this->polling=true;

    }



    public function render()
    {

       

        if(!empty(auth()->user()->tipo)){

 $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas

 

    $rfc=auth()->user()->empresas;
    $noti = Notificaciones::
        whereIn('rfc', auth()->user()->empresas)
        ->get();
    
        }else{

            $noti=[];
        }

     
        
        return view('livewire.notification-secction',['notifications'=>$noti,'polling'=>$this->polling]);
    }



public function off(){

    $this->polling=false;
}


}
