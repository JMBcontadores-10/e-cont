<div >

<script>


window.addEventListener('PushNotifaction', event => {


    Push.create("E-cont Dice",{
            body: "Cheque creado con exito",
            icon: "img/logo.png",
            timeout: 8000,
            onClick: function () {
                window.focus();
                this.close();
        }
    });




});

    </script>


    <div  wire:ignore.self  class="modal fade  come-from-modal right" id="notifications-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{count($notifications)}} Notificaciones</h4>
                </div>
                <div class="modal-body" style="background-color:rgba(183,217,237,0.3);">










                  @if(auth()->user()->tipo)
                  @foreach ($notifications as $noti)
@php

@endphp

                            <div class="media d-flex align-items-center" >
                              <div class="media-left pr-0">
                               <!-- <div class="avatar mr-1 m-0"><img src="app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="39" width="39"></div>-->
                              </div>
                              <div class="media-body">

                             @if($noti->tipo=="CED")
                             <a  wire:click="verchequeLink('{{$noti->rfc}}','{{$noti->cheques_id}}','{{$noti->_id}}')">
                             <h6 class="media-heading"><span class="text-bold-500">  {{$noti->rfc}}</span> ¡Se Editó el cheque!<br>Factura#:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Fecha de pago:&nbsp;{{$noti->fecha}}<br>


                                {{$noti->created_at->locale('es')->diffForHumans()}}</small>
                             </a>
                             <button wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                                <i class="bx bxs-message-square-x"></i></button>
                       <hr>  <br>

                             @elseif($noti->tipo=="CFA")
                             <a  wire:click="verchequeLink('{{$noti->rfc}}','{{$noti->cheques_id}}','{{$noti->_id}}')">
                             <h6 class="media-heading"><span class="text-bold-500">  {{$noti->rfc}}</span> ¡Se Agregó un cheque, con mes diferente al actual!<br>Factura#:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Fecha de pago:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->locale('es')->diffForHumans()}}</small>
                             </a>
                             <button wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                                <i class="bx bxs-message-square-x"></i></button>
                       <hr>  <br>
                             @elseif($noti->tipo=="CE")

                             <h6 class="media-heading"><span class="text-bold-500">  {{$noti->rfc}}</span> ¡Eliminó un Cheque!<br>Factura#:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Fecha de pago:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->locale('es')->diffForHumans()}}</small>
                             <button wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                                <i class="bx bxs-message-square-x"></i></button>
                       <hr>  <br>

                       @elseif($noti->tipo=="FC")

                       <h6 class="media-heading"><span class="text-bold-500">  {{$noti->rfc}}</span> ¡Se canceló una factura!<br>Factura#:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Fecha Cancelación:&nbsp;{{$noti->fecha}}<br>Cheque Id:&nbsp; @if(is_array($noti->cheques_id)) {{$noti->cheques_id[0]}} @else {{$noti->cheques_id}}    @endif  <br> {{$noti->created_at->locale('es')->diffForHumans()}}</small>
                       <button wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                          <i class="bx bxs-message-square-x"></i></button>
                 <hr>  <br>

                             @elseif($noti->tipo=="M")
                           <a wire:click="notificacionLink('{{$noti->cheques_id}}','{{$noti->emisorMensaje}}' ,'{{$noti->_id}}')">

                           <h6 class="media-heading"><span class="text-bold-500">  {{$noti->emisorMensaje}}</span> ¡Te dejó un mensaje en un cheque!<br>Factura#:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Cheque Id: {{$noti->cheques_id}} <br>Fecha:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->locale('es')->diffForHumans()}}</small>
                           </a>
                           <button wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                            <i class="bx bxs-message-square-x"></i></button>
                   <hr>  <br>


                             @endif

                              </div>



                        </div>


                              {{--
                            <div class="media d-flex align-items-center py-0">
                              <!--<div class="media-left pr-0"><img class="mr-1" src="app-assets/images/icon/sketch-mac-icon.png" alt="avatar" height="39" width="39"></div>-->
                              <div class="media-body">
                                <h6 class="media-heading"><span class="text-bold-500">Updates Available</span></h6><small class="notification-text">Sketch 50.2 is currently newly added</small>
                              </div>
                              <div class="media-right pl-0">
                                <div class="row border-left text-center">
                                  <!--<div class="col-12 px-50 py-75 border-bottom">
                                    <h6 class="media-heading text-bold-500 mb-0">Update</h6>
                                  </div>-->
                                  <div class="col-12 px-50 py-75">
                                    <h6 class="media-heading mb-0">Cerrar</h6>
                                  </div>
                                </div>
                              </div>
                            </div></a><a class="d-flex justify-content-between cursor-pointer" href="javascript:void(0);">

              --}}
              @endforeach


              @elseif(empty(auth()->user()->tipo))
              @foreach ($notifications as $noti)



                        <div class="media d-flex align-items-center" >
                          <div class="media-left pr-0">
                           <!-- <div class="avatar mr-1 m-0"><img src="app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="39" width="39"></div>-->
                          </div>
                          <div class="media-body">

                            @if($noti->folioFiscal!==NULL)


                            {{-- <h6 class="media-heading"><span class="text-bold-500">  {{$noti->numcheque}}</span> ¡Cancelo una factura!<br>FolioFiscal:&nbsp; {{$noti->folioFiscal}}</h6><small class="notification-text">Cheque Id: {{$noti->cheques_id}} <br>  Fecha de cancelación:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->diffForHumans()}}</small> --}}

                             @elseif($noti->tipo=="M")
                                  <a wire:click="notificacionLink('{{$noti->cheques_id}}','{{$noti->receptorMensaje}}','{{$noti->_id}}')">

                             <h6 class="media-heading"><span class="text-bold-500">Tu contador</span> ¡te dejó un mensaje!<br>En :&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Cheque Id: {{$noti->cheques_id}} <br>{{$noti->created_at->diffForHumans()}}</small>
                             </a>

                             @endif
                          </div>

                              <button   wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                                <i class="bx bxs-message-square-x"></i></button>

                        </div><hr><br>


          @endforeach

              @endif



















                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    {{-- <button type="button" class="btn btn-primary"></button> --}}
                </div>
            </div>
        </div>
    </div>


</div>
