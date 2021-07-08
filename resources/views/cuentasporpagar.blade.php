@extends('layouts.app')

@php
use App\Models\MetadataR;
@endphp

<head>
    <title>Cuentas por pagar Contarapp </title>
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url('/') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Descargas</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>
        <div class="row justify-content-end">
            <div>
                <form action="/" method="get">
                    <button class="button2">Módulo: Cheques y Transferencias</button>
                </form>
            </div>
            <div>
                <form action="/" method="get">
                    <button class="button2">Vincular Varios Proveedores</button>
                </form>
            </div>
        </div>

        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar proveedor">
            {{-- <a href="#bottom" class="btn btn-primary ml-2">Ir abajo</a> --}}
        </div><br>

        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">N°</th>
                    <th class="text-center">RFC Emisor</th>
                    <th class="text-center">Razón Social</th>
                    <th class="text-center">Lista Negra</th>
                    <th class="text-center">N° de XML</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Detalles</th>
                </tr>
            </thead>
            <tbody class="buscar">
                @foreach ($col as $i)
                    <tr>
                        <td class="text-center">{{ ++$n }}</td>
                        <td class="text-center">{{ $i['emisorRfc'] }}</td>
                        <td>{{ $i['emisorNombre'] }}</td>
                        <td class="td1 text-center"><img src="{{ asset('img/ima.png') }}" alt=""></td>
                        @php
                            $sum = 0;
                            $nXml = 0;
                            $colT = MetadataR::where(['receptorRfc' => $rfc, 'emisorRfc' => $i['emisorRfc']])
                                ->orderBy('emisorNombre', 'asc')
                                ->get();
                            foreach ($colT as $v) {
                                $var = (float) $v->total;
                                $sum = $sum + $var;
                                $nXml++;
                            }
                            $tXml = $tXml + $nXml;
                            $tTabla = $tTabla + $sum;
                        @endphp
                        <td class="text-center">{{ $nXml }}</td>
                        <td class="text-center">${{ number_format($sum, 2) }}</td>
                        <td class="text-center">
                            <form action="detalles" method="POST">
                                @csrf
                                <input type="hidden" name="emisorRfc" value="{{ $i['emisorRfc'] }}">
                                <input type="hidden" name="emisorNombre" value="{{ $i['emisorNombre'] }}">
                                <input type=submit value=Ver>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center text-bold">Total</td>
                    <td class="text-center text-bold">{{ $tXml }}</td>
                    <td id="bottom" class="text-center text-bold">${{ number_format($tTabla, 2) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
