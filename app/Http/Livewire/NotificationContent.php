<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notificaciones;

class NotificationContent extends Component
{


    protected $listeners = [
        'actualizarNoti' => '$refresh',
     ];
    public function render()
    {


        if(!empty(auth()->user()->tipo)){

            $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas



               $rfc=auth()->user()->empresas;
               $noti = Notificaciones::
                whereIn('rfc', auth()->user()->empresas)
                ->orwhereIn('emisorMensaje', auth()->user()->empresas)
                ->where('read_at', 0)
                ->orWhere('tipo','CA')
                ->orWhere('tipo','FC')
                ->orWhere('tipo','M')
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')

                ->get();

                   }elseif(auth()->user()->tipo==NULL){

               $rfc=auth()->user()->empresas;
               $noti = Notificaciones::where('rfc',auth()->user()->RFC)
               ->orWhere('receptorMensaje', auth()->user()->RFC)
               ->where('read_at', 0)
               ->orWhere('tipo','FC')
               ->orWhere('tipo','M')
            //    ->orWhereNotNull('folioFiscal')
               ->orderBy('fecha', 'desc')
               ->orderBy('created_at', 'desc')
               ->get();



                   }else{

                     $noti=[];

                   }







        return view('livewire.notification-content',['notifications'=>$noti]);
    }


  public function cerrarNotificacion($id){


                Notificaciones::
                    where('_id', $id)
                ->update([
                    'read_at' => 1,
                ]);





  }
}
