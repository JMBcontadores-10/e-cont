<div>






     <!-- Modal -->

     <div wire:ignore.self class="modal fade" id="pdfcheque{{$datos->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"> <span style="text-decoration: none;" class="icons fas fa-balance-scale"> Ver PDF</span></h6>

                    @if($datos->nombrec=="0")
                    <button id="mdlP{{$datos->_id}}" type="button" wire:click="refrecar()"   class="close" data-dismiss="modal" aria-label="Close">
                       
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                        @else
                        
                    <button id="mdlP" type="button"    class="close" data-dismiss="modal" aria-label="Close">
                    
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                    
                    @endif
                         
                </div>


                 <script>



    window.addEventListener('cerrarPdfmodal', event => {

    document.getElementById("mdlP").click();



    });



    //$("[data-dismiss=modal]").trigger({ type: "click" });// cerrar modal por data-dismiss.:)



                 </script>




   <div class="modal-body"><!--modal body -->
      {{$datos->nombrec}}





   <div class="dropzone">
    <p   class="mt-5 text-center">
        <p class="pf">Archivo Exsitente:</p>

    </p>
    <p id="files-area">
        <span id="filesList">
            <div class="wrapper" >


                @php $n=1; @endphp

     @if($datos->nombrec =="0")


        <h4>  No hay archivo </h4>



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
        <div class="BotonesPDFContainer">
            <!--Eliminar PDF-->
            <div class="BtnDelPDF" wire:click="eliminar()" wire:loading.attr="disabled">
                <i class="icons fas fa-trash-alt"></i>
            </div>
        </div>
     </div>
   @endif





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







</div>
