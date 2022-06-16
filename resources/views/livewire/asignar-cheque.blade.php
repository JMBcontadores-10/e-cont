
<div>{{-------------div principal---------------}}

@php
    //Obtenemos el valor de la fecha del dia de hoy
    $date = date('Y-m-d');
    $activated="disabled";
    $acti="disabled";
    use App\Models\Cheques;

@endphp

<script>

function myFunction(id) {
  // Get the checkbox
  var checkBox = document.getElementById(id);
  // Get the output text
  var b = document.getElementById("boton"+id);

  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    b.style.display = "block";
  } else {
    b.style.display = "none";
  }

}
  </script>



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

{{--
{{$content}}<br>{{$totalPagado}} --}}
valor de input : {{$cont}} <br>
<p id="valueInput"></p>

@if ($content == "icons"  )

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

@php

$suma=0;
$_id=NULL;


foreach($chequesAsig as $id){

$q=Cheques::where('_id', $id)->first();


//// se verifica si existe el campo saldo y si es diferente a cero
if (isset($q->saldo)) {
$suma += $q->saldo;

}else{
    $suma += $q->importecheque;

}



// elseif(isset($q->saldo) && $q->saldo > $totalPagado - $suma){

// $importeMayor = $q->saldo;

// }else{

// $importeMayor = $q->importecheque;
// $_id=$q->_id;

// }


}


@endphp

@if ($suma >$totalPagado )
<div class=" card alert alert-danger" role="alert">

@elseif ($suma < $totalPagado)
<div class="card">
@else
<div class="card alert alert-success" role="alert">
@endif
<div class="card-header">

    <small> <strong> Total pagado: </strong>

         ${{$totalPagado}} /  <strong>Importe:</strong> ${{$suma }}</small>
    @if ($suma == $totalPagado)
    <div style=" position: absolute; left: 280px; width:30px;  ">
    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" > <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
    </svg>
</div>
@endif

    </div>
    @if (count($chequesAsig) >= 1  && $suma >$totalPagado)


    @php

    // echo  $resta=$totalPagado- $suma ;
    //
    //  if (isset($importeMayor->saldo)) {

    //     $resta2 =$importeMayor-$resta;
    //     $importe =$importeMayor->saldo;
    // }else{
    //     $resta2 =$importeMayor->importecheque-$resta;

    //   $importe=  $importeMayor['importecheque'];
    // }
    @endphp
   <small>    Existe diferencia , con este importe debera hacer un ajuste.<br>
    Se va a descontar $ del importe del cheque $
   </small>
    @endif

</div>



<button wire:click="asignar('{{$_id}}','{{$suma}}')" type="button" class="btn btn-secondary" {{$activated}}>Asignar</button>


    <button   wire:click="enviar('{{$datos}}','{{$RFC}}','{{$fechaFinal}}')" data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
        data-keyboard="false" data-target="#nuevo-cheque"
        {{$acti}} class="btn btn-primary glow invoice-create">
        Nuevo Cheque/Transferencia
        </button>
        <div wire:loading>
            <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                <div></div>
                <div></div>
            </div>
            <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
        </div>


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
            <div class="tr table-body-cell">Sustraer</div>
          </div>

        {{-- Cuerpo de la tabla --}}
        @foreach ($Cheques as $i)
            {{-- Obtenemos los datos del XMLRecibidos --}}

            <div class="resp-table-row">
                {{-- UUID --}}
                <div class="table-body-cell">
                    <div class="form-check">


                            <input onclick="myFunction(this.id)" id="{{ $i->_id }}" wire:model="chequesAsignados" class="mis-checkboxes" type="checkbox"
                                id="allcheck" value="{{ $i->_id }}" />



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

                    @if (isset($i->saldo))
                    ${{ number_format($i->saldo, 2) }}
                    @else
                    ${{ number_format($i->importecheque, 2) }}

                    @endif
                </div>
                <div class="table-body-cell">
                    @php $q=Cheques::where('_id', $i->_id)->first();
                    @endphp

<a
    id=""data-toggle="modal"data-controls-modal="#asingnarCheque"
    class="content_true fas fa-square" data-target="#sustraer{{$i->_id}}" >
</a>
                </div>

            </div>

     <livewire:sustraer :sustraerImporte="$i" :totalPagado="$totalPagado" :wire:key="'user-profile-one-'.$i->_id">
        @endforeach
     </div>
  </div>


