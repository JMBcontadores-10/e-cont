<div>






<!--

</a>-->

<span class="tooltip-content">Ajuste</span>
     <!-- Modal -->

     <div wire:ignore.self class="modal fade" id="ajuste{{$datos->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-balance-scale"> Ajuste{{$datos->numcheque}}</span></h6>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true close-btn">×</span>
                     </button>
                 </div>
 <div class="modal-body"><!--modal body -->




    @if ($datos->ajuste==0)
    <p>No existe ajuste </p>
@else
 <p>Se realizo por: ${{ $datos->ajuste }} mxn. </p>
 @endif

 @if($datos->verificado!==1)
 <form  wire:submit.prevent="guardar">
    @csrf
<input type="hidden" name="id" value="{{ $datos->id }}">
<input wire:model.defer="ajuste" type="number" step="any" name="ajuste" class="form-control">

<div wire:loading wire:target="guardar" >
    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
      <div></div>
      <div></div>

  </div>
  Guardando ajuste
  </div>

  <br>
<button type="submit"  wire:click="guardar()" class="btn btn-primary close-modal">Ajustar</button>

     </form>

@endif




 </div>
             </div>
         </div>



 </div>










</div>
