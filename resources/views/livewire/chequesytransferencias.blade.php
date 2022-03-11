<div><!-- div contenedor principal-->


    @php
use App\Models\Cheques;
use App\Http\Controllers\ChequesYTransferenciasController;
use Illuminate\Support\Facades\DB;
@endphp


        @php
        $rfc = Auth::user()->RFC;
       $class='';
        if(empty($class)){
           $class="table nowrap dataTable no-footer";

        }



     @endphp


<script>
window.addEventListener('disabled', event => {

  });






    </script>




          <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
          <div class="content-header row">
          </div>
          <div class="content-body"><!-- invoice list -->
  <section class="invoice-list-wrapper">
    <!-- create invoice button-->
    <div class="invoice-create-btn mb-1">

        <a data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static" data-keyboard="false"    data-target="#nuevo-cheque" class="btn btn-primary glow invoice-create"
    >Nuevo Cheque/Transferencia </a>
    </div>
    <!--<form action="{{ url('vincular-cheque') }}" method="POST">
        @csrf
        <button class="button2">Registrar Cheque/Transferencia</button>
    </form>-->

   <!-- <form  wire:submit.prevent="buscar">
        @csrf

    <input wire:model.defer="search" type="text"  name="ajuste" class="form-control">

    <div wire:loading wire:target="buscar" >
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
          <div></div>
          <div></div>

      </div>
      Guardando ajuste
      </div>

    <button type="submit"   class="btn btn-primary close-modal">Ajustar</button>

         </form>-->

@empty(!$empresas)




{{-- <h4>{{$empresas[3][0]}}</h4> --}}




{{$empresa}}



<br><br>

{{--@php
foreach($empresas as $fila)
{
    foreach($fila as $nombre)
    {
	echo " $nombre ";
    }

	echo "<br>";
}
@endphp--}}
            <label for="inputState">Empresa</label>
            <select wire:loading.attr="disabled"   wire:model="rfcEmpresa" id="inputState1" class=" select form-control"  >
                <option  value="00" >--Selecciona Empresa--</option>
                <?php $rfc=0; $rS=1;foreach($empresas as $fila)
                {

                    echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';

          }
                ?>
            </select>

            &nbsp;&nbsp;<br>
@endempty



            <div class="form-inline mr-auto">
            <input wire:loading.attr="readonly"  wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Filtro" aria-label="Search">
            &nbsp;&nbsp;
            <label for="inputState">Mes</label>
            <select wire:model="mes" id="inputState1"  wire:loading.attr="disabled"  class=" select form-control"  >
                <option  value="00" >Todos</option>
                <?php foreach ($meses as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . '</option>';
                } ?>
            </select>
            &nbsp;&nbsp;


            <label for="inputState">Año</label>
            <select wire:loading.attr="disabled" wire:model="anio" id="inputState2" class="select form-control">

                <?php foreach (array_reverse($anios) as $value) {
                    echo '<option value="' . $value . '">' . $value . '</option>';
                } ?>
            </select>
            &nbsp;&nbsp;
            <fieldset>
                <div class="custom-control custom-checkbox">
                  <input wire:model="todos" type="checkbox" class="custom-control-input bg-danger" checked name="customCheck" id="customColorCheck4">
                  <label class="custom-control-label" for="customColorCheck4">Todos</label>
                </div>
              </fieldset>
    &nbsp;&nbsp;

        <input  wire:model.debounce.300ms="importe" class="form-control"  placeholder="Importe $"  type="number"  step="0.01" aria-label="importe" style="width:110px;" >
        &nbsp;
        <select wire:loading.attr="disabled" wire:model="condicion" id="inputState1" class=" select form-control"  >
            <option  value=">=" >--Condición--</option>
            <option value="=" >igual</option>
            <option value=">" >mayor que</option>
            <option value="<" >menor que</option>
        </select>
  &nbsp;
        <select wire:loading.attr="disabled" wire:model="estatus" id="inputState1" class=" select form-control"  >
            <option  value="" >--Estatus--</option>
            <option value="pendi" >Pendientes</option>

            @if(auth()->user()->tipo)
            <option value="sin_revisar" >Sin Revisar</option>
            <option value="sin_conta" >Sin Contabilizar</option>
            @endif
        </select>


