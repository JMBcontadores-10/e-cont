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
        <table class="{{ $class }}" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center align-middle">Proyecto</th>
                    <th class="text-center align-middle">Cierre facturación</th>
                    <th class="text-center align-middle">IMSS</th>
                    <th class="text-center align-middle">Impuesto Sobre Nómina</th>
                    <th class="text-center align-middle">Impuesto Estatal Cedular</th>
                    <th class="text-center align-middle">Impuesto Sobre Hospedaje</th>
                    <th class="text-center align-middle">Declaración INEGI</th>
                    <th class="text-center align-middle">Impuestos Federales</th>
                    <th class="text-center align-middle">Envío contabilidad electrónica</th>
                    <th class="text-center align-middle">Acuse DIOT</th>
                    <th class="text-center align-middle">Cierre E-cont</th>
                    <th class="text-center align-middle">Costo ventas</th>
                    <th class="text-center align-middle">Archivo Digital</th>
                    <th class="text-center align-middle">Conciliación impuestos</th>
                </tr>
            </thead>
            <tbody>
                {{-- Empresa/Proyecto --}}
                @foreach ($empresas as $empresa)
                    @php
                        //Consultamos los datos de los proyectos
                        $proyectos = User::where('RFC', $empresa['RFC'])->first();
                        
                        //Conusltamos los Expedientes fiscales
                        $expfiscales = ExpedFiscal::where('rfc', $empresa['RFC'])->first();
                        
                        //Llamamos al metodo del modelo para obtener el mes en cadena
                        $mesespa = $espa->fecha_es($mestareaadmin);
                    @endphp

                    <tr>
                        {{-- Nombre de proyecto --}}
                        <td class="text-center align-middle">{{ $empresa['Nombre'] }}</td>

                        {{-- Cierre de facturacion --}}
                        @if (!empty($proyectos->CierreFactu) || !empty($empresa['Cierre_Facturacion']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Facturacion.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @elseif (!empty($empresa['Cierre_Facturacion']))
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Cierre_Facturacion')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                            type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Cierre_Facturacion')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                            type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- IMSS --}}
                        @if (!empty($proyectos->IMSS) || !empty($empresa['IMSS']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'IMSS')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                            type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Remuneraciones/Nomina --}}
                        @if (!empty($proyectos->ImptoRemuneracion) || !empty($empresa['Impuestos_Remuneraciones']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Remuneraciones')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                            type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto estatal cedular --}}
                        @if (!empty($proyectos->ImpuestoEstatal) || !empty($empresa['Impuestos_Estatal']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Estatal.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @elseif (!empty($empresa['Impuestos_Estatal']))
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Estatal')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                            type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Estatal')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                            type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Hospedaje --}}
                        @if (!empty($proyectos->ImptoHospedaje) || !empty($empresa['Impuestos_Hospedaje']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Hospedaje')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                            type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuesto sobre Hospedaje --}}
                        @if (!empty($proyectos->DeclaINEGI) || !empty($empresa['Declaracion_INEGI']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Declaracion_INEGI.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Declaracion_INEGI')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01"
                                            max={{ date('Y-m-d') }} type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Impuestos federales --}}
                        @if (!empty($proyectos->ImptoFederal) || !empty($empresa['Impuestos_Federales']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @elseif (!empty($empresa['Impuestos_Federales']))
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Federales')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01"
                                            max={{ date('Y-m-d') }} type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form
                                        wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Federales')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01"
                                            max={{ date('Y-m-d') }} type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- Balanza mensual --}}
                        @if (!empty($proyectos->BalanMensual) || !empty($empresa['Balanza_Mensual']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @elseif (!empty($empresa['Balanza_Mensual']))
                                <td class="text-center align-middle">
                                    <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Balanza_Mensual')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01"
                                            max={{ date('Y-m-d') }} type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Balanza_Mensual')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01"
                                            max={{ date('Y-m-d') }} type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif


                        {{-- DIOT --}}
                        @if (!empty($proyectos->DIOT) || !empty($empresa['DIOT']))
                            @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion']))
                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                    <br>

                                    {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.DIOT.' . $mesespa . '.Declaracion'] }}
                                </td>
                            @elseif (!empty($empresa['DIOT']))
                                <td class="text-center align-middle">
                                    <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'DIOT')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01"
                                            max={{ date('Y-m-d') }} type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @else
                                <td class="text-center align-middle">
                                    <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'DIOT')">
                                        <input wire:model.defer="fechaimpu" min="2014-01-01"
                                            max={{ date('Y-m-d') }} type="date" required>
                                        <button class="tn btn-secondary BtnVinculadas" type="submit"
                                            wire:loading.attr="disabled">Capturar</button>
                                    </form>
                                </td>
                            @endif
                        @else
                            <td class="text-center align-middle" style="background-color: #bcbcbc" disabled>
                            </td>
                        @endif

                        {{-- Cierre Econt --}}
                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion']))
                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                <img src="{{ asset('img/ima.png') }}" alt="">

                                <br>

                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Cierre_Econt.' . $mesespa . '.Declaracion'] }}
                            </td>
                        @elseif (!empty($empresa['Cierre_Econt']))
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Cierre_Econt')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @else
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Cierre_Econt')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @endif


                        {{-- Costo Venta --}}
                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion']))
                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                <img src="{{ asset('img/ima.png') }}" alt="">

                                <br>

                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Costo_Ventas.' . $mesespa . '.Declaracion'] }}
                            </td>
                        @elseif (!empty($empresa['Costo_Ventas']))
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Costo_Ventas')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @else
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Costo_Ventas')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @endif


                        {{-- Archivo digital --}}
                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion']))
                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                <img src="{{ asset('img/ima.png') }}" alt="">

                                <br>

                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Archivo_Digital.' . $mesespa . '.Declaracion'] }}
                            </td>
                        @elseif (!empty($empresa['Archivo_Digital']))
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Archivo_Digital')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @else
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Archivo_Digital')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @endif


                        {{-- Conciliacion de impuestos --}}
                        @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion']))
                            <td class="text-center align-middle" style="background-color: #e0ffca">
                                <img src="{{ asset('img/ima.png') }}" alt="">

                                <br>

                                {{ $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '_C.Declaracion'] ?? $expfiscales['ExpedFisc.' . $aniotareaadmin . '.Conciliacion_Impuesto.' . $mesespa . '.Declaracion'] }}
                            </td>
                        @elseif (!empty($empresa['Conciliacion_Impuesto']))
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Conciliacion_Impuesto')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @else
                            <td class="text-center align-middle">
                                <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Conciliacion_Impuesto')">
                                    <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                        type="date" required>
                                    <button class="tn btn-secondary BtnVinculadas" type="submit"
                                        wire:loading.attr="disabled">Capturar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
