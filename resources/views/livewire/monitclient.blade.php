<div>
    @php
        //Llamamos al modelo
        use App\Models\User;
        
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
                                            data-target="#detalleporclient' .
    $datametaclient['RFC'] .
    '"class="icons fas fa-eye"></a> </td>';
                                        
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
</div>
