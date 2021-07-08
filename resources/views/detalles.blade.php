@extends('layouts.app')

@php
use App\Models\MetadataR;
use App\Models\XmlR;
@endphp

<head>
    <title>Cuentas por pagar Contarapp </title>
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url('/cuentasporpagar') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Descargas</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold">Cuentas por pagar de:</label>
            <h1 style="font-weight: bold">{{ $emisorNombre }}</h1>
            <h5 style="font-weight: bold">{{ $emisorRfc }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>

        <div class="row justify-content-end">
            <div>
                <form action="/" method="get">
                    <button class="button2">Módulo: Cheques y Transferencias</button>
                </form>
            </div>
        </div>

        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar proveedor">
        </div><br>

        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">N°</th>
                    <th class="text-center">Check <input type="checkbox" checked id="allcheck" name="allcheck" /></th>
                    <th class="text-center">UUID</th>
                    <th class="text-center">Fecha Fiscal</th>
                    <th class="text-center">Concepto</th>
                    <th class="text-center">Metodo-Pago</th>
                    <th class="text-center">UUID-Referencial</th>
                    <th class="text-center">Folio</th>
                    <th class="text-center">Efecto</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Descargar</th>
                </tr>
            </thead>
            <tbody class="buscar">
                @foreach ($colM as $i)
                    <tr>
                        <td class="text-center">{{ ++$n }}</td>
                        <td class="text-center"><input type="checkbox" id="allcheck" name="allcheck" /></td>
                        <td class="text-center">{{ $i->folioFiscal }}</td>
                        <td class="text-center">{{ $i->fechaCertificacion }}</td>
                        @php
                        $mp = '';
                            $colX = XmlR::where(['UUID' => $i->folioFiscal])->get();
                            // dd($colX);
                            foreach ($colX as $v) {
                                $mp = $v['0'];
                                dd($mp);
                            }
                        @endphp
                        <td>Descripción</td>
                        <td>{{ $mp }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