<!-- <input  wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Search" aria-label="Search">
           -->



        </div>



        <div wire:loading >
            <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
              <div></div>
              <div></div>

          </div>
          <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
          </div>



    <!-- Options and filter dropdown button-->
    <div class="action-dropdown-btn d-none">
      <div class="dropdown invoice-filter-action">
        <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          Filter Invoice
        </button>

      </div>

    </div>
    <div class="table-responsive">
        <table id="example" class="{{$class}}" style="width:100%">
          <thead>
            <tr>

              <th>
                <span class="align-middle">fecha </span>
              </th>

              <th>Factura#</th>
              <th>beneficiario</th>
              <th>T.operación</th>
              <th>F.pago</th>
              <th>Pagado</th>
              <th>$Cfdi</th>
              <th>comprobar</th>


              <th >...</th>




            </tr>
          </thead>

          @php $arreglo=""; @endphp
          @foreach ($colCheques as $i)

          @php


           $editar = true;
          $id = $i->_id;
          $tipo = $i->tipomov;
          $fecha = $i->fecha;
          $dateValue = strtotime($fecha);
           $anio = date('Y',$dateValue);
          $rutaDescarga = 'storage/contarappv1_descargas/'.$rfc.'/'.$anio.'/Cheques_Transferencias/';
          $numCheque = $i->numcheque;
          $beneficiario = $i->Beneficiario;
          $importeC = $i->importecheque;
          $sumaxml = $i->importexml;
          $ajuste = $i->ajuste;
          $verificado = $i->verificado;
          $faltaxml = $i->faltaxml;
          $contabilizado = $i->conta;
          $pendiente = $i->pendi;
              $tipoO = $i->tipoopera;
              if ($tipoO == 'Impuestos' or $tipoO == 'Parcialidad') {
                  $diferencia = 0;
              } else {
                  $diferencia = $importeC - abs($sumaxml);
                  $diferencia = $diferencia - $ajuste;
              }
              if ($diferencia > 1 or $diferencia < -1) {
                  $diferenciaP = 0;
              } else {
                  $diferenciaP = 1;
              }
              $diferencia = number_format($diferencia, 2);
              $nombreCheque = $i->nombrec;
              if ($nombreCheque == '0') {
                  $subirArchivo = true;
                  $nombreChequeP = 0;
              } else {
                  $subirArchivo = false;
                  $nombreChequeP = 1;
              }
              $rutaArchivo = $rutaDescarga . $nombreCheque;
              if (!empty($i->doc_relacionados)) {
                  $docAdi = $i->doc_relacionados;
              }
              $revisado_fecha = $i->revisado_fecha;
              $contabilizado_fecha = $i->contabilizado_fecha;
              $poliza = $i->poliza;
              $comentario = $i->comentario;
              $impresion = $i['impresion'];


if(strpos($nombreCheque, '/')!==false){

$p = explode("/", $nombreCheque);

 $i->update([  // actualiza el campo nombrec a 0
        'nombrec' => $p[1],
       ]);


}

