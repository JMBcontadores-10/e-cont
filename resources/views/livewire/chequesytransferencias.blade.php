<div>
    <!-- div contenedor principal-->
    @php
        use App\Models\Cheques;
        use App\Models\XmlR;
        use App\Models\MetadataR;
        use App\Http\Controllers\ChequesYTransferenciasController;
        use Illuminate\Support\Facades\DB;

        $rfc = Auth::user()->RFC;
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
    @endphp

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

    <!-- TableExport js --->
    {{-- ----Referencias: https://github.com/hhurz/tableExport.jquery.plugin
                         https://examples.bootstrap-table.com/#extensions/export.html
    ------------- --}}

    <script src="{{ asset('js/tableExport/libs/FileSaver/FileSaver.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/tableExport.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/jsPDF/jspdf.umd.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/pdfmake.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/vfs_fonts.js') }}" defer></script>
    <!-- To export arabic characters include mirza_fonts.js _instead_ of vfs_fonts.js
<script type="text/javascript" src="libs/pdfmake/mirza_fonts.js"></script>
-->

    <!-- For a chinese font include either gbsn00lp_fonts.js or ZCOOLXiaoWei_fonts.js _instead_ of vfs_fonts.js
<script type="text/javascript" src="libs/pdfmake/gbsn00lp_fonts.js"></script>
-->

