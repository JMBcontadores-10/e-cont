<div><!-- div contenedor principal-->
    @php
    use App\Models\Cheques;
    use App\Models\XmlR;
    use App\Models\MetadataR;
    use App\Http\Controllers\ChequesYTransferenciasController;
    use Illuminate\Support\Facades\DB;

    $rfc = Auth::user()->RFC;
    $class='';
    if(empty($class)){
        $class="table nowrap dataTable no-footer";
    }
    @endphp

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

    {{--Contenedor--}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    {{--Boton para crear un nuevo cheque --}}
                    <div class="invoice-create-btn mb-1">
                        <a data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static" data-keyboard="false" data-target="#nuevo-cheque" class="btn btn-primary glow invoice-create">
                            Nuevo Cheque/Transferencia
                        </a>
                    </div>

                    {{-- <button id="btn">Eviar Correo </button>
                    <div class="conten">

                        <div id="notificacion" class="notEmail">
                            <div class="mensaje_not">
                                Correo enviado con éxito 1
                            </div>
                            <img src="https://fotos.subefotos.com/9d3c5e949d2642e2db4d2b28a4fe0ef5o.gif">
                        </div>

                        </div>
                      <script>
                            $("#btn").click(function () {
                                estilos();
                            });
                        </script> --}}


@if (session()->has('id'))
<div class="alert alert-success">
    {{ session('id') }}
</div>

<script>

    $(document).ready(function() {

     window.livewire.emit('notivincu','{{session('id')}}' );



     document.querySelector('#show62448d76a9450000d70052e6').click();






} );


</script>


@endif

                    {{--Condicional para mostrar un listado de empresas--}}
                    @empty(!$empresas)

                    <label for="inputState">Empresa: {{$empresa}}</label>
                    <select wire:loading.attr="disabled" wire:model="rfcEmpresa" id="inputState1" class=" select form-control">
                        <option  value="00" >--Selecciona Empresa--</option>
                        <?php $rfc=0; $rS=1;foreach($empresas as $fila){
                            echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';
                        }?>
                    </select>

                    <br>

                    @endempty

                    {{--Filtros de busqueda--}}
                    <div class="form-inline mr-auto">

                        {{--Busqueda por texto--}}
                        <input   wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Filtro" aria-label="Search">
                        &nbsp;&nbsp;

                        {{--Busqueda por mes--}}
                        <label for="inputState">Mes</label>
                        <select wire:model="mes" id="inputState1"  wire:loading.attr="disabled"  class=" select form-control"  >
                            <option  value="00" >Todos</option>
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            }?>
                        </select>
                        &nbsp;&nbsp;

                        {{--Busqueda por año--}}
                        <label for="inputState">Año</label>
                        <select wire:loading.attr="disabled" wire:model="anio" id="inputState2" class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            }?>
                        </select>
                        &nbsp;&nbsp;

                        {{--Checkbox para buscar todos los registros--}}
                        <fieldset>
                            <div class="custom-control custom-checkbox">
                              <input wire:model="todos" type="checkbox" class="custom-control-input bg-danger" checked name="customCheck" id="customColorCheck4">
                              <label class="custom-control-label" for="customColorCheck4">Todos</label>
                            </div>
                        </fieldset>
                        &nbsp;&nbsp;

                        {{--Busqueda por importe--}}
                        <input wire:model.debounce.300ms="importe"  class="form-control"  placeholder="Importe $"  type="number"  step="0.01" aria-label="importe" style="width:110px;" >
                        &nbsp;&nbsp;

                        {{--Busqueda por condicion--}}
                     <select wire:loading.attr="disabled" wire:model="condicion" id="inputState1" class=" select form-control"  >
                            <option  value=">=" >--Condición--</option>
                            <option value="=" >igual</option>
                            <option value=">" >mayor que</option>
                            <option value="<" >menor que</option>
                        </select>

                        &nbsp;&nbsp;
                         {{--Busqueda por estado--}}
                        <select wire:loading.attr="disabled" wire:model="estatus" id="inputState1" class=" select form-control"  >
                            <option  value="">--Estatus--</option>
                            <option value="pendi" >Pendientes</option>

                            @if(auth()->user()->tipo)
                            <option value="sin_revisar" >Sin Revisar</option>
                            <option value="sin_conta" >Sin Contabilizar</option>
                            @endif
                        </select>
                    </div>

                    {{--Animacion de cargando--}}
                    <div wire:loading >
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                          <div></div>
                          <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                    {{--Boton de filtrado--}}
                    <div class="action-dropdown-btn d-none">
                        <div class="dropdown invoice-filter-action">
                            <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                Filter Invoice
                            </button>
                        </div>
                    </div>

                    {{--Tabla de contenido--}}
                    <div class="table-responsive">
                        <table id="example" class="{{$class}}" style="width:100%">
                            {{--Encabezado--}}
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

                            {{--Acciones previas a mostrar el cuerpo de la tabla--}}
                            @php
                            $arreglo="";
                            $totalVinculadas=0;
                            @endphp

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
                            }else {
                                $diferencia = $importeC - abs($sumaxml);
                                $diferencia = $diferencia - $ajuste;
                            }
                            if ($diferencia > 1 or $diferencia < -1) {
                                $diferenciaP = 0;
                            }else {
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
                                {{--Cuerpo de la tabla con la funcion de expancion--}}
                                <tr  onclick="showHideRow('{{$id}}');">
                                    {{--Fecha--}}
                                    <td>
                                        @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                            @php
                                            Cheques::find($id)->update(['pendi' => 1]);
                                            @endphp
                                            <a style="color:red; padding: 0px 5px 0px 5px;"  class="parpadea fas fa-exclamation"
                                            onclick="alertaP({{ $diferenciaP }},{{ $faltaxml }}, {{ $nombreChequeP }})"></a>
                                        @else
                                            @php
                                            Cheques::find($id)->update(['pendi' => 0]);
                                            @endphp
                                            @if ($verificado == 1 && Auth::user()->tipo)
                                            @switch($contabilizado)
                                                @case(0)
                                                <a class="parpadea icons fa fa-check" style="color: green" aria-hidden="true" onclick="alert('Revisado')"></a>
                                                @break

                                                @case(1)
                                                <a class="parpadea icons fas fa-calculator" style="color: blue" aria-hidden="true" onclick="alert('Contabilizado')"></a>
                                                @break

                                                @default
                                            @endswitch
                                            @endif
                                        @endif
                                         {{$fecha}} &nbsp;
                                  </td>

                                  {{--Num de factura--}}
                                  <td>
                                    <a style="color:#3498DB" >{{ Str::limit($numCheque, 20); }}</a>
                                  </td>

                                  {{--Benficiario--}}
                                  <td>
                                    {{ Str::limit($beneficiario, 20);}}
                                  </td>

                                  {{--Tipo de operacion--}}
                                  <td>
                                    {{$tipoO}}
                                  </td>

                                  {{--Forma de pago--}}
                                  <td>
                                    <span class="invoice-amount">{{$tipo}}</span>
                                  </td>

                                  {{--Pagado--}}
                                  <td>
                                    <span class="invoice-amount">${{ number_format($importeC, 2) }}</span>
                                  </td>

                                  {{--$ de CFDI--}}
                                  <td>
                                    <span class="invoice-amount">${{ number_format($sumaxml, 2) }}</span>
                                  </td>

                                  {{--Comprobar--}}
                                  <td>
                                    <span class="invoice-amount">${{ $diferencia }}</span>
                                  </td>

                                  {{--...--}}
                                  <td></td>
                                </tr>

                                {{--Segunda fila del movimiento--}}
                                <tr id="hidden_row{{$id}}" class="hidden_row">
                                    <td colspan=12 style="  background-color:rgba(242,246,249,0.2);">
                                        {{--Encabezado--}}
                                        <a style="color:#3498DB">{{$numCheque}}</a>
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        {{$beneficiario}}

                                        <br>

                                        {{--Contenido--}}
                                        <div class="box">
                                            {{--Ajuste--}}
                                            @if(Auth::user()->tipo)
                                                <div>
                                                    <div class="tr"> Ajuste</div>
                                                    @if ($ajuste!=0)
                                                        @php $class="content_true" @endphp
                                                    @else
                                                        @php $class="icons" @endphp
                                                    @endif
                                                    {{--Condicional para las acciones de movimientos ya revisados--}}

                                                    <a class="{{$class}} fas fa-balance-scale"
                                                    data-toggle="modal" data-target="#ajuste{{$id}}"></a>

                                                </div>
                                            @endif

                                            {{--Notas--}}
                                            <div>
                                                <div class="tr"> Nota(s)</div>
                                                @if (!empty($comentario))
                                                    @php $class_c="content_true" @endphp
                                                @else
                                                    @php $class_c="icons" @endphp
                                                @endif
                                                <a id="nota{{$id}}" class="{{$class_c}} fas fa-sticky-note" data-toggle="modal" data-target="#comentarios-{{$id}}"> </a>
                                            </div>

                                            {{--PDF--}}
                                            <div>
                                                <div class="tr">PDF</div>
                                                @if ($nombreCheque!="0")
                                                    <a id="rutArc" href="{{ $rutaArchivo }}" target="_blank"></a>
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

                                            {{--Documentos adicionales--}}
                                            <div>
                                                <div class="tr"> Doctos. Adicionales</div>
                                                @if($verificado==1)
                                                    <a class="icons fas fa-upload"
                                                    onclick="alert('ya esta revisado no puedes hacer nada :)')"></a>
                                                @else
                                                    <a class="icons fas fa-upload"
                                                    data-toggle="modal" data-controls-modal="#uploadRelacionados"  name="{{$id}}"
                                                    data-backdrop="static" data-keyboard="false"   onclick="filepond(this.name)" data-target="#uploadRelacionados"></a>
                                                @endif

                                                &nbsp; | &nbsp;

                                                @if (!$docAdi['0'] == '')
                                                    @php $class="content_true" @endphp
                                                @else
                                                    @php $class="icons" @endphp
                                                @endif

                                                <a  class="{{$class}} fas fa-folder-open"
                                                data-toggle="modal"  wire:click="$emitTo('relacionados', 'refreshComponent')"
                                                data-target="#relacionados-{{$id}}" ></a>
                                            </div>

                                            {{--Vinculadas--}}
                                            @if ($faltaxml != 0)
                                            <div>
                                                <div class="tr">Vinculadas</div>
                                                <a class="icons fas fa-eye" style="color: #3498DB"
                                                data-toggle="modal" data-target="#facturasVinculadas{{$id}}"></a>
                                            </div>
                                            @endif

                                            {{--Editar--}}
                                            <div>
                                                <div class="tr">Editar</div>
                                                {{--Condicional para acciones con movimientos revisados--}}
                                                @if ($verificado == 1)
                                                    <a class="icons fas fa-edit" data-toggle="modal" data-target="#Revisado" ></a>
                                                @else
                                                    <a class="icons fas fa-edit" data-toggle="modal" data-target="#editar-{{$id}}" ></a>
                                                @endif
                                            </div>

                                            {{--Eliminar cheque--}}
                                            <div>
                                                <div class="tr">Eliminar Cheque</div>
                                                {{--Condicional para acciones con movimientos revisados--}}
                                                @if ($verificado == 1)
                                                    <a class="icons fas fa-trash-alt fa-lg" data-toggle="modal" data-target="#Revisado" ></a>
                                                @else
                                                    <a class="icons fas fa-trash-alt fa-lg" data-toggle="modal" data-target="#eliminar-{{$id}}" ></a>
                                                @endif
                                            </div>

                                            {{--Revisar--}}
                                            @if (Auth::user()->tipo)
                                            <div>
                                                <div class="tr">Revisado</div>
                                                @if($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                                    @php Cheques::find($id)->update(['pendi' => 1]); @endphp
                                                @elseif($verificado == 0 )
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" wire:model="revisado" value="{{$id}}" name="stOne{{$id}}" id="Revi{{$id}}">
                                                        <label class="form-check-label" for="Revi{{$id}}">Revisado</label>
                                                    </div>
                                                @else
                                                    <div id="Revisado{{$id}}" onclick="ToolRevisado(this.id)">
                                                        <div id="{{$id}}" class="RevisadoContainer" onclick="MostrarRevisado(this.id)">
                                                            <a class="icons far fa-check-circle BtnRevisado" style="color: green"></a>
                                                                <div id="MostrarRevi{{$id}}" class="MensajeContainer">
                                                                    <div class="Contenido">
                                                                        <p class="TextoMensaje">Revisado el: </p>
                                                                        <p class="TextoMensaje">{{$revisado_fecha}}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            {{--Contabilizado--}}
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
                                                <div id="{{$id}}" class="RevisadoContainer" onclick="MostrarConta(this.id)">
                                                    <i style="color: blue; " class="icons fas fa-calculator"></i>
                                                    <div id="MostrarConta{{$id}}" class="MensajeContainer">
                                                        <div class="Contenido">
                                                            <p class="TextoMensaje">{{$poliza}}</p>
                                                            <p class="TextoMensaje">{{$contabilizado_fecha}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                                    @php Cheques::find($id)->update(['pendi' => 1]); @endphp
                                                @endif
                                            </div>
                                            @endif

                                            {{--Impresion--}}
                                            <div>
                                                <div class="tr">imp</div>
                                                @if($impresion == 'on')
                                                    <i class="icons fas fa-print fa-2x" style="color: green"></i>
                                                @endif
                                                @if($impresion == '')
                                                    <div class="ImpContainer">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"  wire:model="impresion" value="{{$id}}" name="s{{$id}}" id="Conta{{$id}}">
                                                            <label class="form-check-label" for="Conta{{$id}}">Impresión</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            {{--Cheques ID--}}
                                            <div>
                                                <div class="tr">Cheque Id</div>
                                                &nbsp;&nbsp;&nbsp; {{$id}}
                                            </div>
                                        </div>
                                    </td>

                                    {{--Llamando a las vistas de otros componentes--}}
                                    <livewire:ajuste :ajusteCheque="$i" :wire:key="'user-profile-one-'.$i->_id">
                                    <livewire:comentarios :comentarioCheque="$i" :wire:key="'user-profile-two-'.$i->_id">
                                    <livewire:relacionados  :filesrelacionados=$i :wire:key="'user-profile-five-'.$i->_id">
                                    <livewire:pdfcheque :pdfcheque=$i :wire:key="'user-profile-tre-'.$i->_id">
                                    <livewire:editar  :editCheque=$i :wire:key="'user-profile-four-'.$i->_id">
                                    <livewire:poliza  :polizaCheque=$i :wire:key="'user-profile-six-'.$i->_id">
                                    <livewire:eliminar  :eliminarCheque=$i :wire:key="'user-profile-seven-'.$i->_id">

                                    @if(!$i->faltaxml ==0)
                                        <livewire:facturas-vinculadas  :facturaVinculada=$i :wire:key="'user-profile-eight-'.$i->_id">
                                    @endif

                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                        {{ $colCheques->links() }}
                        @livewireScripts
                    </div>
                </section>
            </div>
        </div>
    </div>

    {{--Llamamos a las vistas de otros componentes--}}
    <livewire:agregarcheque>
    <livewire:uploadrelacionados>
    @include('livewire.demo')

    {{--Modal para las acciones bloqueadas por movimientos revisadps--}}
    {{--Modal de detalles de cuentas por pagar--}}
    {{--Creacion del modal--}}
    <div wire:ignore.self class="modal fade" id="Revisado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                {{--Encabezado--}}
                <div class="modal-header">
                    <h6 class="modal-title" id="TitleRevisado"><span style="text-decoration: none;" class="icons far fa-check-circle"> Movimiento revisado</span></h6>
                    <button id="BtnCloseRevi" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                   </button>
                </div>
                {{--Cuerpo del modal--}}
                <div class="modal-body">



                    <p id="ModalRevi">No puedes realizar esta acción en un movimiento revisado</p>

                </div>
            </div>
        </div>
    </div>
</div>
