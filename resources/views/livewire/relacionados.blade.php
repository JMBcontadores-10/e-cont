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
                        <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
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

    {{--Encazado del modal--}}
    {{-- Nombre de la factura--}}
   <div class="EncabezadoModalChequesYTransf">
    <h4>Factura</h4>
    <h4 class="LblEncabezado"><b>{{$datos1->numcheque}}</b></h4>
</div>

{{--Texto de archivos existentes--}}
<div class="ArchExistContenedor">
    @php
    //Realizamos un foreach para acceder al arreglo de los archivos relacionados
    foreach ($datos1->doc_relacionados as $docs ){
        /*Si eliminamos todos los archivos el arreglo no queda vacio queda con un registro
        vacio, por lo que realizaremos an condicional para saber que la cantida de archivos
        es "0"*/

        //Si no hay archivos
        if ($docs == "") {
            $TotalArchivos = 0;
        }
        //Si hay archivos
        else {
            $TotalArchivos = sizeof($datos1->doc_relacionados);
        }
    }
@endphp

{{--Mostramos el total de archivos--}}
    @if (isset($TotalArchivos))
    <p class="pf LblArchExist"><b>Total de archivos adicionales {{$TotalArchivos}}</b></p>
    @else
    <p class="pf LblArchExist"><b>Total de archivos adicionales</b></p>
    @endif
</div>

                    <div id="#relacionadosView{{$datos1->_id}}" class="dropzone">
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
                         <div class="TxtNoArchivos">
                            <h4>No hay archivos</h4>
                        </div>
                         @else
                         <div class="b" id="c{{$n}}">

                            <input id="rutaAdicional" name="ruta-adicionales" type="hidden"
                            value="{{ $rutaDescarga }}">

                            <input id="iden{{$n}}" type="hidden" value="{{ $docs}}" >


        <!--Contenedor para eliminar y visualizar PDF-->
        <div class="EncabezadoPDFContainer">
            <a class="DocumentPDF fas fa-file-pdf" target="_blank" href="{{asset($ruta)}}"></a>
        </div>
        <div class="CuerpoNamePDFContainer">
            <span class="SpanNamePDF">{{Str::limit(Str::afterLast($docs, '&'), 10); }}<span>
        </div>
        {{--Condicional para la accion eliminar, cuando el movimiento esta revisado--}}
        @if ($datos1->verificado == 0)
        <div class="BotonesPDFContainer">
            <!--Eliminar PDF-->
            <div class="BtnDelPDF" wire:click="eliminar('{{$docs}}')" wire:loading.attr="disabled">
                <i class="icons fas fa-trash-alt"></i>
            </div>
        </div>
        @endif

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
                </div>
            </div>
        </div>
    </div>
</div>





</div>



