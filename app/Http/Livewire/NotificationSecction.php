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


/* Tipo de notificaciones
Cheque Agregado = CA
Notificacion de Mensaje= M
Factura Cancelada = FC

*/


        if(!empty(auth()->user()->tipo)){

 $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas


 $rfc=auth()->user()->empresas;
 $noti = Notificaciones::whereIn('rfc',$rfc)
 ->where('tipo','CA')
  ->where('read_at', 0)




  ->orderBy('fecha', 'desc')
  ->orderBy('created_at', 'desc')

  ->get();


        }elseif(empty(auth()->user()->tipo)){

            $rfc=auth()->user()->empresas;
            $noti = Notificaciones::where('receptorMensaje', 'CDI1801116Y9')
            ->where('read_at', 0)


         //    ->orWhereNotNull('folioFiscal')
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        }else{

            $noti=[];/// array notifiaciones contador vacio

        }








        return view('livewire.notification-secction',['notifications'=>$noti,'rfc'=>auth()->user()->RFC]);
    }

public function actualizar(){

    $this->emitTo('notification-content','actualizarNoti');
}


public function avisoPush(){

    $this->dispatchBrowserEvent('PushNotifaction', []);

}


}
