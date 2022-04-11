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
                    {{-- Encabezado del modulo --}}
                    <div class="justify-content-start">
                        <h1 style="font-weight: bold">{{ ucfirst(Auth::user()->nombre) }}</h1>
                        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
                    </div>

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
                    @endempty

                    <br>
                    <br>

                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                        <br>
                    </div>

                    <br>

                    {{-- Seccion de descargas --}}
                    {{-- Descargas --}}
                    <h1 style="font-weight: bold">Descarga</h1>

                    {{-- Select/mostrar calendario --}}
                    <div class="row">
                        {{-- Select para seleccionar el tipo de CFDI --}}
                        <div class="col-4">
                            <label for="selecttipocfdi">Tipo:</label>
                            <select wire:model="tipo" name="selecttipocfdi" id="selecttipocfdi"
                                wire:change="ResetParamColsul()" class="select form-control">
                                <option value="Recibidos">Recibidos</option>
                                <option value="Emitidos">Emitidos</option>
                            </select>
                        </div>

                        {{-- Calendario de registros --}}
                        <div class="col-4">
                            <div id="espaciado" style="height: 23.5px"></div>
                            <div class="invoice-create-btn mb-1">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#calendariomodal"
                                    data-backdrop="static" data-keyboard="false">Calendario de registros</button>
                            </div>
                        </div>

                        {{-- Boton de descarga a los CFDI seleccionados --}}
                        <div class="col">
                            <div id="espaciado" style="height: 23.5px"></div>
                            <div class="invoice-create-btn mb-1">
                                <button class="btn btn-success BtnVinculadas" wire:click="Descvincucfdi()">Descargar
                                    seleccionados</button>
                            </div>
                        </div>
                    </div>

                    <br>

                    {{-- Condicional para mostrar los filtros de cada tipo --}}
                    @if ($tipo == 'Recibidos')
                        {{-- Recibidos --}}
                        <label>Selecciona una fecha:</label>

                        {{-- Filtros de busqueda --}}
                        <div class="form-inline mr-auto">
                            {{-- Busqueda por dia --}}
                            <label for="diareci">Dia</label>
                            <select wire:model.defer="diareci" id="diareci" wire:loading.attr="disabled"
                                class="select form-control">
                                @php
                                    for ($i = 1; $i <= 31; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                @endphp
                            </select>
                            &nbsp;&nbsp;

                            {{-- Busqueda por mes --}}
                            <label for="mesreci">Mes</label>
                            <select wire:model.defer="mesreci" id="mesreci" wire:loading.attr="disabled"
                                class=" select form-control">
                                <?php foreach ($meses as $key => $value) {
                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                } ?>
                            </select>
                            &nbsp;&nbsp;


                            {{-- Busqueda por año --}}
                            <label for="anioreci">Año</label>
                            <select wire:loading.attr="disabled" wire:model.defer="anioreci" id="anioreci"
                                class="select form-control">
                                <?php foreach (array_reverse($anios) as $value) {
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                } ?>
                            </select>
                            &nbsp;&nbsp;

                            <button class="btn btn-secondary BtnVinculadas" wire:click="ConsultSAT()">Buscar</button>
                            &nbsp;&nbsp;
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
                                    <select wire:model.defer="diaemitinic" id="diaemitinic" wire:loading.attr="disabled"
                                        class="select form-control">
                                        @php
                                            for ($i = 1; $i <= 31; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        @endphp
                                    </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por mes --}}
                                    <label for="mesemitinic">Mes</label>
                                    <select wire:model.defer="mesemitinic" id="mesemitinic" wire:loading.attr="disabled"
                                        class=" select form-control">
                                        <?php foreach ($meses as $key => $value) {
                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;


                                    {{-- Busqueda por año --}}
                                    <label for="anioemitinic">Año</label>
                                    <select wire:loading.attr="disabled" wire:model.defer="anioemitinic" id="anioemitinic"
                                        class="select form-control">
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
                                    <select wire:model.defer="diaemitfin" id="diaemitfin" wire:loading.attr="disabled"
                                        class="select form-control">
                                        @php
                                            for ($i = 1; $i <= 31; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        @endphp
                                    </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por mes --}}
                                    <label for="mesemitfin">Mes</label>
                                    <select wire:model.defer="mesemitfin" id="mesemitfin" wire:loading.attr="disabled"
                                        class=" select form-control">
                                        <?php foreach ($meses as $key => $value) {
                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por año --}}
                                    <label for="anioemitfin">Año</label>
                                    <select wire:loading.attr="disabled" wire:model.defer="anioemitfin" id="anioemitfin"
                                        class="select form-control">
                                        <?php foreach (array_reverse($anios) as $value) {
                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;

                                    <button class="btn btn-secondary BtnVinculadas" wire:click="ConsultSAT()">Buscar</button>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                        </div>
                    @endif

                    <br>

                    {{-- Tabla de consulta --}}

                    {{-- Condicional para mostrar la tabla de cada tipo --}}
                    @if ($tipo == 'Recibidos')
                        {{-- Tabla de recibidos --}}
                        <div class="table-responsive">
                            <table id="example" class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">XML <input type="checkbox" /></th>
                                        <th class="text-center align-middle">R. Imp. <input type="checkbox" /></th>
                                        <th class="text-center align-middle">Acuse</th>
                                        <th class="text-center align-middle">Folio Fiscal</th>
                                        <th class="text-center align-middle">RFC</th>
                                        <th class="text-center align-middle">Razón Social</th>
                                        <th class="text-center align-middle">Emisión</th>
                                        <th class="text-center align-middle">Certificación</th>
                                        <th class="text-center align-middle">Total</th>
                                        <th class="text-center align-middle">Efecto</th>
                                        <th class="text-center align-middle">Estado</th>
                                        <th class="text-center align-middle">Cancelación</th>
                                        <th class="text-center align-middle">Aprobación</th>
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
                                                $rutaxml = "storage/contarappv1_descargas/$rfcEmpresa/$anioreci/Descargas/$mesruta/Recibidos/XML/";
                                                //PDF
                                                $rutapdf = "storage/contarappv1_descargas/$rfcEmpresa/$anioreci/Descargas/$mesruta/Recibidos/PDF/";
                                                
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
                                                $acusefile = $rutapdf . strtoupper($listrecibi->uuid) . '-acuse' . '.pdf';
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
                                                        <div id="checkbox-group" class="checkbox-group">
                                                            <input value="{{ $listrecibi->uuid }}"
                                                                wire:model.defer="cfdiselectxml"
                                                                style="transform: scale(1.5);"
                                                                class="mis-checkboxes ChkMasProv" type="checkbox" />
                                                        </div>
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- R.Imp --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <div id="checkbox-group" class="checkbox-group">
                                                            <input value="{{ $listrecibi->uuid }}"
                                                                wire:model.defer="cfdiselectpdf"
                                                                style="transform: scale(1.5);"
                                                                class="mis-checkboxes ChkMasProv" type="checkbox" />
                                                        </div>
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- Acuse --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <div id="checkbox-group" class="checkbox-group">
                                                            <input value="{{ $listrecibi->uuid }}"
                                                                wire:model.defer="cfdiselectpdfacuse"
                                                                style="transform: scale(1.5);"
                                                                class="mis-checkboxes ChkMasProv" type="checkbox" />
                                                        </div>
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
                                                    <span class="invoice-amount">{{ $existxml }}</span>
                                                </td>

                                                {{-- Desc PDF --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount">{{ $existpdf }}</span>
                                                </td>

                                                {{-- Desc ACUSE --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <span class="invoice-amount">{{ $existpdfacuse }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @elseif ($tipo == 'Emitidos')
                        {{-- Tabla de recibidos --}}
                        <div class="table-responsive">
                            <table id="example" class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">XML <input type="checkbox" /></th>
                                        <th class="text-center align-middle">R. Imp. <input type="checkbox" /></th>
                                        <th class="text-center align-middle">Acuse</th>
                                        <th class="text-center align-middle">Folio Fiscal</th>
                                        <th class="text-center align-middle">RFC</th>
                                        <th class="text-center align-middle">Razón Social</th>
                                        <th class="text-center align-middle">Emisión</th>
                                        <th class="text-center align-middle">Certificación</th>
                                        <th class="text-center align-middle">Total</th>
                                        <th class="text-center align-middle">Efecto</th>
                                        <th class="text-center align-middle">Estado</th>
                                        <th class="text-center align-middle">Aprobación</th>
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
                                                $rutaxml = "storage/contarappv1_descargas/$rfcEmpresa/$anioreci/Descargas/$mesruta/Recibidos/XML/";
                                                //PDF
                                                $rutapdf = "storage/contarappv1_descargas/$rfcEmpresa/$anioreci/Descargas/$mesruta/Recibidos/PDF/";
                                                
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
                                                $acusefile = $rutapdf . strtoupper($listrecibi->uuid) . '-acuse' . '.pdf';
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
                                                        <div id="checkbox-group" class="checkbox-group">
                                                            <input value="{{ $listrecibi->uuid }}"
                                                                wire:model.defer="cfdiselectxml"
                                                                style="transform: scale(1.5);"
                                                                class="mis-checkboxes ChkMasProv" type="checkbox" />
                                                        </div>
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- R.Imp --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <div id="checkbox-group" class="checkbox-group">
                                                            <input value="{{ $listrecibi->uuid }}"
                                                                wire:model.defer="cfdiselectpdf"
                                                                style="transform: scale(1.5);"
                                                                class="mis-checkboxes ChkMasProv" type="checkbox" />
                                                        </div>
                                                    @else
                                                        <span class="invoice-amount"> - </span>
                                                    @endif
                                                </td>

                                                {{-- Acuse --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <div id="checkbox-group" class="checkbox-group">
                                                            <input value="{{ $listrecibi->uuid }}"
                                                                wire:model.defer="cfdiselectpdfacuse"
                                                                style="transform: scale(1.5);"
                                                                class="mis-checkboxes ChkMasProv" type="checkbox" />
                                                        </div>
                                                    @endif
                                                </td>

                                                {{-- Folio fiscal --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ strtoupper($listrecibi->uuid) }}</span>
                                                </td>

                                                {{-- RFC Emisor --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listrecibi->rfcReceptor }}</span>
                                                </td>

                                                {{-- Razon social (nombre del emisor) --}}
                                                <td class="text-center align-middle">
                                                    <span
                                                        class="invoice-amount">{{ $listrecibi->nombreReceptor }}</span>
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
                                                    <span class="invoice-amount">{{ $existxml }}</span>
                                                </td>

                                                {{-- Desc PDF --}}
                                                <td class="text-center align-middle">
                                                    <span class="invoice-amount">{{ $existpdf }}</span>
                                                </td>

                                                {{-- Desc ACUSE --}}
                                                <td class="text-center align-middle">
                                                    @if ($listrecibi->estadoComprobante == 'Vigente')
                                                        <span class="invoice-amount"> - </span>
                                                    @else
                                                        <span class="invoice-amount">{{ $existpdfacuse }}</span>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="RefreshCal()">
                        <span aria-hidden="true close-btn">×</span>
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
                                    $mescal = 'Enero de ';
                                    break;
                                case 2:
                                    $mescal = 'Febrero de ';
                                    break;
                                case 3:
                                    $mescal = 'Marzo de ';
                                    break;
                                case 4:
                                    $mescal = 'Abril de ';
                                    break;
                                case 5:
                                    $mescal = 'Mayo de ';
                                    break;
                                case 6:
                                    $mescal = 'Junio de ';
                                    break;
                                case 7:
                                    $mescal = 'Julio de ';
                                    break;
                                case 8:
                                    $mescal = 'Agosto de ';
                                    break;
                                case 9:
                                    $mescal = 'Septiembre de ';
                                    break;
                                case 10:
                                    $mescal = 'Octubre de ';
                                    break;
                                case 11:
                                    $mescal = 'Noviembre de ';
                                    break;
                                case 12:
                                    $mescal = 'Diciembre de ';
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

                        {{-- Busqueda por año --}}
                        <label for="inputState">Año</label>
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
</div>
