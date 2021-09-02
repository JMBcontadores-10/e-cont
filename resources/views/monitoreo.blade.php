@extends('layouts.app')

@php
use App\Models\MetadataE;
use Illuminate\Support\Facades\DB;
@endphp

@section('content')

    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{url()->previous()}}">
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

            <br>
            <h1>Facturación {{ $fechaF }}</h1>
        </div>
        <br>
        <h1>Exportar</h1>
        <br>
        <div class="row">
            <button type="submit" id="export_data" value="Excel" class="btn btn-info">PDF</button>
            <button type="submit" id="export_data" value="Excel" class="btn btn-info">Excel</button>
        </div>
        <br>
    </div>
    <div class="row">
        <div class="col-3">
            <figure class="highcharts-figure">
                <div id="container1"></div>
            <table class="table table-striped" id="datatable">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Facturas</th>
                        <th>Monto</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $arr = [];
                    @endphp
                    @for ($m = 0; $m < 24; $m++)
                        <tr>
                            <td>{{ $m }}</td>
                            @php
                                if ($m < 10) {
                                    $hur = '0' . $m;
                                    $fechaM = $fechaF . 'T' . $hur;
                                } else {
                                    $fechaM = $fechaF . 'T' . $m;
                                }

                                $cons = DB::table('metadata_e')
                                    ->select('fechaEmision', 'total')
                                    ->where('emisorRfc', $rfc)
                                    ->where('fechaEmision', 'like', $fechaM . '%')
                                    // ->orderBy('fechaEmision')
                                    ->get();


                                $count = $cons->count();
                                $arr[] = $count;


                                $suma = $cons->sum('total');


                            @endphp

                            <td>{{ $count }}</td>
                            <td>${{ $suma }}</td>
                        </tr>

                    @endfor
                </tbody>
            </table>
            </figure>
        </div>

        <div class="col-9">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>RFC</th>
                    <th>Razón Social</th>
                    <th># Facturas Emitidas</th>
                    <th>Monto</th>
                    <th>Ver</th>
                </tr>
                @php
                    $cont = 0;
                @endphp
                @foreach ($col as $i)
                    <tr>
                        @php
                            $colT = DB::collection('metadata_e')
                                ->select('total')
                                ->where('receptorRfc', $i['receptorRfc'])
                                ->where('fechaEmision', 'like', $fechaF . '%')
                                ->where('emisorRfc', $rfc)
                                ->get();
                            $countT = $colT->count();

                            $sumaT = $colT->sum('total');

                        @endphp
                        <td>{{++$cont}}</td>
                        <td>{{ $i['receptorRfc'] }}</td>
                        <td>{{ $i['receptorNombre'] }}</td>
                        <td>{{ $countT }}</td>
                        <td>$ {{ $sumaT }}</td>
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

    @endsection



