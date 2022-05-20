<div>
    @php
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
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
                    <div class="table-responsive">
                        <table class="{{ $class }}" id="tableclient" data-tableexport-display="always"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan="5" data-tableexport-colspan="4" class="text-center align-middle">Facturación por cliente
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
                                    //Variables para obtener el total
                                    $totalfactu = 0;
                                    $totalmonto = 0;
                                @endphp

                                @foreach ($consulmetaclient as $datametaclient)
                                    @php
                                        //Variable de contenedor
                                        $totalfactuclient = 0; //Cantidad de facturas
                                        $montofactuclient = 0;
                                        
                                        //Ciclo para obtener la cantidad de facturas por cliente
                                        foreach ($consulmetaporhora as $datametaporhora) {
                                            if ($datametaclient['receptorRfc'] == $datametaporhora['receptorRfc']) {
                                                $totalfactuclient++;
                                                $montofactuclient += $datametaporhora['total'];
                                            }
                                        }
                                        
                                        //Obtenemos el total
                                        $totalfactu += $totalfactuclient; //Cantidad
                                        $totalmonto += $montofactuclient; //Monto
                                    @endphp

                                    <tr>
                                        {{-- RFC --}}
                                        <td>
                                            {{ $datametaclient['receptorRfc'] }}
                                        </td>

                                        {{-- Razon social --}}
                                        <td>
                                            {{ $datametaclient['receptorNombre'] }}
                                        </td>

                                        {{-- # de facturas emitidas --}}
                                        <td>
                                            {{ $totalfactuclient }}
                                        </td>

                                        {{-- Monto --}}
                                        <td>
                                            $ {{ number_format($montofactuclient, 2) }}
                                        </td>

                                        {{-- Detalles --}}
                                        <td>
                                            <a data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                                data-target="#detalleporclient{{ $datametaclient['receptorRfc'] }}"
                                                class="icons fas fa-eye"></a>
                                        </td>
                                    </tr>
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
</div>
