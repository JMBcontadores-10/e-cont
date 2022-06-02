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

    {{-- Contenedor --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    {{-- Boton para crear un nuevo cheque --}}
                    <div class="invoice-create-btn mb-1">
                        <a data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
                            data-keyboard="false" data-target="#nuevo-cheque"
                            class="btn btn-primary glow invoice-create">
                            Nuevo Cheque/Transferencia
                        </a>
                        {{-- ------------Boton para vinculacion atomatica de pagos a PPD---------- --}}

                        <button wire:click="$emitTo('vincular-pagos-automatico','refreshPagoAutomatico')"
                            class=" btn btn-secondary " style="vertical-align:middle">
                            <a data-toggle="modal" data-controls-modal="#nuevo-cheque" data-backdrop="static"
                                data-keyboard="false" data-target="#vinculacionAutomtica">
                                <span>Vincular pagos </span>
                            </a>
                        </button>

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

                    {{-- Condicional para mostrar un listado de empresas --}}



                    {{-- Filtros de busqueda --}}
                    <div class="form-inline mr-auto">

                        {{-- Busqueda por texto --}}
                        <input wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Filtro"
                            aria-label="Search">
                        &nbsp;&nbsp;


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
                                <input wire:model="todos" type="checkbox" class="custom-control-input bg-danger" checked
                                    name="customCheck" id="customColorCheck4">
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

                    {{-- Tabla de contenido --}}
                    <div class="table-responsive">
                        <table id="example" class="{{ $class }}" style="width:100%">
                            {{-- Encabezado --}}
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
                                    <th>...</th>
                                </tr>
                            </thead>

                            {{-- Acciones previas a mostrar el cuerpo de la tabla --}}
                            @php
                                $arreglo = '';
                                $totalVinculadas = 0;
                            @endphp


                        </table>

                        @livewireScripts
                    </div>
                </section>
            </div>
        </div>
    </div>

    {{-- Llamamos a las vistas de otros componentes --}}
    <livewire:agregarcheque>
        <livewire:vincular-pagos-automatico>
            <livewire:uploadrelacionados>



</div>
