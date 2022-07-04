<div>
 <!-- Modal -->
 <div wire:ignore.self class="modal fade" id="VerNominasAsignadas{{$asignadas->_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"  class="icons fas fa-atention"> Nominas asignadas</span></h6>
                <button id="mdlEl" type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">


<h6> Este cheque esta asignado a: </h6>
@foreach ($xmle as $xml )
@if ($loop->first)
Nomina :{{$xml->Folio}} Emisor: {{$xml['Emisor.Nombre']}}
@endif
@endforeach


      </div> <!-- /.modal-body -->

    </div>
</div>

</div>




</div>

