<div>

    @php
        //Importamos los modelos necesarios
        use App\Models\MetadataR;
        use App\Models\XmlE;
        
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
    @endphp

    {{-- Modal que muestre las facturas --}}
    @for ($i = 0; $i <= 23; $i++)
        @php
            //Variable total facturas
            $totalfactu = 0;
        @endphp
        {{-- Modal de hisatorico --}}
        {{-- Creacion del modal (BASE) --}}
        <div wire:ignore.self class="modal fade" id="factuporhora{{ $i }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
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
                        {{-- Boton de exportacion --}}
                        <div class="form-inline mr-auto">
                            <button type="button" class="btn btn-success BtnVinculadas"
                                onclick="ExportHoraClientExcel('{{ $empresa }}{{ $i }}', '{{ $empresa }}')">Excel</button>
                            &nbsp;&nbsp;

                            <button type="button" class="btn btn-danger BtnVinculadas"
                                onclick="ExportHoraClientPDF('{{ $empresa }}{{ $i }}', '{{ $empresa }}')">Pdf</button>
                            &nbsp;&nbsp;
                        </div>

                        <br>

                        <div class="table-responsive">
                            <table id="{{ $empresa }}{{ $i }}" class="{{ $class }}"
                                style="width:100%">
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
                                    @foreach ($infofactumetaemit as $datametahora)
                                        @php
                                            //Contador de conceptos
                                            $ConceptCount = 0;
                                            
                                            $espa = new MetadataR();
                                            $fechaE = $datametahora['fechaEmision'];
                                            $folioF = $datametahora['folioFiscal'];
                                            $numero = (string) (int) substr($fechaE, 5, 2);
                                            $mesNombre = (string) (int) substr($fechaE, 5, 2);
                                            $anio = (string) (int) substr($fechaE, 0, 4);
                                            $mees = $espa->fecha_es($mesNombre);
                                            
                                            $rutaXml = "storage/contarappv1_descargas/$empresa/$anio/Descargas/$numero.$mees/Emitidos/XML/$folioF.xml";
                                            $rutaPdf = "storage/contarappv1_descargas/$empresa/$anio/Descargas/$numero.$mees/Emitidos/PDF/$folioF.pdf";
                                            
                                            //Obtenemos la fecha
                                            $horameta = date('G', strtotime($datametahora['fechaEmision']));
                                            
                                            foreach ($infofactuxmlemit as $dataxmlhora) {
                                                //Obtenemos la fecha
                                                $horaxml = date('G', strtotime($dataxmlhora['Fecha']));
                                            
                                                //Condicional para saber si pertenece a la fecha
                                                if ($dataxmlhora['UUID'] == $datametahora['folioFiscal']) {
                                                    $serie = $dataxmlhora['Serie'];
                                                    $folio = $dataxmlhora['Folio'];
                                                    $expedicion = $dataxmlhora['LugarExpedicion'];
                                                    $forma = $dataxmlhora['FormaPago'];
                                                    $concepto = $dataxmlhora['Conceptos.Concepto'];
                                                }
                                            }
                                        @endphp

                                        @if ($horameta == $i)
                                            @php
                                                //Realizaremos una sumatoria de todos los totales
                                                $totalfactu += floatval($datametahora['total']);
                                            @endphp
                                            <tr>
                                                {{-- Estado SAT --}}
                                                <td>
                                                    {{ $datametahora['estado'] }}
                                                </td>

                                                {{-- Tipo --}}
                                                <td>
                                                    {{ $datametahora['efecto'] }}
                                                </td>

                                                {{-- Fecha emision --}}
                                                <td>
                                                    {{ $datametahora['fechaEmision'] }}
                                                </td>

                                                {{-- Fecha timbrado --}}
                                                <td>
                                                    {{ $datametahora['fechaCertificacion'] }}
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
                                                    {{ $datametahora['folioFiscal'] }}
                                                </td>

                                                {{-- Lugar de expedicion --}}
                                                <td>
                                                    {{ $expedicion }}
                                                </td>

                                                {{-- RFC receptor --}}
                                                <td>
                                                    {{ $datametahora['receptorRfc'] }}
                                                </td>

                                                {{-- Nombre receptor --}}
                                                <td>
                                                    {{ $datametahora['receptorNombre'] }}
                                                </td>

                                                {{-- Total --}}
                                                <td>
                                                    {{ number_format(floatval($datametahora['total']), 2) }}
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
                                                    @if ($datametahora['estado'] != 'Cancelado')
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
    @endfor
</div>
