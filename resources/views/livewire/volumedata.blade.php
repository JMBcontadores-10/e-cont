<div>
    @php
        //Importamos el modelo
        use App\Models\User;
        
        //Hacemos una consulta de la empresa para saber que datos vamos a mostrar
        $infogas = User::where('RFC', $this->empresa)
            ->get()
            ->first();
    @endphp

    {{-- Modal de la captura de precio --}}
    {{-- Creacion del modal (BASE) --}}
    <div wire:ignore.self class="modal fade" id="volucaptumodal{{ $dia }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal{{ $dia }}">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-gas-pump">Capturar volumétrico {{ $dia }}</span></h6>
                    <button id="closevoludata{{ $dia }}" type="button" class="close"
                        wire:click="Refresh()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Mensaje de que no hay un inventiario final --}}
                    @if (empty($fechaayer))
                        <div class="alert alert-warning">
                            No existe un "Inventario Real" del día anterior, puede capturar los datos volumétricos de
                            este día, pero de favor llene el día anterior si se tiene los datos
                        </div>
                    @endif

                    {{-- Condicional para mostrar las tablas cuando haya un rfc --}}
                    @if ($empresa)
                        {{-- Cambiar de tabla --}}
                        <div id="CambioTabla">
                            <div class="row" align="center">
                                {{-- Seccion original --}}
                                <div class="col">
                                    {{-- Boton --}}
                                    <div class="btnoricircle">
                                        {{-- Icono --}}
                                        <div class="btniconvolu">
                                            <i class="fas fa-file-invoice-dollar fa-2x"></i>
                                        </div>
                                    </div>
                                    {{-- Descripcion --}}
                                    Volumetrico
                                </div>
                                {{-- Seccion cambio de precio --}}
                                <div class="col">
                                    {{-- Boton --}}
                                    <div class="btncambipreccircle">
                                        {{-- Icono --}}
                                        <div class="btniconvolu">
                                            <i class="fas fa-file-medical fa-2x"></i>
                                        </div>
                                    </div>
                                    {{-- Descripcion --}}
                                    Cambio de precio
                                </div>
                            </div>
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

                        {{-- Tabla de captura --}}
                        {{-- Volumetricos --}}
                        <div class="Tablacaptu">
                            <form class="FormCaptu{{ $dia }}" wire:submit.prevent="NuevoVolu">

                                {{-- Input que contiene la fecha --}}
                                <input id="FechaVolu{{ $dia }}" wire:model.defer="fecha" type="hidden"
                                    name="Fecha">

                                <div id="resp-table">
                                    <div id="resp-table-body">
                                        {{-- Encabezado de la tabla --}}
                                        <div class="resp-table-row">
                                            <div class="tr table-body-cell"></div>

                                            @if ($Magna == '1')
                                                <div class="tr table-body-cell" style="background: #C8F5DE">
                                                    <label>MAGNA</label>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="tr table-body-cell" style="background: #FFD1D1">
                                                    <label>PREMIUM</label>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="tr table-body-cell" style="background: #cdcdcd">
                                                    <label>DIESEL</label>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Cuerpo de la tabla --}}

                                        {{-- INVENTARIO INICIAL --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Inventario inicial</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventinicmagna{{ $dia }}" name="IventInicM"
                                                        wire:model.defer="inventinicmagna" class="form-control Magna"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventinicpremium{{ $dia }}" name="IventInicP"
                                                        wire:model.defer="inventinicpremium"
                                                        class="form-control Premium" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventinicdiesel{{ $dia }}" name="IventInicD"
                                                        wire:model.defer="inventinicdiesel" class="form-control Diesel"
                                                        required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- COMPRAS --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Compras</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" id="compramagna{{ $dia }}"
                                                        name="CompraM" wire:model.defer="compramagna"
                                                        class="form-control Magna CompraM" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="CompraP"
                                                        id="comprapremium{{ $dia }}"
                                                        wire:model.defer="comprapremium"
                                                        class="form-control Premium CompraP" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="CompraD"
                                                        id="compradiesel{{ $dia }}"
                                                        wire:model.defer="compradiesel"
                                                        class="form-control Diesel CompraD" required>
                                                </div>
                                            @endif
                                        </div>


                                        {{-- PRECIO DE COMPRA --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Precio de compra</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecCompM"
                                                        wire:model.defer="preccompmagna"
                                                        class="form-control CompM{{ $dia }}" required
                                                        {{ $activem }}>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecCompP"
                                                        wire:model.defer="preccomppremium"
                                                        class="form-control CompP{{ $dia }}" required
                                                        {{ $activep }}>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecCompD"
                                                        wire:model.defer="preccompdiesel"
                                                        class="form-control CompD{{ $dia }}" required
                                                        {{ $actived }}>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Condicional para que se muestre solamente en una empresa --}}
                                        @if ($infogas['PrecCompDesc'] == 1)
                                            {{-- COMPRAS CON DESCUENTO --}}
                                            <div class="resp-table-row">
                                                <div class="table-body-cell">
                                                    <label>Precio de compra (Con descuento)</label>
                                                </div>

                                                @if ($Magna == '1')
                                                    <div class="table-body-cell">
                                                        <input type="number" step="0.01"
                                                            id="compradescmagna{{ $dia }}" name="CompraDescM"
                                                            wire:model.defer="compradescmagna"
                                                            class="form-control Magna CompM{{ $dia }}"
                                                            required {{ $activem }}>
                                                    </div>
                                                @endif

                                                @if ($Premium == '1')
                                                    <div class="table-body-cell">
                                                        <input type="number" step="0.01" name="CompraDescP"
                                                            id="compradescpremium{{ $dia }}"
                                                            wire:model.defer="compradescpremium"
                                                            class="form-control Premium CompP{{ $dia }}"
                                                            required {{ $activep }}>
                                                    </div>
                                                @endif

                                                @if ($Diesel == '1')
                                                    <div class="table-body-cell">
                                                        <input type="number" step="0.01" name="CompraDescD"
                                                            id="compradescdiesel{{ $dia }}"
                                                            wire:model.defer="compradescdiesel"
                                                            class="form-control Diesel CompD{{ $dia }}"
                                                            required {{ $actived }}>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- LITROS VENDIDOS --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Litros vendidos</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="LitVendM"
                                                        id="litvendmagna{{ $dia }}"
                                                        wire:model.defer="litvendmagna" class="form-control Magna"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="LitVendP"
                                                        id="litvendpremium{{ $dia }}"
                                                        wire:model.defer="litvendpremium" class="form-control Premium"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="LitVendD"
                                                        id="litvenddiesel{{ $dia }}"
                                                        wire:model.defer="litvenddiesel" class="form-control Diesel"
                                                        required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- PRECIO VENTA --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Precio venta</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecVentM"
                                                        wire:model.defer="precventmagna" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecVentP"
                                                        wire:model.defer="precventpremium" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecVentD"
                                                        wire:model.defer="precventdiesel" class="form-control"
                                                        required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- INVENTARIO REAL (AUTOSTICK) --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Inventario real (AutoStick)</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input id="autostickmagna{{ $dia }}" type="number"
                                                        step="0.01" name="AutoStickM" wire:model.defer="autostickmagna"
                                                        class="form-control Magna" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="autostickpremium{{ $dia }}" type="number"
                                                        step="0.01" name="AutoStickP"
                                                        wire:model.defer="autostickpremium" class="form-control Premium"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="autostickdiesel{{ $dia }}" type="number"
                                                        step="0.01" name="AutoStickD" wire:model.defer="autostickdiesel"
                                                        class="form-control Diesel" required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- INVENTARIO DETERMINADO --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Inventario determinado</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdetermagna{{ $dia }}" name="InvDeterM"
                                                        wire:model.defer="invdetermagna" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdeterpremium{{ $dia }}" name="InvDeterP"
                                                        wire:model.defer="invdeterpremium" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdeterdiesel{{ $dia }}" name="InvDeterD"
                                                        wire:model.defer="invdeterdiesel" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- MERMA --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Merma</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input id="mermamagna{{ $dia }}" name="MermaM"
                                                        wire:model.defer="mermamagna" class="form-control" readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="mermapremium{{ $dia }}" name="MermaP"
                                                        wire:model.defer="mermapremium" class="form-control" readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="mermadiesel{{ $dia }}" name="MermaD"
                                                        wire:model.defer="mermadiesel" class="form-control" readonly>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <br>

                                {{-- Boton para gusrdar los datos --}}
                                <button id="BtnSaveVolu" type="submit" wire:loading.attr="disabled"
                                    class="btn btn-secondary btnsavevolu">Capturar</button>
                            </form>
                        </div>

                        {{-- Cambio de precio --}}
                        <div class="Tablacambipreci">
                            <label>*Llenar en caso de cambio de precio</label>

                            <br>

                            <form class="CambiPreci{{ $dia }}" wire:submit.prevent="CambioPrec">

                                {{-- Input que contiene la fecha --}}
                                <input wire:model.defer="fecha" id="FechaCamb{{ $dia }}" type="hidden"
                                    name="Fecha">

                                <div id="resp-table">
                                    <div id="resp-table-body">
                                        {{-- Encabezado de la tabla --}}
                                        <div class="resp-table-row">
                                            <div class="tr table-body-cell"></div>

                                            @if ($Magna == '1')
                                                <div class="tr table-body-cell" style="background: #C8F5DE">
                                                    <label>MAGNA</label>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="tr table-body-cell" style="background: #FFD1D1">
                                                    <label>PREMIUM</label>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="tr table-body-cell" style="background: #cdcdcd">
                                                    <label>DIESEL</label>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Cuerpo de la tabla --}}

                                        {{-- INVENTARIO INICIAL --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Inventario inicial</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventiniccambmagna{{ $dia }}" name="IventInicM"
                                                        wire:model.defer="inventiniccambmagna"
                                                        class="form-control MagnaCamb" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventiniccambpremium{{ $dia }}" name="IventInicP"
                                                        wire:model.defer="inventiniccambpremium"
                                                        class="form-control PremiumCamb" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventiniccambdiesel{{ $dia }}" name="IventInicD"
                                                        wire:model.defer="inventiniccambdiesel"
                                                        class="form-control DieselCamb" required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- COMPRAS --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Compras</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="compracambmagna{{ $dia }}" name="CompraM"
                                                        wire:model.defer="compracambmagna"
                                                        class="form-control MagnaCamb" required readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="compracambpremium{{ $dia }}" name="CompraP"
                                                        wire:model.defer="compracambpremium"
                                                        class="form-control PremiumCamb" required readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="compracambdiesel{{ $dia }}" name="CompraD"
                                                        wire:model.defer="compracambdiesel"
                                                        class="form-control DieselCamb" required readonly>
                                                </div>
                                            @endif
                                        </div>


                                        {{-- PRECIO DE COMPRA --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Precio de compra</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecCompM"
                                                        wire:model.defer="preccompcambmagna" class="form-control"
                                                        required readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecCompP"
                                                        wire:model.defer="preccompcambpremium" class="form-control"
                                                        required readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecCompD"
                                                        wire:model.defer="preccompcambdiesel" class="form-control"
                                                        required readonly>
                                                </div>
                                            @endif
                                        </div>


                                        {{-- Condicional para que se muestre solamente en una empresa --}}
                                        @if ($empresa == 'STR9303188X3')
                                            {{-- COMPRAS CON DESCUENTO --}}
                                            <div class="resp-table-row">
                                                <div class="table-body-cell">
                                                    <label>Precio de compra (Con descuento)</label>
                                                </div>

                                                @if ($Magna == '1')
                                                    <div class="table-body-cell">
                                                        <input type="number" step="0.01"
                                                            id="compracambdescmagna{{ $dia }}"
                                                            name="CompraDescM" wire:model.defer="compracambdescmagna"
                                                            class="form-control MagnaCamb" required readonly>
                                                    </div>
                                                @endif

                                                @if ($Premium == '1')
                                                    <div class="table-body-cell">
                                                        <input type="number" step="0.01"
                                                            id="compracambdescpremium{{ $dia }}"
                                                            name="CompraDescP" wire:model.defer="compracambdescpremium"
                                                            class="form-control PremiumCamb" required readonly>
                                                    </div>
                                                @endif

                                                @if ($Diesel == '1')
                                                    <div class="table-body-cell">
                                                        <input type="number" step="0.01"
                                                            id="compracambdescdiesel{{ $dia }}"
                                                            name="CompraDescD" wire:model.defer="compracambdescdiesel"
                                                            class="form-control DieselCamb" required readonly>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- LITROS VENDIDOS --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Litros vendidos</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendcambmagna{{ $dia }}" name="LitVendM"
                                                        wire:model.defer="litvendcambmagna"
                                                        class="form-control MagnaCamb" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendcambpremium{{ $dia }}" name="LitVendP"
                                                        wire:model.defer="litvendcambpremium"
                                                        class="form-control PremiumCamb" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendcambdiesel{{ $dia }}" name="LitVendD"
                                                        wire:model.defer="litvendcambdiesel"
                                                        class="form-control DieselCamb" required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- PRECIO VENTA --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Precio venta</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecVentM"
                                                        wire:model.defer="precventcambmagna" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecVentP"
                                                        wire:model.defer="precventcambpremium" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" name="PrecVentD"
                                                        wire:model.defer="precventcambdiesel" class="form-control"
                                                        required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- INVENTARIO REAL (AUTOSTICK) --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Inventario real (AutoStick)</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input id="autostickcambmagna{{ $dia }}" type="number"
                                                        step="0.01" name="AutoStickM"
                                                        wire:model.defer="autostickcambmagna"
                                                        class="form-control MagnaCamb" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="autostickcambpremium{{ $dia }}" type="number"
                                                        step="0.01" name="AutoStickP"
                                                        wire:model.defer="autostickcambpremium"
                                                        class="form-control PremiumCamb" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="autostickcambdiesel{{ $dia }}" type="number"
                                                        step="0.01" name="AutoStickD"
                                                        wire:model.defer="autostickcambdiesel"
                                                        class="form-control DieselCamb" required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- INVENTARIO DETERMINADO --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Inventario determinado</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdetercambmagna{{ $dia }}" name="InvDeterM"
                                                        wire:model.defer="invdetercambmagna" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdetercambpremium{{ $dia }}" name="InvDeterP"
                                                        wire:model.defer="invdetercambpremium" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdetercambdiesel{{ $dia }}" name="InvDeterD"
                                                        wire:model.defer="invdetercambdiesel" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- MERMA --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Merma</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input id="mermacambmagna{{ $dia }}" name="MermaM"
                                                        wire:model.defer="mermacambmagna" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="mermacambpremium{{ $dia }}" name="MermaP"
                                                        wire:model.defer="mermacambpremium" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="mermacambdiesel{{ $dia }}" name="MermaD"
                                                        wire:model.defer="mermacambdiesel" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <br>

                                {{-- Boton para gusrdar los datos --}}
                                <button type="submit" wire:loading.attr="disabled"
                                    class="btn btn-secondary btncambioprec">Capturar</button>
                            </form>
                        </div>
                    @else
                        <label>No hay una empresa seleccionada, favor de seleccionar una</label>
                    @endif
                </div>
            </div>
            {{-- Input que alamcena el formulario a enviar --}}
            <input name="formdatavolu" wire:model.defer="formdatavolu" type="hidden">
        </div>
    </div>

    {{-- Js --}}
    <script>
        $(document).ready(function() {
            //Dehabilitamos el cambio de cantidad utilizando scroll
            $(document).on("wheel", "input[type=number]", function(e) {
                $(this).blur();
            });

            //Hacemos click al boton de cerrar del modal
            window.addEventListener('CerrarVoluData', event => {
                $("#closevoludata" + event.detail.dia).click();
            });

            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            //Calculos para inventario determinado
            //Volumetrico

            //Magna
            $(".Magna").keyup(function() {
                //Variables
                var InvInicM = 0;
                var ComprasM = 0;
                var LinVendM = 0;
                var InvDeterM = 0;
                var InvRealM = 0;
                var MermaM = 0;
                var Fecha = $("#FechaSelect").val();

                //Variables para sacar inventario determinado
                //Inventario inicial
                InvInicM = $("#inventinicmagna" + Fecha).val();

                //Compras
                ComprasM = $("#compramagna" + Fecha).val();

                //Litros vendidos
                LinVendM = $("#litvendmagna" + Fecha).val();

                //Sacamos el inventario determinado
                InvDeterM = (parseFloat(InvInicM) + parseFloat(ComprasM)) - parseFloat(LinVendM);
                $("#invdetermagna" + Fecha).val(InvDeterM.toFixed(2));

                //Variables para sacar merma
                //Inventario Determinado
                InvDeterM = $("#invdetermagna" + Fecha).val();

                //Inventario Real (Autostick)
                InvRealM = $("#autostickmagna" + Fecha).val();

                //Sacamos la merma
                MermaM = parseFloat(InvDeterM) - parseFloat(InvRealM);
                $("#mermamagna" + Fecha).val(MermaM.toFixed(2));
            });

            //Premium
            $(".Premium").keyup(function() {
                //Variables
                var InvInicP = 0;
                var ComprasP = 0;
                var LinVendP = 0;
                var InvDeterP = 0;
                var InvRealP = 0;
                var MermaP = 0;
                var Fecha = $("#FechaSelect").val();

                //Inventario inicial
                InvInicP = $("#inventinicpremium" + Fecha).val();

                //Compras
                ComprasP = $("#comprapremium" + Fecha).val();

                //Litros vendidos
                LinVendP = $("#litvendpremium" + Fecha).val();

                //Sacamos el inventario determinado
                InvDeterP = (parseFloat(InvInicP) + parseFloat(ComprasP)) - parseFloat(LinVendP);
                $("#invdeterpremium" + Fecha).val(InvDeterP.toFixed(2));

                //Variables para sacar merma
                //Inventario Determinado
                InvDeterP = $("#invdeterpremium" + Fecha).val();

                //Inventario Real (Autostick)
                InvRealP = $("#autostickpremium" + Fecha).val();

                //Sacamos la merma
                MermaP = parseFloat(InvDeterP) - parseFloat(InvRealP);
                $("#mermapremium" + Fecha).val(MermaP.toFixed(2));
            });

            //Diesel
            $(".Diesel").keyup(function() {
                //Variables
                var InvInicD = 0;
                var ComprasD = 0;
                var LinVendD = 0;
                var InvDeterD = 0;
                var InvRealD = 0;
                var MermaD = 0;
                var Fecha = $("#FechaSelect").val();

                //Inventario inicial
                InvInicD = $("#inventinicdiesel" + Fecha).val();

                //Compras
                ComprasD = $("#compradiesel" + Fecha).val();

                //Litros vendidos
                LinVendD = $("#litvenddiesel" + Fecha).val();

                //Sacamos el inventario determinado
                InvDeterD = (parseFloat(InvInicD) + parseFloat(ComprasD)) - parseFloat(LinVendD);
                $("#invdeterdiesel" + Fecha).val(InvDeterD.toFixed(2));

                //Variables para sacar merma
                //Inventario Determinado
                InvDeterD = $("#invdeterdiesel" + Fecha).val();

                //Inventario Real (Autostick)
                InvRealD = $("#autostickdiesel" + Fecha).val();

                //Sacamos la merma
                MermaD = parseFloat(InvDeterD) - parseFloat(InvRealD);
                $("#mermadiesel" + Fecha).val(MermaD.toFixed(2));
            });



            //Accion para el boton de envio de datos
            $(".btnsavevolu").click(function() {
                //Fecha seleccionada
                var Fecha = $("#FechaSelect").val();

                //Agregamos los datos de la fecha
                $("#FechaVolu" + Fecha).val(Fecha);

                //Variable de contador
                var InputLlenos = 0;

                //Vamos a obtener el valor total de input del formulario
                var TotalInput = $(".FormCaptu" + Fecha + " input[type=number]").length;

                //Ciclo para acceder a todos los valores de los input
                $(".FormCaptu" + Fecha + " input[type=number]").each(function() {
                    //Conidcional para saber si el input esta lleno
                    if ($(this).val() !== "") {
                        InputLlenos++; //Sumamos uno al contador
                    }
                });

                //Corroboramos que el total de inputs y el total de llenos sea el mismo
                if (InputLlenos == TotalInput) {
                    //Campturamos el formulario
                    var FormSeriVolu = $(".FormCaptu" + Fecha);

                    //Serializamos el formulario en un arreglo
                    var FormSeriArray = FormSeriVolu.serializeArray();

                    //Llaves para la creacion del JSON
                    var IndexJsonVolu = {};

                    //Con un ciclo pasamos los datos del formulario serializado a las llaves
                    $.map(FormSeriArray, function(n, i) {
                        IndexJsonVolu[n['name']] = n['value'];
                    });

                    //Obtenemos los determinados
                    //Datos de los input
                    var indeterM = $("#invdetermagna" + Fecha).val();
                    var indeterP = $("#invdeterpremium" + Fecha).val();
                    var indeterD = $("#invdeterdiesel" + Fecha).val();

                    //Los insertamos en las variables
                    @this.set('invdetermagna', indeterM);
                    @this.set('invdeterpremium', indeterP);
                    @this.set('invdeterdiesel', indeterD);

                    //Mandamos el JSON al servidor
                    @this.set('formdatavolu', IndexJsonVolu);
                }
            });

            //**********************************************************************************************************************
            //Calculos para inventario determinado
            //Cambio de precio

            //Magna
            $(".MagnaCamb").keyup(function() {
                //Variables
                var InvInicCambM = 0;
                var ComprasCambM = 0;
                var LinVendCambM = 0;
                var InvDeterCambM = 0;
                var InvRealCambM = 0;
                var MermaCambM = 0;
                var Fecha = $("#FechaSelect").val();

                //Inventario inicial
                InvInicCambM = $("#inventiniccambmagna" + Fecha).val();

                //Compras
                ComprasCambM = $("#compracambmagna" + Fecha).val();

                //Litros vendidos
                LinVendCambM = $("#litvendcambmagna" + Fecha).val();

                //Sacamos el inventario determinado
                InvDeterCambM = (parseFloat(InvInicCambM) + parseFloat(ComprasCambM)) - parseFloat(
                    LinVendCambM);
                $("#invdetercambmagna" + Fecha).val(InvDeterCambM.toFixed(2));

                //Variables para sacar merma
                //Inventario Determinado
                InvDeterCambM = $("#invdetercambmagna" + Fecha).val();

                //Inventario Real (Autostick)
                InvRealCambM = $("#autostickcambmagna" + Fecha).val();

                //Sacamos la merma
                MermaCambM = parseFloat(InvDeterCambM) - parseFloat(InvRealCambM);
                $("#mermacambmagna" + Fecha).val(MermaCambM.toFixed(2));
            });

            //Premium
            $(".PremiumCamb").keyup(function() {
                //Variables
                var InvInicCambP = 0;
                var ComprasCambP = 0;
                var LinVendCambP = 0;
                var InvDeterCambP = 0;
                var InvRealCambP = 0;
                var MermaCambP = 0;
                var Fecha = $("#FechaSelect").val();

                //Inventario inicial
                InvInicCambP = $("#inventiniccambpremium" + Fecha).val();

                //Compras
                ComprasCambP = $("#compracambpremium" + Fecha).val();

                //Litros vendidos
                LinVendCambP = $("#litvendcambpremium" + Fecha).val();

                //Sacamos el inventario determinado
                InvDeterCambP = (parseFloat(InvInicCambP) + parseFloat(ComprasCambP)) - parseFloat(
                    LinVendCambP);
                $("#invdetercambpremium" + Fecha).val(InvDeterCambP.toFixed(2));

                //Variables para sacar merma
                //Inventario Determinado
                InvDeterCambP = $("#invdetercambpremium" + Fecha).val();

                //Inventario Real (Autostick)
                InvRealCambP = $("#autostickcambpremium" + Fecha).val();

                //Sacamos la merma
                MermaCambP = parseFloat(InvDeterCambP) - parseFloat(InvRealCambP);
                $("#mermacambpremium" + Fecha).val(MermaCambP.toFixed(2));
            });

            //Diesel
            $(".DieselCamb").keyup(function() {
                //Variables
                var InvInicCambD = 0;
                var ComprasCambD = 0;
                var LinVendCambD = 0;
                var InvDeterCambD = 0;
                var InvRealCambD = 0;
                var MermaCambD = 0;
                var Fecha = $("#FechaSelect").val();

                //Inventario inicial
                InvInicCambD = $("#inventiniccambdiesel" + Fecha).val();

                //Compras
                ComprasCambD = $("#compracambdiesel" + Fecha).val();

                //Litros vendidos
                LinVendCambD = $("#litvendcambdiesel" + Fecha).val();

                //Sacamos el inventario determinado
                InvDeterCambD = (parseFloat(InvInicCambD) + parseFloat(ComprasCambD)) - parseFloat(
                    LinVendCambD);
                $("#invdetercambdiesel" + Fecha).val(InvDeterCambD.toFixed(2));

                //Variables para sacar merma
                //Inventario Determinado
                InvDeterCambD = $("#invdetercambdiesel" + Fecha).val();

                //Inventario Real (Autostick)
                InvRealCambD = $("#autostickcambdiesel" + Fecha).val();

                //Sacamos la merma
                MermaCambD = parseFloat(InvDeterCambD) - parseFloat(InvRealCambD);
                $("#mermacambdiesel" + Fecha).val(MermaCambD.toFixed(2));
            });

            $(".btncambioprec").click(function() {
                //Fecha seleccionada
                var Fecha = $("#FechaSelect").val();

                //Agregamos los datos de la fecha
                $("#FechaCamb" + Fecha).val(Fecha);

                //Variable de contador
                var InputLlenos = 0;

                //Vamos a obtener el valor total de input del formulario
                var TotalInput = $(".CambiPreci" + Fecha + " input[type=number]").length;

                //Ciclo para acceder a todos los valores de los input
                $(".CambiPreci" + Fecha + " input[type=number]").each(function() {
                    //Conidcional para saber si el input esta lleno
                    if ($(this).val() !== "") {
                        InputLlenos++; //Sumamos uno al contador
                    }
                });

                //Corroboramos que el total de inputs y el total de llenos sea el mismo
                if (InputLlenos == TotalInput) {
                    //Campturamos el formulario
                    var FormSeriVolu = $(".CambiPreci" + Fecha);

                    //Serializamos el formulario en un arreglo
                    var FormSeriArray = FormSeriVolu.serializeArray();

                    //Llaves para la creacion del JSON
                    var IndexJsonVolu = {};

                    //Con un ciclo pasamos los datos del formulario serializado a las llaves
                    $.map(FormSeriArray, function(n, i) {
                        IndexJsonVolu[n['name']] = n['value'];
                    });

                    //Mandamos el JSON al servidor
                    @this.set('formdatavolu', IndexJsonVolu);
                }
            });

            //**********************************************************************************************************************
            //Control de los inputs de precio de compra y con descuento
            //Volumetrico

            //Magna
            $(".CompraM").keyup(function() {
                //Fecha seleccionada
                var Fecha = $("#FechaSelect").val();

                //Obtenemos el valor del input
                var ValCompraM = $(this).val();

                //Condicional para desbloquear los input si el valor es mayor que cero
                if (ValCompraM > 0) {
                    //Desbloqueamos el boton
                    $(".CompM" + Fecha).prop('readonly', false);
                } else {
                    //Bloqueamos el boton
                    $(".CompM" + Fecha).prop('readonly', true);

                    //Agregamos un 0 a los input
                    $(".CompM" + Fecha).val(0);
                }
            });

            //Premium
            $(".CompraP").keyup(function() {
                //Fecha seleccionada
                var Fecha = $("#FechaSelect").val();

                //Obtenemos el valor del input
                var ValCompraP = $(this).val();

                //Condicional para desbloquear los input si el valor es mayor que cero
                if (ValCompraP > 0) {
                    //Desbloqueamos el boton
                    $(".CompP" + Fecha).prop('readonly', false);
                } else {
                    //Bloqueamos el boton
                    $(".CompP" + Fecha).prop('readonly', true);

                    //Agregamos un 0 a los input
                    $(".CompP" + Fecha).val(0);
                }
            });

            //Diesel
            $(".CompraD").keyup(function() {
                //Fecha seleccionada
                var Fecha = $("#FechaSelect").val();

                //Obtenemos el valor del input
                var ValCompraD = $(this).val();

                //Condicional para desbloquear los input si el valor es mayor que cero
                if (ValCompraD > 0) {
                    //Desbloqueamos el boton
                    $(".CompD" + Fecha).prop('readonly', false);
                } else {
                    //Bloqueamos el boton
                    $(".CompD" + Fecha).prop('readonly', true);

                    //Agregamos un 0 a los input
                    $(".CompD" + Fecha).val(0);
                }
            });
            //**********************************************************************************************************************

            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            //Cambiamos de tablas
            //Original
            $(".btnoricircle").click(function() {
                //Marcamos el boton
                $(".btnoricircle").css('background', '#397ac4');
                $(".btnoricircle").css('color', '#ffffff');

                //Desmarcamos el boton de nuevo precio
                $(".btncambipreccircle").css('background', '#ffffff');
                $(".btncambipreccircle").css('color', '#397ac4');

                //Mostramos la tabla
                $(".Tablacambipreci").fadeOut("slow");
                $(".Tablacaptu").fadeIn("slow");
            });

            //Cambio de precio
            $(".btncambipreccircle").click(function() {
                //Marcamos el boton
                $(".btncambipreccircle").css('background', '#397ac4');
                $(".btncambipreccircle").css('color', '#ffffff');

                //Desmarcamos el boton de nuevo precio
                $(".btnoricircle").css('background', '#ffffff');
                $(".btnoricircle").css('color', '#397ac4');

                //Mostramos la tabla
                $(".Tablacaptu").fadeOut("slow");
                $(".Tablacambipreci").fadeIn("slow");
            });
        });
    </script>
</div>
