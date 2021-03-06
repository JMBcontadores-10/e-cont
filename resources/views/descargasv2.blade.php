@extends('layouts.app')

<head>
    <title>Descargas SAT E-cont</title>
</head>

@section('content')
    @php
    $rfc = Auth::user()->RFC;
    $nombre = Auth::user()->nombre;
    $dtz = new DateTimeZone('America/Mexico_City');
    $dt = new DateTime('now', $dtz);
    $diaDescarga = $dt->format('Y-n-d');
    @endphp

    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url()->previous() }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Descargas</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div align="left">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <form method="POST" class="login-form">
                @csrf
                <input type="hidden" name="accion" value="login_fiel" />
                <div class="row">
                    <div class="col-sm-3 form-group">
                        <button type="submit" class="btn btn-success">Iniciar sesión</button>
                    </div>
                </div>
            </form>
            <hr style="border-color:black; width:100%;">
        </div>
        <div id="calender"></div>

    </div>

    <div class="mx-4 mt-4">
        <h2>Descarga</h2>
        <div class="tablas-resultados ">
            <div class="overlay"></div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="nav-item active"><a class="nav-link" href="#recibidos"
                        aria-controls="recibidos" role="tab" data-toggle="tab">Recibidos</a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link" href="#emitidos"
                        aria-controls="emitidos" role="tab" data-toggle="tab">Emitidos</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="recibidos">
                    @include('descargam.form-recibidos-inc2')

                    {{-- <div class="input-group">
                        <span class="input-group-text">Buscar</span>
                        <input id="filtrar" type="text" class="form-control"
                            placeholder="Ingrese UUID que desea buscar o el nombre de empresa...">
                    </div><br> --}}

                    <form method="POST" class="descargaR-form">
                        @csrf
                        <input type="hidden" name="accion" value="descargar-recibidos" />
                        <input type="hidden" name="sesion" class="sesion-ipt" />
                        <div style="overflow:auto">
                            <table class="table table-sm table-bordered table-hover " id="tabla-recibidos">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="text-center align-middle">N°</th>
                                        <th class="text-center align-middle">XML <input type="checkbox" id="allxml"
                                                name="allxml" />
                                        </th>
                                        <th class="text-center align-middle">R. Imp. <input type="checkbox" id="allpdf"
                                                name="allpdf" /></th>
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
                                <tbody class="buscar"></tbody>
                            </table>
                        </div>
                        <br>
                        <div class="text-right">
                            <i id="loading" class="fas fa-2x fa-spinner fa-pulse mr-3"></i>
                            {{-- <a href="#" class="btn btn-primary excelR-export"
                                download="{{ $rfc }}_{{ $diaDescarga }}_cfdi_recibidos.xls">Exportar a Excel</a> --}}
                            <button id="bottomR" type="submit" class="btn btn-success">Descargar seleccionados</button>
                        </div>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="emitidos">
                    @include('descargam.form-emitidos-inc2')
                    <form method="POST" class="descargaE-form">
                        @csrf
                        <input type="hidden" name="accion" value="descargar-emitidos" />
                        <input type="hidden" name="sesion" class="sesion-ipt" />
                        <div class="mx-4" style="overflow:auto">
                            <table class="table table-sm table-bordered table-hover" id="tabla-emitidos">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="text-center align-middle">N°</th>
                                        <th class="text-center align-middle">XML <input type="checkbox" id="eallxml"
                                                name="eallxml" /></th>
                                        <th class="text-center align-middle">R. Imp. <input type="checkbox" id="eallpdf"
                                                name="eallpdf" /></th>
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
                                <tbody></tbody>
                            </table>
                        </div>
                        <br>
                        <div class="text-right">
                            <i id="loadingE" class="fas fa-2x fa-spinner fa-pulse mr-3"></i>
                            {{-- <a href="#" class="btn btn-primary excelE-export"
                                download="{{ $rfc }}_{{ $diaDescarga }}_cfdi_emitidos.xls">Exportar a Excel</a> --}}
                            <button id="bottomE" type="submit" class="btn btn-success">Descargar seleccionados</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('calendario')
    <script type="text/javascript" src="{{ URL::asset('js/calendario.js') }}" defer></script>
    <!-- esta en raíz de public -->
@endpush
