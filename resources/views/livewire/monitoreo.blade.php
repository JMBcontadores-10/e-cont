<div>
    {{-- Libreria de exportacion --}}
    <script src="{{ asset('js/tableExport/libs/FileSaver/FileSaver.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/tableExport.min.js') }}" defer></script>
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
    @endphp



    @if ($empresa)
        {{-- FACTURACION POR MES --}}
        {{-- Creacion del modal (BASE) --}}
        <div wire:ignore.self class="modal fade" id="factuporclient" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
                <div class="modal-content">
                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                                class="icons fas fa-comments">Facturación por mes </span></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    {{-- Cuerpo del modal --}}
                    <div class="modal-body">
                        
                    </div>
                </div>
            </div>
        </div>





        {{-- FACTURACION POR CLIENTE MODAL --}}
        {{-- Creacion del modal (BASE) --}}
        <div wire:ignore.self class="modal fade" id="factuporclient" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
                <div class="modal-content">
                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                                class="icons fas fa-comments">Facturación por clientes </span></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    {{-- Cuerpo del modal --}}
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">RFC</th>
                                        <th class="text-center align-middle">Razón social</th>
                                        <th class="text-center align-middle"># Fact. Emitidas</th>
                                        <th class="text-center align-middle">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        //Variables para obtener el total
                                        $totalfactu = 0;
                                        $totalmonto = 0;
                                    @endphp

                                    @foreach ($consulmetaclient as $datametaclient)
                                        @php
                                            //Realizaremos una consulta para saber el # de facturas y los montos
                                            $infometaemitclient = MetadataE::select('total')
                                                ->where('receptorRfc', $datametaclient->receptorRfc)
                                                ->whereBetween('fechaEmision', [$fechainic . 'T00:00:00', $fechafin . 'T23:59:59'])
                                                ->get();
                                            
                                            //Obtenemos el total
                                            $infometaclient = $infometaemitclient->count();
                                            
                                            //Sumamos el conteo de facturas
                                            $totalfactu += $infometaclient;
                                            
                                            //Variable para obtener el total
                                            $totalclient = 0;
                                            
                                            //Obtenemos el total
                                            foreach ($infometaemitclient as $datoemitclient) {
                                                $totalclient += $datoemitclient->total;
                                            }
                                            
                                            //Sumamos el monto de facturas
                                            $totalmonto += $totalclient;
                                        @endphp

                                        <tr>
                                            {{-- RFC --}}
                                            <td>
                                                {{ $datametaclient->receptorRfc }}
                                            </td>

                                            {{-- Razon social --}}
                                            <td>
                                                {{ $datametaclient->receptorNombre }}
                                            </td>

                                            {{-- # De factura --}}
                                            <td>
                                                {{ $infometaclient }}
                                            </td>

                                            {{-- Monto --}}
                                            <td>
                                                $ {{ number_format($totalclient, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        {{-- Total factu --}}
                                        <td>
                                            {{ $totalfactu }}
                                        </td>
                                        {{-- Total monto --}}
                                        <td>
                                            $ {{ number_format($totalmonto, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Modal que muestre las facturas --}}
        @for ($i = 0; $i <= 23; $i++)
            {{-- Modal de hisatorico --}}
            {{-- Creacion del modal (BASE) --}}
            <div wire:ignore.self class="modal fade" id="factuporhora{{ $i }}" tabindex="-1"
                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
                    <div class="modal-content">
                        {{-- Encabezado --}}
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                                    class="icons fas fa-comments">Facturas </span></h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true close-btn">×</span>
                            </button>
                        </div>
                        {{-- Cuerpo del modal --}}
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="{{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">Estado SAT</th>
                                            <th class="text-center align-middle">Tipo</th>
                                            <th class="text-center align-middle">Fecha Emit.</th>
                                            <th class="text-center align-middle">Fecha Timb.</th>
                                            <th class="text-center align-middle">Serie</th>
                                            <th class="text-center align-middle">Folio</th>
                                            <th class="text-center align-middle">UUID</th>
                                            <th class="text-center align-middle">Lugar Exped.</th>
                                            <th class="text-center align-middle">RFC Recept.</th>
                                            <th class="text-center align-middle">Nombre Recept.</th>
                                            <th class="text-center align-middle">Total</th>
                                            <th class="text-center align-middle">Forma Pago</th>
                                            <th class="text-center align-middle">Concepto</th>
                                            <th class="text-center align-middle">Detalles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Tabla --}}
                                        @foreach ($consulmetaporhora as $datametahora)
                                            @php
                                                //Contador de conceptos
                                                $ConceptCount = 0;
                                                
                                                $espa = new MetadataR();
                                                $fechaE = $datametahora->fechaEmision;
                                                $folioF = $datametahora->folioFiscal;
                                                $numero = (string) (int) substr($fechaE, 5, 2);
                                                $mesNombre = (string) (int) substr($fechaE, 5, 2);
                                                $anio = (string) (int) substr($fechaE, 0, 4);
                                                $mees = $espa->fecha_es($mesNombre);
                                                
                                                $rutaXml = "storage/contarappv1_descargas/$rfcEmpresa/$anio/Descargas/$numero.$mees/Emitidos/XML/$folioF.xml";
                                                $rutaPdf = "storage/contarappv1_descargas/$rfcEmpresa/$anio/Descargas/$numero.$mees/Emitidos/PDF/$folioF.pdf";
                                                
                                                //Obtenemos la fecha
                                                $horameta = date('G', strtotime($datametahora->fechaEmision));
                                            @endphp

                                            @php
                                                foreach ($consulxmlporhora as $dataxmlhora) {
                                                    //Obtenemos la fecha
                                                    $horaxml = date('G', strtotime($dataxmlhora->Fecha));
                                                
                                                    if ($horaxml == $i) {
                                                        //Vamos a obtener los datos necesarios del XML
                                                        $serie = $dataxmlhora->Serie;
                                                        $folio = $dataxmlhora->Folio;
                                                        $expedicion = $dataxmlhora->LugarExpedicion;
                                                        $forma = $dataxmlhora->FormaPago;
                                                        $concepto = $dataxmlhora['Conceptos.Concepto'];
                                                    }
                                                }
                                            @endphp

                                            @if ($horameta == $i)
                                                <tr>
                                                    {{-- Estado SAT --}}
                                                    <td>
                                                        {{ $datametahora->estado }}
                                                    </td>

                                                    {{-- Tipo --}}
                                                    <td>
                                                        {{ $datametahora->efecto }}
                                                    </td>

                                                    {{-- Fecha emision --}}
                                                    <td>
                                                        {{ $datametahora->fechaEmision }}
                                                    </td>

                                                    {{-- Fecha timbrado --}}
                                                    <td>
                                                        {{ $datametahora->fechaCertificacion }}
                                                    </td>

                                                    {{-- Serie --}}
                                                    <td>
                                                        {{ $serie }}
                                                    </td>

                                                    {{-- Folio --}}
                                                    <td>
                                                        {{ $folio }}
                                                    </td>

                                                    {{-- UUID --}}
                                                    <td>
                                                        {{ $datametahora->folioFiscal }}
                                                    </td>

                                                    {{-- Lugar de expedicion --}}
                                                    <td>
                                                        {{ $expedicion }}
                                                    </td>

                                                    {{-- RFC receptor --}}
                                                    <td>
                                                        {{ $datametahora->receptorRfc }}
                                                    </td>

                                                    {{-- Nombre receptor --}}
                                                    <td>
                                                        {{ $datametahora->receptorNombre }}
                                                    </td>

                                                    {{-- Total --}}
                                                    <td>
                                                        {{ $datametahora->total }}
                                                    </td>

                                                    {{-- Forma de pago --}}
                                                    <td>
                                                        {{ $forma }}
                                                    </td>

                                                    {{-- Concepto --}}
                                                    <td>
                                                        @if (isset($concepto[0]['Descripcion']))
                                                            {{ ++$ConceptCount }}.-
                                                            {{ Str::limit($concepto[0]['Descripcion'], 20) }}
                                                            <br>
                                                        @endif
                                                    </td>

                                                    {{-- Detalles --}}
                                                    <td>
                                                        @if ($datametahora->estado != 'Cancelado')
                                                            <a href="{{ $rutaXml }}"
                                                                download="{{ $folioF }}.xml">
                                                                <i class="fas fa-file-download fa-2x"></i>
                                                            </a>
                                                            <a href="{{ $rutaPdf }}" target="_blank">
                                                                <i class="fas fa-file-pdf fa-2x"
                                                                    style="color: rgb(202, 19, 19)"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    @endif

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
                    @endempty

                    <br>
                    <br>
                    <br>

                    {{-- Encabezado del dia --}}
                    <div align="center">
                        <h3>Facturación {{ $fechaayer }}</h3>
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
                    <label>Periodo a consultar</label>
                    <form wire:submit.prevent="ConsulMeta">
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

                            <button {{ $active }} type="button" class="btn btn-success BtnVinculadas"
                                onclick="exportReportToExcel('{{ $empresa }}')">Excel</button>
                            &nbsp;&nbsp;

                            <button {{ $active }} type="button" class="btn btn-danger BtnVinculadas"
                                onclick="exportReportToPdf('{{ $empresa }}')">Pdf</button>


                            {{-- Espaciado --}}
                            <div id="espmonifilt" style="width: 8.2em;"></div>

                            <button {{ $active }} type="button" data-backdrop="static" data-keyboard="false"
                                data-toggle="modal" data-target="#factuporclient"
                                class="btn btn-secondary BtnVinculadas">
                                Factu. por cliente</button>
                            &nbsp;&nbsp;

                            <button {{ $active }} type="button" class="btn btn-secondary BtnVinculadas">
                                Factu. del mes</button>
                            &nbsp;&nbsp;
                        </div>
                    </form>

                    <br>

                    <div class="row">
                        {{-- Tabla de informacion de las facturas por hora --}}
                        <div class="col">
                            <div id="conttablemoni" class="table-responsive">
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
                                                        {{-- Ciclo para obtener los datos de la consulta --}}
                                                        @foreach ($consulmetaporhora as $factura)
                                                            @php
                                                                //Obtenemos la fecha
                                                                $hora = date('G', strtotime($factura->fechaEmision));
                                                                
                                                                //Condicional para verificar si la hora concuerda
                                                                if ($hora == $i) {
                                                                    //Si concuerda vamos acumulando las facturas
                                                                    $facturas++;
                                                                }
                                                            @endphp
                                                        @endforeach

                                                        @php
                                                            //Obtenemos la cantidad de facturas
                                                            array_push($cantidades, $facturas);
                                                            //Sumamos la el total obtenido
                                                            $totalfact += intval($facturas);
                                                        @endphp

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
                                                        {{-- Ciclo para obtener los datos de la consulta --}}
                                                        @foreach ($consulmetaporhora as $factura)
                                                            @php
                                                                //Obtenemos la fecha
                                                                $hora = date('G', strtotime($factura->fechaEmision));
                                                                
                                                                //Variable acumuladora de facturas
                                                                if ($hora == $i) {
                                                                    //Si concuerda vamos sumando los totales
                                                                    $monto += floatval($factura->total);
                                                                }
                                                            @endphp
                                                        @endforeach

                                                        @php
                                                            //Obtenemos la cantidad de facturas
                                                            array_push($montos, $monto);
                                                            //Sumamos la el total obtenido
                                                            $totalmonto += floatval($monto);
                                                        @endphp

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
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="monihora {{ $class }}" style="width:100%">
                                    <tbody>
                                        <tr>
                                            {{-- Total --}}
                                            <td>Total:</td>
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
                                        <canvas id="myChart" width="1000" height="1000"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div id="conttablemoni" class="table-responsive">
                                <table id="factuhisto" class="monihora {{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Facturas en cantidad</th>
                                        <tr>
                                            <th class="text-center align-middle">Concepto</th>
                                            <th class="text-center align-middle">Cantidad</th>
                                        </tr>
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

                            <div id="conttablemoni" class="table-responsive">
                                <table id="factuhisto" class="monihora {{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Facturas en $</th>
                                        <tr>
                                            <th class="text-center align-middle">Concepto</th>
                                            <th class="text-center align-middle">Cantidad</th>
                                        </tr>
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
</div>
