
<div><!---  principal-->


    <div wire:ignore.self class="modal fade" id="vinculacionAutomtica" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-sitemap">&nbsp;Vinculacion Automatica</span></h6>

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

        <button wire:loading.remove  class="btn btn-success" style="vertical-align:middle">
        <a wire:click="vincularAutomatico('{{$rfc}}')">
            <span>Iniciar Vinculación Automatica </span>
        </a>
        </button >




    <div wire:loading   wire:target="vincularAutomatico"  >
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div><br>
          Vinculando pagos...
      </div>
      <br>



@if($numero!=0)

      @if(empty($vinculos) )

      <p>No se encontaron pagos para vincular</p>
@php
     $this->numero=0;
@endphp

      @else
      {{count($vinculos)}}<br>
      se vincularon pagos a los siguientes cheques:<br>
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