</div> <!-- end div scroll --->

@else

@if ($content == "content_true" && $granTotal>0)


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

@php
   $suma=Cheques::
         whereIn('_id', $chequesAsig)
         ->where('importecheque','<',$granTotal)
         ->get()->sum('importecheque');
   $importeMayor=Cheques::
         whereIn('_id', $chequesAsig)
         ->where('importecheque','>',$granTotal)
         ->first();

@endphp


@if ($suma >$granTotal || isset($importeMayor))
<div class=" card alert alert-danger" role="alert">

@elseif ($suma <$granTotal )
<div class="card alert alert-warning" role="alert">
@else
<div class="card alert alert-success" role="alert">
@endif
    <div class="card-header" >
    <small>
        Este periodo ya tiene cheques asignados pero la suma del importe  no cubre el total pago<br>
        Puede seguir agregando mas cheques : Diferencia por cubrir ${{$granTotal}}<br>
    <strong> Total pagado: </strong> ${{$granTotal}} /  <strong>Importe:</strong> ${{$suma}}</small>
    @if ($suma ==$granTotal)
    <div style=" position: absolute; left: 580px; width:30px;  ">
    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" > <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
    </svg>
</div>
@endif
    </div>
    @if (isset($importeMayor))
    Se va a descontar ${{$granTotal-$suma}} del importe del cheque ${{$importeMayor['importecheque']}}<strong> presione asignar si esta seguro</strong>
    @elseif(count($chequesAsig) > 1  && $suma >$granTotal)
    Existe diferencia , con este importe deberas hacer un ajuste en Cheques y Transferencias
    @endif

</div>


@if (isset($importeMayor) )
@php echo  $resta=$granTotal- $suma;

    echo "<br>". $resta2 =$importeMayor->importecheque-$resta;
@endphp
<button wire:click="asignar2('{{$importeMayor->_id}}','{{$resta2}}')" type="button" class="btn btn-secondary" {{$activated}}>Asignar</button>

@else
<button wire:click="asignar()" type="button" class="btn btn-secondary" {{$activated}}>Asignar</button>

@endif


<button   wire:click="enviar('{{$datos}}','{{$RFC}}','{{$fechaFinal}}')" data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
    data-keyboard="false" data-target="#nuevo-cheque"
    {{$acti}} class="btn btn-primary glow invoice-create">
    Nuevo Cheque/Transferencia
    </button>

<button wire:click="cheque('{{$folioFiscal}}')" type="button" class="btn btn-success"> Ver cheques asignados</button>

<small>
    <div wire:loading>
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
            <div></div>
            <div></div>
        </div>
        <i class="fas fa-mug-hot"></i> &nbsp;Cargando datos por favor espere un momento....
    </div>
</small>
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

@else{{-----else content and granTotal ----}}
<center>
La suma de los cheques asignados a este perido con cuerdan correctamente con el total pago.

<button wire:click="cheque('{{$folioFiscal}}')" type="button" class="btn btn-success">Ver cheques asignados</button>
<button wire:click="asignar()" type="button" class="btn btn-success">desvincular todo</button>

<center>
@endif{{----end content and granTotal--}}

@endif

<div wire:loading   wire:target="cheque"  >
    <div class="loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div><br>
      Redireccionando...
  </div>

</div><!-- fin modal body -->




</div>
</div>
</div>





<style>

    .checkmark__circle{stroke-dasharray: 166;stroke-dashoffset: 166;stroke-width: 2;stroke-miterlimit: 10;stroke: #7ac142;fill: none;animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards}.checkmark{width: 56px;height: 56px;border-radius: 50%;display: block;stroke-width: 2;stroke: #fff;stroke-miterlimit: 10;margin: 10% auto;box-shadow: inset 0px 0px 0px #7ac142;animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both}.checkmark__check{transform-origin: 50% 50%;stroke-dasharray: 48;stroke-dashoffset: 48;animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards}@keyframes stroke{100%{stroke-dashoffset: 0}}@keyframes scale{0%, 100%{transform: none}50%{transform: scale3d(1.1, 1.1, 1)}}@keyframes fill{100%{box-shadow: inset 0px 0px 0px 30px #7ac142}}

    </style>

{{--
@include('livewire.agregarcheque') --}}




</div>{{----------- fin div principal--------------}}




