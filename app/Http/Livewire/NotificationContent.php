<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notificaciones;
use Illuminate\Contracts\Session\Session;


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
            $noti = Notificaciones::whereIn('rfc',$rfc)
            ->orWhereIn('emisorMensaje',$rfc)

             ->where('read_at', 0)

             ->orderBy('fecha', 'desc')
             ->orderBy('created_at', 'desc')

                ->get();

                   }elseif(empty(auth()->user()->tipo)){

               $rfc=auth()->user()->empresas;
               $noti = Notificaciones::Where('receptorMensaje',auth()->user()->RFC )
               ->where('read_at', 0)


            //  ->orWhereNotNull('folioFiscal')
               ->orderBy('fecha', 'desc')
               ->orderBy('created_at', 'desc')
               ->get();



                   }else{

                     $noti=[];

                   }







        return view('livewire.notification-content',['notifications'=>$noti]);
    }


    public function notificacionLink($id,$rfc,$idNoti){

        session()->put('idns', $id);
        session()->put('rfcn', $rfc);

        Notificaciones::
            where('_id', $idNoti)
        ->update([
            'read_at' => 1,
        ]);

        return redirect()->to('/chequesytransferencias');
    }

  public function cerrarNotificacion($id){


                Notificaciones::
                    where('_id', $id)
                ->update([
                    'read_at' => 1,
                ]);

  }

// public function notificacionLink($id){

//     session()->flash('id', $id);

//     return redirect()->to('/chequesytransferencias');
// }

public function verchequeLink($rfc,$id,$idNoti){


    session()->put('rfc', $rfc);
    session()->put('id', $id);

    Notificaciones::
        where('_id', $idNoti)
    ->update([
        'read_at' => 1,
    ]);


    return redirect()->to('/chequesytransferencias');
}



}
