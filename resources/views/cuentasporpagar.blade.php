@extends('layouts.app')

@php
use App\Models\MetadataR;
use App\Models\ListaNegra;
@endphp

<head>
    <title>Cuentas por pagar Contarapp</title>
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url('/') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Cuentas por pagar</p>
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
            <form action="{{ url('cheques-transferencias') }}">
                <button class="button2">Módulo: Cheques y Transferencias</button>
            </form>
            <form>
                <button class="button2" id="vinpbtn">Vincular Varios Proveedores</button>
            </form>
        </div>

        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar proveedor">
            <a id="vinp" href="#bottom" class="btn btn-primary ml-2">Ir a vincular proveedores</a>
        </div><br>
        <form action="{{ url('detalles') }}" method="POST">
            <table class="table table-sm table-hover table-bordered">
                <thead>
                    <tr class="table-primary">
                        <th class="text-center">N°</th>
                        <th id="vinp" class="text-center">Vincular Proveedores</th>
                        <th class="text-center">RFC Emisor</th>
                        <th class="text-center">Razón Social</th>
                        <th class="text-center">Lista Negra</th>
                        <th class="text-center">N° de CFDI's</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Detalles</th>
                    </tr>
                </thead>
                <tbody class="buscar">
                    @foreach ($col as $i)
                        <tr>
                            <td class="text-center">{{ ++$n }}</td>
                            <td id="vinp" class="text-center">
                                <div id="checkbox-group" class="checkbox-group">
                                    <input class="mis-checkboxes" type="checkbox" id="allcheck" name="allcheck[]"
                                        value="{{ $i['emisorRfc'] }}" />
                                </div>
                            </td>
                            <td class="text-center">{{ $i['emisorRfc'] }}</td>
                            <td>{{ $i['emisorNombre'] }}</td>
                            @php
                                $colLN = ListaNegra::where(['RFC' => $i['emisorRfc']]);
                                $cget = $colLN->get()->first();
                            @endphp
                            @if ($cget == null)
                                <td class="td1 text-center"><img src="{{ asset('img/ima.png') }}" alt=""></td>
                            @else
                                <td class="td1 text-center"><img src="{{ asset('img/ima2.png') }}" alt=""></td>
                            @endif
                            @php
                                $sum = 0;
                                $nXml = 0;
                                $colT = MetadataR::where(['receptorRfc' => $rfc, 'emisorRfc' => $i['emisorRfc']])
                                    ->whereNull('cheques_id')
                                    ->orderBy('emisorNombre', 'asc')
                                    ->get();
                                foreach ($colT as $v) {
                                    $var = (float) $v->total;
                                    if ($v->efecto == 'Egreso') {
                                        $var = -1 * abs($var);
                                    }
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
                        <td id="vinp"></td>
                        <td class="text-bold" align="right">Total:</td>
                        <td class="text-center text-bold">{{ $tXml }}</td>
                        <td id="bottom" class="text-center text-bold">${{ number_format($tTabla, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <input id="vinpsub" type="submit" value="Vincular Proveedores"
                    style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
            </div>
        </form>
    </div>
@endsection
