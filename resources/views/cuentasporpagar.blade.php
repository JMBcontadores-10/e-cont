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
        <form action="{{ url('detalles') }}" method="GET">
            <table class="table table-sm table-hover table-bordered">
                <thead>
                    <tr class="table-primary">
                        <th class="text-center align-middle">N°</th>
                        <th id="vinp" class="text-center align-middle">Vincular Proveedores</th>
                        <th class="text-center align-middle">RFC Emisor</th>
                        <th class="text-center align-middle">Razón Social</th>
                        <th class="text-center align-middle">Lista Negra</th>
                        <th class="text-center align-middle">N° de CFDI's</th>
                        <th class="text-center align-middle">Total</th>
                        <th class="text-center align-middle">Detalles</th>
                    </tr>
                </thead>
                <tbody class="buscar">
                    @foreach ($col as $i)
                        @php
                            $sum = 0;
                            $nXml = 0;
                            $colT = DB::collection('metadata_r')
                                ->select('total', 'efecto')
                                ->where('receptorRfc', $rfc)
                                ->where('emisorRfc', $i['emisorRfc'])
                                ->whereNull('cheques_id')
                                ->get();
                            $nXml = $colT->count();
                            foreach ($colT as $v) {
                                $var = (float) $v['total'];
                                if ($v['efecto'] == 'Egreso') {
                                    $var = -1 * abs($var);
                                }
                                $sum = $sum + $var;
                            }
                            $tXml = $tXml + $nXml;
                            $tTabla = $tTabla + $sum;
                        @endphp
                        @if (!$nXml == 0)
                            <tr>
                                <td class="text-center align-middle">{{ ++$n }}</td>
                                <td id="vinp" class="text-center align-middle">
                                    <div id="checkbox-group" class="checkbox-group">
                                        <input class="mis-checkboxes" type="checkbox" id="allcheck" name="allcheck[]"
                                            value="{{ $i['emisorRfc'] }}" />
                                    </div>
                                </td>
                                <td class="text-center align-middle">{{ $i['emisorRfc'] }}</td>
                                <td class="align-middle">{{ $i['emisorNombre'] }}</td>
                                @if (!DB::collection('lista_negra')->select('RFC')->where(['RFC' => $i['emisorRfc']])->exists())
                                    <td class="td1 text-center align-middle"><img src="{{ asset('img/ima.png') }}" alt="">
                                    </td>
                                @else
                                    <td class="td1 text-center align-middle"><img src="{{ asset('img/ima2.png') }}"
                                            alt="">
                                    </td>
                                @endif
                                <td class="text-center align-middle">{{ $nXml }}</td>
                                <td class="text-center align-middle">${{ number_format($sum, 2) }}</td>
                                <td class="text-center align-middle">
                                    <form action="detalles" method="GET">
                                        <input type="hidden" name="emisorRfc" value="{{ $i['emisorRfc'] }}">
                                        <input type="hidden" name="emisorNombre" value="{{ $i['emisorNombre'] }}">
                                        <input type=submit value=Ver>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="vinp"></td>
                        <td class="text-bold " align="right">Total:</td>
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
