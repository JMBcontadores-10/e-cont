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

    {{-- JS --}}
    <script src="{{ asset('js/tareas.js') }}" defer></script>

    {{-- Filtros de busqueda --}}
    <div class="form-inline mr-auto">
        {{-- Boton para agregar una nueva tarea --}}
        <button class="btn btn-primary" data-toggle="modal" data-target="#nuevatarea" data-backdrop="static"
            data-keyboard="false">
            <i class="fas fa-plus" style="top: 0 !important"></i> Nueva tarea
        </button>

        {{-- Espaciado --}}
        <div style="width: 5em"></div>

        {{-- Busqueda por mes --}}
        <label class="mestarea" for="inputState">Mes</label>
        &nbsp;&nbsp;
        <select id="MesSelecTarea" wire:model="mestareaadmin" id="inputState1" wire:loading.attr="disabled"
            class="mestarea select form-control">
            <?php foreach ($meses as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            } ?>
        </select>
        &nbsp;&nbsp;
        &nbsp;&nbsp;

        {{-- Busqueda por año --}}
        <label for="inputState">Año</label>
        &nbsp;&nbsp;
        <select id="AnioSelectTarea" wire:loading.attr="disabled" wire:model="aniotareaadmin" id="inputState2"
            class="select form-control">
            <?php foreach (array_reverse($anios) as $value) {
                echo '<option value="' . $value . '">' . $value . '</option>';
            } ?>
        </select>
        &nbsp;&nbsp;
        &nbsp;&nbsp;

        {{-- Busqueda por avance --}}
        <label for="inputState">Avance</label>
        &nbsp;&nbsp;
        <select wire:loading.attr="disabled" wire:model="avancetareaadmin" id="inputState2"
            class="select form-control AvanceSelectTarea">
            <option>Departamento</option>
            <option>Colaboradores</option>
            <option>Proyecto</option>
            <option>Tareas</option>
        </select>
        &nbsp;&nbsp;
        &nbsp;&nbsp;

        {{-- Busqueda por avance --}}
        <label {{ $active }} for="inputState">Departamento</label>
        &nbsp;&nbsp;
        <select {{ $active }} wire:loading.attr="disabled" id="selectdepto" wire:model="departament"
            class="select form-control AvanceSelectTarea">
            <option>Contabilidad</option>
            <option>Nóminas</option>
            <option>Facturación</option>
        </select>
        &nbsp;&nbsp;
    </div>

    <br><br>

    {{-- Animacion de cargando --}}
    <div wire:loading>
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
            <div></div>
            <div></div>
        </div>
        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
    </div>

    {{-- switch para seleccionar el tipo de avance --}}
    @switch($avancetareaadmin)
        @case('Departamento')
            {{-- Importamos los componentes --}}
            <livewire:tareadepto :mestareaadmin=$mestareaadmin :aniotareaadmin=$aniotareaadmin :departament=$departament
                :wire:key="'user-profile-one-'.$mestareaadmin.$aniotareaadmin.$departament" />
        @break

        @case('Colaboradores')
            {{-- Importamos los componentes --}}
            <livewire:tareaadmincolab :mestareaadmin=$mestareaadmin :aniotareaadmin=$aniotareaadmin
                :wire:key="'user-profile-one-'.$mestareaadmin.$aniotareaadmin" />
        @break

        @case('Proyecto')
            {{-- Tabla de tareas --}}
            <div class="table-responsive">
                <table class="{{ $class }}" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center align-middle FontProyectos sticky-colhead first-col">Proyecto</th>
                            <th class="text-center align-middle FontProyectos">Cierre <br> facturación</th>
                            <th class="text-center align-middle FontProyectos">IMSS</th>
                            <th class="text-center align-middle FontProyectos">Impuesto <br> Sobre Nómina</th>
                            <th class="text-center align-middle FontProyectos">Impuesto <br> Estatal Cedular</th>
                            <th class="text-center align-middle FontProyectos">Impuesto <br> Sobre Hospedaje</th>
                            <th class="text-center align-middle FontProyectos">Declaración <br> INEGI</th>
                            <th class="text-center align-middle FontProyectos">Impuestos <br> Federales</th>
                            <th class="text-center align-middle FontProyectos">Envío <br> contabilidad electrónica</th>
                            <th class="text-center align-middle FontProyectos">Acuse <br> DIOT</th>
                            <th class="text-center align-middle FontProyectos">Cierre <br> E-cont</th>
                            <th class="text-center align-middle FontProyectos">Costo <br> ventas</th>
                            <th class="text-center align-middle FontProyectos">Archivo <br> Digital</th>
                            <th class="text-center align-middle FontProyectos">Conciliación <br> impuestos</th>
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
                                <td class="text-center align-middle FontProyectos sticky-col first-col">
                                    {{ $empresa['Nombre'] }}</td>

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
                                                <input wire:model.defer="fechaimpu" min="2014-01-01"
                                                    max={{ date('Y-m-d') }} type="date" required>
                                                <button class="tn btn-secondary BtnVinculadas" type="submit"
                                                    wire:loading.attr="disabled">Capturar</button>
                                            </form>
                                        </td>
                                    @else
                                        <td class="text-center align-middle">
                                            <form
                                                wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Estatal')">
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
                                        <form
                                            wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Conciliacion_Impuesto')">
                                            <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                                type="date" required>
                                            <button class="tn btn-secondary BtnVinculadas" type="submit"
                                                wire:loading.attr="disabled">Capturar</button>
                                        </form>
                                    </td>
                                @else
                                    <td class="text-center align-middle">
                                        <form
                                            wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Conciliacion_Impuesto')">
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
        @break

        @case('Tareas')
            <livewire:tareacolab />
        @break

    @endswitch

    {{-- Importamos los componentes --}}
    <livewire:tareanueva />

    @if (!empty(auth()->user()->admin))
        <script>
            //Emitir los datos de la empresa al componente
            $(document).ready(function() {
                //Guardamos en variables locales el contenido de sessionstorage
                var Seccion = sessionStorage.getItem('Seccion');

                //Condicion para saber si las variables no estan vacias
                if (Seccion !== null) {
                    //Emitimos los datos al controlador
                    window.livewire.emit('tareaselect', {
                        seccion: Seccion,
                    });
                    sessionStorage.clear();
                }
            });
        </script>
    @endif
</div>
