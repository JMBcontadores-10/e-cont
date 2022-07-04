<div><!---  principal-->


    <div wire:ignore.self class="modal fade" id="vinculacionAutomticaTraslados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-sitemap">&nbsp;Vinculación Automática Traslados</span></h6>

                   <button id="mdla" type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span  aria-hidden="true close-btn">×</span>
               </button>


               @if ($empresas=='')

@php
$rfc=auth()->user()->RFC;
@endphp
@else
@php
$rfc="contador";
@endphp

 @endif




                </div>
      <div class="modal-body"><!--modal body -->

        <center>
<!-- seccion del texto -->
<blockquote class="blockquote text-center">
  <p class="mb-0">Puede vincular automaticamente las cartas porte  a su  factura correspondiente.</p>
  <footer class="blockquote-footer">Para esta acción la factura debe estar ya vinculada a un cheque</footer>
</blockquote>
<!-- seccion del texto -->


        <button wire:loading.remove  class="btn btn-success" style="vertical-align:middle">
        <a wire:click="vincularAutomatico('{{$rfc}}')">
            <span>Iniciar Vinculación Automática </span>
        </a>
        </button >




    <div wire:loading   wire:target="vincularAutomatico"  >
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div><br>
          Vinculando cartas porte...
      </div>
      <br>



@if($numero!=0)

      @if(empty($vinculos) )

      <p> No se encontaron cartas porte para vincular</p>
@php
     $this->numero=0;
@endphp

      @else
      {{count($vinculos)}}<br>
      Se vincularon cartas porte a los siguientes cheques:<br>
      @php
      foreach ($vinculo1 as $v) {
      echo "<small>".$v."</small><br>";
      }
           $this->vinculos=[];
            $this->vinculo1=[];
            $this->numero=0;
      @endphp


      @endif
@endif


    </center>

    </div>  <!-- fin modal body -->


</div>
</div>
</div>




</div><!-- fin div principal-->
