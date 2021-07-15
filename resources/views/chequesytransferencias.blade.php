@extends('layouts.app')

@php
use App\Models\MetadataR;
use App\Models\ListaNegra;
@endphp

<head>
    <title>Cheques y Transferencias Contarapp</title>
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url()->previous() }}">
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
                <form action="{{ url('vincular-cheque') }}">
                    <button class="button2">Registrar Cheque/Transferencia</button>
                </form>
            </div>
            <div>
                <form action="{{ url('cuentasporpagar') }}">
                    <button class="button2 ml-3">Módulo: Cuentas por pagar</button>
                </form>
            </div>
        </div>

        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar palabra clave">
            {{-- <a href="#bottom" class="btn btn-primary ml-2">Ir abajo</a> --}}
        </div><br>
    </div>
    <table class="table table-sm table-hover table-bordered ml-3 mr-3">
        <thead>
            <tr>
                <th class="text-center">N°</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">#Cheque/#Transferencia</th>
                <th class="text-center">Beneficiario</th>
                <th class="text-center">Tipo de operación</th>
                <th class="text-center">Importe Total</th>
                <th class="text-center">Importe CFDI</th>
                <th class="text-center">Diferencia</th>
                <th class="text-center">Cheque/Transferencia PDF</th>
                <th class="text-center">Acciones</th>
                <th class="text-center">Verificación</th>
                <th class="text-center">Contabilizado</th>
                <th class="text-center">Borrar</th>
            </tr>
        </thead>
        <tbody class="buscar">
            @foreach ($colCheques as $i)
                @php
                    $id = $i['_id'];
                    $fecha = $i['fecha'];
                    $numCheque = $i['numcheque'];
                    $beneficiario = $i['Beneficiario'];
                    $tipoO = $i['tipoopera'];
                    $importeC = $i['importecheque'];
                    $sumaxml = $i['importexml'];
                    if ($tipoO == 'Impuestos' or $tipoO == 'Parcialidad') {
                        $diferencia = 0;
                    } else {
                        $diferencia = $importeC - $sumaxml;
                    }
                    $nombreCheque = $i['nombrec'];
                    $contabilizado = $i['conta'];
                @endphp
                <tr>
                    <td class="text-center">{{ ++$n }}</td>
                    <td class="text-center">{{ $fecha }}</td>
                    <td class="text-center">{{ $numCheque }}</td>
                    <td class="text-center">{{ $beneficiario }}</td>
                    <td class="text-center">{{ $tipoO }}</td>
                    <td class="text-center">${{ number_format($importeC, 2) }}</td>
                    <td class="text-center">${{ number_format($sumaxml, 2) }}</td>
                    <td class="text-center">${{ number_format($diferencia, 2) }}</td>
                    @if ($nombreCheque == '0')
                        <td class="td1 text-center"><img src="{{ asset('img/ima2.png') }}" alt=""></td>
                    @else
                        <td class="td1 text-center"><img src="{{ asset('img/ima.png') }}" alt=""></td>
                    @endif
                    <td class="text-center">
                        <form action="{{ url('/') }}">
                            @csrf
                            <input type="submit" value="Ver">
                        </form>
                        <form action="{{ url('/') }}">
                            @csrf
                            <input type="submit" value="Editar">
                        </form>
                    </td>
                    <td class="text-center">
                        <form action="{{ url('/') }}">
                            @csrf
                            <input type="submit" value="Pendientes">
                        </form>
                    </td>
                    @if ($contabilizado == 0)
                        <td class="text-center">
                            <form action="{{ url('/') }}">
                                @csrf
                                <input type="checkbox" name="conta" value=2> Contabilizado
                                <input type="submit" name="Aceptar2" value="Aceptar">
                            </form>
                        </td>
                    @else
                        <td class="text-center"></td>
                    @endif
                    <td class="text-center">
                        <form action="{{ url('delete-cheque') }}" method="POST">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                            <input onclick="return confirm('¿Seguro que deseas eliminar el cheque?')" type="submit"
                                value="Borrar">
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
