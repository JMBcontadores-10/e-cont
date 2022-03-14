<div>  {{-- inicio div --}}

 <!-- Modal -->

 <div wire:ignore.self class="modal fade" id="poliza{{$datos->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-file-contract"> Póliza&nbsp;{{$datos->numcheque}}</span></h6>
                <button id="poliza" type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
<div class="modal-body"><!--modal body -->

    <script>
        window.addEventListener('cerrarPolizamodal', event => {
            $("#poliza").click();
            if ($('.modal-backdrop').is(':visible')) {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
};

        });
    </script>




<form  wire:submit.prevent="guardar">
@csrf
<input type="hidden" name="id" value="{{ $datos->id }}">
<textarea wire:model.defer="polizaCheque.poliza" cols="20" rows="2" class="form-control"placeholder="Introduce póliza" ></textarea><br>
@error('polizaCheque.poliza')
    <span class="text-red-500 py-3"> {{$message}} </span>
@enderror
<div wire:loading wire:target="guardar" >
<div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
 <div></div>
 <div></div>

</div>
Guardando poliza
</div>

<button type="submit"  wire:click="guardar()" class="btn btn-primary close-modal">Guardar Póliza</button>

</form>






</div>
        </div>
    </div>



</div>






</div>{{-- fin  div --}}
