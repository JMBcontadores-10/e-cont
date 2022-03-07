<?php

namespace App\Http\Livewire;

use App\Models\Notificaciones;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationSecction extends Component
{

    protected $listeners = ['avisoPush' => 'avisoPush' ]; // listeners para refrescar el modal

    public function render()
    {


        if(!empty(auth()->user()->tipo)){

 $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas

 

    $rfc=auth()->user()->empresas;
    $noti = Notificaciones::
        whereIn('rfc', auth()->user()->empresas)
        ->where('read_at', 0)
        ->get();
    
        }else{

            $noti=[];
        }
        
        return view('livewire.notification-secction',['notifications'=>$noti]);
    }

public function actualizar(){

    $this->emitTo('notification-content','actualizarNoti');
}


public function avisoPush(){

    $this->dispatchBrowserEvent('PushNotifaction', []);

}


}
