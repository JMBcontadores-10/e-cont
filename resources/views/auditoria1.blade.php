@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="container">
            <div class="float-md-left">
                <a class="b3" href="javascript:javascript:history.go(-1)">
                    << Regresar</a>
            </div>
            <div class="float-md-right">
                <p class="label2">Auditoría</p>
            </div>
            <br>
            <hr style="border-color:black; width:100%;">
            <div class="justify-content-start">
                <label class="label1" style="font-weight: bold"> Sesión de: </label>
                <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
                <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
                <hr style="border-color:black; width:100%;">
            </div>
        </div>
        @php
            $rfc = Auth::user()->RFC;
        @endphp

        <h1>Facturas {{ $tipoer }} </h1>
        <h1>Periodo del {{ $fecha1er }} al {{ $fecha2er }}</h1>

        <div class="row">
            <div class="col-6">
                <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th>N°</th>
                            <th scope="col">Estado SAT</th>
                            <th scope="col">UUID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colAu as $a)
                            <tr>
                                <td>{{ ++$n }}</td>
                                <td>{{ $a['estado'] }}</td>
                                <td>{{ $a['folioFiscal'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th>N°</th>
                            <th scope="col">Estado SAT</th>
                            <th scope="col">UUID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($metadata as $b)
                            @php
                                if ($b->estatus == '1') {
                                    $b->estatus = 'Vigente';
                                } else {
                                    $b->estatus = 'Cancelado';
                                }
                            @endphp
                            <tr>
                                <td>{{ ++$m }}</td>
                                <td>{{ $b->estatus }}</td>
                                <td>{{ $b->uuid }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    @endsection
