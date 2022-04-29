<div>
    {{-- Obtenemos informacion de la gasolinera --}}
    @php
        use App\Models\User;
        
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
    @endphp

    {{-- Modal de la captura de precio --}}
    {{-- Creacion del modal (BASE) --}}
    <div wire:ignore.self class="modal fade" id="volucaptumodal{{ $dia }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-gas-pump">Capturar volumetrico</span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{ $empresa }}
                    {{ $dia }}

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

                        {{-- Tabla de captura --}}
                        <div class="Tablacamptu">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <br>

                            {{-- Boton para gusrdar los datos --}}
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-secondary">Capturar</button>
                        </div>

                        {{-- Cambio de precio --}}
                        <div class="Tablacambipreci">
                            <label>*Llenar en caso de cambio de precio</label>

                            <br>

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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
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
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Premium == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif

                                        @if ($Diesel == '1')
                                            <div class="table-body-cell">
                                                <input class="form-control">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <br>

                            {{-- Boton para gusrdar los datos --}}
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-secondary">Capturar</button>
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
            //Iniciando marcando el boton original
            $(".btnoricircle").css('background', '#397ac4');
            $(".btnoricircle").css('color', '#ffffff');

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
                $(".Tablacamptu").fadeIn("slow");
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
                $(".Tablacamptu").fadeOut("slow");
                $(".Tablacambipreci").fadeIn("slow");
            });
        });
    </script>
</div>
