<div >
 



    <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="javascript:void(0);" data-toggle="dropdown"><i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i><span class="badge badge-pill badge-danger badge-up">
   
            
      
        <div wire:poll.10s>{{count($notifications)}}</div>
   
    
    </span></a>
        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
          <li class="dropdown-menu-header">
          

            <div class="dropdown-header px-1 py-75 d-flex justify-content-between"><span class="notification-title">{{count($notifications)}} Nuevas Notificaciones</span><span class="text-bold-400 cursor-pointer">{{--Mark all as read--}}....</span></div>
          </li>


     
 

<li class="scrollable-container media-list"><a class="d-flex justify-content-between" href="javascript:void(0);">
 
    @if(auth()->user()->tipo)           
    @foreach ($notifications as $noti)
  

              <div class="media d-flex align-items-center">
                <div class="media-left pr-0">
                 <!-- <div class="avatar mr-1 m-0"><img src="app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="39" width="39"></div>-->
                </div>
                <div class="media-body">

                  <h6 class="media-heading"><span class="text-bold-500">  {{$noti->rfc}}</span> ¡Agrego un nuevo cheque!<br>#Cheque:&nbsp; {{$noti->numcheque}}</h6><small class="notification-text">Fecha de pago:&nbsp;{{$noti->fecha}}<br>{{$noti->created_at->diffForHumans()}}</small>
                </div>

                    <h6 class="media-heading mb-0">Cerrar</h6>

              </div></a><a class="d-flex justify-content-between read-notification cursor-pointer" href="javascript:void(0);">


               

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



@endif    
</li>

             

              
          <li class="dropdown-menu-footer"><a class="dropdown-item p-50 text-primary justify-content-center" href="javascript:void(0)">{{--Read all notifications--}}....</a></li>
        </ul>
      </li>



    
</div>
