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
                <form class="form-inline">
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
                <form action="/">
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

        <table class="table table-sm table-hover table-bordered">
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

            </tbody>
        </table>

    </div>
@endsection
