<div>{{-------------[ div principal ]-------------}}



<!-- Modal -->


<div wire:ignore.self class="modal fade" id="recibosnom{{ $datos->Folio }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"> <span style="text-decoration: none;" class="icons fas fa-file-invoice"> Subir Recibo de Nomina recibosnom{{ $datos->Folio }}</span></h6>
                @if($datos->nombrec=="0")
                    <button id="mdlP{{$datos->Folio}}" type="button" wire:click="refrecar()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                @else
                    <button id="mdlP{{$datos->Folio}}" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
               @endif
            </div>

{{--Cerramos la ventana cada vez que hagamos un cambio--}}
<script>
    window.addEventListener('cerrarPdfmodal', event => {
        $("#mdlP"+event.detail.IdCheque).click();
    });
</script>

<div class="modal-body"><!--modal body -->
    <div class="EncabezadoModalChequesYTransf">
        <h4>Recibos de Nomina</h4>
        <h4 class="LblEncabezado"><b>{{$datos->numcheque}}</b></h4>
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
            $dateValue = strtotime($datos['Complemento.0.Nomina.FechaInicialPago']);//obtener la fecha
            $mes = date('m',$dateValue);// obtener el mes
            $anio= date('Y',$dateValue);// obtener el año

            $ruta = "contarappv1_descargas/" .$RFC. "/". $anio."/Nomina/Periodo".$datos->Folio . "/RecibosNomina/RecibosPeriodo".$datos->Folio.".pdf";

          @endphp

{{-- {{$RFC}} {{$datos->Folio}} --}}

@if(!Storage::disk('public2')->exists($ruta))
<div class="TxtNoArchivos">
    <h4>No hay archivo</h4>

</div>
{{---------Input filepond------------}}
<input  name="recibosNomina" type="file" id="recibosNomina{{$datos->Folio}}"  />

{{--
listaRaya{{$datos->Folio}} --}}


@else
<div class="b" id="c">

   @php
       //Obtenemos el nombre original de los PDF

       $ruta = "storage/contarappv1_descargas/" .$RFC. "/". $anio."/Nomina/Periodo".$datos->Folio . "/RecibosNomina/RecibosPeriodo".$datos->Folio.".pdf";

   @endphp

   <!--Contenedor para eliminar y visualizar PDF-->
   <div class="EncabezadoPDFContainer">
       <a class="DocumentPDF fas fa-file-pdf" target="_blank" href="{{asset($ruta)}}"></a>
   </div>
   <div class="CuerpoNamePDFContainer">
       <span class="SpanNamePDF"> {{Str::limit('NominaPeriodo'.$datos->Folio, 10); }} <span>
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
<div class="modal-footer">

<button type="button" name="cierra"  wire:click="$emitTo('nominas','nominarefresh')" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>


   </div>
</div>
</div>

 </div>




</div>




</div>{{-------------[ div principal ]-------------}}
