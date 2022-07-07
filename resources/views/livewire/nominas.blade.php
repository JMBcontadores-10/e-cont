<div> {{-- ---din principal--- --}}


    <!-- TableExport js --->
    {{-- ----Referencias: https://github.com/hhurz/tableExport.jquery.plugin
                        https://examples.bootstrap-table.com/#extensions/export.html
   ------------- --}}
    <script src="{{ asset('js/tableExport/libs/FileSaver/FileSaver.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/tableExport.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/jsPDF/jspdf.umd.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/pdfmake.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/vfs_fonts.js') }}" defer></script>
    @php

        /// importar clase nomina
        use App\Http\Classes\Nomina;
        use Illuminate\Support\Facades\DB;

        $Nomina = new Nomina();

        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }

        $date = date('Y-m-d');

        $suma = 0;
    @endphp
<!--   Recibir variables desde cheques y transferencias -->
@if (session()->get('rfcnomina'))
{{-- <div class="alert alert-success">

</div> --}}

<script>
    $(document).ready(function() {
        // alert('{{ session('id') }}');
        window.livewire.emit('filtrarRequest', '{{ Session::get('rfcnomina') }}', '{{ Session::get('mes') }}','{{Session::get('anio')}}');

    });
</script>

@php
    Session::forget('rfcnomina');
    Session::forget('mes');
    Session::forget('anio');
@endphp
@endif


    {{-- Contenedor --}}

<style>


.content-wrapper{

    z-index:0;
}

.content-body{

    z-index:0;
}


