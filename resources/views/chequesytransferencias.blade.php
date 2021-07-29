@extends('layouts.app')

<head>
    <title>Cheques y Transferencias Contarapp</title>
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url('/') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Cheques y Transferencias</p>
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
            <div class="col-sm-7">
                <form class="form-inline" method="POST">
                    @csrf
                    <label class="text-bold" for="mes">Seleccione el periodo: </label>
                    <div class="form-group">
                        <select class="form-control m-2" id="mes" name="mes">
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="anio" name="anio">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary ml-2">Consultar</button>
                    </div>
                </form>
            </div>
            <div>
                <form action="{{ url('vincular-cheque') }}" method="POST">
                    @csrf
                    <button class="button2">Registrar Cheque/Transferencia</button>
                </form>
            </div>
            <div>
                <form action="{{ url('cuentasporpagar') }}">
                    <button class="button2 ml-3">Módulo: Cuentas por pagar</button>
                </form>
            </div>
        </div>

        {{-- <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar palabra clave">
            <a href="#bottom" class="btn btn-primary ml-2">Ir abajo</a>
        </div>
        <br> --}}

    </div>
    <table class="table table-sm table-hover table-bordered ml-3 mr-3">
        <thead>
            <tr class="table-primary">
                <th class="text-center">N°</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">#Cheque / #Transferencia</th>
                <th class="text-center">Beneficiario</th>
                <th class="text-center">Tipo de operación</th>
                <th class="text-center">Importe Total</th>
                <th class="text-center">Importe CFDI</th>
                <th class="text-center">Ajuste</th>
                <th class="text-center">Diferencia</th>
                <th class="text-center">Cheque / Transferencia PDF</th>
                <th class="text-center">Acciones</th>
                <th class="text-center">Verificación</th>
                <th class="text-center">Contabilizado</th>
                <th class="text-center">Borrar</th>
            </tr>
        </thead>
        <tbody class="buscar">
            @foreach ($colCheques as $i)
                @php
                    $editar = true;
                    $id = $i['_id'];
                    $tipo = $i['tipomov'];
                    $fecha = $i['fecha'];
                    $numCheque = $i['numcheque'];
                    $beneficiario = $i['Beneficiario'];
                    $tipoO = $i['tipoopera'];
                    $importeC = $i['importecheque'];
                    $sumaxml = $i['importexml'];
                    $ajuste = $i['ajuste'];
                    if ($tipoO == 'Impuestos' or $tipoO == 'Parcialidad') {
                        $diferencia = 0;
                    } else {
                        $diferencia = $importeC - abs($sumaxml);
                        $diferencia = $diferencia - $ajuste;
                        $diferencia = number_format($diferencia, 2);
                    }
                    if ($diferencia != 0) {
                        $diferenciaP = 0;
                    } else {
                        $diferenciaP = 1;
                    }
                    $verificado = $i['verificado'];
                    $faltaxml = $i['faltaxml'];
                    $contabilizado = $i['conta'];
                    $pendiente = $i['pendi'];
                    $nombreCheque = $i['nombrec'];
                    if ($nombreCheque == '0') {
                        $subirArchivo = true;
                        $nombreChequeP = 0;
                    } else {
                        $subirArchivo = false;
                        $nombreChequeP = 1;
                    }
                    $rutaArchivo = $rutaDescarga . $nombreCheque;
                @endphp
                <tr>
                    <td class="text-center">{{ ++$n }}</td>
                    <td class="text-center">{{ $fecha }}</td>
                    <td class="text-center">{{ $numCheque }}</td>
                    <td class="text-center">{{ $beneficiario }}</td>
                    <td class="text-center">{{ $tipoO }}</td>
                    <td class="text-center">${{ number_format($importeC, 2) }}</td>
                    <td class="text-center">${{ number_format($sumaxml, 2) }}</td>
                    <td class="text-center">
                        @if ($verificado == 0)
                            <form action="{{ url('cheques-transferencias') }}">
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="number" min="0" step="any" name="ajuste" class="mb-2" style="width: 65px">
                                <input type="submit" value="Enviar">
                            </form>
                        @else
                            <img src="{{ asset('img/veri.png') }}" alt="">
                        @endif
                    </td>
                    <td class="text-center">${{ $diferencia }}</td>
                    <td class="td1 text-center">
                        @if ($nombreCheque == '0')
                            <img src="{{ asset('img/ima2.png') }}" alt="">
                        @else
                            <a href="{{ $rutaArchivo }}" target="_blank">
                                <img src="{{ asset('img/ima.png') }}" alt="">
                            </a>
                        @endif
                    </td>
                    <td class="text-center">
                        <form action="{{ url('detallesCT') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="verificado" value="{{ $verificado }}">
                            <input type="submit" value="Ver">
                        </form>
                        @if ($verificado == 0)
                            <form action="{{ url('vincular-cheque') }}" method="POST">
                                @csrf
                                <input type="hidden" name="editar" value="{{ $editar }}">
                                <input type="hidden" id="id" name="id" value="{{ $id }}">
                                <input type="hidden" name="tipo" value="{{ $tipo }}">
                                <input type="hidden" name="numCheque" value="{{ $numCheque }}">
                                <input type="hidden" name="fechaCheque" value="{{ $fecha }}">
                                <input type="hidden" name="importeCheque" value="{{ $importeC }}">
                                <input type="hidden" name="importeT" value="{{ $sumaxml }}">
                                <input type="hidden" name="beneficiario" value="{{ $beneficiario }}">
                                <input type="hidden" name="tipoOperacion" value="{{ $tipoO }}">
                                <input type="hidden" name="subirArchivo" value="{{ $subirArchivo }}">
                                <input type="hidden" name="nombrec" value="{{ $nombreCheque }}">
                                <input type="submit" value="Editar">
                            </form>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($diferencia != 0 or $faltaxml == 0 or $nombreCheque == '0')
                            <img src="{{ asset('img/warning.png') }}" alt="" class="mb-2">
                            <input type="submit" name="Pendientes" value="Pendientes"
                                onclick="alertaP({{ $diferenciaP }},{{ $faltaxml }}, {{ $nombreChequeP }})">
                        @elseif ($verificado == 0)
                            <form action="{{ url('cheques-transferencias') }}" method="post">
                                @csrf
                                <input type="hidden" id="id" name="id" value="{{ $id }}">
                                <input type="checkbox" name="revisado" required class="mb-2"> Revisado
                                <br>
                                <input type="submit" name="Aceptar" value="Aceptar">
                            </form>
                        @else
                            <img src="{{ asset('img/veri.png') }}" alt="">
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($verificado == 1 and $contabilizado == 0)
                            <form action="{{ url('cheques-transferencias') }}" method="POSt">
                                @csrf
                                <input type="hidden" id="id" name="id" value="{{ $id }}">
                                <input type="checkbox" name="conta" required class="mb-2"> Contabilizado
                                <input type="submit" name="Aceptar" value="Aceptar">
                            </form>
                        @elseif ($verificado == 1 and $contabilizado == 1)
                            <img src="{{ asset('img/conta3.png') }}" alt="">
                        @elseif ($verificado == 0 and $contabilizado == 0)
                            <img src="{{ asset('img/espera.png') }}" alt="">
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($verificado == 0)
                            <form action="{{ url('delete-cheque') }}" method="POST">
                                @csrf
                                <input type="hidden" id="id" name="id" value="{{ $id }}">
                                <input type="hidden" name="rutaArchivo" value="{{ $rutaArchivo }}">
                                <input onclick="return confirm('¿Seguro que deseas eliminar el cheque/transferencia?')"
                                    type="submit" value="Borrar">
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