if(strpos($docAdi[0], '/')!==false){


foreach ($i->doc_relacionados as $doc)
{
$pp = explode("/", $doc);

    $i->pull('doc_relacionados', $doc);

        $i->push('doc_relacionados', $pp[1]);

}



}






          @endphp
          <tbody>



         {{------- Actualizacion del estado pendiente a 0------}}


            <tr onclick="showHideRow('{{$id}}');">
              <td>
                  {{-- <small class="text-muted"> --}}

                               @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                        @php
                                            Cheques::find($id)->update(['pendi' => 1]);
                                        @endphp

                                         <a style="color:red;"  class="parpadea fas fa-exclamation"
                                          onclick="alertaP({{ $diferenciaP }},{{ $faltaxml }}, {{ $nombreChequeP }})">
                                        </a>

                                        @else

                                        @php
                                            Cheques::find($id)->update(['pendi' => 0]);
                                        @endphp


                                        @endif


              {{$fecha}}</td>

              <td>
                <a style="color:#3498DB" >{{ Str::limit($numCheque, 20); }}</a>
              </td>
              <td> {{ Str::limit($beneficiario, 20);}}</td>
              <td>{{$tipoO}}</td>

              <td><span class="invoice-amount">{{$tipo}}</span></td>
              <td><span class="invoice-amount">${{ number_format($importeC, 2) }}</span></td>

              <td><span class="invoice-amount">${{ number_format($sumaxml, 2) }}</span></td>
              <td><span class="invoice-amount">${{ $diferencia }}</span></td>
              <td>{{-- ajuste y notas---}}<span class="invoice-amount">

            </tr>

            <tr id="hidden_row{{$id}}" class="hidden_row"  >
              <td colspan=12 style="  background-color:rgba(242,246,249,0.2);">

              <a style="color:#3498DB">{{$numCheque}}</a>

                 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <!--
                 <br>
            -->

                 {{$beneficiario}}

                 <br>

                 <div class="box">



                  @if(Auth::user()->tipo)
                  <!--Seccion ajute-->
                    <div>
                        <div class="tr"> Ajuste</div>
                        @if ($ajuste!=0)
                            @php $class="content_true" @endphp
                        @else
                            @php $class="icons" @endphp
                        @endif
                        <a class="{{$class}} fas fa-balance-scale"
                        data-toggle="modal" data-target="#ajuste{{$id}}"></a>
                    </div>
                  @endif


                  <div>
                      <div class="tr"> Nota(s)</div>

                 @if (!empty($comentario))

                 @php $class_c="content_true" @endphp

                 @else

                 @php $class_c="icons" @endphp

              @endif

        <a  class="{{$class_c}} fas fa-sticky-note" data-toggle="modal" data-target="#comentarios-{{$id}}"> </a>
                  </div>
                  <div>
                      <div class="tr">PDF</div>
                       @if ($nombreCheque!="0")
                       <a id="rutArc" href="{{ $rutaArchivo }}" target="_blank"> </a>

          @php $class_p="content_true_pdf" @endphp
          @else
          @php $class_p="icons" @endphp
          @endif



          @if ($nombreCheque =="0")
          <a id="{{$id}}" class="{{$class_p}} fas fa-file-pdf"
          data-toggle="modal" data-target="#pdfcheque{{$id}}"  data-backdrop="static" data-keyboard="false"   onclick="filepondEditCheque(this.id)" > </a>
         @else

         <a id="{{$id}}" class="{{$class_p}} fas fa-file-pdf"
     data-toggle="modal" data-target="#pdfcheque{{$id}}"  onclick="filepondEditCheque(this.id)" > </a>
         @endif
                  </div>
                  <div>
                      <div class="tr"> Doctos. Adicionales</div>
          @if($verificado==1)
          <a class="icons fas fa-upload"
          onclick="alert('ya esta revisado no puedes hacer nada :)')">
         </a>{{-- id="{{$id}}"--}}
            @else


          <a class="icons fas fa-upload"
                      data-toggle="modal" data-controls-modal="#uploadRelacionados"  name="{{$id}}"  data-backdrop="static" data-keyboard="false"   onclick="filepond(this.name)"  data-target="#uploadRelacionados">
                     </a>{{-- id="{{$id}}"--}}

       @endif

                      &nbsp; | &nbsp;
                      @if (!$docAdi['0'] == '')

                      @php $class="content_true" @endphp

                      @else

                      @php $class="icons" @endphp


                   @endif

                      <a  class="{{$class}} fas fa-folder-open"
                      data-toggle="modal"  wire:click="$emitTo('relacionados', 'refreshComponent')"    data-target="#relacionados-{{$id}}" >{{--id="{{$id}}"--}}
                     </a>

                  </div>
                  @if ($faltaxml != 0)
                  <div>

                      <div class="tr">Vinculadas</div>



                                            {{-- <form action="{{ url('detallesCT') }}" method="POST">
                                              @csrf
                                              <input type="hidden" name="id" value="{{ $id }}">
                                              <input type="hidden" name="verificado" value="{{ $verificado }}">
                                              <button   style= " border:none" >
ver
                                            </button>
                                          </form> --}}

                                          <a  class="icons fas fa-eye"
                                          data-toggle="modal"     data-target="#facturasVinculadas{{$id}}" >{{--id="{{$id}}"--}}
                                         </a>





                        </div>
                        @endif



                  <div>
                <div class="tr">Editar</div>

                <a  class="icons fas fa-edit"
                data-toggle="modal"     data-target="#editar-{{$id}}" >{{--id="{{$id}}"--}}
               </a>

                  </div>

                  <div>

                      <div class="tr">Eliminar Cheque</div>

                      <a  class="icons fas fa-trash-alt fa-lg"
                data-toggle="modal"     data-target="#eliminar-{{$id}}" >{{--id="{{$id}}"--}}
                                       </a>


                        </div>



