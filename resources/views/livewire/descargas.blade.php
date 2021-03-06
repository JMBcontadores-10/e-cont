<div>
    @php
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
    @endphp

    {{-- Contenedor para mantener responsivo el contenido del modulo --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    {{-- Aqui va el contenido del modulo --}}
                    {{-- Select para selccionar la empresa (Contadores) --}}
                    @empty(!$empresas)
                        {{-- Mostramos el RFC de la empresa que se selecciona --}}
                        <label for="inputState">Empresa: {{ $empresa }}</label>
                        <select wire:model="rfcEmpresa" id="inputState1" class="select form-control" wire:change="ObtAuth()">
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

                    <br>

                    {{-- Seccion de descargas --}}
                    {{-- Descargas --}}
                    <h1 style="font-weight: bold">Descargas</h1>

                    {{-- Select/mostrar calendario --}}
                    <div class="row">
                        {{-- Select para seleccionar el tipo de CFDI --}}
                        <div class="col-4">
                            <label for="selecttipocfdi">Tipo:</label>
                            <select wire:model="tipo" name="selecttipocfdi" id="selecttipocfdi"
                                wire:change="ResetParamColsul()" wire:loading.attr="disabled"
                                class="select form-control">
                                <option value="Recibidos">Recibidos</option>
                                <option value="Emitidos">Emitidos</option>
                            </select>
                        </div>

                        {{-- Calendario de registros --}}
                        <div class="col-4">
                            <div id="espaciado" style="height: 23.5px"></div>
                            <div class="invoice-create-btn mb-1">
                                <button id="Btncalendario" class="btn btn-primary" data-toggle="modal"
                                    data-target="#calendariomodal" data-backdrop="static" data-keyboard="false"
                                    wire:loading.attr="disabled">Calendario
                                    de registros</button>
                            </div>
                        </div>

                        {{-- Boton de descarga a los CFDI seleccionados --}}
                        <div class="col">
                            <div id="espaciado" style="height: 23.5px"></div>
                            <div class="invoice-create-btn mb-1">
                                <button id="Btndescarcfdi" class="btn btn-success BtnVinculadas"
                                    wire:loading.attr="disabled">Descargar
                                    seleccionados</button>
                            </div>
                        </div>
                    </div>

                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                    <br>

                    {{-- Mensaje de alerta cuando no se selecciona CFDI --}}
                    <div id="Mnssincfdi" hidden>
                        <div id="mnsexcep" class="alert alert-danger">
                        </div>
                    </div>

                    {{-- Mensaje de informacion de descarga --}}
                    <div id="Mnsdeccompl" hidden>
                        <div id="mnssuccess" class="alert alert-primary">
                            <b>
                                <div id="meninic"></div>
                            </b>
                            <div id="mendesc"></div>
                            <div id="menerror"></div>
                        </div>
                    </div>

                    {{-- Muestra la cantidad de cfdi cosultados --}}
                    <label>Total de registros obtenidos: {{ $totallist }}</label>

                    <br>

                    {{-- Condicional para mostrar los filtros de cada tipo --}}
                    @if ($tipo == 'Recibidos')
                        {{-- Recibidos --}}
                        <label>Selecciona una fecha:</label>

                        {{-- Filtros de busqueda --}}
                        <div class="form-inline mr-auto">
                            {{-- Busqueda por dia --}}
                            <label for="diareci">Dia</label>
                            <select wire:model.defer="diareci" id="diareci"
                                class="select form-control filtbusc busqreci" wire:loading.attr="disabled">
                                <option value="all">Todos</option>
                                @php
                                    for ($i = 1; $i <= 31; $i++) {
                                        if ($i < 10) {
                                            echo '<option value="0' . $i . '">' . $i . '</option>';
                                        } else {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    }
                                @endphp
                            </select>
                            &nbsp;&nbsp;

                            {{-- Busqueda por mes --}}
                            <label for="mesreci">Mes</label>
                            <select wire:model.defer="mesreci" id="mesreci"
                                class="select form-control filtbusc busqreci" wire:loading.attr="disabled">
                                <?php foreach ($meses as $key => $value) {
                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                } ?>
                            </select>
                            &nbsp;&nbsp;


                            {{-- Busqueda por a??o --}}
                            <label for="anioreci">A??o</label>
                            <select wire:model.defer="anioreci" id="anioreci"
                                class="select form-control filtbusc busqreci" wire:loading.attr="disabled">
                                <?php foreach (array_reverse($anios) as $value) {
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                } ?>
                            </select>
                            &nbsp;&nbsp;

                            <button class="btn btn-secondary BtnVinculadas BtnConsulSAT" wire:loading.attr="disabled"
                                wire:click="ConsultSAT()">Buscar</button>
                            &nbsp;&nbsp;
                        </div>

                        <br>

                        {{-- Tabla de recibidos --}}
                        <div class="table-responsive">
                            <table id="example" class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">XML <input id="allxml" type="checkbox"
                                                wire:loading.attr="disabled" /></th>
                                        <th class="text-center align-middle">R. Imp. <input id="allpdf"
                                                type="checkbox" wire:loading.attr="disabled" />
                                        </th>
                                        <th class="text-center align-middle">Acuse</th>
                                        <th class="text-center align-middle">Folio Fiscal</th>
                                        <th class="text-center align-middle">RFC</th>
                                        <th class="text-center align-middle">Raz??n Social</th>
                                        <th class="text-center align-middle">Emisi??n</th>
                                        <th class="text-center align-middle">Certificaci??n</th>
                                        <th class="text-center align-middle">Total</th>
                                        <th class="text-center align-middle">Efecto</th>
                                        <th class="text-center align-middle">Estado</th>
                                        <th class="text-center align-middle">Cancelaci??n</th>
                                        <th class="text-center align-middle">Aprobaci??n</th>
                                        <th class="text-center align-middle">Descargado XML</th>
                                        <th class="text-center align-middle">Descargado PDF</th>
                                        <th class="text-center align-middle">Descargado Acuse</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Condicional para saber si hay una lista que mostrar o no --}}
                                    @if (is_string($list))
                                        <tr>
                                            <td colspan="16">
                                                <span class="invoice-amount"> {{ $list }} </span>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($list as $listrecibi)
                                            @php
                                                //Obtenemos las rutas de los CFDI recibidos
                                                //Mes
                                                switch ($mesreci) {
                                                    case '1':
                                                        $mesruta = '1.Enero';
                                                        break;
                                                
                                                    case '2':
                                                        $mesruta = '2.Febrero';
                                                        break;
                                                
                                                    case '3':
                                                        $mesruta = '3.Marzo';
                                                        break;
                                                
                                                    case '4':
                                                        $mesruta = '4.Abril';
                                                        break;
                                                
                                                    case '5':
                                                        $mesruta = '5.Mayo';
                                                        break;
                                                
                                                    case '6':
                                                        $mesruta = '6.Junio';
                                                        break;
                                                
                                                    case '7':
                                                        $mesruta = '7.Julio';
                                                        break;
                                                
                                                    case '8':
                                                        $mesruta = '8.Agosto';
                                                        break;
                                                
                                                    case '9':
                                                        $mesruta = '9.Septiembre';
                                                        break;
                                                
                                                    case '10':
                                                        $mesruta = '10.Octubre';
                                                        break;
                                                
                                                    case '11':
                                                        $mesruta = '11.Noviembre';
                                                        break;
                                                
                                                    case '12':
                                                        $mesruta = '12.Diciembre';
                                                        break;
                                                }
                                                
                                                //XML
                                                $rutaxml = "storage/contarappv1_descargas/" . strtoupper($this->rfcEmpresa) . "/$anioreci/Descargas/$mesruta/Recibidos/XML/";
                                                //PDF
                                                $rutapdf = "storage/contarappv1_descargas/" . strtoupper($this->rfcEmpresa) . "/$anioreci/Descargas/$mesruta/Recibidos/PDF/";
                                                //Acuse
                                                $rutapdfacuse = "storage/contarappv1_descargas/" . strtoupper($this->rfcEmpresa) . "/$anioreci/Descargas/$mesruta/Recibidos/ACUSE/";
                                                
                                                //Buscamos si exsiten los archivos (si estn descargados)
                                                //XML
                                                $xmlfile = $rutaxml . strtoupper($listrecibi->uuid) . '.xml';
                                                if (file_exists($xmlfile)) {
                                                    $existxml = 'Si';
                                                } else {
                                                    $existxml = 'No';
                                                }
                                                
                                                //PDF
                                                $pdffile = $rutapdf . strtoupper($listrecibi->uuid) . '.pdf';
                                                if (file_exists($pdffile)) {
                                                    $existpdf = 'Si';
                                                } else {
                                                    $existpdf = 'No';
                                                }
                                                
                                                //Acuse
                                                $acusefile = $rutapdfacuse . strtoupper($listrecibi->uuid) . '-acuse' . '.pdf';
                                                if (file_exists($acusefile)) {
                                                    $existpdfacuse = 'Si';
                                                } else {
                                                    $existpdfacuse = 'No';
                                                }
                                            @endphp

                                            <tr>
                                                {{-- XML Checkbox --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <input value="{{ $listrecibi->uuid }}"
                                                            wire:loading.attr="disabled"
                                                            style="transform: scale(1.5);" name="chkxml"
                                                            class="mis-checkboxes ChkMasProv chkxml"
                                                            type="checkbox" />
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- R.Imp --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <input value="{{ $listrecibi->uuid }}"
                                                            wire:loading.attr="disabled"
                                                            style="transform: scale(1.5);" name="chkpdf"
                                                            class="mis-checkboxes ChkMasProv chkpdf"
                                                            type="checkbox" />
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- Acuse --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <input value="{{ $listrecibi->uuid }}"
                                                            wire:loading.attr="disabled"
                                                            style="transform: scale(1.5);" name="chkacuse"
                                                            class="mis-checkboxes ChkMasProv chkacuse"
                                                            type="checkbox" />
                                                    @endif
                                                </td>

                                                {{-- Folio fiscal --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ strtoupper($listrecibi->uuid) }}</span>
                                                </td>

                                                {{-- RFC Emisor --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount">{{ $listrecibi->rfcEmisor }}</span>
                                                </td>

                                                {{-- Razon social (nombre del emisor) --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listrecibi->nombreEmisor }}</span>
                                                </td>

                                                {{-- Fecha emision --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listrecibi->fechaEmision }}</span>
                                                </td>

                                                {{-- Fecha certificcion --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listrecibi->fechaCertificacion }}</span>
                                                </td>

                                                {{-- Total --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount">{{ $listrecibi->total }}</span>
                                                </td>

                                                {{-- Efecto --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listrecibi->efectoComprobante }}</span>
                                                </td>

                                                {{-- Estado --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listrecibi->estadoComprobante }}</span>
                                                </td>

                                                {{-- Fecha de cancelacion --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <span
                                                            class="invoice-amount">{{ $listrecibi->fechaProcesoCancelacion }}</span>
                                                    @endif
                                                </td>

                                                {{-- Aprobacion --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <img src="img/ima.png">
                                                    @else
                                                        <img src="img/ima2.png">
                                                    @endif
                                                </td>

                                                {{-- Desc XML --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount descxml">{{ $existxml }}</span>
                                                </td>

                                                {{-- Desc PDF --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount descpdf">{{ $existpdf }}</span>
                                                </td>

                                                {{-- Desc ACUSE --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <span
                                                            class="invoice-amount descacuse">{{ $existpdfacuse }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @elseif ($tipo == 'Emitidos')
                        {{-- Emitidos --}}
                        <label>Selecciona un rango de fecha:</label>

                        {{-- Rango de incio --}}

                        <div class="row">
                            <div class="col">
                                <label>Fecha inicial:</label>
                                {{-- Filtros de busqueda --}}
                                <div class="form-inline mr-auto">
                                    {{-- Busqueda por dia --}}
                                    <label for="diaemitinic">Dia</label>
                                    <select wire:model.defer="diaemitinic" id="diaemitinic"
                                        class="select form-control selectfilemit filtbusc busqemit"
                                        wire:loading.attr="disabled">
                                        @php
                                            for ($i = 1; $i <= 31; $i++) {
                                                if ($i < 10) {
                                                    echo '<option value="0' . $i . '">' . $i . '</option>';
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                        } @endphp </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por mes --}}
                                    <label for="mesemitinic">Mes</label>
                                    <select wire:model.defer="mesemitinic" id="mesemitinic"
                                        class=" select form-control selectfilemit filtbusc busqemit"
                                        wire:loading.attr="disabled">
                                        <?php foreach ($meses as $key => $value) {
                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;


                                    {{-- Busqueda por a??o --}}
                                    <label for="anioemitinic">A??o</label>
                                    <select wire:model.defer="anioemitinic" id="anioemitinic"
                                        class="select form-control selectfilemit filtbusc busqemit"
                                        wire:loading.attr="disabled">
                                        <?php foreach (array_reverse($anios) as $value) {
                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="col">
                                {{-- Rango de fin --}}

                                <label>Fecha final:</label>
                                {{-- Filtros de busqueda --}}
                                <div class="form-inline mr-auto">
                                    {{-- Busqueda por dia --}}
                                    <label for="diaemitfin">Dia</label>
                                    <select wire:model.defer="diaemitfin" id="diaemitfin"
                                        class="select form-control selectfilemit filtbusc busqemit"
                                        wire:loading.attr="disabled">
                                        @php
                                            for ($i = 1; $i <= 31; $i++) {
                                                if ($i < 10) {
                                                    echo '<option value="0' . $i . '">' . $i . '</option>';
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                        } @endphp </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por mes --}}
                                    <label for="mesemitfin">Mes</label>
                                    <select wire:model.defer="mesemitfin" id="mesemitfin"
                                        class=" select form-control selectfilemit filtbusc busqemit"
                                        wire:loading.attr="disabled">
                                        <?php foreach ($meses as $key => $value) {
                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por a??o --}}
                                    <label for="anioemitfin">A??o</label>
                                    <select wire:model.defer="anioemitfin" id="anioemitfin"
                                        class="select form-control selectfilemit filtbusc busqemit"
                                        wire:loading.attr="disabled">
                                        <?php foreach (array_reverse($anios) as $key => $value) {
                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;

                                    <button class="btn btn-secondary BtnVinculadas BtnConsulSAT"
                                        wire:loading.attr="disabled" wire:click="ConsultSAT()">Buscar</button>
                                    &nbsp;&nbsp;
                                </div>

                                {{-- Js para los filtros de emitidos --}}
                                <script>
                                    $(document).ready(function() {
                                        //Filtros para limitar el dia y mes 
                                        function LimitDiasMes() {
                                            //Antes de iniciar desblqueamos todos los meses
                                            $('#mesemitfin option').attr('disabled', false);
                                            //Y tambien los a??os
                                            $('#anioemitfin option').attr('disabled', false);
                                            //Dias
                                            $('#diaemitfin option').attr('disabled', false);

                                            //Obtenemos los valores iniciales
                                            //Dia inicial
                                            var DiaInic = $("#diaemitinic").val();
                                            //Mes inicial
                                            var MesInic = $("#mesemitinic").val();
                                            //A??o inicial
                                            var AnioInic = $("#anioemitinic")[0].selectedIndex;
                                            var AnioInicVal = $("#anioemitinic").val();

                                            //Obtenemos lo finales
                                            //A??o final
                                            var AnioFin = $("#anioemitfin").val();
                                            //Mes final
                                            var MesFin = $("#mesemitfin").val();

                                            //En base a los datos obtenidos vamos a limitar la fecha
                                            //A??o
                                            //Para este caso vamos a obtener el tama??o del select
                                            var Totalopanio = $('#anioemitinic option').length;

                                            for (var i = AnioInic + 1; i < Totalopanio; i++) {
                                                $('#anioemitfin option:eq(' + i + ')').attr('disabled', true);
                                            }

                                            //Mes
                                            if (AnioInicVal == AnioFin) {
                                                for (var i = 0; i < MesInic - 1; i++) {
                                                    //Bloquearemos el uso de los meses anteriores al seleccionado
                                                    $('#mesemitfin option:eq(' + i + ')').attr('disabled', true);
                                                }
                                            } else {
                                                //Si no es lo mismo desbloqueamos los dias
                                                $('#mesemitfin option').attr('disabled', false);
                                            }

                                            //Para los dias solo se limitaran cuando estan en el mismo mes
                                            //Obtenemos el valor del mes final
                                            if (MesInic == MesFin && AnioInicVal == AnioFin) {
                                                for (var i = 0; i < DiaInic - 1; i++) {
                                                    //Bloquearemos el uso de los dias anteriores al seleccionado
                                                    $('#diaemitfin option:eq(' + i + ')').attr('disabled', true);
                                                }
                                            } else {
                                                //Si no es lo mismo desbloqueamos los dias
                                                $('#diaemitfin option').attr('disabled', false);
                                            }
                                        }


                                        //Ejecutamos la funcion al iniciar los filtros de emitidos
                                        LimitDiasMes();

                                        //Ahora realizaremos lo mismo pero cuando se haga un cambio en el select
                                        $(".selectfilemit").change(function() {
                                            LimitDiasMes();
                                        });

                                        //Limitar la busqueda de la fecha (Emitidos)
                                        $(".busqemit").change(function() {
                                            //Obtenemos la fecha seleccionada
                                            //Inicial
                                            var MesSelectInc = $("#mesemitinic").val();
                                            var AnioSelectInc = $("#anioemitinic").val();
                                            var DiaSelectInc = $("#diaemitinic").val();

                                            //Fin
                                            var MesSelectFin = $("#mesemitfin").val();
                                            var AnioSelectFin = $("#anioemitfin").val();
                                            var DiaSelectFin = $("#diaemitfin").val();

                                            //En base a la fecha seleccionada vamos a obtener el total de dias
                                            var FechaSelectInic = new Date(AnioSelectInc, MesSelectInc, 0).getDate();

                                            //En base a la fecha seleccionada vamos a obtener el total de dias
                                            var FechaSelectFin = new Date(AnioSelectFin, MesSelectFin, 0).getDate();

                                            //Condicional donde comprobaremos si el dia seleccionado esta en el rango
                                            if (DiaSelectInc <= FechaSelectInic && DiaSelectFin <= FechaSelectFin) {
                                                //Desbloqueamos el boton de buscar
                                                $(".BtnConsulSAT").prop("disabled", false);

                                                $("#mnsexcep").text("");
                                                $("#Mnssincfdi").prop("hidden", true);
                                                $("#Btndescarcfdi").prop("disabled", false);
                                            } else {
                                                //Bloqueamos el boton de buscar
                                                $(".BtnConsulSAT").prop("disabled", true);

                                                $("#mnsexcep").text(
                                                    "Favor de seleccionar una fecha existente");
                                                $("#Mnssincfdi").prop("hidden", false);
                                                $("#Btndescarcfdi").prop("disabled", true);
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>

                        <br>

                        {{-- Tabla de emitidos --}}
                        <div class="table-responsive">
                            <table id="example" class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">XML <input id="allxml"
                                                type="checkbox" wire:loading.attr="disabled" /></th>
                                        <th class="text-center align-middle">R. Imp. <input id="allpdf"
                                                type="checkbox" wire:loading.attr="disabled" />
                                        </th>
                                        <th class="text-center align-middle">Acuse</th>
                                        <th class="text-center align-middle">Folio Fiscal</th>
                                        <th class="text-center align-middle">RFC</th>
                                        <th class="text-center align-middle">Raz??n Social</th>
                                        <th class="text-center align-middle">Emisi??n</th>
                                        <th class="text-center align-middle">Certificaci??n</th>
                                        <th class="text-center align-middle">Total</th>
                                        <th class="text-center align-middle">Efecto</th>
                                        <th class="text-center align-middle">Estado</th>
                                        <th class="text-center align-middle">Aprobaci??n</th>
                                        <th class="text-center align-middle">Descargado XML</th>
                                        <th class="text-center align-middle">Descargado PDF</th>
                                        <th class="text-center align-middle">Descargado Acuse</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (is_string($list))
                                        <tr>
                                            <td colspan="16">
                                                <span class="invoice-amount"> {{ $list }} </span>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($list as $listemiti)
                                            @php
                                                //Obtenemos las rutas de los CFDI recibidos
                                                //Mes
                                                switch ($mesemitinic) {
                                                    case '1':
                                                        $mesruta = '1.Enero';
                                                        break;
                                                
                                                    case '2':
                                                        $mesruta = '2.Febrero';
                                                        break;
                                                
                                                    case '3':
                                                        $mesruta = '3.Marzo';
                                                        break;
                                                
                                                    case '4':
                                                        $mesruta = '4.Abril';
                                                        break;
                                                
                                                    case '5':
                                                        $mesruta = '5.Mayo';
                                                        break;
                                                
                                                    case '6':
                                                        $mesruta = '6.Junio';
                                                        break;
                                                
                                                    case '7':
                                                        $mesruta = '7.Julio';
                                                        break;
                                                
                                                    case '8':
                                                        $mesruta = '8.Agosto';
                                                        break;
                                                
                                                    case '9':
                                                        $mesruta = '9.Septiembre';
                                                        break;
                                                
                                                    case '10':
                                                        $mesruta = '10.Octubre';
                                                        break;
                                                
                                                    case '11':
                                                        $mesruta = '11.Noviembre';
                                                        break;
                                                
                                                    case '12':
                                                        $mesruta = '12.Diciembre';
                                                        break;
                                                }
                                                
                                                //XML
                                                $rutaxml = "storage/contarappv1_descargas/" . strtoupper($this->rfcEmpresa) . "/$anioemitinic/Descargas/$mesruta/Emitidos/XML/";
                                                //PDF
                                                $rutapdf = "storage/contarappv1_descargas/" . strtoupper($this->rfcEmpresa) . "/$anioemitinic/Descargas/$mesruta/Emitidos/PDF/";
                                                //Acuse
                                                $rutapdfacuse = "storage/contarappv1_descargas/" . strtoupper($this->rfcEmpresa) . "/$anioemitinic/Descargas/$mesruta/Emitidos/ACUSE/";
                                                
                                                //Buscamos si exsiten los archivos (si estn descargados)
                                                //XML
                                                $xmlfile = $rutaxml . strtoupper($listemiti->uuid) . '.xml';
                                                if (file_exists($xmlfile)) {
                                                    $existxml = 'Si';
                                                } else {
                                                    $existxml = 'No';
                                                }
                                                
                                                //PDF
                                                $pdffile = $rutapdf . strtoupper($listemiti->uuid) . '.pdf';
                                                if (file_exists($pdffile)) {
                                                    $existpdf = 'Si';
                                                } else {
                                                    $existpdf = 'No';
                                                }
                                                
                                                //Acuse
                                                $acusefile = $rutapdfacuse . strtoupper($listemiti->uuid) . '-acuse' . '.pdf';
                                                if (file_exists($acusefile)) {
                                                    $existpdfacuse = 'Si';
                                                } else {
                                                    $existpdfacuse = 'No';
                                                }
                                            @endphp

                                            <tr>
                                                {{-- XML Checkbox --}}
                                                <td class="text-center align-middle">
                                                    @if ($listemiti->estadoComprobante == 'Vigente')
                                                        <input value="{{ $listemiti->uuid }}"
                                                            wire:loading.attr="disabled"
                                                            style="transform: scale(1.5);" name="chkxml"
                                                            class="mis-checkboxes ChkMasProv chkxml"
                                                            type="checkbox" />
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- R.Imp --}}
                                                <td class="text-center align-middle">
                                                    @if ($listemiti->estadoComprobante == 'Vigente')
                                                        <input value="{{ $listemiti->uuid }}"
                                                            wire:loading.attr="disabled"
                                                            style="transform: scale(1.5);" name="chkpdf"
                                                            class="mis-checkboxes ChkMasProv chkpdf"
                                                            type="checkbox" />
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- Acuse --}}
                                                <td class="text-center align-middle">
                                                    @if ($listemiti->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <input value="{{ $listemiti->uuid }}"
                                                            wire:loading.attr="disabled"
                                                            style="transform: scale(1.5);" name="chkacuse"
                                                            class="mis-checkboxes ChkMasProv chkacuse"
                                                            type="checkbox" />
                                                    @endif
                                                </td>

                                                {{-- Folio fiscal --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ strtoupper($listemiti->uuid) }}</span>
                                                </td>

                                                {{-- RFC Emisor --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listemiti->rfcReceptor }}</span>
                                                </td>

                                                {{-- Razon social (nombre del emisor) --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listemiti->nombreReceptor }}</span>
                                                </td>

                                                {{-- Fecha emision --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listemiti->fechaEmision }}</span>
                                                </td>

                                                {{-- Fecha certificcion --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listemiti->fechaCertificacion }}</span>
                                                </td>

                                                {{-- Total --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount">{{ $listemiti->total }}</span>
                                                </td>

                                                {{-- Efecto --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listemiti->efectoComprobante }}</span>
                                                </td>

                                                {{-- Estado --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listemiti->estadoComprobante }}</span>
                                                </td>

                                                {{-- Aprobacion --}}
                                                <td class="text-center align-middle">
                                                    @if ($listemiti->estadoComprobante == 'Vigente')
                                                        <img src="img/ima.png">
                                                    @else
                                                        <img src="img/ima2.png">
                                                    @endif
                                                </td>


                                                {{-- Desc XML --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount descxml">{{ $existxml }}</span>
                                                </td>

                                                {{-- Desc PDF --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount descpdf">{{ $existpdf }}</span>
                                                </td>

                                                {{-- Desc ACUSE --}}
                                                <td class="text-center align-middle">
                                                    @if ($listemiti->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <span
                                                            class="invoice-amount descacuse">{{ $existpdfacuse }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>

    {{-- Modal del calendario --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="calendariomodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-calendar">Calendario</span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">??</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Seccion del calendario --}}
                    {{-- Fecha de hoy --}}
                    <div id="contfechahoy" align="center">
                        @php
                            //Swich para convertir Int mes en String
                            switch ($mescal) {
                                case 1:
                                    $mescal = 'Enero ';
                                    break;
                                case 2:
                                    $mescal = 'Febrero ';
                                    break;
                                case 3:
                                    $mescal = 'Marzo ';
                                    break;
                                case 4:
                                    $mescal = 'Abril ';
                                    break;
                                case 5:
                                    $mescal = 'Mayo ';
                                    break;
                                case 6:
                                    $mescal = 'Junio ';
                                    break;
                                case 7:
                                    $mescal = 'Julio ';
                                    break;
                                case 8:
                                    $mescal = 'Agosto ';
                                    break;
                                case 9:
                                    $mescal = 'Septiembre ';
                                    break;
                                case 10:
                                    $mescal = 'Octubre ';
                                    break;
                                case 11:
                                    $mescal = 'Noviembre ';
                                    break;
                                case 12:
                                    $mescal = 'Diciembre ';
                                    break;
                            }
                        @endphp

                        <h3>{{ $mescal }} {{ $aniocal }}</h3>
                    </div>

                    <br>

                    {{-- Calendario --}}
                    {{-- Filtros de busqueda --}}
                    <div class="form-inline mr-auto">
                        {{-- Busqueda por mes --}}
                        <label for="inputState">Mes</label>
                        <select wire:model="mescal" id="inputState1" wire:loading.attr="disabled"
                            class="select form-control">
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;

                        {{-- Busqueda por a??o --}}
                        <label for="inputState">A??o</label>
                        <select wire:loading.attr="disabled" wire:model="aniocal" id="inputState2"
                            class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;
                    </div>

                    <br>

                    {{-- Formato calendario --}}
                    <div class="table-responsive">
                        <table class="table table-bordered calemitreci">
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
                                @php
                                    foreach ($weeks as $week) {
                                        echo $week;
                                    }
                                @endphp
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        $(document).ready(function() {
            //Funcion para marcar los checkboxes no descargados
            function checksindesc() {
                //Obtenemos el valor del select
                var Tiposelect = $("#selecttipocfdi").val();

                //Condicional para saber si el select es un emitido o recibido
                if (Tiposelect == "Recibidos") {
                    //Vamos a pasar por todas las filas
                    $("#example tbody tr").each(function() {
                        //Obtenemos el valor de las columnas
                        var xmldesc = $(this).find("td:eq(13)").text();
                        var pdfdesc = $(this).find("td:eq(14)").text();
                        var pdfacusedesc = $(this).find("td:eq(15)").text();

                        //Eliminamos los espacios en blanco
                        xmldesc = xmldesc.trim();
                        pdfdesc = pdfdesc.trim();
                        pdfacusedesc = pdfacusedesc.trim();

                        //Marcamos el checkbox cuando estos no existan
                        if (xmldesc == "No") {
                            $(this).find("td:eq(0) input").prop("checked", true);
                        }

                        if (pdfdesc == "No") {
                            $(this).find("td:eq(1) input").prop("checked", true);
                        }

                        if (pdfacusedesc == "No") {
                            $(this).find("td:eq(2) input").prop("checked", true);
                        }
                    });
                } else {
                    //Vamos a pasar por todas las filas
                    $("#example tbody tr").each(function() {
                        //Obtenemos el valor de las columnas
                        var xmldesc = $(this).find("td:eq(12)").text();
                        var pdfdesc = $(this).find("td:eq(13)").text();
                        var pdfacusedesc = $(this).find("td:eq(14)").text();

                        //Eliminamos los espacios en blanco
                        xmldesc = xmldesc.trim();
                        pdfdesc = pdfdesc.trim();
                        pdfacusedesc = pdfacusedesc.trim();

                        //Marcamos el checkbox cuando estos no existan
                        if (xmldesc == "No") {
                            $(this).find("td:eq(0) input").prop("checked", true);
                        }

                        if (pdfdesc == "No") {
                            $(this).find("td:eq(1) input").prop("checked", true);
                        }

                        if (pdfacusedesc == "No") {
                            $(this).find("td:eq(2) input").prop("checked", true);
                        }
                    });
                }
            }

            //Variables
            var valchkxml = []; //Inicializamos con la variable vacia
            var valchkpdf = []; //Inicializamos con la variable vacia
            var valchkacuse = []; //Inicializamos con la variable vacia
            var activdesc = 0;

            //Al iniciar el modulo se ejecutara la funcion de marcado
            checksindesc();

            //Accion de marcar todos los checkboxes
            //XML
            $("#allxml").change(function() {
                //Marcamos los checkboxes
                $(".chkxml").prop("checked", $(this).prop("checked"));
            });

            //PDF
            $("#allpdf").change(function() {
                //Marcamos los checkboxes
                $(".chkpdf").prop("checked", $(this).prop("checked"));
            });

            //Acciones al cambiar de tipo
            $("#selecttipocfdi").change(function() {
                //Bloqueamos el boton de consulta
                $(".BtnConsulSAT").prop("disabled", true);

                //Bloqueamos el boton de calendario
                $("#Btncalendario").prop("disabled", true);

                //Bloqueamos los select de fechas
                $(".filtbusc").prop("disabled", true);
            });

            //Boton de descarga
            $("#Btndescarcfdi").click(function() {
                //Almacenamos el valor de los checkboxes en un arreglo
                //PDF
                $("input[name=chkpdf]:checked").each(function() {
                    valchkpdf.push(this.value);
                });

                //XML
                $("input[name=chkxml]:checked").each(function() {
                    valchkxml.push(this.value);
                });

                //Acuses
                $("input[name=chkacuse]:checked").each(function() {
                    valchkacuse.push(this.value);
                });

                //Convertimos los arreglos en una cadena
                var Serialvalxml = valchkxml.toString();
                var Serialvalpdf = valchkpdf.toString();
                var Serialvalacuse = valchkacuse.toString();

                //Condicional para saber si se selecciono algun cfdi
                if (Serialvalxml.length > 1 || Serialvalpdf.length > 1 || Serialvalacuse.length > 1) {
                    //Condicional para saber si excedemos las 10000 descargas (permitidas por el SAT)
                    if (valchkxml.length > 10000 || valchkpdf.length > 10000 || valchkacuse.length >
                        10000) {
                        $("#mnsexcep").text(
                            "Lo sentimos el portal SAT no permite realizar mas de 10,000 descargas");
                        $("#Mnssincfdi").prop("hidden", false);
                        $("#Btndescarcfdi").prop("disabled", true);
                        setTimeout(function() {
                            $("#Mnssincfdi").prop("hidden", true);
                            $("#Btndescarcfdi").prop("disabled", false);
                        }, 2500);
                    } else {
                        //Bloqueamos el boton de buscar
                        $(".BtnConsulSAT").prop("disabled", true);

                        //Bloqueamos el boton de calendario
                        $("#Btncalendario").prop("disabled", true);

                        //Bloqueamos los select de fechas
                        $(".filtbusc").prop("disabled", true);

                        //Emitimos los valores marcados
                        window.livewire.emit('addallcfdi', {
                            xmlval: Serialvalxml,
                            pdfval: Serialvalpdf,
                            acuseval: Serialvalacuse
                        });
                    }
                } else {
                    $("#mnsexcep").text(
                        "Favor de seleccionar un CFDI");
                    $("#Mnssincfdi").prop("hidden", false);
                    $("#Btndescarcfdi").prop("disabled", true);
                    setTimeout(function() {
                        $("#Mnssincfdi").prop("hidden", true);
                        $("#Btndescarcfdi").prop("disabled", false);
                    }, 2500);
                }
            });

            //Limitar la busqueda de la fecha (Recibidos)
            $(".busqreci").change(function() {
                //Obtenemos la fecha seleccionada
                var MesSelect = $("#mesreci").val();
                var AnioSelect = $("#anioreci").val();
                var DiaSelect = $("#diareci").val();

                //En base a la fecha seleccionada vamos a obtener el total de dias
                var FechaSelect = new Date(AnioSelect, MesSelect, 0).getDate();

                //Condicional donde comprobaremos si el dia seleccionado esta en el rango
                if (DiaSelect <= FechaSelect || DiaSelect == "all") {
                    //Desbloqueamos el boton de buscar
                    $(".BtnConsulSAT").prop("disabled", false);

                    $("#mnsexcep").text("");
                    $("#Mnssincfdi").prop("hidden", true);
                    $("#Btndescarcfdi").prop("disabled", false);
                } else {
                    //Bloqueamos el boton de buscar
                    $(".BtnConsulSAT").prop("disabled", true);

                    $("#mnsexcep").text(
                        "Favor de seleccionar una fecha existente");
                    $("#Mnssincfdi").prop("hidden", false);
                    $("#Btndescarcfdi").prop("disabled", true);
                }
            });

            //Funcion para desmarcar los checkboxes
            window.addEventListener('deschecked', event => {
                //PDF
                $(".chkpdf").prop("checked", false);

                //Acuse
                $(".chkacuse").prop("checked", false);

                //XML
                $(".chkxml").prop("checked", false);

                //AllXml
                $("#allxml").prop("checked", false);

                //AllPdf
                $("#allpdf").prop("checked", false);

                //Desbloqueamos el boton de buscar
                $(".BtnConsulSAT").prop("disabled", false);

                //Desbloqueamos el boton de calendario
                $("#Btncalendario").prop("disabled", false);

                //Desbloqueamos los select de fechas
                $(".filtbusc").prop("disabled", false);

                //Vaciamos los array
                valchkxml = [];
                valchkpdf = [];
                valchkacuse = [];

                //Cuando se realice el proceso de desmaracar todos los checkboxes, marcamos lo que no se han descargado
                checksindesc();
            });

            //Funcion para mostrar la informacion de descarga
            window.addEventListener('showresultdesc', event => {
                $("#meninic").text("CFDI descargados con exito:");
                $("#mendesc").text("Descargados: " + event.detail.descarg);
                $("#menerror").text("Errores: " + event.detail.error);

                $("#Mnsdeccompl").prop("hidden", false);
                setTimeout(function() {
                    $("#Mnsdeccompl").prop("hidden", true);
                }, 5000);
            });
        });
    </script>
</div>
