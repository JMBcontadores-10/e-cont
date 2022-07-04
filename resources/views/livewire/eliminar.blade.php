<div>



    @php
  $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $date = $dt->format('Y-m-d');
        use App\Models\MetadataE;

 @endphp

<script>
    window.addEventListener('cerrarEliminar', event => {

        document.getElementById("mdlEl").click();
        if ($('.modal-backdrop').is(':visible')) {
  $('body').removeClass('modal-open');
  $('.modal-backdrop').remove();
};

      });

        </script>


    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="eliminar-{{$datos->_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"  class="icons fas fa-atention"> Eliminar</span></h6>
                    <button id="mdlEl" type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">
                @if($errors->any())
                <div class="alert alert-danger d-block mt-3"> </div>
                <ul>
                @foreach  ($errors->all() as $error)
                <li> {{$error}} </li>
                @endforeach
                </ul>
               </div>
               @endif

               @if (session()->has(''))

               <script>
                  $(document).ready(function() {
   $('#editar-{{$datos->_id}}').modal("hide")
         });

                   </script>
             @endif

             <script>

function miFunc() {
    location.reload();
  }
                  </script>


                    <form  wire:submit.prevent="eliminar">
                        @csrf
                       <h5> ¿Seguro que deseas eliminar este cheque?</h5>
                   <input type="hidden" wire:model="eliminarCheque._id">
                   @if($datos->nombrec !="0" || $datos->doc_relacionados[0] !=null || $numVinculados!=0 )
                      <p><strong> Se realizarán las siguientes acciones: </strong>  </p><br>
                      @endif
                      <ul>
                        @php 
                         
                        $nomina= MetadataE:: where('cheques_id', $datos->_id)->where('estado','!=','Cancelado')->first();
                      
                        @endphp
                        @if (isset($nomina->folioFiscal))
                        <li>Este cheque esta asociado a una nomina (Se desviculará). </li>
                        @endif

                         @if ($datos->nombrec !="0")
                         <li>Se eliminará el PDF principal </li>
                         @endif
                         @if (!$datos->doc_relacionados[0] ==null )
                         <li>Se eliminará(n) lo(s) {{count($datos->doc_relacionados)}} documentos adicionales </li>
                         @endif
                         @if ($numVinculados!=0)
                         <li>Tienes {{$numVinculados}} factura(s) viculada(s), regresará a tu módulo de cuentas por pagar </li>
                         @endif

                      </ul>

                  @if($datos->nombrec !="0" || $datos->doc_relacionados[0] !=null)
                  <hr>
                  <p>
                  Atención: E-cont no se hace responsable por los archivos que se van a eliminar.<br>
                  Si no cuentas con un respaldo de tus PDF, puedes hacerlo antes de eliminar este cheque.
                  </p>

                  <a href="{{ url('zip-download', ['idguest' => $datos->_id])}}"> Descarga una copia de tus archivos </a>
                   @endif

                <div wire:loading wire:target="eliminar" >
                    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                      <div></div>
                      <div></div>

                  </div>
                  Eliminando Cheque...
                  </div>
                <div class="modal-footer">

                    <button type="submit"  wire:click="eliminar()"   class="btn btn-primary close-modal">Si</button>
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">No</button>
                </div>
            </form>

            </div>

        </div>
    </div>





</div>

