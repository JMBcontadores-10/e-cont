<div>






     <!-- Modal -->


     <div wire:ignore.self class="modal fade" id="pdfcheque{{$datos->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"> <span style="text-decoration: none;" class="icons fas fa-balance-scale"> Ver PDF</span></h6>
                    @if($datos->nombrec=="0")
                        <button id="mdlP{{$datos->id}}" type="button" wire:click="refrecar()" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    @else
                        <button id="mdlP{{$datos->id}}" type="button" class="close" data-dismiss="modal" aria-label="Close">
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
    {{--Encazado del modal--}}
    {{-- Nombre de la factura--}}
   <div class="EncabezadoModalChequesYTransf">
       <h4>Factura</h4>
       <h4 class="LblEncabezado"><b>{{$datos->numcheque}}</b></h4>
   </div>

   {{--Texto de archivos existentes--}}
   <div class="ArchExistContenedor">
        <p class="pf LblArchExist"><b>Archivo Exsitente</b></p>
   </div>

   {{--Zona para subir archivos--}}
   <div class="dropzone">
    <p id="files-area">
        <span id="filesList">
            <div class="wrapper" >


                @php $n=1; @endphp

     @if($datos->nombrec =="0")
            <div class="TxtNoArchivos">
                <h4>No hay archivo</h4>
            </div>
      {{---------Input filepond------------}}
        <input   name="editCheque" type="file" id="editCheque{{$datos->id}}"  />
        <input type="hidden"  name="editcheque" wire:model="pdfcheque._id"  />
     @else
     <div class="b" id="c">
        <input id="rutaAdicional" name="ruta-adicionales" type="hidden"value="">
        <input id="iden" type="hidden" value="{{ $datos->nombrec}}">
        @php
            $dateValue = strtotime($datos->fecha);//obtener la fecha
            $mesfPago = date('m',$dateValue);// obtener el mes
            $anioPago= date('Y',$dateValue);// obtener el año
            $ruta='storage/contarappv1_descargas/'.$datos->rfc.'/'.$anioPago.'/Cheques_Transferencias/'.$mes.'/'.$datos->nombrec.'';


            if (file_exists($ruta)) {
                $ruta=$ruta;

            }else{
                $ruta='storage/contarappv1_descargas/'.$datos->rfc.'/'.$anioPago.'/Cheques_Transferencias/'.$datos->nombrec.'';

            }
        @endphp

        <!--Contenedor para eliminar y visualizar PDF-->
        <div class="EncabezadoPDFContainer">
            <a class="DocumentPDF fas fa-file-pdf" target="_blank" href="{{asset($ruta)}}"></a>
        </div>
        <div class="CuerpoNamePDFContainer">
            <span class="SpanNamePDF"> {{Str::limit(Str::afterLast($datos->nombrec, '&'), 10); }} <span>
        </div>

        {{--Condicional para la accion eliminar, cuando el movimiento esta revisado--}}
        @if ($datos->verificado == 0)
        <div class="BotonesPDFContainer">
            <!--Eliminar PDF-->
            <div class="BtnDelPDF" wire:click="eliminar()" wire:loading.attr="disabled">
                <i class="icons fas fa-trash-alt"></i>
            </div>
        </div>
        @endif

        
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

    @if($datos->nombrec=="0")
        <button type="button" name="cierra"  wire:click="refrecar()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    @else
    <button type="button" name="cierra"  wire:click="refrecar()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    @endif
       </div>
  </div>
  </div>

     </div>




  </div>







</div>
