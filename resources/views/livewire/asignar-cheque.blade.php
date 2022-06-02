
<div>{{-------------div principal---------------}}

@php
    //Obtenemos el valor de la fecha del dia de hoy
    $date = date('Y-m-d');
    $activated="disabled";
    $acti="disabled";

@endphp





<div wire:ignore.self class="modal fade" id="asignarCheque{{ $datos }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"> <span style="text-decoration: none;" class="icons fas fa-money-check"> asignarCheque perido {{ $datos }}</span></h6>

                    <button id="mdlP{{$datos}}" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>

            </div>
{{-- {{$datos}} {{$RFC}}  {{$fechaFinal}} --}}
{{--Cerramos la ventana cada vez que hagamos un cambio--}}
<script>

    window.addEventListener('cerrarPdfmodal', event => {
        $("#mdlP"+event.detail.IdCheque).click();
    });

</script>
{{-- {{var_dump($chequesAsignados)}} --}}
<div class="modal-body"><!--modal body -->
{{-- <p> contado  {{$cont}}</p> --}}
   {{-- Boton para crear un nuevo cheque --}}
{{-- {{$objeto['Complemento.0.Nomina.FechaFinalPago']}}<BR>
    {{$objeto['Folio']}}<BR> --}}
        {{-- {{$RFC}}<BR> --}}

@if ( count($chequesAsig) > 0)
 @php
  $activated="enabled";
 @endphp
@endif

@if ( count($chequesAsig) == 0)
 @php
  $acti="enabled";
 @endphp
@endif

   <button wire:click="asignar()" type="button" class="btn btn-secondary" {{$activated}}>Asignar</button>

    <button   wire:click="enviar('{{$datos}}','{{$RFC}}','{{$fechaFinal}}')" data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
        data-keyboard="false" data-target="#nuevo-cheque"
        {{$acti}} class="btn btn-primary glow invoice-create">
        Nuevo Cheque/Transferencia
        </button>



 <div style=" height: 200px; overflow-y: scroll;">
  {{-- Generacion de la tabla --}}
  <div id="resp-table">
    <div id="resp-table-body">
        {{-- Encabezado de la tabla --}}
         <div class="resp-table-row">
            <div class="tr table-body-cell"></div>
            <div class="tr table-body-cell">Fecha</div>
            <div class="tr table-body-cell">Nombre</div>
            {{-- <div class="tr table-body-cell">Emisor</div> --}}
            <div class="tr table-body-cell">Beneficiario</div>
            <div class="tr table-body-cell">Importe</div>
          </div>

        {{-- Cuerpo de la tabla --}}
        @foreach ($Cheques as $i)
            {{-- Obtenemos los datos del XMLRecibidos --}}

            <div class="resp-table-row">
                {{-- UUID --}}
                <div class="table-body-cell">
                    <div class="form-check">

                        <div wire:ignore id="checkbox-group" class="checkbox-group">
                            <input wire:model="chequesAsignados" class="mis-checkboxes" type="checkbox"
                                id="allcheck" value="{{ $i->_id }}" />

                        </div>
                    </div>
                </div>

                {{-- Fecha emision --}}
                <div class="table-body-cell">{{ $i->fecha}}</div>

                {{-- Emisor --}}
                <div class="table-body-cell">{{ $i->numcheque }}</div>

                {{-- Concepto --}}
                <div class="table-body-cell">
                    {{ $i->Beneficiario }}
                </div>

                {{-- Metodo/Pago --}}
                <div class="table-body-cell">
                    ${{ number_format($i->importecheque, 2) }}
                </div>


            </div>
        @endforeach
     </div>
  </div>

</div> <!-- end div scroll --->





</div><!-- fin modal body -->




</div>
</div>
</div>







{{--
@include('livewire.agregarcheque') --}}




</div>{{----------- fin div principal--------------}}


