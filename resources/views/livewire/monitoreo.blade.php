<div>
    {{-- Estilo del boton de notificaciones --}}
    <style>
        .notification {
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }

        .notification .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            padding: 5px 10px;
            border-radius: 30%;
            background: #ff7a7a;
            color: white;
        }
    </style>

    {{-- Libreria de exportacion --}}
    <script src="{{ asset('js/tableExport/libs/FileSaver/FileSaver.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/tableExport.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/jsPDF/jspdf.umd.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/pdfmake.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/vfs_fonts.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/js-xlsx/xlsx.core.min.js') }}" defer></script>

    {{-- Libreria de graficos --}}
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js') }}" defer></script>

    {{-- JS --}}
    <script src="{{ asset('js/monitoreo.js') }}" defer></script>

    @php
        use App\Models\MetadataE;
        use App\Models\MetadataR;
        
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
        
        //Descomponemos el Json en un objeto
        $consulmetaporhora = json_decode($consulemit);
    @endphp

    {{-- Contenedor para mantener responsivo el contenido del modulo --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    {{-- Select para selccionar la empresa (Contadores) --}}
                    @empty(!$empresas)
                        {{-- Mostramos el RFC de la empresa que se selecciona --}}
                        <label for="inputState">Empresa: {{ $empresa }}</label>
                        <select wire:model="rfcEmpresa" id="empresaselect" class="select form-control">
                            <option value="">--Selecciona Empresa--</option>

                            {{-- Llenamos el select con las empresa vinculadas --}}
                            <?php $rfc = 0;
                            $rS = 1;
                            foreach ($empresas as $fila) {
                                echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                            } ?>
                        </select>

                        <br>
                    @endempty

                    {{-- Select para selccionar la sucursal (Contadores) --}}
                    @if (!empty($infoempre['Sucursales']))
                        {{-- Mostramos el RFC de la sucursal que se selecciona --}}
                        <label for="inputState">Sucursal</label>
                        <select wire:model="sucursal" class="select form-control">
                            <option value="">--Selecciona Sucursal--</option>

                            {{-- Mostramos las sucursales --}}
                            @foreach ($infoempre['Sucursales'] as $dataempre)
                                <option value="{{ $dataempre['Clave'] }}">{{ $dataempre['Nombre'] }}</option>
                            @endforeach
                        </select>

                        <br>
                    @endif

                    {{-- Mensaje de alerta cuando no se selecciona CFDI --}}
                    <div id="Mnstotaldias" hidden>
                        <div id="mnsexcep" class="alert alert-danger">
                        </div>
                    </div>

                    <br>
                    <br>

                    {{-- Encabezado del dia --}}
                    <div align="center">
                        {{-- Condicional para saber si se selecciono un rango --}}
                        @if ($fechainic == $fechafin)
                            <h3>Facturación {{ $fechainic }}</h3>
                        @else
                            <h3>Facturación {{ $fechainic }} al {{ $fechafin }}</h3>
                        @endif
                    </div>

                    <div wire:loading>
                        <br>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                        <br>
                    </div>

                    <br>

                    {{-- Filtros de busqueda --}}
                    <label>Periodo a consultar (Maximo de 31 dias)</label>
                    <form wire:submit.prevent="ConsulEmit">
                        {{-- Filtros de busqueda --}}
                        <div class="form-inline mr-auto">
                            <input class="form-control" id="fecha" wire:model.defer="fechainic" type="date"
                                min="2014-01-01" max={{ date('Y-m-d') }} required> &nbsp;&nbsp;

                            A &nbsp;&nbsp;

                            <input class="form-control" id="fecha" wire:model.defer="fechafin" type="date"
                                min="2014-01-01" max={{ date('Y-m-d') }} required> &nbsp;&nbsp;

                            <button id="btnconsulmoni" class="btn btn-secondary BtnVinculadas" type="submit"
                                wire:loading.attr="disabled">Buscar</button>
                            &nbsp;&nbsp;

                            {{-- Espaciado --}}
                            <div id="espmonifilt" style="width: 18.6em;"></div>

                            <button {{ $active }} type="button" data-backdrop="static" data-keyboard="false"
                                data-toggle="modal" data-target="#factuporclient"
                                class="btn btn-secondary BtnVinculadas notification">
                                <span>Factu. por cliente</span>
                                <span class="badge" id="numinconsis"></span>
                            </button>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <button {{ $active }} type="button" data-backdrop="static" data-keyboard="false"
                                data-toggle="modal" data-target="#factupormes" class="btn btn-secondary BtnVinculadas">
                                Factu. del mes</button>
                            &nbsp;&nbsp;
                        </div>
                    </form>

                    <br>


                    <div class="row">
                        {{-- Tabla de informacion de las facturas por hora --}}
                        <div class="col">
                            <div class="table-responsive conttablemoni">
                                <table id="factuhisto" class="monihora {{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="text-center align-middle">Facturación por hora</th>
                                            {{-- Columnas --}}
                                        <tr>
                                            <th class="text-center align-middle">Hora</th>
                                            <th class="text-center align-middle">Facturas</th>
                                            <th class="text-center align-middle">Monto</th>
                                            <th class="text-center align-middle">Detalles</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            //Variables para obtener el total de los registros
                                            $totalfact = 0;
                                            $totalmonto = 0;
                                            $cantidades = [];
                                            $montos = [];
                                        @endphp

                                        {{-- Cuerpo de la tabla --}}
                                        @for ($i = 0; $i <= 23; $i++)
                                            @php
                                                //Variables de contadores y sumatorios
                                                $facturas = 0;
                                                $monto = 0;
                                                
                                                //Condicional para saber si hay un dato de consulta
                                                if ($consulmetaporhora) {
                                                    //Ciclo para obtener los datos de la consulta
                                                    foreach ($consulmetaporhora as $factura) {
                                                        //Obtenemos la fecha
                                                        $hora = date('G', strtotime($factura->FechaEmision));
                                                
                                                        //Condicional para verificar si la hora concuerda
                                                        if ($hora == $i) {
                                                            //Si concuerda vamos acumulando las facturas
                                                            $facturas++;
                                                            //Si concuerda vamos sumando los totales
                                                            $monto += floatval($factura->Total);
                                                        }
                                                    }
                                                
                                                    //Obtenemos la cantidad de facturas
                                                    array_push($cantidades, $facturas);
                                                    //Obtenemos la cantidad de facturas
                                                    array_push($montos, $monto);
                                                
                                                    //Sumamos la el total obtenido
                                                    $totalfact += $facturas;
                                                    //Sumamos la el total obtenido
                                                    $totalmonto += $monto;
                                                }
                                            @endphp

                                            <tr>
                                                {{-- Horas --}}
                                                <td>
                                                    {{ $i }}
                                                </td>

                                                {{-- Facturas --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un dato de consulta --}}
                                                    @if ($consulmetaporhora)
                                                        {{-- Mostramos el total --}}
                                                        {{ $facturas }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                {{-- Monto --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un dato de consulta --}}
                                                    @if ($consulmetaporhora)
                                                        {{-- Mostramos el total --}}
                                                        $ {{ number_format($monto, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                {{-- Detalles --}}
                                                <td>
                                                    <a data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                                        data-target="#factuporhora{{ $i }}"
                                                        class="icons fas fa-eye"></a>
                                                </td>
                                            </tr>
                                        @endfor
                                        <tr>
                                            {{-- Total --}}
                                            <td><b>Total:</b></td>
                                            <td>{{ $totalfact }}</td>
                                            <td>$ {{ number_format($totalmonto, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Grafico de la tabla / Promedios --}}
                        <div class="col">
                            <div style="width:33em">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title mb-0"><b>Facturas por hora</b></h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="cantifactuhora" width="1000" height="1000"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive conttablemoni">
                                <table id="factuhisto" class="monihora {{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Facturas en cantidad</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle">Concepto</th>
                                            <th class="text-center align-middle">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            //Obtenemos lo datos de estadisica
                                            //Promedio
                                            $promediocanti = floatval($totalfact) / 23;
                                        @endphp

                                        <tr>
                                            <td>
                                                Promedio
                                            </td>

                                            <td>
                                                {{ number_format($promediocanti, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Maximo
                                            </td>

                                            <td>
                                                @if (!empty($cantidades))
                                                    {{ max($cantidades) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Minimo
                                            </td>

                                            <td>
                                                @if (!empty($cantidades))
                                                    {{ min($cantidades) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <br>

                            <div class="table-responsive conttablemoni">
                                <table id="factuhisto" class="monihora {{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Facturas en $</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle">Concepto</th>
                                            <th class="text-center align-middle">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            //Obtenemos lo datos de estadisica
                                            //Promedio
                                            $promediomonto = floatval($totalmonto) / 23;
                                        @endphp

                                        <tr>
                                            <td>
                                                Promedio
                                            </td>

                                            <td>
                                                $ {{ number_format($promediomonto, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Maximo
                                            </td>

                                            <td>
                                                @if (!empty($montos))
                                                    $ {{ number_format(max($montos), 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Minimo
                                            </td>

                                            <td>
                                                @if (!empty($montos))
                                                    $ {{ number_format(min($montos), 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>
    {{-- Llamado de modale --}}
    @if ($this->diainter <= 31)
        @if ((!empty($this->rfcEmpresa) && !empty($this->sucursal)) || (!empty($this->rfcEmpresa) && empty($this->infoempre['Sucursales'])))
            {{-- Facturas por hora --}}
            <livewire:monithora :empresa=$empresa :emitidos=$consulemit :fechainic=$fechainic :fechafin=$fechafin
                :wire:key="'user-profile-one-'.$consulemit.$empresa.$fechainic.$fechafin">

                <livewire:monitclient :empresa=$empresa :emitidos=$consulemit :fechainic=$fechainic :fechafin=$fechafin
                    :wire:key="'user-profile-two-'.$empresa.$consulemit.$fechainic.$fechafin">

                    <livewire:monitmes :empresa=$empresa :sucursal=$sucursal :fechainic=$fechainic :fechafin=$fechafin
                        :wire:key="'user-profile-three-'.$empresa.$sucursal.$fechainic.$fechafin">
        @endif
    @endif
</div>
