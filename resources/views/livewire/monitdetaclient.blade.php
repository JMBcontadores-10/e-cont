<div>
    @php
        use App\Models\MetadataR;
        
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
    @endphp

    {{-- DETALLES POR CLIENTE --}}
    {{-- Creacion del modal (BASE) --}}
    @foreach ($consulmetaclient as $datametaclient)
        @php
            //Definimos la variable de la suma total
            $totalfactu = 0;
        @endphp

        {{-- DETALLES POR CLIENTE --}}
        {{-- Creacion del modal (BASE) --}}
        <div wire:ignore.self class="modal fade" id="detalleporclient{{ $datametaclient['receptorRfc'] }}"
            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
                <div class="modal-content">
                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                                class="icons fas fa-comments">Detalles por
                                clientes </span></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    {{-- Cuerpo del modal --}}
                    <div class="modal-body">
                        {{-- Boton de exportacion --}}
                        <div class="form-inline mr-auto">
                            <button type="button" class="btn btn-success BtnVinculadas"
                                onclick="ExportHoraClientExcel('{{ $empresa }}{{ $datametaclient['receptorRfc'] }}', '{{ $empresa }}')">Excel</button>
                            &nbsp;&nbsp;

                            <button type="button" class="btn btn-danger BtnVinculadas"
                                onclick="ExportHoraClientPDF('{{ $empresa }}{{ $datametaclient['receptorRfc'] }}', '{{ $empresa }}')">Pdf</button>
                            &nbsp;&nbsp;
                        </div>

                        <br>

                        <div class="table-responsive">
                            <table class="{{ $class }}"
                                id="{{ $empresa }}{{ $datametaclient['receptorRfc'] }}" style="width:100%">
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
                                    @foreach ($consulmetaporhora as $datacliente)
                                        @if ($datacliente['receptorRfc'] == $datametaclient['receptorRfc'])
                                            @php
                                                //Contador de conceptos
                                                $ConceptCount = 0;
                                                
                                                $espa = new MetadataR();
                                                $fechaE = $datacliente['fechaEmision'];
                                                $folioF = $datacliente['folioFiscal'];
                                                $numero = (string) (int) substr($fechaE, 5, 2);
                                                $mesNombre = (string) (int) substr($fechaE, 5, 2);
                                                $anio = (string) (int) substr($fechaE, 0, 4);
                                                $mees = $espa->fecha_es($mesNombre);
                                                
                                                $rutaXml = "storage/contarappv1_descargas/$empresa/$anio/Descargas/$numero.$mees/Emitidos/XML/$folioF.xml";
                                                $rutaPdf = "storage/contarappv1_descargas/$empresa/$anio/Descargas/$numero.$mees/Emitidos/PDF/$folioF.pdf";
                                                
                                                foreach ($consulxmlporhora as $dataclientexml) {
                                                    if ($dataclientexml['UUID'] == $datacliente['folioFiscal']) {
                                                        $serie = $dataclientexml['Serie'];
                                                        $folio = $dataclientexml['Folio'];
                                                        $expedicion = $dataclientexml['LugarExpedicion'];
                                                        $forma = $dataclientexml['FormaPago'];
                                                        $concepto = $dataclientexml['Conceptos.Concepto'];
                                                
                                                        //Realizaremos una sumatoria de todos los totales
                                                        $totalfactu += floatval($datacliente['total']);
                                                    }
                                                }
                                            @endphp

                                            <tr>
                                                {{-- Estado SAT --}}
                                                <td>
                                                    {{ $datacliente['estado'] }}
                                                </td>

                                                {{-- Tipo --}}
                                                <td>
                                                    {{ $datacliente['efecto'] }}
                                                </td>

                                                {{-- Fecha emision --}}
                                                <td>
                                                    {{ $datacliente['fechaEmision'] }}
                                                </td>

                                                {{-- Fecha timbrado --}}
                                                <td>
                                                    {{ $datacliente['fechaCertificacion'] }}
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
                                                    {{ $datacliente['folioFiscal'] }}
                                                </td>

                                                {{-- Lugar de expedicion --}}
                                                <td>
                                                    {{ $expedicion }}
                                                </td>

                                                {{-- RFC receptor --}}
                                                <td>
                                                    {{ $datacliente['receptorRfc'] }}
                                                </td>

                                                {{-- Nombre receptor --}}
                                                <td>
                                                    {{ $datacliente['receptorNombre'] }}
                                                </td>

                                                {{-- Total --}}
                                                <td>
                                                    {{ number_format(floatval($datacliente['total']), 2) }}
                                                </td>

                                                {{-- Forma de pago --}}
                                                <td>
                                                    {{ $forma }}
                                                </td>

                                                {{-- Concepto --}}
                                                <td>
                                                    @if (isset($concepto[0]['Descripcion']))
                                                        @foreach ($concepto as $detaconcepto)
                                                            {{ ++$ConceptCount . '.- ' . Str::limit($detaconcepto['Descripcion'], 20) }}
                                                        @endforeach
                                                    @endif
                                                </td>

                                                {{-- Detalles --}}
                                                <td>
                                                    @if ($datacliente['estado'] != 'Cancelado')
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
                                        <td>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