@if (Auth::user()->tipo)
<!--Seccion revisado-->
    <div>
        <div class="tr">Revisado</div>
        @if($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
            @php Cheques::find($id)->update(['pendi' => 1]); @endphp
        @elseif($verificado == 0 )
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model="revisado"   value="{{$id}}" name="stOne{{$id}}" id="stOne"  >
                <label class="form-check-label" for="flexCheckChecked">Revisado</label>
            </div>
        @else
            <div id="{{$id}}" class="RevisadoContainer" onclick="MostrarRevisado(this.id, '{{$revisado_fecha}}')">
                <a class="icons far fa-check-circle BtnRevisado" style="color: green"></a>
                    <div id="MostrarRevi{{$id}}" class="MensajeContainer">
                        <div class="Contenido">
                            <p class="TextoMensaje">Revisado el: </p>
                            <p class="TxtRevicion TextoMensaje"></p>
                        </div>
                    </div>
            </div>


    @endif
    </div>


    <div>
        @if ($verificado == 1 and $contabilizado == 1)
            <div class="tr">Contabilizado</div>
        @else
            <div class="tr">Póliza</div>
        @endif

        @if ($verificado == 1 and $contabilizado == 0)
            <a class="icons fas fa-file-contract"
            data-toggle="modal" data-target="#poliza{{$id}}"></a>
        @elseif ($verificado == 1 and $contabilizado == 1)
            <div id="{{$id}}" class="RevisadoContainer" onclick="MostrarConta(this.id, '{{$poliza}}', '{{$contabilizado_fecha}}')">
                <i style="color: blue; " class="icons fas fa-calculator"></i>
                <div id="MostrarConta{{$id}}" class="MensajeContainer">
                    <div class="Contenido">
                        <p class="TextoMensaje TxtNomConta"></p>
                        <p class="TextoMensaje TxtFechaConta"></p>
                    </div>
                </div>
            </div>
        @endif

        @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
            @php Cheques::find($id)->update(['pendi' => 1]); @endphp
        @endif
        @endif
    </div>




                                  <div>

                          <div class="tr">imp</div>


                          @if($impresion == 'on')
                                    <i class="icons fas fa-print fa-2x" style="color: green"></i>

                                    @endif


                                   @if($impresion == '')

                                   <div class="ImpContainer">
                                    <input id="flexCheckCheckeddd" type="checkbox"  wire:model="impresion"   value="{{$id}}" name="s{{$id}}" id="stOneaa{{$id}}"  >
                                    <label for="flexCheckCheckeddd">
                                  Impresión
                                    </label>
                                   </div>
                                        @endif

                          </div>





                                  {{-- <div>
                              <div class="tr">Impresion</div>

                                      @if($impresion == 'on')

                                      <i class="icons fas fa-print" style="color: green"></i>


                                      @endif

                                      @if($impresion == '')
                                      <form action="{{ url('cheques-transferencias') }}" method="POST">
                                              @csrf
                                              <input type="hidden" id="id" name="id" value="{{ $id }}">

                                              <input type="checkbox" name="impresion" required class="mb-2">

                                              Impresion
                                              <div>

                                              <input type="submit" name="Aceptar" value="Aceptar">
                                          </div>
                                              @endif
                                          </form>




                            </div> --}}


                                        <div>

                                          <div class="tr">Cheque Id</div>
                                          &nbsp;&nbsp;&nbsp; {{$id}}

                                                 </div>
                                                 </div>


     </span>


  </div>

  </div>
              </td>



                <livewire:ajuste :ajusteCheque="$i" :wire:key="'user-profile-one-'.$i->_id">
                <livewire:comentarios :comentarioCheque="$i" :wire:key="'user-profile-two-'.$i->_id">
                <livewire:relacionados  :filesrelacionados=$i :wire:key="'user-profile-five-'.$i->_id" >
                <livewire:pdfcheque :pdfcheque=$i :wire:key="'user-profile-tre-'.$i->_id" >

                <livewire:editar  :editCheque=$i :wire:key="'user-profile-four-'.$i->_id">

                <livewire:poliza  :polizaCheque=$i :wire:key="'user-profile-six-'.$i->_id" >
                <livewire:eliminar  :eliminarCheque=$i :wire:key="'user-profile-seven-'.$i->_id" >
                    @if(!$i->faltaxml ==0)
                <livewire:facturas-vinculadas  :facturaVinculada=$i :wire:key="'user-profile-eight-'.$i->_id" >
                    @endif


          </tr>

           @endforeach
          </tbody>
        </table>

      {{ $colCheques->links() }}


@livewireScripts
    </div>
  </section>
          </div>
        </div>
      </div>




      <livewire:agregarcheque>
    <livewire:uploadrelacionados>



        @include('livewire.demo')
      {{--  @include('livewire.ajuste')--}}
</div><!-- fin div contenedor principal-->

 {{-- @php

    $col = Cheques::

        where('rfc','CDI1801116Y9')

        ->get()
        ;


@endphp

@if(empty($col))



@else
@foreach ($col as $i)

@php
 $editar = true;
$id = $i->_id;
$tipo = $i->tipomov;
$fecha = $i->fecha;
$dateValue = strtotime($fecha);
 $anio = date('Y',$dateValue);
$rutaDescarga = 'storage/contarappv1_descargas/'.$rfc.'/'.$anio.'/Cheques_Transferencias/';
$numCheque = $i->numcheque;
$beneficiario = $i->Beneficiario;
$importeC = $i->importecheque;
$sumaxml = $i->importexml;
$ajuste = $i->ajuste;
$verificado = $i->verificado;
$faltaxml = $i->faltaxml;
$contabilizado = $i->conta;
$pendiente = $i->pendi;
    $tipoO = $i->tipoopera;
    if ($tipoO == 'Impuestos' or $tipoO == 'Parcialidad') {
        $diferencia = 0;
    } else {
        $diferencia = $importeC - abs($sumaxml);
        $diferencia = $diferencia - $ajuste;
    }
    if ($diferencia > 1 or $diferencia < -1) {
        $diferenciaP = 0;
    } else {
        $diferenciaP = 1;
    }
    $diferencia = number_format($diferencia, 2);
    $nombreCheque = $i->nombrec;
    if ($nombreCheque == '0') {
        $subirArchivo = true;
        $nombreChequeP = 0;
    } else {
        $subirArchivo = false;
        $nombreChequeP = 1;
    }
    $rutaArchivo = $rutaDescarga . $nombreCheque;
    if (!empty($i->doc_relacionados)) {
        $docAdi = $i->doc_relacionados;
    }
    $revisado_fecha = $i->revisado_fecha;
    $contabilizado_fecha = $i->contabilizado_fecha;
    $poliza = $i->poliza;
    $comentario = $i->comentario;
@endphp

<livewire:ajuste  :ajusteCheque=$i : key="$i->id" >
<livewire:comentarios  :comentarioCheque=$i : key="$i->id" >




@endforeach
<livewire:pdfcheque :pdfcheque=$i : key="$i->id" >
<livewire:editar  :editCheque=$i : key="$i->id">
<livewire:relacionados  :filesrelacionados=$i : key="$i->id" >
        <livewire:pdfcheque :pdfcheque=$i : key="$i->id" >

@endif  --}}


