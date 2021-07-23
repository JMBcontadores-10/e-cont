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
            <a class="b3" href="{{ url('/cheques-transferencias') }}">
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
            <h1 align="center">Cheque y/o transferencia para Multi-Cheques</h1>
            <hr style="border-color:black; width:100%;">
        </div>

        {{-- <form action="{{ url('vincular-cheque') }}" method="POST"> --}}
        @csrf
        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr class="table-primary">
                    <th class="text-center">N°</th>
                    <th class="text-center">Check <input type="checkbox" id="allcheck" name="allcheck" /></th>
                    <th class="text-center">RFC Emisor</th>
                    <th class="text-center">Razón Social Emisor</th>
                    <th class="text-center">UUID</th>
                    <th class="text-center">Fecha Emisión</th>
                    <th class="text-center">Concepto</th>
                    <th class="text-center">Folio</th>
                    <th class="text-center">Efecto</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Descargar</th>
                    <th class="text-center">Archivo</th>
                </tr>
            </thead>
            <tbody class="buscar">
                @foreach ($colM as $i)
                    @php
                        $emisorRfc = $i->emisorRfc;
                        $emisorNombre = $i->emisorNombre;
                        $folioF = $i->folioFiscal;
                        $fechaE = $i->fechaEmision;
                        $efecto = $i->efecto;
                        $total = $i->total;
                        $estado = $i->estado;
                        if ($efecto == 'Egreso') {
                            $total = -1 * abs($total);
                        }

                    @endphp
                    <tr>
                        <td class="text-center">{{ ++$n }}</td>
                        <td class="text-center allcheck">
                            <div id="checkbox-group" class="checkbox-group">
                                <input class="mis-checkboxes" type="checkbox" id="allcheck" name="allcheck[]"
                                    value="{{ $folioF }}" />
                            </div>
                        </td>
                        <td class="text-center">{{ $emisorRfc }}</td>
                        <td class="text-center">{{ $emisorNombre }}</td>
                        <td class="text-center">{{ $folioF }}</td>
                        <td class="text-center">{{ $fechaE }}</td>
                        @php
                            $anio = substr($fechaE, 0, 4);
                            $mes = (string) (int) substr($fechaE, 5, 2);
                            $rutaXml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/XML/$folioF.xml";
                            $rutaPdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/PDF/$folioF.pdf";
                            $concepto = '-';
                            $metodoPago = '-';
                            $uuidRef = '-';
                            $folio = '-';
                            $totalX = 0;
                            $colX = XmlR::where(['UUID' => $folioF])->get();
                            foreach ($colX as $v) {
                                $concepto = $v['0.Conceptos.Concepto.0.Descripcion'];
                                $metodoPago = $v['0.MetodoPago'];
                                $folio = $v['0.Folio'];
                                if ($efecto == 'Pago') {
                                    $uuid = $v['0.Complemento.0.Pagos.Pago.0.DoctoRelacionado.0.IdDocumento'];
                                }
                            }
                        @endphp
                        <td class="text-center">{{ $concepto }}</td>
                        <td class="text-center">{{ $folio }}</td>
                        <td class="text-center">{{ $efecto }}</td>
                        <td class="text-center">${{ number_format($total, 2) }}</td>
                        <td class="text-center">{{ $estado }}</td>
                        <td class="text-center">
                            <a class="btn btn-primary m-1" href="{{ $rutaXml }}"
                                download="{{ $folioF }}.xml">XML</a>
                            <a class="btn btn-danger m-1" href="{{ $rutaPdf }}" target="_blank">PDF</a>
                        </td>
                        <td class="text-center">
                        <input type="submit" name="sa" value="Subir Archivo">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div class="d-flex justify-content-center">
                <input id="vinct" type="submit" value="Vincular Cheque/Transferencia"
                    style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
            </div> --}}
        {{-- </form> --}}
    </div>
@endsection
