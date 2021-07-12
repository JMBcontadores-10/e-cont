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

        {{-- <div class="row justify-content-end">
            <div>
                <form action="/" method="get">
                    <button class="button2">Módulo: Cheques y Transferencias</button>
                </form>
            </div>
        </div> --}}

        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar proveedor">
        </div><br>

        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">N°</th>
                    <th class="text-center">Check <input type="checkbox" id="allcheck" name="allcheck" /></th>
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
                    @php
                        $folioF = $i->folioFiscal;
                        $fechaC = $i->fechaCertificacion;
                        $efecto = $i->efecto;
                        $total = $i->total;
                        $estado = $i->estado;
                        if ($efecto == 'Egreso') {
                            $total = -1 * abs($total);
                        }

                    @endphp
                    <tr>
                        <td class="text-center">{{ ++$n }}</td>
                        <td class="text-center allcheck"><input class="mis-checkboxes" tu-attr-precio='{{ $total }}'
                                type="checkbox" id="allcheck" name="allcheck" /></td>
                        <td class="text-center">{{ $folioF }}</td>
                        <td class="text-center">{{ $fechaC }}</td>
                        @php
                            $anio = substr($fechaC, 0, 4);
                            $mes = (string) (int) substr($fechaC, 5, 2);
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
                        <td class="text-center">{{ $metodoPago }}</td>
                        <td class="text-center">{{ $uuidRef }}</td>
                        <td class="text-center">{{ $folio }}</td>
                        <td class="text-center">{{ $efecto }}</td>
                        <td class="text-center">${{ number_format($total, 2) }}</td>
                        <td class="text-center">{{ $estado }}</td>
                        <td class="text-center">
                            <a class="btn btn-primary m-1" href="{{ $rutaXml }}"
                                download="{{ $folioF }}.xml">XML</a>
                            <a class="btn btn-danger m-1" href="{{ $rutaPdf }}" target="_blank">PDF</a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center text-bold">Total:</td>
                    <td> <input readonly id="total" type="text" placeholder=" $0.00" /> </td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
