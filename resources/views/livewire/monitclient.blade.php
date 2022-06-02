<div>
    @php
        //Llamamos al modelo
        use App\Models\User;
        use App\Models\MetadataR;
        
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
        
        //Obtnemos el nombre de la empresa
        $consulempre = User::where('RFC', $empresa)
            ->get()
            ->first();
        
        //Descomponemos el Json en un objeto
        $consulmetaporhora = json_decode($emitidos);
    @endphp
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
                    <div class="form-inline mr-auto">
                        <button type="button" class="btn btn-success BtnVinculadas"
                            onclick="exportReportToExcel('{{ $empresa }}')">Excel</button>
                        &nbsp;&nbsp;

                        <button type="button" class="btn btn-danger BtnVinculadas"
                            onclick="exportReportToPdf('{{ $empresa }}')">Pdf</button>
                    </div>

                    <br>

                    <div wire:loading>
                        <br>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                        <br>
                    </div>

                    <div class="table-responsive">
                        <table class="{{ $class }}" id="tableclient" data-tableexport-display="always"
                            style="width:100%">
                            <thead>
                                <tr hidden>
                                    <th colspan="5" data-tableexport-colspan="4" class="text-center align-middle">
                                        {{ $empresa }} - {{ strtoupper($consulempre['nombre']) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="5" data-tableexport-colspan="4" class="text-center align-middle">
                                        Facturación por cliente
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">RFC</th>
                                    <th class="text-center align-middle">Razón social</th>
                                    <th class="text-center align-middle"># Fact. Emitidas</th>
                                    <th class="text-center align-middle">Monto</th>
                                    <th class="text-center align-middle">Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    //Construimos la tabla
                                    //Variables de contenedor
                                    $datainfomonit = '';
                                    $rowinfomonit = [];
                                    
                                    //Variables para obtener el total
                                    $totalfactu = 0;
                                    $totalmonto = 0;
                                @endphp

                                {{-- Contruccion de la tabla --}}
                                @foreach ($consulmetaclient as $datametaclient)
                                    @php
                                        //Variable de contenedor
                                        $totalfactuclient = 0; //Cantidad de facturas
                                        $montofactuclient = 0;
                                        
                                        //Alamacenamos el RFC para enviarlo
                                        $rfcreci = "'" . $datametaclient['RFC'] . "'";
                                        
                                        //Ciclo para obtener la cantidad de facturas por cliente
                                        foreach ($consulmetaporhora as $datametaporhora) {
                                            if ($datametaclient['RFC'] == $datametaporhora->ReceptorRfc) {
                                                $totalfactuclient++;
                                                $montofactuclient += $datametaporhora->Total;
                                            }
                                        }
                                        
                                        //Obtenemos el total
                                        $totalfactu += $totalfactuclient; //Cantidad
                                        $totalmonto += $montofactuclient; //Monto
                                        
                                        //Ingresamos los datos requeridos
                                        
                                        //RFC receptor
                                        $datainfomonit .= '<td>' . $datametaclient['RFC'] . '</td>';
                                        
                                        //Nombre receptor
                                        $datainfomonit .= '<td>' . $datametaclient['Nombre'] . '</td>';
                                        
                                        //#Fact emitidas
                                        $datainfomonit .= '<td>' . $totalfactuclient . '</td>';
                                        
                                        //Monto
                                        $datainfomonit .= '<td> $ ' . number_format($montofactuclient, 2) . '</td>';
                                        
                                        //Detalle
                                        $datainfomonit .=
                                            '<td> <a data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                            data-target="#detalleporclient" wire:click="SendRFCReci(' .
    $rfcreci .
    ')" class="icons fas fa-eye"></a> </td>';
                                        
                                        //Alamcenamos los datos en el arreglo
                                        $rowinfomonit[$totalfactuclient . $datametaclient['RFC']] = '<tr>' . $datainfomonit . '</tr>';
                                        
                                        //Vaciamos la variable para almacenar las otras
                                        $datainfomonit = '';
                                    @endphp
                                @endforeach

                                @php
                                    //Ordenamos la tabla
                                    krsort($rowinfomonit, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
                                @endphp

                                {{-- Imprimimos la tabla --}}
                                @foreach ($rowinfomonit as $tableinfomonit)
                                    {!! $tableinfomonit !!}
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td><b>Total:</b></td>
                                    {{-- Total # de facturas emitidas --}}
                                    <td>
                                        {{ $totalfactu }}
                                    </td>

                                    {{-- Total del monto --}}
                                    <td>
                                        $ {{ number_format($totalmonto, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- DETALLES POR CLIENTE --}}
    @php
        //Definimos la variable de la suma total
        $totalfactu = 0;
    @endphp

    {{-- DETALLES POR CLIENTE --}}
    {{-- Creacion del modal (BASE) --}}
    <div wire:ignore.self class="modal fade" id="detalleporclient" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-comments">Detalles por
                            cliente </span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn" onclick="CleanTableClient()">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Boton de exportacion --}}
                    <div class="form-inline mr-auto">
                        <button type="button" class="btn btn-success BtnVinculadas"
                            onclick="ExportHoraClientExcel('{{ $empresa }}{{ $rfcinfo }}', 'Detalles por cliente {{ $empresa }}')">Excel</button>
                        &nbsp;&nbsp;

                        <button type="button" class="btn btn-danger BtnVinculadas"
                            onclick="ExportHoraClientPDF('{{ $empresa }}{{ $rfcinfo }}', 'Detalles por cliente {{ $empresa }}')">Pdf</button>
                        &nbsp;&nbsp;
                    </div>

                    <br>

                    <div wire:loading>
                        <br>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                        <br>
                    </div>

                    <div class="table-responsive">
                        <table class="{{ $class }}" id="{{ $empresa }}{{ $rfcinfo }}"
                            style="width:100%">
                            <thead>
                                <tr hidden>
                                    <th colspan="14" data-tableexport-colspan="13" class="text-center align-middle">
                                        {{ $empresa }} - {{ strtoupper($consulempre['nombre']) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="14" data-tableexport-colspan="13" class="text-center align-middle">
                                        Detalles de facturación
                                    </th>
                                </tr>
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
                                @if ($consulmetaporhora)
                                    @foreach ($consulmetaporhora as $datacliente)
                                        @if ($datacliente->ReceptorRfc == $rfcinfo)
                                            @php
                                                //Contador de conceptos
                                                $ConceptCount = 0;
                                                
                                                //Sumamos los totales
                                                $totalfactu += $datacliente->Total;
                                                
                                                $espa = new MetadataR();
                                                $fechaE = $datacliente->FechaEmision;
                                                $folioF = $datacliente->UUID;
                                                $numero = (string) (int) substr($fechaE, 5, 2);
                                                $mesNombre = (string) (int) substr($fechaE, 5, 2);
                                                $anio = (string) (int) substr($fechaE, 0, 4);
                                                $mees = $espa->fecha_es($mesNombre);
                                                
                                                $rutaXml = "storage/contarappv1_descargas/$empresa/$anio/Descargas/$numero.$mees/Emitidos/XML/$folioF.xml";
                                                $rutaPdf = "storage/contarappv1_descargas/$empresa/$anio/Descargas/$numero.$mees/Emitidos/PDF/$folioF.pdf";
                                            @endphp

                                            <tr class="detaclientbody">
                                                {{-- Estado SAT --}}
                                                <td>
                                                    {{ $datacliente->Estado }}
                                                </td>

                                                {{-- Tipo --}}
                                                <td>
                                                    {{ $datacliente->Efecto }}
                                                </td>

                                                {{-- Fecha emision --}}
                                                <td>
                                                    {{ $datacliente->FechaEmision }}
                                                </td>

                                                {{-- Fecha timbrado --}}
                                                <td>
                                                    {{ $datacliente->FechaCertificacion }}
                                                </td>

                                                {{-- Serie --}}
                                                <td>
                                                    {{ $datacliente->Serie }}
                                                </td>

                                                {{-- Folio --}}
                                                <td>
                                                    {{ $datacliente->Folio }}
                                                </td>

                                                {{-- UUID --}}
                                                <td>
                                                    {{ $datacliente->UUID }}
                                                </td>

                                                {{-- Lugar de expedicion --}}
                                                <td>
                                                    {{ $datacliente->LugarExpedicion }}
                                                </td>

                                                {{-- RFC receptor --}}
                                                <td>
                                                    {{ $datacliente->ReceptorRfc }}
                                                </td>

                                                {{-- Nombre receptor --}}
                                                <td>
                                                    {{ $datacliente->ReceptorNombre }}
                                                </td>

                                                {{-- Total --}}
                                                <td>
                                                    {{ number_format(floatval($datacliente->Total), 2) }}
                                                </td>

                                                {{-- Forma de pago --}}
                                                <td>
                                                    {{ $datacliente->FormaPago }}
                                                </td>

                                                {{-- Concepto --}}
                                                <td>
                                                    @if ($datacliente->Concepto)
                                                        @foreach ($datacliente->Concepto as $detaconcepto)
                                                            {{ ++$ConceptCount . '.- ' . Str::limit($detaconcepto->Descripcion, 20) }}
                                                            <br>
                                                        @endforeach
                                                    @endif
                                                </td>

                                                {{-- Detalles --}}
                                                <td>
                                                    @if ($datacliente->Estado != 'Cancelado')
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
                                    {{-- Mostramos el total --}}
                                    <tr>
                                        {{-- Estado SAT --}}
                                        <td>
                                        </td>

                                        {{-- Tipo --}}
                                        <td>
                                        </td>

                                        {{-- Fecha emision --}}
                                        <td>
                                        </td>

                                        {{-- Fecha timbrado --}}
                                        <td>
                                        </td>

                                        {{-- Serie --}}
                                        <td>
                                        </td>

                                        {{-- Folio --}}
                                        <td>
                                        </td>

                                        {{-- UUID --}}
                                        <td>
                                        </td>

                                        {{-- Lugar de expedicion --}}
                                        <td>
                                        </td>

                                        {{-- RFC receptor --}}
                                        <td>
                                        </td>

                                        {{-- Nombre receptor --}}
                                        <td>
                                            <b>Total:</b>
                                        </td>

                                        {{-- Total --}}
                                        <td id="totaldetaclient">
                                            {{ number_format($totalfactu, 2) }}
                                        </td>

                                        {{-- Forma de pago --}}
                                        <td>
                                        </td>

                                        {{-- Concepto --}}
                                        <td>
                                        </td>

                                        {{-- Detalles --}}
                                        <td>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
