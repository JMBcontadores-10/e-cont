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
    </div>

    <form action="{{ url('desvincular-cheque') }}" method="POST">
        @csrf
        <table class="table table-sm table-hover table-bordered mx-2">
            <thead>
                <tr class="table-primary">
                    <th class="text-center align-middle">N°</th>
                    @if ($verificado == 0)
                        <th class="text-center align-middle">Desvincular CFDI <input type="checkbox" id="allcheck"
                                name="allcheck" /></th>
                    @endif
                    <th class="text-center align-middle">RFC Emisor</th>
                    <th class="text-center align-middle">Razón Social Emisor</th>
                    <th class="text-center align-middle">UUID</th>
                    <th class="text-center align-middle">Fecha Emisión</th>
                    <th class="text-center align-middle">Concepto</th>
                    <th class="text-center align-middle">Folio</th>
                    <th class="text-center align-middle">Metodo - Pago</th>
                    <th class="text-center align-middle">Complemento</th>
                    <th class="text-center align-middle">Efecto</th>
                    <th class="text-center align-middle">Total</th>
                    <th class="text-center align-middle">Estado</th>
                    <th class="text-center align-middle">Descargar</th>
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
                        <td class="text-center align-middle">{{ ++$n }}</td>
                        @if ($verificado == 0)
                            <td class="text-center align-middle allcheck">
                                <div id="checkbox-group" class="checkbox-group">
                                    <input class="mis-checkboxes" tu-attr-precio='{{ $total }}' type="checkbox"
                                        id="allcheck" name="allcheck[]" value="{{ $folioF }}" />
                                </div>
                            </td>
                        @endif
                        <td class="text-center align-middle">{{ $emisorRfc }}</td>
                        <td class="text-center align-middle">{{ $emisorNombre }}</td>
                        <td class="text-center align-middle">{{ $folioF }}</td>
                        <td class="text-center align-middle">{{ $fechaE }}</td>
                        @php
                            $anio = substr($fechaE, 0, 4);
                            $mes = (string) (int) substr($fechaE, 5, 2);
                            $rutaXml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/XML/$folioF.xml";
                            $rutaPdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/PDF/$folioF.pdf";
                            $nUR = 0;
                            $nCon = 0;
                            $totalX = 0;
                            $colX = XmlR::where(['UUID' => ''])->get();
                            if (!$colX->isEmpty()) {
                                foreach ($colX as $v) {
                                    // $concepto0 = $v['Conceptos.Concepto.0.Descripcion'];
                                    $concepto = $v['Conceptos.Concepto'];
                                    $metodoPago = $v['MetodoPago'];
                                    $folio = $v['Folio'];
                                    if ($efecto == 'Pago') {
                                        $docRel = $v['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
                                        $metodoPago = '-';
                                    }
                                }
                            } else {
                                $concepto = 'X';
                                $metodoPago = 'X';
                                $folio = 'X';
                                $uuidRef = 'X';
                            }

                        @endphp
                        <td class="text-center align-middle">
                            @if (!$colX->isEmpty())
                                @foreach ($concepto as $c)
                                    {{ ++$nCon }}. {{ $c['Descripcion'] }}<br>
                                @endforeach
                                {{-- {{ $concepto0 }} --}}
                            @else
                                {{ $concepto }}
                            @endif
                        </td>
                        <td class="text-center align-middle">{{ $folio }}</td>
                        <td class="text-center align-middle">{{ $metodoPago }}</td>
                        <td class="text-center align-middle">
                            @if (!$colX->isEmpty())
                                @if ($efecto == 'Pago')
                                    @foreach ($docRel as $d)
                                        {{ ++$nUR }}. {{ $d['IdDocumento'] }}<br>
                                    @endforeach
                                @else
                                    -
                                @endif
                            @else
                                {{ $uuidRef }}
                            @endif
                        </td>
                        <td class="text-center align-middle">{{ $efecto }}</td>
                        <td class="text-center align-middle">${{ number_format($total, 2) }}</td>
                        <td class="text-center align-middle">{{ $estado }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ $rutaXml }}" download="{{ $folioF }}.xml">
                                <i class="fas fa-file-download fa-2x"></i>
                            </a>
                            <a href="{{ $rutaPdf }}" target="_blank">
                                <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($verificado == 0)
            <div class="d-flex justify-content-center">
                <input readonly name="cheques_id" type="hidden" value="{{ $id }}" />
                <input readonly id="total" name="totalXml" type="hidden" value="0" />
                <input id="vinct" type="submit" value="Desvincular Cheque/Transferencia"
                    style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
            </div>
        @endif
    </form>
@endsection
