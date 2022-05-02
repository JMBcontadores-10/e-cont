<div>
    {{-- Obtenemos informacion de la gasolinera --}}
    @php
        use App\Models\User;
        use App\Models\Volumetrico;
        
        $infogas = User::where('RFC', $empresa)->get();
        
        //Obtenemos los datos requeridos
        if (count($infogas) > 0) {
            //Recorremos la consulta para obtener los datos
            foreach ($infogas as $datagas) {
                //Obtenemos los tipo de combustible que maneja las gasolineras
                $Magna = $datagas->TipoM;
                $Premium = $datagas->TipoP;
                $Diesel = $datagas->TipoD;
            }
        } else {
            //De lo contrario los declaramos vacios
            $Magna = '';
            $Premium = '';
            $Diesel = '';
        }
        
        //Consultamos lo datos de los volumetricos
        $datavolum = Volumetrico::where(['rfc' => $empresa])
            ->get()
            ->first();
        
        //Obtenemos el dia anterior
        $diaanterior = date('Y-m-d', strtotime($dia . '- 1 days'));
        
        if ($datavolum) {
            //Obtenemos el inventario final volumetrico anterior
            $inventantefinm = $datavolum['volumetrico.' . $diaanterior . '.AutoStickM'];
            $inventantefinp = $datavolum['volumetrico.' . $diaanterior . '.AutoStickP'];
            $inventantefind = $datavolum['volumetrico.' . $diaanterior . '.AutoStickD'];
        }
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
                            class="icons fas fa-gas-pump">Capturar volumetrico {{ $dia }}</span></h6>
                    <button type="button" class="close" wire:click="Refresh()" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Mensaje de que no hay un inventiario final --}}
                    @if (empty($inventantefinm) || empty($inventantefinp) || empty($inventantefind))
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
                                                        id="inventinicmagna{{ $dia }}"
                                                        wire:model.defer="inventinicmagna"
                                                        class="form-control Magna{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventinicpremium{{ $dia }}"
                                                        wire:model.defer="inventinicpremium"
                                                        class="form-control Premium{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventinicdiesel{{ $dia }}"
                                                        wire:model.defer="inventinicdiesel"
                                                        class="form-control Diesel{{ $dia }}" required>
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
                                                        id="compramagna{{ $dia }}"
                                                        wire:model.defer="compramagna"
                                                        class="form-control Magna{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="comprapremium{{ $dia }}"
                                                        wire:model.defer="comprapremium"
                                                        class="form-control Premium{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="compradiesel{{ $dia }}"
                                                        wire:model.defer="compradiesel"
                                                        class="form-control Diesel{{ $dia }}" required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- LITROS VENDIDOS --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Litros vendidos</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendmagna{{ $dia }}"
                                                        wire:model.defer="litvendmagna"
                                                        class="form-control Magna{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendpremium{{ $dia }}"
                                                        wire:model.defer="litvendpremium"
                                                        class="form-control Premium{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvenddiesel{{ $dia }}"
                                                        wire:model.defer="litvenddiesel"
                                                        class="form-control Diesel{{ $dia }}" required>
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
                                                    <input type="number" step="0.01" wire:model.defer="precventmagna"
                                                        class="form-control" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" wire:model.defer="precventpremium"
                                                        class="form-control" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" wire:model.defer="precventdiesel"
                                                        class="form-control" required>
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
                                                    <input type="number" step="0.01" wire:model.defer="autostickmagna"
                                                        class="form-control" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" wire:model.defer="autostickpremium"
                                                        class="form-control" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01" wire:model.defer="autostickdiesel"
                                                        class="form-control" required>
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
                                                    <input id="invdetermagna{{ $dia }}"
                                                        wire:model.defer="invdetermagna" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdeterpremium{{ $dia }}"
                                                        wire:model.defer="invdeterpremium" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdeterdiesel{{ $dia }}"
                                                        wire:model.defer="invdeterdiesel" class="form-control"
                                                        readonly>
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
                                                        id="inventiniccambmagna{{ $dia }}"
                                                        wire:model.defer="inventiniccambmagna"
                                                        class="form-control MagnaCamb{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventiniccambpremium{{ $dia }}"
                                                        wire:model.defer="inventiniccambpremium"
                                                        class="form-control PremiumCamb{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="inventiniccambdiesel{{ $dia }}"
                                                        wire:model.defer="inventiniccambdiesel"
                                                        class="form-control DieselCamb{{ $dia }}" required>
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
                                                        id="compracambmagna{{ $dia }}"
                                                        wire:model.defer="compracambmagna"
                                                        class="form-control MagnaCamb{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="compracambpremium{{ $dia }}"
                                                        wire:model.defer="compracambpremium"
                                                        class="form-control PremiumCamb{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="compracambdiesel{{ $dia }}"
                                                        wire:model.defer="compracambdiesel"
                                                        class="form-control DieselCamb{{ $dia }}" required>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- LITROS VENDIDOS --}}
                                        <div class="resp-table-row">
                                            <div class="table-body-cell">
                                                <label>Litros vendidos</label>
                                            </div>

                                            @if ($Magna == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendcambmagna{{ $dia }}"
                                                        wire:model.defer="litvendcambmagna"
                                                        class="form-control MagnaCamb{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendcambpremium{{ $dia }}"
                                                        wire:model.defer="litvendcambpremium"
                                                        class="form-control PremiumCamb{{ $dia }}" required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        id="litvendcambdiesel{{ $dia }}"
                                                        wire:model.defer="litvendcambdiesel"
                                                        class="form-control DieselCamb{{ $dia }}" required>
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
                                                    <input type="number" step="0.01"
                                                        wire:model.defer="precventcambmagna" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        wire:model.defer="precventcambpremium" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
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
                                                    <input type="number" step="0.01"
                                                        wire:model.defer="autostickcambmagna" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        wire:model.defer="autostickcambpremium" class="form-control"
                                                        required>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input type="number" step="0.01"
                                                        wire:model.defer="autostickcambdiesel" class="form-control"
                                                        required>
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
                                                    <input id="invdetercambmagna{{ $dia }}"
                                                        wire:model.defer="invdetercambmagna" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Premium == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdetercambpremium{{ $dia }}"
                                                        wire:model.defer="invdetercambpremium" class="form-control"
                                                        readonly>
                                                </div>
                                            @endif

                                            @if ($Diesel == '1')
                                                <div class="table-body-cell">
                                                    <input id="invdetercambdiesel{{ $dia }}"
                                                        wire:model.defer="invdetercambdiesel" class="form-control"
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
        </div>
    </div>

    {{-- Js --}}
    <script>
        $(document).ready(function() {
            //Cerramos el modal
            window.addEventListener('SuccessVolum', event => {
                $(".close").click();
            });

            //Dehabilitamos el cambio de cantidad utilizando scroll
            $(document).on("wheel", "input[type=number]", function(e) {
                $(this).blur();
            });

            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            //Calculos para inventario determinado
            //Volumetrico

            //Magna
            $(".Magna{{ $dia }}").keyup(function() {
                //Variables
                var InvInicM = 0;
                var ComprasM = 0;
                var LinVendM = 0;
                var InvDeterM = 0;

                //Inventario inicial
                InvInicM = $("#inventinicmagna{{ $dia }}").val();

                //Compras
                ComprasM = $("#compramagna{{ $dia }}").val();

                //Litros vendidos
                LinVendM = $("#litvendmagna{{ $dia }}").val();

                //Sacamos el inventario determinado
                InvDeterM = (parseFloat(InvInicM) + parseFloat(ComprasM)) - parseFloat(LinVendM);
                $("#invdetermagna{{ $dia }}").val(InvDeterM.toFixed(2));
            });

            //Premium
            $(".Premium{{ $dia }}").keyup(function() {
                //Variables
                var InvInicP = 0;
                var ComprasP = 0;
                var LinVendP = 0;
                var InvDeterP = 0;

                //Inventario inicial
                InvInicP = $("#inventinicpremium{{ $dia }}").val();

                //Compras
                ComprasP = $("#comprapremium{{ $dia }}").val();

                //Litros vendidos
                LinVendP = $("#litvendpremium{{ $dia }}").val();

                //Sacamos el inventario determinado
                InvDeterP = (parseFloat(InvInicP) + parseFloat(ComprasP)) - parseFloat(LinVendP);
                $("#invdeterpremium{{ $dia }}").val(InvDeterP.toFixed(2));
            });

            //Diesel
            $(".Diesel{{ $dia }}").keyup(function() {
                //Variables
                var InvInicD = 0;
                var ComprasD = 0;
                var LinVendD = 0;
                var InvDeterD = 0;

                //Inventario inicial
                InvInicD = $("#inventinicdiesel{{ $dia }}").val();

                //Compras
                ComprasD = $("#compradiesel{{ $dia }}").val();

                //Litros vendidos
                LinVendD = $("#litvenddiesel{{ $dia }}").val();

                //Sacamos el inventario determinado
                InvDeterD = (parseFloat(InvInicD) + parseFloat(ComprasD)) - parseFloat(LinVendD);
                $("#invdeterdiesel{{ $dia }}").val(InvDeterD.toFixed(2));
            });

            //Accion para el boton de envio de datos
            $(".btnsavevolu").click(function() {
                //Variable de contador
                var InputLlenos = 0;

                //Vamos a obtener el valor total de input del formulario
                var TotalInput = $(".FormCaptu{{ $dia }} input[type=number]").length;

                //Ciclo para acceder a todos los valores de los input
                $(".FormCaptu{{ $dia }} input[type=number]").each(function() {
                    //Conidcional para saber si el input esta lleno
                    if ($(this).val() !== "") {
                        InputLlenos++; //Sumamos uno al contador
                    }
                });

                //Corroboramos que el total de inputs y el total de llenos sea el mismo
                if (InputLlenos == TotalInput) {
                    //Almacenamos los valores determinados antes de enviarlos
                    @this.set('invdetermagna', String($("#invdetermagna{{ $dia }}").val()));
                    @this.set('invdeterpremium', String($("#invdeterpremium{{ $dia }}").val()));
                    @this.set('invdeterdiesel', String($("#invdeterdiesel{{ $dia }}").val()));
                }
            });

            //**********************************************************************************************************************
            //Calculos para inventario determinado
            //Cambio de precio

            //Magna
            $(".MagnaCamb{{ $dia }}").keyup(function() {
                //Variables
                var InvInicCambM = 0;
                var ComprasCambM = 0;
                var LinVendCambM = 0;
                var InvDeterCambM = 0;

                //Inventario inicial
                InvInicCambM = $("#inventiniccambmagna{{ $dia }}").val();

                //Compras
                ComprasCambM = $("#compracambmagna{{ $dia }}").val();

                //Litros vendidos
                LinVendCambM = $("#litvendcambmagna{{ $dia }}").val();

                //Sacamos el inventario determinado
                InvDeterCambM = (parseFloat(InvInicCambM) + parseFloat(ComprasCambM)) - parseFloat(
                    LinVendCambM);
                $("#invdetercambmagna{{ $dia }}").val(InvDeterCambM.toFixed(2));
            });

            //Premium
            $(".PremiumCamb{{ $dia }}").keyup(function() {
                //Variables
                var InvInicCambP = 0;
                var ComprasCambP = 0;
                var LinVendCambP = 0;
                var InvDeterCambP = 0;

                //Inventario inicial
                InvInicCambP = $("#inventiniccambpremium{{ $dia }}").val();

                //Compras
                ComprasCambP = $("#compracambpremium{{ $dia }}").val();

                //Litros vendidos
                LinVendCambP = $("#litvendcambpremium{{ $dia }}").val();

                //Sacamos el inventario determinado
                InvDeterCambP = (parseFloat(InvInicCambP) + parseFloat(ComprasCambP)) - parseFloat(
                    LinVendCambP);
                $("#invdetercambpremium{{ $dia }}").val(InvDeterCambP.toFixed(2));
            });

            //Diesel
            $(".DieselCamb{{ $dia }}").keyup(function() {
                //Variables
                var InvInicCambD = 0;
                var ComprasCambD = 0;
                var LinVendCambD = 0;
                var InvDeterCambD = 0;

                //Inventario inicial
                InvInicCambD = $("#inventiniccambdiesel{{ $dia }}").val();

                //Compras
                ComprasCambD = $("#compracambdiesel{{ $dia }}").val();

                //Litros vendidos
                LinVendCambD = $("#litvendcambdiesel{{ $dia }}").val();

                //Sacamos el inventario determinado
                InvDeterCambD = (parseFloat(InvInicCambD) + parseFloat(ComprasCambD)) - parseFloat(
                    LinVendCambD);
                $("#invdetercambdiesel{{ $dia }}").val(InvDeterCambD.toFixed(2));
            });

            $(".btncambioprec").click(function() {
                //Variable de contador
                var InputLlenos = 0;

                //Vamos a obtener el valor total de input del formulario
                var TotalInput = $(".CambiPreci{{ $dia }} input[type=number]").length;

                //Ciclo para acceder a todos los valores de los input
                $(".CambiPreci{{ $dia }} input[type=number]").each(function() {
                    //Conidcional para saber si el input esta lleno
                    if ($(this).val() !== "") {
                        InputLlenos++;//Sumamos uno al contador
                    }
                });

                //Corroboramos que el total de inputs y el total de llenos sea el mismo
                if (InputLlenos == TotalInput) {
                    //Almacenamos los valores determinados antes de enviarlos
                    @this.set('invdetercambmagna', String($("#invdetercambmagna{{ $dia }}")
                        .val()));
                    @this.set('invdetercambpremium', String($("#invdetercambpremium{{ $dia }}")
                        .val()));
                    @this.set('invdetercambdiesel', String($("#invdetercambdiesel{{ $dia }}")
                        .val()));
                }
            });

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
