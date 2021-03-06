@extends('layouts.app')

@php
use App\Models\MetadataR;
use App\Models\XmlR;
use App\Models\XmlE;
@endphp

<head>
    <title>Facturas desglosadas </title>
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{url()->previous() }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Facturas</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold">Facturas de</label>
            <h1>{{ $receptorNombre }}</h1>
            <h1>{{ $receptorRfc }}</h1>
            <hr style="border-color:black; width:100%;">
        </div>

        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar">
        </div><br>

        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Estado SAT</th>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Fecha Timbrado</th>
                    <th class="text-center">Serie</th>
                    <th class="text-center">Folio</th>
                    <th class="text-center">UUID</th>
                    <th class="text-center">Lugar de Expedición</th>
                    <th class="text-center">RFC Receptor</th>
                    <th class="text-center">Nombre Receptor</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Forma de Pago</th>
                    <th class="text-center">Conceptos</th>
                </tr>
            </thead>
            <tbody class="buscar">
                @foreach ($colF as $te)
                    <tr>
                        <td>{{ $te['estado'] }}</td>
                        @php

                            $fecha = $te['fechaCertificacion'];
                            $fech = substr($fecha, 0, 10);
                            $folio = $te['folioFiscal'];

                            $colD = XmlE::where('UUID', $folio)->get();

                            foreach ($colD as $d) {
                                $serie = $d['Serie'];
                                $lugare = $d['LugarExpedicion'];
                                $formap = $d['FormaPago'];
                                $tipoc = $d['TipoDeComprobante'];
                                $folio1 = $d['folio'];
                                $concepto = $d['Conceptos.Concepto'];
                                $nCon = 0;
                            }
                        @endphp
                        @if (isset($tipoc))
                            <td>{{ $tipoc }}</td>
                        @else
                            <td>---</td>
                        @endif
                        <td>{{ $fech }}</td>
                        @if (isset($serie))
                            <td>{{ $serie }}</td>
                        @else
                            <td>---</td>
                        @endif
                        @if (isset($folio1))
                            <td>{{ $folio1 }}</td>
                        @else
                            <td>---</td>
                        @endif
                        <td>{{ $te['folioFiscal'] }}</td>
                        @if (isset($lugare))
                            <td>{{ $lugare }}</td>
                        @else
                            <td>---</td>
                        @endif

                        <td>{{ $te['receptorRfc'] }}</td>
                        <td>{{ $te['receptorNombre'] }}</td>
                        <td>${{ $te['total'] }}</td>
                        @if (isset($formap))
                            <td>{{ $formap }}</td>
                        @else
                            <td>---</td>
                        @endif


                        @if (isset($concepto))
                            @foreach ($concepto as $id)
                                <td>{{ ++$nCon }}. {{ $id['Descripcion'] }}<br></td>
                            @endforeach
                        @else
                            <td>---</td>
                        @endif


                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
