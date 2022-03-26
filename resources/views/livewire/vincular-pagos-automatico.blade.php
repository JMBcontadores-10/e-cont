<div><!---  principal-->

    <div wire:ignore.self class="modal fade" id="vinculacionAutomtica" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-plus">&nbsp;Agrega Cheque/Transferencia</span></h6>

                   <button id="mdla" type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span  aria-hidden="true close-btn">×</span>
               </button>


                </div>
      <div class="modal-body"><!--modal body -->



    @if ($numero==0)
{{$numero}}

    <div wire:loading  >
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
          <div></div>
          <div></div>

      </div>
      VINCULANDO  ...
      </div>
      @endif
      <a wire:click="vincularAutomatico()">Vincular Pagos a ppd </a>




    </div>  <!-- fin modal body -->


</div>
</div>
</div>





</div><!-- fin div principal-->
