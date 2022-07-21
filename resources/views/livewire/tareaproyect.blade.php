<div>
    @php
        //Importamos los modelos
        use App\Models\User;
        use App\Models\ExpedFiscal;
        use App\Models\Cheques;
        
        //Convertimos los meses de numero a palabra
        $espa = new Cheques();
        
        //Obtenemos la clase para agregar a la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
    @endphp

    {{-- Tabla de tareas --}}
    <div class="table-responsive">
        <table class="{{ $class }} tablecontabilidad" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center align-middle FontProyectos sticky-colhead first-col">Proyecto</th>
                    <th class="text-center align-middle FontProyectos">Cierre <br> facturación</th>
                    <th class="text-center align-middle FontProyectos">IMSS</th>
                    <th class="text-center align-middle FontProyectos">ISN</th>
                    <th class="text-center align-middle FontProyectos">Impuesto <br> Cedular</th>
                    <th class="text-center align-middle FontProyectos">ISH</th>
                    <th class="text-center align-middle FontProyectos">Declaración <br> INEGI</th>
                    <th class="text-center align-middle FontProyectos">Impuestos <br> Federales</th>
                    <th class="text-center align-middle FontProyectos">Balanza <br> Mensual</th>
                    <th class="text-center align-middle FontProyectos">DIOT</th>
                    <th class="text-center align-middle FontProyectos">Cierre <br> E-cont</th>
                    <th class="text-center align-middle FontProyectos">Notas de <br> Credito</th>
                    <th class="text-center align-middle FontProyectos">Costo <br> ventas</th>
                    <th class="text-center align-middle FontProyectos">Archivo <br> Digital</th>
                    <th class="text-center align-middle FontProyectos">Concentrado de <br> impuestos</th>
                </tr>
            </thead>
            <tbody>
                {{-- EMPRESAS REGISTRADOS Y ASIGNADOS --}}

                {{-- Descomponemos la consulta de los colaboradores --}}
                @foreach ($consulconta as $infoconta)
                    <tr>
                        {{-- Nombre de proyecto --}}
                        <td style="background-color: #f8f8f8;" class="FontProyectos sticky-col first-col">
                            <label>{{ $infoconta['nombre'] }}</label>
                        </td>

                        @for ($i = 1; $i <= 14; $i++)
                            <td style="background-color: #f8f8f8;">
                            </td>
                        @endfor
                    </tr>
                    {{-- Obtenemos las emepresa sde cada colaborador --}}
                    @foreach ($infoconta['empresas'] as $contaempresa)
                        {{-- Empresa/Proyecto --}}
                        @foreach ($empresas as $empresa)
                            @if ($empresa['RFC'] == $contaempresa || strpos($empresa['RFC'], $contaempresa) !== false)
                                {{-- strpos para obtener coincidencias de una cadena --}}
                                @php
                                    //Consultamos los datos de los proyectos
                                    $proyectos = User::where('RFC', $empresa['RFC'])->first();
                                    
                                    //Conusltamos los Expedientes fiscales
                                    $expfiscales = ExpedFiscal::where('rfc', $empresa['RFC'])->first();
                                    
                                    //Llamamos al metodo del modelo para obtener el mes en cadena
                                    $mesespa = $espa->fecha_es($mestareaadmin);
                                @endphp

                                <tr style="color: #3e464e">
                                    {{-- Nombre de proyecto --}}
                                    <td class="text-center align-middle FontProyectos sticky-col first-col">
                                        {{ $empresa['Nombre'] }}</td>

                                    {{-- Cierre de facturacion --}}
                                    @if (!empty($proyectos->CierreFactu) || !empty($empresa['Cierre_Facturacion']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- IMSS --}}
                                    @if (!empty($proyectos->IMSS) || !empty($empresa['IMSS']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- Impuesto sobre Remuneraciones/Nomina --}}
                                    @if (!empty($proyectos->ImptoRemuneracion) || !empty($empresa['Impuestos_Remuneraciones']))
                                        @if (!empty(
                                            $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion']
                                        ) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- Impuesto estatal cedular --}}
                                    @if (!empty($proyectos->ImpuestoEstatal) || !empty($empresa['Impuestos_Estatal']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- Impuesto sobre Hospedaje --}}
                                    @if (!empty($proyectos->ImptoHospedaje) || !empty($empresa['Impuestos_Hospedaje']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- Declaracion INEGI --}}
                                    @if (!empty($proyectos->DeclaINEGI) || !empty($empresa['Declaracion_INEGI']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- Impuestos federales --}}
                                    @if (!empty($proyectos->ImptoFederal) || !empty($empresa['Impuestos_Federales']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- Balanza mensual --}}
                                    @if (!empty($proyectos->BalanMensual) || !empty($empresa['Balanza_Mensual']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif


                                    {{-- DIOT --}}
                                    @if (!empty($proyectos->DIOT) || !empty($empresa['DIOT']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif

                                    {{-- Cierre Econt --}}
                                    @if (!empty($proyectos->CierreEcont) || !empty($empresa['Cierre_Econt']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif

                                    {{-- Notas de credito --}}
                                    @if (!empty($proyectos->EmitCredit) || !empty($empresa['Notas_Credito']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                                        </td>
                                    @endif

                                    {{-- Costo Venta --}}
                                    @if (!empty($proyectos->CostoVentas) || !empty($empresa['Costo_Ventas']))
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc"
                                            disabled>
                                        </td>
                                    @endif


                                    {{-- Costo Venta --}}
                                    @if (!empty($proyectos->ArchivoDigit) || !empty($empresa['Archivo_Digital']))
                                        {{-- Archivo digital --}}
                                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion']) ||
                                            !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc"
                                            disabled>
                                        </td>
                                    @endif

                                    {{-- Conciliacion de impuestos --}}
                                    @if (!empty($proyectos->ConcImpu) || !empty($empresa['Conciliacion_Impuesto']))
                                        @if (!empty(
                                            $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion']
                                        ) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion']))
                                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                                <img src="{{ asset('img/ima.png') }}" alt="">

                                                <br>

                                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion'] }}
                                            </td>
                                        @else
                                            <td class="text-center align-middle">
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center align-middle" style="background-color: #bcbcbc"
                                            disabled>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach

                {{-- Empresas no registradas en ECONT --}}
                <tr>
                    {{-- Nombre de proyecto --}}
                    <td style="background-color: #f8f8f8;" class="FontProyectos sticky-col first-col">
                        <label>No alta Econt</label>
                    </td>

                    @for ($i = 1; $i <= 13; $i++)
                        <td style="background-color: #f8f8f8;">
                        </td>
                    @endfor
                </tr>

                {{-- Empresa/Proyecto --}}
                @foreach ($empnoalta as $emprenoalta)
                    @php
                        //Consultamos los datos de los proyectos
                        $proyectos = User::where('RFC', $emprenoalta['RFC'])->first();
                        
                        //Conusltamos los Expedientes fiscales
                        $expfiscales = ExpedFiscal::where('rfc', $emprenoalta['RFC'])->first();
                        
                        //Llamamos al metodo del modelo para obtener el mes en cadena
                        $mesespa = $espa->fecha_es($mestareaadmin);
                    @endphp

                    <tr style="color: #3e464e">
                        {{-- Nombre de proyecto --}}
                        <td class="text-center align-middle FontProyectos sticky-col first-col">
                            {{ $emprenoalta['Nombre'] }}</td>

                        {{-- Cierre de facturacion --}}
                        @if (!empty($proyectos->CierreFactu) || !empty($emprenoalta['Cierre_Facturacion']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- IMSS --}}
                        @if (!empty($proyectos->IMSS) || !empty($emprenoalta['IMSS']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Remuneraciones/Nomina --}}
                        @if (!empty($proyectos->ImptoRemuneracion) || !empty($emprenoalta['Impuestos_Remuneraciones']))
                            @if (!empty(
                                $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion']
                            ) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto estatal cedular --}}
                        @if (!empty($proyectos->ImpuestoEstatal) || !empty($emprenoalta['Impuestos_Estatal']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Hospedaje --}}
                        @if (!empty($proyectos->ImptoHospedaje) || !empty($emprenoalta['Impuestos_Hospedaje']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Hospedaje --}}
                        @if (!empty($proyectos->DeclaINEGI) || !empty($emprenoalta['Declaracion_INEGI']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuestos federales --}}
                        @if (!empty($proyectos->ImptoFederal) || !empty($emprenoalta['Impuestos_Federales']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Balanza mensual --}}
                        @if (!empty($proyectos->BalanMensual) || !empty($emprenoalta['Balanza_Mensual']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- DIOT --}}
                        @if (!empty($proyectos->DIOT) || !empty($emprenoalta['DIOT']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Cierre Econt --}}
                        @if (!empty($proyectos->CierreEcont) || !empty($emprenoalta['Cierre_Econt']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif

                        {{-- Notas de credito --}}
                        @if (!empty($proyectos->EmitCredit) || !empty($emprenoalta['Notas_Credito']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif

                        {{-- Costo Venta --}}
                        @if (!empty($proyectos->CostoVentas) || !empty($emprenoalta['Costo_Ventas']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif

                        {{-- Archivo digital --}}
                        @if (!empty($proyectos->ArchivoDigit) || !empty($emprenoalta['Archivo_Digital']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif



                        {{-- Conciliacion de impuestos --}}
                        @if (!empty($proyectos->ConcImpu) || !empty($emprenoalta['Conciliacion_Impuesto']))
                            @if (!empty(
                                $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion']
                            ) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif
                    </tr>
                @endforeach





                {{-- Empresas en cero --}}
                <tr>
                    {{-- Nombre de proyecto --}}
                    <td style="background-color: #f8f8f8;" class="FontProyectos sticky-col first-col">
                        <label>Ceros</label>
                    </td>

                    @for ($i = 1; $i <= 14; $i++)
                        <td style="background-color: #f8f8f8;">
                        </td>
                    @endfor
                </tr>

                {{-- Empresa/Proyecto --}}
                @foreach ($empreceros as $empresaceros)
                    @php
                        //Consultamos los datos de los proyectos
                        $proyectos = User::where('RFC', $empresaceros['RFC'])->first();
                        
                        //Conusltamos los Expedientes fiscales
                        $expfiscales = ExpedFiscal::where('rfc', $empresaceros['RFC'])->first();
                        
                        //Llamamos al metodo del modelo para obtener el mes en cadena
                        $mesespa = $espa->fecha_es($mestareaadmin);
                    @endphp

                    <tr style="color: #3e464e">
                        {{-- Nombre de proyecto --}}
                        <td class="text-center align-middle FontProyectos sticky-col first-col">
                            {{ $empresaceros['Nombre'] }}</td>

                        {{-- Cierre de facturacion --}}
                        @if (!empty($proyectos->CierreFactu) || !empty($empresaceros['Cierre_Facturacion']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- IMSS --}}
                        @if (!empty($proyectos->IMSS) || !empty($empresaceros['IMSS']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Remuneraciones/Nomina --}}
                        @if (!empty($proyectos->ImptoRemuneracion) || !empty($empresaceros['Impuestos_Remuneraciones']))
                            @if (!empty(
                                $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion']
                            ) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto estatal cedular --}}
                        @if (!empty($proyectos->ImpuestoEstatal) || !empty($empresaceros['Impuestos_Estatal']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Hospedaje --}}
                        @if (!empty($proyectos->ImptoHospedaje) || !empty($empresaceros['Impuestos_Hospedaje']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Hospedaje --}}
                        @if (!empty($proyectos->DeclaINEGI) || !empty($empresaceros['Declaracion_INEGI']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuestos federales --}}
                        @if (!empty($proyectos->ImptoFederal) || !empty($empresaceros['Impuestos_Federales']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Balanza mensual --}}
                        @if (!empty($proyectos->BalanMensual) || !empty($empresaceros['Balanza_Mensual']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- DIOT --}}
                        @if (!empty($proyectos->DIOT) || !empty($empresaceros['DIOT']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif

                        {{-- Cierre Econt --}}
                        @if (!empty($proyectos->CierreEcont) || !empty($empresaceros['Cierre_Econt']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif



                        {{-- Notas de credito --}}
                        @if (!empty($proyectos->EmitCredit) || !empty($empresaceros['Notas_Credito']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Notas_Credito.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif

                        {{-- Costo Venta --}}
                        @if (!empty($proyectos->CostoVentas) || !empty($empresaceros['Costo_Ventas']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif




                        {{-- Archivo digital --}}
                        @if (!empty($proyectos->ArchivoDigit) || !empty($empresaceros['Archivo_Digital']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion']) ||
                                !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif



                        {{-- Archivo digital --}}
                        @if (!empty($proyectos->ConcImpu) || !empty($empresaceros['Conciliacion_Impuesto']))
                            {{-- Conciliacion de impuestos --}}
                            @if (!empty(
                                $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion']
                            ) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
