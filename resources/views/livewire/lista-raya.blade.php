<div>

 <!-- Modal -->


 <div wire:ignore.self class="modal fade" id="raya{{ $datos }}{{$fecha}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"> <span style="text-decoration: none;" class="icons fas fa-clipboard-list"> Subir lista de Raya </span></h6>

                    <button wire:click="refreshRaya()" id="mdlP{{$datos}}{{$fecha}}" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
            </div>

{{--Cerramos la ventana cada vez que hagamos un cambio--}}
<script>
    window.addEventListener('cerrarRayamodal', event => {
        $("#mdlP"+event.detail.IdCheque+event.detail.fecha).click();
    });
</script>

<div class="modal-body"><!--modal body -->
    <div class="EncabezadoModalChequesYTransf">
        <h4>Lista de Raya</h4>

    </div>

    {{--Texto de archivos existentes--}}
    <div class="ArchExistContenedor">
         <p class="pf LblArchExist"><b>Archivo Existente</b></p>
    </div>


 {{--Zona para subir archivos--}}
 <div class="dropzone">
    <p id="files-area">
        <span id="filesList">
            <div class="wrapper" >


                @php $n=1;
            $dateValue = strtotime($fecha);//obtener la fecha
            $mes = date('m',$dateValue);// obtener el mes
            $anio= date('Y',$dateValue);// obtener el año


            $rutaa = "storage/contarappv1_descargas/" .$RFC. "/". $anio."/Nomina/Periodo".$datos . "/Raya/NominaPeriodo".$datos.".pdf";
            $dirname = "storage/contarappv1_descargas/" .$RFC. "/". $anio."/Nomina/Periodo".$datos. "/Raya";
          @endphp

{{-- {{$RFC}} {{$datos->Folio}} --}}

@if(!Storage::disk('public2')->exists($ruta))
<div class="TxtNoArchivos">
    <h4>No hay archivo</h4>

</div>
{{---------Input filepond------------}}
<input  name="listaRaya" type="file" id="listaRaya{{$datos}}{{$anio}}"  />

{{--
listaRaya{{$datos->Folio}}
--}}


@else
<div class="b" id="c">

   @php
       //Obtenemos el nombre original de los PDF



   @endphp

   <!--Contenedor para eliminar y visualizar PDF-->
   <div class="EncabezadoPDFContainer">
       <a class="DocumentPDF fas fa-file-pdf" target="_blank" href="{{asset($ruta)}}"></a>
   </div>
   <div class="CuerpoNamePDFContainer">
       <span class="SpanNamePDF"> {{Str::limit('Periodo'.$datos, 10); }} <span>
   </div>


   <div class="BotonesPDFContainer">
    <!--Eliminar PDF-->
    <div class="BtnDelPDF" wire:click="eliminar('{{$rutaa}}','{{$datos}}','{{$dirname}}','{{$fecha}}')" wire:loading.attr="disabled">
        <i class="icons fas fa-trash-alt"></i>
    </div>
</div>



</div>

@endif
</span>
</p>
 </div>

</div>

{{-- Pantalla de carga al eliminar un archivo--}}
<div wire:loading wire:target="eliminar">
<div class="PantallaCarga">
   <div style="color: #3CA2DB" class="DisplayElimCarga la-ball-clip-rotate-multiple">
       <div></div>
       <div></div>
   </div>
   Eliminando archivo
</div>
</div>

{{--Parte inferior del modal--}}

</div>
</div>

 </div>




</div>




</div>