<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script></script>
    {{-- Contenedor --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">


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


                    @if (session()->get('idns'))
                        {{-- <div class="alert alert-success">
</div> --}}
                        @php

                            $name = Session::get('idns');
                            //    echo $name;
                        @endphp

                        <script>
                            $(document).ready(function() {

                                window.livewire.emit('notivincu', '{{ Session::get('idns') }}', '{{ Session::get('rfcn') }}');


                            });
                        </script>

                        @php
                            Session::forget('idns');
                            Session::forget('rfcn');
                        @endphp
                    @endif


                    @if (session()->get('rfc'))
                        {{-- <div class="alert alert-success">

</div> --}}

                        <script>
                            $(document).ready(function() {
                                // alert('{{ session('id') }}');
                                window.livewire.emit('vercheq', '{{ Session::get('rfc') }}', '{{ Session::get('id') }}');

                            });
                        </script>

                        @php
                            Session::forget('id');
                            Session::forget('rfc');
                        @endphp
                    @endif

          {{------------ recibir variables de moduilo nominas para mostrar los chques vcinculados  -------------}}
          @if (session()->get('idnominas'))
          {{-- <div class="alert alert-success">

</div> --}}
@php  $nomina= Session::get('nomina'); @endphp

          <script>
              $(document).ready(function() {
                  // alert('{{ session('id') }}');
                  window.livewire.emit('chequesVi', '{{ Session::get('rfcnomina') }}', '{{ Session::get('idnominas') }}');

              });
          </script>


      @endif

      {{-- {{ var_dump($this->ids)}} --}}
                    {{-- Condicional para mostrar un listado de empresas --}}
                    @empty(!$empresas)
                        <label for="inputState">Empresa: {{ $empresa }}</label>
                        <select wire:loading.attr="disabled" wire:model="rfcEmpresa" id="inputState1"
                            class=" select form-control">
                            <option value="">--Selecciona Empresa--</option>
                            <?php $rfc = 0;
                            $rS = 1;
                            foreach ($empresas as $fila) {
                                echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                                $razonSocial=$fila[$rS];
                            } ?>
                        </select>

                        <br>
                    @endempty

                    {{-- Filtros de busqueda --}}
                    <div class="form-inline mr-auto">

                        {{-- Busqueda por texto --}}
                        <input wire:model.debounce.300ms="search" class="form-control" type="text"
                            placeholder="Filtro" aria-label="Search">
                        &nbsp;&nbsp;

                        {{-- Busqueda por mes --}}
                        <label for="inputState">Mes</label>
                        <select wire:model="mes" id="inputState1" wire:loading.attr="disabled"
                            class=" select form-control">
                            <option value="00">Todos</option>
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;

                        {{-- Busqueda por año --}}
                        <label for="inputState">Año</label>
                        <select wire:loading.attr="disabled" wire:model="anio" id="inputState2"
                            class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;

                        {{-- Checkbox para buscar todos los registros --}}
                        <fieldset>
                            <div class="custom-control custom-checkbox">
                                <input wire:model="todos" type="checkbox" class="custom-control-input bg-danger"
                                    checked name="customCheck" id="customColorCheck4">
                                <label class="custom-control-label" for="customColorCheck4">Todos</label>
                            </div>
                        </fieldset>
                        &nbsp;&nbsp;

                        {{-- Busqueda por importe --}}
                        <input wire:model.debounce.300ms="importe" class="form-control" placeholder="Importe $"
                            type="number" step="0.01" aria-label="importe" style="width:110px;">
                        &nbsp;&nbsp;

                        {{-- Busqueda por condicion --}}
                        <select wire:loading.attr="disabled" wire:model="condicion" id="inputState1"
                            class=" select form-control">
                            <option value=">=">--Condición--</option>
                            <option value="=">igual</option>
                            <option value=">">mayor que</option>
                            <option value="<">menor que</option>
                        </select>

                        &nbsp;&nbsp;
                        {{-- Busqueda por estado --}}
                        <select wire:loading.attr="disabled" wire:model="estatus" id="inputState1"
                            class=" select form-control">
                            <option value="">--Estatus--</option>
                            <option value="pendi">Pendientes</option>

                            @if (auth()->user()->tipo)
                                <option value="sin_revisar">Sin Revisar</option>
                                <option value="sin_conta">Sin Contabilizar</option>
                            @endif
                        </select>



                    </div>

                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                    {{-- Boton de filtrado --}}
                    <div class="action-dropdown-btn d-none">
                        <div class="dropdown invoice-filter-action">
                            <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter Invoice
                            </button>
                        </div>
                    </div>
 <!----- Header tabla hidden  --->
                    <table style="display:none;" data-tableexport-display="always" style="width:100%">
                        <tr>
<th> </th>
                          <th >Reporte Generado</th>
                          <th>Pendientes de la Empresa</th>

                        </tr>
                        <tr>
                            <td >


                            </td>
                          <td>@php
                            date_default_timezone_set("America/Mexico_City");

setlocale(LC_ALL,"es_MX.utf8");
echo strftime("%A %d de %B del %Y");


//Salida: español
                             @endphp</td>
                          <td>{{$empresa}}</td>

                        </tr>

                      </table>
<!---  fin Header tabla hidden---->

<table data-tableexport-display="none" style="width:100%">
    <tr>
        <th ><img width="150" src="img/logo-contarapp-01.png" ></th>
      <th >@php
        date_default_timezone_set("America/Mexico_City");

setlocale(LC_ALL,"spanish_Utf8");
echo strftime("%A %d de %B del %Y");


//Salida: viernes 05 de Septiembre del 2016
         @endphp</th>


    </tr>
    <tr>
    <td colspan="2" style="text-align: left"> {{-- Boton para crear un nuevo cheque --}}
        <div class="invoice-create-btn mb-1">
            <a data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
                data-keyboard="false" data-target="#nuevo-cheque"
                class="btn btn-primary glow invoice-create btn-sm">
                Nuevo Cheque/Transferencia
            </a>
            {{-- ------------Boton para vinculacion atomatica de pagos a PPD---------- --}}

            <button wire:click="$emitTo('vincular-pagos-automatico','refreshPagoAutomatico')"
                class=" btn btn-secondary btn-sm " style="vertical-align:middle">
                <a data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
                    data-keyboard="false" data-target="#vinculacionAutomtica">
                    <span>Vincular pagos </span>
                </a>
            </button>
            {{-------------- Boton para vincular traslados ------------------------------}}
            <button wire:click="$emitTo('vincular-traslados-automatico','refreshPagoAutomatico')"
                class=" btn btn-secondary btn-sm " style="vertical-align:middle">
                <a data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
                    data-keyboard="false" data-target="#vinculacionAutomticaTraslados">
                    <span> Vincular carta porte </span>
                </a>
            </button>

            {{-- si $estatus es igual a Pendiente y $colCheques igual o mayor a 1  muestra boton pdf --}}

            @if ($estatus == 'pendi' && count($colCheques) >= 1)
                &nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="exportReportToPdf('{{ $empresa }}')">
                    <i class="fas fa-file-pdf "></i>
                    PDF pendientes
                </button>
            @endif </td>
    </tr>
    <tr>
        <td colspan="2" >
            @php

if(!empty(auth()->user()->tipo)){

$rfcs=auth()->user()->empresas;
                 $pendientes = Cheques::
                         whereIn('rfc', $rfcs)
                        ->where('importecheque', $this->condicion, $this->importe)

                        ->where('pendi', 1)
                        ->orderBy('fecha', 'desc')
                        ->get()->count();

                        echo "!".ucfirst(auth()->user()->nombre) ."! Tienes".$pendientes."pendientes por revisar";

}
            @endphp




        </td>


    </tr>


  </table>



                    {{-- Tabla de contenido --}}
                    <div class="table-responsive">
                        <table id="datos" class="{{ $class }}" style="width:100%">
                            {{-- Encabezado --}}



                            <thead>

                                <tr>

                                    <th>
                                        <span class="align-middle">Fecha de pago</span>
                                    </th>
                                    <th>Factura#</th>
                                    <th>Beneficiario</th>
                                    <th data-tableexport-display="none">T.operación</th>
                                    <th>F.pago</th>
                                    <th>Pagado</th>
                                    <th>$Cfdi</th>
                                    <th data-tableexport-display="none">Comprobar</th>
                                    <!-- Celdas unicas para expotacion PDF pendientes -->
                                    <th style="display:none;" data-tableexport-display="always">Detalles
                                        {{-- Pendientes --}} </th>
                                    <th style="display:none;" data-tableexport-display="always">Comentarios</th>
                                    <!-- fin Celdas unicas para expotacion PDF pendientes -->
                                    <th>...</th>
                                </tr>
                            </thead>

                            {{-- Acciones previas a mostrar el cuerpo de la tabla --}}
                            @php
                                $arreglo = '';
                                $totalVinculadas = 0;
                                $asignado=0;
                                $tO=0;
                            @endphp

                            @foreach ($colCheques as $i)
                                @php
                                    $editar = true;
                                    $id = $i->_id;
                                    $tipo = $i->tipomov;
                                    $fecha = $i->fecha;
                                    $dateValue = strtotime($fecha);
                                    $anio = date('Y', $dateValue);
                                    $rutaDescarga = 'storage/contarappv1_descargas/' . $rfc . '/' . $anio . '/Cheques_Transferencias/';
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
                                    $comentario = $i->comentario;

                                    if($tipoO == 'Nómina') {$tO=1;}

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
                                     ///// si exsite un saldo se determina que al cheque le faltan asignaciones
                                    if(isset($i->saldo) &&  $i->saldo > 0 ){

                                        $asignado=1;

                                       ///// cehque cerrado
                                    }elseif (isset($i->nominaAsignada)){

                                        $asignado=2;
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
                                    $contabili_fecha = $i->updated_at;
                                    $poliza = $i->poliza;
                                    $comentario = $i->comentario;
                                    $impresion = $i['impresion'];

                                    if (strpos($nombreCheque, '/') !== false) {
                                        $p = explode('/', $nombreCheque);
                                        $i->update([
                                            // actualiza el campo nombrec a 0
                                            'nombrec' => $p[1],
                                        ]);
                                    }

                                    if (!empty($docAdi[0])) {
                                        if (strpos($docAdi[0], '/') !== false) {
                                            foreach ($i->doc_relacionados as $doc) {
                                                $pp = explode('/', $doc);
                                                $i->pull('doc_relacionados', $doc);
                                                $i->push('doc_relacionados', $pp[1]);
                                            }
                                        }
                                    }

// echo $asignado;
                                @endphp

                                <tbody>
                                    <!--- verificar si existen pagos vinculados  "CANCELADOS"-->
                                    @php

                                        $Pcancelado=MetadataR::where('cheques_id', $id)->where('estado','Cancelado')->first(); ///consulta a metadata_r
                                         if($Pcancelado){   $alertPagoCancelado ="#ffcc00";}else{$alertPagoCancelado ="style='background-color: white";}
                                    @endphp
                                   <div id="section{{$id}}">
                                    {{-- Cuerpo de la tabla con la funcion de expancion --}}
                                    <tr    onclick="showHideRow('{{ $id }}');">

                                        <td>



                                            @if ( $tipoO != 'Parcialidad'  &&  $tipo != 'Débito' && $tipo != 'Efectivo' && $tipoO != 'Otro' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                                @php
                                                    Cheques::find($id)->update(['pendi' => 1]);
                      $completo=$diferenciaP.$faltaxml.$nombreChequeP.$asignado.$tO;
                                                @endphp
                                                        @if($completo != '00121')


                                                <a style="color:red; padding: 0px 5px 0px 5px;"
                                                    class="parpadea fas fa-exclamation"
                                                    onclick="alertaP({{ $diferenciaP }},{{ $faltaxml }}, {{ $nombreChequeP }},{{$asignado}},{{$tO}} )"></a>
                                                    @endif
                                                    @else


                                                @php
                                                    Cheques::find($id)->update(['pendi' => 0]);
                                                @endphp
                                                @if ($verificado == 1 && Auth::user()->tipo)
                                                    @switch($contabilizado)
                                                        @case(0)
                                                            <a class="parpadea icons fa fa-check" style="color: green"
                                                                aria-hidden="true" onclick="alert('Revisado')"></a>
                                                        @break

                                                        @case(1)
                                                            <a class="parpadea icons fas fa-calculator" style="color: blue"
                                                                aria-hidden="true" onclick="alert('Contabilizado')"></a>
                                                        @break

                                                        @default
                                                    @endswitch
                                                @endif
                                            @endif
                                            {{ $fecha }} &nbsp;
                                        </td>

                                        {{-- Num de factura --}}
                                        <td>
                                            <a style="color:#3498DB">{{ Str::limit($numCheque, 20) }}</a>
                                        </td>

                                        {{-- Benficiario --}}
                                        <td>
                                            {{ Str::limit($beneficiario, 20) }}
                                        </td>

                                        {{-- Tipo de operacion --}}
                                        <td data-tableexport-display="none">
                                            {{ $tipoO }}
                                        </td>

                                        {{-- Forma de pago --}}
                                        <td>
                                            <span class="invoice-amount">{{ $tipo }}</span>
                                        </td>

                                        {{-- Pagado --}}
                                        <td>
                                            @if (session()->get('idnominas') && isset($i->$nomina) )

                                            <span class="invoice-amount">${{ number_format( $i->$nomina, 2) }}</span>
                                             @else
                                            <span class="invoice-amount">${{ number_format($importeC, 2) }}</span>
                                            @endif



                                        </td>

                                        {{-- $ de CFDI --}}
                                        <td>
                                            @if ($tipoO == 'Nómina')
                                            <span class="invoice-amount">----</span>
                                           @else
                                            <span class="invoice-amount">${{ number_format($sumaxml, 2) }}</span>
                                            @endif
                                        </td>

                                        {{-- Comprobar --}}
                                        <td data-tableexport-display="none">
                                            @if ($tipoO == 'Nómina')
                                            <span class="invoice-amount">----</span>
                                              @else
                                            <span class="invoice-amount">${{ $diferencia }}</span>

                                            @endif
                                        </td>
                                      {{-- Nominas vinculadas --}}
                                        <td>
                                            @if ($tipoO == 'Nómina' && $asignado ==1 || $asignado == 2)


                                            <i
                                            data-toggle="modal" wire:click="$emitTo('ver-nominas-asignadas','refreshVerNominas')"
                                            data-target="#VerNominasAsignadas{{ $id }}"
                                            style="color: #3498DB;"
                                         class=" {{ $class }} fas fa-balance-scale bx bx-git-repo-forked align-middle"></i>
                                         @endif
                                        </td>
                                        {{-- - Detalles pendientes --}}
                                        <td style="display:none;" data-tableexport-display="always" data-tableexport-display="always">

                                                <!-- se muestra funcion pendientes() -->
                                                @php

                                                    $salto = '<br>';
                                                    $msg = '';
                                                    $msg2 = '';
                                                    $msg3 = '';
                                                    if ($faltaxml == 0) {
                                                        $msg = "- No tiene CFDI's vinculados.";
}
if ($nombreChequeP == 0) {
    $msg2 = '- No tiene pdf asociado.';
}
if ($diferenciaP == 0) {
    $msg3 = '- Existe diferencia con el importe total.';
}

    echo $msg .  $msg2 . $msg3 ;
                                                @endphp

                                        </td>
                                        {{-- - comentarios pendientes --}}
                                        <td style="display:none;" data-tableexport-display="always">
                                          @if (empty($comentario))
                                            <span class="invoice-amount">No hay comentarios.</span>
                                            @else
                                            {{ $comentario }}
                                          @endif



                                        </td>



                                        {{-- ... --}}
                                        <td></td>
                                    </tr>

                                    {{-- Segunda fila del movimiento --}}
                                    <tr id="hidden_row{{ $id }}" class="hidden_row">
                                        <td colspan=12 style="  background-color:rgba(242,246,249,0.2);">
                                            {{-- Encabezado --}}
                                            <a style="color:#3498DB">{{ $numCheque }}</a>
                                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                            {{ $beneficiario }}

                                            <br>

                                            {{-- Contenido --}}
                                            <div class="box">
                                                {{-- Ajuste --}}
                                                @if (Auth::user()->tipo)
                                                    <div>
                                                        <div class="tr"> Ajuste</div>
                                                        @if ($ajuste != 0)
                                                            @php $class="content_true" @endphp
                                                        @else
                                                            @php $class="icons" @endphp
                                                        @endif
                                                        {{-- Condicional para las acciones de movimientos ya revisados --}}

                                                        <a class="{{ $class }} fas fa-balance-scale"
                                                            data-toggle="modal"
                                                            data-target="#ajuste{{ $id }}"></a>

                                                    </div>
                                                @endif

                                                {{-- Notas --}}
                                                <div>
                                                    <div class="tr"> Nota(s)</div>
                                                    @if (!empty($comentario))
                                                        @php $class_c="content_true" @endphp
                                                    @else
                                                        @php $class_c="icons" @endphp
                                                    @endif
                                                    <a id="nota{{ $id }}"
                                                        class="{{ $class_c }} fas fa-sticky-note"
                                                        data-toggle="modal"
                                                        data-target="#comentarios-{{ $id }}"> </a>
                                                </div>

                                                {{-- PDF --}}
                                                <div>
                                                    <div class="tr">Pago</div>
                                                    @if ($nombreCheque != '0')
                                                        <a id="rutArc" href="{{ $rutaArchivo }}"
                                                            target="_blank"></a>
                                                        @php $class_p="content_true_pdf" @endphp
                                                    @else
                                                        @php $class_p="icons" @endphp
                                                    @endif

                                                    @if ($nombreCheque == '0')
                                                        <a id="{{ $id }}"
                                                            class="{{ $class_p }} fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            data-target="#pdfcheque{{ $id }}"
                                                            data-backdrop="static" data-keyboard="false"
                                                            onclick="filepondEditCheque(this.id)"> </a>
                                                    @else
                                                        <a id="{{ $id }}"
                                                            class="{{ $class_p }} fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            data-target="#pdfcheque{{ $id }}"
                                                            onclick="filepondEditCheque(this.id)"> </a>
                                                    @endif
                                                </div>

                                                {{-- Documentos adicionales --}}
                                                <div>
                                                    <div class="tr"> Doctos. Adicionales</div>
                                                    {{-- @if ($verificado == 1)
                                                        <a class="icons fas fa-upload"
                                                            onclick="alert('ya esta revisado no puedes hacer nada :)')"></a>
                                                    @else
                                                        <a class="icons fas fa-upload" data-toggle="modal"
                                                            data-controls-modal="#uploadRelacionados"
                                                            name="{{ $id }}" data-backdrop="static"
                                                            data-keyboard="false" onclick="filepond(this.name)"
                                                            data-target="#uploadRelacionados"></a>
                                                    @endif --}}


                                                    <a class="icons fas fa-upload" data-toggle="modal"
                                                        data-controls-modal="#uploadRelacionados"
                                                        name="{{ $id }}" data-backdrop="static"
                                                        data-keyboard="false" onclick="filepond(this.name)"
                                                        data-target="#uploadRelacionados"></a>

                                                    &nbsp; | &nbsp;





                                                    @if (!$docAdi['0'] == '')
                                                        @php $class="content_true" @endphp
                                                    @else
                                                        @php $class="icons" @endphp
                                                    @endif


                                                    <a class="{{ $class }} fas fa-folder-open"
                                                        data-toggle="modal"
                                                        wire:click="$emitTo('relacionados', 'refreshComponent')"
                                                        data-target="#relacionados-{{ $id }}"></a>
                                                </div>

                                                {{-- Vinculadas --}}
                                                @if ($faltaxml != 0)
                                                    <div>
                                                        <div class="tr">Facturas</div>
                                                        <a wire:click="$emitTo('facturas-vinculadas','refrescarModalFacturas')"
                                                            class="icons fas fa-eye" style="color: #3498DB"
                                                            data-toggle="modal"
                                                            data-target="#facturasVinculadas{{ $id }}"></a>
                                                    </div>
                                                @endif




                                                {{-- Editar --}}
                                                <div>
                                                    <div class="tr">Editar</div>
                                                    {{-- Condicional para acciones con movimientos revisados --}}
                                                    {{-- @if ($verificado == 1)
                                                        <a class="icons fas fa-edit" data-toggle="modal"
                                                            data-target="#Revisado"></a>
                                                    @else
                                                        <a class="icons fas fa-edit" data-toggle="modal"
                                                            data-target="#editar-{{ $id }}"></a>
                                                    @endif --}}

                                                    <a class="icons fas fa-edit" data-toggle="modal"
                                                        data-target="#editar-{{ $id }}"></a>
                                                </div>

                                                {{-- Eliminar cheque --}}
                                                <div>
                                                    <div class="tr">Eliminar Cheque</div>
                                                    {{-- Condicional para acciones con movimientos revisados --}}
                                                    {{-- @if ($verificado == 1)
                                                        <a class="icons fas fa-trash-alt fa-lg" data-toggle="modal"
                                                            data-target="#Revisado"></a>
                                                    @else
                                                        <a class="icons fas fa-trash-alt fa-lg" data-toggle="modal"
                                                            data-target="#eliminar-{{ $id }}"></a>
                                                    @endif --}}

                                                    <a class="icons fas fa-trash-alt fa-lg" data-toggle="modal"
                                                        data-target="#eliminar-{{ $id }}"></a>
                                                </div>

                                                {{-- Revisar --}}
                                                @if (Auth::user()->tipo)
                                                    <div>
                                                        <div class="tr">Revisado</div>
                                                        @if ($tipoO != 'Parcialidad' && $tipo != 'Débito' && $tipo != 'Efectivo' && $tipoO != 'Otro' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                                            @php Cheques::find($id)->update(['pendi' => 1]); @endphp
                                                        @elseif($verificado == 0)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    wire:model="revisado" value="{{ $id }}"
                                                                    name="stOne{{ $id }}"
                                                                    id="Revi{{ $id }}">
                                                                <label class="form-check-label"
                                                                    for="Revi{{ $id }}">Revisar</label>
                                                            </div>
                                                        @else
                                                            <div id="Revisado{{ $id }}"
                                                                onclick="ToolRevisado(this.id)">
                                                                <div id="{{ $id }}"
                                                                    class="RevisadoContainer"
                                                                    onclick="MostrarRevisado(this.id)">
                                                                    <a class="icons far fa-check-circle BtnRevisado"
                                                                        style="color: green"></a>
                                                                    <div id="MostrarRevi{{ $id }}"
                                                                        class="MensajeContainer">
                                                                        <div class="Contenido">
                                                                            <p class="TextoMensaje">Revisado el: </p>
                                                                            <p class="TextoMensaje">
                                                                                {{ $revisado_fecha }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Contabilizado --}}
                                                    <div>
                                                        @if ($verificado == 1 and $contabilizado == 1)
                                                            <div class="tr">Contabilizado</div>
                                                        @else
                                                            <div class="tr">Póliza</div>
                                                        @endif

                                                        @if ($verificado == 1 and $contabilizado == 0)
                                                            <a class="icons fas fa-file-contract" data-toggle="modal"
                                                                data-target="#poliza{{ $id }}"></a>
                                                        @elseif ($verificado == 1 and $contabilizado == 1)
                                                            <div id="{{ $id }}" class="RevisadoContainer"
                                                                onclick="MostrarConta(this.id)">
                                                                <i style="color: blue; "
                                                                    class="icons fas fa-calculator"></i>
                                                                <div id="MostrarConta{{ $id }}"
                                                                    class="MensajeContainer">
                                                                    <div class="Contenido">
                                                                        <p class="TextoMensaje">{{ $poliza }}
                                                                        </p>
                                                                        <p class="TextoMensaje">
                                                                            {{ $contabilizado_fecha ?? $contabili_fecha }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($tipo != 'Débito' && $tipo != 'Efectivo' && $tipoO != 'Otro' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                                            @php Cheques::find($id)->update(['pendi' => 1]); @endphp
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Impresion --}}
                                                <div>
                                                    <div class="tr">Impreso</div>
                                                    @if ($impresion == 'on')
                                                        <i class="icons fas fa-print fa-2x" style="color: green"></i>
                                                    @endif
                                                    @if ($impresion == '')
                                                        <div class="ImpContainer">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    wire:model="impresion"
                                                                    value="{{ $id }}"
                                                                    name="s{{ $id }}"
                                                                    id="Conta{{ $id }}">
                                                                <label class="form-check-label"
                                                                    for="Conta{{ $id }}">Impresión</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Cheques ID --}}
                                                <div>
                                                    <div class="tr">Cheque Id</div>
                                                    &nbsp;&nbsp;&nbsp; {{ $id }}
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Llamando a las vistas de otros componentes --}}
                                        <livewire:ajuste :ajusteCheque="$i" :wire:key="'user-profile-one-'.$i->_id">
                                         <livewire:comentarios :comentarioCheque="$i"
                                                :wire:key="'user-profile-two-'.$i->_id">
                                          <livewire:relacionados :filesrelacionados=$i
                                                    :wire:key="'user-profile-five-'.$i->_id">
                                          <livewire:pdfcheque :pdfcheque=$i
                                                        :wire:key="'user-profile-tre-'.$i->_id">
                                         <livewire:editar :editCheque=$i
                                                            :wire:key="'user-profile-four-'.$i->_id">
                                         <livewire:poliza :polizaCheque=$i
                                                                :wire:key="'user-profile-six-'.$i->_id">
                                            <livewire:eliminar :eliminarCheque=$i
                                                                    :wire:key="'user-profile-seven-'.$i->_id">

                                                                    @if (!$i->faltaxml == 0)
                                                                        <livewire:facturas-vinculadas
                                                                            :facturaVinculada=$i
                                                                            :wire:key="'user-profile-eight-'.$i->_id">
                                                                    @endif
                                 <livewire:ver-nominas-asignadas :asignadas=$i
                                                                    :wire:key="'user-profile-kgytr-'.$i->_id">
                                    </tr>
                                   </div>
                                </tbody>
                            @endforeach



                        </table>

                        {{ $colCheques->links() }} {{-- Animacion de cargando --}}

