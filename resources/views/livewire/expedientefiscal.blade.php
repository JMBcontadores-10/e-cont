<div>
    {{-- JS --}}
    <script src="{{ asset('js/expedfisc.js') }}" defer></script>

    @php
        use App\Models\Cheques;
        use App\Models\ExpedFiscal;
        use App\Models\User;
        
        //Obtenemos la clase para agregar a la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
        
        //Convertimos los meses de numero a palabra
        $espa = new Cheques();
        
        //Obtenemos los datos de facturacion
        $ConsulClient = User::where('RFC', $empresa)->first();
        
        //Condicional para saber si la empresa tiene una sucursal
        if (!empty($infoempre['Sucursales'])) {
            //Descomponemos los datos de la sucursal
            foreach ($infoempre['Sucursales'] as $datasucursal) {
                //Comparmos si las sucursales son iguales a la seleccionada
                if ($datasucursal['RFC'] == $this->sucursal) {
                    //Almacenamos los datos en variables
                    $ImptoFederal = $datasucursal['ImptoFederal'] ?? null;
                    $ImptoRemuneracion = $datasucursal['ImptoRemuneracion'] ?? null;
                    $ImptoHospedaje = $datasucursal['ImptoHospedaje'] ?? null;
                    $IMSS = $datasucursal['IMSS'] ?? null;
                    $DIOT = $datasucursal['DIOT'] ?? null;
                    $BalanMensual = $datasucursal['BalanMensual'] ?? null;
                    $Matriz = $empresa ?? null;
                    $NombreSucur = $datasucursal['Nombre'] ?? null;
                }
            }
        } else {
            //Almacenamos los datos en variables
            $ImptoFederal = $ConsulClient['ImptoFederal'] ?? null;
            $ImptoRemuneracion = $ConsulClient['ImptoRemuneracion'] ?? null;
            $ImptoHospedaje = $ConsulClient['ImptoHospedaje'] ?? null;
            $IMSS = $ConsulClient['IMSS'] ?? null;
            $DIOT = $ConsulClient['DIOT'] ?? null;
            $BalanMensual = $ConsulClient['BalanMensual'] ?? null;
        }
    @endphp

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

                    {{-- Select para selccionar la sucursal (Contadores) --}}
                    @if (!empty($infoempre['Sucursales']))
                        <br>

                        {{-- Mostramos el RFC de la sucursal que se selecciona --}}
                        <label for="inputState">Sucursal</label>
                        <select wire:model="sucursal" class="select form-control">
                            <option value="">--Selecciona Sucursal--</option>

                            {{-- Mostramos las sucursales --}}
                            @foreach ($infoempre['Sucursales'] as $dataempre)
                                <option value="{{ $dataempre['RFC'] }}">{{ $dataempre['Nombre'] }}</option>
                            @endforeach
                        </select>

                        <br>
                    @endif

                    <br>
                    <br>

                    <div align="center">
                        <h1>Expediente Fiscal {{ $anioexpe }}</h5>
                    </div>

                    <br>

                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                    <br>

                    {{-- Filtro de busqueda --}}
                    <label>Seleccione un año</label>

                    <br>

                    <div class="form-inline mr-auto">
                        {{-- Busqueda por año --}}
                        <label for="inputState">Año</label>
                        <select wire:loading.attr="disabled" wire:model="anioexpe" id="inputState2"
                            class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>

                    {{-- Creacion de la tabla --}}
                    {{-- Tabla --}}
                    <div class="table-responsive">
                        <table class="{{ $class }}" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" colspan="14">Expediente Fiscal
                                        {{ $anioexpe }}</th>
                                </tr>
                                @if ((!empty($this->rfcEmpresa) && !empty($this->sucursal)) || (!empty($this->rfcEmpresa) && empty($this->infoempre['Sucursales'])))
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2">Mes</th>

                                        @if (!empty($ImptoFederal))
                                            <th class="text-center align-middle" style="background-color: #9bc2e6"
                                                colspan="2">
                                                Impuestos federales</th>
                                        @endif

                                        @if (!empty($ImptoRemuneracion))
                                            <th class="text-center align-middle" style="background-color: #a9d08e"
                                                colspan="2">
                                                Impuesto sobre Remuneraciones/Nomina</th>
                                        @endif

                                        @if (!empty($ImptoHospedaje))
                                            <th class="text-center align-middle" style="background-color: #ffe699"
                                                colspan="2">
                                                Impuesto sobre Hospedaje</th>
                                        @endif

                                        @if (!empty($IMSS))
                                            <th class="text-center align-middle" style="background-color: #f8caac"
                                                colspan="2">
                                                IMSS</th>
                                        @endif

                                        @if (!empty($DIOT))
                                            <th class="text-center align-middle" colspan="2">
                                                DIOT</th>
                                        @endif

                                        @if (!empty($BalanMensual))
                                            <th class="text-center align-middle" style="background-color: #e1eeda"
                                                colspan="2">
                                                Balanza mensual</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if (!empty($ImptoFederal))
                                            <th class="text-center align-middle" style="background-color: #9bc2e6">Fecha
                                                presentación</th>
                                            <th class="text-center align-middle" style="background-color: #9bc2e6">
                                                Acuse(s)
                                            </th>
                                        @endif

                                        @if (!empty($ImptoRemuneracion))
                                            <th class="text-center align-middle" style="background-color: #a9d08e">Fecha
                                                presentación</th>
                                            <th class="text-center align-middle" style="background-color: #a9d08e">
                                                Acuse(s)
                                            </th>
                                        @endif

                                        @if (!empty($ImptoHospedaje))
                                            <th class="text-center align-middle" style="background-color: #ffe699">Fecha
                                                presentación</th>
                                            <th class="text-center align-middle" style="background-color: #ffe699">
                                                Acuse(s)
                                            </th>
                                        @endif

                                        @if (!empty($IMSS))
                                            <th class="text-center align-middle" style="background-color: #f8caac">Fecha
                                                presentación</th>
                                            <th class="text-center align-middle" style="background-color: #f8caac">
                                                Acuse(s)
                                            </th>
                                        @endif

                                        @if (!empty($DIOT))
                                            <th class="text-center align-middle">Fecha presentación</th>
                                            <th class="text-center align-middle">Acuse(s)</th>
                                        @endif

                                        @if (!empty($BalanMensual))
                                            <th class="text-center align-middle" style="background-color: #e1eeda">Fecha
                                                presentación</th>
                                            <th class="text-center align-middle" style="background-color: #e1eeda">
                                                Acuse(s)
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                            </thead>
                            <tbody>
                                @if ((!empty($this->rfcEmpresa) && !empty($this->sucursal)) || (!empty($this->rfcEmpresa) && empty($this->infoempre['Sucursales'])))
                                    @php
                                        //Consulta para saber si la empresa tiene una sucursal
                                        if (!empty($this->sucursal)) {
                                            //Realizamos una comsulta a los datos de expediente fiscal
                                            $consulexpefisc = ExpedFiscal::where('rfc', $this->sucursal)->first();
                                        
                                            //Almacenamos el RFC de la empresa o sucursal
                                            $rfcselect = $this->sucursal;
                                        } else {
                                            //Realizamos una comsulta a los datos de expediente fiscal
                                            $consulexpefisc = ExpedFiscal::where('rfc', $empresa)->first();
                                        
                                            //Almacenamos el RFC de la empresa o sucursal
                                            $rfcselect = $empresa;
                                        }
                                    @endphp

                                    {{-- Contenido de neustra tabla --}}
                                    {{-- Ciclo para pasar por todas los meses --}}
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php
                                            //Llamamos al metodo del modelo para obtener el mes en cadena
                                            $mesespa = $espa->fecha_es($i);
                                        @endphp

                                        <tr>
                                            {{-- Meses --}}
                                            <td>
                                                {{ $mesespa }}

                                                <div {{ $active }}>
                                                    <br>
                                                    {{-- Checkbox para mostrar los complementarios --}}
                                                    <label>Complement. <input class="Complemen" type="checkbox"
                                                            mescomple="{{ $mesespa }}_C"></label>
                                                </div>
                                            </td>

                                            {{-- Condicional para importar la funcion de JS --}}
                                            @if (!empty($ImptoFederal) || !empty($ImptoRemuneracion) || !empty($ImptoHospedaje) || !empty($IMSS) || !empty($DIOT) || !empty($BalanMensual))
                                                <script>
                                                    //Boton para mostrar el formulario
                                                    $(".fechapre").click(function() {
                                                        //Ocultamos los formularios abiertos
                                                        $(".formexpe").hide();

                                                        //Obtenemos el identificador de cada boton
                                                        var idformu = $(this).attr("formulario");

                                                        //Mostramos el formulario
                                                        $("#" + idformu).show();

                                                        //Agregamos los datos al input
                                                        $("#DataExpeDig").val(idformu);
                                                    });

                                                    $(".Complemen").click(function() {
                                                        //Obtenemos el valor del atributo
                                                        var MesComple = $(this).attr('mescomple');

                                                        //Condicional para saber el estado del checkbox
                                                        if ($(this).is(':checked')) {
                                                            //Desmarcamos los checkboxs
                                                            $(".Complemen").prop('checked', false);

                                                            //Escondemos la fila de complementarios
                                                            $(".Complement").attr('hidden', true);

                                                            //Mostramos el complementario seleccionado
                                                            $("#" + MesComple).attr('hidden', false);

                                                            //Marcamos el Checkbox seleccionado
                                                            $(this).prop('checked', true);
                                                        } else {
                                                            //Mostramos el complementario seleccionado
                                                            $("#" + MesComple).attr('hidden', true);
                                                        }
                                                    });
                                                </script>
                                            @endif

                                            @if (!empty($ImptoFederal))
                                                {{-- Impuestos Federales --}}

                                                {{-- Fecha presentacion --}}
                                                <td style="background-color: #d4e7f9">
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Federales.' . $mesespa . '.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Federales-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Federales-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Federales.' . $mesespa . '.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Impuestos_Federales-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Federales.' . $mesespa . '.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Federales', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Federales', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($ImptoRemuneracion))
                                                {{-- Impuestos sobre Remuneraciones --}}

                                                {{-- Fecha presentacion --}}
                                                <td style="background-color: #dfffc9">
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Remuneraciones-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Remuneraciones-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Remuneraciones.' . $mesespa . '.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Impuestos_Remuneraciones-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Remuneraciones.' . $mesespa . '.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Remuneraciones', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Remuneraciones', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($ImptoHospedaje))
                                                {{-- Impuestos sobre Hospedaje --}}

                                                {{-- Fecha presentacion --}}
                                                <td style="background-color: #fff1c5">
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Hospedaje-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Hospedaje-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Hospedaje.' . $mesespa . '.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Impuestos_Hospedaje-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Hospedaje.' . $mesespa . '.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Hospedaje', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Hospedaje', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($IMSS))
                                                {{-- IMSS --}}

                                                {{-- Fecha presentacion --}}
                                                <td style="background-color: #ffe3d0">
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.IMSS.' . $mesespa . '.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="IMSS-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="IMSS-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.IMSS.' . $mesespa . '.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="IMSS-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.IMSS.' . $mesespa . '.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('IMSS', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('IMSS', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($DIOT))
                                                {{-- DIOT --}}

                                                {{-- Fecha presentacion --}}
                                                <td style="background-color: #f7f7f7">
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.DIOT.' . $mesespa . '.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="DIOT-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="DIOT-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.DIOT.' . $mesespa . '.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="DIOT-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.DIOT.' . $mesespa . '.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('DIOT', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('DIOT', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($BalanMensual))
                                                {{-- Balanza Mensual --}}

                                                {{-- Fecha presentacion --}}
                                                <td style="background-color: #edfbe6">
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Balanza_Mensual.' . $mesespa . '.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Balanza_Mensual-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Balanza_Mensual-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Balanza_Mensual.' . $mesespa . '.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Balanza_Mensual-{{ $rfcselect }}-{{ $mesespa }}-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Balanza_Mensual.' . $mesespa . '.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Balanza_Mensual', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Balanza_Mensual', '{{ $rfcselect }}', '{{ $mesespa }}', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>



                                        {{-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++ Complementarios +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}
                                        @php
                                            //Arreglo con los tipos de impuestos
                                            $tipos = ['Impuestos_Federales', 'Impuestos_Remuneraciones', 'Impuestos_Hospedaje', 'IMSS', 'DIOT', 'Balanza_Mensual'];
                                            
                                            //Declaramos la variable para el loop
                                            $activecomple = 'hidden';
                                            
                                            //Bucle para saber si hay algo cargado y mostrar los complementos
                                            foreach ($tipos as $tipoexpe) {
                                                if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.' . $tipoexpe . '.' . $mesespa . '_C.Declaracion'])) {
                                                    $activecomple = null;
                                            
                                                    //Emitimos para marca el checkbox del mes abierto
                                                    $this->dispatchBrowserEvent('showcomplemes', ['mes' => $mesespa . '_C']);
                                                }
                                            }
                                        @endphp


                                        <tr class="Complement" id="{{ $mesespa }}_C" {{ $activecomple }}>
                                            {{-- Meses --}}
                                            <td>
                                                <label>{{ $mesespa }} Complementario</label>
                                            </td>


                                            @if (!empty($ImptoFederal))
                                                {{-- Impuestos Federales --}}

                                                {{-- Fecha presentacion --}}
                                                <td>
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Federales-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Federales-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Federales.' . $mesespa . '_C.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Impuestos_Federales-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Federales.' . $mesespa . '_C.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Federales', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Federales', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($ImptoRemuneracion))
                                                {{-- Impuestos sobre Remuneraciones --}}

                                                {{-- Fecha presentacion --}}
                                                <td>
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Remuneraciones-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Remuneraciones-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Impuestos_Remuneraciones-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Remuneraciones.' . $mesespa . '_C.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Remuneraciones', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Remuneraciones', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($ImptoHospedaje))
                                                {{-- Impuestos sobre Hospedaje --}}

                                                {{-- Fecha presentacion --}}
                                                <td>
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Hospedaje-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Impuestos_Hospedaje-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Hospedaje.' . $mesespa . '_C.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Impuestos_Hospedaje-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Impuestos_Hospedaje.' . $mesespa . '_C.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Hospedaje', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Impuestos_Hospedaje', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($IMSS))
                                                {{-- IMSS --}}

                                                {{-- Fecha presentacion --}}
                                                <td>
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.IMSS.' . $mesespa . '_C.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="IMSS-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="IMSS-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.IMSS.' . $mesespa . '_C.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="IMSS-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.IMSS.' . $mesespa . '_C.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('IMSS', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('IMSS', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($DIOT))
                                                {{-- DIOT --}}

                                                {{-- Fecha presentacion --}}
                                                <td>
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.DIOT.' . $mesespa . '_C.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="DIOT-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="DIOT-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.DIOT.' . $mesespa . '_C.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="DIOT-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.DIOT.' . $mesespa . '_C.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('DIOT', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('DIOT', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $Matriz ?? null }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            @if (!empty($BalanMensual))
                                                {{-- Balanza Mensual --}}

                                                {{-- Fecha presentacion --}}
                                                <td>
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion']))
                                                        <a {{ $active }}
                                                            formulario="Balanza_Mensual-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar content_true"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            formulario="Balanza_Mensual-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}"
                                                            class="fechapre selectfecha icons fas fas fa-calendar"></a>
                                                    @endif
                                                    <label
                                                        {{ $active }}>{{ $consulexpefisc['ExpedFisc.' . $anioexpe . '.Balanza_Mensual.' . $mesespa . '_C.Declaracion'] ?? 'Sin fecha' }}</label>
                                                    <div class="formexpe"
                                                        id="Balanza_Mensual-{{ $rfcselect }}-{{ $mesespa }}_C-{{ $anioexpe }}">
                                                        <br>
                                                        <form wire:submit.prevent="FechaPresent">
                                                            <input min="2014-01-01" max={{ date('Y-m-d') }}
                                                                wire:model.defer="fechapresent" type="date">
                                                            <button onclick="RegDatos()"
                                                                class="tn btn-secondary BtnVinculadas" type="submit"
                                                                wire:loading.attr="disabled">Capturar</button>
                                                        </form>
                                                    </div>
                                                </td>

                                                {{-- Acuse --}}
                                                <td>
                                                    {{-- Condicional para saber si hay un PDF cargado --}}
                                                    @if (!empty($consulexpefisc['ExpedFisc.' . $anioexpe . '.Balanza_Mensual.' . $mesespa . '_C.Acuse']))
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf content_true_pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Balanza_Mensual', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @else
                                                        <a {{ $active }}
                                                            class="selectfecha icons fas fa-file-pdf"
                                                            data-toggle="modal"
                                                            wire:click="SendDataAcuse('Balanza_Mensual', '{{ $rfcselect }}', '{{ $mesespa }}_C', '{{ $anioexpe }}', '{{ $NombreSucur ?? null }}')"
                                                            data-target="#acuseexp" data-backdrop="static"
                                                            data-keyboard="false"></a>
                                                    @endif
                                                </td>
                                            @endif

                                            {{-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++ Complementarios +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}
                                        </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <input id="DataExpeDig" type="hidden">

    {{-- Llamamos a los modales --}}
    {{-- Acuses --}}
    <livewire:expediacuse>

        {{-- Js --}}
        <script>
            //Funcion para alamcenar el valor de los datos
            function RegDatos() {
                //Obtenemos el valor del input
                var DataExp = $("#DataExpeDig").val();

                //Mandamos el JSON al servidor
                @this.set('dataregistr', DataExp);
            }
        </script>
</div>
