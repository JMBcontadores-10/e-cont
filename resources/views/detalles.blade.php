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
        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar proveedor">
        </div>
        <br>

    </div>
    <form action="{{ url('vincular-cheque') }}" method="POST">
        @csrf
        <div class="mx-4" style="overflow:auto">
            <table class="table table-sm table-hover table-bordered mx-3">
                <thead>
                    <tr class="table-primary">
                        <th class="text-center align-middle">N°</th>
                        <th class="text-center align-middle">Vincular CFDI</th>
                        {{-- @if ($emisorNombre == 'Varios Proveedores')
                            <th class="text-center align-middle">RFC Emisor</th>
                            <th class="text-center align-middle">Razón Social Emisor</th>
                        @endif --}}
                        <th class="text-center align-middle">UUID</th>
                        <th class="text-center align-middle">Fecha Emisión</th>
                        <th class="text-center align-middle">Concepto</th>
                        <th class="text-center align-middle">Metodo - Pago</th>
                        <th class="text-center align-middle">UUID - Referencial</th>
                        <th class="text-center align-middle">Folio</th>
                        <th class="text-center align-middle">Efecto</th>
                        <th class="text-center align-middle">Total</th>
                        <th class="text-center align-middle">Estado</th>
                        <th class="text-center align-middle">Descargar</th>
                    </tr>
                </thead>
                <tbody class="buscar">
                    @php
                        $varios = $emisorNombre;
                    @endphp
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
                            <td class="text-center align-middle allcheck">
                                @if ($estado != 'Cancelado')
                                    <div id="checkbox-group" class="checkbox-group">
                                        <input class="mis-checkboxes" tu-attr-precio='{{ $total }}' type="checkbox"
                                            name="allcheck[]" value="{{ $folioF }}" />
                                    </div>
                                @endif
                            </td>
                            {{-- @if ($varios == 'Varios Proveedores')
                                <td class="text-center align-middle">{{ $emisorRfc }}</td>
                                <td class="text-center align-middle">{{ $emisorNombre }}</td>
                            @endif --}}

                            <td class="text-center align-middle">{{ $folioF }}</td>
                            <td class="text-center align-middle">{{ $fechaE }}</td>
                            @php
                                $anio = substr($fechaE, 0, 4);
                                $mes = (string) (int) substr($fechaE, 5, 2);
                                // Se asignan las rutas donde está almacenado el archivo
                                $rutaXml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/XML/$folioF.xml";
                                $rutaPdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/PDF/$folioF.pdf";
                                $totalX = 0;
                                $nUR = 0;
                                $nCon = 0;
                                // Se consulta la coincidencia de metadata con el contenido xml para obtener los campos faltantes
                                $colX = XmlR::where(['UUID' => $folioF])->get();
                                if (!$colX->isEmpty()) {
                                    foreach ($colX as $v) {
                                        $concepto = $v['Conceptos.Concepto'];
                                        $metodoPago = $v['MetodoPago'];
                                        $folio = $v['Folio'];
                                        if ($efecto == 'Pago') {
                                            $docRel = $v['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
                                            $metodoPago = '-';
                                            if (!isset($docRel)) {
                                                $docRel = $v['Complemento.0.default:Pagos.default:Pago.default:DoctoRelacionado.IdDocumento'];
                                            }
                                        } elseif ($efecto == 'Egreso' or $efecto == 'Ingreso') {
                                            $docRel = $v['CfdiRelacionados.CfdiRelacionado'];
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
                                @else
                                    {{ $concepto }}
                                @endif
                            </td>
                            <td class="text-center align-middle">{{ $metodoPago }}</td>
                            <td class="text-center align-middle">
                                @if (!$colX->isEmpty())
                                    @if ($efecto == 'Pago')
                                        @if (is_array($docRel) || is_object($docRel))
                                            @foreach ($docRel as $d)
                                                {{ ++$nUR }}. {{ $d['IdDocumento'] }}<br>
                                            @endforeach
                                        @else
                                            {{ ++$nUR }}. {{ $docRel }}
                                        @endif
                                    @elseif ($efecto == 'Egreso' and !$docRel == null or $efecto == 'Ingreso' and !$docRel == null)
                                        @foreach ($docRel as $d)
                                            {{ ++$nUR }}. {{ $d['UUID'] }}<br>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                @else
                                    {{ $uuidRef }}
                                @endif
                            </td>
                            <td class="text-center align-middle">{{ $folio }}</td>
                            <td class="text-center align-middle">{{ $efecto }}</td>
                            <td class="text-center align-middle">${{ number_format($total, 2) }}</td>
                            <td class="text-center align-middle">{{ $estado }}</td>
                            <td class="text-center align-middle">
                                @if ($estado != 'Cancelado')
                                    <a href="{{ $rutaXml }}" download="{{ $folioF }}.xml">
                                        <i class="fas fa-file-download fa-2x"></i>
                                    </a>
                                    <a href="{{ $rutaPdf }}" target="_blank">
                                        <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                                    </a>
                                @endif
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
                        {{-- @if ($emisorNombre == 'Varios Proveedores')
                            <td></td>
                            <td></td>
                        @endif --}}
                        <td class="text-center text-bold align-middle">Total:</td>
                        <td> <input readonly id="total" name="totalXml" type="text" class="form-control" placeholder="$0.00"
                                value=0 /></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="ml-4 mt-3">
            {{ $colM->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
        </div>
        <div class="d-flex justify-content-center">
            <input id="vinct" type="submit" value="Vincular Cheque/Transferencia"
                style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
        </div>
    </form>

@endsection