<!--  tabla hidden footer-->
<table  style="width:100%">
    <tr>
        {{-- <img width="150" src="img/logo-contarapp-01.png" > --}}
         <th ></th>
      <th >e-cont.mx © @php echo date('Y')  @endphp Todos Derechos Reservados</th>


    </tr>


  </table>
                        {{-- -- si $colCheques es mayor a 0 - --}}
                        @if ($colCheques->count() > 0)
                            <div wire:loading>
                                <div style=" color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                                    <div></div>
                                    <div></div>
                                </div>

                                <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                            </div>





                        @endif
                        @livewireScripts
                    </div>
                </section>
            </div>
        </div>
    </div>

    {{-- Llamamos a las vistas de otros componentes --}}
    <livewire:agregarcheque>
        <livewire:vincular-pagos-automatico>
        <!-- llamamos al componente Vinculacion-autmoatica-traslados -->
        <livewire:vincular-traslados-automatico>

            <livewire:uploadrelacionados>
                @include('livewire.demo')

                {{-- Modal para las acciones bloqueadas por movimientos revisadps --}}
                {{-- Modal de detalles de cuentas por pagar --}}
                {{-- Creacion del modal --}}
                <div wire:ignore.self class="modal fade" id="Revisado" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            {{-- Encabezado --}}
                            <div class="modal-header">
                                <h6 class="modal-title" id="TitleRevisado"><span style="text-decoration: none;"
                                        class="icons far fa-check-circle"> Movimiento revisado</span></h6>
                                <button id="BtnCloseRevi" type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true close-btn">×</span>
                                </button>
                            </div>
                            {{-- Cuerpo del modal --}}
                            <div class="modal-body">



                                <p id="ModalRevi">No puedes realizar esta acción en un movimiento revisado</p>

                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    //Emitir los datos de la empresa al componente
                    $(document).ready(function() {
                        //Guardamos en variables locales el contenido de sessionstorage
                        var IdMovi = sessionStorage.getItem('idmovi');
                        var Empresa = sessionStorage.getItem('empresa');

                        //Condicion para saber si las variables no estan vacias
                        if (IdMovi !== null && Empresa !== null) {
                            //Emitimos los datos al controlador
                            window.livewire.emit('mostvincu', {
                                idmovi: IdMovi,
                                empresa: Empresa
                            });
                            sessionStorage.clear();
                        }
                    });
                </script>
</div>
<script>
    //Emitir los datos de la empresa al componente
    $(document).ready(function() {
        //Guardamos en variables locales el contenido de sessionstorage
        var IdMovi = sessionStorage.getItem('idmovi');
        var Empresa = sessionStorage.getItem('empresa');

        //Condicion para saber si las variables no estan vacias
        if (IdMovi !== null && Empresa !== null) {
            //Emitimos los datos al controlador
            window.livewire.emit('mostvincu', {
                idmovi: IdMovi,
                empresa: Empresa
            });
            sessionStorage.clear();
        }
    });
</script>




</div>
@php
Session::forget('rfcnomina');

@endphp