</style>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">




                    {{-- Filtros de busqueda --}}

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
                            } ?>
                        </select>

                        <br>
                    @endempty

                    {{-- Filtros de busqueda --}}
                    <div class="form-inline mr-auto">


                        {{-- Busqueda por año --}}
                        <label for="inputState">Año</label>
                        <select wire:loading.attr="disabled" wire:model="anio" id="inputState2"
                            class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;
                        {{-- Busqueda por mes --}}
                        <label for="inputState">Mes</label>
                        <select wire:model="mes" id="inputState1" wire:loading.attr="disabled"
                            class=" select form-control">
                            <option value="00">--Selecciona--</option>
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;


                    </div>

                    {{-- fin seccion de  Filtros --}}
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

                    {{-- {{var_dump($list)}} --}}
                    {{-- Tabla de contenido --}}
                    <div class="table-responsive">
                        <table id="datos" class="{{ $class }}" style="width:100%">
                            {{-- Encabezado --}}
                            <thead>
                                <tr>
                                    <th>
                                        <span class="align-middle">#Periodo</span>
                                    </th>
                                    <th>Periodo</th>
                                    <th>Fecha Pago </th>
                                    <th>Raya</th>
                                    <th>Recibos de <br> Nómina </th>
                                    <th>Detalles</th>
                                    <th>Total pago</th>
                                    <th>ISR </th>
                                    <th>Asignar cheque</th>

                                </tr>
                            </thead>
                            {{-- -------  INCIO DEL FOR   --- --}}

                            <tbody>
                                @foreach ($nominas as $nom)

                                    <tr>
                                        <td class="text-center align-middle">
                                            @if ($nom['Complemento.0.Nomina.TipoNomina'] == 'E')
                                                <a class="icon_basic">E</a>
                                            @endif
                                            {{ $nom['Folio'] }}
                                        </td>
                                        @if (isset($nom['Complemento.0.Nomina.FechaInicialPago']))
                                            @php  $fechaPago=$nom['Complemento.0.Nomina.FechaPago']  ;    @endphp
                                            <td class="text-center align-middle">
                                                {{ $nom['Complemento.0.Nomina.FechaInicialPago'] }} al
                                                {{ $nom['Complemento.0.Nomina.FechaFinalPago'] }} </td>
                                            <td class="text-center align-middle">
                                                {{ $nom['Complemento.0.Nomina.FechaPago'] }} </td>
                                        @else
                                            @php  $fechaPago=$nom['Complemento.Nomina.FechaPago']  ;    @endphp
                                            <td class="text-center align-middle">
                                                {{ $nom['Complemento.Nomina.FechaInicialPago'] }} al
                                                {{ $nom['Complemento.Nomina.FechaFinalPago'] }} </td>
                                            <td class="text-center align-middle">
                                                {{ $nom['Complemento.Nomina.FechaPago'] }} </td>
                                        @endif
                                        @php
                                            /// validacion de existencia de arcivo para activar el icono con contenido/////
                                            $ruta = 'contarappv1_descargas/' . $this->rfcEmpresa . '/' . $anio . '/Nomina/Periodo' . $nom['Folio'] . '/Raya/NominaPeriodo' . $nom['Folio'] . '.pdf';
                                            $rutaR = 'contarappv1_descargas/' . $this->rfcEmpresa . '/' . $anio . '/Nomina/Periodo' . $nom['Folio'] . '/RecibosNomina/RecibosPeriodo' . $nom['Folio'] . '.pdf';

                                            if (Storage::disk('public2')->exists($ruta)) {
                                                $clas="content_true";
                                            } else {
                                                $clas="icons";
                                            }

                                            if (Storage::disk('public2')->exists($rutaR)) {
                                                $clasR="content_true";
                                            } else {
                                                $clasR="icons";
                                            }

                                        @endphp


                                        <td class="text-center align-middle">

                                            <a wire:loading.attr="hidden"
                                                class="{{ $clas }} fas fa-clipboard-list"
                                                onclick="filepondRaya('{{ $this->rfcEmpresa }}','{{ $anio }}','{{ $nom['Folio'] }}')"
                                                data-toggle="modal"data-backdrop="static"
                                                data-target="#raya{{ $nom['Folio'] }}{{ $fechaPago }}"></a>
                                        </td>
                                        <td class="text-center align-middle">

                                            <a wire:loading.attr="hidden"
                                                class="{{ $clasR }}  fas fa-file-invoice"
                                                onclick="filepondRecibosNomina('{{ $this->rfcEmpresa }}','{{ $anio }}','{{ $nom['Folio'] }}')"
                                                data-toggle="modal"data-backdrop="static"
                                                data-target="#recibosnom{{ $nom['Folio'] }}{{ $fechaPago }}"></a>

                                        </td>
                                        <td class="text-center align-middle">
                                            <a wire:loading.attr="hidden" data-toggle="modal"
                                                data-controls-modal="#detallesEmpleados{{ $nom['Folio'] }}"
                                                data-backdrop="static" data-keyboard="false"
                                                data-target="#detallesEmpleados{{ $nom['Folio'] }}"
                                                class=" icons fas fa-eye"></a>
                                        </td>
                                        <td class="text-center align-middle">
                                            ${{ number_format($granTotal= $Nomina::TotalPagado($this->rfcEmpresa, $anio, $nom['Folio']), 2) }}
                                        </td>
                                        <td class="text-center align-middle">
                                            ${{ number_format($Nomina->ISR($this->rfcEmpresa, $anio, $nom['Folio']), 2) }}
                                        </td>
                                        <!-- TD que contiene el modal asignar cheque-->
                                        <td class="text-center align-middle">


                                            <a wire:loading.attr="hidden" data-toggle="modal"
                                                data-controls-modal="#asingnarCheque" name="14"
                                                id="{{ $nom['Folio'] }}" data-backdrop="static" data-keyboard="false"
                                                {{-- wire:click="$emitTo('asignar-cheque','refresAsignar')" --}}
                                                data-target="#asignarCheque{{ $nom['Folio'] }}"
                                                class="icons fas fa-money-check">
                                            </a>

                                        </td>
                                        <!-- TD que contiene el modal asignar cheque-->
                                    </tr>
                                    {{-- <livewire:lista-raya :raya="$nom" :wire:key="'user-profile-one-'.$nom['Folio']"> --}}
                                    @livewire('lista-raya', ['folio' => $nom['Folio'], 'RFC' => $this->rfcEmpresa, 'fecha' => $fechaPago, 'ruta' => $ruta], key('user-profile-one-' . $nom['Folio'] . $fechaPago))
                                    @livewire('recibosnomina', ['folio' => $nom['Folio'], 'RFC' => $this->rfcEmpresa, 'fecha' => $fechaPago], key('user-profile-twoo-' . $nom['Folio'] . $fechaPago))


                                        @livewire(
                                            'asignar-cheque',
                                            [
                                                'fecha' => $nom['Complemento.0.Nomina.FechaFinalPago'],
                                                'asignarCheque' => $nom['Folio'],
                                                'RFC' => $this->rfcEmpresa,
                                                'content' => 'icons',
                                                'serie' => $nom['Serie'],
                                                'mes'=>$this->mes,
                                                'granTotal' => $granTotal,

                                                'fechaPago' => $nom['Complemento.Nomina.FechaPago'],

                                            ],

                                            key('user-profile-three-' . $nom['Folio'] . $fechaPago),
                                        )


                                    @livewire('detallesempleados', ['anio' => $anio, 'fecha' => $fechaPago, 'folio' => $nom['Folio'], 'RFC' => $this->rfcEmpresa], key('user-profile-four-' . $nom['Folio'] . $fechaPago))

                                    @php $suma=0; @endphp

                                @endforeach

                            </tbody>

                            {{-- -------  FIN  DEL FOR   --- --}}

                        </table>

                    </div>

                </section>

                <livewire:agregarcheque>
                    <livewire:vincular-pagos-automatico>
                        <livewire:uploadrelacionados>

            </div>
        </div>
    </div>


</div>{{-- -----fin div principal----- --}}
