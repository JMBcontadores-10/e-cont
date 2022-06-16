<div>

<!-- Small modal -->

<div wire:ignore.self class="modal fade bd-example-modal-sm" id="sustraer{{ $datos->_id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
{{$datos->_id}} <br>
{{$totalPagado}}
          </div>




    </div>
  </div>
</div>


</div>
