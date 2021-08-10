@extends('layouts.app')

@php
use App\Models\MetadataE;
use Illuminate\Support\Facades\DB;
@endphp

@section('content')

    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url('/') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Monitoreo de Facturación</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>
        <div class="row" style="justify-content: center;">

            @php
                $rfc = Auth::user()->RFC;

                $dtz = new DateTimeZone('America/Mexico_City');
                $dt = new DateTime('now', $dtz);

                if (isset($argv[1])) {
                    $dt->sub(new DateInterval($argv[1]));
                } else {
                    $dt->sub(new DateInterval('P1D'));
                }

                $anio = $dt->format('Y');
                $mes = $dt->format('m');
                $dia = $dt->format('d');
                $fechaF = "$anio-$mes-$dia";
                $fecha1 = $fechaF . 'T00:00:00';
                $fecha2 = $fechaF . 'T23:59:59';

            @endphp

            <br>
            <h1>Facturación @php
                echo $fechaF;
            @endphp</h1>
        </div>
        <br>
        <h1>Exportar</h1>
        <br>
        <div class="row">
            <button type="submit" id="export_data" value="Excel" class="btn btn-info">PDF</button>
            <button type="submit" id="export_data" value="Excel" class="btn btn-info">Excel</button>
        </div>
        <br>
        <div class="row">
            <div class="col-2">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Facturas</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                            @for ($m = 0; $m < 24; $m++)
                                <tr>
                                    <td>{{ $m }}</td>
                                    {{-- @php
                                        $fac = MetadataE::where('emisorRfc', $rfc)
                                                ->whereBetween('fechaEmision', array($fecha1,$fecha2))
                                                ->get();

                                            foreach ($fac as $fa) {
                                                $fechaF = $fa['fechaEmision'];
                                            }
                                    @endphp --}}


                                    <td></td>
                                    <td></td>
                                </tr>

                            @endfor

                    </tbody>
                </table>

            </div>
            <div class="col-1">

            </div>

            <div class="col-9">
                <table class="table table-striped">
                    <tr>
                        <th>RFC</th>
                        <th>Razón Social</th>
                        <th># Facturas Emitidas</th>
                        <th>Monto</th>
                        <th>Ver</th>
                    </tr>
                    @foreach ($col as $i)
                        <tr>
                            <td>{{ $i['receptorRfc'] }}</td>
                            <td>{{ $i['receptorNombre'] }}</td>
                            @php
                                $rfc = Auth::user()->RFC;
                                $rfcR = $i['receptorRfc'];
                                $cant = MetadataE::where('receptorRfc', $rfcR)
                                    ->whereBetween('fechaEmision', [$fecha1, $fecha2])
                                    ->count();

                            @endphp
                            <td>{{ $cant }}</td>
                            <td>$ {{ $i['total'] }}</td>
                            <td>
                                <form action="{{ route('detallesfactura') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="receptorRfc" value="{{ $i['receptorRfc'] }}">
                                    <input type="hidden" name="receptorNombre" value="{{ $i['receptorNombre'] }}">
                                    <input type=submit value=Ver>
                                </form>
                            </td>
                    @endforeach

                </table>

            </div>

        </div>
    </div>

@endsection
