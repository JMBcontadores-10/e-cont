<div>

<!-- Small modal -->

<div wire:ignore.self class="modal fade bd-example-modal-sm" id="sustraer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Atención</h5>
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> --}}
          </div>
          <div class="modal-body">



 <!-- input que recibe el importe para restar -->
<strong><i class="fas fa-triangle-exclamation"></i> El monto asigando, se restará del importe principal.</strong>

{{-- {{$datos->_id}} <br>
{{$totalPagado}}<br>
{{$serie}}<br> --}}
<div wire:loading>
    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
        <div></div>
        <div></div>
    </div>
    <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
</div>



{{-- Importe por cubrir: $ {{$totalrestante}} --}}
{{$miId}}
<input  type="number" wire:model.defer="importe"  class="form-control" placeholder="Asignar importe ">
<button wire:click="almacenar()" type="submit" class="btn btn-danger btn-sm">Asignar</button>

          </div> <!-- fin class body-->




    </div>
  </div>
</div>


</div>
