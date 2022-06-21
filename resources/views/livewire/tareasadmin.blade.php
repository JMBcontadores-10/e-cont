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
        <label for="inputState">Mes</label>
        <select id="MesSelecTarea" wire:model="mestareaadmin" id="inputState1" wire:loading.attr="disabled"
            class="select form-control">
            <?php foreach ($meses as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            } ?>
        </select>
        &nbsp;&nbsp;

        {{-- Busqueda por año --}}
        <label for="inputState">Año</label>
        <select id="AnioSelectTarea" wire:loading.attr="disabled" wire:model="aniotareaadmin" id="inputState2"
            class="select form-control">
            <?php foreach (array_reverse($anios) as $value) {
                echo '<option value="' . $value . '">' . $value . '</option>';
            } ?>
        </select>
        &nbsp;&nbsp;

        {{-- Busqueda por avance --}}
        <label for="inputState">Avance</label>
        <select id="AvanceSelectTarea" wire:loading.attr="disabled" wire:model="avancetareaadmin" id="inputState2"
            class="select form-control">
            <option>Departamento</option>
            <option>Colaboradores</option>
            <option>Proyecto</option>
        </select>
        &nbsp;&nbsp;
    </div>

    <br><br>

    {{-- switch para seleccionar el tipo de avance --}}
    @switch($avancetareaadmin)
        @case('Departamento')
        @break

        @case('Colaboradores')
            {{-- Importamos los componentes --}}
            <livewire:tareacolab :mestareaadmin=$mestareaadmin :aniotareaadmin=$aniotareaadmin
                :wire:key="'user-profile-one-'.$mestareaadmin.$aniotareaadmin" />
        @break

        @case('Proyecto')
            {{-- Tabla de tareas --}}
            <div class="table-responsive">
                <table class="{{ $class }}" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">Proyecto</th>

                            <th class="text-center align-middle" style="background-color: #9bc2e6">
                                Impuestos federales</th>

                            <th class="text-center align-middle" style="background-color: #a9d08e">
                                Impuesto sobre Remuneraciones/Nomina</th>

                            <th class="text-center align-middle" style="background-color: #ffe699">
                                Impuesto sobre Hospedaje</th>

                            <th class="text-center align-middle" style="background-color: #f8caac">
                                IMSS</th>

                            <th class="text-center align-middle">
                                DIOT</th>

                            <th class="text-center align-middle" style="background-color: #e1eeda">
                                Balanza mensual</th>
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

                                {{-- Impuestos federales --}}
                                @if (!empty($proyectos->ImptoFederal) || !empty($empresa['Impuestos_Federales']))
                                    @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion']))
                                        <td class="text-center align-middle" style="background-color: #e0ffca">
                                            <img src="{{ asset('img/ima.png') }}" alt="">
                                        </td>
                                    @elseif (!empty($empresa['Impuestos_Federales']))
                                        <td class="text-center align-middle">
                                            <form
                                                wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Federales')">
                                                <input wire:model.defer="fechaimpu" min="2014-01-01" max={{ date('Y-m-d') }}
                                                    type="date" required>
                                                <button class="tn btn-secondary BtnVinculadas" type="submit"
                                                    wire:loading.attr="disabled">Capturar</button>
                                            </form>
                                        </td>
                                    @else
                                        <td class="text-center align-middle">
                                            <form
                                                wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'Impuestos_Federales')">
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
                                @if (!empty($proyectos->ImptoRemuneracion))
                                    @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion']))
                                        <td class="text-center align-middle" style="background-color: #e0ffca">
                                            <img src="{{ asset('img/ima.png') }}" alt="">
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

                                {{-- Impuesto sobre Hospedaje --}}
                                @if (!empty($proyectos->ImptoHospedaje))
                                    @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion']))
                                        <td class="text-center align-middle" style="background-color: #e0ffca">
                                            <img src="{{ asset('img/ima.png') }}" alt="">
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

                                {{-- IMSS --}}
                                @if (!empty($proyectos->IMSS))
                                    @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.IMSS.' . $mesespa . '_C.Declaracion']))
                                        <td class="text-center align-middle" style="background-color: #e0ffca">
                                            <img src="{{ asset('img/ima.png') }}" alt="">
                                        </td>
                                    @else
                                        <td class="text-center align-middle">
                                            <form wire:submit.prevent="ImpuFin('{{ $empresa['RFC'] }}', 'IMSS')">
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

                                {{-- Balanza mensual --}}
                                @if (!empty($proyectos->BalanMensual) || !empty($empresa['Balanza_Mensual']))
                                    @if (!empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '.Declaracion']) || !empty($expfiscales['ExpedFisc.' . $aniotareaadmin . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion']))
                                        <td class="text-center align-middle" style="background-color: #e0ffca">
                                            <img src="{{ asset('img/ima.png') }}" alt="">
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @break

    @endswitch

    {{-- Importamos los componentes --}}
    <livewire:tareanueva />
</div>
