@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url()->previous() }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Control Volumétrico</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>
        <div class="d-flex justify-content-center">
            <h1>Consulta Histórica</h1>
        </div>

        <table class="table table-sm">
            <thead>
                <tr class="table-success">
                    <th colspan="7" style="text-align:center; font-size:24px;">Magna</th>
                </tr>
                <tr class="table-success">
                    <th scope="col">#</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Inventario Inicial</th>
                    <th scope="col">Compras</th>
                    <th scope="col">Ventas</th>
                    <th scope="col">Inventario Real (auto-stick)</th>
                    <th scope="col">Precio Venta</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $m = 0;
                @endphp
                @foreach ($conVol as $cV)
                    <tr>
                        <th scope="row">{{ ++$m }}</th>
                        <td>{{ $cV['fech1'] }}</td>
                        <td>{{ $cV['iiM'] }}</td>
                        <td>{{ $cV['cM'] }}</td>
                        <td>{{ $cV['vM'] }}</td>
                        <td>{{ $cV['aM'] }}</td>
                        <td>${{ $cV['pM'] }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <br>
        <br>

        <table class="table table-sm">
            <thead>
                <tr class="table-danger">
                    <th colspan="7" style="text-align:center; font-size:24px;">Premium</th>
                </tr>
                <tr class="table-danger">
                    <th scope="col">#</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Inventario Inicial</th>
                    <th scope="col">Compras</th>
                    <th scope="col">Ventas</th>
                    <th scope="col">Inventario Real (auto-stick)</th>
                    <th scope="col">Precio Venta</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $p = 0;
                @endphp
                @foreach ($conVol as $cV)
                    <tr>
                        <th scope="row">{{ ++$p }}</th>
                        <td>{{ $cV['fech1'] }}</td>
                        <td>{{ $cV['iiP'] }}</td>
                        <td>{{ $cV['cP'] }}</td>
                        <td>{{ $cV['vP'] }}</td>
                        <td>{{ $cV['aP'] }}</td>
                        <td>${{ $cV['pP'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
        @php
            $diesel = DB::table('clientes')
                ->select('diesel')
                ->where('RFC', Auth::user()->RFC)
                ->get();
            foreach ($diesel as $di) {
                $die = $di['diesel'];
            }
        @endphp
        @if ($die == '1')



            <table class="table table-sm">
                <thead>
                    <tr class="table-warning">
                        <th colspan="7" style="text-align:center; font-size:24px;">Diesel</th>
                    </tr>
                    <tr class="table-warning">
                        <th scope="col">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Inventario Inicial</th>
                        <th scope="col">Compras</th>
                        <th scope="col">Ventas</th>
                        <th scope="col">Inventario Real (auto-stick)</th>
                        <th scope="col">Precio Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $d = 0;
                    @endphp
                    @foreach ($conVol as $cV)
                        <tr>
                            <th scope="row">{{ ++$d }}</th>
                            <td>{{ $cV['fech1'] }}</td>
                            <td>{{ $cV['iiD'] }}</td>
                            <td>{{ $cV['cD'] }}</td>
                            <td>{{ $cV['vD'] }}</td>
                            <td>{{ $cV['aD'] }}</td>
                            <td>${{ $cV['pD'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>



@endsection
