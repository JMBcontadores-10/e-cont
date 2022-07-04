
<div>{{-------------div principal---------------}}

@php
/// importar clase nomina
use App\Http\Classes\Nomina;
$Nomina = new Nomina(); /// objeto
    //Obtenemos el valor de la fecha del dia de hoy
    $date = date('Y-m-d');
    $activated="disabled";
    $acti="disabled";
    use App\Models\Cheques;
    use App\Models\MetadataE;

    $_id=NULL;
    $nomna="nomina".$serie.$datos;
    $tabActive="";

    $totalPagado = $Nomina::TotalPago($RFC, $serie, $asignarCheque,$mes);

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


function ImporteTemp(){

    alert("holaa");
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
{{-- {{$RFC}} {{$serie}}<br> --}}
{{-- Total pagado: {{ $tpagado=$Nomina::TotalPago($RFC, $serie, $asignarCheque,$mes)}}<br> --}}

{{-- {{var_dump($chequesAsignados)}} --}}
<!--modal body -->
{{-- <p> contado  {{$cont}}</p> --}}
   {{-- Boton para crear un nuevo cheque --}}
{{-- {{$objeto['Complemento.0.Nomina.FechaFinalPago']}}<BR>
    {{$objeto['Folio']}}<BR> --}}
        {{-- {{$RFC}}<BR> --}}

{{--
{{$content}}<br>{{$totalPagado}} --}}
{{-- valor de input : {{$cont}} <br> --}}

@php $tpagado=$Nomina::TotalPago($RFC, $serie, $asignarCheque,$mes);
/// retona un uuid asociado a la nomina para sacar los cheques vinculados

$idschequesVinculados=$Nomina::TotalPago($RFC, $serie, $asignarCheque,$mes,'retornaUUID');
 //// checar si hay cheques vinculados

@endphp



<div class="modal-body">
{{--To   {{$granTotal}}
@if (isset($cheques_asociados->cheques_id))
{{var_dump($cheques_asociados->cheques_id)}}

@endif --}}
<strong> Total de la nomina: ${{number_format($granTotal,2)}} </strong>

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



foreach($chequesAsig as $id){

   $importeAsig= $Nomina->importesTemporales($this->temporales, $id);

$q=Cheques::where('_id', $id)->first();

// echo "aqui".$q->$nomna;

//// se verifica si existe el campo nomina
if (isset($q->$nomna)) {
    if($importeAsig >0 ){
$suma += $importeAsig;
    }else{$suma += $q->$nomna;    }

}elseif(isset($q->saldo)){
 if($importeAsig >0 ){
$suma += $importeAsig;
    }else{
    $suma += $q->saldo;
    }
}else{
    if($importeAsig >0 ){
$suma += $importeAsig;
    }else{
    $suma += $q->importecheque;
    }

}
#########################################
//////  sacar el total a apagar de la nomina


#########################################

}


@endphp
{{isset($tpagado)}}

@if ($suma >$totalPagado )
<div class=" card alert alert-danger" role="alert">

@elseif ($suma < $totalPagado)
<div class="card">
@else
<div class="card alert alert-success" role="alert">
@endif
<div class="card-header">

    <small> <strong> Total por cubrir: </strong>

         ${{number_format($totalPagado,2)}} /  <strong>Importe(s):</strong> ${{number_format($suma,2) }}</small>
    @if ($suma == $totalPagado)
    <div style=" position: absolute; left: 280px; width:30px;  ">
    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" > <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
    </svg>
</div>
@endif

    </div>
    @if (count($chequesAsig) >= 1  && $suma >$tpagado)


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
   <small> Existe diferencia , con este importe debera hacer un ajuste.<br>
    Se va a descontar  del importe del cheque
   </small>
    @endif

</div>

@if ($tpagado == 0)


<center>
La suma de los cheques asignados a este perido con cuerdan correctamente con el total pago.
<h5>  <br><bold><small> Total cubierto: </small> $ {{number_format($granTotal,2)}} </bold> </h5>
</center>
@endif
        <div class="invoice-create-btn mb-1">

            @if ($tpagado != 0)
            {{-- Boton paranuevo cheque --}}

<button   wire:click="enviar('{{$datos}}','{{$RFC}}','{{$fechaFinal}}')" data-toggle="modal"
data-controls-modal="#nuevo-cheque" data-backdrop="static"
data-keyboard="false" data-target="#nuevo-cheque"
{{$acti}} class="btn btn-primary ">
Nuevo Cheque / Tranferencia
</button>
@endif
            {{-------------- Boton para vincular traslados ------------------------------}}
            @php

            $arreglo_cheques=MetadataE::where('folioFiscal', $idschequesVinculados)->first();
       @endphp

            <section id="basic-tabs-components">
                <div class="card">
<!-- referencvia pagina de iconos  https://coderthemes.com/minton/layouts/default/icons-boxicons.html?  -->
                  <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                      @if ($tpagado != 0)
                       @php $tabActive= "active";@endphp
                        <li class="nav-item">
                        <a class="nav-link {{$tabActive}} " id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab"
                          aria-selected="true">
                          <i class="bx bx-git-repo-forked align-middle"></i>
                          <span class="align-middle">Cheques para Vincular</span>
                        </a>
                      </li>
                      @endif
                      @if ($tpagado == 0)
                      @php $tabActive= "active";@endphp
                      <li class="nav-item">
                        <a class="nav-link {{$tabActive}}" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab"
                          aria-selected="false">
                          <i class="bx bx-git-pull-request align-middle"></i>
                          <span class="align-middle">Desvincular Cheques</span>
                          @if (isset($arreglo_cheques->cheques_id) && count($arreglo_cheques->cheques_id) > 0 )
                          <span class="badge badge-light">@php echo count($arreglo_cheques->cheques_id)  @endphp</span>
                          @endif
                        </a>
                      </li>
                      @else
                      <li class="nav-item">
                        <a class="nav-link " id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab"
                          aria-selected="false">
                          <i class="bx bx-git-pull-request align-middle"></i>
                          <span class="align-middle">Desvicular Cheques</span>
                          @if (isset($arreglo_cheques->cheques_id) && count($arreglo_cheques->cheques_id) > 0 )
                          <span class="badge badge-light">@php echo count($arreglo_cheques->cheques_id)  @endphp</span>
                          @endif
                        </a>
                      </li>


                      @endif

                      <li class="nav-item">



                      </li>
                    </ul>
                    <div class="tab-content">

                        @if ($tpagado != 0)
                        @php $tabActive= "active";@endphp

                      <div class="tab-pane {{ $tabActive}}" id="home" aria-labelledby="home-tab" role="tabpanel">

                        <!--- [ tabla cheques para vincular] --->

            {{-- ------------Boton para vinculacion ---------- --}}

            <button wire:click="asignar('{{$tpagado}}','{{$_id}}','{{$suma}}')"
             type="button" class="btn btn-secondary btn-sm" {{$activated}}>
             Asignar</button>

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
                 @php
                     $valor=0;
                 @endphp
                 {{-- Metodo/Pago --}}
                <div  class="table-body-cell">


@php
// echo var_dump($this->temporales)."<br>";
// echo $i->_id;
$valor = $Nomina->importesTemporales($this->temporales, $i->_id);
//   echo "importe".  $Nomina->importesTemporales($this->temporales, $i->_id);
@endphp

                    @if ($i->$nomna!==null   )
                    ${{ number_format($i->$nomna - $valor, 2) }}
                    @if ($valor >0)
                     &nbsp;/ &nbsp; <strong style="color:royalblue"> ${{ number_format( $valor, 2) }}
                     <a style="color:royalblue; padding: 0px 5px 0px 5px;"
                        class="parpadea fas fa-exclamation" onclick="ImporteTemp()"
                       ></a></strong>
                    @endif
                    @elseif(isset($i->saldo) && $i->saldo != 0)
                    ${{ number_format($i->saldo - $valor, 2) }}
                    @if ($valor >0)
                    &nbsp;/ &nbsp; <strong style="color:royalblue"> ${{ number_format( $valor, 2) }}
                        <a style="color:royalblue; padding: 0px 5px 0px 5px;"
                        class="parpadea fas fa-exclamation" onclick="ImporteTemp()"
                       ></a>
                    </strong>
                   @endif
                    @else
                    ${{ number_format($i->importecheque - $valor, 2) }}
                    @if ($valor >0)
                    &nbsp;/ &nbsp; <strong style="color:royalblue"> ${{ number_format( $valor, 2) }}
                        <a style="color:royalblue; padding: 0px 5px 0px 5px;"
                        class="parpadea fas fa-exclamation"  onclick="ImporteTemp()"
                       ></a>
                    </strong>
                   @endif

                    @endif

                </div>
                <div class="table-body-cell">
                    @php $q=Cheques::where('_id', $i->_id)->first();
                    @endphp

                  <button type="button"
                  data-toggle="modal"data-controls-modal="#asingnarCheque"
                  data-target="#sustraer" wire:click="emitirAsustraer('{{$i->_id}}')"
                  class="btn btn-secondary btn-sm">Ajustar</button>


                </div>

            </div>

     {{-- <livewire:sustraer :sustraerImporte="$i" :totalPagado="$totalPagado" :serie="$serie" :periodo="$datos" :totalrestante="$tpagado"  :wire:key="'sustraer'. $i->UUID.$i->_id"> --}}
        @endforeach
     </div>
  </div>


</div> <!-- end div scroll --->




                      <!--  fin de la tabla vicular chques-->
                      </div>
                      @endif
                      <!--- final de if tab para mstrar los cheques a vincular de la tabla-->
                      @if ($tpagado == 0)
                      @php $tabActive= "active";@endphp
                      @endif

                      <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                       <!--[tabla para desvicular cheques ]-->

                     {{---checar si hay cheques vinculados---}}


                       @if (isset($arreglo_cheques->cheques_id) && count($arreglo_cheques->cheques_id) > 0 )

                       <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                       <form wire:submit.prevent="desvicunlar">
                       <button type="submit" class="btn btn-secondary  btn-sm">Desvincular</button>
                       <button style="color:rgb(54, 53, 53);" wire:click="cheque('{{$idschequesVinculados}}')"
                       type="button" class="btn btn-warning  btn-sm  ">
                        Ver en cheques y trasnferencias</button>
                      {{-- <button wire:click="asignar()" type="button" class="btn btn-success  btn-sm">desvincular todo</button> --}}

                      @php
                      // variables
                      $cheques_id=[];
                      try {
                          /// se metadato de un cfd viculado donde vienen lo cheques vinculados
                      $metadata = MetadataE:: // consulta a MetadataE
                                  where('folioFiscal',$idschequesVinculados)
                                  ->where('efecto', 'Nómina')
                                  ->first();
                       if(isset($metadata->cheques_id)){

                      foreach($metadata->cheques_id as $id){
                      $cheques_id[]=$id;
                      }
                       }else{ $cheques_id[] = "";  }
                      $chequeList=Cheques::whereIn('_id',$cheques_id)->get();
                    } catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

                      @endphp


                      <!--- fin de la seccion collapse -->

                          <!-- tabla de cheques relacionados para desvicular desde nominas -->
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
                                    @foreach ($chequeList as $i)
                                        {{-- Obtenemos los datos del XMLRecibidos --}}

                                        <div class="resp-table-row">
                                            {{-- UUID --}}
                                            <div class="table-body-cell">
                                                <div class="form-check">


                                              <input   wire:model.defer="chequesVinculados" class="mis-checkboxes" type="checkbox"
                                                       value="{{ $i->_id }}" />



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
                                             @php
                                                 $valor=0;
                                             @endphp
                                             {{-- Metodo/Pago --}}
                                            <div  class="table-body-cell">

                            @php
                            // echo var_dump($this->temporales)."<br>";
                            // echo $i->_id;
                            $valor = $Nomina->importesTemporales($this->temporales, $i->_id);
                            //   echo "importe".  $Nomina->importesTemporales($this->temporales, $i->_id);
                            @endphp

                                                @if ($i->$nomna!==null   )
                                                ${{ number_format($i->$nomna - $valor, 2) }}
                                                @if ($valor >0)
                                                 &nbsp;/ &nbsp; <strong style="color:royalblue"> ${{ number_format( $valor, 2) }}
                                                 <a style="color:royalblue; padding: 0px 5px 0px 5px;"
                                                    class="parpadea fas fa-exclamation" onclick="ImporteTemp()"
                                                   ></a></strong>
                                                @endif
                                                @elseif(isset($i->saldo) && $i->saldo != 0)
                                                ${{ number_format($i->saldo - $valor, 2) }}
                                                @if ($valor >0)
                                                &nbsp;/ &nbsp; <strong style="color:royalblue"> ${{ number_format( $valor, 2) }}
                                                    <a style="color:royalblue; padding: 0px 5px 0px 5px;"
                                                    class="parpadea fas fa-exclamation" onclick="ImporteTemp()"
                                                   ></a>
                                                </strong>
                                               @endif
                                                @else
                                                ${{ number_format($i->importecheque - $valor, 2) }}
                                                @if ($valor >0)
                                                &nbsp;/ &nbsp; <strong style="color:royalblue"> ${{ number_format( $valor, 2) }}
                                                    <a style="color:royalblue; padding: 0px 5px 0px 5px;"
                                                    class="parpadea fas fa-exclamation"  onclick="ImporteTemp()"
                                                   ></a>
                                                </strong>
                                               @endif

                                                @endif

                                            </div>


                                        </div>


                                    @endforeach
                                 </div>
                              </div>


                            </div> <!-- end div scroll --->

                       </form>
                          <!-- fin de la tbala cheques realcionados desde nominas-->





                         @else

                    <center> No hay cheques vinculados a esta nomina.</center>
                        @endif
                       <!-- [ fin de la tabla para desvicular cheques] --->
                      </div>

                    </div>
                  </div>
                </div>
              </section>
              <!-- Basic Tag Input end -->






{{--
<center>

La suma de los cheques asignados a este perido con cuerdan correctamente con el total pago.

<h5> Cheques Vinculados a la nomina.<bold> </bold> </h5>

<center> --}}


</div><!-- fin modal body -->




</div>
</div>
</div>


</div></div></div><!--fin del contener collapse-->


<style>

    .checkmark__circle{stroke-dasharray: 166;stroke-dashoffset: 166;stroke-width: 2;stroke-miterlimit: 10;stroke: #7ac142;fill: none;animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards}.checkmark{width: 56px;height: 56px;border-radius: 50%;display: block;stroke-width: 2;stroke: #fff;stroke-miterlimit: 10;margin: 10% auto;box-shadow: inset 0px 0px 0px #7ac142;animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both}.checkmark__check{transform-origin: 50% 50%;stroke-dasharray: 48;stroke-dashoffset: 48;animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards}@keyframes stroke{100%{stroke-dashoffset: 0}}@keyframes scale{0%, 100%{transform: none}50%{transform: scale3d(1.1, 1.1, 1)}}@keyframes fill{100%{box-shadow: inset 0px 0px 0px 30px #7ac142}}

    </style>


{{--
@include('livewire.agregarcheque') --}}




</div>{{----------- fin div principal--------------}}




