<div>
    {{-- Libreria de exportacion --}}
    <script src="{{ asset('js/tableExport/libs/FileSaver/FileSaver.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/tableExport.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/jsPDF/jspdf.umd.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/pdfmake.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/vfs_fonts.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/js-xlsx/xlsx.core.min.js') }}" defer></script>

    {{-- Implementacion del icono CRE --}}
    <link href="{{ asset('css/cre.css') }}" rel="stylesheet">

    {{-- JS --}}
    <script src="{{ asset('js/volumetrico.js') }}" defer></script>


    @php
        //Llamamos los modelos
        use App\Models\User;
        
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
        
        $nomsucur = '';
    @endphp

    {{-- Modal de hisatorico --}}
    {{-- Creacion del modal (BASE) --}}
    <div wire:ignore.self class="modal fade" id="voluhistorymodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-history">Histórico {{ $empresa }}</span></h6>
                    <button type="button" class="closerevi close" wire:click="Refresh()" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    <form wire:submit.prevent="ConsulHistoric">
                        {{-- Filtros de busqueda --}}
                        <div class="form-inline mr-auto">
                            <input class="form-control" id="fecha" wire:model.defer="fechainic" type="date"
                                min="2014-01-01" max={{ date('Y-m-d') }} required> &nbsp;&nbsp;

                            A &nbsp;&nbsp;

                            <input class="form-control" id="fecha" wire:model.defer="fechafin" type="date"
                                min="2014-01-01" max={{ date('Y-m-d') }} required> &nbsp;&nbsp;

                            <button class="btn btn-secondary BtnVinculadas" type="submit"
                                wire:loading.attr="disabled">Buscar</button>
                            &nbsp;&nbsp;

                            <button {{ $active }} type="button" class="btn btn-success BtnVinculadas"
                                onclick="exportReportToExcel('{{ $empresa }}')">Excel</button>
                            &nbsp;&nbsp;
                            <button {{ $active }} type="button" class="btn btn-danger BtnVinculadas"
                                onclick="exportReportToPdf('{{ $empresa }}')">Pdf</button>
                            &nbsp;&nbsp;
                        </div>
                    </form>

                    <br>

                    {{-- Tablas por cada tipo combustible que maneja el usuario --}}
                    {{-- MAGNA --}}
                    @if ($Magna == '1')
                        {{-- Tabla --}}
                        <div class="table-responsive">
                            <table id="voluhistorimagna" class="voluhistori {{ $class }}" style="width:100%">
                                <thead>
                                    @if ($infogas['PrecCompDesc'] == 1)
                                        <tr>
                                            <th style="background-color:rgb(200, 245, 222)" colspan="10"
                                                class="text-center align-middle">MAGNA</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th style="background-color:rgb(200, 245, 222)" colspan="9"
                                                class="text-center align-middle">MAGNA</th>
                                        </tr>
                                    @endif

                                    {{-- Columnas --}}
                                    <tr>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Fecha</th>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Inventario inicial</th>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Compras</th>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Precio de compra</th>
                                        @if ($infogas['PrecCompDesc'] == 1)
                                            <th style="background-color:rgb(200, 245, 222)"
                                                class="text-center align-middle">Precio de compra (Con descuento)</th>
                                        @endif
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Litros vendidos</th>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Precio venta</th>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">AutoStick</th>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Inventario determinado</th>
                                        <th style="background-color:rgb(200, 245, 222)"
                                            class="text-center align-middle">Merma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($historicomagna))
                                        {{-- Datos del historico --}}
                                        @foreach ($historicomagna as $historicomagna)
                                            {!! $historicomagna !!}
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <br>
                    @endif

                    {{-- PREMIUM --}}
                    @if ($Premium == '1')
                        {{-- Tabla --}}
                        <div class="table-responsive">
                            <table id="voluhistoripremium" class="voluhistori {{ $class }}" style="width:100%">
                                <thead>
                                    {{-- Encabezado/Titulo --}}
                                    @if ($infogas['PrecCompDesc'] == 1)
                                        <tr>
                                            <th style="background-color:rgb(255, 209, 209)" colspan="10"
                                                class="text-center align-middle">PREMIUM</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th style="background-color:rgb(255, 209, 209)" colspan="9"
                                                class="text-center align-middle">PREMIUM</th>
                                        </tr>
                                    @endif

                                    {{-- Columnas --}}
                                    <tr>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Fecha</th>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Inventario inicial</th>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Compras</th>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Precio de compra</th>
                                        @if ($infogas['PrecCompDesc'] == 1)
                                            <th style="background-color:rgb(255, 209, 209)"
                                                class="text-center align-middle">Precio de compra (Con descuento)</th>
                                        @endif
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Litros vendidos</th>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Precio venta</th>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">AutoStick</th>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Inventario determinado</th>
                                        <th style="background-color:rgb(255, 209, 209)"
                                            class="text-center align-middle">Merma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($historicopremium))
                                        {{-- Datos del historico --}}
                                        @foreach ($historicopremium as $historicopremium)
                                            {!! $historicopremium !!}
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <br>
                    @endif

                    {{-- DIESEL --}}
                    @if ($Diesel == '1')
                        {{-- Tabla --}}
                        <div class="table-responsive">
                            <table id="voluhistoridiesel" class="voluhistori {{ $class }}" style="width:100%">
                                <thead>
                                    @if ($infogas['PrecCompDesc'] == 1)
                                        <tr>
                                            <th style="background-color:rgb(205, 205, 205)" colspan="10"
                                                class="text-center align-middle">DIESEL</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th style="background-color:rgb(205, 205, 205)" colspan="9"
                                                class="text-center align-middle">DIESEL</th>
                                        </tr>
                                    @endif

                                    {{-- Columnas --}}
                                    <tr>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Fecha</th>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Inventario inicial</th>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Compras</th>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Precio de compra</th>
                                        @if ($infogas['PrecCompDesc'] == 1)
                                            <th style="background-color:rgb(205, 205, 205)"
                                                class="text-center align-middle">Precio de compra (Con descuento)</th>
                                        @endif
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Litros vendidos</th>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Precio venta</th>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">AutoStick</th>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Inventario determinado</th>
                                        <th style="background-color:rgb(205, 205, 205)"
                                            class="text-center align-middle">Merma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($historicodiesel))
                                        {{-- Datos del historico --}}
                                        @foreach ($historicodiesel as $historicodiesel)
                                            {!! $historicodiesel !!}
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal revisado --}}
    {{-- Creacion del modal (BASE) --}}
    <div wire:ignore.self class="modal fade" id="revisapdf" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-check-circle">Revisado PDF</span></h6>
                    <button type="button" class="closerevi close" wire:click="Refresh()" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    <h5>Esta opción marca como revisado los PDF subidos del mes y bloquea la opción de eliminar el
                        archivo PDF
                    </h5>

                    <br>

                    <div align="center">
                        <h5>¿Está seguro de marcar como revisado?</h5>

                        <br>

                        <button wire:click="ReviPDF()" class="btn btn-success BtnVinculadas">Marcar como
                            revisado</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Contenedor para mantener responsivo el contenido del modulo --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    {{-- Select para selccionar la empresa (Contadores) --}}
                    @empty(!$empresas)
                        {{-- Mostramos el RFC de la empresa que se selecciona --}}
                        <label for="inputState">Empresa: {{ $empresa }}</label>
                        <select wire:model="rfcEmpresa" wire:change="Refresh()" id="inputState1"
                            class="select form-control">
                            <option value="">--Selecciona Empresa--</option>

                            {{-- Llenamos el select con las empresa vinculadas --}}
                            <?php $rfc = 0;
                            $rS = 1;
                            foreach ($empresas as $fila) {
                                echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                            } ?>
                        </select>

                        <br>
                    @endempty

                    {{-- Select para selccionar la empresa (Contadores) --}}
                    @if (!empty($infogas['Sucursales']))
                        {{-- Mostramos el RFC de la empresa que se selecciona --}}
                        <label for="inputState">Sucursal</label>
                        <select wire:model="sucursal" wire:change="Refresh()" class="select form-control">
                            <option value="">--Selecciona Sucursal--</option>

                            {{-- Mostramos las sucursales --}}
                            @foreach ($infogas['Sucursales'] as $datagas)
                                <option value="{{ $datagas['RFC'] }}">{{ $datagas['Nombre'] }}</option>
                            @endforeach
                        </select>
                    @endif

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

                    {{-- Seccion del calendario --}}
                    {{-- Fecha de hoy --}}
                    <div id="contfechahoy" align="center">
                        @php
                            //Swich para convertir Int mes en String
                            switch ($mescal) {
                                case 1:
                                    $mescalstr = 'Enero ';
                                    break;
                                case 2:
                                    $mescalstr = 'Febrero ';
                                    break;
                                case 3:
                                    $mescalstr = 'Marzo ';
                                    break;
                                case 4:
                                    $mescalstr = 'Abril ';
                                    break;
                                case 5:
                                    $mescalstr = 'Mayo ';
                                    break;
                                case 6:
                                    $mescalstr = 'Junio ';
                                    break;
                                case 7:
                                    $mescalstr = 'Julio ';
                                    break;
                                case 8:
                                    $mescalstr = 'Agosto ';
                                    break;
                                case 9:
                                    $mescalstr = 'Septiembre ';
                                    break;
                                case 10:
                                    $mescalstr = 'Octubre ';
                                    break;
                                case 11:
                                    $mescalstr = 'Noviembre ';
                                    break;
                                case 12:
                                    $mescalstr = 'Diciembre ';
                                    break;
                            }
                        @endphp

                        <h3>{{ $mescalstr }} {{ $aniocal }}</h3>
                    </div>

                    <br>

                    {{-- Calendario --}}
                    <div class="form-inline mr-auto">
                        {{-- Filtros de busqueda --}}

                        {{-- Busqueda por mes --}}
                        <label for="inputState">Mes</label>
                        <select wire:model="mescal" id="inputState1" wire:loading.attr="disabled"
                            class="select form-control">
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;

                        {{-- Busqueda por año --}}
                        <label for="inputState">Año</label>
                        <select wire:loading.attr="disabled" wire:model="aniocal" id="inputState2"
                            class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;

                        {{-- Revisado --}}
                        @if (auth()->user()->tipo && !empty($empresa))
                            {{-- Historico --}}
                            <button class="btn btn-secondary BtnVinculadas" data-toggle="modal"
                                data-target="#voluhistorymodal" data-backdrop="static"
                                data-keyboard="false">Histórico</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;

                            <button class="btn btn-success BtnVinculadas" data-toggle="modal" data-target="#revisapdf"
                                data-backdrop="static" data-keyboard="false">Marcar revisado PDF</button>
                        @endif
                    </div>

                    <br>

                    {{-- Formato calendario --}}
                    <div class="table-responsive" wire:loading.class="disabledattr">
                        <table id="Tablavolu" class="table table-bordered calemitreci">
                            <thead>
                                <tr>
                                    <th>Domingo</th>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miercoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                    <th>Sabado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($weeks as $week)
                                    {{-- Realizar un echo de codigo HTML --}}
                                    {!! $week !!}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Input invicible que captura el valor de la fecha --}}
                    <input id="FechaSelect" type="hidden">
                </section>
            </div>
        </div>
    </div>
    {{-- Obtenemos el total de dias --}}
    @php
        $totaldias = $aniocal . '-' . $mescal . '-01'; //Asignamos la fecha inicial del mes y año seleccionado
        $totaldias = date('t', strtotime($totaldias)); //Obtenemos el total de dias de la fecha seleccionada
    @endphp

    {{-- Ciclo para crear las fechas --}}
    @for ($dias = 1; $dias <= $totaldias; $dias++)
        @php
            $fecha = date('Y-m-d', strtotime($aniocal . '-' . $mescal . '-' . $dias)); //Creacion de la fecha con el formato
        @endphp

        {{-- Condicional para saber si se selecciono una sucursal --}}
        @if (!empty($sucursales))
            @php
                $consulsucur = User::where('RFC', $empresa)->first();
                foreach ($consulsucur['Sucursales'] as $infosucursales) {
                    if ($infosucursales['RFC'] == $sucursales) {
                        $nomsucur = $infosucursales['Nombre'];
                    }
                }
            @endphp

            {{-- Llamamos al componente del modal junto con los datos necesarios --}}
            <livewire:volumedata :empresa=$sucursales :dia=$fecha :wire:key="'user-profile-one-'.$sucursales.$fecha">
                <livewire:volumepdf :empresa=$empresa :sucursales=$sucursales :nomsucur=$nomsucur :dia=$fecha
                    :wire:key="'user-profile-two-'.$sucursales.$fecha">
                    <livewire:volumecre :empresa=$empresa :sucursales=$sucursales :nomsucur=$nomsucur :dia=$fecha
                        :wire:key="'user-profile-three-'.$sucursales.$fecha">
                    @else
                        {{-- Llamamos al componente del modal junto con los datos necesarios --}}
                        <livewire:volumedata :empresa=$empresa :dia=$fecha
                            :wire:key="'user-profile-one-'.$empresa.$fecha">
                            <livewire:volumepdf :empresa=$empresa :dia=$fecha
                                :wire:key="'user-profile-two-'.$empresa.$fecha">
                                <livewire:volumecre :empresa=$empresa :dia=$fecha
                                    :wire:key="'user-profile-three-'.$empresa.$fecha">
        @endif
    @endfor

    {{-- Js --}}
    <script>
        $(document).ready(function() {
            //Hacemos click al boton de cerrar del modal
            window.addEventListener('CerrarVoluRevi', event => {
                $(".closerevi").click();
            });


            //Funcion para obtener la fecha seleccionada
            $(".selectfecha").click(function() {
                //Guardamos en una variable el atributo que contiene la fecha
                var fechaselect = $(this).attr("fecha");

                //Almacenamos la fecha seleccionada en un input
                $("#FechaSelect").val(fechaselect);

                //Variable que alamcenara el rfc de la sucursal
                var RFCSucur = '{{ $sucursales }}';

                //Condicional para saber si elegimos una sucursal o una empresa
                if (RFCSucur.length < 1) {
                    //Creamos el ID y la ruta ID
                    var rutaid = "volupdf" + fechaselect + "&{{ $empresa }}";
                    var id = fechaselect + "&{{ $empresa }}";
                } else {
                    //Creamos el ID y la ruta ID
                    var rutaid = "volupdf" + fechaselect + "&{{ $empresa }}" +
                        "&{{ $sucursales }}" + "&{{ $nomsucur }}";
                    var id = fechaselect + "&{{ $empresa }}" + "&{{ $sucursales }}" +
                        "&{{ $nomsucur }}";
                }

                //Llamamos a la funcion de FilePond
                FilePondPDFVolu(rutaid, id);
            });

            $(".selectfechacre").click(function() {
                //Guardamos en una variable el atributo que contiene la fecha
                var fechaselect = $(this).attr("fecha");

                //Almacenamos la fecha seleccionada en un input
                $("#FechaSelect").val(fechaselect);

                //Variable que alamcenara el rfc de la sucursal
                var RFCSucur = '{{ $sucursales }}';

                //Condicional para saber si elegimos una sucursal o una empresa
                if (RFCSucur.length < 1) {
                    //Creamos el ID y la ruta ID
                    var rutaidcre = "volupdfcre" + fechaselect + "&{{ $empresa }}";
                    var idcre = fechaselect + "&{{ $empresa }}";
                } else {
                    //Creamos el ID y la ruta ID
                    var rutaidcre = "volupdfcre" + fechaselect + "&{{ $empresa }}" +
                        "&{{ $sucursales }}" + "&{{ $nomsucur }}";
                    var idcre = fechaselect + "&{{ $empresa }}" + "&{{ $sucursales }}" +
                        "&{{ $nomsucur }}";
                }

                //Llamamos a la funcion de FilePond
                FilePondPDFCRE(rutaidcre, idcre);
            });
        });
    </script>
</div>
