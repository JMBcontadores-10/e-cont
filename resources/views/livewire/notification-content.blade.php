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


                            <div class="media d-flex align-items-center" >
                              <div class="media-left pr-0">
                               <!-- <div class="avatar mr-1 m-0"><img src="app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="39" width="39"></div>-->
                              </div>
                              <div class="media-body">

                             @if($noti->folioFiscal!==NULL)


                             <h6 class="media-heading"><span class="text-bold-500">  {{$noti->numcheque}}</span> ¡Cancelo una factura!<br>FolioFiscal:&nbsp; {{$noti->folioFiscal}}</h6><small class="notification-text">Cheque Id: {{$noti->cheques_id}} <br>  Fecha de cancelación:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->diffForHumans()}}</small>

                             @elseif($noti->tipo=="CA")
                             <h6 class="media-heading"><span class="text-bold-500">  {{$noti->rfc}}</span> ¡Agrego un nuevo cheque!<br>Factura#:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Fecha de pago:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->diffForHumans()}}</small>

                           @else

                           <h6 class="media-heading"><span class="text-bold-500">  {{$noti->emisorMensaje}}</span> ¡Te dejo un mensaje en un cheque!<br>Factura#:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Cheque Id: {{$noti->cheques_id}} <br>Fecha:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->diffForHumans()}}</small>



                             @endif

                              </div>

                                  <button wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                                    <i class="bx bxs-message-square-x"></i></button>
                            </div> <hr>  <br>




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


                            <h6 class="media-heading"><span class="text-bold-500">  {{$noti->numcheque}}</span> ¡Cancelo una factura!<br>FolioFiscal:&nbsp; {{$noti->folioFiscal}}</h6><small class="notification-text">Cheque Id: {{$noti->cheques_id}} <br>  Fecha de cancelación:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->diffForHumans()}}</small>

                             @elseif($noti->tipo=="M")
                                  <a wire:click="notificacionLink('{{$noti->cheques_id}}')">
                             <h6 class="media-heading"><span class="text-bold-500">Tu contador</span> ¡te dejo un mensaje!<br>En :&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Cheque Id: {{$noti->cheques_id}} <br>{{$noti->created_at->diffForHumans()}}</small>
                                  </a>

                             @endif
                          </div>

                              <button wire:click="cerrarNotificacion('{{$noti->_id}}')"  type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1">
                                <i class="bx bxs-message-square-x"></i></button>

                        </div> <hr>  <br>





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
