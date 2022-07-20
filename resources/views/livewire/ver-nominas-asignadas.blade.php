<div>
 <!-- Modal -->
 <div wire:ignore.self class="modal fade" id="VerNominasAsignadas{{$asignadas->_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"  class="icons fas fa-atention"> Nóminas asignadas</span></h6>
                <button id="mdlEl" type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">


<h6> Este cheque esta asignado a: </h6>
@php
use App\Models\XmlE;

    $xmle=XmlE::
       whereIn('UUID',$this->foliosFiscales)
       ->select('Folio','Fecha','Complemento','Total','Emisor','Serie','UUID')
       ->groupBy('Folio')
        ->orderBy('Folio','Asc')


       ->get();

@endphp
<ul id="ul">
    @foreach ($xmle as $xml)
        @if (isset($xml['Complemento.0.Nomina.FechaPago']))
            @php $fecha= $xml['Complemento.0.Nomina.FechaPago']; @endphp
        @else
            @php $fecha= $xml['Complemento.Nomina.FechaPago'];    @endphp
        @endif
        {{-- @if ($loop->first) --}}

        <li title="Ir a nominas"  wire:click="IrNominas('{{$fecha}}','{{$xml['Emisor.Rfc']}}')" id="li">Nomina : Periodo {{ $xml['Folio'] }} Serie: {{ $xml['Serie'] }}
            FechaPago:{{ $fecha }}</li>






        {{-- @endif --}}
    @endforeach

</ul>

</div> <!-- /.modal-body -->

</div>
</div>

</div>

<style>


#ul {
list-style-type: none;
padding: 0;
margin: 0;
cursor:pointer;
}

#ul #li {
border: 1px solid #ddd;
margin-top: -1px; /* Prevent double borders */
background-color: #f6f6f6;
padding: 12px;
text-decoration: none;
font-size: 11pt;
color: rgb(83, 81, 81);
display: block;
position: relative;
}

#ul #li:hover {
background-color: #eee;
}


</style>





</div>
