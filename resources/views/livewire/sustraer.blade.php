<div >



<!-- Small modal -->

<div   wire:ignore.self  class=" super1  modal  bd-example-modal-sm" id="sustraer{{$periodo}}{{ $sustraerImporte->_id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
<strong> El monto asigando, se restará del importe principal.</strong>

{{-- {{$sustraerImporte->_id}} <br>
{{$totalPagado}}<br>
{{$serie}}<br> --}}
<div wire:loading>
    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
        <div></div>
        <div></div>
    </div>
    <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
</div>

<br> Importe por pagar:<strong> $ {{$totalrestante}}</strong>
 / Limite para asignar:
@if (isset($sustraerImporte->saldo))
<strong> ${{number_format($sustraerImporte->saldo,2)}}</strong>
@else
 <strong> ${{number_format($sustraerImporte->importecheque,2)}}</strong>
 @endif
<input  type="number"   wire:model.defer="importe"  class="form-control" placeholder="Asignar importe ">
@if ($error == 1)

<div  class="alert alert-danger" role="alert"><small><strong>el importe no puede exceder el total pendiente por cubrir ó el importe del cheque seleccionado</strong></small></div>
@elseif ($error == 2)

<div class="alert alert-danger" role="alert">el importe no puede  ser negativo </div>

@endif
<button wire:click="almacenar()" type="submit" class="btn btn-danger btn-sm">Asignar</button>
<!-- sustraer('{{$sustraerImporte->_id}}')-->
          </div> <!-- fin class body-->




    </div>
  </div>
</div>




</div>
