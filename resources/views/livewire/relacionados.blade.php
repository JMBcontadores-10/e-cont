<div>


    
 


     @php

      $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);
            $date = $dt->format('Y-m-d');
            $rfc = Auth::user()->RFC;
           $anio = $dt->format('Y');

        $rutaDescarga="/storage/contarappv1_descargas/".$rfc."/".$anio."/Cheques_Transferencias/Documentos_Relacionados/";


     @endphp



        <!-- Modal -->

        <div wire:ignore.self class="modal fade" id="relacionados-{{$datos1->_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-folder-open"> Adicionales</span></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
    <div class="modal-body">

                       <script>Push.Permission.request(); //permisos para el push / </script>

                    <div>
                        @if (session()->has('message'))
                        <script>

    Push.create('Notificación E-cont', {
    body: 'Se cargaron los archivos.',
    icon: 'icon.png',
    timeout: 9000,               // Timeout before notification closes automatically.
    vibrate: [100, 100, 100],    // An array of vibration pulses for mobile devices.
    onClick: function() {
        // Callback for when the notification is clicked.
        console.log(this);
    }
});

$("[data-dismiss=modal]").trigger({ type: "click" });// cerrar modal por data-dismiss.:)



                        </script>


                        @endif
                    </div>







                    <div id="#relacionadosView{{$datos1->_id}}" class="dropzone">
                        <p   class="mt-5 text-center">
                            <p class="pf">Archivos Exsitentes:</p>

                        </p>
                        <p id="files-area">
                            <span id="filesList">
                                <div class="wrapper">


                                    @php $n=1; 
                                    
                                    
       $dateValue = strtotime($datos1->fecha);//obtener la fecha
        $mesfPago = date('m',$dateValue);// obtener el mes
        $anioPago= date('Y',$dateValue);// obtener el año

     
 
                                    @endphp


                         @foreach ($datos1->doc_relacionados as $docs )

                         @php
   $ruta='storage/contarappv1_descargas/'.$datos1->rfc.'/'.$anioPago.'/Cheques_Transferencias/Documentos_Relacionados/'.$mesPago.'/'.$docs.'';


if (file_exists($ruta)) {

$ruta=$ruta;

}else{

$ruta='storage/contarappv1_descargas/'.$datos1->rfc.'/'.$anioPago.'/Cheques_Transferencias/Documentos_Relacionados/'.$docs.'';

}
                         @endphp

                         @if($docs == "")
                            <h4>  No hay archivos </h4>
                         @else
                         <div class="b" id="c{{$n}}">

                            <input id="rutaAdicional" name="ruta-adicionales" type="hidden"
                            value="{{ $rutaDescarga }}">

                            <input id="iden{{$n}}" type="hidden" value="{{ $docs}}" >

                        


             <a class="alert fas fa-file-pdf" target="_blank" href="{{asset($ruta)}}"></a>
                                <span> {{Str::limit(Str::afterLast($docs, '#'), 10); }} <span>


                        <hr>

                  <button   wire:click="eliminar('{{$docs}}')" wire:loading.attr="disabled"  style="margin-top:-20px;" class="fabutton" >
                    <i class="icons fas fa-trash-alt"></i> </button>


                   <!-- <hr>
                            <button style="margin-top:-20px;" class="fabutton" wire:click="" type="submit">
                                <i class="icons fas fa-eye"></i> </button>-->



                         </div>
                         @endif
               @php $n++; @endphp
                         @endforeach


                        </span>
                        </p>
                    </div>

                    <div wire:loading wire:target="eliminar" >
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                          <div></div>
                          <div></div>
                          
                      </div>
                      Eliminando archivo
                      </div>
                    <br>
                    <br>

               




                   


                </div>

            
            </div>
        </div>




    </div>

</div>
    




</div> 



